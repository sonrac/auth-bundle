<?php

declare(strict_types=1);

namespace Sonrac\OAuth2\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
//use Symfony\Component\Routing\Loader\YamlFileLoader as RoutesYamlLoader;

/**
 * Class SonracOAuthExtension
 * @package Sonrac\OAuth2\DependencyInjection
 */
class SonracOAuthExtension extends Extension
{
    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $fileLocator = new FileLocator(__DIR__ . '/../Resources/config');
        $loader = new YamlFileLoader(
            $container,
            $fileLocator
        );
        $xmlLoader = new XmlFileLoader($container, $fileLocator);

        $loader->load('services.yaml');

        $xmlLoader->load('services.xml');
        $xmlLoader->load('services/security.xml');

//        $routerLoader = new RoutesYamlLoader($fileLocator);
//        $routerLoader->load('routes.yaml');

        $configuration = $this->getConfiguration($configs, $container);

        if (!$configuration) {
            throw new \LogicException('Configuration does not found');
        }

        $config = $this->processConfiguration($configuration, $configs);

        $this->setParameters($container, $config);

        $this->replaceServiceDefinitions($container, $config);
    }

    /**
     * Set bundle parameters.
     *
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @param array $config
     *
     * @throws \Exception
     */
    private function setParameters(ContainerBuilder $container, array &$config): void
    {
        $container->setParameter('sonrac_oauth.keys.encryption', $config['keys']['encryption']);
        $container->setParameter('sonrac_oauth.keys.pair.path', $config['keys']['pair']['path']);
        $container->setParameter('sonrac_oauth.keys.pair.private_key_name', $config['keys']['pair']['private_key_name']);
        $container->setParameter('sonrac_oauth.keys.pair.public_key_name', $config['keys']['pair']['public_key_name']);
        $container->setParameter('sonrac_oauth.keys.pair.pass_phrase', $config['keys']['pair']['pass_phrase']);

        $container->setParameter('sonrac_oauth.repository.access_token', $config['repository']['access_token']);
        $container->setParameter('sonrac_oauth.repository.auth_code', $config['repository']['auth_code']);
        $container->setParameter('sonrac_oauth.repository.client', $config['repository']['client']);
        $container->setParameter('sonrac_oauth.repository.refresh_token', $config['repository']['refresh_token']);
        $container->setParameter('sonrac_oauth.repository.scope', $config['repository']['scope']);
        $container->setParameter('sonrac_oauth.repository.user', $config['repository']['user']);

        if (isset($config['swagger_constants']) && \is_array($config['swagger_constants'])) {
            foreach ($config['swagger_constants'] as $swagger_constant => $value) {
                $swagger_constant = 'SWAGGER_' . \mb_strtoupper($swagger_constant);
                if ($value === '{url}') {
                    $value = $container->get('router')->generate('home');
                }
                \defined($swagger_constant) or \define($swagger_constant, $value);
            }
        }
    }

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @param array $config
     *
     * @return void
     */
    private function replaceServiceDefinitions(ContainerBuilder $container, array &$config): void
    {
        $repositories = $config['repository'];

        $container->getDefinition('sonrac_oauth.security.token_factory.abstract')
            ->setArgument('$clientRepository', new Reference($repositories['client']));

        $container->getDefinition('sonrac_oauth.security.authorization_validator.bearer_token')
            ->setArgument('$accessTokenRepository', new Reference($repositories['access_token']));

        $container->getDefinition('sonrac_oauth.security.authentication_provider.abstract')
            ->setArgument('$clientRepository', new Reference($repositories['client']));

        $container->getDefinition('sonrac_oauth.security.authorization_server.abstract')
            ->setArgument('$clientRepository', new Reference($repositories['client']))
            ->setArgument('$accessTokenRepository', new Reference($repositories['access_token']))
            ->setArgument('$scopeRepository', new Reference($repositories['scope']));
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias(): string
    {
        return 'sonrac_oauth';
    }
}
