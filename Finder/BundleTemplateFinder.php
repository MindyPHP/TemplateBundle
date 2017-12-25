<?php

declare(strict_types=1);

/*
 * Studio 107 (c) 2017 Maxim Falaleev
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Mindy\Bundle\TemplateBundle\Finder;

use Mindy\Template\Finder\FinderInterface;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

class BundleTemplateFinder implements FinderInterface
{
    /**
     * @var array
     */
    protected $paths = [];

    /**
     * BundleTemplateFinder constructor.
     *
     * @param array $bundles
     */
    public function __construct(array $bundles = [])
    {
        $paths = [];
        foreach ($bundles as $bundle) {
            /** @var BundleInterface $bundle */
            $paths = array_merge(
                $paths,
                glob(sprintf('%s/Resources/templates', $bundle->getPath()))
            );
        }

        $this->paths = $paths;
    }

    /**
     * @param $templatePath
     *
     * @return null|string absolute path of template if founded
     */
    public function find(string $templatePath)
    {
        foreach ($this->paths as $path) {
            $filePath = sprintf(
                '%s/%s',
                rtrim($path, '/'),
                ltrim($templatePath, '/')
            );

            if (is_file($filePath)) {
                return $filePath;
            }
        }

        return null;
    }

    /**
     * @return array of available template paths
     */
    public function getPaths(): array
    {
        return $this->paths;
    }

    /**
     * @param string $path
     *
     * @return string
     */
    public function getContents(string $path)
    {
        return file_get_contents($path);
    }

    /**
     * @param string $path
     *
     * @return int|null
     */
    public function lastModified(string $path)
    {
        return filemtime($path);
    }
}
