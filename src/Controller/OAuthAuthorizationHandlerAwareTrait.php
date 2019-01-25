<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12/23/18
 * Time: 4:35 PM.
 */

declare(strict_types=1);

namespace Sonrac\OAuth2\Controller;

use Sonrac\OAuth2\Security\Handler\OAuthAuthorizationHandler;

/**
 * Trait OAuthAuthorizationHandlerAwareTrait.
 */
trait OAuthAuthorizationHandlerAwareTrait
{
    /**
     * @var \Sonrac\OAuth2\Security\Handler\OAuthAuthorizationHandler|null
     */
    protected $OAuthAuthorizationHandler;

    /**
     * @param \Sonrac\OAuth2\Security\Handler\OAuthAuthorizationHandler|null $OAuthAuthorizationHandler
     */
    public function setOAuthAuthorizationHandler(?OAuthAuthorizationHandler $OAuthAuthorizationHandler = null): void
    {
        $this->OAuthAuthorizationHandler = $OAuthAuthorizationHandler;
    }
}
