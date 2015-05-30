<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty email_format modifier plugin
 *
 * Type:     modifier<br>
 * Name:     email_format<br>
 * Purpose:  Format email messages
 * @link http://www.cybertec.at
 * @author   Sándor Miglécz <sandor at cybertec dot at>
 * @param string
 * @param classname (optional)
 * @return string
 */
function smarty_modifier_email_format($string, $classname = "", $lineend = "<br/>") {
	$string = preg_replace(array('/(<?)([a-z0-9_.:-]+)@[a-z0-9_.-]+(>?)/i', '/<\s*>/'), array("&lt;\\2 at ...&gt;", ""), $string);
	//return preg_replace(array('/\r\n/', '/\n\r/', '/\n/', '/\r/'), array("\\r\\n\r\n", "\\n\\r\n\r", "\\n\n", "\\r\r"), $string);
	$lines = explode("\n", str_replace("\r", "", $string));
	$result = array();
	$prev_indent = $indent = 0;
	foreach ($lines as &$line){
		preg_match('/^([\t  >]*)([^  >]?.*)$/i', $line, $matches);
		$indent = substr_count($matches[1], ">");
		if ($indent > $prev_indent){
			for ($i = $prev_indent; $i < $indent; $i++){
				$result[] = $classname == "" ?"<div>\n" :"<div class=\"{$classname}\">\n";
			}
		}elseif ($indent < $prev_indent) {
			for ($i = $prev_indent; $i > $indent; $i--){
				$result[] = "</div>\n";
			}
		}
		$result[] = $matches[2] . $lineend . "\n";
		$prev_indent = $indent;
	}
	return join("", $result);
}

/* vim: set expandtab: */

?>

