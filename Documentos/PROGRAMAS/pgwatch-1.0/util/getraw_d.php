<?php

include "lib/common.php";

$sync_interval = $env->get_conf("sync_interval");

while (true){
	$tmp = env::$db->query("INSERT INTO log.t_sync (tstamp) values (now()) RETURNING tstamp") or die("Failed to log and query current time");
	$now = env::$db->fetch_1();

	$node_lst = config_node::find();
	new log("NOTICE", "Synchronization started");
	foreach ($node_lst as $node){
		$node->sync($now);
	}
	new log("NOTICE", "Synchronization finished");
//	sleep($sync_interval);
exit;
}

?>
