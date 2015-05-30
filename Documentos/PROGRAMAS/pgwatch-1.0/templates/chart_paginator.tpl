<script language="javascript">
var p_{{$id}} = new YAHOO.widget.Paginator({
	rowsPerPage: {{$rows_per_page|default:10}},
	totalRecords: {{$total_records}},
	containers: ["{{$id}}-paginator-top", "{{$id}}-paginator-bottom"],
	initialPage: {{$pager|default:1}}
});
p_{{$id}}.subscribe("changeRequest", function(s){
	$.historyLoad("{{$base_url}}&pager="+s.page); // +"&o="+s.recordOffset+"&l="+s.rowsPerPage);
});
p_{{$id}}.render();
</script>