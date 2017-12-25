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

class ThemeTemplateFinder implements FinderInterface
{
    /**
     * @var string
     */
    protected $theme;
    /**
     * @var string
     */
    protected $themesPath;

    /**
     * ThemeTemplateFinder constructor.
     *
     * @param string $themesPath
     * @param string $theme
     */
    public function __construct(string $themesPath, string $theme = 'default')
    {
        $this->themesPath = $themesPath;
        $this->theme = $theme;
    }

    /**
     * @param string $theme
     */
    public function setTheme(string $theme)
    {
        $this->theme = $theme;
    }

    /**
     * @return string|null
     */
    public function getTheme(): string
    {
        return $this->theme;
    }

    /**
     * @param $templatePath
     *
     * @return null|string absolute path of template if founded
     */
    public function find(string $templatePath)
    {
        $themePath = current($this->getPaths());
        $path = sprintf('%s/%s', $themePath, ltrim($templatePath, '/'));

        if (is_file($path)) {
            return $path;
        }

        return null;
    }

    /**
     * @return array of available template paths
     */
    public function getPaths(): array
    {
        return (array) sprintf('%s/%s/templates', rtrim($this->themesPath, '/'), trim($this->theme, '/'));
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
