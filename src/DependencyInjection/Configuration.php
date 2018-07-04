<?php

namespace sonrac\Auth\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration.
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

        $rootNode = $treeBuilder->root('sonrac_auth');

        $rootNode->children()
            ->scalarNode('encryption_key')
            ->defaultValue('%kernel.root_dir%/resources/keys')
            ->end()
            ->scalarNode('private_key_path')
            ->defaultValue('%kernel.root_dir%/resources/keys')
            ->validate()
            ->ifString()
            ->ifTrue(function ($v) {
                if (empty($v)) {
                    return false;
                }

                if (!\is_dir($v) || !\is_readable($v)) {
                    return true;
                }

                return false;
            })->thenInvalid('Key pair directory does not exists. Generate keys with command sonrac:auth:generate:keys')
            ->end()
            ->end()
            ->scalarNode('password_salt')
            ->defaultValue(null)
            ->end()
            ->scalarNode('pass_phrase')
            ->defaultValue(null)
            ->end()
            ->scalarNode('public_key_name')
            ->defaultValue('pub.key')
            ->end()
            ->scalarNode('private_key_name')
            ->defaultValue('priv.key')
            ->end();

        return $treeBuilder;
    }
}
