<?php

namespace sonrac\Auth\DependencyInjection;

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
     * @throws \Exception
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
        $config        = $this->processConfiguration($configuration, $configs);

        $this->setParameters($config, $container);
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
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias(): string
    {
        return 'sonrac_auth';
    }
}
