<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12/1/18
 * Time: 11:57 PM
 */

declare(strict_types=1);

namespace Sonrac\OAuth2\Configurator;

use League\OAuth2\Server\AuthorizationValidators\BearerTokenValidator;
use Sonrac\OAuth2\Factory\SecureKeyFactory;

/**
 * Class BearerTokenValidatorConfigurator
 * @package Sonrac\OAuth2\Configurator
 */
class BearerTokenValidatorConfigurator
{
    /**
     * @var \Sonrac\OAuth2\Factory\SecureKeyFactory
     */
    private $keyFactory;

    /**
     * BearerTokenValidatorConfigurator constructor.
     * @param \Sonrac\OAuth2\Factory\SecureKeyFactory $keyFactory
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
