<?php

declare(strict_types=1);

namespace App\Ebcms\Plugin\Http;

use App\Ebcms\Admin\Http\Common;
use DigPHP\Framework\Framework;
use DigPHP\Request\Request;

use function Composer\Autoload\includeFile;

class Uninstall extends Common
{

    public function post(
        Request $request
    ) {
        $name = $request->post('name');

        $install_lock = Framework::getRoot() . '/config/plugin/' . $name . '/install.lock';
        if (!file_exists($install_lock)) {
            return $this->error('未安装！');
        }

        $disabled_lock = Framework::getRoot() . '/config/plugin/' . $name . '/disabled.lock';
        if (!file_exists($disabled_lock)) {
            return $this->error('请先停用！');
        }

        $plugin_dir = Framework::getRoot() . '/plugin/' . $name;
        if (file_exists($plugin_dir . '/uninstall.php')) {
            includeFile($plugin_dir . '/uninstall.php');
        }

        unlink($install_lock);

        return $this->success('操作成功！');
    }
}
