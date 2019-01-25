<?php

declare(strict_types=1);

namespace Sonrac\OAuth2\Entity;

/**
 * Class AuthCode.
 */
class AuthCode
{
    use TimeEntityTrait;

    /**
     * AuthCode identifier.
     *
     * @var string|null
     */
    protected $id;

    /**
     * Client identifier.
     *
     * @var string|null
     */
    protected $clientId;

    /**
     * User identifier.
     *
     * @var int|null
     */
    protected $userId;

    /**
     * Redirect url.
     *
     * @var string|null
     */
    protected $redirectUri;

    /**
     * Scopes.
     *
     * @var array|null
     */
    protected $scopes;

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
     */
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * Get client identifier.
     *
     * @return string|null
     */
    public function getClientId(): ?string
    {
        return $this->clientId;
    }

    /**
     * Set client identifier.
     *
     * @param string $clientId
     */
    public function setClientId(string $clientId): void
    {
        $this->clientId = $clientId;
    }

    /**
     * Get user identifier.
     *
     * @return int|null
     */
    public function getUserId(): ?int
    {
        return $this->userId;
    }

    /**
     * Set user identifier.
     *
     * @param int|null $userId
     */
    public function setUserId(?int $userId): void
    {
        $this->userId = $userId;
    }

    /**
     * Get redirect uri.
     *
     * @return string|null
     */
    public function getRedirectUri(): ?string
    {
        return $this->redirectUri;
    }

    /**
     * Set redirect uri.
     *
     * @param string|null $redirectUri
     */
    public function setRedirectUri(?string $redirectUri): void
    {
        $this->redirectUri = $redirectUri;
    }

    /**
     * Get scopes.
     *
     * @return array
     */
    public function getScopes(): array
    {
        return null !== $this->scopes ? $this->scopes : [];
    }

    /**
     * Set scopes.
     *
     * @param array $scopes
     */
    public function setScopes(array $scopes): void
    {
        $this->scopes = $scopes;
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
     */
    public function setIsRevoked(bool $isRevoked): void
    {
        $this->isRevoked = $isRevoked;
    }
}
