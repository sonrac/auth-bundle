<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12/5/18
 * Time: 9:10 PM
 */

declare(strict_types=1);

namespace Sonrac\OAuth2\Security\Token;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;
use Symfony\Component\Security\Core\Role\Role;

/**
 * Class AbstractPreAuthenticationToken
 * @package Sonrac\OAuth2\Security\Token
 */
abstract class AbstractPreAuthenticationToken extends AbstractToken
{
    /**
     * @var \League\OAuth2\Server\Entities\ClientEntityInterface
     */
    private $client;

    /**
     * @var string
     */
    private $providerKey;

    /**
     * @var string|null
     */
    private $credentials;

    /**
     * @var string[]
     */
    private $scopes;

    /**
     * AbstractPreAuthenticationToken constructor.
     * @param \League\OAuth2\Server\Entities\ClientEntityInterface $client
     * @param string $providerKey
     * @param string|null $credentials
     * @param array $scopes
     * @param array $roles
     */
    public function __construct(
        ClientEntityInterface $client,
        string $providerKey,
        ?string $credentials = null,
        array $scopes = [],
        array $roles = []
    ) {
        parent::__construct($roles);

        $this->client = $client;
        $this->providerKey = $providerKey;
        $this->credentials = $credentials;
        $this->scopes = $scopes;
    }

    /**
     * {@inheritdoc}
     */
    public function setAuthenticated($authenticated)
    {
        if ($authenticated) {
            throw new \LogicException('Cannot set this token to trusted.');
        }

        parent::setAuthenticated(false);
    }

    /**
     * @return \League\OAuth2\Server\Entities\ClientEntityInterface
     */
    public function getClient(): ClientEntityInterface
    {
        return $this->client;
    }

    /**
     * @return string
     */
    public function getProviderKey(): string
    {
        return $this->providerKey;
    }

    /**
     * @return string[]
     */
    public function getScopes(): array
    {
        return $this->scopes;
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
            $this->credentials,
            $this->providerKey,
            $this->scopes,
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
            $this->credentials,
            $this->providerKey,
            $this->scopes,
            $parent
        ] = \unserialize($serialized);

        parent::unserialize($parent);
    }

    /**
     * {@inheritdoc}
     */
    public function __toString()
    {
        $class = \get_class($this);
        $class = substr($class, strrpos($class, '\\') + 1);

        $roles = array_map(function (Role $role) {
            return $role->getRole();
        }, $this->getRoles());

        return sprintf(
            '%s(client="%s", user="%s", authenticated=%s, scopes="%s", roles="%s")',
            $class,
            $this->client->getIdentifier(),
            $this->getUsername(),
            json_encode($this->isAuthenticated()),
            implode(', ', $this->scopes),
            implode(', ', $roles)
        );
    }
}
