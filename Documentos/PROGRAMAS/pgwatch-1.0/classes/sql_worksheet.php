<?php

class sql_worksheet {

	public $list;

	public function __construct(&$list){
		$this->list = &$list;
	}

	public function display(){
		$source = isset($this->list['source']) ?$this->list['source'] :0;
		switch ($source){
		case 0:
			$node_lst = config_node::find("ORDER BY descr");
			env::$smarty->assign("nodes", $node_lst);
			env::$smarty->display("sql_worksheet.tpl");
			return;
		case 1:
			$db = config_database::get($this->list['db_id']);
			if (!$db) return false;
			$connect_string = $db->connect_string;
			break;
		case 2:
			$connect_string = $this->list['connect_string'];
			break;
		}
		
		env::$halt_on_error = false;
		$conn = database::connect($connect_string);
		if (!$conn){
			env::$halt_on_error = true;
			echo "<div style='font-family:courier;white-space:pre-wrap'>",nl2br(env::$errstr),"</div>";
			return;
		}
		$db = new pgwatch_database($conn);
		$ret = $db->query($this->list['query']);
		if (!$ret){
			env::$halt_on_error = true;
				echo "<div style='font-family:courier;white-space:pre-wrap'>",nl2br(env::$errstr),"</div>";
			return;
		}
		echo $db->get_yui_table("sql_result", array('width'=>600));
	}

}

?>