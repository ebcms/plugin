<?php

declare(strict_types=1);

namespace App\Ebcms\Plugin\Http;

use App\Ebcms\Admin\Http\Common;
use App\Ebcms\Plugin\Model\Server;
use PsrPHP\Request\Request;

class Query extends Common
{
    public function get(
        Request $request,
        Server $server
    ) {
        $res = $server->query('/' . $request->get('api'), (array) $request->get('params'));
        if ($res['errcode']) {
            return $this->error($res['message'], $res['redirect_url'] ?? '', $res['errcode']);
        } else {
            return $this->success('获取成功', $res['data']);
        }
    }
}
