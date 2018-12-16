<?php

declare(strict_types=1);

namespace Sonrac\OAuth2\Doctrine\Type;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Sonrac\OAuth2\Bridge\Grant\AuthCodeGrant;
use Sonrac\OAuth2\Bridge\Grant\ClientCredentialsGrant;
use Sonrac\OAuth2\Bridge\Grant\ImplicitGrant;
use Sonrac\OAuth2\Bridge\Grant\PasswordGrant;
use Sonrac\OAuth2\Bridge\Grant\RefreshTokenGrant;

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

        foreach (self::getStatuses() as $status) {
            $statuses .= (\mb_strlen($statuses) ? ', ' : '') . "'{$status}'";
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
            AuthCodeGrant::TYPE,
            ClientCredentialsGrant::TYPE,
            ImplicitGrant::TYPE,
            PasswordGrant::TYPE,
            RefreshTokenGrant::TYPE,
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
