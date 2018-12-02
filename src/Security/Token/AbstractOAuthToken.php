<?php

declare(strict_types=1);

namespace Sonrac\OAuth2\Security\Token;

use Sonrac\OAuth2\Security\Scope\Scope;
use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;

/**
 * Class AbstractOAuthToken
 * @package Sonrac\OAuth2\Security\Token
 */
abstract class AbstractOAuthToken extends AbstractToken implements OAuthTokenInterface
{
    /**
     * @var string
     */
    private $credentials;

    /**
     * @var string
     */
    private $providerKey;

    /**
     * @var \Sonrac\OAuth2\Security\Scope\Scope[]
     */
    private $scopes = [];

    /**
     * AbstractOAuthToken constructor.
     * @param string $credentials
     * @param string $providerKey
     * @param array $scopes
     * @param array $roles
     */
    public function __construct(string $credentials, string $providerKey, array $scopes = [], array $roles = [])
    {
        parent::__construct($roles);

        $this->credentials = $credentials;
        $this->providerKey = $providerKey;

        foreach ($scopes as $scope) {
            if (\is_string($scope)) {
                $scope = new Scope($scope);
            } elseif (false === $scope instanceof Scope) {
                throw new \InvalidArgumentException(sprintf('$scopes must be an array of strings, or Scope instances, but got %s.', \gettype($scope)));
            }

            $this->scopes[] = $scope;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCredentials()
    {
        return $this->credentials;
    }


    /**
     * {@inheritdoc}
     */
    public function getProviderKey(): string
    {
        return $this->providerKey;
    }

    /**
     * {@inheritdoc}
     */
    public function getScopes(): array
    {
        return $this->scopes;
    }
}
