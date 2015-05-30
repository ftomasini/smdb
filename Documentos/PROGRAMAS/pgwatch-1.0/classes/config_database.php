<?php

class config_database extends dbrow {

	public static $_metadata = array(
		'classname' => "config_database",
		'table' => "config.t_database",
		'seq' => "config.t_database_id_seq",
		'fields' => array(
			'id' => "integer",
			'node_id' => "integer",
			'database_name' => "text",
			'connect_string' => "text",
			'added' => "timestamp",
		),
		'id' => "id",
	);
	public static function get_metadata($key){return self::$_metadata[$key];}
	public static function get($id){return dbrow::get(self::$_metadata['classname'], $id);}
	public static function find($cond=""){return dbrow::find(self::$_metadata['classname'], $cond);}
	
	public function __construct(){
		parent::__construct();
	}

	public function get_node(){
		return config_node::get($this->node_id);
	}

	// collect raw data from remote database
	public function sync($now){
		$node = $this->get_node();
		new log("NOTICE", "Synchronizing database " . $this->database_name);
		$version = preg_replace('/^(\d+\.\d+).*/', '\1', $node->version);
		switch ($version){
			case "8.4":
				$xml_file = "config/fetch_8_4.xml";
				break;
			case "9.0":
				$xml_file = "config/fetch_9_0.xml";
				break;
			case "9.1":
				$xml_file = "config/fetch_9_1.xml";
				break;
		}
		$params = array(
			'link'    => $this->connect_string,
			'node'    => $node,
			'node_id' => $node->get_id(),
			'dbname'  => $this->database_name,
			'db_id'   => $this->get_id(),
			'now'     => $now,
		);

		$link = @pg_connect($this->connect_string);
		if (!$link){
			new logging("Could not connect to '{$this->connect_string}'.", "WARNING");
			return false;
		}
		$xml_text = file_get_contents($xml_file);
		$pxml = new pgwatch_xml($xml_text, $params);
		$pxml->process_children();
		return true;
	}

}

?>
