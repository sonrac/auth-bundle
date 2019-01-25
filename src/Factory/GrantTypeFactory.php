<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12/1/18
 * Time: 8:09 PM
 */

declare(strict_types=1);

namespace Sonrac\OAuth2\Factory;

use League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use Sonrac\OAuth2\Bridge\Grant\AuthCodeGrant;
use Sonrac\OAuth2\Bridge\Grant\ClientCredentialsGrant;
use Sonrac\OAuth2\Bridge\Grant\ImplicitGrant;
use Sonrac\OAuth2\Bridge\Grant\PasswordGrant;
use Sonrac\OAuth2\Bridge\Grant\RefreshTokenGrant;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class GrantTypeFactory
 * @package Sonrac\OAuth2\Factory
 */
class GrantTypeFactory
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    private $container;

    /**
     * GrantTypeFactory constructor.
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param \DateInterval $authCodeTTL
     * @param \DateInterval $refreshTokenTTL
     *
     * @return \Sonrac\OAuth2\Bridge\Grant\AuthCodeGrant
     */
    public function createAuthCodeGrant(\DateInterval $authCodeTTL, \DateInterval $refreshTokenTTL): AuthCodeGrant
    {
        throw new \LogicException('Not supported');
        $authCodeGrant = new AuthCodeGrant(
            $this->container->get(AuthCodeRepositoryInterface::class),
            $this->container->get(RefreshTokenRepositoryInterface::class),
            $authCodeTTL
        );

        $authCodeGrant->setRefreshTokenTTL($refreshTokenTTL);

        return $authCodeGrant;
    }

    /**
     * @param \DateInterval $refreshTokenTTL
     *
     * @return \Sonrac\OAuth2\Bridge\Grant\ClientCredentialsGrant
     */
    public function createClientCredentialsGrant(\DateInterval $refreshTokenTTL): ClientCredentialsGrant
    {
        $clientCredentialsGrant = new ClientCredentialsGrant();

        $clientCredentialsGrant->setRefreshTokenTTL($refreshTokenTTL);

        return $clientCredentialsGrant;
    }

    /**
     * @param \DateInterval $accessTokenTTL
     * @param string $queryDelimiter
     *
     * @return \Sonrac\OAuth2\Bridge\Grant\ImplicitGrant
     */
    public function createImplicitGrant(\DateInterval $accessTokenTTL, string $queryDelimiter = '#'): ImplicitGrant
    {
        throw new \LogicException('Not supported');
        $implicitGrant = new ImplicitGrant($accessTokenTTL, $queryDelimiter);

        return $implicitGrant;
    }

    /**
     * @param \DateInterval $refreshTokenTTL
     *
     * @return \Sonrac\OAuth2\Bridge\Grant\PasswordGrant
     */
    public function createPasswordGrant(\DateInterval $refreshTokenTTL): PasswordGrant
    {
        $passwordGrant = new PasswordGrant(
            $this->container->get(UserRepositoryInterface::class),
            $this->container->get(RefreshTokenRepositoryInterface::class)
        );

        $passwordGrant->setRefreshTokenTTL($refreshTokenTTL);

        return $passwordGrant;
    }

    /**
     * @param \DateInterval $refreshTokenTTL
     *
     * @return \Sonrac\OAuth2\Bridge\Grant\RefreshTokenGrant
     */
    public function createRefreshTokenGrant(\DateInterval $refreshTokenTTL): RefreshTokenGrant
    {
        $refreshTokenGrant = new RefreshTokenGrant($this->container->get(RefreshTokenRepositoryInterface::class));

        $refreshTokenGrant->setRefreshTokenTTL($refreshTokenTTL);

        return $refreshTokenGrant;
    }

    /**
     * @return array
     */
    public static function grantTypes(): array
    {
        return [
            AuthCodeGrant::TYPE,
            ClientCredentialsGrant::TYPE,
            ImplicitGrant::TYPE,
            PasswordGrant::TYPE,
            RefreshTokenGrant::TYPE,
        ];
    }
}
