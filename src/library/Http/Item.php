<?php

declare(strict_types=1);

namespace App\Ebcms\Plugin\Http;

use App\Psrphp\Admin\Http\Common;
use App\Ebcms\Plugin\Model\Server;
use App\Psrphp\Admin\Lib\Response;
use Composer\Autoload\ClassLoader;
use PsrPHP\Request\Request;
use PsrPHP\Template\Template;
use ReflectionClass;

class Item extends Common
{
    public function get(
        Request $request,
        Server $server,
        Template $template
    ) {
        $res = $server->query('/detail', [
            'id' => $request->get('id'),
        ]);
        if ($res['errcode']) {
            return Response::error($res['message'], $res['redirect_url'] ?? '', $res['errcode']);
        }
        return $template->renderFromFile('item@ebcms/plugin', [
            'plugin' => $res['data'],
            'type' => $this->getType($res['data']),
        ]);
    }

    private function getType(array $item): string
    {
        $root = dirname(dirname(dirname((new ReflectionClass(ClassLoader::class))->getFileName())));
        if (!is_dir($root . '/plugin/' . $item['name'])) {
            return 'install';
        }
        $json_file = $root . '/plugin/' . $item['name'] . '/config.json';
        if (!file_exists($json_file)) {
            return 'error';
        }
        $json = json_decode(file_get_contents($json_file), true);
        if (!isset($json['id'])) {
            return 'error';
        }
        if ($json['id'] != $item['id']) {
            return 'error';
        }
        if (!isset($json['version'])) {
            return 'error';
        }
        if ($json['version'] != $item['version']) {
            return 'upgrade';
        } else {
            return '';
        }
    }
}
