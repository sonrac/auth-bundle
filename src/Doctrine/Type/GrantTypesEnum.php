<?php

declare(strict_types=1);

namespace Sonrac\OAuth2\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Sonrac\OAuth2\Factory\GrantTypeFactory;

/**
 * Class GrantTypesEnum
 * @package Sonrac\OAuth2\Doctrine\Type
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

        foreach (GrantTypeFactory::grantTypes() as $status) {
            $statuses .= (\mb_strlen($statuses) ? ', ' : '') . "'{$status}'";
        }

        return "ENUM({$statuses})";
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
        if (!\in_array($value, GrantTypeFactory::grantTypes(), true)) {
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
