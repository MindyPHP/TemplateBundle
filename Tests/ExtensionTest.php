<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\TemplateBundle\Tests\DependencyInjection;

use Mindy\Bundle\TemplateBundle\DependencyInjection\TemplateExtension;
use Mindy\Template\Finder\ChainFinder;
use Mindy\Template\TemplateEngine;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use PHPUnit\Framework\TestCase;

class ExtensionTest extends TestCase
{
    /**
     * @var TemplateExtension
     */
    protected $extension;
    /**
     * @var ContainerBuilder
     */
    protected $container;

    public function setUp()
    {
        $this->extension = new TemplateExtension();

        $this->container = new ContainerBuilder();
        $this->container->setParameter('kernel.bundles', []);
        $this->container->setParameter('kernel.cache_dir', __DIR__.'/cache');
        $this->container->setParameter('kernel.root_dir', __DIR__);
        $this->container->registerExtension($this->extension);
    }

    public function testServices()
    {
        // An extension is only loaded in the container if a configuration is provided for it.
        // Then, we need to explicitely load it.
        $this->container->loadFromExtension($this->extension->getAlias());
        $this->container->compile();

        $this->assertTrue($this->container->has('templating.engine.mindy'));

        $this->assertTrue($this->container->has(TemplateEngine::class));
        $definition = $this->container->getDefinition(TemplateEngine::class);
        $this->assertSame(TemplateEngine::class, $definition->getClass());

        $this->assertTrue($this->container->has(ChainFinder::class));
    }

    public function testParameters()
    {
        // An extension is only loaded in the container if a configuration is provided for it.
        // Then, we need to explicitely load it.
        $this->container->loadFromExtension($this->extension->getAlias());
        $this->container->compile();

        $this->assertSame(
            sprintf("%s/Resources/templates", __DIR__),
            $this->container->getParameter('mindy.template.path')
        );
        $this->assertSame('default', $this->container->getParameter('mindy.template.theme'));
        $this->assertSame(0, $this->container->getParameter('mindy.template.mode'));
        $this->assertTrue($this->container->getParameter('mindy.template.exception_handler'));
    }
}
