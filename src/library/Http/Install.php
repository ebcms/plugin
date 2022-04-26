<?php

declare(strict_types=1);

namespace App\Ebcms\Plugin\Http;

use App\Ebcms\Admin\Http\Common;
use Composer\Autoload\ClassLoader;
use Composer\InstalledVersions;
use DiggPHP\Request\Request;
use DiggPHP\Framework\Framework;

class Install extends Common
{
    public function post(
        Request $request
    ) {
        $name = $request->post('name');
        if (InstalledVersions::isInstalled($name)) {
            return $this->error('系统应用不支持该操作！');
        }

        $install_lock = Framework::getRoot() . '/config/' . $name . '/install.lock';
        if (file_exists($install_lock)) {
            return $this->error('已经安装，若要重装请先卸载！');
        }

        $loader = new ClassLoader();
        $loader->addPsr4(
            str_replace(['-', '/'], ['', '\\'], ucwords('App\\' . $name . '\\', '/\\-')),
            Framework::getRoot() . '/' . $name . '/src/library/'
        );
        $loader->register();

        $class_name = str_replace(['-', '/'], ['', '\\'], ucwords('\\App\\' . $name . '\\App', '/\\-'));
        $action = 'onInstall';
        if (method_exists($class_name, $action)) {
            Framework::execute([$class_name, $action]);
        }

        if (!is_dir(dirname($install_lock))) {
            mkdir(dirname($install_lock), 0755, true);
        }
        file_put_contents($install_lock, date(DATE_ISO8601));

        return $this->success('操作成功！');
    }
}
