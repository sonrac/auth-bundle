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
    public function getExpiryDateTime()
    {
        if ($this->{$this->getExpireAtFieldName()} instanceof \DateTime) {
            return $this->{$this->getExpireAtFieldName()};
        }

        if (\is_numeric($this->{$this->getExpireAtFieldName()})) {
            return (new \DateTime())->setTimestamp($this->{$this->getExpireAtFieldName()});
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function setExpiryDateTime(\DateTime $dateTime): void
    {
        $this->{$this->getExpireAtFieldName()} = $dateTime->getTimestamp();
    }

    public function getExpireAtFieldName(): string
    {
        return 'expire_at';
    }
}
