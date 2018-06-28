<?php

namespace sonrac\Auth\Entity;

use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use Psr\Container\ContainerInterface;
use Swagger\Annotations as OAS;

/**
 * Class AuthCode.
 * Auth code entity.
 *
 * @OAS\Schema(
 *     title="AuthCode",
 *     description="Auth code entity",
 *     required={"auth_code", "redirect_uri", "client_id", "scopes"}
 * )
 */
class AuthCode implements AuthCodeEntityInterface
{
    use TimeEntityTrait, ExpiryTimeTrait;

    /**
     * Auth code identifier.
     *
     * @var int
     *
     * @OAS\Property(example=1)
     */
    protected $id;

    /**
     * Auth code.
     *
     * @var string
     *
     * @OAS\Property(example=1)
     */
    protected $auth_code;

    /**
     * Is revoked.
     *
     * @var bool
     *
     * @OAS\Property(example=false, default=false)
     */
    protected $is_revoked;

    /**
     * Redirect url.
     *
     * @var string
     *
     * @OAS\Property(example="http://example.com./redirect")
     */
    protected $redirect_uri;

    /**
     * User identifier.
     *
     * @var int
     *
     * @OAS\Property(example=1)
     */
    protected $user_id;

    /**
     * Client identifier.
     *
     * @var int
     *
     * @OAS\Property(example=1, default="null")
     */
    protected $client_id;

    /**
     * Expired time.
     *
     * @var int
     *
     * @OAS\Property(format="bigInt", example="1529397813")
     */
    protected $expire_at;

    /**
     * Created time.
     *
     * @var int
     *
     * @OAS\Property(format="bigInt", example="1529397813")
     */
    protected $created_at;

    /**
     * Updated time.
     *
     * @var int
     *
     * @OAS\Property(format="bigInt", example="1529397813")
     */
    protected $updated_at;

    /**
     * Conteiner.
     *
     * @var \Psr\Container\ContainerInterface
     */
    protected $container;

    /**
     * Scopes.
     *
     * @var array
     *
     * @OAS\Property(example={"user_get", "clients_get"}, default={})
     */
    protected $scopes;

    /**
     * Client.
     *
     * @var \League\OAuth2\Server\Entities\ClientEntityInterface|null
     */
    protected $client;

    /**
     * User.
     *
     * @var \League\OAuth2\Server\Entities\UserEntityInterface|null
     */
    protected $user;

    /**
     * AuthCode constructor.
     *
     * @param \Psr\Container\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function getRedirectUri(): string
    {
        return $this->redirect_uri ?? '';
    }

    /**
     * {@inheritdoc}
     */
    public function setRedirectUri($uri)
    {
        $this->redirect_uri = $uri;
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentifier(): int
    {
        return $this->id ?? 0;
    }

    /**
     * {@inheritdoc}
     */
    public function setIdentifier($identifier)
    {
        $this->id = (int) $identifier;
    }

    /**
     * {@inheritdoc}
     */
    public function setUserIdentifier($identifier)
    {
        $this->user_id = $identifier;
    }

    /**
     * {@inheritdoc}
     */
    public function getUserIdentifier()
    {
        return $this->user_id;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Psr\Container\ContainerExceptionInterface
     */
    public function getClient()
    {
        if ($this->client) {
            return $this->client;
        }

        if (!$this->client_id) {
            return null;
        }

        return $this->container->get('service_container')->get('oauth.repository.client')->find($this->client_id);
    }

    /**
     * {@inheritdoc}
     */
    public function setClient(ClientEntityInterface $client)
    {
        $this->client_id = $client->getIdentifier();

        $this->client = $client;
    }

    /**
     * {@inheritdoc}
     */
    public function addScope(ScopeEntityInterface $scope)
    {
        $this->scopes[] = $scope;
    }

    /**
     * Get scopes.
     *
     * {@inheritdoc}
     */
    public function getScopes(): array
    {
        return $this->scopes ?? [];
    }

    /**
     * Get auth code.
     *
     * @return string
     */
    public function getAuthCode(): string
    {
        return $this->auth_code ?? '';
    }

    /**
     * Set auth code.
     *
     * @param string $auth_code
     */
    public function setAuthCode(string $auth_code)
    {
        $this->auth_code = $auth_code;
    }

    /**
     * Check auth code is revoked.
     *
     * @return bool
     */
    public function isRevoked(): bool
    {
        return $this->is_revoked ?? false;
    }

    /**
     * Set auth code revoked.
     *
     * @param bool $is_revoked
     */
    public function setIsRevoked(bool $is_revoked)
    {
        $this->is_revoked = $is_revoked;
    }

    /**
     * Get user identifier.
     *
     * @return int|null
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Set user identifier.
     *
     * @param int $user_id
     */
    public function setUserId(int $user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * Get client identifier.
     *
     * @return int|null
     */
    public function getClientId()
    {
        return $this->client_id;
    }

    /**
     * Set client identifier.
     *
     * @param int $client_id
     */
    public function setClientId(int $client_id)
    {
        $this->client_id = $client_id;
    }

    /**
     * Get expired time.
     *
     * @return int|null
     */
    public function getExpireAt()
    {
        return $this->expire_at;
    }

    /**
     * Set expire time.
     *
     * @param int $expire_at
     */
    public function setExpireAt(int $expire_at)
    {
        $this->expire_at = $expire_at;
    }
}
