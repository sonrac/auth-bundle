<?php

namespace sonrac\Auth\DependencyInjection;

use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\CryptKey;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;
use Psr\Container\ContainerInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Routing\Loader\YamlFileLoader as RoutesYamlLoader;

/**
 * Class SonracAuthExtension.
 */
class SonracAuthExtension extends Extension
{
    /**
     * {@inheritdoc}
     *
     * @throws \Symfony\Component\DependencyInjection\Exception\BadMethodCallException
     * @throws \Symfony\Component\DependencyInjection\Exception\InvalidArgumentException
     * @throws \Exception
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     * @throws \LogicException
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $fileLocator = new FileLocator(__DIR__.'/../Resources/config');
        $loader      = new YamlFileLoader(
            $container,
            $fileLocator
        );

        $loader->load('services.yaml');
        $routerLoader = new RoutesYamlLoader($fileLocator);
        $routerLoader->load('routes.yaml');

        $configuration = $this->getConfiguration($configs, $container);

        if (!$configuration) {
            throw new \LogicException('Configuration does not found');
        }

        $config        = $this->processConfiguration($configuration, $configs);

        $this->setParameters($config, $container);
        $this->configureServices($container);
    }

    /**
     * Set bundle parameters.
     *
     * @param array                                                   $config
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    private function setParameters(array $config, ContainerBuilder $container): void
    {
        $container->setParameter('sonrac_auth.pass_phrase', $config['pass_phrase']);
        $container->setParameter('sonrac_auth.encryption_key', $config['encryption_key']);
        $container->setParameter('sonrac_auth.private_key_path', $config['private_key_path']);
        $container->setParameter('sonrac_auth.password_salt', $config['password_salt']);
        $container->setParameter('sonrac_auth.private_key_name', $config['private_key_name']);
        $container->setParameter('sonrac_auth.public_key_name', $config['public_key_name']);
        $container->setParameter('sonrac_auth.enable_grant_types', $config['enable_grant_types']);
    }

    /**
     * Configure league services.
     *
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     *
     * @throws \Symfony\Component\DependencyInjection\Exception\BadMethodCallException
     * @throws \Symfony\Component\DependencyInjection\Exception\InvalidArgumentException
     * @throws \Exception
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     */
    private function configureServices(ContainerBuilder $container): void
    {
//        $keyPath = $container->getParameter('sonrac_auth.private_key_path').DIRECTORY_SEPARATOR.
//            $container->getParameter('sonrac_auth.private_key_name');
//
//        $privateKey = $container->getParameter('sonrac_auth.pass_phrase') ?
//            new CryptKey(
//                $keyPath,
//                $container->getParameter('sonrac_auth.pass_phrase')
//            ) : $keyPath;
//
//        $authorizationServer = new AuthorizationServer(
//            $container->get(ClientRepositoryInterface::class),
//            $container->get(AccessTokenRepositoryInterface::class),
//            $container->get(ScopeRepositoryInterface::class),
//            $privateKey,
//            $container->getParameter('sonrac_auth.encryption_key')
//        );
//        $container->set('league.authorization_server', $authorizationServer);
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias(): string
    {
        return 'sonrac_auth';
    }
}
