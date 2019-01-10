<?php

declare(strict_types=1);

namespace Sonrac\OAuth2\Command;

use Doctrine\Bundle\DoctrineBundle\Command\DoctrineCommand;
use Sonrac\OAuth2\Adapter\Exception\NotUniqueClientIdentifierException;
use Sonrac\OAuth2\Adapter\Repository\ClientRepositoryInterface;
use Sonrac\OAuth2\Factory\GrantTypeFactory;
use Symfony\Component\Console\Exception\InvalidOptionException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class GenerateClientCommand
 * @package Sonrac\OAuth2\Command
 */
class GenerateClientCommand extends DoctrineCommand
{
    /**
     * @var \Sonrac\OAuth2\Adapter\Repository\ClientRepositoryInterface
     */
    private $clientRepository;

    /**
     * GenerateClientCommand constructor.
     * @param \Sonrac\OAuth2\Adapter\Repository\ClientRepositoryInterface $clientRepository
     * @param string|null $name
     */
    public function __construct(ClientRepositoryInterface $clientRepository, ?string $name = null)
    {
        parent::__construct($name);

        $this->clientRepository = $clientRepository;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    protected function configure(): void
    {
        $this->setName('sonrac_oauth:generate:client');

        $this->addOption(
            'name',
            'nm',
            InputOption::VALUE_REQUIRED,
            'Client application name'
        )->addOption(
            'identifier',
            'id',
            InputOption::VALUE_OPTIONAL,
            'Client application identifier',
            null
        )->addOption(
            'grant-types',
            'g',
            InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL,
            'Allowed grant types. By default include all available grant types',
            GrantTypeFactory::grantTypes()
        )->addOption(
            'redirect-uris',
            'r',
            InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL,
            'Allowed redirect uris. By default is empty'
        );
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getOption('name');

        $identifier = $input->getOption('identifier');

        if ('' === $identifier) {
            throw new InvalidOptionException('Option "identifier" can not be an empty string.');
        }

        $grantTypes = $input->getOption('grant-types');
        $grantTypes = \is_array($grantTypes)
            ? $grantTypes
            : (null === $grantTypes || '' === $grantTypes ? [] : [$grantTypes]);

        if (\count($grantTypes) !== \count(array_intersect(GrantTypeFactory::grantTypes(), $grantTypes))) {
            throw new InvalidOptionException('Option "grant-types" contains invalid value.');
        }

        $redirectUris = $input->getOption('redirect-uris');
        $redirectUris = \is_array($redirectUris) ? $redirectUris : [];

        $secret = $this->generateRandomString();

        try {
            $client = $this->clientRepository->createClientEntity(
                $name, $secret, $grantTypes, $redirectUris, $identifier, $input->getOptions()
            );
        } catch (NotUniqueClientIdentifierException $exception) {
            throw new InvalidOptionException('Option "identifier" is not unique.');
        }

        $output->writeln('Client successfully generated');
        $output->writeln(\sprintf('Client ID: %s', $client->getIdentifier()));
        $output->writeln(\sprintf('Client secret: %s', $client->getSecret()));
    }

    /**
     * Generate random secret key.
     *
     * @param int $length
     *
     * @return string
     */
    private function generateRandomString($length = 255): string
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ!@#$%^&*()<>?.,+=-_';
        $charactersLength = \mb_strlen($characters);
        $randomString = '';

        for ($i = 0; $i < $length; ++$i) {
            $randomString .= $characters[\rand(0, $charactersLength - 1)];
        }

        return $randomString;
    }
}
