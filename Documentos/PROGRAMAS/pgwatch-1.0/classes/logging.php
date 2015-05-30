<?php
# this file contains functions made for proper logging
# --------------------------------------------------------

define('LOGFILE', env::sget_conf("tmp_dir") . "/pgwatch1.log");

class logging {
	public static $logfile = null;
	function __construct($val, $prefix = "NOTICE") {
		if (DEBUG === true) {
			$logfile = self::$logfile ?self::$logfile :LOGFILE;
			//if (is_writable($logfile)) @chmod($logfile, 0666);
			$b = debug_backtrace();
			$caller = basename($b[0]['file']).":".$b[0]['line'];
			if (count($b)>1){
				$caller = basename($b[1]['file']).":".$b[1]['line'] ."|". $caller;
			}
			$str = $prefix . " - " . var_export($val, true);
			$fp = fopen($logfile, "a") or die ("cannot open logfile");
			fwrite($fp, "[".date("Y-m-d G:i:s") . "|{$caller}] - " . $str."\n");
			fclose($fp);
		}
	}
}

?>
