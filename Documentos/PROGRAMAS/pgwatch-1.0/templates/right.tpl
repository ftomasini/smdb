<div id="right">
<!-- widget -->
{{if isset($menufile) && $menufile!=""}}
<div class="widget" id="rightmenu">
<ul>
{{include file=$menufile selected="rm1"}}
</ul>
</div>
{{/if}}
{{if $bottomfile==""}}{{include file="rightbottom.tpl"}}
{{else}}{{include file=$bottomfile}}
{{/if}}
</div>
