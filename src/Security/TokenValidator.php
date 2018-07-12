<?php

declare(strict_types=1);

namespace sonrac\Auth\Security;

use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Signer\Rsa\Sha256;
use Lcobucci\JWT\ValidationData;
use League\OAuth2\Server\AuthorizationValidators\AuthorizationValidatorInterface;
use League\OAuth2\Server\CryptKey;
use League\OAuth2\Server\CryptTrait;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class TokenValidator
 */
class TokenValidator implements AuthorizationValidatorInterface
{
    use CryptTrait;

    /**
     * @var \League\OAuth2\Server\CryptKey
     */
    protected $publicKey;
    /**
     * Token header name.
     *
     * @var string $tokenName
     */
    protected $tokenName;
    /**
     * @var \League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface
     */
    private $accessTokenRepository;

    /**
     * TokenValidator constructor.
     *
     * @param \League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface $accessTokenRepository
     * @param string                                                            $tokenName
     */
    public function __construct(
        AccessTokenRepositoryInterface $accessTokenRepository,
        string $tokenName
    ) {
        $this->accessTokenRepository = $accessTokenRepository;
        $this->tokenName = $tokenName;
    }

    /**
     * Set the public key
     *
     * @param \League\OAuth2\Server\CryptKey $key
     */
    public function setPublicKey(CryptKey $key): void
    {
        $this->publicKey = $key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthorization(ServerRequestInterface $request)
    {
        if ($request->hasHeader('authorization') === false) {
            throw OAuthServerException::accessDenied('Missing "Authorization" header');
        }

        $header = $request->getHeader($this->tokenName);
        $jwt = trim(preg_replace('/^(?:\s+)?Bearer\s/', '', $header[0]));

        try {
            // Attempt to parse and validate the JWT
            $token = (new Parser())->parse($jwt);
            try {
                if ($token->verify(new Sha256(), $this->publicKey->getKeyPath()) === false) {
                    throw OAuthServerException::accessDenied('Access token could not be verified');
                }
            } catch (\BadMethodCallException $exception) {
                throw OAuthServerException::accessDenied('Access token is not signed');
            }

            // Ensure access token hasn't expired
            $data = new ValidationData();
            $data->setCurrentTime(time());

            if ($token->validate($data) === false) {
                throw OAuthServerException::accessDenied('Access token is invalid');
            }

            // Check if token has been revoked
            if ($this->accessTokenRepository->isAccessTokenRevoked($token->getClaim('jti'))) {
                throw OAuthServerException::accessDenied('Access token has been revoked');
            }

            // Return the request with additional attributes
            return $request
                ->withAttribute('oauth_access_token_id', $token->getClaim('jti'))
                ->withAttribute('oauth_client_id', $token->getClaim('aud'))
                ->withAttribute('oauth_user_id', $token->getClaim('sub'))
                ->withAttribute('oauth_scopes', $token->getClaim('scopes'));
        } catch (\InvalidArgumentException $exception) {
            // JWT couldn't be parsed so return the request as is
            throw OAuthServerException::accessDenied($exception->getMessage());
        } catch (\RuntimeException $exception) {
            //JWR couldn't be parsed so return the request as is
            throw OAuthServerException::accessDenied('Error while decoding to JSON');
        }
    }
}
