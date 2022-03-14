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
                    if (!response.status) {
                        alert(response.message);
                    } else {
                        location.reload();
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
                        if (response.status) {
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
                        if (response.status) {
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
                        if (response.status) {
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
                        {if $vo['_install']}
                        {if $vo['_disabled']}
                        <a class="btn btn-sm btn-outline-secondary" style="cursor:pointer;" href="javascript:change('{$name}', 0);" data-bs-toggle="tooltip" title="应用已停用，点此切换">未运行...</a>
                        <a class="btn btn-sm btn-outline-secondary" style="cursor:pointer;" href="javascript:uninstall('{$name}');" data-bs-toggle="tooltip" title="点此卸载此插件">卸载</a>
                        {else}
                        <a class="btn btn-sm btn-success" style="cursor:pointer;" href="javascript:change('{$name}', 1);" data-bs-toggle="tooltip" title="应用运行中，点此切换">运行中...</a>
                        {/if}
                        {else}
                        <a class="btn btn-sm btn-outline-danger" style="cursor:pointer;" href="javascript:install('{$name}');" data-bs-toggle="tooltip" title="该插件未安装，点此安装">安装</a>
                        <a class="btn btn-sm btn-outline-secondary" style="cursor:pointer;" href="javascript:del('{$name}');" data-bs-toggle="tooltip" title="彻底删除该插件">删除</a>
                        {/if}
                    </div>
                </div>
            </div>
        </div>
        {/foreach}
    </div>
</div>
{include common/footer@ebcms/admin}