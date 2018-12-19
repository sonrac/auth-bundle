<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12/18/18
 * Time: 10:52 PM
 */

declare(strict_types=1);

namespace Sonrac\OAuth2\Adapter\Repository;

use League\OAuth2\Server\Entities\AuthCodeEntityInterface;

/**
 * Interface AuthCodeRepositoryInterface
 * @package Sonrac\OAuth2\Adapter\Repository
 */
interface AuthCodeRepositoryInterface
{
    /**
     * @param \League\OAuth2\Server\Entities\AuthCodeEntityInterface $authCodeEntity
     *
     * @return void
     *
     * @throws \League\OAuth2\Server\Exception\UniqueTokenIdentifierConstraintViolationException
     *
     * @see \League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface::persistNewAuthCode()
     */
    public function persistNewAuthCode(AuthCodeEntityInterface $authCodeEntity): void;

    /**
     * @param string|int $codeId
     *
     * @return void
     *
     * @see \League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface::revokeAuthCode()
     */
    public function revokeAuthCode($codeId): void;

    /**
     * @param string|int $codeId
     *
     * @return bool
     *
     * @see \League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface::isAuthCodeRevoked()
     */
    public function isAuthCodeRevoked($codeId): bool;
}
