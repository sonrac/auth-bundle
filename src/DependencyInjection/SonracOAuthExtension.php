<?php

declare(strict_types=1);

namespace Sonrac\OAuth2\DependencyInjection;

use League\OAuth2\Server\Grant\AbstractGrant;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
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

        $loader->load('services/repository.xml');
        $loader->load('services/oauth2.xml');
        $loader->load('services/security.xml');
        $loader->load('services/commands.xml');
        $loader->load('services/controllers.xml');

        $configuration = $this->getConfiguration($configs, $container);

        if (!$configuration) {
            throw new \LogicException('Configuration does not found');
        }

        $config = $this->processConfiguration($configuration, $configs);
        $config['default_scopes'] = array_unique($config['default_scopes']);

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
        // key factory configuration

        $container->getDefinition('sonrac_oauth.oauth2.secure_key_factory')
            ->setArgument('$encryptionKey', $config['keys']['encryption'])
            ->setArgument('$keyPath', $config['keys']['pair']['path'])
            ->setArgument('$privateKeyName', $config['keys']['pair']['private_key_name'])
            ->setArgument('$publicKeyName', $config['keys']['pair']['public_key_name'])
            ->setArgument('$passPhrase', $config['keys']['pair']['pass_phrase'] ?? null);

        // repositories configuration

        $container->getDefinition(AccessTokenRepositoryInterface::class)
            ->setArgument('$accessTokenRepository', new Reference($config['repository']['access_token']));

        $container->getDefinition(AuthCodeRepositoryInterface::class)
            ->setArgument('$authCodeRepository', new Reference($config['repository']['auth_code']));

        $container->getDefinition(ClientRepositoryInterface::class)
            ->setArgument('$clientRepository', new Reference($config['repository']['client']));

        $container->getDefinition(RefreshTokenRepositoryInterface::class)
            ->setArgument('$refreshTokenRepository', new Reference($config['repository']['refresh_token']));

        $container->getDefinition(ScopeRepositoryInterface::class)
            ->setArgument('$scopeRepository', new Reference($config['repository']['scope']));

        $container->getDefinition(UserRepositoryInterface::class)
            ->setArgument('$userRepository', new Reference($config['repository']['user']));

        // commands configuration

        $container->getDefinition('sonrac_oauth.command.generate_client')
            ->setArgument('$clientRepository', new Reference($config['repository']['client']));

        // authorization server configurator

        $container->getDefinition('sonrac_oauth.oauth2.authorization_server_configurator')
            ->setArgument('$authCodeTTL', $config['tokens_ttl']['auth_code'])
            ->setArgument('$accessTokenTTL', $config['tokens_ttl']['access_token'])
            ->setArgument('$refreshTokenTTL', $config['tokens_ttl']['refresh_token']);

        foreach ($config['grant_types'] as $grantType => $enable) {
            if ($enable) {
                $container->getDefinition('sonrac_oauth.oauth2.authorization_server_configurator')
                    ->addMethodCall('enableGrantType', [$grantType]);
            }
        }

        // tagged controllers

        $authorizationControllerIds = $container->findTaggedServiceIds('sonrac_oauth.controller.authorization');

        foreach ($authorizationControllerIds as $controllerId => $tags) {
            $container->getDefinition($controllerId)->addMethodCall('setOAuthAuthorizationHandler', [
                new Reference('sonrac_oauth.security.oauth2_authorization_handler')
            ]);
        }

        $issueTokenControllerIds = $container->findTaggedServiceIds('sonrac_oauth.controller.issue_token');

        foreach ($issueTokenControllerIds as $controllerId => $tags) {
            $container->getDefinition($controllerId)->addMethodCall('setOAuthIssueTokenHandler', [
                new Reference('sonrac_oauth.security.oauth_issue_token_handler')
            ]);
        }

        // authentication handler

        if (\count($config['default_scopes']) > 0) {
            $container->getDefinition('sonrac_oauth.oauth2.authorization_server')
                ->addMethodCall(
                    'setDefaultScope', [implode(AbstractGrant::SCOPE_DELIMITER_STRING, $config['default_scopes'])]
                );

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
