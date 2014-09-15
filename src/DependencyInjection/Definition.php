<?php

/**
 * This file is part of the "-[:NEOXYGEN]->" NeoClient package
 *
 * (c) Neoxygen.io <http://neoxygen.io>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 */

namespace Neoxygen\NeoClient\DependencyInjection;

use Symfony\Component\Config\Definition\ConfigurationInterface;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;

class Definition implements ConfigurationInterface
{
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('neoclient');

        $supportedSchemes = array('http', 'https');

        $rootNode->children()
        ->arrayNode('connections')
        ->requiresAtLeastOneElement()
            ->prototype('array')
                ->children()
                    ->scalarNode('scheme')->defaultValue('http')
                        ->validate()
                        ->ifNotInArray($supportedSchemes)
                        ->thenInvalid('The scheme %s is not valid, please choose one of ' . json_encode($supportedSchemes))
                        ->end()
                    ->end()
                    ->scalarNode('host')->defaultValue('localhost')->end()
                    ->integerNode('port')->defaultValue('7474')->end()
                ->end()
            ->end()
        ->end()
        ->arrayNode('extensions')
                ->prototype('array')
                    ->children()
                        ->scalarNode('class')->canNotBeEmpty()->end()
                    ->end()
                ->end()
            ->end()
        ->arrayNode('loggers')
            ->prototype('array')
                ->children()
                    ->scalarNode('type')->canNotBeEmpty()->end()
                    ->scalarNode('path')->end()
                    ->scalarNode('level')->canNotBeEmpty()->end()
                ->end()
            ->end()
        ->end()
        ->end();

        return $treeBuilder;
    }
}