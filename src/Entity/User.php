<?php

declare(strict_types=1);

namespace Sonrac\OAuth2\Entity;

use OpenApi\Annotations as OA;
use Sonrac\OAuth2\Adapter\Entity\UserEntityInterface;

/**
 * Class User.
 */
class User implements UserEntityInterface
{
    use TimeEntityTrait;

    /**
     * User role name.
     *
     * @const
     *
     * @var string
     */
    public const ROLE_USER = 'ROLE_USER';

    /**
     * Manager role name.
     *
     * @const
     *
     * @var string
     */
    public const ROLE_MANAGER = 'ROLE_MANAGER';

    /**
     * Grant manager role name.
     *
     * @const
     *
     * @var string
     */
    public const ROLE_GRANT_MANAGER = 'ROLE_GRANT_MANAGER';

    /**
     * Administrator role name.
     *
     * @const
     *
     * @var string
     */
    public const ROLE_ADMIN = 'ROLE_ADMIN';

    /**
     * Root (superuser) role name.
     *
     * @const
     *
     * @var string
     */
    const ROLE_ROOT = 'ROLE_ROOT';

    /**
     * User status active.
     *
     * @const
     *
     * @var string
     */
    public const STATUS_ACTIVE = 'active';

    /**
     * User status disabled.
     *
     * @const
     *
     * @var string
     */
    public const STATUS_DISABLED = 'disabled';

    /**
     * User status pending.
     *
     * @const
     *
     * @var string
     */
    public const STATUS_PENDING = 'pending';

    /**
     * User status deleted.
     *
     * @const
     *
     * @var string
     */
    public const STATUS_DELETED = 'deleted';

    /**
     * User identifier.
     *
     * @var int|null
     */
    protected $id;

    /**
     * Username.
     *
     * @var string|null
     */
    protected $username;

    /**
     * User email.
     *
     * @var string|null
     */
    protected $email;

    /**
     * Password hash.
     *
     * @var string|null
     */
    protected $password;

    /**
     * User roles with " " as delimiter.
     *
     * @var array|null
     */
    protected $roles;

    /**
     * First name.
     *
     * @var string|null
     */
    protected $firstName;

    /**
     * Last name.
     *
     * @var string|null
     */
    protected $lastName;

    /**
     * Birth date.
     *
     * @var int|null
     *
     * @OA\Property(format="bigInt", example="1529397813")
     */
    protected $birthday;

    /**
     * User avatar.
     *
     * @var string|null
     */
    protected $avatar;

    /**
     * User status.
     * One of "pending", "active", "disabled" or "deleted".
     *
     * @var string|null
     */
    protected $status;

    /**
     * Additional user permissions.
     *
     * @var array|null
     */
    protected $additionalPermissions;

    /**
     * Lst login time.
     *
     * @var int|null
     */
    protected $lastLogin;

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
     * {@inheritdoc}
     */
    public function getIdentifier()
    {
        return $this->id;
    }

    /**
     * Get id.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get username.
     *
     * @return string|null
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * Set username.
     *
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * Get email.
     *
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Set email.
     *
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * Get user password hash.
     *
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * Set user password.
     *
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * Get user roles.
     *
     * @return array
     */
    public function getRoles(): array
    {
        return $this->roles ?? $this->roles = [self::ROLE_USER];
    }

    /**
     * Set user roles.
     *
     * @param array $roles
     */
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    /**
     * Get user first name.
     *
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * Set user first name.
     *
     * @param string $firstName
     */
    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * Get user last name.
     *
     * @return string
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * Set user last name.
     *
     * @param string $lastName
     */
    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * Get birth date.
     *
     * @return int
     */
    public function getBirthday(): ?int
    {
        return $this->birthday;
    }

    /**
     * Set birth date.
     *
     * @param int $birthday
     */
    public function setBirthday(int $birthday): void
    {
        $this->birthday = $birthday;
    }

    /**
     * Get user avatar.
     *
     * @return string|null
     */
    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    /**
     * Set user avatar.
     *
     * @param string|null $avatar
     */
    public function setAvatar(?string $avatar): void
    {
        $this->avatar = $avatar;
    }

    /**
     * Get user status.
     *
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status ?? $this->status = self::STATUS_ACTIVE;
    }

    /**
     * Set user status.
     *
     * @param string $status
     */
    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    /**
     * Get last login time.
     *
     * @return int
     */
    public function getLastLogin(): ?int
    {
        return $this->lastLogin;
    }

    /**
     * Set last login time.
     *
     * @param int $lastLogin
     */
    public function setLastLogin(int $lastLogin): void
    {
        $this->lastLogin = $lastLogin;
    }

    /**
     * Get additional user permissions.
     *
     * @return array
     */
    public function getAdditionalPermissions(): array
    {
        return null !== $this->additionalPermissions ? $this->additionalPermissions : [];
    }

    /**
     * Get additional user permissions.
     *
     * @param array $additionalPermissions
     */
    public function setAdditionalPermissions(array $additionalPermissions): void
    {
        $this->additionalPermissions = $additionalPermissions;
    }

    /**
     * {@inheritdoc}
     */
    public function getSalt()
    {
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {
    }
}
