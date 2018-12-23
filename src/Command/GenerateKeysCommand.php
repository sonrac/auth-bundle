<?php

declare(strict_types=1);

namespace Sonrac\OAuth2\Command;

use Sonrac\OAuth2\Factory\SecureKeyFactory;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class GenerateKeysCommand
 * @package Sonrac\OAuth2\Command
 *
 * Generate oauth2 server keys.
 */
class GenerateKeysCommand extends ContainerAwareCommand
{
    /**
     * @var \Sonrac\OAuth2\Factory\SecureKeyFactory
     */
    private $secureKeyFactory;

    /**
     * Disable output.
     *
     * @var bool
     */
    private $disableOut = false;

    /**
     * GenerateKeysCommand constructor.
     * @param \Sonrac\OAuth2\Factory\SecureKeyFactory $secureKeyFactory
     * @param string|null $name
     */
    public function __construct(
        SecureKeyFactory $secureKeyFactory,
        ?string $name = null
    ) {
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
                'disable-out',
                'do',
                InputOption::VALUE_OPTIONAL,
                'Disable output in console. By default is set',
                false
            )->addOption(
                'passphrase',
                'p',
                InputOption::VALUE_OPTIONAL,
                'Pass phrase for private key. By default is empty',
                null
            );
    }

    /**
     * Execute command.
     *
     * @param \Symfony\Component\Console\Input\InputInterface $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     * @throws \Symfony\Component\DependencyInjection\Exception\InvalidArgumentException
     * @throws \LogicException
     * @throws \RuntimeException
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $force = false !== $input->getOption('force');
        $phrase = $input->getOption('passphrase');
        $this->disableOut = false !== $input->getOption('disable-out');

        if (!$phrase) {
            $phrase = \getenv('SERVER_PASS_PHRASE') ?: $this->secureKeyFactory->getPassPhrase();
        }

        if ($force || !\file_exists($this->secureKeyFactory->getPrivateKeyPath())) {
            if (!\is_dir($this->secureKeyFactory->getKeysPath()) && !@\mkdir($this->secureKeyFactory->getKeysPath(), 0755, true)) {
                throw new \RuntimeException(
                    sprintf("Error create path {%s}. Check folder permission", $this->secureKeyFactory->getKeysPath())
                );
            }
            $this->generatePrivateKey($this->secureKeyFactory->getPrivateKeyPath(), $phrase);
            $this->generatePublicKey(
                $this->secureKeyFactory->getPublicKeyPath(),
                $this->secureKeyFactory->getPrivateKeyPath(),
                $phrase
            );

            foreach ([$this->secureKeyFactory->getPrivateKeyPath(), $this->secureKeyFactory->getPublicKeyPath()] as $file) {
                \chmod($file, 0660);
            }

            // CryptKey class from League OAuth also checks a permission key folder
            \chmod($this->secureKeyFactory->getKeysPath(), 0660);

            $output->writeln('Keys generated in: ' . $this->secureKeyFactory->getKeysPath());
        }
    }

    /**
     * Generate private oauth2 key.
     *
     * @param string $keyPath
     * @param null|string $phrase Secret phrase
     *
     * @author Donii Sergii <doniysa@gmail.com>
     */
    protected function generatePrivateKey(string $keyPath, $phrase = null): void
    {
        $command = 'openssl genrsa ';

        if ($phrase) {
            $command .= " -passout pass:$phrase";
        }
        $command .= " -out {$keyPath}";

        if ($this->disableOut) {
            $command .= ' 2> /dev/null';
        }

        \exec($command, $out, $res);
    }

    /**
     * Generate public oauth2 server key.
     *
     * @param string $keyPath
     * @param string $privateKeyPath
     * @param string|null $phrase Secret phrase
     */
    protected function generatePublicKey(string $keyPath, string $privateKeyPath, $phrase = null): void
    {
        $command = "openssl rsa -in {$privateKeyPath}";
        if ($phrase) {
            $command .= " -passin pass:$phrase";
        }
        $command .= " -pubout -out {$keyPath} ";

        if ($this->disableOut) {
            $command .= ' 2> /dev/null';
        }

        \exec($command, $out, $res);

    }
}
