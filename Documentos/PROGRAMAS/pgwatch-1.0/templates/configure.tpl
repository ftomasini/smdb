{{include file="right.tpl"}}
<div id="left">
	<h1 class="page_headline">Configure</h1>
	<div class="clearfix"></div>
	<div id="configure-dbs">
	<h2>Instances</h2>
	Add database instances which should be checked automatically.<br/>
	Once you have added a new instance click on the new row in the table and configure the list of databases which should be tracked.<br/><br/>
	<div id="instances-table">{{$instances_markup}}</div>
	<br/>
	<h2>Databases of selected instance</h2>
	<div id="databases-table">- Please click on a row above -</div>
	</div>
</div>
<style type="text/css">
	table input, table select {font-family: arial, helvetica, sans-serif, serif; font-size: 8pt;}
</style>
<script language="javascript">
var highlighted_id = null, i;
var selected_instance;
var autorun = function(){
	dt_instances.unsubscribe("cellClickEvent");
	dt_instances.subscribe("cellClickEvent", function (e){
		var rs=dt_instances.getRecordSet();
		for (var i=0; rs.getLength() > i; i++){
			dt_instances.unhighlightRow(i);
		}
		selected_instance = dt_instances.getRecord(e.target);
		highlighted_id = selected_instance.getData("id");
		dt_instances.highlightRow(e.target);
		$.get("index.php?page=configure&func=dbs-databases-load",{node_id:selected_instance.getData("id")},function(data,result){
			if (result=="success" && data!=""){
				$("#databases-table").html(data);
				if (typeof dt_databases != "undefined"){
					default_newrow_databases = {node_id:selected_instance.getData("id"), connect_string:"host="+selected_instance.getData("hostname")+" port="+selected_instance.getData("port")+" dbname= user="};
				}
			}else{
				alert(data);
			}
		});
	});
	if (highlighted_id != null){
		var r,found=false;
		for (i=0; dt_instances.getRecordSet().getLength() > i; i++){
			r = dt_instances.getRecord(i);
			if (r.getData("id") == highlighted_id){
				dt_instances.highlightRow(i);
				found = true;
			}
		}
		if (!found) $("#databases-table").html("");
	}
}
autorun();
</script>