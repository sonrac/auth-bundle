<?php

namespace sonrac\Auth\Entity;

use League\OAuth2\Server\Entities\UserEntityInterface;
use Swagger\Annotations as OAS;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class User.
 *
 * @OAS\Schema(
 *     title="User",
 *     description="User entity"
 * )
 */
class User implements UserEntityInterface, UserInterface
{
    /**
     * User status active.
     *
     * @const
     *
     * @var string
     */
    public const STATUS_ACTIVE='active';

    /**
     * User status disabled.
     *
     * @const
     *
     * @var string
     */
    public const STATUS_DISABLED='disabled';

    /**
     * User status pending.
     *
     * @const
     *
     * @var string
     */
    public const STATUS_PENDING='pending';

    /**
     * User status deleted.
     *
     * @const
     *
     * @var string
     */
    public const STATUS_DELETED='deleted';

    use TimeEntityTrait;

    /**
     * User identifier.
     *
     * @var int
     *
     * @OAS\Property(example=1)
     */
    protected $id;

    /**
     * Username.
     *
     * @var string
     *
     * @OAS\Property(example="username")
     */
    protected $username;

    /**
     * User email.
     *
     * @var string
     *
     * @OAS\Property(example="test@test.com", format="email")
     */
    protected $email;

    /**
     * Password as bcrypt hash.
     *
     * @var string
     *
     * @OAS\Property(readOnly=true, example="password-hash")
     */
    protected $password;

    /**
     * User roles with " " as delimiter.
     *
     * @var string
     *
     * @OAS\Property(example="ROLE_ADMIN|ROLE_MANAGER")
     */
    protected $roles;

    /**
     * First name.
     *
     * @var string
     *
     * @OAS\Property(example="John")
     */
    protected $first_name;

    /**
     * @var string
     *
     * @OAS\Property(example="Doe")
     */
    protected $last_name;

    /**
     * User avatar.
     *
     * @var string
     *
     * @OAS\Property(example="/path/to/avatar.jpg")
     */
    protected $avatar;

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
     * User status.
     * One of "pending", "active", "disabled" or "deleted".
     *
     * @var string
     *
     * @OAS\Property(
     *     example="pending",
     *     default="active",
     *     enum={"pending", "active", "disabled", "deleted"}
     * )
     */
    protected $status;

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
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function eraseCredentials()
    {
    }
}
