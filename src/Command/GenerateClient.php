<?php

namespace sonrac\Auth\Command;

use Doctrine\Bundle\DoctrineBundle\Command\DoctrineCommand;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use sonrac\Auth\Entity\Client as ClientEntity;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class Client.
 */
class GenerateClient extends DoctrineCommand
{
    /**
     * {@inheritdoc}
     *
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    protected function configure(): void
    {
        $this->setName('sonrac_auth:generate:client');

        $this->addOption(
            'name',
            'm',
            InputOption::VALUE_REQUIRED,
            'Client application name'
        )->addOption(
            'description',
            'd',
            InputOption::VALUE_OPTIONAL,
            'Client application description',
            'Created by console command'
        )->addOption(
            'grant-types',
            'g',
            InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL,
            'Allowed grant types. By default include all implement grant types',
            [
                ClientEntity::GRANT_CLIENT_CREDENTIALS,
                ClientEntity::GRANT_PASSWORD,
                ClientEntity::GRANT_AUTH_CODE,
                ClientEntity::GRANT_IMPLICIT,
            ]
        );
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\ORMInvalidArgumentException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getOption('name');
        $grantTypes = $input->getOption('grant-types');
        $description = $input->getOption('description');
        $secret = $this->generateRandomString();
        /** @var \sonrac\Auth\Entity\Client $entity */
        $entity = $this->getContainer()->get(ClientEntityInterface::class);
        $entity->setName($name);
        $entity->setAllowedGrantTypes($grantTypes);
        $entity->setSecret($secret);
        $entity->setCreatedAt(\time());
        $entity->setDescription($description ?? '');

        $entity->preparePersist();
        $em = $this->getEntityManager('default');
        $em->persist($entity);
        $em->flush($entity);

        $output->writeln('Client successfully generated');
        $output->writeln('Client ID: '.$entity->getIdentifier());
        $output->writeln('Client secret: '.$entity->getSecret());
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
