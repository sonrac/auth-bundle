<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12/23/18
 * Time: 4:37 PM
 */

declare(strict_types=1);

namespace Sonrac\OAuth2\Controller;

use Sonrac\OAuth2\Security\Handler\OAuthIssueTokenHandler;

/**
 * Trait OAuthIssueTokenHandlerAwareTrait
 * @package Sonrac\OAuth2\Controller
 */
trait OAuthIssueTokenHandlerAwareTrait
{
    /**
     * @var \Sonrac\OAuth2\Security\Handler\OAuthIssueTokenHandler|null
     */
    protected $OAuthIssueTokenHandler;

    /**
     * @param \Sonrac\OAuth2\Security\Handler\OAuthIssueTokenHandler|null $OAuthIssueTokenHandler
     *
     * @return void
     */
    public function setOAuthIssueTokenHandler(?OAuthIssueTokenHandler $OAuthIssueTokenHandler = null): void
    {
        $this->OAuthIssueTokenHandler = $OAuthIssueTokenHandler;
    }
}
