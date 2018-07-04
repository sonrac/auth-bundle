<?php

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
     * Execute command.
     *
     * @param \Symfony\Component\Console\Input\InputInterface   $input
     * @param \Symfony\Component\Console\Output\OutputInterface $output
     *
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     * @throws \Symfony\Component\DependencyInjection\Exception\InvalidArgumentException
     * @throws \LogicException
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $force = false !== $input->getOption('force');
        $phrase = $input->getOption('passphrase');
        $disableOut = false !== $input->getOption('disable-out');

        if (!$phrase) {
            $phrase = \getenv('SERVER_PASS_PHRASE') ?: $this->getContainer()->getParameter('sonrac_auth.pass_phrase');
        }

        if ($disableOut) {
            \ob_start();
        }

        $keyPath = $this->getContainer()->getParameter('sonrac_auth.private_key_path');
        $privateName = $this->getContainer()->getParameter('sonrac_auth.private_key_name');
        $pubName = $this->getContainer()->getParameter('sonrac_auth.public_key_name');

        if ($force || !\file_exists($keyPath.'/'.$privateName)) {
            $this->generatePrivateKey($keyPath.'/'.$privateName, $phrase);
            $this->generatePublicKey($keyPath.'/'.$pubName, $keyPath.'/'.$privateName, $phrase);

            foreach ([$privateName, $pubName] as $file) {
                \chmod($keyPath.'/'.$file, 0660);
            }

            $output->writeln('Keys generated in: '.$keyPath);
        }
        if ($disableOut) {
            \ob_clean();
        }
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
        \exec($command);
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
        $command .= " -pubout -out {$keyPath}";
        \exec($command);
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
                InputOption::VALUE_OPTIONAL | InputOption::VALUE_NONE,
                'Force regenerate keys'
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
