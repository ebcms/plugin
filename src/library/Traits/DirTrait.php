<?php

declare(strict_types=1);

namespace App\Ebcms\Plugin\Traits;

trait DirTrait
{

    final public function delDir($dir): bool
    {
        if (is_dir($dir)) {
            $dh = opendir($dir);
            while ($file = readdir($dh)) {
                if ($file != '.' && $file != '..') {
                    $fullpath = $dir . '/' . $file;
                    if (!is_dir($fullpath)) {
                        unlink($fullpath);
                    } else {
                        $this->delDir($fullpath);
                    }
                }
            }
            closedir($dh);
            rmdir($dir);
        }
        return true;
    }

    final public function copyDir($source, $dest): bool
    {
        if (!file_exists($dest)) mkdir($dest, 0644, true);
        $handle = opendir($source);
        while (($item = readdir($handle)) !== false) {
            if ($item == '.' || $item == '..') continue;
            $_source = $source . '/' . $item;
            $_dest = $dest . '/' . $item;
            if (is_file($_source)) copy($_source, $_dest);
            if (is_dir($_source)) $this->copyDir($_source, $_dest);
        }
        closedir($handle);
        return true;
    }
}
