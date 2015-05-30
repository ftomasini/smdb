<?php

class dates {

	public $date_from;
	public $date_until;
	public $ts_from;
	public $ts_until;
	public $dates_in_url;
	public $granularity;
	public $granularity_sec;
	public $step;
	public $date_pattern;
	public $date_pattern_day;
	public $gran;
	public $points_max;
	public $gap;

	function __construct(&$list, $shift_start=0){
		if (isset($_SESSION['date_from']) && $_SESSION['date_from']!="")
			$this->date_from = $_SESSION['date_from'];
		if (isset($list['date_from']) && $list['date_from']!="")
			$this->date_from = $list['date_from'];
		if (!$this->date_from)
			$this->date_from = strftime("%Y-%m-%d", time() - 30*86400);
		if (isset($_SESSION['date_until']) && $_SESSION['date_until']!="")
			$this->date_until = $_SESSION['date_until'];
		if (isset($list['date_until']) && $list['date_until']!="")
			$this->date_until = $list['date_until'];
		if (!$this->date_until)
			$this->date_until = strftime("%Y-%m-%d");
		$_SESSION['date_from'] = $this->date_from;
		$_SESSION['date_until'] = $this->date_until;
		$this->dates_in_url = "date_from={$this->date_from}&date_until={$this->date_until}";
		env::$smarty->assign('date_from', $this->date_from);
		env::$smarty->assign('date_until', $this->date_until);
		$a_from = explode("-", $this->date_from)+array(null,null,null);
		$a_until = explode("-", $this->date_until)+array(null,null,null);
		$a_today = explode("-", strftime("%Y-%m-%d"))+array(null,null,null);
		$this->ts_from = mktime(0,0,0,$a_from[1],$a_from[2],$a_from[0]);
		$this->ts_until = mktime(0,0,0,$a_until[1],$a_until[2],$a_until[0]);
		$today = mktime(0,0,0,$a_today[1],$a_today[2],$a_today[0]);
		env::$smarty->assign("diff_from", floor(($this->ts_from - $today) / 86400));
		env::$smarty->assign("diff_until", floor(($this->ts_until - $today) / 86400));

		$db = new pgwatch_database(env::$db->dbconn);
		$this->points_max = env::sget_conf("chart_points_max");

		$this->gran = $this->get_granularity_subquery($shift_start);

		$this->gap = $this->step . " " . $this->granularity;
	}

	public function calculate_granularity(){
		$diff = $diff_orig = ($this->ts_until - $this->ts_from) / 3600; // difference in hours
		$this->granularity = 'hour';
		$this->granularity_sec = 3600;
		$this->date_pattern = 'YYYY-MM-DD HH24"h"';
		$this->date_pattern_day = 'YYYY-MM-DD';
		$this->step =  1; if ($diff/$this->step <= $this->points_max) return;
		$this->step =  2; if ($diff/$this->step <= $this->points_max) return;
		$this->step =  3; if ($diff/$this->step <= $this->points_max) return;
		$this->step =  4; if ($diff/$this->step <= $this->points_max) return;
		$this->step =  6; if ($diff/$this->step <= $this->points_max) return;
		$this->step =  8; if ($diff/$this->step <= $this->points_max) return;
		$this->step = 10; if ($diff/$this->step <= $this->points_max) return;
		$this->step = 12; if ($diff/$this->step <= $this->points_max) return;
		$this->granularity = 'day';
		$this->granularity_sec = 3600 * 24;
		$this->date_pattern_day = $this->date_pattern = 'YYYY-MM-DD';
		$diff = $diff_orig/24;
		$this->step =  1; if ($diff/$this->step <= $this->points_max) return;
		$this->step =  2; if ($diff/$this->step <= $this->points_max) return;
		$this->step =  3; if ($diff/$this->step <= $this->points_max) return;
		$this->step =  4; if ($diff/$this->step <= $this->points_max) return;
		$this->step =  5; if ($diff/$this->step <= $this->points_max) return;
		$this->granularity = 'week';
		$this->granularity_sec = 3600 * 24 * 7;
		$this->date_pattern_day = $this->date_pattern = 'YYYY-MM-DD';
		$diff = $diff_orig/24/7;
		$this->step =  1; if ($diff/$this->step <= $this->points_max) return;
		$this->step =  2; if ($diff/$this->step <= $this->points_max) return;
		$this->granularity = 'month';
		$this->granularity_sec = 3600 * 24 * 30;
		$this->date_pattern_day = $this->date_pattern = 'YYYY-MM';
		$diff = $diff_orig/24/30;
		$this->step =  1; if ($diff/$this->step <= $this->points_max) return;
		$this->step =  2; if ($diff/$this->step <= $this->points_max) return;
		$this->step =  3; if ($diff/$this->step <= $this->points_max) return;
		$this->step =  4; if ($diff/$this->step <= $this->points_max) return;
		$this->step =  6; if ($diff/$this->step <= $this->points_max) return;
		$this->granularity = 'year';
		$this->granularity_sec = 3600 * 24 * 365;
		$this->date_pattern_day = $this->date_pattern = 'YYYY';
		$this->step =  1; return;
	}

	public function get_granularity_subquery($shift_start=0){
		$this->calculate_granularity();

		if (env::sget_conf('truncate_date_filtering_to_whole_intervals')){
			$until_now = ($this->date_until == strftime("%Y-%m-%d"));
			$diff_sec = ($until_now ?time() :$this->ts_until) - $this->ts_from;
			$s = $this->step;
			$gsec = $this->granularity_sec;
			$num_subintervals = $diff_sec / ($s * $gsec);

			if ($until_now){
				$this->ts_until = $this->ts_from + floor($num_subintervals) * $s * $gsec;
			}else{
				$this->ts_until = $this->ts_from + ceil($num_subintervals) * $s * $gsec;
			}
			
			$this->date_until = strftime("%Y-%m-%d", $this->ts_until);
		}

		$g = $this->granularity;
		$gsec = $this->granularity_sec;
		$s = $this->step;
		$sp1 = $this->step+1;
		$s2 = $s/2;
		$p = $this->date_pattern;
		$pd = $this->date_pattern_day;
		$d1 = $this->date_from;
		$d2 = $this->date_until;

		if ($shift_start == 0){
			$sql = "SELECT \n".
				"to_char(d, '{$p}') AS date_a, \n".
				"to_char(d+'{$s} {$g}'::interval, '{$p}') AS date_b, \n".
				"d AS date_a2, \n".
				"(d+'{$s} {$g}'::interval) AS date_b2, \n".
				"to_char(d, '{$p}') AS date_m \n".
				"FROM (SELECT * FROM generate_series(\n".
				"'{$d1}'::timestamptz, \n".
				"'least({$d2}'::timestamptz+'1 day'::interval, \n".
				"(SELECT tstamp FROM log.t_sync ORDER BY id DESC LIMIT 1)), \n".
				"'{$s} {$g}'::interval \n".
				") AS d) AS x";
		}else{
			$shift_amount = $s * $shift_start;
			$sql = "SELECT \n".
				"to_char(d, '{$p}') AS date_a, \n".
				"to_char(d+'{$s} {$g}'::interval, '{$p}') AS date_b, \n".
				"d AS date_a2, \n".
				"(d+'{$s} {$g}'::interval) AS date_b2, \n".
				"to_char(d, '{$p}') AS date_m \n".
				"FROM (SELECT * FROM generate_series(\n".
				"'{$d1}'::timestamptz-'{$shift_amount} {$g}'::interval, \n".
				"least('{$d2}'::timestamptz+'1 day'::interval, \n".
				"(SELECT tstamp FROM log.t_sync ORDER BY id DESC LIMIT 1)), \n".
				"'{$s} {$g}'::interval \n".
				") AS d) AS x";
		}
		return $sql;
	}

}

?>
