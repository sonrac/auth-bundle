<?php

declare(strict_types=1);

namespace Sonrac\OAuth2\Security\Token;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use Sonrac\OAuth2\Security\Scope\Scope;
use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class AbstractOAuthToken.
 */
abstract class AbstractOAuthToken extends AbstractToken
{
    /**
     * @var \League\OAuth2\Server\Entities\ClientEntityInterface
     */
    private $client;

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
     * OAuthToken constructor.
     *
     * @param \League\OAuth2\Server\Entities\ClientEntityInterface $client
     * @param string                                               $providerKey
     * @param string                                               $credentials
     * @param array                                                $scopes
     * @param array                                                $roles
     */
    public function __construct(
        ClientEntityInterface $client,
        string $providerKey,
        ?string $credentials = null,
        array $scopes = [],
        array $roles = []
    ) {
        parent::__construct($roles);

        if ('' === $providerKey) {
            throw new \InvalidArgumentException('$providerKey must not be empty.');
        }

        $this->client      = $client;
        $this->providerKey = $providerKey;
        $this->credentials = $credentials;

        foreach ($scopes as $scope) {
            if (\is_string($scope)) {
                $scope = new Scope($scope);
            } elseif (false === $scope instanceof Scope) {
                throw new \InvalidArgumentException(
                    \sprintf(
                        '$scopes must be an array of strings, or Scope instances, but got %s.',
                        \gettype($scope)
                    )
                );
            }

            $this->scopes[] = $scope;
        }

        if (0 !== \count($this->scopes)) {
            parent::setAuthenticated(true);
        }
    }

    /**
     * @return \League\OAuth2\Server\Entities\ClientEntityInterface
     */
    public function getClient(): ClientEntityInterface
    {
        return $this->client;
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

    /**
     * {@inheritdoc}
     *
     * @return \Symfony\Component\Security\Core\User\UserInterface|null
     */
    public function getUser(): ?UserInterface
    {
        return parent::getUser();
    }

    /**
     * {@inheritdoc}
     */
    public function setAuthenticated($authenticated)
    {
        if ($authenticated) {
            throw new \LogicException('Cannot set this token to trusted after instantiation.');
        }

        parent::setAuthenticated(false);
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {
        parent::eraseCredentials();

        $this->credentials = null;
    }

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return \serialize([
                              clone $this->client,
                              $this->providerKey,
                              $this->credentials,
                              \array_map(function (Scope $scope) {
                                  return clone $scope;
                              }, $this->getScopes()),
                              parent::serialize(),
                          ]);
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized)
    {
        [
            $this->client,
            $this->providerKey,
            $this->credentials,
            $this->scopes,
            $parent,
        ]
            = \unserialize($serialized);

        parent::unserialize($parent);
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        $class = \get_class($this);
        $class = \mb_substr($class, \mb_strrpos($class, '\\') + 1);

        $roles = \array_map(function (Role $role) {
            return $role->getRole();
        }, $this->getRoles());

        $scopes = \array_map(function (Scope $scope) {
            return $scope->getScope();
        }, $this->scopes);

        return \sprintf(
            '%s(client="%s", user="%s", authenticated=%s, scopes="%s", roles="%s")',
            $class,
            $this->client->getIdentifier(),
            $this->getUsername(),
            \json_encode($this->isAuthenticated()),
            \implode(', ', $scopes),
            \implode(', ', $roles)
        );
    }
}
