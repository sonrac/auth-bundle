<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12/18/18
 * Time: 9:59 PM.
 */

declare(strict_types=1);

namespace Sonrac\OAuth2\Bridge\Util;

use Closure;
use Exception;
use League\OAuth2\Server\Exception\OAuthServerException;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory;
use Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

/**
 * Class OAuthHandler.
 */
class OAuthHandler
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
     * @var bool
     */
    private $debug;

    /**
     * @var \Psr\Log\LoggerInterface|null
     */
    private $logger;

    /**
     * OAuthHandler constructor.
     *
     * @param \Symfony\Bridge\PsrHttpMessage\Factory\DiactorosFactory      $diactorosFactory
     * @param \Symfony\Bridge\PsrHttpMessage\Factory\HttpFoundationFactory $httpFoundationFactory
     * @param bool                                                         $debug
     */
    public function __construct(
        DiactorosFactory $diactorosFactory,
        HttpFoundationFactory $httpFoundationFactory,
        bool $debug = false
    ) {
        $this->diactorosFactory      = $diactorosFactory;
        $this->httpFoundationFactory = $httpFoundationFactory;
        $this->debug                 = $debug;
    }

    /**
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function setLogger(LoggerInterface $logger): void
    {
        $this->logger = $logger;
    }

    /**
     * @param \Closure                                        $handler
     * @param \Symfony\Component\HttpFoundation\Request       $request
     * @param \Symfony\Component\HttpFoundation\Response|null $response
     *
     * @return \Symfony\Component\HttpFoundation\Response|null
     */
    public function handle(Closure $handler, Request $request, ?Response $response = null): ?Response
    {
        $psrRequest  = $this->diactorosFactory->createRequest($request);
        $psrResponse = $this->diactorosFactory->createResponse($response ?? new Response());

        try {
            $psrResponse = $handler->__invoke($psrRequest, $psrResponse);
        } catch (OAuthServerException $exception) {
            $psrResponse = $exception->generateHttpResponse($psrResponse);
        } catch (Exception $exception) {
            if (null !== $this->logger) {
                $this->logger->critical($exception->getMessage(), ['exception' => $exception]);
            }

            $psrResponse = $this->createOAuthException($exception)->generateHttpResponse($psrResponse);
        } catch (Throwable $exception) {
            if (null !== $this->logger) {
                $this->logger->critical($exception->getMessage(), ['exception' => $exception]);
            }

            $psrResponse = $this->createOAuthException($exception)->generateHttpResponse($psrResponse);
        }

        return null !== $psrResponse ? $this->httpFoundationFactory->createResponse($psrResponse) : null;
    }

    /**
     * @param \Throwable $exception
     *
     * @return \League\OAuth2\Server\Exception\OAuthServerException
     */
    private function createOAuthException(\Throwable $exception): OAuthServerException
    {
        return OAuthServerException::serverError($this->debug ? $exception->getMessage() : 'Error.');
    }
}
