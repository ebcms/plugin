<?php

declare(strict_types=1);

namespace App\Ebcms\Plugin\Http;

use App\Psrphp\Admin\Http\Common;
use App\Ebcms\Plugin\Model\Server;
use PsrPHP\Template\Template;

class Index extends Common
{
    public function get(
        Template $template,
        Server $server
    ) {
        return $template->renderFromFile('index@ebcms/plugin', [
            'installed' => $server->getInstalled()
        ]);
    }
}
