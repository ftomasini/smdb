<div class="clear"></div>
<div class="clear">
	<div style="float:right">
	{{if $smarty.request.page=="indexes" or $smarty.request.page=="tabindexes"}}
		<a class="history" href="#set=sizest&amp;page=tables&amp;db_id={{$smarty.request.db_id}}&amp;schema={{$smarty.request.schema}}&amp;date_from={{$smarty.request.date_from}}&date_until={{$smarty.request.date_until}}" title="Show tables in this schema">Tables</a>
	{{/if}}
	{{if $smarty.request.page=="tables" or $smarty.request.page=="tabindexes"}}
		<a class="history" href="#set=sizest&amp;page=indexes&amp;db_id={{$smarty.request.db_id}}&amp;schema={{$smarty.request.schema}}&amp;date_from={{$smarty.request.date_from}}&amp;date_until={{$smarty.request.date_until}}" title="Show indexes in this schema">Indexes</a>
	{{/if}}
	</div>
</div>
