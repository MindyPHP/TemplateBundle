<?php

declare(strict_types=1);

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\TemplateBundle\DependencyInjection\Compiler;

use Mindy\Template\Library\LibraryInterface;
use Mindy\Template\TemplateEngine;
use Mindy\Template\VariableProviderInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Adds tagged routing.loader services to routing.resolver service.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class TemplatePass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (false === $container->hasDefinition(TemplateEngine::class)) {
            return;
        }

        $container
            ->registerForAutoconfiguration(LibraryInterface::class)
            ->addTag('template.library');

        $container
            ->registerForAutoconfiguration(VariableProviderInterface::class)
            ->addTag('template.variable_provider');

        $definition = $container->getDefinition(TemplateEngine::class);

        foreach ($container->findTaggedServiceIds('template.library') as $id => $attributes) {
            $definition->addMethodCall('addLibrary', [new Reference($id)]);
        }

        foreach ($container->findTaggedServiceIds('template.variable_provider') as $id => $attributes) {
            $definition->addMethodCall('addVariableProvider', [new Reference($id)]);
        }
    }
}
