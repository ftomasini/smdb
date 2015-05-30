<?php

class log {

	public function __construct($error_level, $message, $source=""){
		if ($source == ""){
			$d = debug_backtrace();
			if (count($d) > 2) array_pop($d);
			if ($r = array_pop($d))
				$source = basename($r['file']) . ":" . $r['line'];
			if ($r = array_pop($d))
				$source .= "|" . basename($r['file']) . ":" . $r['line'];
		}
		$tmp = env::$db->query_params("INSERT INTO log.t_log (error_level, message, source) VALUES ($1, $2, $3)",
			array($error_level, $message, $source));
		if (!$tmp) return false;
	}

}

?>
