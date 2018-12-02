<?php

declare(strict_types=1);

namespace sonrac\Auth\Security;

use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\AuthorizationValidators\AuthorizationValidatorInterface;
use League\OAuth2\Server\Exception\OAuthServerException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Sonrac\OAuth2\Security\Factory\OAuthTokenFactory;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;
use Symfony\Component\Security\Http\HttpUtils;

/**
 * Class OAuthAuthenticationListener
 * @package sonrac\Auth\Security
 */
class OAuthAuthenticationListener implements ListenerInterface
{
    /**
     * @var \League\OAuth2\Server\AuthorizationServer
     */
    private $authorizationServer;

    /**
     * @var \League\OAuth2\Server\AuthorizationValidators\AuthorizationValidatorInterface
     */
    private $authorizationValidator;

    /**
     * @var \Sonrac\OAuth2\Security\Factory\OAuthTokenFactory
     */
    private $oauthTokenFactory;

    /**
     * @var \Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface
     */
    private $authenticationManager;

    /**
     * @var \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var \Symfony\Component\Security\Http\HttpUtils
     */
    private $httpUtils;

    /**
     * @var \Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory
     */
    private $diactorosFactory;

    /**
     * @var \Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory
     */
    private $httpFoundationFactory;

    /**
     * @var string
     */
    private $authorizationPath;

    /**
     * @var string
     */
    private $tokenPath;

    /**
     * @var string
     */
    private $providerKey;

    /**
     * OAuthAuthenticationListener constructor.
     * @param \League\OAuth2\Server\AuthorizationServer $authorizationServer
     * @param \League\OAuth2\Server\AuthorizationValidators\AuthorizationValidatorInterface $authorizationValidator
     * @param \Sonrac\OAuth2\Security\Factory\OAuthTokenFactory $oauthTokenFactory
     * @param \Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface $authenticationManager
     * @param \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface $tokenStorage
     * @param \Symfony\Component\Security\Http\HttpUtils $httpUtils
     * @param \Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory $diactorosFactory
     * @param \Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory $httpFoundationFactory
     * @param string $authorizationPath
     * @param string $tokenPath
     * @param string $providerKey
     */
    public function __construct(
        AuthorizationServer $authorizationServer,
        AuthorizationValidatorInterface $authorizationValidator,
        OAuthTokenFactory $oauthTokenFactory,
        AuthenticationManagerInterface $authenticationManager,
        TokenStorageInterface $tokenStorage,
        HttpUtils $httpUtils,
        DiactorosFactory $diactorosFactory,
        HttpFoundationFactory $httpFoundationFactory,
        string $authorizationPath,
        string $tokenPath,
        string $providerKey
    ) {
        $this->authorizationServer = $authorizationServer;
        $this->authorizationValidator = $authorizationValidator;
        $this->oauthTokenFactory = $oauthTokenFactory;
        $this->authenticationManager = $authenticationManager;
        $this->tokenStorage = $tokenStorage;
        $this->httpUtils = $httpUtils;
        $this->diactorosFactory = $diactorosFactory;
        $this->httpFoundationFactory = $httpFoundationFactory;
        $this->authorizationPath = $authorizationPath;
        $this->tokenPath = $tokenPath;
        $this->providerKey = $providerKey;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(GetResponseEvent $event)
    {
        $psrResponse = $this->respondToAuthorizationRequest($event->getRequest());

        if (null !== $psrResponse) {
            $event->setResponse($this->httpFoundationFactory->createResponse($psrResponse));

            return;
        }

        $psrResponse = $this->respondToTokenRequest($event->getRequest());

        if (null !== $psrResponse) {
            $event->setResponse($this->httpFoundationFactory->createResponse($psrResponse));

            return;
        }

        try {
            $psrRequest = $this->authorizationValidator->validateAuthorization(
                $this->diactorosFactory->createRequest($event->getRequest())
            );
        } catch (OAuthServerException $exception) {
            $psrResponse = $exception->generateHttpResponse($this->createPsrResponse());
        } catch (\Exception $exception) {
            $psrResponse = $this->createOAuthServerException($exception)->generateHttpResponse(
                $this->createPsrResponse()
            );
        }

        if (null !== $psrResponse) {
            $event->setResponse($this->httpFoundationFactory->createResponse($psrResponse));

            return;
        }

        $token = $this->oauthTokenFactory->createFromRequest($psrRequest, $this->providerKey);

        try {
            $authenticatedToken = $this->authenticationManager->authenticate($token);

            $this->tokenStorage->setToken($authenticatedToken);
        } catch (AuthenticationException $exception) {
            //TODO: add exception handling.
            return;
        }

        //TODO: add invalid authentication error
        return;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool
     */
    protected function isAuthorizationRequested(Request $request): bool
    {
        return $this->httpUtils->checkRequestPath($request, $this->authorizationPath)
            && $request->isMethod('POST');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $sfRequest
     *
     * @return \Psr\Http\Message\ResponseInterface|null
     */
    private function respondToAuthorizationRequest(Request $sfRequest): ?ResponseInterface
    {
        if (false || false === $this->isAuthorizationRequested($sfRequest)) {
            return null;
        }

        $request = $this->diactorosFactory->createRequest($sfRequest);
        $response = $this->createPsrResponse();

        try {
            $authRequest = $this->authorizationServer->validateAuthorizationRequest($request);

            //TODO: implement save auth request state

            $response = $this->authorizationServer->completeAuthorizationRequest($authRequest, $response);
        } catch (OAuthServerException $exception) {
            $response = $exception->generateHttpResponse($response);
        } catch (\Exception $exception) {
            $response = $this->createOAuthServerException($exception)->generateHttpResponse($response);
        }

        return $response;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return bool
     */
    protected function isTokenRequested(Request $request): bool
    {
        return $this->httpUtils->checkRequestPath($request, $this->tokenPath)
            && $request->isMethod('POST');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $sfRequest
     *
     * @return \Psr\Http\Message\ResponseInterface|null
     */
    private function respondToTokenRequest(Request $sfRequest): ?ResponseInterface
    {
        if (false === $this->isTokenRequested($sfRequest)) {
            return null;
        }

        $request = $this->diactorosFactory->createRequest($sfRequest);
        $response = $this->createPsrResponse();

        try {
            $response = $this->authorizationServer->respondToAccessTokenRequest($request, $response);
        } catch (OAuthServerException $exception) {
            $response = $exception->generateHttpResponse($response);
        } catch (\Exception $exception) {
            $response = $this->createOAuthServerException($exception)->generateHttpResponse($response);
        }

        return $response;
    }

    /**
     * @return \Psr\Http\Message\ResponseInterface
     */
    private function createPsrResponse(): ResponseInterface
    {
        return $this->diactorosFactory->createResponse(new Response());
    }

    /**
     * @param \Exception $exception
     *
     * @return \League\OAuth2\Server\Exception\OAuthServerException
     */
    private function createOAuthServerException(\Exception $exception): OAuthServerException
    {
        return new OAuthServerException($exception->getMessage(), 0, 'unknown_error', 500);
    }
}
