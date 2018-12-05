<?php

declare(strict_types=1);

namespace sonrac\Auth\Entity;

use OpenApi\Annotations as OA;
use Psr\Container\ContainerInterface;
use Sonrac\OAuth2\Adapter\League\Entity\UserEntityInterface;

/**
 * Class User.
 *
 * @OA\Schema(
 *     title="User",
 *     description="User entity",
 *     required={"email", "username", "first_name", "last_name", "password", "avatar"}
 * )
 * //TODO: remove api token and api token expire time
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
     * @var int
     *
     * @OA\Property(example=1)
     */
    protected $id;

    /**
     * Username.
     *
     * @var string
     *
     * @OA\Property(example="username")
     */
    protected $username;

    /**
     * User email.
     *
     * @var string
     *
     * @OA\Property(example="test@test.com", format="email")
     */
    protected $email;

    /**
     * Password as bcrypt hash.
     *
     * @var string
     *
     * @OA\Property(readOnly=true, example="password-hash")
     */
    protected $password;

    /**
     * User roles with " " as delimiter.
     *
     * @var string
     *
     * @OA\Property(example="ROLE_ADMIN|ROLE_MANAGER")
     */
    protected $roles;

    /**
     * First name.
     *
     * @var string
     *
     * @OA\Property(example="John")
     */
    protected $first_name;

    /**
     * Last name.
     *
     * @var string
     *
     * @OA\Property(example="Doe")
     */
    protected $last_name;

    /**
     * Middle name.
     *
     * @var string
     *
     * @OA\Property(example="Middle")
     */
    protected $middle_name;

    /**
     * User avatar.
     *
     * @var string
     *
     * @OA\Property(example="/path/to/avatar.jpg")
     */
    protected $avatar;

    /**
     * User api token.
     *
     * @var string
     *
     * @OA\Property(example="example-token")
     */
    protected $api_token;

    /**
     * Created time.
     *
     * @var int
     *
     * @OA\Property(format="bigInt", example="1529397813")
     */
    protected $created_at;

    /**
     * Lst login time.
     *
     * @var int
     *
     * @OA\Property(format="bigInt", example="1529397813")
     */
    protected $last_login;

    /**
     * User api token expire date.
     *
     * @var int
     *
     * @OA\Property(format="bigInt", example="1529397813")
     */
    protected $api_token_expire_at;

    /**
     * Birth date.
     *
     * @var int
     *
     * @OA\Property(format="bigInt", example="1529397813")
     */
    protected $birthday;

    /**
     * Updated time.
     *
     * @var int
     *
     * @OA\Property(format="bigInt", example="1529397813")
     */
    protected $updated_at;

    /**
     * User status.
     * One of "pending", "active", "disabled" or "deleted".
     *
     * @var string
     *
     * @OA\Property(
     *     example="pending",
     *     default="active",
     *     enum={"pending", "active", "disabled", "deleted"}
     * )
     */
    protected $status;

    /**
     * Additional user permissions.
     *
     * @var array
     *
     * @OA\Property(
     *     example={"permission1", "permission2"},
     *     @OA\Items(
     *         type="string"
     *     )
     * )
     */
    protected $additional_permissions;

    /**
     * User constructor.
     *
     * @param \Psr\Container\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentifier()
    {
        return $this->id;
    }

    /**
     * Get username.
     *
     * @return string
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
     * @return string
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
     * @return string
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
     * @return string
     */
    public function getRoles(): ?string
    {
        return $this->roles;
    }

    /**
     * Set user roles.
     *
     * @param string $roles
     */
    public function setRoles(string $roles): void
    {
        $this->roles = $roles;
    }

    /**
     * Get user first name.
     *
     * @return string
     */
    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    /**
     * Set user first name.
     *
     * @param string $first_name
     */
    public function setFirstName(string $first_name): void
    {
        $this->first_name = $first_name;
    }

    /**
     * Get user last name.
     *
     * @return string
     */
    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    /**
     * Set user last name.
     *
     * @param string $last_name
     */
    public function setLastName(string $last_name): void
    {
        $this->last_name = $last_name;
    }

    /**
     * Get user avatar.
     *
     * @return string
     */
    public function getAvatar(): string
    {
        return $this->avatar;
    }

    /**
     * Set user avatar.
     *
     * @param string $avatar
     */
    public function setAvatar(string $avatar): void
    {
        $this->avatar = $avatar;
    }

    /**
     * @return int
     */
    public function getCreatedAt(): int
    {
        return $this->created_at;
    }

    /**
     * Get user status.
     *
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status ?? $this->status = 'active';
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
     * {@inheritdoc}
     */
    public function getSalt()
    {
        //TODO: save salt as field in database
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {
    }

    /**
     * Get user api token.
     *
     * @return string
     */
    public function getApiToken(): string
    {
        return $this->api_token;
    }

    /**
     * Set user api token.
     *
     * @param string $api_token
     */
    public function setApiToken(string $api_token): void
    {
        $this->api_token = $api_token;
    }

    /**
     * Get api token expire date.
     *
     * @return int
     */
    public function getApiTokenExpireAt(): ?int
    {
        return $this->api_token_expire_at;
    }

    /**
     * Set expire date for user api token.
     *
     * @param int $api_token_expire_at
     */
    public function setApiTokenExpireAt(int $api_token_expire_at): void
    {
        $this->api_token_expire_at = $api_token_expire_at;
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
     * Get last login time.
     *
     * @return int
     */
    public function getLastLogin(): ?int
    {
        return $this->last_login;
    }

    /**
     * Set last login time.
     *
     * @param int $last_login
     */
    public function setLastLogin(int $last_login): void
    {
        $this->last_login = $last_login;
    }

    /**
     * Get additional user permissions.
     *
     * @return array
     */
    public function getAdditionalPermissions(): array
    {
        if (!$this->additional_permissions || \is_string($this->additional_permissions)) {
            $this->additional_permissions = \explode('|', $this->additional_permissions ?? '') ?? [];
        }

        return $this->additional_permissions;
    }

    /**
     * Get additional user permissions.
     *
     * @param array $additionalPermissions
     */
    public function setAdditionalPermissions(array $additionalPermissions): void
    {
        $this->additional_permissions = $additionalPermissions;
    }
}
