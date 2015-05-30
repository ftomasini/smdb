<?php

class configure {

	public $list;

	public function __construct(&$list){
		$this->list = &$list;
	}

	public function get_vars_table_markup($only_inside=false, $message=""){
		$tmp = env::$db->query("SELECT id, config_name, config_value, type FROM config.t_global ORDER BY 2");
		$table_markup = env::$db->get_yui_table("vars", array(
			'width' => 670,
			'formatters' => array(
				'config_name' => "custom:el.innerHTML=(oRecord.getData('id')!='') ?oData ".
					":'<input type=text value=\''+oData+'\' size=15 onblur=\'var r=dt_vars.getRecord(this);".
					"r.setData(\"config_name\",this.value);\'/>'",
				'config_value' => "custom:el.innerHTML='<input type=text value=\''+oData+'\' size=20 onblur=\'var r=dt_vars.getRecord(this);".
					"if(r.getData(\"type\")==\"number\" && isNaN(this.value)) this.value=r.getData(\"config_value\");".
					"else r.setData(\"config_value\",this.value);".
					"\'/>'",
				'type' => "custom:el.innerHTML=(oRecord.getData('id')!='') ?oData ".
					":('<select onchange=\'dt_vars.getRecord(this).setData(\"type\",this.value);\'>".
					"<option value=text'+(oData=='text'?' selected=selected':'')+'>text</option>".
					"<option value=number'+(oData=='number'?' selected=selected':'')+'>number</option></select>');",
			),
			'format_col_heads' => "replace:_: |ucfirst",
			'template' => "yui_datatable_confv.tpl",
			'allow_insdel' => true,
			'nopaginator' => true,
			'only_inside' => $only_inside,
			'message' => $message,
			'editable' => true,
		));
		return $table_markup;
	}

	public function get_instances_table_markup($only_inside=false, $message=""){
		$versions = env::sget_conf("allowed_pg_versions");
		$version_opts = "";
		foreach ($versions as $v){
			$version_opts .= "<option value=\'{$v}\''+('{$v}'==oData?' selected=\'selected\'':'')+'>{$v}</option>";
		}
		$tmp = env::$db->query("SELECT id,descr,hostname,connuser,connpass,port,version FROM config.t_node ORDER BY descr");
		$table_markup = env::$db->get_yui_table("instances", array(
			'width' => 670,
			'reversed' => false,
			'formatters' => array(
				'hostname' => "custom:el.innerHTML='<input type=text value=\''+oData+'\' style=\'width:80px\' onblur=\'var r=dt_instances.getRecord(this);".
					"r.setData(\"hostname\",this.value);\'/>'",
				'port' => "custom:el.innerHTML='<input type=text value=\''+oData+'\' style=\'width:32px\' onblur=\'var r=dt_instances.getRecord(this);".
					"if(isNaN(this.value)) this.value=r.getData(\"port\");".
					"else r.setData(\"port\",this.value);".
					"\'/>'",
				'connuser' => "custom:el.innerHTML='<input type=text value=\''+oData+'\' style=\'width:60px\' onblur=\'var r=dt_instances.getRecord(this);".
					"r.setData(\"connuser\",this.value);\'/>'",
				'connpass' => "custom:el.innerHTML='<input type=text value=\''+oData+'\' style=\'width:60px\' onblur=\'var r=dt_instances.getRecord(this);".
					"r.setData(\"connpass\",this.value);\'/>'",
				'descr' => "custom:el.innerHTML='<input type=text value=\''+oData+'\' style=\'width:80px\' onblur=\'var r=dt_instances.getRecord(this);".
					"r.setData(\"descr\",this.value);\'/>'",
				'version' => "" /* "custom:el.innerHTML='<select onblur=\'var r=dt_instances.getRecord(this);".
					"r.setData(\"version\",this.value);\'>{$version_opts}</select>';"*/,
			),
			'format_col_heads' => "replace:_: |ucfirst",
			'col_heads' => array(
				'descr' => "Name",
				'connuser' => "User",
				'connpass' => "Password",
			),
			'allow_insdel' => true,
			'nopaginator' => true,
			'only_inside' => $only_inside,
			'message' => $message,
			'autorun_js' => "if (typeof autorun != 'undefined') autorun();",
			'template' => "yui_datatable_confi.tpl",
			'editable' => true,
		));
		return $table_markup;
	}

	public function get_databases_table_markup($node_id, $only_inside=false, $message=""){
		$node_id = 1 * $this->list['node_id'];
		env::$db->query_params("SELECT * FROM config.t_node WHERE id=$1", array($node_id));
		$node = env::$db->fetch_assoc();
		$result = $this->test_node($node);
		$db_formatter = "";
		$conn_str = "";
		if ($result['success']){
			$d = $result['data'];
			$conn_str = sprintf("host=%s user=%s password=%s port=%s", $d['hostname'], $d['connuser'], $d['connpass'], $d['port']);
			$conn_str = str_replace('"', "", $conn_str);
			$conn_str = str_replace("'", "", $conn_str);
			$conn = @pg_connect($conn_str);
			if ($conn){
				$res = pg_query($conn, "SELECT datname FROM pg_catalog.pg_database ORDER BY 1");
				$dbases = array();
				while ($row = pg_fetch_assoc($res)){
					$dbases[] = $row['datname'];
				}
				$db_formatter = "edit_dropdown:" . join(",", $dbases);
			}
		}
		$db_formatter = $db_formatter ?$db_formatter
			: "custom:el.innerHTML='<input type=text value=\''+oData+'\' size=10/>'";
		$tmp = env::$db->query("SELECT id, database_name, connect_string, node_id ".
			"FROM config.t_database WHERE node_id={$node_id} ORDER BY database_name");
		$table_markup = env::$db->get_yui_table("databases", array(
			'width' => 630,
			'formatters' => array(
				'database_name' => $db_formatter,
				'connect_string' => "text", /*"custom:el.innerHTML='<input type=text value=\''+oData+'\' style=\'width:300px\' onblur=\'var r=dt_databases.getRecord(this);".
					"r.setData(\"connect_string\",this.value);\' title=\"Sample: \\'host=localhost port=5432 user=postgres password=mypass dbname=mydb\\'\"/>'",*/
			),
			'format_col_heads' => "replace:_: |ucfirst",
			'hidden_cols' => array( "node_id", "connect_string" ),
			'allow_insdel' => true,
			'nopaginator' => true,
			'only_inside' => $only_inside,
			'message' => $message,
			'template' => "yui_datatable_confd.tpl",
			'editable' => true,
			'format_row' => "row_coloring",
		));
		return $table_markup;
	}

	public function test_node($list){
		$connuser = $list['connuser'] ?$list['connuser'] :"postgres";
		$connpass = $list['connpass'] ?$list['connpass'] :"";
		$port = $list['port'] ?$list['port'] :5432;
		$conn_str = "host=" . $list['hostname'] .
			" dbname=postgres" . 
			" user=" . $connuser .
			" port=" . $port;
		$conn_str .= $connpass ?" password={$connpass}" :"";
		$conn = @pg_connect($conn_str);
		if ($conn){
			$resource = pg_query("SELECT version()");
			$row = pg_fetch_assoc($resource);
			pg_free_result($resource);
			$version = preg_replace('/PostgreSQL ([\d\.]+).*/', '\1', $row['version']);
			$result = array('success'=>1, 'data'=>array('hostname'=>$list['hostname'], 'connuser'=>$connuser, 'connpass'=>$connpass, 'port'=>$port, 'version'=>$version));
		}else{
			$result = array('success'=>0);
		}
		return $result;
	}

	public function display(){
		$func = isset($this->list['func']) ?$this->list['func'] :"";
		switch ($func){
		case "":
			// env::$smarty->assign("vars_markup", $this->get_vars_table_markup());
			env::$smarty->assign("instances_markup", $this->get_instances_table_markup());
			env::$smarty->display("configure.tpl");
			return;
		case "vars":
			echo $this->get_vars_table_markup();
			return;
		case "vars-save":
			if ($this->list['id'] == ""){
				$tmp = env::$db->query_params("INSERT INTO config.t_global (config_name, config_value,type) VALUES ($1,$2,$3)",
					array($this->list['config_name'], $this->list['config_value'], $this->list['type']));
			}else{
				$tmp = env::$db->query_params("UPDATE config.t_global SET (config_name, config_value,type)=($1,$2,$3) WHERE id=$4",
					array($this->list['config_name'], $this->list['config_value'], $this->list['type'], $this->list['id']));
			}
			if (!$tmp) return false;
			echo $this->get_vars_table_markup(true, _("Saved."));
			return;
		case "vars-delete":
			if (is_numeric($this->list['id'])){
				$tmp = env::$db->query_params("DELETE FROM config.t_global WHERE id=$1", array($this->list['id']));
				if (!$tmp) return false;
			}
			echo $this->get_vars_table_markup(true, _("Deleted."));
			return;
		case "dbs":
			env::$smarty->assign("instances_markup", $this->get_instances_table_markup());
			env::$smarty->display("configure-dbs.tpl");
			return;
		case "dbs-instances-load":
			echo $this->get_instances_table_markup(true);
			return;
		case "dbs-databases-load":
			echo $this->get_databases_table_markup(true);
			return;
		case "instances-test":
			$result = $this->test_node($this->list);
			echo json_encode($result);
			return;
		case "instances-save":
			$res = $this->test_node($this->list);
			if ($res['success']){
				$this->list = array_merge($this->list, $res['data']);
			}
			if ($this->list['id'] == ""){
				$tmp = env::$db->query_params("INSERT INTO config.t_node (hostname,descr,version,port,connuser,connpass) VALUES ($1,$2,$3,$4,$5,$6)",
					array($this->list['hostname'], $this->list['descr'], $this->list['version'], 1 * $this->list['port'],
							$this->list['connuser'], $this->list['connpass']));
			}else{
				$tmp = env::$db->query_params("UPDATE config.t_node SET (hostname,descr,version,port,connuser,connpass)=($1,$2,$3,$4,$6,$7) WHERE id=$5",
					array($this->list['hostname'], $this->list['descr'], $this->list['version'], 1 * $this->list['port'],
							$this->list['id'], $this->list['connuser'], $this->list['connpass']));
			}
			if (!$tmp) return false;
			echo $this->get_instances_table_markup(true, _("Saved."));
			return;
		case "instances-delete":
			if (is_numeric($this->list['id'])){
				$tmp = env::$db->query_params("DELETE FROM config.t_node WHERE id=$1", array($this->list['id']));
				if (!$tmp) return false;
			}
			echo $this->get_instances_table_markup(true, _("Deleted."));
			return;
		case "databases-save":
			if ($this->list['id'] == ""){
				$tmp = env::$db->query_params("INSERT INTO config.t_database (node_id,database_name,connect_string) VALUES ($1,$2,$3)",
					array($this->list['node_id'], $this->list['database_name'], $this->list['connect_string']));
			}else{
				$tmp = env::$db->query_params("UPDATE config.t_database SET (node_id,database_name,connect_string)=($1,$2,$3) WHERE id=$4",
					array($this->list['node_id'], $this->list['database_name'], $this->list['connect_string'], $this->list['id']));
			}
			if (!$tmp) return false;
			echo $this->get_databases_table_markup(true, _("Saved."));
			return;
		case "databases-delete":
			if (is_numeric($this->list['id'])){
				$tmp = env::$db->query_params("DELETE FROM config.t_database WHERE id=$1", array($this->list['id']));
				if (!$tmp) return false;
			}
			echo $this->get_databases_table_markup(true, _("Deleted."));
			return;
		case "databases-test":
			env::$halt_on_error = false;
			$conn = pg_connect($this->list['connect_string']);
			if ($conn){
				echo _("Connection works!");
			}else{
				echo _("Wrong connect string!");
			}
			return;
		}
	}

}

	?>