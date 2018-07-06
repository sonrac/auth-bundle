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
                ->defaultValue('nkmWKwpRiwPDQig6JDU9mVfg0+I6JXsmbV0UKt6DNqw=')
                ->validate()
                    ->ifTrue(function ($v) {
                        var_dump($v);
                        return !empty($v) && mb_strlen($v) < 32;
                    })->thenInvalid('Encryption key invalid. generate using `base64_encode(random_bytes(32))`')
                ->end()
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
            ->end()
            ->arrayNode('enable_grant_types')
                ->children()
                    ->booleanNode('client_credentials')->end()
                    ->booleanNode('code')->end()
                    ->booleanNode('password')->end()
                    ->booleanNode('implicit')->end()
                ->end()
            ->end()
            ->end()
        ;

        foreach ([
            'access_token_lifetime',
            'refresh_token_lifetime',
            'auth_code_lifetime'
                 ] as $nodeName) {
            $rootNode->children()
                ->integerNode($nodeName)
                    ->defaultValue(3600)
                    ->validate()
                        ->ifTrue(function ($v) {
                            return (int) $v <= 0;
                        })->thenInvalid('Access token lifetime invalid value. Must be > 0')
                    ->end()
                ->end();
        }

        return $treeBuilder;
    }
}
