<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12/4/18
 * Time: 11:16 PM
 */

declare(strict_types=1);

namespace Sonrac\OAuth2\Security\Handler;

use League\OAuth2\Server\AuthorizationServer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;

/**
 * Class OAuthAuthorizationHandler
 * @package Sonrac\OAuth2\Security\Handler
 */
class OAuthAuthorizationHandler extends AbstractOAuthPsrHandler
{
    /**
     * @var \League\OAuth2\Server\AuthorizationServer
     */
    private $authorizationServer;

    /**
     * OAuthAuthorizationHandler constructor.
     * @param \League\OAuth2\Server\AuthorizationServer $authorizationServer
     * @param \Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory $diactorosFactory
     * @param \Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory $httpFoundationFactory
     */
    public function __construct(
        AuthorizationServer $authorizationServer,
        DiactorosFactory $diactorosFactory,
        HttpFoundationFactory $httpFoundationFactory
    ) {
        parent::__construct($diactorosFactory, $httpFoundationFactory);

        $this->authorizationServer = $authorizationServer;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \League\OAuth2\Server\Exception\OAuthServerException
     */
    protected function psrHandle(ServerRequestInterface $request, ResponseInterface $response): ?ResponseInterface
    {
        $authRequest = $this->authorizationServer->validateAuthorizationRequest($request);

        //TODO: add csrf token data validation.
        //TODO: implement save auth request state

        $response = $this->authorizationServer->completeAuthorizationRequest($authRequest, $response);

        return $response;
    }
}
