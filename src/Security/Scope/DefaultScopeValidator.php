<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 1/10/19
 * Time: 9:28 PM
 */

declare(strict_types=1);

namespace Sonrac\OAuth2\Security\Scope;

use Psr\Http\Message\ServerRequestInterface;
use Sonrac\OAuth2\Security\Token\AbstractOAuthToken;

/**
 * Class DefaultScopeValidator
 * @package Sonrac\OAuth2\Security\Scope
 */
class DefaultScopeValidator implements ScopeValidatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function validateTokenScopes(AbstractOAuthToken $token, ServerRequestInterface $request): void
    {
        // No validation is performed. Create your own scope validator.
    }
}
