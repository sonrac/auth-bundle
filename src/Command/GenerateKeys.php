<?php

declare(strict_types=1);

namespace sonrac\Auth\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class GenerateKeys
 * Generate oauth2 server keys.
 */
class GenerateKeys extends ContainerAwareCommand
{
    /**
     * Disable output.
     *
     * @var bool
     */
    private $disableOut = false;

    /**
     * Execute command.
     *
     * @param \Symfony\Component\Console\Input\InputInterface   $input
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
            $phrase = \getenv('SERVER_PASS_PHRASE') ?: $this->getContainer()->getParameter('sonrac_auth.pass_phrase');
        }

        $keyPath = $this->getContainer()->getParameter('sonrac_auth.private_key_path');
        $privateName = $this->getContainer()->getParameter('sonrac_auth.private_key_name');
        $pubName = $this->getContainer()->getParameter('sonrac_auth.public_key_name');

        if ($force || !\file_exists($keyPath.'/'.$privateName)) {
            if (!\is_dir($keyPath) && !@\mkdir($keyPath, 0755, true)) {
                throw new \RuntimeException("Error create path {{$keyPath}}. Check folder permission");
            }
            $this->generatePrivateKey($keyPath.'/'.$privateName, $phrase);
            $this->generatePublicKey($keyPath.'/'.$pubName, $keyPath.'/'.$privateName, $phrase);

            foreach ([$privateName, $pubName] as $file) {
                \chmod($keyPath.'/'.$file, 0660);
            }

            $output->writeln('Keys generated in: '.$keyPath);
        }

        // CryptKey class from League OAuth also checks a permission key folder
        \chmod($keyPath, 0660);
    }

    /**
     * Generate private outh2 key.
     *
     * @param string      $keyPath
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
     * @param string      $keyPath
     * @param string      $privateKeyPath
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

    /**
     * {@inheritdoc}
     *
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    protected function configure(): void
    {
        $this->setName('sonrac_auth:generate:keys')
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
}
