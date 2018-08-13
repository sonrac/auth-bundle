<?php

declare(strict_types=1);

namespace sonrac\Auth\Security;

use Psr\Container\ContainerInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;

/**
 * Class OAuthAuthenticationListener
 */
class OAuthAuthenticationListener implements ListenerInterface
{
    /**
     * @var \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @var \Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface
     */
    private $authenticationManager;

    /**
     * Uniquely identifies the secured area
     *
     * @var string
     */
    private $providerKey;

    /**
     * Container.
     *
     * @var \Psr\Container\ContainerInterface
     */
    private $container;

    public function __construct(
        TokenStorageInterface $tokenStorage,
        AuthenticationManagerInterface $authenticationManager,
        ContainerInterface $container
    ) {
        $this->tokenStorage = $tokenStorage;
        $this->authenticationManager = $authenticationManager;
        $this->container = $container;
    }

    public function handle(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        $token = $request->headers->get($this->container->get('service_container')->getParameter('sonrac_auth.header_token_name'));

        $authenticatedToken = $this->authenticationManager->authenticate($token);

        $this->tokenStorage->setToken($authenticatedToken);
    }
}
