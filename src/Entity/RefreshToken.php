<?php

namespace sonrac\Auth\Entity;

use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Entities\Traits\RefreshTokenTrait;
use Swagger\Annotations as OAS;

/**
 * Class RefreshToken.
 * Refresh token entity.
 *
 * @OAS\Schema(
 *     title="RefreshToken",
 *     description="Refresh token entity",
 *     required={"refresh_token", "token", "expire_at"}
 * )
 */
class RefreshToken implements RefreshTokenEntityInterface
{
    use TimeEntityTrait, RefreshTokenTrait;

    /**
     * Refresh token.
     *
     * @var string
     *
     * @OAS\Property(example="refresh_token", maxLength=2000)
     */
    protected $refresh_token;

    /**
     * Access token.
     *
     * @var string
     *
     * @OAS\Property(example="token", maxLength=2000, uniqueItems=true)
     */
    protected $token;

    /**
     * Expire date.
     *
     * @var int
     *
     * @OAS\Property(example=1529397813, format="bigInt")
     */
    protected $expire_at;

    /**
     * Refresh token scopes.
     *
     * @var array
     *
     * @OAS\Property(example={"client", "admin"}, default={"default"})
     */
    protected $token_scopes;

    /**
     * Is revoked token.
     *
     * @var bool
     *
     * @OAS\Property(example=false, default=false)
     */
    protected $is_revoked = false;

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
     * {@inheritdoc}
     */
    public function getIdentifier(): string
    {
        return $this->refresh_token ?? '';
    }

    /**
     * {@inheritdoc}
     */
    public function setIdentifier($identifier): void
    {
        $this->refresh_token = $identifier;
    }

    /**
     * @return string
     */
    public function getRefreshToken(): string
    {
        return $this->refresh_token ?? '';
    }

    /**
     * @param string $refresh_token
     */
    public function setRefreshToken(string $refresh_token): void
    {
        $this->refresh_token = $refresh_token;
    }

    /**
     * @return string
     */
    public function getToken(): string
    {
        return $this->token ?? '';
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
    public function getExpireAt(): int
    {
        return $this->expire_at ?? 0;
    }

    /**
     * @param int $expire_at
     */
    public function setExpireAt(int $expire_at): void
    {
        $this->expire_at = $expire_at;
    }

    /**
     * @return bool
     */
    public function isRevoked(): bool
    {
        return $this->is_revoked ?? false;
    }

    /**
     * @param bool $is_revoked
     */
    public function setIsRevoked(bool $is_revoked): void
    {
        $this->is_revoked = $is_revoked;
    }

    /**
     * @return int
     */
    public function getCreatedAt(): int
    {
        return $this->created_at ?? \time();
    }

    /**
     * @param int $created_at
     */
    public function setCreatedAt(int $created_at): void
    {
        $this->created_at = $created_at;
    }

    /**
     * Get scopes.
     *
     * @return array
     */
    public function getScopes(): array
    {
        if (!$this->token_scopes || \is_string($this->token_scopes)) {
            $this->token_scopes = \explode('|', $this->token_scopes || '');
        }

        return $this->token_scopes;
    }

    /**
     * Set scopes.
     *
     * @param array $scopes
     */
    public function setScopes(array $scopes): void
    {
        $this->token_scopes = $scopes;
    }

    /**
     * Prepare data before persist.
     */
    public function preparePersist(): void
    {
        if (\is_array($this->token_scopes)) {
            $this->token_scopes = \implode('|', $this->token_scopes);
        }
    }
}
