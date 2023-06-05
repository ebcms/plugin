<?php

declare(strict_types=1);

namespace App\Ebcms\Plugin\Http;

use App\Psrphp\Admin\Http\Common;
use App\Ebcms\Plugin\Model\Server;
use App\Psrphp\Admin\Lib\Response;
use Composer\Autoload\ClassLoader;
use PsrPHP\Request\Request;
use ReflectionClass;
use Throwable;

class Check extends Common
{
    public function get(
        Server $server,
        Request $request
    ) {
        try {
            $root = dirname(dirname(dirname((new ReflectionClass(ClassLoader::class))->getFileName())));
            $json_file = $root . '/plugin/' . $request->get('name') . '/config.json';
            if (!file_exists($json_file)) {
                return Response::error('配置文件不存在，可能非官方插件~');
            }
            $json = json_decode(file_get_contents($json_file), true);
            if (!isset($json['id'])) {
                return Response::error('非官方市场插件~');
            }
            $param['id'] = $json['id'];
            if (!isset($json['version'])) {
                return Response::error('无法确定本地版本号，可能非官方插件~');
            }
            $param['version'] = $json['version'];
            $res = $server->query('/check', $param);
            if ($res['errcode']) {
                return Response::error($res['message'], $res['redirect_url'] ?? '', $res['errcode'], $res['data'] ?? null);
            }
            return Response::success($res['message'], $res['data'] ?? null);
        } catch (Throwable $th) {
            return Response::error($th->getMessage());
        }
    }
}
