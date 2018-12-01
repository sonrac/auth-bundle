<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12/1/18
 * Time: 11:57 PM
 */

namespace sonrac\Auth\Configurator;

use League\OAuth2\Server\AuthorizationValidators\BearerTokenValidator;
use sonrac\Auth\Factory\SecureKeyFactory;

/**
 * Class BearerTokenValidatorConfigurator
 * @package sonrac\Auth\Configurator
 */
class BearerTokenValidatorConfigurator
{
    /**
     * @var \sonrac\Auth\Factory\SecureKeyFactory
     */
    private $keyFactory;

    /**
     * BearerTokenValidatorConfigurator constructor.
     * @param \sonrac\Auth\Factory\SecureKeyFactory $keyFactory
     */
    public function __construct(SecureKeyFactory $keyFactory)
    {
        $this->keyFactory = $keyFactory;
    }

    /**
     * @param \League\OAuth2\Server\AuthorizationValidators\BearerTokenValidator $validator
     *
     * @return \League\OAuth2\Server\AuthorizationValidators\BearerTokenValidator
     */
    public function configure(BearerTokenValidator $validator): BearerTokenValidator
    {
        $validator->setPublicKey($this->keyFactory->getPublicKey());
        $validator->setEncryptionKey($this->keyFactory->getEncryptionKey());

        return $validator;
    }
}
