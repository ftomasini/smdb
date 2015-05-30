<?php

class validator {

	public static $check_pattern =
		'/(INSERT\W+INTO.+VALUES|DELETE\W+FROM|UPDATE.+SET|(CREATE|ALTER|DROP|TRUNCATE)\W+(TABLE|SEQUENCE|SCHEMA|ROLE|DATABASE))/i';

	public static function remove_illegal(&$list, $check_keys=null){
		$bad = 0;
		foreach ($list as $key=>&$val){
			if (is_array($check_keys) && !isset($check_keys[$key])) continue;
			if (is_array($val)){
				new logging($val,"INPUT_CHECK:ARRAY");
				$ret = self::remove_illegal($val);
				if ($ret){
					if ($ret>$bad) $bad = $ret;
					new logging($key, "INPUT_WARNING:ARRAY:REMOVE");
					unset($list[$key]);
				}
			}elseif (is_scalar($val)){
				$val = stripslashes($val);
				new logging($val,"INPUT_CHECK:SCALAR");
				if (isset($list['page']) && $list['page']=="sql_worksheet" && $key=="query"){ // exception
				}else{
					if (preg_match(self::$check_pattern, $key)){
						unset($list[$key]);
						new logging($key, "INPUT_WARNING:SQL:KEY:REMOVE");
						new logging($_SESSION, "INPUT_WARNING:SESSION");
						$bad = 2;
					}
					if (preg_match(self::$check_pattern, $val)){
						$val = null;
						new logging($val, "INPUT_WARNING:SQL:VALUE:CLEAR");
						new logging($_SESSION, "INPUT_WARNING:SESSION");
						$bad = 2;
					}
				}
				$plainkey = strip_tags($key);
				if ($plainkey != $key){
					unset($list[$key]);
					new logging($key, "INPUT_WARNING:HTML:KEY:STRIP");
					$bad = 1;
				}
				$plainval = strip_tags($val);
				if ($plainval != $val){
					$val = $plainval;
					new logging($val, "INPUT_WARNING:HTML:VALUE:STRIP");
					$bad = 1;
				}
			}else{
				new logging($val, "INPUT_WARNING:TYPE:VALUE:REMOVE");
				unset($list[$key]);
				$bad = 1;
			}
		}
		return $bad;
	}

}

?>