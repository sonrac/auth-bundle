<?php

declare(strict_types=1);

namespace Sonrac\OAuth2\Entity;

use Sonrac\OAuth2\Adapter\Entity\ScopeEntityInterface;

/**
 * Class Scope
 * @package Sonrac\OAuth2\Entity
 */
class Scope implements ScopeEntityInterface
{
    use TimeEntityTrait;

    /**
     * Scope identifier.
     *
     * @var string|null
     */
    protected $id;

    /**
     * Scope title.
     *
     * @var string|null
     */
    protected $title;

    /**
     * Scope description.
     *
     * @var string|null
     */
    protected $description;

    /**
     * Scope permissions.
     *
     * @var array|null
     */
    protected $permissions;

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
    public function getIdentifier(): string
    {
        return $this->id;
    }

    /**
     * Get scope identifier
     *
     * @return string|null
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * Set scope identifier.
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
     * Get scope title.
     *
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Set scope title.
     *
     * @param string $title
     *
     * @return void
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * Get scope description.
     *
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Set scope description.
     *
     * @param string $description
     *
     * @return void
     */
    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * Get permissions.
     *
     * @return array
     */
    public function getPermissions(): array
    {
        return null !== $this->permissions ? $this->permissions : [];
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
