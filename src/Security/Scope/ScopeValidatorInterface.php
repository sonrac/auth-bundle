<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12/18/18
 * Time: 10:35 PM
 */

declare(strict_types=1);

namespace Sonrac\OAuth2\Security\Scope;

use Psr\Http\Message\ServerRequestInterface;
use Sonrac\OAuth2\Security\Token\AbstractOAuthToken;

/**
 * Interface ScopeValidatorInterface
 * @package Sonrac\OAuth2\Security\Scope
 */
interface ScopeValidatorInterface
{

    /**
     * @param \Sonrac\OAuth2\Security\Token\AbstractOAuthToken $token
     * @param \Psr\Http\Message\ServerRequestInterface $request
     *
     * @return void
     *
     * @throws \League\OAuth2\Server\Exception\OAuthServerException
     */
    public function validateTokenScopes(AbstractOAuthToken $token, ServerRequestInterface $request): void;
}
