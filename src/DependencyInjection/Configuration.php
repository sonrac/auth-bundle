<?php

declare(strict_types=1);

namespace Sonrac\OAuth2\DependencyInjection;

use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\AuthCodeRepositoryInterface;
use League\OAuth2\Server\Repositories\ClientRepositoryInterface;
use League\OAuth2\Server\Repositories\RefreshTokenRepositoryInterface;
use League\OAuth2\Server\Repositories\ScopeRepositoryInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use Sonrac\OAuth2\Bridge\Grant\AuthCodeGrant;
use Sonrac\OAuth2\Bridge\Grant\ClientCredentialsGrant;
use Sonrac\OAuth2\Bridge\Grant\ImplicitGrant;
use Sonrac\OAuth2\Bridge\Grant\PasswordGrant;
use Sonrac\OAuth2\Bridge\Grant\RefreshTokenGrant;
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
            ->arrayNode('repository')
                ->addDefaultsIfNotSet()
                ->children()
                    ->scalarNode('access_token')->defaultValue('sonrac_oauth.doctrine.repository.access_token')->end()
                    ->scalarNode('auth_code')->defaultValue('sonrac_oauth.doctrine.repository.auth_code')->end()
                    ->scalarNode('client')->defaultValue('sonrac_oauth.doctrine.repository.client')->end()
                    ->scalarNode('refresh_token')->defaultValue('sonrac_oauth.doctrine.repository.refresh_token')->end()
                    ->scalarNode('scope')->defaultValue('sonrac_oauth.doctrine.repository.scope')->end()
                    ->scalarNode('user')->defaultValue('sonrac_oauth.doctrine.repository.user')->end()
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
            ->end()
            ->arrayNode('tokens_ttl')
                ->addDefaultsIfNotSet()
                ->children()
                    ->scalarNode('auth_code')->defaultValue('PT1H')->end()
                    ->scalarNode('access_token')->defaultValue('PT1H')->end()
                    ->scalarNode('refresh_token')->defaultValue('PT1H')->end()
                ->end()
            ->end()
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
            ->arrayNode('default_scopes')
                ->scalarPrototype()->end()
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
