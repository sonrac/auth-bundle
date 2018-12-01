<?php

declare(strict_types=1);

namespace sonrac\Auth\DependencyInjection\Security;

use sonrac\Auth\Entity\Client;
use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\SecurityFactoryInterface;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class OAuthFactory
 * @package sonrac\Auth\DependencyInjection\Security
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
        $authorizationServerId = 'sonrac_oauth.security.authorization_server.' . $id;
        $authorizationServerConfiguratorId = 'sonrac_oauth.security.authorization_server_configurator.' . $id;
        $authenticationProviderId = 'sonrac_oauth.security.authentication_provider.' . $id;
        $authenticationListenerId = 'sonrac_oauth.security.authentication_listener.' . $id;

        $grantTypeIds = $this->registerGrantTypes($container, $config, $id);

        $authorizationServerConfiguratorDefinition = $container
            ->setDefinition(
                $authorizationServerConfiguratorId,
                new ChildDefinition('sonrac_oauth.security.authorization_server_configurator.abstract')
            )
            ->setArgument('$accessTokenTTL', $config['access_token_ttl']);

        foreach ($grantTypeIds as $grantTypeId) {
            $authorizationServerConfiguratorDefinition->addMethodCall(
                'registerGrantType', [new Reference($grantTypeId)]
            );
        }

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
            ->setArgument('$providerKey', $id);

        $this->registerAuthenticationListener(
            $container, $authenticationListenerId, $config, $id, $authorizationServerId, $authenticationProviderId
        );

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
            ->scalarNode('authorization_path')->isRequired()->end()
            ->scalarNode('auth_code_ttl')->defaultValue('PT1M')->end()
            ->scalarNode('access_token_ttl')->defaultValue('PT1M')->end()
            ->scalarNode('refresh_token_ttl')->defaultValue('PT1M')->end()
            ->arrayNode('enabled_grant_types')
                ->addDefaultsIfNotSet()
                ->children()
                    ->booleanNode(Client::GRANT_AUTH_CODE)->defaultValue(true)->end()
                    ->booleanNode(Client::GRANT_CLIENT_CREDENTIALS)->defaultValue(true)->end()
                    ->booleanNode(Client::GRANT_IMPLICIT)->defaultValue(true)->end()
                    ->booleanNode(Client::GRANT_PASSWORD)->defaultValue(true)->end()
                    ->booleanNode(Client::GRANT_REFRESH_TOKEN)->defaultValue(true)->end()
                ->end()
            ->end()
            ->scalarNode('auth_header_name')->defaultValue('X-Access-Token')->end();

        $node->end();
    }

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @param string $id
     * @param array $config
     * @param string $providerKey
     *
     * @param string $authorizationServerId
     * @param string $authenticationManagerId
     * @return void
     */
    private function registerAuthenticationListener(
        ContainerBuilder $container,
        string $id,
        array &$config,
        string $providerKey,
        string $authorizationServerId,
        string $authenticationManagerId
    ): void {
        $definition = $container->setDefinition(
            $id, new ChildDefinition('sonrac_oauth.security.authentication_listener.abstract')
        );

        $definition
            ->setArgument('$authorizationServer', new Reference($authorizationServerId))
            ->setArgument('$authenticationManager', new Reference($authenticationManagerId))
            ->setArgument('$authorizationPath', $config['authorization_path'])
            ->setArgument('$providerKey', $providerKey);
    }

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @param array $config
     * @param string $providerKey
     *
     * @return array
     */
    private function registerGrantTypes(
        ContainerBuilder $container,
        array &$config,
        string $providerKey
    ): array {
        $grants = $config['enabled_grant_types'];
        $granTypeIds = [];

        if (isset($grants[Client::GRANT_AUTH_CODE]) && $grants[Client::GRANT_AUTH_CODE]) {
            $id = 'sonrac_oauth.security.auth_code_grant.' . $providerKey;

            $container
                ->setDefinition($id, new ChildDefinition('sonrac_oauth.security.auth_code_grant.abstract'))
                ->setArgument('$authCodeTTL', $config['auth_code_ttl'])
                ->setArgument('$refreshTokenTTL', $config['refresh_token_ttl']);

            $granTypeIds[] = $id;
        }

        if (isset($grants[Client::GRANT_CLIENT_CREDENTIALS]) && $grants[Client::GRANT_CLIENT_CREDENTIALS]) {
            $id = 'sonrac_oauth.security.client_credentials_grant.' . $providerKey;

            $container
                ->setDefinition(
                    $id, new ChildDefinition('sonrac_oauth.security.client_credentials_grant.abstract')
                )
                ->setArgument('$refreshTokenTTL', $config['refresh_token_ttl']);

            $granTypeIds[] = $id;
        }

        if (isset($grants[Client::GRANT_IMPLICIT]) && $grants[Client::GRANT_IMPLICIT]) {
            $id = 'sonrac_oauth.security.implicit_grant.' . $providerKey;

            $container
                ->setDefinition($id, new ChildDefinition('sonrac_oauth.security.implicit_grant.abstract'))
                ->setArgument('$accessTokenTTL', $config['access_token_ttl']);

            $granTypeIds[] = $id;
        }

        if (isset($grants[Client::GRANT_PASSWORD]) && $grants[Client::GRANT_PASSWORD]) {
            $id = 'sonrac_oauth.security.password_grant.' . $providerKey;

            $container
                ->setDefinition($id, new ChildDefinition('sonrac_oauth.security.password_grant.abstract'))
                ->setArgument('$refreshTokenTTL', $config['refresh_token_ttl']);

            $granTypeIds[] = $id;
        }

        if (isset($grants[Client::GRANT_REFRESH_TOKEN]) && $grants[Client::GRANT_REFRESH_TOKEN]) {
            $id = 'sonrac_oauth.security.refresh_token_grant.' . $providerKey;

            $container
                ->setDefinition($id, new ChildDefinition('sonrac_oauth.security.refresh_token_grant.abstract'))
                ->setArgument('$refreshTokenTTL', $config['refresh_token_ttl']);

            $granTypeIds[] = $id;
        }

        return $granTypeIds;
    }
}
