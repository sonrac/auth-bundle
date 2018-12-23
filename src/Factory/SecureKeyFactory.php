<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 12/1/18
 * Time: 11:46 PM
 */

declare(strict_types=1);

namespace Sonrac\OAuth2\Factory;

use League\OAuth2\Server\CryptKey;

/**
 * Class SecureKeyFactory
 * @package Sonrac\OAuth2\Factory
 */
class SecureKeyFactory
{
    /**
     * @var string
     */
    private $encryptionKey;

    /**
     * @var string
     */
    private $keyPath;

    /**
     * @var string
     */
    private $privateKeyName;

    /**
     * @var string
     */
    private $publicKeyName;

    /**
     * @var string|null
     */
    private $passPhrase;

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
     * @param string $encryptionKey
     * @param string $keyPath
     * @param string $privateKeyName
     * @param string $publicKeyName
     * @param string|null $passPhrase
     */
    public function __construct(
        string $encryptionKey,
        string $keyPath,
        string $privateKeyName,
        string $publicKeyName,
        ?string $passPhrase = null
    ) {
        $this->encryptionKey = $encryptionKey;
        $this->keyPath = $keyPath;
        $this->privateKeyName = $privateKeyName;
        $this->publicKeyName = $publicKeyName;
        $this->passPhrase = $passPhrase;
    }

    /**
     * @return \Defuse\Crypto\Key
     */
    public function getEncryptionKey(): string
    {
        return $this->encryptionKey;
    }

    /**
     * @return \League\OAuth2\Server\CryptKey
     */
    public function getPrivateKey(): CryptKey
    {
        if (null !== $this->privateKey) {
            return $this->privateKey;
        }

        $this->privateKey = new CryptKey($this->getPrivateKeyPath(), $this->passPhrase);

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

        $this->publicKey = new CryptKey($this->getPublicKeyPath(), $this->passPhrase);

        return $this->publicKey;
    }

    /**
     * @return string
     */
    public function getKeysPath(): string
    {
        return $this->keyPath;
    }

    /**
     * @return string
     */
    public function getPrivateKeyPath(): string
    {
        return $this->keyPath . DIRECTORY_SEPARATOR . $this->privateKeyName;
    }

    /**
     * @return string
     */
    public function getPublicKeyPath(): string
    {
        return $this->keyPath . DIRECTORY_SEPARATOR . $this->publicKeyName;
    }

    /**
     * @return string|null
     */
    public function getPassPhrase(): ?string
    {
        return $this->passPhrase;
    }
}
