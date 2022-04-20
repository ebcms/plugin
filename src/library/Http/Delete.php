<?php

declare(strict_types=1);

namespace App\Ebcms\Plugin\Http;

use App\Ebcms\Admin\Http\Common;
use App\Ebcms\Plugin\Traits\DirTrait;
use Composer\InstalledVersions;
use DigPHP\Request\Request;
use Ebcms\Framework\Framework;

class Delete extends Common
{
    use DirTrait;

    public function post(
        Request $request
    ) {
        $name = $request->post('name');
        if (InstalledVersions::isInstalled($name)) {
            return $this->error('系统应用不支持该操作！');
        }

        $install_lock = Framework::getRoot() . '/config/' . $name . '/install.lock';
        if (file_exists($install_lock)) {
            return $this->error('请先卸载！');
        }
        $disabled_lock = Framework::getRoot() . '/config/' . $name . '/disabled.lock';
        if (!file_exists($disabled_lock)) {
            return $this->error('请先停用！');
        }
        $this->delDir(Framework::getRoot() . '/' . $name);
        $this->delDir(Framework::getRoot() . '/config/' . $name);
        return $this->success('操作成功！');
    }
}
