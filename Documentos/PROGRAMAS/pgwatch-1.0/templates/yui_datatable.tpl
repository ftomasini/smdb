{{if !$only_inside}}<div id="yui-datatable-{{$id}}">{{/if}}
{{if !$nopaginator}}
<div class="clear">
<div id="{{$id}}-paginator" style="float:left"></div>
<button title="Shrink/expand columns" onclick="yui_datatable_cols_toggle(dt_{{$id}},this)" style="float:left;margin-top:7px;" class="shrink"></button>
</div>
{{/if}}
<div style="width:{{$width}}px; overflow:auto; clear:both;">
<div id="{{$id}}-container" class="clear yui-dt">{{include file="wait.tpl"}}</div>
{{if $editable && $allow_insdel}}
<div id="{{$id}}-buttons" style="margin-top:4px">
<button id="ins_{{$id}}">Add New Row</button>
{{if $message}}
<span id="message-{{$id}}"><a>{{$message}}</a></span>
<script language="javascript">$("#message-{{$id}}").fadeOut(6000)</script>
{{/if}}
</div>
{{/if}}
</div>
<script language="javascript">
var data_{{$id}} = YAHOO.lang.JSON.parse("{{$data|@json_encode|replace:'"':'\"'|replace:"'":"\\'"}}");
var ds_{{$id}} = new YAHOO.util.DataSource(data_{{$id}});
ds_{{$id}}.responseType = YAHOO.util.DataSource.TYPE_JSARRAY;
eval("ds_{{$id}}.responseSchema = {f:{{$fields|replace:'"':'\"'|replace:"'":"\\'"}}}");
eval("var cd_{{$id}} = {{$coldefs|replace:'"':'\"'|replace:"'":"\\'"}}");
var cf_{{$id}} = {
	{{if !$nopaginator}}
	paginator: new YAHOO.widget.Paginator({
		rowsPerPage: {{$rows_per_page|default:5}},
		template: YAHOO.widget.Paginator.TEMPLATE_ROWS_PER_PAGE,
		rowsPerPageOptions: [5,10,25,50,100],
		containers: "{{$id}}-paginator"
		//, pageLinks: 5
	}),
	{{/if}}
	draggableColumns: true
	{{if count($data)>200}}, renderLoopSize: 100{{/if}}
	{{if $sort_col}}, sortedBy: {
		key: '{{$sort_col}}',
		dir: {{if strtolower($sort_dir)=='desc'}}YAHOO.widget.DataTable.CLASS_DESC{{else}}YAHOO.widget.DataTable.CLASS_ASC{{/if}}
	}
	{{/if}}
	{{if isset($format_row)}}
		, formatRow: {{$format_row}}
	{{/if}}
};
{{if $editable}}
var save_{{$id}} = function(r){
	var coldefs = dt_{{$id}}.getColumnSet().getDefinitions(), params = {}, i;
	for (i in coldefs) params[coldefs[i].key] = r.getData(coldefs[i].key);
	$.get("index.php?page=configure&func={{$id}}-save", params,
		function(data,result){
			if (result=="success") $("#yui-datatable-{{$id}}").html(data);
			else alert(result);
		}, "html"
	);
}
var delete_{{$id}} = function(r){
	var coldefs = dt_{{$id}}.getColumnSet().getDefinitions(), params = {}, i;
	for (i in coldefs) params[coldefs[i].key] = r.getData(coldefs[i].key);
	$.get("index.php?page=configure&func={{$id}}-delete", params,
		function(data,result){
			if (result=="success") $("#yui-datatable-{{$id}}").html(data);
			else alert(result);
		}, "html"
	);
}
cd_{{$id}}.push({key:"ops",label:"Save / Delete",formatter:function(elCell, oRecord, oColumn, oData){
	new YAHOO.widget.Button({label: "Save",   onclick: {fn: function(){save_{{$id}}(oRecord)}}}).appendTo(elCell);
	new YAHOO.widget.Button({label: "Delete", onclick: {fn: function(){if(confirm("Sure delete?"))delete_{{$id}}(oRecord)}}}).appendTo(elCell);
}});
{{/if}}
var dt_{{$id}} = new YAHOO.widget.DataTable("{{$id}}-container", cd_{{$id}}, ds_{{$id}}, cf_{{$id}});
{{if $editable}}
{{if $allow_insdel}}
	var default_newrow_{{$id}} = {};
	new YAHOO.widget.Button("ins_{{$id}}", {onclick:{fn:function(){
	var coldefs = dt_{{$id}}.getColumnSet().getDefinitions(), r = default_newrow_{{$id}}, i;
	for (i in coldefs) if (typeof r[coldefs[i].key] == "undefined") r[coldefs[i].key] = "";
	dt_{{$id}}.addRow(r,0);
	{{if !$nopaginator}}
	dt_{{$id}}.getPaginator().setPage(0);
	{{/if}}
}}});
{{/if}}
//dt_{{$id}}.subscribe("cellClickEvent", dt_{{$id}}.onEventShowCellEditor);
{{/if}}
{{if $autorun_js}}{{$autorun_js}}{{/if}}
</script>
{{if !$only_inside}}</div>{{/if}}
