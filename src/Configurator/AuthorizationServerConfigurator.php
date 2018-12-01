<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12/1/18
 * Time: 9:53 PM
 */

namespace sonrac\Auth\Configurator;

use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\Grant\GrantTypeInterface;

/**
 * Class AuthorizationServerConfigurator
 * @package sonrac\Auth\Configurator
 */
class AuthorizationServerConfigurator
{
    /**
     * @var \DateInterval
     */
    private $accessTokenTTL;

    /**
     * @var \League\OAuth2\Server\Grant\GrantTypeInterface[]
     */
    private $grantTypes = [];

    /**
     * AuthorizationServerConfigurator constructor.
     * @param string $accessTokenTTL
     *
     * @throws \Exception
     */
    public function __construct(string $accessTokenTTL)
    {
        $this->accessTokenTTL = new \DateInterval($accessTokenTTL);
    }

    /**
     * @param \League\OAuth2\Server\Grant\GrantTypeInterface $grantType
     *
     * @return void
     */
    public function registerGrantType(GrantTypeInterface $grantType): void
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
            $authorizationServer->enableGrantType($grantType, $this->accessTokenTTL);
        }

        return $authorizationServer;
    }
}
