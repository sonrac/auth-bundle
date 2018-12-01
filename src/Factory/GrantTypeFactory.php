<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12/1/18
 * Time: 8:09 PM
 */

namespace sonrac\Auth\Factory;

use League\OAuth2\Server\Grant\AuthCodeGrant;
use League\OAuth2\Server\Grant\ClientCredentialsGrant;
use League\OAuth2\Server\Grant\ImplicitGrant;
use League\OAuth2\Server\Grant\PasswordGrant;
use League\OAuth2\Server\Grant\RefreshTokenGrant;
use League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;

/**
 * Class GrantTypeFactory
 * @package sonrac\Auth\Factory
 */
class GrantTypeFactory
{
    /**
     * @param \League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface $authCodeRepository
     * @param \League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface $refreshTokenRepository
     * @param string $authCodeTTL
     * @param string $refreshTokenTTL
     *
     * @return \League\OAuth2\Server\Grant\AuthCodeGrant
     *
     * @throws \Exception
     */
    public static function createAuthCodeGrant(
        AuthCodeRepositoryInterface $authCodeRepository,
        RefreshTokenRepositoryInterface $refreshTokenRepository,
        string $authCodeTTL,
        string $refreshTokenTTL
    ): AuthCodeGrant {
        $authCodeGrant = new AuthCodeGrant(
            $authCodeRepository, $refreshTokenRepository, new \DateInterval($authCodeTTL)
        );

        $authCodeGrant->setRefreshTokenTTL(new \DateInterval($refreshTokenTTL));

        return $authCodeGrant;
    }

    /**
     * @param string $refreshTokenTTL
     *
     * @return \League\OAuth2\Server\Grant\ClientCredentialsGrant
     *
     * @throws \Exception
     */
    public static function createClientCredentialsGrant(string $refreshTokenTTL): ClientCredentialsGrant
    {
        $clientCredentialsGrant = new ClientCredentialsGrant();

        $clientCredentialsGrant->setRefreshTokenTTL(new \DateInterval($refreshTokenTTL));

        return $clientCredentialsGrant;
    }

    /**
     * @param string $accessTokenTTL
     * @param string $queryDelimiter
     *
     * @return \League\OAuth2\Server\Grant\ImplicitGrant
     *
     * @throws \Exception
     */
    public static function createImplicitGrant(string $accessTokenTTL, string $queryDelimiter = '#'): ImplicitGrant
    {
        $implicitGrant = new ImplicitGrant(new \DateInterval($accessTokenTTL), $queryDelimiter);

        return $implicitGrant;
    }

    /**
     * @param \League\OAuth2\Server\Repositories\UserRepositoryInterface $userRepository
     * @param \League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface $refreshTokenRepository
     * @param string $refreshTokenTTL
     *
     * @return \League\OAuth2\Server\Grant\PasswordGrant
     *
     * @throws \Exception
     */
    public static function createPasswordGrant(
        UserRepositoryInterface $userRepository,
        RefreshTokenRepositoryInterface $refreshTokenRepository,
        string $refreshTokenTTL
    ): PasswordGrant {
        $passwordGrant = new PasswordGrant(
            $userRepository, $refreshTokenRepository
        );

        $passwordGrant->setRefreshTokenTTL(new \DateInterval($refreshTokenTTL));

        return $passwordGrant;
    }

    /**
     * @param \League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface $refreshTokenRepository
     * @param string $refreshTokenTTL
     *
     * @return \League\OAuth2\Server\Grant\RefreshTokenGrant
     *
     * @throws \Exception
     */
    public static function createRefreshTokenGrant(
        RefreshTokenRepositoryInterface $refreshTokenRepository,
        string $refreshTokenTTL
    ): RefreshTokenGrant {
        $refreshTokenGrant = new RefreshTokenGrant($refreshTokenRepository);

        $refreshTokenGrant->setRefreshTokenTTL(new \DateInterval($refreshTokenTTL));

        return $refreshTokenGrant;
    }
}
