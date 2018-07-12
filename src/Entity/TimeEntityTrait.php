<?php

declare(strict_types=1);

namespace sonrac\Auth\Entity;

/**
 * Class TimeEntityTrait.
 *
 * Class property created_at and updated_at must be exists.
 */
trait TimeEntityTrait
{
    /**
     * Get created time.
     *
     * @return int
     */
    public function getCreatedAt(): int
    {
        return (int) ($this->{$this->getCreatedAtFieldName()});
    }

    /**
     * Get created at field name.
     *
     * @return string
     */
    protected function getCreatedAtFieldName(): string
    {
        return 'created_at';
    }

    /**
     * Set created time.
     *
     * @param int|string $created_at
     */
    public function setCreatedAt($created_at): void
    {
        $this->{$this->getCreatedAtFieldName()} = (int) $created_at;
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
     * Get updated at field name.
     *
     * @return string
     */
    protected function getUpdatedAtFieldName(): string
    {
        return 'updated_at';
    }

    /**
     * Set updated time.
     *
     * @param int|string $updated_at
     */
    public function setUpdatedAt($updated_at): void
    {
        $this->{$this->getUpdatedAtFieldName()} = (int) $updated_at;
    }
}
