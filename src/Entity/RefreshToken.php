<?php

declare(strict_types=1);

namespace sonrac\Auth\Entity;

use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use League\OAuth2\Server\Entities\ScopeEntityInterface;
use League\OAuth2\Server\Entities\Traits\RefreshTokenTrait;
use Openapi\Annotations as OA;

/**
 * Class RefreshToken.
 * Refresh token entity.
 *
 * @OA\Schema(
 *     title="RefreshToken",
 *     description="Refresh token entity",
 *     required={"refresh_token", "token", "expire_at"}
 * )
 */
class RefreshToken implements RefreshTokenEntityInterface
{
    use TimeEntityTrait, RefreshTokenTrait{
        setAccessToken as setAccessTokenTrait;
        setExpiryDateTime as setExpiryDateTimeTrait;
    }

    /**
     * Refresh token.
     *
     * @var string
     *
     * @OA\Property(example="refresh_token", maxLength=2000)
     */
    protected $refresh_token;

    /**
     * Access token.
     *
     * @var string
     *
     * @OA\Property(example="token", maxLength=2000, uniqueItems=true)
     */
    protected $token;

    /**
     * Expire date.
     *
     * @var int
     *
     * @OA\Property(example=1529397813, format="bigInt")
     */
    protected $expire_at;

    /**
     * Refresh token scopes.
     *
     * @var array
     *
     * @OA\Property(
     *     example={"client", "admin"},
     *     default={"default"},
     *     @OA\Items(
     *         type="string"
     *     )
     * )
     */
    protected $token_scopes;

    /**
     * Is revoked token.
     *
     * @var bool
     *
     * @OA\Property(example=false, default=false)
     */
    protected $is_revoked = false;

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
     * Get token.
     *
     * @return string
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * Set token.
     *
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
    public function getScopes(): ?array
    {
        return $this->token_scopes;
    }

    /**
     * Set scopes.
     *
     * @param array $scopes
     */
    public function setScopes(array $scopes): void
    {
        $this->token_scopes = $this->token_scopes ?? [];
        foreach ($scopes as $scope) {
            if (\is_object($scope) && \in_array(ScopeEntityInterface::class, \class_implements($scope))) {
                $this->token_scopes[] = $scope->getIdentifier();

                continue;
            }

            $this->token_scopes[] = $scope;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setAccessToken(AccessTokenEntityInterface $accessToken): void
    {
        $this->token = $accessToken->getIdentifier();

        $this->setAccessTokenTrait($accessToken);
        $this->setScopes($accessToken->getScopes());
    }

    /**
     * {@inheritdoc}
     */
    public function setExpiryDateTime(\DateTime $dateTime): void
    {
        $this->expire_at = $dateTime->getTimestamp();
        $this->setExpiryDateTimeTrait($dateTime);
    }
}
