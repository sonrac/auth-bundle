<?php

declare(strict_types=1);

namespace sonrac\Auth\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Routing\Loader\YamlFileLoader as RoutesYamlLoader;

/**
 * Class SonracAuthExtension.
 */
class SonracAuthExtension extends Extension
{
    /**
     * {@inheritdoc}
     *
     * @throws \Symfony\Component\DependencyInjection\Exception\BadMethodCallException
     * @throws \Symfony\Component\DependencyInjection\Exception\InvalidArgumentException
     * @throws \Exception
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceCircularReferenceException
     * @throws \Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException
     * @throws \LogicException
     * @throws \Symfony\Component\DependencyInjection\Exception\OutOfBoundsException
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $fileLocator = new FileLocator(__DIR__.'/../Resources/config');
        $loader      = new YamlFileLoader(
            $container,
            $fileLocator
        );

        $loader->load('services.yaml');
        $routerLoader = new RoutesYamlLoader($fileLocator);
        $routerLoader->load('routes.yaml');

        $configuration = $this->getConfiguration($configs, $container);

        if (!$configuration) {
            throw new \LogicException('Configuration does not found');
        }

        $config = $this->processConfiguration($configuration, $configs);

        $this->setParameters($config, $container);
    }

    /**
     * Set bundle parameters.
     *
     * @param array                                                   $config
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     *
     * @throws \Exception
     */
    private function setParameters(array $config, ContainerBuilder $container): void
    {
        $container->setParameter('sonrac_auth.pass_phrase', $config['pass_phrase']);
        $container->setParameter('sonrac_auth.encryption_key', $config['encryption_key']);
        $container->setParameter('sonrac_auth.private_key_path', $config['private_key_path']);
        $container->setParameter('sonrac_auth.password_salt', $config['password_salt']);
        $container->setParameter('sonrac_auth.private_key_name', $config['private_key_name']);
        $container->setParameter('sonrac_auth.public_key_name', $config['public_key_name']);
        $container->setParameter('sonrac_auth.refresh_token_lifetime', $config['refresh_token_lifetime']);
        $container->setParameter('sonrac_auth.auth_code_lifetime', $config['auth_code_lifetime']);
        $container->setParameter('sonrac_auth.access_token_lifetime', $config['access_token_lifetime']);
        $container->setParameter('sonrac_auth.enable_grant_types', $config['enable_grant_types']);
        $container->setParameter('sonrac_auth.query_delimiter', $config['query_delimiter']);
        $container->setParameter('sonrac_auth.default_scopes', $config['default_scopes'] ?? ['default']);
        $container->setParameter('sonrac_auth.header_token_name', $config['header_token_name']);

        if (isset($config['swagger_constants']) && \is_array($config['swagger_constants'])) {
            foreach ($config['swagger_constants'] as $swagger_constant => $value) {
                $swagger_constant = 'SWAGGER_'.\mb_strtoupper($swagger_constant);
                if ($value === '{url}') {
                    $value = $container->get('router')->generate('home');
                }
                \defined($swagger_constant) or \define($swagger_constant, $value);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias(): string
    {
        return 'sonrac_auth';
    }
}
