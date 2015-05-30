<?php

class database {

	public $dbconn;
	public $result;
	public $last_sql;
	public $log_verbose;
	public $auto_free;
	public $pos;

	public static $conns = array();

	public function __construct($dbconn){
		new logging($dbconn, "DATABASE_CONNECT_TO");
		if (is_resource($dbconn)){
			$this->dbconn = $dbconn;
		}else{
			trigger_error(_("Could not identify given database: ").$dbconn, E_USER_ERROR);
		}
		$this->log_verbose = true;
		$this->auto_free = true;
		$this->pos = null;
	}

	public function query($sql, $errmsg=null, $logmsg=null){
		$this->last_sql = $sql;
		new logging($sql,"SQL");
		if (is_resource($this->result)) pg_free_result($this->result);
		$this->result = pg_query($this->dbconn, $sql);
		if (!$this->result){
			new logging("SQL failed","WARNING");
			trigger_error(_($errmsg ?$errmsg :"could not execute query") 
				. ": " . $sql ." - reason: " 
				.$this->last_error(), E_USER_ERROR);
			$this->rollback();
			return false;
		}
		new logging("SQL succeeded, retrieved: ". $this->num_rows() .", affected: ".$this->affected(),"NOTICE");
		if ($logmsg) new logging($logmsg, "NOTICE");
		$this->pos = 0;
		return $this->result;
	}
	public function queryd($sql, $errmsg=null, $logmsg=null){
		echo "<pre>";
		var_dump($a=htmlspecialchars($sql));
		$ret = $this->query($sql, $errmsg, $logmsg);
		echo "</pre>";
		return $ret;
	}

	// query + free resource if success
	// returns boolean
	public function query_free($sql){
		$tmp = $this->query($sql);
		if (!$tmp) return false;
		$this->free();
		return true;
	}

	public function query_params($sql, $params, $errmsg=null, $logmsg=null){
		$this->last_sql = $sql;
		new logging($sql,"SQL");
		if ($this->log_verbose){
			new logging($params,"PARAMS");
		}else{
			new logging("Params dumping skipped because long data is ecpectible","PARAMS");
		}
		if (is_resource($this->result)) pg_free_result($this->result);
		$this->result = pg_query_params($this->dbconn, $sql, $params);
		if (!$this->result){
			new logging("SQL failed","WARNING");
			trigger_error( ($errmsg ?$errmsg :_("could not execute query")) 
				. ": " . $sql ." - reason: " 
				.$this->last_error(), E_USER_ERROR . "<br/>" . 
				"params:" . print_r($params,1));
			$this->rollback();
			return false;
		}
		new logging("SQL succeeded, affected: ".$this->affected(),"NOTICE");
		if ($logmsg) new logging($logmsg, "NOTICE");
		$this->pos = 0;
		return $this->result;
	}
	public function query_paramsd($sql, $params, $errmsg=null, $logmsg=null){
		echo "<pre>";
		var_dump($a=htmlspecialchars($sql));
		var_dump($a=htmlspecialchars($params));
		$ret = $this->query_params($sql, $params, $errmsg, $logmsg);
		echo "</pre>";
		return $ret;
	}

	// query + free resource if success
	// returns boolean
	public function query_params_free($sql, $params){
		$tmp = $this->query_params($sql, $params);
		if (!$tmp) return false;
		$this->free();
		return true;
	}

	public function fetch_row($res=null){
		$res = $res ?$res :$this->result;
		$ret = pg_fetch_row($res);
		if ($ret !== false){
			$this->pos++;
		}
		return $ret;
	}

	public function fetch_1($res=null){
		$res = $res ?$res :$this->result;
		$row = $this->fetch_row($res);
		$ret = array_shift($row);
		if ($this->auto_free){
			$this->free($res);
		}
		return $ret;
	}

	public function fetch_assoc($res=null){
		$res = $res ?$res :$this->result;
		$ret = pg_fetch_assoc($res);
		if ($ret !== false){
			$this->pos++;
		}
		return $ret;
	}

	public function fetch_col($col, $res=null){
		$res = $res ?$res :$this->result;
		$ret = pg_fetch_result($res, $col);
		if ($ret !== false)
			$this->pos++;
		return $ret;
	}

	public function free($res=null){
		$res = $res ?$res :$this->result;
		if (is_resource($res))
			pg_free_result($res);
		$this->pos = null;
	}

	public function seek($i, $res=null){
		$res = $res ?$res :$this->result;
		if (is_resource($res)){
			$ret = pg_result_seek($res, $i);
			$this->pos = $ret===true ?$i :$this->pos;
			return $ret;
		}
		return false;
	}
	
	public function reset($res=null){
		$res = $res ?$res :$this->result;
		if (is_resource($res))
			return $this->seek(0, $res);
		return false;
	}

	public function fetch_all($index_col=null, $index_val=null, $res=null){
		$res = $res ?$res :$this->result;
		$ret = array();
		$this->reset($res);
		while ($row = $this->fetch_row($res)){
			if ($index_col){
				$ret[$row[$index_col]] = $index_val ?$row[$index_val] :$row;
			}else{
				$ret[] = $index_val ?$row[$index_val] :$row;
			}
		}
		if ($this->auto_free) $this->free($res);
		return $ret;
	}

	public function fetch_all_assoc($key_col=null, $value_col=null, $res=null){
		$res = $res ?$res :$this->result;
		$ret = array();
		$this->reset($res);
		while ($row = $this->fetch_assoc($res)){
			if ($key_col){
				$ret[$row[$key_col]] = $value_col ?$row[$value_col] :$row;
			}else{
				$ret[] = $value_col ?$row[$value_col] :$row;
			}
		}
		if ($this->auto_free) $this->free($res);
		return $ret;
	}

	public function fetch_all_assoc_hold($key_col=null, $value_col=null, $res=null){
		$res = $res ?$res :$this->result;
		$ret = array();
		$this->reset($res);
		while ($row = $this->fetch_assoc($res)){
			if ($key_col){
				$ret[$row[$key_col]] = $value_col ?$row[$value_col] :$row;
			}else{
				$ret[] = $value_col ?$row[$value_col] :$row;
			}
		}
		return $ret;
	}

	public function begin(){
		if (pg_transaction_status($this->dbconn) == PGSQL_TRANSACTION_IDLE){
			$this->query("BEGIN WORK");
		}
	}

	public function commit(){
		if (pg_transaction_status($this->dbconn) != PGSQL_TRANSACTION_IDLE){
			$this->query("COMMIT WORK");
		}
	}

	public function rollback(){
		if (pg_transaction_status($this->dbconn) != PGSQL_TRANSACTION_IDLE){
			$this->query("ROLLBACK WORK");
		}
	}

	public function affected($res=null){
		$res = $res ?$res :$this->result;
		if (is_resource($res))
			return pg_affected_rows($res);
		return 0;
	}

	public function num_rows($res=null){
		$res = $res ?$res :$this->result;
		if (is_resource($res))
			return pg_num_rows($res);
		return null;
	}

	public function colnames($res=null){
		$res = $res ?$res :$this->result;
		$ret = array();
		for ($i=0; $i<pg_num_fields($res); $i++){
			$ret[] = pg_field_name($res, $i);
		}
		return $ret;
	}

	public function to_json($linktarget = null, $linkcolumn = null, $mode = "display", $link_keyval=true){
		$res = $res ?$res :$this->result;
		$dt = new datatable($this->dbconn, null);
		$dt->result_to_json($res, $linktarget, $linkcolumn, $mode, $link_keyval);
	}

	public function __destruct(){
		if (is_resource($this->result)){
			$this->free();
		}
	}

	public function nextval($seq){
		$tmp = $this->query("SELECT nextval('{$seq}')");
		if (!$tmp) return null;
		return $this->fetch_1();
	}

	public function check_access($table_fullname, $operation, $result_type="text", $subject_type="table"){
		return check::access($this->dbconn, $table_fullname, $operation, $result_type, $subject_type);
	}

	// executes a select that must return at least 1 row for validity
	public function query_access($sql, $error_type="text"){
		new logging("Checking user's access for planned operation", "CHECK_ACCESS");
		$tmp = $this->query($sql);
		if (!$tmp){
			new logging("Access denied", "WARNING");
			trigger_error("PRIVILEGE_ERROR:".$error_type, E_USER_ERROR);
			return false;
		}
		$rows = $this->fetch_all();
		if (count($rows)==0){
			new logging("Access denied", "WARNING");
			trigger_error("PRIVILEGE_ERROR:".$error_type, E_USER_ERROR);
			return false;
		}
		new logging("Access allowed", "NOTICE");
		return true;
	}

	public static function connect($descriptor_str, $client_encoding=null){
		if (isset(self::$conns[$descriptor_str])){
			return self::$conns[$descriptor_str];
		}
		$ret = @pg_connect($descriptor_str);
		if ($ret){
			$db = new self($ret);
			if ($db && !is_null($client_encoding)){
				$db->query("SET client_encoding TO '{$client_encoding}'");
			}
		}
		self::$conns[$descriptor_str] = $ret;
		return $ret;
	}

	public static function fieldname_format($str){
		$str = str_replace("_", " ", $str);
		$str = ucwords($str);
		return $str;
	}

	public function get_jquery_table($id){
		$data = $this->fetch_all_assoc();
		$tbl = new jquery_tablesorter($data);
		return $tbl->get_table_html($id);
	}

	public function get_yui_table($id, $args=null){
		return yui_datatable::get_table_html($this->result, $id, $args);
	}

	public function get_fusion_chart($type, $args=null, $hold=false){
		return chart::get_chart_html($this->result, $type, $args, $hold);
	}

	public function last_error($dbconn=null){
		$dbconn = $dbconn ?$dbconn :$this->dbconn;
		return pg_last_error($dbconn);
	}

}

?>
