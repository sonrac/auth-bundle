<?php


namespace sonrac\Auth\Security;
use Symfony\Component\Security\Core\Authentication\AuthenticationManagerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;

/**
 * Class OAuth2AuthenticationManager
 */
class OAuth2AuthenticationManager implements AuthenticationManagerInterface
{
    /**
     * @inheritDoc
     */
    public function authenticate(TokenInterface $token)
    {
        // TODO: Implement authenticate() method.
    }

}