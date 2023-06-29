<?php

declare(strict_types=1);

namespace App\Ebcms\Plugin\Http;

use App\Psrphp\Admin\Http\Common;
use App\Ebcms\Plugin\Model\Server;
use App\Psrphp\Admin\Lib\Response;
use Composer\Autoload\ClassLoader;
use PsrPHP\Psr16\LocalAdapter;
use PsrPHP\Request\Request;
use PsrPHP\Router\Router;
use PsrPHP\Session\Session;
use ReflectionClass;
use Throwable;

class Source extends Common
{
    public function get(
        Request $request,
        Server $server,
        Router $router,
        LocalAdapter $cache,
        Session $session
    ) {
        try {
            $token = 'plugin_' . md5(uniqid() . rand(10000000, 99999999));
            $cache->set('pluginapitoken', $token, 30);
            $name = $request->get('name');
            $root = dirname(dirname(dirname((new ReflectionClass(ClassLoader::class))->getFileName())));

            $param = [
                'api' => $router->build('/ebcms/plugin/api', [
                    'token' => $token
                ]),
                'name' => $name,
            ];

            if (is_dir($root . '/plugin/' . $name)) {
                $json_file = $root . '/plugin/' . $name . '/config.json';
                if (!file_exists($json_file)) {
                    return Response::error('与本地插件冲突~');
                }
                $json = json_decode(file_get_contents($json_file), true);
                if (!isset($json['name'])) {
                    return Response::error('与本地插件冲突~');
                }
                if ($json['name'] != $request->get('name')) {
                    return Response::error('与本地插件冲突~');
                }
                if (!isset($json['version'])) {
                    return Response::error('与本地插件冲突~');
                }
                $param['version'] = $json['version'];
            }

            $res = $server->query('/source', $param);
            if ($res['errcode']) {
                return Response::error($res['message'], $res['redirect_url'] ?? '', $res['errcode']);
            }
            if (null === $item = $cache->get($token)) {
                return Response::error('超时，请重新操作~');
            }
            $item['item_path'] = $root . '/plugin/' . $name;
            $session->set('item', $item);
            return Response::success($res['message']);
        } catch (Throwable $th) {
            return Response::error($th->getMessage());
        }
    }
}
