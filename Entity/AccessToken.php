<?php

namespace sonrac\AuthBundle\Entity;

use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\Traits\AccessTokenTrait;
use League\OAuth2\Server\Entities\Traits\TokenEntityTrait;
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
    use AccessTokenTrait, TimeEntityTrait, TokenEntityTrait {
        setExpiryDateTime as setExpiryDateTimeTrait;
        getClient as getClientTrait;
        setClient as setClientTrait;
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
     * @var string
     *
     * @OAS\Property(example="client|admin", maxLength=5000)
     */
    protected $token_scopes;

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
    protected $is_revoked;

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
     * @return string
     */
    public function getToken(): string
    {
        return $this->token;
    }

    /**
     * @param string $token
     */
    public function setToken(string $token): void
    {
        $this->token = $token;
    }

    /**
     * @return int
     */
    public function getUserId(): int
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
     * @return int
     */
    public function getClientId(): int
    {
        return $this->client_id;
    }

    /**
     * @param int $client_id
     */
    public function setClientId(int $client_id): void
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
    public function getExpireAt(): int
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
     * @return string|null
     */
    public function getTokenScopes(): ?string
    {
        if (!$this->scopes && $scopes = $this->getScopes()) {
            $this->scopes = '';
            foreach ($scopes as $scope) {
                $this->scopes .= ($this->scopes ? '|' : '').$scope->getIdentifier();
            }
        }

        return $this->scopes;
    }

    /**
     * Set token scopes.
     *
     * @param string $scopes
     */
    public function setTokenScopes(string $scopes): void
    {
        $this->scopes = $scopes;
    }
}
