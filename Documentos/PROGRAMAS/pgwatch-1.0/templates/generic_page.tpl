{{include file="`$rightmenu.file`" menufile="`$rightmenu.menufile`"}}
<div id="left">
{{include file="breadcrumb.tpl"}}

<a class="history" href="index.php#{{$url_self_params}}"><h1 class="page_headline">{{$title|escape:"html"}}</h1></a>
<div class="clear"></div>
{{$description}}
<div class="clear"></div>

{{if $toolbar_tpl}}{{include file="$toolbar_tpl"}}{{/if}}
{{if $show_dashboard_button}}
<button id="bt_dashboard" style="float:left">Add chart to dashboard</button>
<script language="javascript">
var bt_dashboard = new YAHOO.widget.Button("bt_dashboard", {onclick:{fn:function(){add_to_dashboard('{{$url_fixed_params}}')}}});
</script>
{{assign var="show_dashboard_button" value=0}}
{{/if}}

{{if $timeline}}{{include file="date_input.tpl"}}
{{else}}
{{*<div id="chartinfo">Total sql time: {{math equation="round(x*1000,3)" x=$query_time}}ms</div>*}}
{{/if}}

{{$content}}

</div>
