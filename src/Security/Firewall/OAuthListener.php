<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12/4/18
 * Time: 11:49 PM
 */

declare(strict_types=1);

namespace Sonrac\OAuth2\Security\Firewall;

use Sonrac\OAuth2\Security\Config\OAuthPathConfig;
use Sonrac\OAuth2\Security\Handler\OAuthAuthenticationHandler;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\Security\Http\Firewall\ListenerInterface;

/**
 * Class OAuthListener
 * @package Sonrac\OAuth2\Security\Firewall
 */
class OAuthListener implements ListenerInterface
{
    /**
     * @var \Sonrac\OAuth2\Security\Handler\OAuthAuthenticationHandler
     */
    private $authenticationHandler;

    /**
     * @var \Sonrac\OAuth2\Security\Config\OAuthPathConfig
     */
    private $pathConfig;

    /**
     * OAuthListener constructor.
     * @param \Sonrac\OAuth2\Security\Handler\OAuthAuthenticationHandler $authenticationHandler
     * @param \Sonrac\OAuth2\Security\Config\OAuthPathConfig $pathConfig
     */
    public function __construct(OAuthAuthenticationHandler $authenticationHandler, OAuthPathConfig $pathConfig)
    {
        $this->authenticationHandler = $authenticationHandler;
        $this->pathConfig = $pathConfig;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(GetResponseEvent $event)
    {
        $request = $event->getRequest();

        if ($this->pathConfig->isAuthorizationPath($request)
            || $this->pathConfig->isIssueTokenPath($request)
        ) {
            return;
        }

        $response = $this->authenticationHandler->attemptAuthentication($request);

        if (null !== $response) {
            $event->setResponse($response);
        }
    }
}
