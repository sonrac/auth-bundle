<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12/4/18
 * Time: 11:42 PM
 */

declare(strict_types=1);

namespace Sonrac\OAuth2\Security\Handler;

use League\OAuth2\Server\AuthorizationValidators\AuthorizationValidatorInterface;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Sonrac\OAuth2\Bridge\Util\OAuthHandler;
use Sonrac\OAuth2\Security\Token\AbstractPreAuthenticationToken;
use Sonrac\OAuth2\Security\Token\PreAuthenticationClientToken;
use Sonrac\OAuth2\Security\Token\PreAuthenticationToken;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class OAuthAuthenticationHandler
 * @package Sonrac\OAuth2\Security\Handler
 */
class OAuthAuthenticationHandler
{
    /**
     * @var \League\OAuth2\Server\AuthorizationValidators\AuthorizationValidatorInterface
     */
    private $authorizationValidator;

    /**
     * @var \League\OAuth2\Server\Repositories\ClientRepositoryInterface
     */
    private $clientRepository;

    /**
     * @var \Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface
     */
    private $authenticationManager;

    /**
     * @var \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var string
     */
    private $providerKey;

    /**
     * @var \Sonrac\OAuth2\Bridge\Util\OAuthHandler
     */
    private $OAuthHandler;

    /**
     * OAuthAuthenticationHandler constructor.
     * @param \League\OAuth2\Server\AuthorizationValidators\AuthorizationValidatorInterface $authorizationValidator
     * @param \League\OAuth2\Server\Repositories\ClientRepositoryInterface $clientRepository
     * @param \Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface $authenticationManager
     * @param \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface $tokenStorage
     * @param \Sonrac\OAuth2\Bridge\Util\OAuthHandler $OAuthHandler
     * @param string $providerKey
     */
    public function __construct(
        AuthorizationValidatorInterface $authorizationValidator,
        ClientRepositoryInterface $clientRepository,
        AuthenticationManagerInterface $authenticationManager,
        TokenStorageInterface $tokenStorage,
        OAuthHandler $OAuthHandler,
        string $providerKey
    ) {
        $this->authorizationValidator = $authorizationValidator;
        $this->clientRepository = $clientRepository;
        $this->authenticationManager = $authenticationManager;
        $this->tokenStorage = $tokenStorage;
        $this->providerKey = $providerKey;
        $this->OAuthHandler = $OAuthHandler;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response|null
     */
    public function attemptAuthentication(Request $request): ?Response
    {
        return $this->OAuthHandler->handle(function (ServerRequestInterface $request, ResponseInterface $response) {
            $request = $this->authorizationValidator->validateAuthorization($request);

            $token = $this->createTokenFromRequest($request);

            $authenticatedToken = $this->authenticationManager->authenticate($token);

            $this->tokenStorage->setToken($authenticatedToken);

            return null;
        }, $request);
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return \Sonrac\OAuth2\Security\Token\AbstractPreAuthenticationToken
     */
    private function createTokenFromRequest(ServerRequestInterface $request): AbstractPreAuthenticationToken
    {
        $clientId = $request->getAttribute('oauth_client_id');

        $client = $this->clientRepository->getClientEntity(
            $clientId, null, null, false
        );

        $userId = $request->getAttribute('oauth_user_id');

        $scopes = $request->getAttribute('oauth_scopes');
        $scopes = \is_array($scopes) ? $scopes : [$scopes];

        if (null !== $userId && '' !== $userId) {
            $token = new PreAuthenticationToken($userId, $client, $this->providerKey, '', $scopes);
        } else {
            $token = new PreAuthenticationClientToken($client, $this->providerKey, '', $scopes);
        }

        $token->setAttribute('access_token_id', $request->getAttribute('oauth_access_token_id'));

        return $token;
    }
}
