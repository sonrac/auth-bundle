<?php

namespace sonrac\Auth\Entity;

/**
 * Trait ExpiryTimeTrait.
 */
trait ExpiryTimeTrait
{
    /**
     * {@inheritdoc}
     */
    public function getExpiryDateTimeAsInt()
    {
        if ($this->{$this->getExpireAtFieldName()} instanceof \DateTime) {
            return $this->{$this->getExpireAtFieldName()};
        }

        if (\is_numeric($this->{$this->getExpireAtFieldName()})) {
            return (new \DateTime())->setTimestamp($this->{$this->getExpireAtFieldName()});
        }

        return null;
    }

    public function getExpireAtFieldName(): string
    {
        return 'expire_at';
    }

    /**
     * {@inheritdoc}
     */
    public function setExpiryDateTimeAsInt(\DateTime $dateTime): void
    {
        $this->{$this->getExpireAtFieldName()} = $dateTime->getTimestamp();
    }
}
