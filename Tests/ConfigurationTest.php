<?php

/*
 * This file is part of Mindy Framework.
 * (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\TemplateBundle\Tests\DependencyInjection;

use Mindy\Bundle\TemplateBundle\DependencyInjection\Configuration;
use Mindy\Template\Renderer;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Processor;

class ConfigurationTest extends TestCase
{
    public function testDefaultConfig()
    {
        $processor = new Processor();
        $config = $processor->processConfiguration(new Configuration(), []);

        $this->assertEquals($config, self::getBundleDefaultConfig());
    }

    protected static function getBundleDefaultConfig()
    {
        return [
            'mode' => Renderer::RECOMPILE_NORMAL,
            'cache_dir' => '%kernel.cache_dir%/templates',
            'auto_escape' => true,
            'bundles' => [
                'enabled' => true,
                'templatesDir' => 'templates',
            ],
            'theme' => [
                'enabled' => false,
                'basePath' => '%kernel.root_dir%/Resources',
                'theme' => 'default',
                'templatesDir' => 'templates',
            ],
            'templates' => [
                'enabled' => true,
                'basePath' => '%kernel.root_dir%/Resources',
                'templatesDir' => 'templates',
            ],
        ];
    }
}
