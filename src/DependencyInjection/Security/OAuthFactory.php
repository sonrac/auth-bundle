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
        $authenticationProviderId = 'sonrac_oauth.security.authentication_provider.' . $id;
        $authenticationListenerId = 'sonrac_oauth.security.authentication_listener.' . $id;

        //TODO: Legacy, use factories instead.
        $container->register('sonrac_oauth.security.legacy.auth_code_ttl_interval.' . $id, \DateInterval::class)
            ->setPrivate(true)
            ->setArgument('$interval_spec', $config['auth_code_ttl']);
        $container->register('sonrac_oauth.security.legacy.access_token_ttl_interval.' . $id, \DateInterval::class)
            ->setPrivate(true)
            ->setArgument('$interval_spec', $config['access_token_ttl']);
        $container->register('sonrac_oauth.security.legacy.refresh_token_ttl_interval.' . $id, \DateInterval::class)
            ->setPrivate(true)
            ->setArgument('$interval_spec', $config['refresh_token_ttl']);

        $grantTypesIds = $this->registerGrantTypes($container, $config, $id);

        $this->registerAuthorizationServer($container, $authorizationServerId, $id, $config, $grantTypesIds);

        $this->registerAuthenticationProvider($container, $authenticationProviderId, $id);

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
            ->replaceArgument('$authorizationServer', new Reference($authorizationServerId))
            ->replaceArgument('$authenticationManager', new Reference($authenticationManagerId))
            ->setArgument('$authorizationPath', $config['authorization_path'])
            ->setArgument('$providerKey', $providerKey);
    }

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @param string $id
     * @param string $providerKey
     *
     * @return void
     */
    private function registerAuthenticationProvider(
        ContainerBuilder $container,
        string $id,
        string $providerKey
    ): void {
        $definition = $container->setDefinition(
            $id, new ChildDefinition('sonrac_oauth.security.authentication_provider.abstract')
        );

        $definition
            ->setArgument('$providerKey', $providerKey);
    }

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @param string $id
     * @param string $providerKey
     * @param array $config
     * @param array $grantTypesIds
     *
     * @return void
     *
     */
    private function registerAuthorizationServer(
        ContainerBuilder $container,
        string $id,
        string $providerKey,
        array &$config,
        array &$grantTypesIds
    ): void {

        $definition = $container->setDefinition(
            $id, new ChildDefinition('sonrac_oauth.security.authorization_server.abstract')
        );

        foreach ($grantTypesIds as $grantTypesId) {
            $definition->addMethodCall('enableGrantType', [
                '$grantType' => new Reference($grantTypesId),
                '$accessTokenTTL' => new Reference('sonrac_oauth.security.legacy.access_token_ttl_interval.' . $providerKey),
            ]);
        }
    }

    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     * @param array $config
     * @param string $providerKey
     *
     * @return array - registered grant id's
     *
     * @throws \Exception
     */
    private function registerGrantTypes(ContainerBuilder $container, array &$config, string $providerKey): array
    {
        $grants = $config['enabled_grant_types'];
        $enabledGrantIds = [];

        if (isset($grants[Client::GRANT_AUTH_CODE]) && $grants[Client::GRANT_AUTH_CODE]) {
            $id = 'sonrac_oauth.security.auth_code_grant.' . $providerKey;

            $container
                ->setDefinition($id, new ChildDefinition('sonrac_oauth.security.auth_code_grant.abstract'))
                ->setArgument('$authCodeTTL', new Reference('sonrac_oauth.security.legacy.auth_code_ttl_interval.' . $providerKey))
                ->addMethodCall('setRefreshTokenTTL', [
                    '$refreshTokenTTL' => new Reference('sonrac_oauth.security.legacy.refresh_token_ttl_interval.' . $providerKey)
                ]);

            $enabledGrantIds[] = $id;
        }

        if (isset($grants[Client::GRANT_CLIENT_CREDENTIALS]) && $grants[Client::GRANT_CLIENT_CREDENTIALS]) {
            $id = 'sonrac_oauth.security.client_credentials_grant.' . $providerKey;

            $container
                ->setDefinition(
                    $id, new ChildDefinition('sonrac_oauth.security.client_credentials_grant.abstract')
                )
                ->addMethodCall('setRefreshTokenTTL', [
                    '$refreshTokenTTL' => new Reference('sonrac_oauth.security.legacy.refresh_token_ttl_interval.' . $providerKey)
                ]);

            $enabledGrantIds[] = $id;
        }

        if (isset($grants[Client::GRANT_IMPLICIT]) && $grants[Client::GRANT_IMPLICIT]) {
            $id = 'sonrac_oauth.security.implicit_grant.' . $providerKey;

            $container
                ->setDefinition($id, new ChildDefinition('sonrac_oauth.security.implicit_grant.abstract'))
                ->setArgument('$accessTokenTTL', new Reference('sonrac_oauth.security.legacy.access_token_ttl_interval.' . $providerKey));

            $enabledGrantIds[] = $id;
        }

        if (isset($grants[Client::GRANT_PASSWORD]) && $grants[Client::GRANT_PASSWORD]) {
            $id = 'sonrac_oauth.security.password_grant.' . $providerKey;

            $container
                ->setDefinition($id, new ChildDefinition('sonrac_oauth.security.password_grant.abstract'))
                ->addMethodCall('setRefreshTokenTTL', [
                    '$refreshTokenTTL' => new Reference('sonrac_oauth.security.legacy.refresh_token_ttl_interval.' . $providerKey)
                ]);

            $enabledGrantIds[] = $id;
        }

        if (isset($grants[Client::GRANT_REFRESH_TOKEN]) && $grants[Client::GRANT_REFRESH_TOKEN]) {
            $id = 'sonrac_oauth.security.refresh_token_grant.' . $providerKey;

            $container
                ->setDefinition($id, new ChildDefinition('sonrac_oauth.security.refresh_token_grant.abstract'))
                ->addMethodCall('setRefreshTokenTTL', [
                    '$refreshTokenTTL' => new Reference('sonrac_oauth.security.legacy.refresh_token_ttl_interval.' . $providerKey)
                ]);

            $enabledGrantIds[] = $id;
        }

        return $enabledGrantIds;
    }
}
