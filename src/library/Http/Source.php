<?php

declare(strict_types=1);

namespace App\Ebcms\Plugin\Http;

use App\Ebcms\Admin\Http\Common;
use App\Ebcms\Plugin\Model\Server;
use Composer\Autoload\ClassLoader;
use PsrPHP\Psr16\LocalAdapter;
use PsrPHP\Request\Request;
use PsrPHP\Router\Router;
use PsrPHP\Session\Session;
use PsrPHP\Framework\Framework;
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
            $param = [
                'api' => $router->build('/ebcms/plugin/api', [
                    'token' => $token
                ]),
                'name' => $name,
            ];
            $root = dirname(dirname(dirname((new ReflectionClass(ClassLoader::class))->getFileName())));
            $json_file = $root . '/plugin/' . $name . '/config.json';
            if (file_exists($json_file)) {
                $json = json_decode(file_get_contents($json_file), true);
                $param['version'] = $json['version'];
            }

            $res = $server->query('/source', $param);
            if ($res['errcode']) {
                return $this->error($res['message'], $res['redirect_url'] ?? '', $res['errcode']);
            }
            if (null === $item = $cache->get($token)) {
                return $this->error('超时，请重新操作~');
            }
            $item['item_path'] = $root . '/plugin/' . $name;
            $session->set('item', $item);
            return $this->success($res['message']);
        } catch (Throwable $th) {
            return $this->error($th->getMessage());
        }
    }
}
