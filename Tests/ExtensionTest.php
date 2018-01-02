<?php

declare(strict_types=1);

/*
 * This file is part of Mindy Framework.
 * (c) 2018 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\TemplateBundle\Tests\DependencyInjection;

use Mindy\Bundle\TemplateBundle\DependencyInjection\TemplateExtension;
use Mindy\Template\Finder\ChainFinder;
use Mindy\Template\TemplateEngine;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\DependencyInjection\FrameworkExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class ExtensionTest extends TestCase
{
    /**
     * @var TemplateExtension[]
     */
    protected $extensions;
    /**
     * @var ContainerBuilder
     */
    protected $container;

    public function setUp()
    {
        $this->extensions = [
            new FrameworkExtension(),
            new TemplateExtension(),
        ];

        $this->container = new ContainerBuilder();
        $this->container->setParameter('kernel.container_class', 'testContainer');
        $this->container->setParameter('kernel.secret', '123');
        $this->container->setParameter('kernel.charset', 'utf-8');
        $this->container->setParameter('kernel.debug', true);
        $this->container->setParameter('kernel.bundles', []);
        $this->container->setParameter('kernel.cache_dir', __DIR__.'/cache');
        $this->container->setParameter('kernel.root_dir', __DIR__);
        foreach ($this->extensions as $ext) {
            $this->container->registerExtension($ext);
        }
    }

    public function testServices()
    {
        // An extension is only loaded in the container if a configuration is provided for it.
        // Then, we need to explicitely load it.
        foreach ($this->extensions as $ext) {
            $this->container->loadFromExtension($ext->getAlias());
        }
        $this->container->compile();

        $this->assertTrue($this->container->has('templating.engine.mindy'));

        $this->assertTrue($this->container->has(TemplateEngine::class));
        $definition = $this->container->getDefinition(TemplateEngine::class);
        $this->assertSame(TemplateEngine::class, $definition->getClass());

        $this->assertTrue($this->container->has(ChainFinder::class));

        $this->assertSame(
            sprintf('%s/Resources/templates', __DIR__),
            $this->container->getParameter('mindy.template.path')
        );
        $this->assertSame('default', $this->container->getParameter('mindy.template.theme'));
        $this->assertSame(0, $this->container->getParameter('mindy.template.mode'));
        $this->assertTrue($this->container->getParameter('mindy.template.exception_handler'));
    }
}
