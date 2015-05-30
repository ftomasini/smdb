<div class="clear"></div>
<div class="clear">
	<div style="float:right">
	{{if $smarty.request.page=="indexes" or $smarty.request.page=="tabindexes"}}
		<a class="history" href="#set=sizes&amp;page=tables&amp;db_id={{$smarty.request.db_id}}&amp;schema={{$smarty.request.schema}}" title="Show tables in this schema">Tables</a>
	{{/if}}
	{{if $smarty.request.page=="tables" or $smarty.request.page=="tabindexes"}}
		<a class="history" href="#set=sizes&amp;page=indexes&amp;db_id={{$smarty.request.db_id}}&amp;schema={{$smarty.request.schema}}" title="Show indexes in this schema">Indexes</a>
	{{/if}}
	</div>
</div>
