{include common/header@ebcms/admin}
<div class="container">
    <div class="my-4 h1">插件</div>
    <script>
        function change(name, disabled) {
            $.ajax({
                type: "POST",
                url: "{echo $router->build('/ebcms/plugin/disable')}",
                data: {
                    name: name,
                    disabled: disabled
                },
                dataType: "JSON",
                success: function(response) {
                    if (response.code == 0) {
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                },
                error: function() {
                    alert('发生错误~');
                }
            });
        }

        function del(name) {
            if (confirm('确定彻底删除该插件吗？删除后无法恢复！')) {
                $.ajax({
                    type: "POST",
                    url: "{echo $router->build('/ebcms/plugin/delete')}",
                    data: {
                        name: name
                    },
                    dataType: "JSON",
                    success: function(response) {
                        alert(response.message);
                        if (response.code == 0) {
                            location.reload();
                        }
                    },
                    error: function() {
                        alert('发生错误~');
                    }
                });
            }
        }

        function install(name) {
            if (confirm('确定安装该插件吗？')) {
                $.ajax({
                    type: "POST",
                    url: "{echo $router->build('/ebcms/plugin/install')}",
                    data: {
                        name: name
                    },
                    dataType: "JSON",
                    success: function(response) {
                        alert(response.message);
                        if (response.code == 0) {
                            location.reload();
                        }
                    },
                    error: function() {
                        alert('发生错误~');
                    }
                });
            }
        }

        function uninstall(name) {
            if (confirm('确定卸载该插件吗？')) {
                $.ajax({
                    type: "POST",
                    url: "{echo $router->build('/ebcms/plugin/uninstall')}",
                    data: {
                        name: name
                    },
                    dataType: "JSON",
                    success: function(response) {
                        alert(response.message);
                        if (response.code == 0) {
                            location.reload();
                        }
                    },
                    error: function() {
                        alert('发生错误~');
                    }
                });
            }
        }
        $(function() {
            $('[data-bs-toggle="tooltip"]').tooltip();
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/holderjs@2.9.6/holder.min.js" integrity="sha256-yF/YjmNnXHBdym5nuQyBNU62sCUN9Hx5awMkApzhZR0=" crossorigin="anonymous"></script>
    <div class="row row-cols-auto gy-4">
        {foreach $plugins as $name => $vo}
        <div class="col">
            <div class="d-flex" style="width: 350px;">
                <div class="me-3">
                    {if isset($vo['icon']) && $vo['icon']}
                    <img style="cursor:pointer;height:100px;width:100px;" class="img-thumbnail img-fluid" src="{echo $vo['icon']}">
                    {else}
                    <img style="cursor:pointer;height:100px;width:100px;" class="img-thumbnail img-fluid" data-src="holder.js/80x80?auto=yes&text=nopic&size=16">
                    {/if}
                </div>
                <div class="flex-fill position-relative">
                    <div class="mt-0 mb-2"><strong>{$vo['title']??$name}</strong></div>
                    <div class="text-muted">{$vo['description']??'暂无介绍'}</div>
                    <div class="position-absolute bottom-0 start-0">
                        <div class="dropdown">
                            {if $vo['_install']}
                            {if $vo['_disabled']}
                            <button class="btn btn-warning btn-sm dropdown-toggle" type="button" id="dropdownMenu_{$name}" data-bs-toggle="dropdown" aria-expanded="false">
                                未运行
                            </button>
                            {else}
                            <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="dropdownMenu_{$name}" data-bs-toggle="dropdown" aria-expanded="false">
                                运行中
                            </button>
                            {/if}
                            {else}
                            <button class="btn btn-secondary btn-sm dropdown-toggle" type="button" id="dropdownMenu_{$name}" data-bs-toggle="dropdown" aria-expanded="false">
                                未安装
                            </button>
                            {/if}
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenu_{$name}">
                                {if $vo['_install']}
                                {if $vo['_disabled']}
                                <li><button class="dropdown-item" type="button" onclick="change('{$name}', 0);" data-bs-toggle="tooltip" title="应用未运行，点此切换">运行</button></li>
                                <li><button class="dropdown-item" type="button" onclick="uninstall('{$name}');" data-bs-toggle="tooltip" title="点此卸载此插件">卸载</button></li>
                                {else}
                                <li><button class="dropdown-item" type="button" onclick="change('{$name}', 1);" data-bs-toggle="tooltip" title="应用运行中，点此切换">停止</button></li>
                                {/if}
                                {else}
                                <li><button class="dropdown-item" type="button" onclick="install('{$name}');" data-bs-toggle="tooltip" title="该插件未安装，点此安装">安装</button></li>
                                <li><button class="dropdown-item" type="button" onclick="del('{$name}');" data-bs-toggle="tooltip" title="彻底删除该插件">删除</button></li>
                                {/if}
                                {if $vo['_menus']}
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                {foreach $vo['_menus'] as $m}
                                <li><a class="dropdown-item" href="{$m.url}">{$m.title}</a></li>
                                {/foreach}
                                {/if}
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {/foreach}
    </div>
</div>
{include common/footer@ebcms/admin}