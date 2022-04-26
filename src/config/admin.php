<?php

use DiggPHP\Router\Router;
use DiggPHP\Framework\Framework;

return [
    'menus' => Framework::execute(function (
        Router $router
    ): array {
        $res = [];
        $res[] = [
            'title' => '插件管理',
            'url' => $router->build('/ebcms/plugin/index'),
            'tags' => ['important'],
            'priority' => 3,
            'icon' => 'data:image/svg+xml;base64,' . base64_encode('<svg class="icon" style="width: 1em;height: 1em;vertical-align: middle;fill: currentColor;overflow: hidden;" viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg" p-id="5063"><path d="M887.868235 255.879529L513.024 469.052235l-374.904471-213.232941a29.997176 29.997176 0 0 1 1.505883-52.886588L485.737412 29.093647a57.825882 57.825882 0 0 1 54.452706 0l346.23247 172.333177c21.082353 12.047059 21.082353 42.345412 1.505883 54.452705z" fill="#FF6B40" p-id="5064"></path><path d="M437.368471 986.051765l-317.44-196.547765a57.344 57.344 0 0 1-30.238118-51.380706V342.016c0-24.154353 25.660235-37.767529 45.296941-25.660235l317.500235 196.487529a59.030588 59.030588 0 0 1 28.732236 51.440941v396.107294c1.505882 22.648471-24.154353 37.767529-43.851294 25.660236z" fill="#5D7FDD" p-id="5065"></path><path d="M588.559059 986.051765l317.44-196.547765a59.030588 59.030588 0 0 0 28.79247-51.380706V342.016a29.696 29.696 0 0 0-45.357176-25.660235L571.934118 512.843294a59.030588 59.030588 0 0 0-28.732236 51.440941v396.107294c0 22.648471 25.720471 37.767529 45.357177 25.660236z" fill="#52C41A" p-id="5066"></path></svg>'),
        ];
        return $res;
    })
];
