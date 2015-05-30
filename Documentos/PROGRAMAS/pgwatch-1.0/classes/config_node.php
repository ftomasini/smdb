<?php

class config_node extends dbrow {

	public static $_metadata = array(
		'classname' => "config_node",
		'table' => "config.t_node",
		'seq' => "config.t_node_id_seq",
		'fields' => array(
			'id' => "integer",
			'hostname' => "text",
			'descr' => "text",
			'added' => "timestamp",
			'version' => "text",
		),
		'id' => "id",
	);
	public static function get_metadata($key){return self::$_metadata[$key];}
	public static function get($id){return dbrow::get(self::$_metadata['classname'], $id);}
	public static function find($cond=""){return dbrow::find(self::$_metadata['classname'], $cond);}

	public function __construct(){
		parent::__construct();
	}

	public function get_databases(){
		return config_database::find("WHERE node_id=".$this->get_id());
	}

	public function get_label(){
		$label = $this->descr ?$this->descr :$this->hostname;
		return $label;
	}

	public function sync($now){
		new log("NOTICE", "Synchronizing node " . $this->get_label());
		$db_lst = $this->get_databases();
		foreach ($db_lst as $db){
			$db->sync($now);
		}
	}

}

?>
