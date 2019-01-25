<?php

declare(strict_types=1);

namespace Sonrac\OAuth2;

use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Sonrac\OAuth2\DependencyInjection\Security\OAuthFactory;
use Sonrac\OAuth2\DependencyInjection\SonracOAuthExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class AuthBundle
 * @package Sonrac\OAuth2
 */
class AuthBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $mappings = [
            __DIR__ . '/Resources/config/doctrine/' => 'Sonrac\OAuth2\Entity',
        ];

        if (\class_exists('Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass')) {
            $container->addCompilerPass(DoctrineOrmMappingsPass::createXmlMappingDriver($mappings));
        }

        /** @var \Symfony\Bundle\SecurityBundle\DependencyInjection\SecurityExtension $security */
        $security = $container->getExtension('security');
        $security->addSecurityListenerFactory(new OAuthFactory());
    }

    /**
     * {@inheritdoc}
     */
    public function getContainerExtension()
    {
        return new SonracOAuthExtension();
    }
}
