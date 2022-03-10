{include common/header@ebcms/admin}
<table class="table table-bordered my-3">
    <tr>
        <th class="text-muted text-nowrap">名称</th>
        <td>{$plugin['title']??''} <sup>{$plugin['version']??''}</sup></td>
        <th class="text-muted text-nowrap">协议</th>
        <td>{$plugin['license']??''}</td>
    </tr>
    {if isset($plugin['homepage'])}
    <tr>
        <th class="text-muted text-nowrap">主页</th>
        <td colspan="3">
            <a href="{$plugin['homepage']}" target="_blank">{$plugin['homepage']}</a>
        </td>
    </tr>
    {/if}
    {if isset($plugin['authors']) && is_array($plugin['authors'])}
    <tr>
        <th class="text-muted text-nowrap">作者</th>
        <td colspan="3">
            {foreach $plugin['authors'] as $k=>$author}
            <ul class="list-unstyled bg-light border rounded shadow-sm p-2 {if $k>0}mt-2 mb-0{else}my-0{/if}">
                {foreach $author as $k=>$v}
                <li><span class="mr-2"><b class="text-uppercase">{$k}: </b>{$v}</span></li>
                {/foreach}
            </ul>
            {/foreach}
        </td>
    </tr>
    {/if}
</table>
<div id="readme"></div>
<script src="https://cdn.jsdelivr.net/npm/markdown-it@12.0.3/dist/markdown-it.min.js" integrity="sha256-w9HUyWlYpo2NY0GnFNkPqoxBdCNZNn1B3lgPQif2d2I=" crossorigin="anonymous"></script>
<script src="https://cdn.bootcdn.net/ajax/libs/highlight.js/10.1.1/highlight.min.js"></script>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/highlight.js@10.1.2/styles/vs.css">
<script>
    function base64Decode(input) {
        rv = window.atob(input);
        rv = escape(rv);
        rv = decodeURIComponent(rv);
        return rv;
    }
    var md = window.markdownit({
        highlight: function(str, lang) {
            if (lang && hljs.getLanguage(lang)) {
                try {
                    return '<pre class="hljs"><code>' +
                        hljs.highlight(lang, str, true).value +
                        '</code></pre>';
                } catch (__) {}
            }
            return '<pre class="hljs"><code>' + window.markdownit().utils.escapeHtml(str) + '</code></pre>';
        }
    });
    $("#readme").html(md.render(base64Decode("{echo base64_encode($readme??'__暂无介绍__')}")));
    $("#readme a").attr("target", "_blank");
    $("#readme table").addClass("table table-bordered my-3");
</script>
{include common/footer@ebcms/admin}
