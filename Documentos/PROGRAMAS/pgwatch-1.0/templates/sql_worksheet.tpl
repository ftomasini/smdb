{{include file="right.tpl"}}
<div id="left">
	<h1 class="page_headline">SQL Worksheet</h1>
	<div class="clearfix"></div>
	Any sql is allowed here. The result will be shown in the Result tab, arranged in a table.<br/>
	In case of multiple commands the result of the last query is going to be displayed.
	<div class="clearfix"></div><br/>
	<div id="sql_tabs" class="yui-navset" style="width:650px">
		<ul class="yui-nav" style="margin:0;padding:0;">
			<li class="selected"><a href="#sql-tab" rel="nav"><em>SQL</em></a></li>
			<li><a href="#result-tab" rel="nav"><em>Result</em></a></li>
		</ul>
		<div class="yui-content">
			<div id="sql-tab"><p>
				<form style="margin:0;padding:0;border:0;" name="sqlform" id="sqlform" method="post">
					<table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin-bottom:5px">
						<tr><td style="width:30%">Existing database connections:</td>
							<td style="width:70%"><input type="radio" name="source" value="1" checked="checked"/>
								<select onchange="query.focus()" name="db_id">
								{{foreach from=$nodes item="node"}}
									<optgroup label="{{$node->descr}}">
									{{foreach from=$node->get_databases() item="db"}}
										<option value="{{$db->get_id()}}">{{$db->database_name|escape:"html"}}</option>
									{{/foreach}}
									</optgroup>
								{{/foreach}}
								</select>
							</td>
						</tr>
						<tr><td>Custom connection string:</td>
							<td><input type="radio" name="source" value="2"/>
								<input type="text" name="connect_string" value="hostname= port= dbname= user= password=" style="width:300px"/>
							</td>
						</tr>
						<tr><td colspan="2">
							<textarea name="query" style="width:620px">--enter your query here
</textarea>
						</td></tr>
					</table>
					<input type="submit" name="submit" value="Execute Query" id="sql_submit"/>
				</form>
			</p></div>
			<div id="result-tab"><p></p></div>
		</div>
	</div>
</div>
<script language="javascript">
var t=new YAHOO.widget.TabView("sql_tabs");
var b=new YAHOO.widget.Button("sql_submit");
$("form#sqlform").ajaxForm({
	url: "index.php?page=sql_worksheet&rnd="+Math.random(),
	target:"#result-tab p",
	success: function(a){
		t.selectTab(1);
	}
});
</script>