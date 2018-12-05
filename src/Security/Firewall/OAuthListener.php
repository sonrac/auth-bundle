<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12/4/18
 * Time: 11:49 PM
 */

declare(strict_types=1);

namespace Sonrac\OAuth2\Security\Firewall;

use Psr\Log\LoggerInterface;
use Sonrac\OAuth2\Security\Handler\OAuthHandlerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;

/**
 * Class OAuthListener
 * @package Sonrac\OAuth2\Security\Firewall
 */
class OAuthListener implements ListenerInterface
{
    /**
     * @var \IteratorAggregate
     */
    private $handlers;

    /**
     * @var \Psr\Log\LoggerInterface|null
     */
    private $logger;

    /**
     * OAuthListener constructor.
     * @param \IteratorAggregate $handlers
     * @param \Psr\Log\LoggerInterface|null $logger
     */
    public function __construct(
        \IteratorAggregate $handlers,
        ?LoggerInterface $logger = null
    ) {
        $this->handlers = $handlers;
        $this->logger = $logger;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(GetResponseEvent $event)
    {
        $response = null;

        foreach ($this->handlers as $handler) {
            /** @var \Sonrac\OAuth2\Security\Handler\OAuthHandlerInterface $handler */
            if (false === $handler->requires($event->getRequest())) {
                continue;
            }

            $response = $this->runHandler($event->getRequest(), $handler);

            break;
        }

        if (null !== $response) {
            $event->setResponse($response);
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param \Sonrac\OAuth2\Security\Handler\OAuthHandlerInterface $handler
     *
     * @return \Symfony\Component\HttpFoundation\Response|null
     */
    private function runHandler(Request $request, OAuthHandlerInterface $handler): ?Response
    {
        try {
            $response = $handler->handle($request);
        } catch (AuthenticationException $exception) {
            //TODO: add handling for AuthenticationException
            return null;
        } catch (\Exception $exception) {
            if (null !== $this->logger) {
                $this->logger->critical($exception->getMessage(), ['context' => $exception]);
            }

            // add conversion to http 500 error
            return null;
        }

        return $response;
    }
}
