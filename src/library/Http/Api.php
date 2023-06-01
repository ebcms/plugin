<?php

declare(strict_types=1);

namespace App\Ebcms\Plugin\Http;

use App\Psrphp\Admin\Lib\Response;
use App\Psrphp\Admin\Traits\RestfulTrait;
use PsrPHP\Psr16\LocalAdapter;
use PsrPHP\Request\Request;

class Api
{
    use RestfulTrait;

    public function post(
        Request $request,
        LocalAdapter $cache
    ) {
        if (!$token = $request->get('token')) {
            return Response::error('token校验失败！');
        }
        if ($token != $cache->get('pluginapitoken')) {
            return Response::error('token校验失败！');
        }
        $cache->set($token, $request->post(), 10);
        return Response::success('success');
    }
}
