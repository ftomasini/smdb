<?php

include "lib/common.php";

$preagg_levels = array('day' => 1);

$preaggs = array(
	't_stat_all_tables' => array(
		'day' => "t_stat_all_tables_day",
		'aggregates' => array(
			"seq_scan", "seq_tup_read", "idx_scan", "idx_tup_fetch", "n_tup_ins", "n_tup_upd",
			"n_tup_del", "n_tup_hot_upd", "n_live_tup", "n_dead_tup", "table_size", "total_size"
		),
		'groupby' => array(
			"node_id", "database_id", "relid", "schemaname", "relname"
		),
	),
	't_stat_all_indexes' => array(
		'day' => "t_stat_all_indexes_day",
		'aggregates' => array(
			"idx_scan", "idx_tup_read", "idx_tup_fetch", "relation_size"
		),
		'groupby' => array(
			"node_id", "database_id", "relid", "indexrelid", "schemaname", "relname", "indexrelname"
		),
	),
	't_stat_all_schemas' => array(
		'day' => "t_stat_all_schemas_day",
		'aggregates' => array(
			"seq_scan", "seq_tup_read", "idx_scan", "idx_tup_fetch", "n_tup_ins", "n_tup_upd", "n_tup_del",
			"n_tup_hot_upd", "n_live_tup", "n_dead_tup", "table_size", "total_size"
		),
		'groupby' => array(
			"node_id", "database_id", "relid", "schemaname", "relname"
		),
	),
	't_stat_all_schema_indexes' => array(
		'day' => "t_stat_all_schema_indexes_day",
		'aggregates' => array(
			"idx_scan", "idx_tup_read", "idx_tup_fetch", "relation_size"
		),
		'groupby' => array(
			"node_id", "database_id", "relid", "indexrelid", "schemaname", "relname", "indexrelname"
		),
	),
	't_statio_all_tables' => array(
		'day' => "t_statio_all_tables_day",
		'aggregates' => array(
			"heap_blks_read", "heap_blks_hit", "idx_blks_hit", "toast_blks_read",
			"toast_blks_hit", "tidx_blks_read", "tidx_blks_hit"
		),
		'groupby' => array(
			"node_id", "database_id", "relid", "schemaname", "relname"
		),
	),
	't_statio_all_indexes' => array(
		'day' => "t_statio_all_indexes_day",
		'aggregates' => array(
			"idx_blks_read", "idx_blks_hit"
		),
		'groupby' => array(
			"node_id", "database_id", "relid", "indexrelid", "schemaname", "relname", "indexrelname"
		),
	),
	't_statio_all_schemas' => array(
		'day' => "t_statio_all_schemas_day",
		'aggregates' => array(
			"heap_blks_read", "heap_blks_hit", "idx_blks_hit", "toast_blks_read",
				"toast_blks_hit", "tidx_blks_read", "tidx_blks_hit"
		),
		'groupby' => array(
			"node_id", "database_id", "relid", "schemaname", "relname"
		),
	),
	't_statio_all_schema_indexes' => array(
		'day' => "t_statio_all_schema_indexes_day",
		'aggregates' => array(
			"idx_blks_read", "idx_blks_hit"
		),
		'groupby' => array(
			"node_id", "database_id", "relid", "indexrelid", "schemaname", "relname", "indexrelname"
		),
	),

);

$interval_length = "10 days";

echo "\nChecking data for interval: {$interval_length} up to YESTERDAY\n\n";

$verbose = true;

foreach ($preaggs as $origtable => $details){

	echo strtoupper($origtable),":\n", str_repeat("=", strlen($origtable)+1);

	$select = "";
	$groupby = "";
	foreach ($details['groupby'] as $field){
		$select .= ",{$field}\n\t";
		$groupby .= ",{$field}\n\t";
	}
	foreach ($details['aggregates'] as $field){
		$select .= ",max({$field}) AS {$field}\n\t";
	}

	$sql = "SELECT " . substr($select,1) .
		"\nFROM raw_data." . $origtable .
		"\nWHERE tstamp>='YESTERDAY'::timestamp-'{$interval_length}'::interval AND tstamp<'YESTERDAY'::timestamp" .
		"\nGROUP BY " . substr($groupby,1) . 
		"\nORDER BY " . substr($groupby,1);

	echo "\nQuerying {$origtable}...";
	env::$db->query($sql);
	echo "done";
	$result = env::$db->fetch_all_assoc();
	$grouped = array();
	foreach ($result as $row){
		$key = "";
		foreach ($details['groupby'] as $field){
			$key .= "~" . $row[$field];
			unset($row[$field]);
		}
		$grouped[substr($key,1)] = $row;
	}
	unset($result);

	$sql_day = "SELECT " . substr($select,1) .
		"\nFROM raw_data." . $origtable . "_day" .
		"\nWHERE tstamp>='YESTERDAY'::timestamp-'{$interval_length}'::interval AND tstamp<'YESTERDAY'::timestamp" .
		"\nGROUP BY " . substr($groupby,1) . 
		"\nORDER BY " . substr($groupby,1);

	echo "\nQuerying {$origtable}_day...";
	env::$db->query($sql_day);
	echo "done";
	$result_day = env::$db->fetch_all_assoc();
	$grouped_day = array();
	foreach ($result_day as $row){
		$key = "";
		foreach ($details['groupby'] as $field){
			$key .= "~" . $row[$field];
			unset($row[$field]);
		}
		$grouped_day[substr($key,1)] = $row;
	}
	unset($result_day);

	$equal = 0;
	$missing = 0;
	$different = 0;
	$extra = 0;
	foreach ($grouped as $group_key => $row){
		if (!isset($grouped_day[$group_key])){
			if ($verbose) echo "\nMISSING: {$origtable}_day ({$group_key})";
			$missing++;
		}elseif($grouped_day[$group_key] != $grouped[$group_key]){
			$different++;
			if ($verbose) echo "\nDIFFERENCE: {$origtable} != {$origtable}_day ({$group_key})";
			foreach ($row as $key => $val){
				if ($val != $grouped_day[$group_key][$key]){
					if ($verbose) echo "\n\t{$key}: {$val} != {$grouped_day[$group_key][$key]}";
				}else{
					if ($verbose) echo "\n\t\t{$key}: {$val} == {$grouped_day[$group_key][$key]}";
				}
			}
		}else{
			$equal++;
			continue;
			if ($verbose) echo "\nEQUAL: {$origtable} == {$origtable}_day ({$group_key})";
			foreach ($row as $key => $val){
				if ($val == $grouped_day[$group_key][$key]){
					if ($verbose) echo "\n\t{$key}: {$val} == {$grouped_day[$group_key][$key]}";
				}
			}
		}
	}
	$extra_keys = array_diff(array_keys($grouped_key), array_keys($grouped));
	foreach ($extra_keys as $key){
		if ($verbose) echo "\nWRONG EXTRA ROWS: {$origtable}_day ({$group_key})";
		$extra++;
	}

	echo "\n\nCompared records statistics:";
	echo "\nequal: ", $equal;
	echo "\nmissing: ", $missing;
	echo "\ndifferent: ", $different;
	echo "\nextra: ", $extra;
	echo "\n\n";
}

?>

