<?php

declare(strict_types=1);

namespace App\Ebcms\Plugin\Http;

use App\Ebcms\Admin\Http\Common;
use DigPHP\Template\Template;
use Ebcms\Framework\AppInterface;
use Ebcms\Framework\Framework;
use ReflectionClass;
use SplPriorityQueue;

class Index extends Common
{
    public function get(
        Template $template
    ) {

        $plugins = [];

        foreach (glob(Framework::getRoot() . '/plugin/*/src/library/App.php') as $file) {

            $app = substr($file, strlen(Framework::getRoot() . '/'), -strlen('/src/library/App.php'));
            require_once $file;

            $class_name = str_replace(['-', '/'], ['', '\\'], ucwords('\\App\\' . $app . '\\App', '/\\-'));
            if (
                !class_exists($class_name)
                || !is_subclass_of($class_name, AppInterface::class)
            ) {
                continue;
            }

            $json_file = Framework::getRoot() . '/' . $app . '/plugin.json';
            $json = file_exists($json_file) ? json_decode(file_get_contents($json_file), true) : [];

            $plugins[] = [
                'name' => $app,
                'version' => $json['version'] ?? '',
                'title' => $json['title'] ?? '',
                'description' => $json['description'] ?? '',
                'icon' => $json['icon'] ?? '',
                'install' => file_exists(Framework::getRoot() . '/config/' . $app . '/install.lock'),
                'disabled' => file_exists(Framework::getRoot() . '/config/' . $app . '/disabled.lock'),
                'menus' => iterator_to_array($this->getMenuList($app))
            ];
        }

        return $template->renderFromFile('index@ebcms/plugin', [
            'plugins' => $plugins,
        ]);
    }

    private function getMenuList($plugin)
    {
        $args = [];

        $class_name = str_replace(['-', '/'], ['', '\\'], ucwords('\\App\\' . $plugin . '\\App', '/\\-'));
        $reflector = new ReflectionClass($class_name);
        $config_file = dirname(dirname($reflector->getFileName())) . '/config/admin.php';

        if (is_file($config_file)) {
            $tmp = $this->requireFile($config_file);
            if (!is_null($tmp)) {
                $args[] = $tmp;
            }
        }

        $config_file = Framework::getRoot() . '/config/' . $plugin . '/admin.php';
        if (is_file($config_file)) {
            $tmp = $this->requireFile($config_file);
            if (!is_null($tmp)) {
                $args[] = $tmp;
            }
        }

        $cfg = $args ? array_merge(...$args) : null;

        $res = new SplPriorityQueue;
        foreach ($cfg['menus'] ?? [] as $menu) {
            $menu = array_merge([
                'title' => '',
                'url' => '',
                'priority' => 50,
            ], (array) $menu);
            if (
                $menu['title']
                && $menu['url']
            ) {
                $res->insert($menu, $menu['priority']);
            }
        }

        return $res;
    }

    private function requireFile(string $file)
    {
        static $loader;
        if (!$loader) {
            $loader = new class()
            {
                public function load(string $file)
                {
                    return require $file;
                }
            };
        }
        return $loader->load($file);
    }
}
