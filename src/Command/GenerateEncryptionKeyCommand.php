<?php

declare(strict_types=1);

namespace Sonrac\OAuth2\Command;

use Defuse\Crypto\Key;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class GenerateEncryptionKeyCommand
 * @package Sonrac\OAuth2\Command
 *
 * Generate oauth2 server keys.
 */
class GenerateEncryptionKeyCommand extends Command
{
    /**
     * {@inheritdoc}
     *
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    protected function configure(): void
    {
        $this->setName('sonrac_oauth:generate:keys:encryption')
            ->setDescription('Generate oauth2 server encryption key');
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function execute(InputInterface $input, OutputInterface $output)
    {
        $key = \base64_encode(\random_bytes(Key::KEY_BYTE_SIZE));

        $output->writeln(\sprintf('Your encryption key is: "%s"', $key));
    }
}
