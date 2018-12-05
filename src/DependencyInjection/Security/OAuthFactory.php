<?php

declare(strict_types=1);

namespace Sonrac\OAuth2\DependencyInjection\Security;

use Sonrac\OAuth2\Adapter\League\Grant\AuthCodeGrant;
use Sonrac\OAuth2\Adapter\League\Grant\ClientCredentialsGrant;
use Sonrac\OAuth2\Adapter\League\Grant\ImplicitGrant;
use Sonrac\OAuth2\Adapter\League\Grant\PasswordGrant;
use Sonrac\OAuth2\Adapter\League\Grant\RefreshTokenGrant;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\SecurityFactoryInterface;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\DependencyInjection\Argument\IteratorArgument;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class OAuthFactory
 * @package Sonrac\OAuth2\DependencyInjection\Security
 *
 * //TODO: add option default_scopes
 * //TODO: add option token validator with default value
 */
class OAuthFactory implements SecurityFactoryInterface
{
    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public function create(ContainerBuilder $container, $id, $config, $userProvider, $defaultEntryPoint)
    {
        $authorizationServerConfiguratorId = 'sonrac_oauth.security.authorization_server_configurator.' . $id;
        $authorizationServerId = 'sonrac_oauth.security.authorization_server.' . $id;

        $pathConfigId = 'sonrac_oauth.security.oauth_path_config.' . $id;

        $authorizationHandlerId = 'sonrac_oauth.security.oauth_authorization_handler.' . $id;
        $issueTokenHandlerId = 'sonrac_oauth.security.oauth_issue_token_handler.' . $id;
        $authenticationHandlerId = 'sonrac_oauth.security.oauth_authentication_handler.' . $id;

        $authenticationProviderId = 'sonrac_oauth.security.authentication_provider.' . $id;
        $authenticationListenerId = 'sonrac_oauth.security.authentication_listener.' . $id;

        $this->registerAuthorizationServer(
            $container, $authorizationServerConfiguratorId, $authorizationServerId, $config
        );

        $container
            ->setDefinition(
                $pathConfigId, new ChildDefinition('sonrac_oauth.security.oauth_path_config.abstract')
            )
            ->setArgument('$authorizationPath', $config['paths']['authorization'])
            ->setArgument('$issueTokenPath', $config['paths']['token']);

        $container
            ->setDefinition(
                $authorizationHandlerId,
                new ChildDefinition('sonrac_oauth.security.oauth_authorization_handler.abstract')
            )
            ->setArgument('$authorizationServer', new Reference($authorizationServerId))
            ->setArgument('$pathConfig', new Reference($pathConfigId));

        $container
            ->setDefinition(
                $issueTokenHandlerId,
                new ChildDefinition('sonrac_oauth.security.oauth_issue_token_handler.abstract')
            )
            ->setArgument('$authorizationServer', new Reference($authorizationServerId))
            ->setArgument('$pathConfig', new Reference($pathConfigId));

        $container
            ->setDefinition(
                $authenticationHandlerId,
                new ChildDefinition('sonrac_oauth.security.oauth_authentication_handler.abstract')
            )
            ->setArgument('$pathConfig', new Reference($pathConfigId))
            ->setArgument('$providerKey', $id);

        $container
            ->setDefinition(
                $authenticationProviderId,
                new ChildDefinition('sonrac_oauth.security.authentication_provider.abstract')
            )
            ->setArgument('$userProvider', new Reference($userProvider))
            ->setArgument('$providerKey', $id);

        $container
            ->setDefinition(
                $authenticationListenerId,
                new ChildDefinition('sonrac_oauth.security.authentication_listener.abstract')
            )
            ->setArgument('$handlers', new IteratorArgument([
                new Reference($authorizationHandlerId),
                new Reference($issueTokenHandlerId),
                new Reference($authenticationHandlerId),
            ]));

        return [$authenticationProviderId, $authenticationListenerId, $defaultEntryPoint];
    }

    /**
     * {@inheritdoc}
     */
    public function getPosition()
    {
        return 'form';
    }

    /**
     * {@inheritdoc}
     */
    public function getKey()
    {
        return 'sonrac_oauth';
    }

    /**
     * {@inheritdoc}
     */
    public function addConfiguration(NodeDefinition $builder)
    {
        /** @var \Symfony\Component\Config\Definition\Builder\NodeBuilder $node */
        $node = $builder->children();

        $node
            ->scalarNode('authorization_header')->defaultValue('X-Access-Token')->end()
            ->arrayNode('paths')
                ->isRequired()
                ->children()
                    ->scalarNode('authorization')->isRequired()->end()
                    ->scalarNode('token')->isRequired()->end()
                ->end()
            ->end()
            ->arrayNode('ttl')
                ->addDefaultsIfNotSet()
                ->children()
                    ->scalarNode('auth_code')->defaultValue('PT1H')->end()
                    ->scalarNode('access_token')->defaultValue('PT1H')->end()
                    ->scalarNode('refresh_token')->defaultValue('PT1H')->end()
                ->end()
            ->end()
            ->arrayNode('grant_types')
                ->addDefaultsIfNotSet()
                ->children()
                    ->booleanNode(AuthCodeGrant::TYPE)->defaultValue(false)->end()
                    ->booleanNode(ClientCredentialsGrant::TYPE)->defaultValue(true)->end()
                    ->booleanNode(ImplicitGrant::TYPE)->defaultValue(false)->end()
                    ->booleanNode(PasswordGrant::TYPE)->defaultValue(true)->end()
                    ->booleanNode(RefreshTokenGrant::TYPE)->defaultValue(true)->end()
                ->end()
            ->end();

        $node->end();
    }

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @param string $configuratorId
     * @param string $serverId
     * @param array $config
     *
     * @return void
     */
    private function registerAuthorizationServer(
        ContainerBuilder $container,
        string $configuratorId,
        string $serverId,
        array &$config
    ): void {
        $configuratorDefinition = $container
            ->setDefinition(
                $configuratorId,
                new ChildDefinition('sonrac_oauth.security.authorization_server_configurator.abstract')
            )
            ->setArgument('$authCodeTTL', $config['ttl']['auth_code'])
            ->setArgument('$accessTokenTTL', $config['ttl']['access_token'])
            ->setArgument('$refreshTokenTTL', $config['ttl']['refresh_token']);

        foreach ($config['grant_types'] as $grantType => $enable) {
            if ($enable) {
                $configuratorDefinition->addMethodCall('enableGrantType', [$grantType]);
            }
        }

        $container
            ->setDefinition($serverId, new ChildDefinition('sonrac_oauth.security.authorization_server.abstract'))
            ->setConfigurator([new Reference($configuratorId), 'configure']);
    }
}
