<?php

declare(strict_types=1);

namespace App\Ebcms\Plugin\Http;

use App\Ebcms\Admin\Http\Common;
use Composer\InstalledVersions;
use DiggPHP\Request\Request;
use DiggPHP\Framework\Framework;

class Uninstall extends Common
{

    public function post(
        Request $request
    ) {
        $name = $request->post('name');
        if (InstalledVersions::isInstalled($name)) {
            return $this->error('系统应用不支持该操作！');
        }

        $install_lock = Framework::getRoot() . '/config/' . $name . '/install.lock';
        if (!file_exists($install_lock)) {
            return $this->error('未安装！');
        }

        $disabled_lock = Framework::getRoot() . '/config/' . $name . '/disabled.lock';
        if (!file_exists($disabled_lock)) {
            return $this->error('请先停用！');
        }

        $class_name = str_replace(['-', '/'], ['', '\\'], ucwords('\\App\\' . $name . '\\App', '/\\-'));
        $action = 'onUninstall';
        if (method_exists($class_name, $action)) {
            Framework::execute([$class_name, $action]);
        }

        unlink($install_lock);

        return $this->success('操作成功！');
    }
}
