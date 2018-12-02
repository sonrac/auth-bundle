<?php

declare(strict_types=1);

namespace Sonrac\OAuth2\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 * @package Sonrac\OAuth2\DependencyInjection
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     *
     * @throws \RuntimeException
     */
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder();

        $rootNode = $treeBuilder->root('sonrac_oauth');

        $rootNode->children()
            ->arrayNode('keys')
                ->isRequired()
                ->children()
                    ->scalarNode('encryption')
                        ->defaultValue('nkmWKwpRiwPDQig6JDU9mVfg0+I6JXsmbV0UKt6DNqw=')
                        ->validate()
                            ->ifString()
                            ->ifTrue(function ($v) {
                                return empty($v) && \mb_strlen($v) < 32;
                            })->thenInvalid('Encryption key invalid. generate using `base64_encode(random_bytes(32))`')
                        ->end()
                    ->end()
                    ->arrayNode('pair')
                        ->isRequired()
                        ->children()
                            ->scalarNode('path')
                                ->defaultValue('%kernel.root_dir%/resources/keys')
                                ->validate()
                                ->ifString()
                                ->ifTrue(function ($v) {
                                    if (\PHP_SAPI === 'cli') {
                                        return false;
                                    }

                                    if (empty($v)) {
                                        return false;
                                    }

                                    if (!\is_dir($v) || !\is_readable($v)) {
                                        return true;
                                    }

                                    return false;
                                })->thenInvalid(
                                    'Key pair directory does not exists. Generate keys with command sonrac:auth:generate:keys'
                                )
                                ->end()
                            ->end()
                            ->scalarNode('private_key_name')->defaultValue('priv.key')->end()
                            ->scalarNode('public_key_name')->defaultValue('pub.key')->end()
                            ->scalarNode('pass_phrase')->defaultValue(null)->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
            ->arrayNode('repository')
                ->addDefaultsIfNotSet()
                ->children()
                    ->scalarNode('access_token')
                        ->defaultValue('sonrac_oauth.repository.access_token')
                    ->end()
                    ->scalarNode('auth_code')
                        ->defaultValue('sonrac_oauth.repository.auth_code')
                    ->end()
                    ->scalarNode('client')
                        ->defaultValue('sonrac_oauth.repository.client')
                    ->end()
                    ->scalarNode('refresh_token')
                        ->defaultValue('sonrac_oauth.repository.refresh_token')
                    ->end()
                    ->scalarNode('scope')
                        ->defaultValue('sonrac_oauth.repository.scope')
                    ->end()
                    ->scalarNode('user')
                        ->defaultValue('sonrac_oauth.repository.user')
                    ->end()
                ->end()
            ->end()
            ->arrayNode('swagger_constants')
                ->isRequired()
                ->requiresAtLeastOneElement()
                ->useAttributeAsKey('name')
                ->scalarPrototype()
            ->end()
            ->end()
            ->end();

        return $treeBuilder;
    }
}
