<?php

namespace sonrac\Auth;

use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use sonrac\Auth\DependencyInjection\SonracAuthExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class AuthBundle.
 * Auth bundle.
 */
class AuthBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $mappings = [
            __DIR__.'/Resources/config/doctrine/' => 'sonrac\Auth\Entity',
        ];

        if (\class_exists('Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass')) {
            $container->addCompilerPass(
                DoctrineOrmMappingsPass::createYamlMappingDriver(
                    $mappings
                )
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getContainerExtension()
    {
        return new SonracAuthExtension();
    }
}
