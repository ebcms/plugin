<?php

declare(strict_types=1);

namespace App\Ebcms\Plugin\Http;

use App\Ebcms\Admin\Http\Common;
use DigPHP\Framework\Framework;
use DigPHP\Template\Template;
use Psr\Http\Message\ResponseInterface;
use SplPriorityQueue;

class Index extends Common
{
    public function get(
        Template $template
    ): ResponseInterface {
        $plugins = [];
        foreach (glob(Framework::getRoot() . '/plugin/*/plugin.json') as $value) {
            $name = pathinfo(dirname($value), PATHINFO_FILENAME);
            $info = array_merge([
                'name' => '',
                'version' => '',
                'title' => '',
                'description' => '',
                'icon' => '',
                'license' => '',
            ], (array)json_decode(file_get_contents($value), true));
            $info['_install'] = file_exists(Framework::getRoot() . '/config/plugin/' . $name . '/install.lock');
            $info['_disabled'] = file_exists(Framework::getRoot() . '/config/plugin/' . $name . '/disabled.lock');
            $info['_menus'] = iterator_to_array($this->getMenus($name));
            $plugins[$name] = $info;
        }

        $html = $template->renderFromFile('index@ebcms/plugin', [
            'plugins' => $plugins,
        ]);
        return $this->html($html);
    }

    private function getMenus($name)
    {
        $args = [];
        $config_file = Framework::getRoot() . '/plugin/' . $name . '/src/config/admin.php';
        if (is_file($config_file)) {
            $tmp = $this->requireFile($config_file);
            if (!is_null($tmp)) {
                $args[] = $tmp;
            }
        }

        $config_file = Framework::getRoot() . '/config/plugin/' . $name . '/admin.php';
        if (is_file($config_file)) {
            $tmp = $this->requireFile($config_file);
            if (!is_null($tmp)) {
                $args[] = $tmp;
            }
        }

        $cfg = $args ? array_merge(...$args) : null;

        $menus = new SplPriorityQueue;

        foreach ($cfg['menus'] ?? [] as $menu) {
            $menu = array_merge([
                'title' => '',
                'url' => '',
                'icon' => 'data:image/svg+xml;base64,' . base64_encode('<svg class="icon" style="width: 1em;height: 1em;vertical-align: middle;fill: currentColor;overflow: hidden;" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="12938"><path d="" p-id="12939"></path></svg>'),
                'badge' => '',
                'priority' => 50,
            ], (array) $menu);
            if (
                $menu['title']
                && $menu['url']
            ) {
                $menus->insert($menu, $menu['priority']);
            }
        }

        return $menus;
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
