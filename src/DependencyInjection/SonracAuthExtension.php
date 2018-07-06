<?php

namespace sonrac\Auth\DependencyInjection;

use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\CryptKey;
use League\OAuth2\Server\Grant\ClientCredentialsGrant;
use League\OAuth2\Server\Grant\PasswordGrant;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;
use sonrac\Auth\Entity\Client;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
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
     * @throws \Symfony\Component\DependencyInjection\Exception\OutOfBoundsException
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $fileLocator = new FileLocator(__DIR__.'/../Resources/config');
        $loader = new YamlFileLoader(
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

        $config = $this->processConfiguration($configuration, $configs);

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
        $container->setParameter('sonrac_auth.refresh_token_lifetime', $config['refresh_token_lifetime']);
        $container->setParameter('sonrac_auth.auth_code_lifetime', $config['auth_code_lifetime']);
        $container->setParameter('sonrac_auth.access_token_lifetime', $config['access_token_lifetime']);
        $container->setParameter('sonrac_auth.enable_grant_types', $config['enable_grant_types']);
        $container->setParameter('sonrac_auth.query_delimiter', $config['query_delimiter']);
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias(): string
    {
        return 'sonrac_auth';
    }
}
