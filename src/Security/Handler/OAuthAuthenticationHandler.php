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
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Sonrac\OAuth2\Security\Config\OAuthPathConfig;
use Sonrac\OAuth2\Security\Token\AbstractPreAuthenticationToken;
use Sonrac\OAuth2\Security\Token\PreAuthenticationClientToken;
use Sonrac\OAuth2\Security\Token\PreAuthenticationToken;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class OAuthAuthenticationHandler
 * @package Sonrac\OAuth2\Security\Handler
 */
class OAuthAuthenticationHandler extends AbstractOAuthPsrHandler
{
    /**
     * @var \League\OAuth2\Server\AuthorizationValidators\AuthorizationValidatorInterface
     */
    private $authorizationValidator;

    /**
     * @var \Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface
     */
    private $authenticationManager;

    /**
     * @var \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var \Sonrac\OAuth2\Security\Config\OAuthPathConfig
     */
    private $pathConfig;

    /**
     * @var string
     */
    private $providerKey;

    /**
     * @var array|null
     */
    private $defaultScopes;

    /**
     * OAuthAuthenticationHandler constructor.
     * @param \League\OAuth2\Server\AuthorizationValidators\AuthorizationValidatorInterface $authorizationValidator
     * @param \Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface $authenticationManager
     * @param \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface $tokenStorage
     * @param \Sonrac\OAuth2\Security\Config\OAuthPathConfig $pathConfig
     * @param string $providerKey
     * @param \Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory $diactorosFactory
     * @param \Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory $httpFoundationFactory
     */
    public function __construct(
        AuthorizationValidatorInterface $authorizationValidator,
        AuthenticationManagerInterface $authenticationManager,
        TokenStorageInterface $tokenStorage,
        OAuthPathConfig $pathConfig,
        string $providerKey,
        DiactorosFactory $diactorosFactory,
        HttpFoundationFactory $httpFoundationFactory
    ) {
        parent::__construct($diactorosFactory, $httpFoundationFactory);

        $this->authorizationValidator = $authorizationValidator;
        $this->authenticationManager = $authenticationManager;
        $this->tokenStorage = $tokenStorage;
        $this->pathConfig = $pathConfig;
        $this->providerKey = $providerKey;
    }

    /**
     * @param array $defaultScopes
     *
     * @return void
     */
    public function setDefaultScopes(array $defaultScopes): void
    {
        $this->defaultScopes = $defaultScopes;
    }

    /**
     * {@inheritdoc}
     */
    public function requires(Request $request): bool
    {
        return false === $this->pathConfig->isAuthorizationPath($request)
            && false === $this->pathConfig->isIssueTokenPath($request);
    }

    /**
     * {@inheritdoc}
     */
    protected function psrHandle(ServerRequestInterface $request, ResponseInterface $response): ?ResponseInterface
    {
        $request = $this->authorizationValidator->validateAuthorization($request);

        $token = $this->createTokenFromRequest($request);

        $authenticatedToken = $this->authenticationManager->authenticate($token);

        $this->tokenStorage->setToken($authenticatedToken);

        return null;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return \Sonrac\OAuth2\Security\Token\AbstractPreAuthenticationToken
     */
    private function createTokenFromRequest(ServerRequestInterface $request): AbstractPreAuthenticationToken
    {
        $clientId = $request->getAttribute('oauth_client_id');
        $userId = $request->getAttribute('oauth_user_id');

        //TODO: check to add default scopes.
        $scopes = $request->getAttribute('oauth_scopes');
        $scopes = false === is_array($scopes)
            ? (null !== $this->defaultScopes ? $this->defaultScopes : [])
            : $scopes;

        if (null !== $userId && '' !== $userId) {
            $token = new PreAuthenticationToken($userId, $clientId, $this->providerKey, '', $scopes);
        } else {
            $token = new PreAuthenticationClientToken($clientId, $this->providerKey, '', $scopes);
        }

        $token->setAttribute('access_token_id', $request->getAttribute('oauth_access_token_id'));

        return $token;
    }
}
