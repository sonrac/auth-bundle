<?php

namespace sonrac\AuthBundle\Entity;

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
     * @inheritDoc
     */
    public function getRedirectUri(): string
    {
        return $this->redirect_uri ?? '';
    }

    /**
     * @inheritDoc
     */
    public function setRedirectUri($uri): void
    {
        $this->redirect_uri = $uri;
    }

    /**
     * @inheritDoc
     */
    public function getIdentifier(): int
    {
        return $this->id ?? 0;
    }

    /**
     * @inheritDoc
     */
    public function setIdentifier($identifier): void
    {
        $this->id = (int) $identifier;
    }

    /**
     * @inheritDoc
     */
    public function setUserIdentifier($identifier)
    {
        $this->user_id = $identifier;
    }

    /**
     * @inheritDoc
     */
    public function getUserIdentifier(): ?int
    {
        return $this->user_id;
    }

    /**
     * @inheritDoc
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
     * @inheritDoc
     */
    public function setClient(ClientEntityInterface $client): void
    {
        $this->client_id = $client->getIdentifier();

        $this->client = $client;
    }

    /**
     * @inheritDoc
     */
    public function addScope(ScopeEntityInterface $scope): void
    {
        $this->scopes[] = $scope;
    }

    /**
     * Get scopes.
     *
     * @inheritDoc
     */
    public function getScopes(): ?array
    {
        return $this->scopes;
    }

    /**
     * Get auth code.
     *
     * @return string
     */
    public function getAuthCode(): ?string
    {
        return $this->auth_code;
    }

    /**
     * Set auth code.
     *
     * @param string $auth_code
     */
    public function setAuthCode(string $auth_code): void
    {
        $this->auth_code = $auth_code;
    }

    /**
     * Check auth code is revoked.
     *
     * @return bool
     */
    public function isRevoked(): ?bool
    {
        return $this->is_revoked;
    }

    /**
     * Set auth code revoked.
     *
     * @param bool $is_revoked
     */
    public function setIsRevoked(bool $is_revoked): void
    {
        $this->is_revoked = $is_revoked;
    }

    /**
     * Get user identifier.
     *
     * @return int
     */
    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    /**
     * Set user identifier.
     *
     * @param int $user_id
     */
    public function setUserId(int $user_id): void
    {
        $this->user_id = $user_id;
    }

    /**
     * Get client identifier.
     *
     * @return int
     */
    public function getClientId(): ?int
    {
        return $this->client_id;
    }

    /**
     * Set client identifier.
     *
     * @param int $client_id
     */
    public function setClientId(int $client_id): void
    {
        $this->client_id = $client_id;
    }

    /**
     * Get expired time.
     *
     * @return int
     */
    public function getExpireAt(): ?int
    {
        return $this->expire_at;
    }

    /**
     * Set expire time.
     *
     * @param int $expire_at
     */
    public function setExpireAt(int $expire_at): void
    {
        $this->expire_at = $expire_at;
    }
}
