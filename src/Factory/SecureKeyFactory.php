<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12/1/18
 * Time: 11:46 PM
 */

namespace sonrac\Auth\Factory;

use League\OAuth2\Server\CryptKey;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class SecureKeyFactory
 * @package sonrac\Auth\Factory
 */
class SecureKeyFactory
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    private $container;

    /**
     * @var \League\OAuth2\Server\CryptKey|null
     */
    private $privateKey;

    /**
     * @var \League\OAuth2\Server\CryptKey|null
     */
    private $publicKey;

    /**
     * SecureKeyFactory constructor.
     * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @return \League\OAuth2\Server\CryptKey
     */
    public function getPrivateKey(): CryptKey
    {
        if (null !== $this->privateKey) {
            return $this->privateKey;
        }


        $path = $this->container->getParameter('sonrac_auth.private_key_path')
            . DIRECTORY_SEPARATOR
            . $this->container->getParameter('sonrac_auth.private_key_name');

        $this->privateKey = new CryptKey(
            $path, $this->container->getParameter('sonrac_auth.pass_phrase')
        );

        return $this->privateKey;
    }

    /**
     * @return \League\OAuth2\Server\CryptKey
     */
    public function getPublicKey(): CryptKey
    {
        if (null !== $this->publicKey) {
            return $this->publicKey;
        }


        $path = $this->container->getParameter('sonrac_auth.private_key_path')
            . DIRECTORY_SEPARATOR
            . $this->container->getParameter('sonrac_auth.public_key_name');

        $this->publicKey = new CryptKey(
            $path, $this->container->getParameter('sonrac_auth.pass_phrase')
        );

        return $this->publicKey;
    }

    /**
     * @return \Defuse\Crypto\Key
     */
    public function getEncryptionKey(): string
    {
        return $this->container->getParameter('sonrac_auth.encryption_key');
    }
}
