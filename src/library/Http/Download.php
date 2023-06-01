<?php

declare(strict_types=1);

namespace App\Ebcms\Plugin\Http;

use App\Psrphp\Admin\Http\Common;
use App\Psrphp\Admin\Lib\Curl;
use App\Psrphp\Admin\Lib\Response;
use PsrPHP\Session\Session;
use Throwable;

class Download extends Common
{
    public function get(
        Session $session,
        Curl $curl
    ) {
        try {
            $item = $session->get('item');
            if (false === $content = $curl->get($item['source'])) {
                return Response::error('文件下载失败~');
            }

            if (md5($content) != $item['md5']) {
                return Response::error('文件校验失败！');
            }

            $tmpfile = tempnam(sys_get_temp_dir(), 'plugininstall');
            if (false == file_put_contents($tmpfile, $content)) {
                return Response::error('文件(' . $tmpfile . ')写入失败，请检查权限~');
            }
            $item['tmpfile'] = $tmpfile;
            $session->set('item', $item);
            return Response::success('下载成功！', $item);
        } catch (Throwable $th) {
            return Response::error($th->getMessage());
        }
    }
}
