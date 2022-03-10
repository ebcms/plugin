<?php

declare(strict_types=1);

namespace App\Ebcms\Plugin\Http;

use App\Ebcms\Admin\Http\Common;
use App\Ebcms\Plugin\Traits\DirTrait;
use DigPHP\Framework\Framework;
use DigPHP\Request\Request;

class Delete extends Common
{
    use DirTrait;

    public function post(
        Request $request
    ) {
        $name = $request->post('name');
        $install_lock = Framework::getRoot() . '/config/plugin/' . $name . '/install.lock';
        if (file_exists($install_lock)) {
            return $this->error('请先卸载！');
        }
        $disabled_lock = Framework::getRoot() . '/config/plugin/' . $name . '/disabled.lock';
        if (!file_exists($disabled_lock)) {
            return $this->error('请先停用！');
        }
        $this->delDir(Framework::getRoot() . '/plugin/' . $name);
        $this->delDir(Framework::getRoot() . '/config/plugin/' . $name);
        return $this->success('操作成功！');
    }
}
