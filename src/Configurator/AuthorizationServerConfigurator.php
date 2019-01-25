<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12/1/18
 * Time: 9:53 PM.
 */

declare(strict_types=1);

namespace Sonrac\OAuth2\Configurator;

use League\OAuth2\Server\AuthorizationServer;
use Sonrac\OAuth2\Bridge\Grant\AuthCodeGrant;
use Sonrac\OAuth2\Bridge\Grant\ClientCredentialsGrant;
use Sonrac\OAuth2\Bridge\Grant\ImplicitGrant;
use Sonrac\OAuth2\Bridge\Grant\PasswordGrant;
use Sonrac\OAuth2\Bridge\Grant\RefreshTokenGrant;
use Sonrac\OAuth2\Factory\GrantTypeFactory;

/**
 * Class AuthorizationServerConfigurator.
 */
class AuthorizationServerConfigurator
{
    /**
     * @var \Sonrac\OAuth2\Factory\GrantTypeFactory
     */
    private $grantTypeFactory;

    /**
     * @var \DateInterval
     */
    private $authCodeTTL;

    /**
     * @var \DateInterval
     */
    private $accessTokenTTL;

    /**
     * @var \DateInterval
     */
    private $refreshTokenTTL;

    /**
     * @var string[]
     */
    private $grantTypes = [];

    /**
     * AuthorizationServerConfigurator constructor.
     *
     * @param \Sonrac\OAuth2\Factory\GrantTypeFactory $grantTypeFactory
     * @param string                                  $authCodeTTL
     * @param string                                  $accessTokenTTL
     * @param string                                  $refreshTokenTTL
     *
     * @throws \Exception
     */
    public function __construct(
        GrantTypeFactory $grantTypeFactory,
        string $authCodeTTL,
        string $accessTokenTTL,
        string $refreshTokenTTL
    ) {
        $this->grantTypeFactory = $grantTypeFactory;
        $this->authCodeTTL      = new \DateInterval($authCodeTTL);
        $this->accessTokenTTL   = new \DateInterval($accessTokenTTL);
        $this->refreshTokenTTL  = new \DateInterval($refreshTokenTTL);
    }

    /**
     * @param string $grantType
     */
    public function enableGrantType(string $grantType): void
    {
        $this->grantTypes[] = $grantType;
    }

    /**
     * @param \League\OAuth2\Server\AuthorizationServer $authorizationServer
     *
     * @return \League\OAuth2\Server\AuthorizationServer
     */
    public function configure(AuthorizationServer $authorizationServer): AuthorizationServer
    {
        foreach ($this->grantTypes as $grantType) {
            switch ($grantType) {
                case AuthCodeGrant::TYPE:
                    $grantType = $this->grantTypeFactory
                        ->createAuthCodeGrant($this->authCodeTTL, $this->refreshTokenTTL);
                    break;
                case ClientCredentialsGrant::TYPE:
                    $grantType = $this->grantTypeFactory->createClientCredentialsGrant($this->refreshTokenTTL);
                    break;
                case ImplicitGrant::TYPE:
                    $grantType = $this->grantTypeFactory->createImplicitGrant($this->accessTokenTTL);
                    break;
                case PasswordGrant::TYPE:
                    $grantType = $this->grantTypeFactory->createPasswordGrant($this->refreshTokenTTL);
                    break;
                case RefreshTokenGrant::TYPE:
                    $grantType = $this->grantTypeFactory->createRefreshTokenGrant($this->refreshTokenTTL);
                    break;
                default:
                    continue 2;
            }

            $authorizationServer->enableGrantType($grantType, $this->accessTokenTTL);
        }

        return $authorizationServer;
    }
}
