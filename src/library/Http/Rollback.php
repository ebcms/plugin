<?php

declare(strict_types=1);

namespace App\Ebcms\Plugin\Http;

use App\Psrphp\Admin\Http\Common;
use App\Psrphp\Admin\Lib\Dir;
use App\Psrphp\Admin\Lib\Response;
use Exception;
use PsrPHP\Session\Session;
use Throwable;
use ZipArchive;

class Rollback extends Common
{

    public function get(
        Session $session,
        Dir $dir
    ) {
        try {
            $item = $session->get('item');
            $dir->del($item['item_path']);
            $this->unZip($item['backup_file'], $item['item_path']);
        } catch (Throwable $th) {
            return Response::error('还原失败：' . $th->getMessage());
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
