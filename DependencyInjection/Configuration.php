<?php

declare(strict_types=1);

/*
 * This file is part of Mindy Framework.
 * (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\TemplateBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();

        $rootNode = $treeBuilder->root('template');
        $rootNode
            ->children()
                ->scalarNode('cache')
                    ->defaultValue('%kernel.cache_dir%/templates')
                ->end()
                ->scalarNode('theme')
                    ->defaultValue('default')
                ->end()
                ->scalarNode('mode')
                    ->defaultValue(0)
                ->end()
                ->booleanNode('exception_handler')
                    ->defaultTrue()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
