<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12/3/18
 * Time: 9:47 PM
 */

declare(strict_types=1);

namespace Sonrac\OAuth2\Factory;

use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;
use League\OAuth2\Server\ResponseTypes\ResponseTypeInterface;

/**
 * Class AuthorizationServerFactory
 * @package Sonrac\OAuth2\Factory
 */
class AuthorizationServerFactory
{
    /**
     * @param \League\OAuth2\Server\Repositories\ClientRepositoryInterface $clientRepository
     * @param \League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface $accessTokenRepository
     * @param \League\OAuth2\Server\Repositories\ScopeRepositoryInterface $scopeRepository
     * @param \Sonrac\OAuth2\Factory\SecureKeyFactory $secureKeyFactory
     * @param \League\OAuth2\Server\ResponseTypes\ResponseTypeInterface|null $responseType
     *
     * @return \League\OAuth2\Server\AuthorizationServer
     */
    public static function createServer(
        ClientRepositoryInterface $clientRepository,
        AccessTokenRepositoryInterface $accessTokenRepository,
        ScopeRepositoryInterface $scopeRepository,
        SecureKeyFactory $secureKeyFactory,
        ?ResponseTypeInterface $responseType = null
    ): AuthorizationServer {
        $authorizationServer = new AuthorizationServer(
            $clientRepository,
            $accessTokenRepository,
            $scopeRepository,
            $secureKeyFactory->getPrivateKey(),
            $secureKeyFactory->getEncryptionKey(),
            $responseType
        );

        return $authorizationServer;
    }
}
