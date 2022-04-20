{include common/header@ebcms/admin}
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
    }
</script>
<div class="container">
    <div class="my-4">
        <div class="h1">插件管理</div>
        <div class="text-muted fw-light">可以通过插件扩展系统功能~</div>
    </div>
    <div class="d-flex flex-column gap-4">
        {foreach $plugins as $plugin}
        <div class="d-flex gap-3">
            <div>
                <img src="{echo $plugin['icon']}" class="img-thumbnail" width="100" alt="">
            </div>
            <div class="d-flex flex-column gap-2 flex-grow-1 bg-light p-3">
                <div><span class="fs-6 fw-bold">{$plugin['title']?:'-'}</span><sup class="ms-1 text-secondary">{$plugin['version']??''}</sup></div>
                <div>{$plugin.description}</div>
                <div><code>{$plugin.name}</code> </div>
                <div class="d-flex gap-2">
                    {if $plugin['install']}
                    {if $plugin['disabled']}
                    <button class="btn btn-sm btn-warning" type="button" onclick="change('{$plugin.name}', 0);" data-bs-toggle="tooltip" data-bs-placement="right" title="插件未运行，点此切换">未运行</button>
                    <button class="btn btn-sm btn-warning" type="button" onclick="uninstall('{$plugin.name}');" data-bs-toggle="tooltip" data-bs-placement="right" title="点此卸载此插件">卸载</button>
                    {else}
                    <button class="btn btn-sm btn-primary" type="button" onclick="change('{$plugin.name}', 1);" data-bs-toggle="tooltip" data-bs-placement="right" title="插件运行中，点此切换">运行中</button>
                    {/if}
                    {else}
                    <button class="btn btn-sm btn-primary" type="button" onclick="install('{$plugin.name}');" data-bs-toggle="tooltip" data-bs-placement="right" title="该插件未安装，点此安装">安装</button>
                    <!-- <button class="btn btn-sm btn-warning" type="button" onclick="del('{$plugin.name}');" data-bs-toggle="tooltip" data-bs-placement="right" title="彻底删除该插件">删除</button> -->
                    {/if}
                </div>
            </div>
        </div>
        {/foreach}
    </div>
</div>
{include common/footer@ebcms/admin}