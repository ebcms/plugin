<?php

declare(strict_types=1);

namespace App\Ebcms\Plugin\Http;

use App\Psrphp\Admin\Http\Common;
use App\Ebcms\Plugin\Model\Server;
use App\Psrphp\Admin\Lib\Response;
use PsrPHP\Request\Request;

class Query extends Common
{
    public function get(
        Request $request,
        Server $server
    ) {
        $res = $server->query('/' . $request->get('api'), (array) $request->get('params'));
        if ($res['errcode']) {
            return Response::error($res['message'], $res['redirect_url'] ?? '', $res['errcode']);
        } else {
            return Response::success('获取成功', $res['data']);
        }
    }
}
