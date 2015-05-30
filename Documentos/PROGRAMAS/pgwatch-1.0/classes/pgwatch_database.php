<?php

class pgwatch_database extends database {

	public $paginate;
	public $rows_per_page;
	public $pager;
	public $total_rows;
	public $time;

	public function __construct($dbconn){
		$this->free();
		parent::__construct($dbconn);
	}

	public static function fieldname_format($str){
		$str = str_replace("_", " ", $str);
		$str = ucwords($str);
		return $str;
	}

	public function query($sql, $errmsg=null, $logmsg=null){
		$time = microtime(1);
		$ret = parent::query($sql, $errmsg, $logmsg);
		$this->time = microtime(1) - $time;
		return $ret;
	}

	public function queryd($sql, $errmsg=null, $logmsg=null){
		$time = microtime(1);
		$ret = parent::queryd($sql, $errmsg, $logmsg);
		$this->time = microtime(1) - $time;
		echo "<pre>time:",round($this->time*1000,3),"ms</pre>";
		return $ret;
	}

	public function query_params($sql, $params, $errmsg=null, $logmsg=null){
		$time = microtime(1);
		$ret = parent::query_params($sql, $params, $errmsg, $logmsg);
		$this->time = microtime(1) - $time;
		return $ret;
	}

	public function query_paramsd($sql, $params, $errmsg=null, $logmsg=null){
		$time = microtime(1);
		$ret = parent::query_paramsd($sql, $params, $errmsg, $logmsg);
		$this->time = microtime(1) - $time;
		echo "<pre>time:",round($this->time*1000,3),"ms</pre>";
		return $ret;
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

	public function paginate_query($query){
		$limit = $this->rows_per_page;
		$offset = $this->rows_per_page * ($this->pager - 1);
		$query = "WITH q AS ({$query}), c AS (SELECT count(*) FROM q) ".
			"SELECT q.*, c.count AS __total_rows__ FROM q, c LIMIT {$limit} OFFSET {$offset}";
		return $query;		
	}

	public function get_paginated_total($res=null){
		$res = is_null($res) ?$this->result :$res;
		if ($res && $this->paginate){
			if (isset($this->total_rows)) return $this->total_rows;
			$row = pg_fetch_assoc($res);
			$this->total_rows = $row['__total_rows__'];
			$this->seek($this->pos, $res);
		}
	}

	public function free($res=null){
		/**/
		$this->paginate = false;
		$this->rows_per_page = null;
		$this->pager = null;
		/**/
		$this->total_rows = null;
	}

}

?>
