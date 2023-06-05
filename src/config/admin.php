<?php

use App\Psrphp\Admin\Model\Account;
use App\Ebcms\Plugin\Http\Index;
use PsrPHP\Framework\Framework;
use PsrPHP\Router\Router;

return [
    'menus' => Framework::execute(function (
        Account $account,
        Router $router
    ): array {
        $menus = [];
        if ($account->checkAuth(Index::class)) {
            $menus[] = [
                'title' => '应用商店',
                'url' => $router->build('/ebcms/plugin/index'),
            ];
        }
        return $menus;
    }),
];
