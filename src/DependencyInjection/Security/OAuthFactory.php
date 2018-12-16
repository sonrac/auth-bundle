<?php

declare(strict_types=1);

namespace Sonrac\OAuth2\DependencyInjection\Security;

use Symfony\Bundle\SecurityBundle\DependencyInjection\Security\Factory\SecurityFactoryInterface;
use Symfony\Component\Config\Definition\Builder\NodeDefinition;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class OAuthFactory
 * @package Sonrac\OAuth2\DependencyInjection\Security
 * //TODO: add check, that firewall is stateless
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
        $pathConfigId = 'sonrac_oauth.security.oauth_path_config.' . $id;
        $authenticationProviderId = 'sonrac_oauth.security.authentication_provider.' . $id;
        $authenticationHandlerId = 'sonrac_oauth.security.oauth_authentication_handler.' . $id;
        $authenticationListenerId = 'sonrac_oauth.security.authentication_listener.' . $id;

        // path config service set up

        $container
            ->setDefinition(
                $pathConfigId, new ChildDefinition('sonrac_oauth.security.oauth_path_config.abstract')
            )
            ->setArgument('$authorizationPath', $config['paths']['authorization'])
            ->setArgument('$issueTokenPath', $config['paths']['token']);

        // authentication provider service set up

        $container
            ->setDefinition(
                $authenticationProviderId,
                new ChildDefinition('sonrac_oauth.security.authentication_provider.abstract')
            )
            ->setArgument('$userProvider', new Reference($userProvider))
            ->setArgument('$userChecker', new Reference('security.user_checker.' . $id))
            ->setArgument('$providerKey', $id);

        // authentication handler

        $container
            ->setDefinition(
                $authenticationHandlerId,
                new ChildDefinition('sonrac_oauth.security.oauth_authentication_handler.abstract')
            )
            ->setArgument('$authorizationValidator', new Reference($config['authorization_validator']))
            ->setArgument('$providerKey', $id);

        // authentication listener

        $container
            ->setDefinition(
                $authenticationListenerId,
                new ChildDefinition('sonrac_oauth.security.authentication_listener.abstract')
            )
            ->setArgument('$authenticationHandler', new Reference($authenticationHandlerId))
            ->setArgument('$pathConfig', new Reference($pathConfigId));

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
            ->scalarNode('authorization_validator')
                ->defaultValue('sonrac_oauth.security.authorization_validator.bearer_token')
            ->end()
            ->arrayNode('paths')
                ->isRequired()
                ->children()
                    ->scalarNode('authorization')->isRequired()->end()
                    ->scalarNode('token')->isRequired()->end()
                ->end()
            ->end();

        $node->end();
    }
}
