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
     * @var string
     */
    private $providerKey;

    /**
     * OAuth2AuthenticationManager constructor.
     * @param string $providerKey
     */
    public function __construct(string $providerKey)
    {
        $this->providerKey = $providerKey;
    }

    /**
     * {@inheritdoc}
     */
    public function authenticate(TokenInterface $token)
    {
        return $token;
    }
}
