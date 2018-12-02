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
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class OAuthFactory
 * @package Sonrac\OAuth2\DependencyInjection\Security
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
        $oauthTokenFactoryId = 'sonrac_oauth.security.token_factory.' . $id;
        $authorizationServerId = 'sonrac_oauth.security.authorization_server.' . $id;
        $authorizationServerConfiguratorId = 'sonrac_oauth.security.authorization_server_configurator.' . $id;
        $authenticationProviderId = 'sonrac_oauth.security.oauth_authentication_provider.' . $id;
        $authenticationListenerId = 'sonrac_oauth.security.authentication_listener.' . $id;

        $authorizationServerConfiguratorDefinition = $container
            ->setDefinition(
                $authorizationServerConfiguratorId,
                new ChildDefinition('sonrac_oauth.security.authorization_server_configurator.abstract')
            )
            ->setArgument('$authCodeTTL', $config['ttl']['auth_code'])
            ->setArgument('$accessTokenTTL', $config['ttl']['access_token'])
            ->setArgument('$refreshTokenTTL', $config['ttl']['refresh_token']);

        foreach ($config['grant_types'] as $grantType => $enable) {
            if ($enable) {
                $authorizationServerConfiguratorDefinition->addMethodCall('enableGrantType', [$grantType]);
            }
        }

        $container
            ->setDefinition(
                $oauthTokenFactoryId, new ChildDefinition('sonrac_oauth.security.token_factory.abstract')
            )
            ->setArgument('$userProvider', new Reference($userProvider));

        $container
            ->setDefinition(
                $authorizationServerId, new ChildDefinition('sonrac_oauth.security.authorization_server.abstract')
            )
            ->setConfigurator([new Reference($authorizationServerConfiguratorId), 'configure']);

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
            ->setArgument('$authorizationServer', new Reference($authorizationServerId))
            ->setArgument('$oauthTokenFactory', new Reference($oauthTokenFactoryId))
            ->setArgument('$authorizationPath', $config['paths']['authorization'])
            ->setArgument('$tokenPath', $config['paths']['token'])
            ->setArgument('$providerKey', $id);

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
}
