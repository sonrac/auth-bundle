<?php

declare(strict_types=1);

namespace Sonrac\OAuth2\Command;

use Sonrac\OAuth2\Factory\SecureKeyFactory;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class GenerateKeysCommand.
 */
class GenerateKeysCommand extends ContainerAwareCommand
{
    /**
     * @var \Sonrac\OAuth2\Factory\SecureKeyFactory
     */
    private $secureKeyFactory;

    /**
     * GenerateKeysCommand constructor.
     *
     * @param \Sonrac\OAuth2\Factory\SecureKeyFactory $secureKeyFactory
     * @param string|null                             $name
     */
    public function __construct(
        SecureKeyFactory $secureKeyFactory,
        ?string $name = null
    )
    {
        parent::__construct($name);

        $this->secureKeyFactory = $secureKeyFactory;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    protected function configure(): void
    {
        $this->setName('sonrac_oauth:generate:keys')
             ->setDescription('Generate oauth2 server keys')
             ->addOption(
                 'force',
                 'f',
                 InputOption::VALUE_OPTIONAL,
                 'Force regenerate keys',
                 false
             )->addOption(
                'bits',
                'b',
                InputOption::VALUE_OPTIONAL,
                'Number of bits in private key',
                4096
            )->addOption(
                'digest-algorithm',
                'digest',
                InputOption::VALUE_OPTIONAL,
                'Digest algorithm for private key',
                'sha512'
            )->addOption(
                'passphrase',
                'p',
                InputOption::VALUE_OPTIONAL,
                'Pass phrase for private key. By default is empty',
                null
            );
    }

    /**
     * {@inheritdoc}
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $force = false !== $input->getOption('force');
        $bits = $input->getOption('bits');
        $digestAlgorithm = $input->getOption('digest-algorithm');
        $passPhrase = $input->getOption('passphrase');

        if (null === $passPhrase || '' === $passPhrase) {
            $passPhrase = $this->secureKeyFactory->getPassPhrase();
        }

        $keyPath = $this->secureKeyFactory->getKeysPath();
        $privateKeyPath = $this->secureKeyFactory->getPrivateKeyPath();
        $publicKeyPath = $this->secureKeyFactory->getPublicKeyPath();

        if ((\file_exists($privateKeyPath) && \file_exists($publicKeyPath)) && false === $force) {
            throw new \RuntimeException('Key pair is already generated.');
        }

        if (false === \is_dir($keyPath) && false === @\mkdir($keyPath, 0755, true)) {
            throw new \RuntimeException(\sprintf('Error create path {%s}. Check folder permission', $keyPath));
        }

        [$privateKey, $publicKey] = $this->generateKeys((int)$bits, $digestAlgorithm, $passPhrase);

        $this->saveKeys($privateKey, $publicKey);

        $this->fixPermissions();

        $output->writeln(\sprintf('Keys generated in: %s', $keyPath));
    }

    /**
     * @param int         $bits
     * @param string      $digestAlgorithm
     * @param string|null $passPhrase
     *
     * @return array
     */
    private function generateKeys(int $bits, string $digestAlgorithm, ?string $passPhrase = null): array
    {
        $config = [
            'private_key_bits' => $bits,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
            'digest_alg'       => $digestAlgorithm,
        ];

        $keyResource = \openssl_pkey_new($config);

        if (false === $keyResource) {
            throw new \LogicException(\sprintf('Error generate key: {%s}', \openssl_error_string()));
        }

        $privateKey = null;

        if (false === \openssl_pkey_export($keyResource, $privateKey, $passPhrase)) {
            throw new \LogicException(\sprintf('Error generate key: {%s}', \openssl_error_string()));
        }

        $details = \openssl_pkey_get_details($keyResource);

        if (false === $details) {
            throw new \LogicException(\sprintf('Error generate key: {%s}', \openssl_error_string()));
        }

        \openssl_free_key($keyResource);

        return [$privateKey, $details['key']];
    }

    /**
     * @param string $privateKey
     * @param string $publicKey
     */
    private function saveKeys(string $privateKey, string $publicKey): void
    {
        if (false === \file_put_contents($this->secureKeyFactory->getPrivateKeyPath(), $privateKey)) {
            throw new \LogicException(\sprintf('Error generate key: {%s}', \error_get_last()['message']));
        }

        if (false === \file_put_contents($this->secureKeyFactory->getPublicKeyPath(), $publicKey)) {
            throw new \LogicException(\sprintf('Error generate key: {%s}', \error_get_last()['message']));
        }
    }

    private function fixPermissions(): void
    {
        \chmod($this->secureKeyFactory->getPrivateKeyPath(), 0600);
        \chmod($this->secureKeyFactory->getPublicKeyPath(), 0660);
        // CryptKey class from League OAuth dependencies also checks a permission key folder
        if (\chmod($this->secureKeyFactory->getKeysPath(), 0755)) { // Fix bad permission owner
            // With permission 0600 directory does not readable for script
            \chmod($this->secureKeyFactory->getKeysPath(), 0700);
        }
    }
}
