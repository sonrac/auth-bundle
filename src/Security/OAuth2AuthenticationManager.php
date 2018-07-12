<?php

declare(strict_types=1);

namespace sonrac\Auth\Security;

use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

/**
 * Class OAuth2AuthenticationManager.
 */
class OAuth2AuthenticationManager implements AuthenticationManagerInterface
{
    /**
     * {@inheritdoc}
     */
    public function authenticate(TokenInterface $token)
    {
        // TODO: Implement authenticate() method.
    }
}
