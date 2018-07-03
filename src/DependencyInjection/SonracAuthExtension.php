<?php

namespace sonrac\Auth\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Routing\Loader\YamlFileLoader as RoutesYamlLoader;

/**
 * Class SonracAuthExtension
 */
class SonracAuthExtension extends Extension
{
    /**
     * @inheritDoc
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $fileLocator = new FileLocator(__DIR__.'/../Resources/config');
        $loader = new YamlFileLoader(
            $container,
            $fileLocator
        );

        $loader->load('services.yaml');
        $routerLoader = new RoutesYamlLoader($fileLocator);
        $routerLoader->load('routes.yaml');
    }
}
