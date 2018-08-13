<?php

declare(strict_types=1);

namespace sonrac\Auth\Entity;

use League\OAuth2\Server\Entities\AuthCodeEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use Psr\Container\ContainerInterface;
use Openapi\Annotations as OA;

/**
 * Class AuthCode.
 * Auth code entity.
 *
 * @OA\Schema(
 *     title="AuthCode",
 *     description="Auth code entity",
 *     required={"code", "redirect_uri", "client_id", "scopes"}
 * )
 */
class AuthCode implements AuthCodeEntityInterface
{
    use TimeEntityTrait, ExpiryTimeTrait {
        getExpiryDateTimeAsInt as getExpiryDateTime;
        setExpiryDateTimeAsInt as setExpiryDateTime;
    }

    /**
     * Auth code.
     *
     * @var string
     *
     * @OA\Property(example="auth_code", uniqueItems=true)
     */
    protected $code;

    /**
     * Is revoked.
     *
     * @var bool
     *
     * @OA\Property(
     *     example=false,
     *     default=false
     * )
     */
    protected $is_revoked = false;

    /**
     * Redirect url.
     *
     * @var string
     *
     * @OA\Property(
     *     example="http://example.com./redirect",
     * )
     */
    protected $redirect_uri;

    /**
     * User identifier.
     *
     * @var int
     *
     * @OA\Property(example=1)
     */
    protected $user_id;

    /**
     * Client identifier.
     *
     * @var int
     *
     * @OA\Property(example=1, default="null")
     */
    protected $client_id;

    /**
     * Expired time.
     *
     * @var int
     *
     * @OA\Property(format="bigInt", example="1529397813")
     */
    protected $expire_at;

    /**
     * Created time.
     *
     * @var int
     *
     * @OA\Property(format="bigInt", example="1529397813")
     */
    protected $created_at;

    /**
     * Updated time.
     *
     * @var int
     *
     * @OA\Property(format="bigInt", example="1529397813")
     */
    protected $updated_at;

    /**
     * Container.
     *
     * @var \Psr\Container\ContainerInterface
     */
    protected $container;

    /**
     * Scopes.
     *
     * @var array
     *
     * @OA\Property(
     *     example={"user_get", "clients_get"},
     *     default={},
     *     @OA\Items(
     *         type="string"
     *     )
     * )
     */
    protected $token_scopes;

    /**
     * Token scopes object.
     *
     * @var \League\OAuth2\Server\Entities\ScopeEntityInterface[]
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
    public function getRedirectUri(): ?string
    {
        return $this->redirect_uri;
    }

    /**
     * {@inheritdoc}
     */
    public function setRedirectUri($uri): void
    {
        $this->redirect_uri = $uri;
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentifier(): ?string
    {
        return $this->code;
    }

    /**
     * {@inheritdoc}
     */
    public function setIdentifier($identifier): void
    {
        $this->code = $identifier;
    }

    /**
     * {@inheritdoc}
     */
    public function setUserIdentifier($identifier): void
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

        return $this->client = $this->container->get(ClientRepositoryInterface::class)->find($this->client_id);
    }

    /**
     * {@inheritdoc}
     */
    public function setClient(ClientEntityInterface $client): void
    {
        $this->client_id = $client->getIdentifier();

        $this->client = $client;
    }

    /**
     * {@inheritdoc}
     */
    public function addScope(ScopeEntityInterface $scope): void
    {
        $this->token_scopes[] = $scope->getIdentifier();
        $this->scopes[]       = $scope;
    }

    /**
     * Get scopes.
     *
     * {@inheritdoc}
     */
    public function getScopes(): ?array
    {
        return $this->getTokenScopes();
    }

    /**
     * Get token scopes list.
     *
     * @return array
     */
    public function getTokenScopes(): ?array
    {
        return $this->token_scopes;
    }

    /**
     * Get auth code.
     *
     * @return string
     */
    public function getCode(): string
    {
        return $this->code ?? '';
    }

    /**
     * Set auth code.
     *
     * @param string $code
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    /**
     * Check auth code is revoked.
     *
     * @return bool
     */
    public function isRevoked(): bool
    {
        return (bool) ($this->is_revoked ?? false);
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
     * @return int|null
     */
    public function getUserId(): int
    {
        return (int) $this->user_id;
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
     * @return int|null
     */
    public function getClientId(): int
    {
        return (int) $this->client_id;
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
     * @return int|null
     */
    public function getExpireAt(): int
    {
        return (int) $this->expire_at;
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
