<?php

declare(strict_types=1);

namespace Sonrac\OAuth2\Entity;

/**
 * Class TimeEntityTrait.
 *
 * Class property createdAt and updatedAt must be exists.
 */
trait TimeEntityTrait
{
    /**
     * Get created time.
     *
     * @return int
     */
    public function getCreatedAt(): ?int
    {
        return $this->{$this->getCreatedAtFieldName()};
    }

    /**
     * Set created time.
     *
     * @param int $createdAt
     */
    public function setCreatedAt(int $createdAt): void
    {
        $this->{$this->getCreatedAtFieldName()} = $createdAt;
    }

    /**
     * Get updated time.
     *
     * @return int|null
     */
    public function getUpdatedAt(): ?int
    {
        return $this->{$this->getUpdatedAtFieldName()};
    }

    /**
     * Set updated time.
     *
     * @param int $updatedAt
     */
    public function setUpdatedAt(int $updatedAt): void
    {
        $this->{$this->getUpdatedAtFieldName()} = $updatedAt;
    }

    /**
     * Get created at field name.
     *
     * @return string
     */
    protected function getCreatedAtFieldName(): string
    {
        return 'createdAt';
    }

    /**
     * Get updated at field name.
     *
     * @return string
     */
    protected function getUpdatedAtFieldName(): string
    {
        return 'updatedAt';
    }
}
