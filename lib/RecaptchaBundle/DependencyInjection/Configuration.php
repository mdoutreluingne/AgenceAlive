<?php

namespace mdoutreluingne\RecaptchaBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('recaptcha');
        $treeBuilder->getRootNode()
            ->children()
            ->scalarNode('key')
            ->isRequired()
            ->cannotBeEmpty()
            ->end()
            ->scalarNode('secret')
            ->isRequired()
            ->cannotBeEmpty()
            ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
?>