<?php

class env {

	public $root_wd;
	public $list;
	public static $conf;
	public static $username;
	public static $password;
	public static $db;
	public static $smarty;
	public static $halt_on_error = true;
	public static $errno = null;
	public static $errstr = null;
	public static $errfile = null;
	public static $errline = null;
	public static $errcontext = null;

	public static $env = null;

	private static $_conf_cache = array();

	public function __construct($root_wd){
		$this->root_wd = $root_wd;
	}

	/**
	 * Instantiation in the singleton way
	 */
	public static function instance($root_wd = null){
		if (!self::$env instanceof self){
			self::$env = new self($root_wd);
		}
		return self::$env;
	}

	public static function load_ini($filename){
		self::$conf = parse_ini_file($filename);
	}

	public function get_conf($name, $db=null){
		$ret = $this->fileconf($name);
		if (!is_null($ret)) return $ret;
		if (is_null($db)) $db = env::$db;
		if (isset(self::$_conf_cache[$name])){
			return self::$_conf_cache[$name];
		}
		$tmp = $db->query("SELECT config_value FROM config.t_global WHERE config_name='{$name}'");
		if (!$tmp) return false;
		$ret = $db->fetch_1();
		self::$_conf_cache[$name] = $ret;
		return $ret;
	}
	public static function sget_conf($name, $db=null){
		if (is_null($db)) $db = env::$db;
		return self::$env->get_conf($name, $db);
	}
	
	public function set_conf($name, $value, $db=null){
		if (is_null($db)) $db = env::$db;
		$tmp = $db->query_params("UPDATE config.t_global SET config_value=$1 WHERE config_name=$2",
			array($name, $value));
		if (!$tmp) return false;
		if ($db->affected() == 0){
			$tmp = $db->query_params("INSERT INTO config.t_global (config_value,conig_fname) VALUES ($1,$2)",
				array($name, $value));
			if (!$tmp) return false;
		}
		self::$_conf_cache[$name] = $value;
		return true;
	}
	public static function sset_conf($name, $value, $db=null){
		if (is_null($db)) $db = env::$db;
		return self::$env->set_conf($name, $value, $db);
	}

	public function fileconf($name){
		if (isset(self::$conf[$name])) return self::$conf[$name];
		return null;
	}

	public static function sfileconf($name){
		return self::$env->fileconf($name);
	}

	public static function get_root_wd(){
		return self::$env->root_wd;
	}

	public static function get_schema_filter($fieldname="schemaname", $allow_empty_string=false){
		$ignore_nsp = self::sget_conf('ignore_namespaces');
		if (count($ignore_nsp) == 0){
			return $allow_empty_string ?"" :"true";
		}
		$parts = array();
		foreach ($ignore_nsp as $nsp){
			if (strpos($nsp, "%") === false) {
				$parts[] = "{$fieldname}='{$nsp}'";
			}else{
				$parts[] = "{$fieldname} ilike '{$nsp}'";
			}
		}
		return "(" . join(" OR ", $parts) . ")";
	}

	public static function suppress_conf($name, $value){
		self::$conf[$name] = $value;
	}

}

?>
