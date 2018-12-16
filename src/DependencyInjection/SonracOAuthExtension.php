<?php

declare(strict_types=1);

namespace Sonrac\OAuth2\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Reference;

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
        $loader = new XmlFileLoader($container, $fileLocator);

        $loader->load('services.xml');
        $loader->load('services/repository.xml');
        $loader->load('services/oauth2.xml');
        $loader->load('services/security.xml');
        $loader->load('services/commands.xml');

        $configuration = $this->getConfiguration($configs, $container);

        if (!$configuration) {
            throw new \LogicException('Configuration does not found');
        }

        $config = $this->processConfiguration($configuration, $configs);

        $this->setParameters($container, $config);

        $this->configureServiceDefinitions($container, $config);
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
    private function configureServiceDefinitions(ContainerBuilder $container, array &$config): void
    {
        // authorization server configurator

        $container->getDefinition('sonrac_oauth.oauth2.authorization_server_configurator')
            ->setArgument('$authCodeTTL', $config['tokens_ttl']['auth_code'])
            ->setArgument('$authCodeTTL', $config['tokens_ttl']['access_token'])
            ->setArgument('$authCodeTTL', $config['tokens_ttl']['refresh_token']);

        foreach ($config['grant_types'] as $grantType => $enable) {
            if ($enable) {
                $container->getDefinition('sonrac_oauth.oauth2.authorization_server_configurator')
                    ->addMethodCall('enableGrantType', [$grantType]);
            }
        }

        // authorization server

        $container->getDefinition('sonrac_oauth.oauth2.authorization_server')
            ->setArgument('$clientRepository', new Reference($config['repository']['client']))
            ->setArgument('$accessTokenRepository', new Reference($config['repository']['access_token']))
            ->setArgument('$scopeRepository', new Reference($config['repository']['scope']));

        // bearer token validator

        $container->getDefinition('sonrac_oauth.security.authorization_validator.bearer_token')
            ->setArgument('$accessTokenRepository', new Reference($config['repository']['access_token']));

        // authentication provider

        $container->getDefinition('sonrac_oauth.security.authentication_provider.abstract')
            ->setArgument('$clientRepository', new Reference($config['repository']['client']));

        // authentication handler

        if (isset($config['default_scopes']) && \is_array($config['default_scopes'])) {
            $container->getDefinition('sonrac_oauth.security.oauth_authentication_handler.abstract')
                ->addMethodCall('setDefaultScopes', [$config['default_scopes']]);

        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias(): string
    {
        return 'sonrac_oauth';
    }
}
