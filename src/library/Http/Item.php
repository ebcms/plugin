<?php

declare(strict_types=1);

namespace App\Ebcms\Plugin\Http;

use App\Psrphp\Admin\Http\Common;
use App\Ebcms\Plugin\Model\Server;
use App\Psrphp\Admin\Lib\Response;
use PsrPHP\Request\Request;
use PsrPHP\Template\Template;

class Item extends Common
{
    public function get(
        Request $request,
        Server $server,
        Template $template
    ) {
        $res = $server->query('/detail', [
            'name' => $request->get('name'),
        ]);
        if ($res['errcode']) {
            return Response::error($res['message'], $res['redirect_url'] ?? '', $res['errcode']);
        }
        return $template->renderFromFile('item@ebcms/plugin', [
            'plugin' => $res['data'],
        ]);
    }
}
