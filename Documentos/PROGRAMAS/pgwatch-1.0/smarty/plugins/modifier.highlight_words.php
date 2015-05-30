<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty highlight_words modifier plugin
 *
 * Type:     modifier<br>
 * Name:     highlight_words<br>
 * Purpose:  Highlight words listed in parameter
 * @link http://www.cybertec.at
 * @author   Sándor Miglécz <sandor at cybertec dot at>
 * @param string
 * @param words
 * @param classname_prefix (optional)
 * @return string
 */
function smarty_modifier_highlight_words($string, $words, $classname_prefix = "") {
	$words = trim($words);
	if ($words == "") return $string;
	$list = preg_split('/\s+/', trim($words));
	$from = array();
	$to = array();
	foreach ($list as $i => &$word){
		$classname = $classname_prefix ?" class=\"{$classname_prefix}{$i}\"" :"";
		$from[] = '/('.$word.')/i';
		$to[] = "<span{$classname}>\\1</span>";
	}
	$string = preg_replace($from, $to, $string);
	return $string;
}

/* vim: set expandtab: */

?>

