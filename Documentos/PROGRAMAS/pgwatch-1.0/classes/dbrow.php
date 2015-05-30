<?php

abstract class dbrow {

	public static $_metadata = array(
		'table' => "",
		'seq' => "",
		'fields' => array(),
		'id' => ""
	);

	private static $_cache = array();

	public static function get_metadata($key){
		//return self::$_metadata[$key];
	}

	public function __construct(){
		//parent::__construct();
	}

	public static function get_cache($classname, $id){
		if (!env::sget_conf('allow_cache')) return null;
		if (isset(self::$_cache[$classname]) && isset(self::$_cache[$classname][$id])){
			return self::$_cache[$classname][$id];
		}
		return null;
	}
	
	public static function set_cache($classname, $obj){
		if (!env::sget_conf('allow_cache')) return;
		$id = $obj->get_id();
		if (!isset(self::$_cache[$classname])){
			self::$_cache[$classname] = array();
		}
		self::$_cache[$classname][$id] = $obj;
	}

	public static function get($classname, $id){
		if ($obj = self::get_cache($classname, $id)) return $obj;
		$a_fields = call_user_func(array($classname, "get_metadata"), "fields");
		$fields = join(",", array_keys($a_fields));
		$table = call_user_func(array($classname, "get_metadata"), "table");
		$idfield = call_user_func(array($classname, "get_metadata"), "id");
		$tmp = env::$db->query_params("SELECT {$fields} FROM {$table} WHERE {$idfield}=$1 LIMIT 1", array($id));
		if (!$tmp) return false;
		$row = env::$db->fetch_assoc();
		env::$db->free();
		$obj = new $classname();
		foreach ($a_fields as $field => &$type){
			if (isset($row[$field])){
				$obj->$field = self::pg2php($row[$field], $type);
			}
		}
		self::set_cache($classname, $obj);
		return $obj;
	}

	public function insert(){
		$classname = get_class($this);
		$a_fields = call_user_func(array($classname, "get_metadata"), "fields");
		$idfield = call_user_func(array($classname, "get_metadata"), "id");
		$value_fields = $a_fields;
		unset($value_fields[$idfield]);
		$fields = join(",", array_keys($value_fields));
		$table = call_user_func(array($classname, "get_metadata"), "table");
		$seq = call_user_func(array($classname, "get_metadata"), "seq");
		$a_values = array();
		$a_param = array();
		$i = 0;
		foreach ($value_fields as $field => &$type){
			$i++;
			$a_values[] = "$".$i;
			$a_param[] = self::php2pg($this->$field, $type);
		}
		$values = join(",", $a_values);
		$tmp = env::$db->query_params("INSERT INTO {$table} ({$idfield},{$fields}) ".
			"VALUES (nextval('{$seq}'),{$values}) RETURNING {$idfield}", $a_param);
		if (!$tmp) return false;
		$this->{$idfield} = env::$db->fetch_1();
		self::set_cache($classname, $this);
		return true;
	}

	public function update(){
		$classname = get_class($this);
		$a_fields = call_user_func(array($classname, "get_metadata"), "fields");
		$idfield = call_user_func(array($classname, "get_metadata"), "id");
		$value_fields = $a_fields;
		unset($value_fields[$idfield]);
		$fields = join(",", array_keys($value_fields));
		$table = call_user_func(array($classname, "get_metadata"), "table");
		$seq = call_user_func(array($classname, "get_metadata"), "seq");
		$a_values = array();
		$a_param = array();
		$i = 0;
		foreach ($value_fields as $field => &$type){
			$i++;
			$a_values[] = "$".$i;
			$a_param[] = self::php2pg($this->$field, $type);
		}
		$values = join(",", $a_values);
		$tmp = env::$db->query_params("UPDATE {$table} SET ({$fields})=({$values}) ".
			"WHERE {$idfield}=".($this->$idfield), $a_param);
		if (!$tmp) return false;
		return true;
	}

	public static function find($classname, $condition=""){
		$a_fields = call_user_func(array($classname, "get_metadata"), "fields");
		$fields = join(",", array_keys($a_fields));
		$table = call_user_func(array($classname, "get_metadata"), "table");
		$idfield = call_user_func(array($classname, "get_metadata"), "id");
		$tmp = env::$db->query("SELECT {$fields} FROM {$table} {$condition}");
		if (!$tmp) return false;
		$ret = array();
		while ($row = env::$db->fetch_assoc()){
			if ($obj = self::get_cache($classname, $row[$idfield])){
				$ret[] = $obj;
			}else{
				$obj = new $classname();
				foreach ($a_fields as $field => &$type){
					$obj->$field = $row[$field];
				}
				unset($type);
				$ret[] = $obj;
			}
		}
		return $ret;
	}

	public function get_id(){
		$classname = get_class($this);
		$idfield = call_user_func(array($classname, "get_metadata"), "id");
		return $this->$idfield;
	}

	public static function php2pg($value, $type){
		return $value;
	}

	public static function pg2php($value, $type){
		return $value;
	}

}

?>
