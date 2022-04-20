<?php

declare(strict_types=1);

namespace App\Ebcms\Plugin\Http;

use App\Ebcms\Admin\Http\Common;
use Composer\InstalledVersions;
use DigPHP\Request\Request;
use Ebcms\Framework\Framework;

class Disable extends Common
{

    public function post(
        Request $request
    ) {
        $name = $request->post('name');
        if (InstalledVersions::isInstalled($name)) {
            return $this->error('系统应用不支持该操作！');
        }

        if (!InstalledVersions::isInstalled($name)) {
            $install_lock = Framework::getRoot() . '/config/' . $name . '/install.lock';
            if (!file_exists($install_lock)) {
                return $this->error('未安装！');
            }
        }

        $disabled_file = Framework::getRoot() . '/config/' . $name . '/disabled.lock';
        if ($request->post('disabled')) {
            if (!file_exists($disabled_file)) {
                if (!is_dir(dirname($disabled_file))) {
                    mkdir(dirname($disabled_file), 0755, true);
                }
                touch($disabled_file);
            }
        } else {
            if (file_exists($disabled_file)) {
                unlink($disabled_file);
            }
        }
        return $this->success('操作成功！');
    }
}
