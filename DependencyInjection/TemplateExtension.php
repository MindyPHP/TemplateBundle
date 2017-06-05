<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\TemplateBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class TemplateExtension extends Extension
{
    /**
     * Loads a specific configuration.
     *
     * @param array            $configs   An array of configuration values
     * @param ContainerBuilder $container A ContainerBuilder instance
     *
     * @throws \InvalidArgumentException When provided tag is not defined in this extension
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');

        $configuration = $this->getConfiguration($configs, $container);
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('template.mode', $config['mode']);
        $container->setParameter('template.auto_escape', $config['auto_escape']);
        $container->setParameter('template.cache_dir', $config['cache_dir']);

        if ($this->isConfigEnabled($container, $config['theme'])) {
            $this->registerThemeTemplateFinderConfiguration($config['theme'], $container, $loader);
        }

        if ($this->isConfigEnabled($container, $config['bundles'])) {
            $this->registerBundlesTemplateFinderConfiguration($config['bundles'], $container, $loader);
        }
    }

    protected function registerBundlesTemplateFinderConfiguration(array $config, ContainerBuilder $container, XmlFileLoader $loader)
    {
        $bundlesDefinition = $container->findDefinition('template.finder.bundles');

        $dirs = [];
        $templatesDir = $bundlesDefinition->getArgument('$templatesDir');
        foreach ($container->getParameter('kernel.bundles') as $bundle => $class) {
            $reflection = new \ReflectionClass($class);
            if (is_dir($dir = dirname($reflection->getFileName()))) {
                if (is_dir(sprintf('%s/Resources/%s', $dir, $templatesDir))) {
                    $dirs[] = $dir;
                }
            }
        }
        $bundlesDefinition->replaceArgument('$bundlesDirs', $dirs);

        $definition = $container->findDefinition('template.finder.chain');
        $definition->addMethodCall('addFinder', [new Reference('template.finder.bundles')]);
    }

    protected function registerThemeTemplateFinderConfiguration(array $config, ContainerBuilder $container, XmlFileLoader $loader)
    {
        $definition = $container->findDefinition('template.finder.chain');
        $definition->addMethodCall('addFinder', [new Reference('template.finder.theme')]);

        $container->setParameter('template.theme', $config['theme']);
    }
}
