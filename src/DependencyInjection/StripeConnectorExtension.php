<?php

namespace Keivan\StripeConnectorBundle\DependencyInjection;

use Keivan\StripeConnectorBundle\StripeConnector;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

class StripeConnectorExtension extends Extension
{

    public function load( array $configs, ContainerBuilder $container )
    {
        $configDir = new FileLocator(__DIR__ . '/../Resources/config');

        $loader = new YamlFileLoader($container, $configDir);
        $loader->load('services.yaml');

        $configuration = new ConfigurationSchema();
        $config = $this->processConfiguration($configuration, $configs);

        $repo = $container->getDefinition(StripeConnector::class);
        $repo->replaceArgument('$apiKey', $config['api_key']);
    }
}