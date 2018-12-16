<?php

declare(strict_types=1);

namespace Sonrac\OAuth2\Entity;

/**
 * Class RefreshToken
 * @package Sonrac\OAuth2\Entity
 */
class RefreshToken
{
    use TimeEntityTrait;

    /**
     * Refresh token identifier.
     *
     * @var string|null
     */
    protected $id;

    /**
     * Access token.
     *
     * @var string|null
     */
    protected $accessToken;

    /**
     * Expired time.
     *
     * @var int|null
     */
    protected $expireAt;

    /**
     * Is revoked.
     *
     * @var bool
     */
    protected $isRevoked = false;

    /**
     * Created time.
     *
     * @var int|null
     */
    protected $createdAt;

    /**
     * Updated time.
     *
     * @var int|null
     */
    protected $updatedAt;

    /**
     * Get id.
     *
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * Set id.
     *
     * @param string $id
     *
     * @return void
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * Get access token.
     *
     * @return string|null
     */
    public function getAccessToken(): ?string
    {
        return $this->accessToken;
    }

    /**
     * Set access token.
     *
     * @param string $accessToken
     *
     * @return void
     */
    public function setAccessToken(string $accessToken): void
    {
        $this->accessToken = $accessToken;
    }

    /**
     * Get expired time.
     *
     * @return int|null
     */
    public function getExpireAt(): ?int
    {
        return $this->expireAt;
    }

    /**
     * Set expire time.
     *
     * @param int $expireAt
     *
     * @return void
     */
    public function setExpireAt(int $expireAt): void
    {
        $this->expireAt = $expireAt;
    }

    /**
     * Check auth code is revoked.
     *
     * @return bool
     */
    public function isRevoked(): bool
    {
        return $this->isRevoked;
    }

    /**
     * Set auth code revoked.
     *
     * @param bool $isRevoked
     *
     * @return void
     */
    public function setIsRevoked(bool $isRevoked): void
    {
        $this->isRevoked = $isRevoked;
    }
}
