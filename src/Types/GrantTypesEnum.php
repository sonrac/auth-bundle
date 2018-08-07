<?php

declare(strict_types=1);

namespace sonrac\Auth\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use sonrac\Auth\Entity\Client;

/**
 * Class GrantTypesEnum.
 */
class GrantTypesEnum extends Type
{
    /**
     * Type name.
     *
     * @const
     *
     * @var string
     */
    public const ENUM_GRANT_TYPE = 'enum_grant_type';

    /**
     * {@inheritdoc}
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform): string
    {
        $statuses = '';

        foreach (self::getStatuses() as $status) {
            $statuses .= (\mb_strlen($statuses) ? ', ' : '')."'{$status}'";
        }

        return "ENUM({$statuses})";
    }

    /**
     * Get statuses list.
     *
     * @return array
     */
    public static function getStatuses(): array
    {
        return [
            Client::GRANT_AUTH_CODE,
            Client::GRANT_CLIENT_CREDENTIALS,
            Client::GRANT_IMPLICIT,
            Client::GRANT_PASSWORD,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return $value;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \InvalidArgumentException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (!\in_array($value, self::getStatuses(), true)) {
            throw new \InvalidArgumentException('Invalid status');
        }

        return $value;
    }

    /**
     * {@inheritdoc}
     */
    public function getName(): string
    {
        return self::ENUM_GRANT_TYPE;
    }

    /**
     * {@inheritdoc}
     */
    public function requiresSQLCommentHint(AbstractPlatform $platform): bool
    {
        return true;
    }
}
