<?php

namespace sonrac\Auth\Entity;

use League\OAuth2\Server\Entities\ScopeEntityInterface;
use Swagger\Annotations as OAS;

/**
 * Class Scope.
 * Scope entity.
 *
 * @OAS\Schema(
 *     title="Scope",
 *     description="Scope entity"
 * )
 */
class Scope implements ScopeEntityInterface
{
    use TimeEntityTrait;

    /**
     * Scope name.
     *
     * @var string
     *
     * @OAS\Property(example=1, uniqueItems=true)
     */
    protected $scope;

    /**
     * Scope description.
     *
     * @var string
     *
     * @OAS\Property(
     *     example="Client scope description",
     *     format="text"
     * )
     */
    protected $description;

    /**
     * Scope description.
     *
     * @var string
     *
     * @OAS\Property(
     *     example="Client scope title"
     * )
     */
    protected $title;

    /**
     * Scope permissions.
     *
     * @var array
     *
     * @OAS\Property(
     *     example={"permission1", "permission2"},
     *     @OAS\Items(
     *         type="string"
     *     )
     * )
     */
    protected $permissions;

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
     * Set scope identifier.
     *
     * @param string $identifier
     */
    public function setIdentifier(string $identifier): void
    {
        $this->scope = $identifier;
    }

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return [$this->getIdentifier()];
    }

    /**
     * {@inheritdoc}
     */
    public function getIdentifier(): string
    {
        return $this->scope;
    }

    /**
     * Get scope description.
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Set scope description.
     *
     * @param string $description
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * Get scope title.
     *
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title ?? '';
    }

    /**
     * Set scope title.
     *
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * Get permissions.
     *
     * @return array
     */
    public function getPermissions(): array
    {
        if (\is_string($this->permissions)) {
            $this->permissions = \explode('|', $this->permissions);
        }

        return $this->permissions;
    }

    /**
     * Set scope permissions.
     *
     * @param array $permissions
     */
    public function setPermissions(array $permissions): void
    {
        $this->permissions = $permissions;
    }
}
