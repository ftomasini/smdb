<?php

class dashboard {

	var $list;
	var $time;
	var $pagesets_time;
	
	public function __construct($list){
		$this->list = $list;
		$this->time = microtime(1);
		$this->pagesets_time = 0;
	}

	public function display(){

		$time = microtime(1);
	
		env::suppress_conf('chart_size_x', 450);
		env::suppress_conf('chart_size_y', 220);

		$items = $this->get_user_items(env::$username);

		env::$smarty->assign("items", $items);
		env::$smarty->assign("dashboard", $this);

		$time = round(1000*(microtime(1)-$time),3)."ms";

		env::$smarty->assign("time", $time);

		env::$smarty->display("dashboard.tpl");
	}

	public function get_pagesets_time(){
		return round(1000*($this->pagesets_time),3)."ms";
	}

	public function get_time(){
		return round(1000*(microtime(1)-$this->time),3)."ms";
	}

	public function get_user_items($username){
		$res = env::$db->query_params("SELECT * FROM users.t_dashboard ".
			"WHERE user_id=(SELECT id FROM users.t_user WHERE username=$1) ".
			"ORDER BY idx, id", array($username));
		if (!$res) return false;
		$ret = env::$db->fetch_all_assoc();
		foreach ($ret as &$row){
			if ($row['time_related'] == 't'){
				$row['target'] .= "&date_from=".strftime("%Y-%m-%d", time() - env::sget_conf('dashboard_default_timeline_days')*86400);
				$row['target'] .= "&date_until=".strftime("%Y-%m-%d", time());
			}
		}
		return $ret;
	}

	public function check_user_item($username, $target){
		$user_id = auth::get_user_id($username);
		$res = env::$db->query_params("SELECT count(*) FROM users.t_dashboard ".
			"WHERE user_id=$1 AND target=$2", array($user_id, $target));
		if (!$res) return false;
		return env::$db->fetch_1();
	}

	public function display_pageset($item, $for){
		$params = $item['target'] . "&dashboard_item_id=" . $item['id'];
		$time = microtime(1);
		parse_str($params, $list);
		$pageset = new xmlpageset($list);
		foreach ($pageset->page_xml->pxml->sxml->chart as $chart){
			$param = $chart->xpath("param[@name='show_datatable']");
			$param[0]['value'] = "false";
			if (!isset($list['date_from']) && in_array((string)$chart['type'], array("Bar2D"))){
				$p = $chart->xpath("param[@name='show_legend']");
				if (count($p)>0){
					$p[0]['value'] = "false";
					$p[0]['type'] = "bool";
				}else{
					$chart->addChild(new SimpleXMLElement("<param name='show_legend' value='false' type='bool'/>"));
				}
			}
		}
		$pageset->display($for);
		$this->pagesets_time += microtime(1)-$time;
	}
	
	public function add(){
		parse_str($this->list['target'], $target);
		$ps = new xmlpageset($target);
		$time_related = ((string) $ps->page_xml->pxml->sxml['timeline']) == "true";
		$target_query = http_build_query($target);
		if ($this->check_user_item(env::$username, $target_query)){
			echo "Duplication ignored";
			return false;
		}
		if ($time_related){
			unset($target['date_from']);
			unset($target['date_until']);
		}
		$user_id = auth::get_user_id(env::$username);
		$res = env::$db->query_params("INSERT INTO users.t_dashboard (user_id, idx, target, time_related) ".
			"VALUES ($1, (SELECT 1+max(idx) FROM users.t_dashboard WHERE user_id=$1), $2, $3)",
			array($user_id, $target_query, $time_related ?"true" :"false"));
		if (!$res){
			echo "Adding failed";
			return false;
		}else{
			echo "Added successfully";
			return true;
		}
	}
	
	public function del(){
		$user_id = auth::get_user_id(env::$username);
		$id = $this->list['id'];
		$res = env::$db->query_params("DELETE FROM users.t_dashboard ".
			"WHERE user_id=$1 AND id=$2", array($user_id, $id));
		if (!$res){
			echo "Removing failed";
			return false;
		}else{
			echo "Removed successfully";
			return true;
		}
	}

}

?>