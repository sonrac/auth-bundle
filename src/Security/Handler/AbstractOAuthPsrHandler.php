<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12/5/18
 * Time: 10:05 PM
 */

declare(strict_types=1);

namespace Sonrac\OAuth2\Security\Handler;

use League\OAuth2\Server\Exception\OAuthServerException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class AbstractOAuthPsrHandler
 * @package Sonrac\OAuth2\Security\Handler
 */
abstract class AbstractOAuthPsrHandler implements OAuthHandlerInterface
{
    /**
     * @var \Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory
     */
    private $diactorosFactory;

    /**
     * @var \Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory
     */
    private $httpFoundationFactory;

    /**
     * AbstractOAuthPsrHandler constructor.
     * @param \Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory $diactorosFactory
     * @param \Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory $httpFoundationFactory
     */
    public function __construct(
        DiactorosFactory $diactorosFactory,
        HttpFoundationFactory $httpFoundationFactory
    ) {
        $this->diactorosFactory = $diactorosFactory;
        $this->httpFoundationFactory = $httpFoundationFactory;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(Request $request): ?Response
    {
        $psrRequest = $this->diactorosFactory->createRequest($request);
        $psrResponse = $this->diactorosFactory->createResponse(new Response());

        try {
            $psrResponse = $this->psrHandle($psrRequest, $psrResponse);
        } catch (OAuthServerException $exception) {
            $psrResponse = $exception->generateHttpResponse($psrResponse);
        }

        return null !== $psrResponse ? $this->httpFoundationFactory->createResponse($psrResponse) : null;
    }

    /**
     * @param \Psr\Http\Message\ServerRequestInterface $request
     * @param \Psr\Http\Message\ResponseInterface $response
     *
     * @return \Psr\Http\Message\ResponseInterface|null
     */
    abstract protected function psrHandle(ServerRequestInterface $request, ResponseInterface $response): ?ResponseInterface;
}
