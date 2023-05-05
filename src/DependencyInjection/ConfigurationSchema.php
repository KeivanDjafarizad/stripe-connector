<?php

namespace Keivan\StripeConnectorBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class ConfigurationSchema implements ConfigurationInterface
{

    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('stripe_connector');

        $rootNode = $treeBuilder->getRootNode();

        $treeBuilder->getRootNode()
            ->fixXmlConfig('stripe_connector')
            ->children()
                ->scalarNode('api_key')->isRequired()->end()
            ->end();
        return $treeBuilder;
    }
}