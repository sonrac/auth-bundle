<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12/4/18
 * Time: 11:36 PM.
 */

declare(strict_types=1);

namespace Sonrac\OAuth2\Security\Handler;

use League\OAuth2\Server\AuthorizationServer;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Sonrac\OAuth2\Bridge\Util\OAuthHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class OAuthIssueTokenHandler.
 */
class OAuthIssueTokenHandler
{
    /**
     * @var \League\OAuth2\Server\AuthorizationServer
     */
    private $authorizationServer;

    /**
     * @var \Sonrac\OAuth2\Bridge\Util\OAuthHandler
     */
    private $OAuthHandler;

    /**
     * OAuthIssueTokenHandler constructor.
     *
     * @param \League\OAuth2\Server\AuthorizationServer $authorizationServer
     * @param \Sonrac\OAuth2\Bridge\Util\OAuthHandler   $OAuthHandler
     */
    public function __construct(AuthorizationServer $authorizationServer, OAuthHandler $OAuthHandler)
    {
        $this->authorizationServer = $authorizationServer;
        $this->OAuthHandler        = $OAuthHandler;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function attemptTokenIssue(Request $request): Response
    {
        return $this->OAuthHandler->handle(function (ServerRequestInterface $request, ResponseInterface $response) {
            $response = $this->authorizationServer->respondToAccessTokenRequest($request, $response);

            return $response;
        }, $request);
    }
}
