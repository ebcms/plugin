<?php

declare(strict_types=1);

namespace App\Ebcms\Plugin\Http;

use App\Psrphp\Admin\Http\Common;
use App\Psrphp\Admin\Lib\Dir;
use App\Psrphp\Admin\Lib\Response;
use PsrPHP\Session\Session;
use Exception;
use Throwable;
use ZipArchive;

class Cover extends Common
{
    public function get(
        Session $session
    ) {
        try {
            $item = $session->get('item');
            if (is_dir($item['item_path'])) {
                $json_file = $item['item_path'] . '/config.json';
                if (!file_exists($json_file)) {
                    return Response::error('与本地插件冲突~');
                }
                $json = json_decode(file_get_contents($json_file), true);
                if (!isset($json['name'])) {
                    return Response::error('与本地插件冲突~');
                }
                if ($json['name'] != $item['name']) {
                    return Response::error('与本地插件冲突~');
                }
                Dir::del($item['item_path']);
            }
            $this->unZip($item['tmpfile'], $item['item_path']);
            return Response::success('文件更新成功!');
        } catch (Throwable $th) {
            return Response::error($th->getMessage());
        }
    }

    private function unZip($file, $destination)
    {
        $zip = new ZipArchive();
        if ($zip->open($file) !== true) {
            throw new Exception('Could not open archive');
        }
        if (true !== $zip->extractTo($destination)) {
            throw new Exception('Could not extractTo ' . $destination);
        }
        if (true !== $zip->close()) {
            throw new Exception('Could not close archive ' . $file);
        }
    }
}
