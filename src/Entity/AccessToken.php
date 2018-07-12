<?php

declare(strict_types=1);

namespace sonrac\Auth\Entity;

use Lcobucci\JWT\Token;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Entities\Traits\AccessTokenTrait;
use League\OAuth2\Server\Entities\Traits\TokenEntityTrait;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use Swagger\Annotations as OAS;

/**
 * Class AccessToken.
 *
 * @OAS\Schema(
 *     title="AccessToken",
 *     description="Access token entity",
 *     required={"token", "user_id", "token_scopes"}
 * )
 */
class AccessToken implements AccessTokenEntityInterface
{
    use AccessTokenTrait, TimeEntityTrait, ExpiryTimeTrait, TokenEntityTrait {
        setExpiryDateTime as setExpiryDateTimeTrait;
        setClient as setClientTrait;
        addScope as addScopeTrait;
    }

    /**
     * Access token.
     *
     * @var string
     *
     * @OAS\Property(maxLength=2000, example="token", uniqueItems=true)
     */
    protected $token;

    /**
     * Token scopes with | as delimiter.
     *
     * @var array
     *
     * @OAS\Property(
     *     example={"client", "admin"},
     *     maxLength=5000,
     *     @OAS\Items(
     *         type="string"
     *     )
     * )
     */
    protected $token_scopes = [];

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
     * @var string
     *
     * @OAS\Property(example=1)
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
     * Access token revoked.
     *
     * @var bool
     *
     * @OAS\Property(example=false, default=false)
     */
    protected $is_revoked = false;

    /**
     * Access token grant_type.
     *
     * @var string
     *
     * @OAS\Property(example="client_credentials", enum={"password", "code", "client_credentials", "implicit"})
     */
    protected $grant_type;

    /**
     * @var \sonrac\Auth\Repository\Clients
     */
    private $repository;

    public function __construct(ClientRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentifier(): ?string
    {
        return $this->token;
    }

    /**
     * {@inheritdoc}
     */
    public function setIdentifier($identifier): void
    {
        $this->token = $identifier;
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
    public function getUserIdentifier(): ?int
    {
        return $this->user_id;
    }

    /**
     * @return int
     */
    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    /**
     * @param int $user_id
     */
    public function setUserId(int $user_id): void
    {
        $this->user_id = $user_id;
    }

    /**
     * Get client identifier.
     *
     * @return string
     */
    public function getClientId(): ?string
    {
        return $this->client_id;
    }

    /**
     * @param string $client_id
     */
    public function setClientId(string $client_id): void
    {
        $this->client_id = $client_id;
    }

    /**
     * {@inheritdoc}
     */
    public function setClient(ClientEntityInterface $client): void
    {
        $this->setClientId($client->getIdentifier());

        $this->setClientTrait($client);
    }

    /**
     * @return int
     */
    public function getExpireAt(): ?int
    {
        return $this->expire_at;
    }

    /**
     * @param int $expire_at
     */
    public function setExpireAt(int $expire_at): void
    {
        $this->expire_at = $expire_at;
    }

    /**
     * Check token is revoked.
     *
     * @return bool|int
     */
    public function isRevoked()
    {
        return $this->is_revoked;
    }

    /**
     * @param bool $is_revoked
     */
    public function setIsRevoked(bool $is_revoked): void
    {
        $this->is_revoked = $is_revoked;
    }

    /**
     * Get token scopes.
     *
     * @return array|null
     */
    public function getTokenScopes(): ?array
    {
        return $this->token_scopes;
    }

    /**
     * Set token scopes.
     *
     * @param array $scopes
     */
    public function setTokenScopes(array $scopes): void
    {
        $this->token_scopes = $scopes;
    }

    /**
     * {@inheritdoc}
     */
    public function getClient()
    {
        if (!$this->client && $this->client_id) {
            $this->client = $this->repository->find($this->client_id);
        }

        return $this->client;
    }

    /**
     * {@inheritdoc}
     */
    public function addScope(ScopeEntityInterface $scope): void
    {
        $this->token_scopes[] = $scope->getIdentifier();
        $this->addScopeTrait($scope);
    }

    /**
     * {@inheritdoc}
     */
    public function setExpiryDateTime(\DateTime $dateTime): void
    {
        $this->setExpiryDateTimeAsInt($dateTime);

        $this->setExpiryDateTimeTrait($dateTime);
    }

    /**
     * Get grant type.
     *
     * @return string
     */
    public function getGrantType(): string
    {
        return $this->grant_type ?? '';
    }

    /**
     * Set grant type.
     *
     * @param string $grantType
     */
    public function setGrantType(string $grantType): void
    {
        $this->grant_type = $grantType;
    }

    /**
     * {@inheritdoc}
     */
    public function getScopes(): array
    {
        return $this->token_scopes ?? [];
    }
}
