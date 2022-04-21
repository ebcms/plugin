<?php

declare(strict_types=1);

namespace App\Ebcms\Plugin\Http;

use App\Ebcms\Admin\Http\Common;
use DigPHP\Template\Template;
use Ebcms\Framework\Framework;

class Index extends Common
{
    public function get(
        Template $template
    ) {

        $plugins = [];

        foreach (glob(Framework::getRoot() . '/plugin/*/src/library/App.php') as $file) {

            $app = substr($file, strlen(Framework::getRoot() . '/'), -strlen('/src/library/App.php'));

            $json_file = Framework::getRoot() . '/' . $app . '/plugin.json';
            $json = file_exists($json_file) ? json_decode(file_get_contents($json_file), true) : [];

            $plugins[] = [
                'name' => $app,
                'version' => $json['version'] ?? '',
                'title' => $json['title'] ?? '',
                'description' => $json['description'] ?? '',
                'icon' => $json['icon'] ?? '',
                'install' => file_exists(Framework::getRoot() . '/config/' . $app . '/install.lock'),
                'disabled' => file_exists(Framework::getRoot() . '/config/' . $app . '/disabled.lock')
            ];
        }

        return $template->renderFromFile('index@ebcms/plugin', [
            'plugins' => $plugins,
        ]);
    }
}
