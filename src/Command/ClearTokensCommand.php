<?php

declare(strict_types=1);

namespace Sonrac\OAuth2\Command;

use Doctrine\Bundle\DoctrineBundle\Command\DoctrineCommand;
use Doctrine\ORM\EntityManager;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\RefreshTokenEntityInterface;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\Output;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class ClearTokensCommand
 * @package Sonrac\OAuth2\Command
 *
 * //TODO: refactor and refactor tests
 */
class ClearTokensCommand extends DoctrineCommand
{
    /**
     * {@inheritdoc}
     *
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    protected function configure(): void
    {
        $this->setName('sonrac_auth:clear:tokens');
        $this->setDescription('Clear access token table');
        $this->addOption(
            'all',
            'a',
            InputOption::VALUE_OPTIONAL,
            'Delete all access tokens. If not set deleted only revoked',
            false
        );
        $this->addOption(
            'with-expired',
            'w',
            InputOption::VALUE_OPTIONAL,
            'Delete all revoked and expired access tokens',
            false
        );
        $this->addOption(
            'with-refresh',
            'r',
            InputOption::VALUE_OPTIONAL,
            'Delete all access tokens with refresh tokens',
            false
        );
    }

    /**
     * {@inheritdoc}
     *
     * @throws \LogicException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     * @throws \Doctrine\DBAL\ConnectionException
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getEntityManager('default');
        $em->getConnection()->beginTransaction();
        $query = $em->createQueryBuilder()
            ->from(\get_class($this->getContainer()->get(AccessTokenEntityInterface::class)), 'access_token')
            ->delete();

        if (false === $input->getOption('all')) {
            $query->orWhere('access_token.is_revoked = :is_revoked')
                ->setParameter('is_revoked', true);

            if (false !== $input->getOption('with-expired')) {
                $query->orWhere('access_token.expire_at <= :expire')
                    ->setParameter('expire', \time());
            }
        }

        try {
            if (false !== $input->getOption('with-refresh')) {
                $output->writeln('Drop refresh tokens.', Output::OUTPUT_PLAIN);
                $this->dropRefreshTokens(
                    $em,
                    false !== $input->getOption('all'),
                    false !== $input->getOption('with-expired')
                );
            }

            $output->writeln('Drop access tokens.', Output::OUTPUT_PLAIN);

            $query
                ->getQuery()
                ->execute();
            $em->getConnection()->commit();
        } catch (\Exception $e) {
            $em->getConnection()->rollBack();

            throw $e;
        }
    }

    /**
     * Drop refresh tokens.
     *
     * @param \Doctrine\ORM\EntityManager $em
     * @param bool                        $all
     * @param bool                        $expired
     * @param int                         $limit
     * @param int                         $offset
     *
     * @throws \LogicException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     */
    protected function dropRefreshTokens(
        EntityManager $em,
        bool $all,
        bool $expired,
        int $limit = 1000,
        int $offset = 0
    ): void {
        $builder = $em->createQueryBuilder();

        $builder->delete()
            ->from(\get_class($this->getContainer()->get(RefreshTokenEntityInterface::class)), 'refresh_token');

        if (!$all) {
            $queryToken = $em->createQueryBuilder()
                ->select('token.token')
                ->from(\get_class($this->getContainer()->get(AccessTokenEntityInterface::class)), 'token')
                ->orWhere('token.is_revoked = :is_revoked')
                ->setMaxResults($limit)
                ->setFirstResult($offset)
                ->setParameter('is_revoked', true);

            if ($expired) {
                $queryToken->orWhere('token.expire_at <= :expired')
                    ->setParameter('expired', \time());
            }

            $tokens = $queryToken->getQuery()->getArrayResult();

            if (\count($tokens) === 0) {
                return;
            }

            $builder->where('refresh_token.token in (:tokens)')
                ->setParameter('tokens', $tokens);
        }

        $builder->getQuery()->execute();

        if (!$all) {
            $offset += $limit;
            $this->dropRefreshTokens($em, $all, $expired, $limit, $offset);
        }
    }
}
