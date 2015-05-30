<?php

function error_handler($errno, $errstr, $errfile, $errline, $errcontext){
	switch ($errno){
		case E_WARNING:
			if (strpos($errfile,"templates_c")) return;
			//echo "WARNING: {$errstr} in {$errfile} ({$errline})\n";
			return true;
			break;
		case E_NOTICE:
			if (strpos($errfile,"templates_c")) return;
			//echo "NOTICE: {$errstr} in {$errfile} ({$errline})\n";
			return true;
			break;
		case E_ERROR:
		case E_USER_ERROR:
			env::$errno = $errno;
			env::$errstr = $errstr;
			env::$errfile = $errfile;
			env::$errline = $errline;
			env::$errcontext = $errcontext;
			if (env::$halt_on_error){
				echo "$errno: {$errstr} in {$errfile} ({$errline})<br/>\n";
				/*
				foreach (debug_backtrace() as $d){
					echo $d['file'],":",$d['line'],"<br/>\n";
					echo "args:",nl2br(print_r($d['args'],1)),"<br/>\n";
				}
				*/
				exit;
			}
			return false;
	}
}

set_error_handler("error_handler");

?>
