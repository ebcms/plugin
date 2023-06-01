<?php

declare(strict_types=1);

namespace App\Ebcms\Plugin\Http;

use App\Psrphp\Admin\Http\Common;
use App\Ebcms\Plugin\Model\Server;
use PsrPHP\Request\Request;
use PsrPHP\Template\Template;

class Item extends Common
{
    public function get(
        Request $request,
        Server $server,
        Template $template
    ) {
        $data = [];
        $res = $server->query('/detail', [
            'name' => $request->get('name'),
        ]);
        if ($res['errcode']) {
            return $this->error($res['message'], $res['redirect_url'] ?? '', $res['errcode']);
        }
        $data['plugin'] = $res['data'];
        $data['type'] = 'install';
        $installed = $server->getInstalled();
        if (isset($installed[$request->get('name')])) {
            $data['type'] = 'upgrade';
        } else {
            $data['type'] = 'install';
        }
        return $template->renderFromFile('item@ebcms/plugin', $data);
    }
}
