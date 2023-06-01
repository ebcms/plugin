<?php

declare(strict_types=1);

namespace App\Ebcms\Plugin\Http;

use App\Psrphp\Admin\Http\Common;
use App\Psrphp\Admin\Lib\Response;
use Composer\Autoload\ClassLoader;
use PsrPHP\Session\Session;
use PsrPHP\Framework\Framework;
use ReflectionClass;
use Throwable;

class Install extends Common
{
    public function get(
        Session $session
    ) {
        try {
            $item = $session->get('item');
            $root = dirname(dirname(dirname((new ReflectionClass(ClassLoader::class))->getFileName())));

            $json_file = $item['item_path'] . '/config.json';
            if (!is_file($json_file)) {
                return Response::error('文件无效！');
            }
            $json = (array) json_decode(file_get_contents($json_file), true);
            if (
                !isset($json['name']) ||
                $json['name'] != $item['name'] ||
                !isset($json['version']) ||
                $json['version'] != $item['version']
            ) {
                return Response::error('文件无效！');
            }

            $lock_file = $root . '/config/plugin/' . $item['name'] . '/install.lock';
            $class_name = str_replace(['-', '/'], ['', '\\'], ucwords('App\\' . $item['name'] . '\\PsrPHP\\Script', '/\\-'));
            $action = is_file($lock_file) ? 'onUpdate' : 'onInstall';
            if (method_exists($class_name, $action)) {
                Framework::execute([$class_name, $action]);
            }

            if (is_file($item['tmpfile'])) {
                unlink($item['tmpfile']);
            }

            if (!is_dir(dirname($lock_file))) {
                mkdir(dirname($lock_file), 0755, true);
            }
            if (is_file(dirname($lock_file) . '/disabled.lock')) {
                unlink(dirname($lock_file) . '/disabled.lock');
            }
            file_put_contents($lock_file, json_encode($item, JSON_UNESCAPED_UNICODE));

            $session->delete('item');

            return Response::success('安装成功!');
        } catch (Throwable $th) {
            return Response::error($th->getMessage());
        }
    }
}
