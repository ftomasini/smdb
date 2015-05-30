<?php

//include $root_wd . "classes/FusionCharts.php";
include $root_wd . "classes/FusionCharts_Gen.php";

class chart {

	public $fc;
	public $type;
	public $width;
	public $height;
	public $caption;
	public $x_axis_name;
	public $y_axis_name;
	public $x_axis_min;
	public $x_axis_max;
	public $y_axis_min;
	public $y_axis_max;
	public $number_prefix;
	public $decimal_precision;
	public $format_number_scale;
	public $animation;
	public $params;
	public $addparams;
	public $paginator;
	public $hidden_cols; // which columns are to be hidden by default for multiseries charts because of null datasets

	//public static $colors = array("00ff50", "008b2b", "007cd1", "004473", "9a00dc", "500071", "ec3462", "7e152f", "ca5711", "77350e");
	public static $colors = array(
		"AFD8F8", "F6BD0F", "8BBA00", "FF8E46", "008E8E", "D64646", "8E468E", "588526",
		"B3AA00", "008ED6", "9D080D", "A186BE", "CC6600", "FDC689", "ABA000", "F26D7D",
		"FFF200", "0054A6", "F7941C", "CC3300", "006600", "663300", "6DCFF6",
		
		"c63836", "638035", "a78235", "fe6150", "462a1e" /* "613b29" */, "26a52f", "9c2c55", "c1272d", "5e7683",
		"a6c3d1", "00a99d", /* - Mercedes-Benz, Gizella StraBe, Szolnok */ "FF0000", "8CC63F", "662D91", "ED1E79");
	
		/*
		"0000ff", "000080", "00ff00", "008000", "ff0000", "800000", 
		"ff00ff", "800080", "00ffff", "008080", "ffff00", "808000", 
		"8000ff", "400080", "00ff80", "008040", "ff8000", "804000", 
		"0080ff", "004080", "80ff00", "408000", "ff0080", "800040", 
		"8080ff", "404080", "80ff80", "408040", "ff8080", "804040", 
		);
		/* "00ff50", "008b2b", "007cd1", "004473", "9a00dc", "500071", "ec3462", "7e152f", "ca5711", "77350e",
		"7070d0", "4e4e91", "f0a0d0", "a87091", "f0a070", "a8704e", "f0f090", "a8a864", "6090e0", "43649c", "d04090",
		"912c64", "7050d0", "4e3891", "c0a050", "867038", "707040", "4e4e2c", "a0c080", "708659", "70c030", "4e8621", "50c0a0", "388670",
		"90b090", "647b64", "30f030", "21a821", "f0d0f0", "a891a8", "c0b040", "867b2c", "60f060", "43a843", "40b0e0", "2c7b9c", "703060",
		"4e2143", "80b030", "597b21", "e0f0d0", "9ca891", "e05090", "9c3864", "90b050", "647b38", "30c040", "21862c", "30b0f0", "217ba8",
		"3070a0", "214e70", "40b090", "2c7b64", "80c050", "598638", "703050", "4e2138", "a080d0", "705991", "b070d0", "7b4e91");*/

	public $legend = array();
	private $data_count = 0;

	/* Column2D          Single Series Column 2D Chart
	 * Column3D          Single Series Column 3D Chart
	 * Line              Single Series Line Chart
	 * Pie3D             Single Series Pie 3D Chart
	 * Pie2D             Single Series Pie 2D Chart
	 * Bar2D             Single Series Bar 2D Chart
	 * Area2D            Single Series Area 2D Chart
	 * Doughnut2D        Single Series Doughnut 2D Chart
	 * MSColumn3D        Multi-Series Column 3D Chart
	 * MSColumn2D        Multi-Series Column 2D Chart
	 * MSArea2D          Multi-Series Column 2D Chart
	 * MSLine            Multi-Series Line Chart
	 * MSBar2D           Multi-Series Bar Chart
	 * StackedColumn2D   Stacked Column 2D Chart
	 * StackedColumn3D   Stacked Column 3D Chart
	 * StackedBar2D      Stacked Bar 2D Chart
	 * StackedArea2D     Stacked Area 2D Chart
	 * MSColumn3DLineDY  Combination Dual Y Chart (Column 3D + Line)
	 * MSColumn2DLineDY  Combination Dual Y Chart (Column 2D + Line)
	 **/
	public function __construct($type, $width=null, $height=null){
		$this->type = $type;
		$db = new pgwatch_database(env::$db->dbconn);
		$this->width = $width ?$width :env::sget_conf("chart_size_x", $db);
		$this->height = $height ?$height :env::sget_conf("chart_size_y", $db);
		$this->fc = new FusionCharts($type, $this->width, $this->height);
		$this->fc->setSWFPath("common/swf/");
		$this->params = null;
		$this->addparams = null;
		$this->caption = "";
		$this->decimal_precision = 0;
		$this->format_number_scale = 1;
		$this->number_prefix = "";
		$this->x_axis_name = "";
		$this->y_axis_name = "";
		$this->x_axis_min = null;
		$this->x_axis_max = null;
		$this->y_axis_min = null;
		$this->y_axis_max = null;
		$this->animation = env::sget_conf("chart_animation", $db);
		$this->fc->addColors(join(";",self::$colors));
		$this->paginator = null;
		$this->hidden_cols = array("__total_rows__");
	}

	// only for general 2d arrays with [name, value] arranged rows
	public function set_data_from_array(&$data){
		foreach ($data as $i=>&$row){
			$this->add_data($row[1], $row[0]);
		}
	}

	public function add_data($value, $name="", $params=""){
		$parts = explode("|", $name);
		$swf_label = array_shift($parts);
		$name = str_replace("|","", $name);
		$this->fc->addChartData($value, "name=".$swf_label.($params ?";{$params}" :""));
		$link = self::get_param("link", $params);
		$href = preg_replace('/^.+historyLoad\(\'(.+)\'\).*$/', '\\1', $link);
		//if ($link) $this->legend[] = "<a class='history' href='#{$href}' onclick='".str_replace("'","&apos;",$link).";return false;'>{$name}</a>";
		if ($link) $this->legend[] = "<a class='history' href='#{$href}'>{$name}</a>";
		else $this->legend[] = $name;
	}
	
	public function get_legend_html($args=null){
		$horizontal = true;
		$colorbox_onclick_js = null;
		$show_toggler = false;
		$toggler_onclick_js = null;
		if (is_array($args)) extract($args);
		$ret = array();
		$br = $horizontal ?"" :"<br class='clear'/>";
		foreach ($this->legend as $i=>$l){
			$color = self::$colors[$i % count(self::$colors)];
			$onclick = $colorbox_onclick_js ?"onclick=\"".str_replace(array('"','%index%'), array("&quot;",$i), $colobox_onclick_js)."\"" :"";
			if ($show_toggler){
				$cb_checked = isset($this->hidden_cols[$i]) ?"" :"checked=\"checked\"";
				$cb_onclick = "";
				$checkbox = "<input type=\"checkbox\" {$checked}/>";
			}else{
				$checkbox = "";
			}
			$ret[] = "<span title='".strip_tags($l)."'>".
				"<span class='chart-legend-box' style='background-color:#{$color}' {$onclick}>&nbsp;&nbsp;&nbsp;&nbsp;</span>".
				"<span class='chart-legend-label'>{$l}</span></span>".$br." ";
		}
		return "<div class='chart-legend' id='".$this->fc->chartID."-legend'>" .
			join("", $ret) .
			" <button onclick='chart_legend_toggle(this,&apos;".$this->fc->chartID."-legend&apos;)' title='Toggle labels' class='shrink'></button>".
			"</div>";
	}

	public function set_paginator($paginator_assoc){
		$this->paginator = $paginator_assoc;
		if (!isset($this->paginator['rows_per_page'])){
			$this->paginator['rows_per_page'] = env::sget_conf("chart_rows_per_page", $db);
		}
	}

	public function get_paginator_html(){
		if (!is_array($this->paginator)) return null;
		$tpl = new pgwatch_smarty();
		$tpl->assign($this->paginator);
		$tpl->assign("id", $this->fc->chartID);
		return $tpl->fetch("chart_paginator.tpl");
	}

	public function set_params(){
		if (is_null($this->params)){
			$this->params = 
				"caption=".self::escape($this->caption).";".
				"xAxisName=".self::escape($this->x_axis_name).";".
				"yAxisName=".self::escape($this->y_axis_name).";".
				"numberPrefix=".self::escape($this->number_prefix).";".
				"decimalPrecision=".self::escape($this->decimal_precision).";".
				"formatNumberScale=".self::escape($this->format_number_scale).";".
				"animation=".self::escape($this->animation);
			$this->params .= $this->x_axis_min ?";xAxisMinValue={$this->x_axis_min}" :"";
			$this->params .= $this->x_axis_max ?";xAxisMaxValue={$this->x_axis_max}" :"";
			$this->params .= $this->y_axis_min ?";yAxisMinValue={$this->y_axis_min}" :"";
			$this->params .= $this->y_axis_max ?";yAxisMaxValue={$this->y_axis_max}" :"";
		}
		$this->params .= $this->addparams ?";{$this->addparams}" :"";
		$base_params = "chartLeftMargin=20;chartRightMargin=20;chartTopMargin=10;chartBottomMargin=10;";
		$this->fc->setChartParams($base_params . $this->params);
	}

	public function fetch(){
		$this->set_params();
		$ret = $this->fc->renderChart(false, false);
		$id = $this->fc->chartID;
		if (is_array($this->paginator)){
			if (isset($this->paginator['paginated_query'])){
				$_db = $this->paginator['paginated_query'];
				if ($_db->paginate){
					$this->paginator['total_records'] = $_db->get_paginated_total();
					$this->paginator['rows_per_page'] = $_db->rows_per_page;
					$this->paginator['pager'] = $_db->pager;
				}
			}
			if (isset($this->paginator['total_records']) && isset($this->paginator['rows_per_page'])
				&& $this->paginator['total_records'] > $this->paginator['rows_per_page']){
				$ret = "<div id='{$id}-paginator-top'></div>\n".
					$ret .
					/* "<div id='{$id}-paginator-bottom'></div>\n". */
					$this->get_paginator_html();
			}
		}
		return $ret;
	}

	public function output(){
		$this->set_params();
		$this->fc->renderChart(false, true);
	}

	public static function get_chart_html($result, $type, $args=null, $hold=false){
		// $time = microtime(1);
		$show_legend = true;
		$show_datatable = false;
		$chart_layout = "chart_layout_default.tpl";
		$caption = "";
		$extract_args = array("width", "height", "link_target", "link_params", "value_column", "label_column",
			"show_legend", "legend_args", "paginator", "show_datatable", "datatable_args", "chart_layout",
			"caption", "x_axis_min", "x_axis_max", "y_axis_min", "y_axis_max");
		foreach ($extract_args as $key){
			if (isset($args[$key])){
				${$key} = $args[$key];
				unset($args[$key]);
			}elseif (!isset(${$key})){
				${$key} = null;
			}
		}
		if (is_null($value_column)){
			for ($i=0; $i < pg_num_fields($result); $i++){
				if ($i==0) $value_column = pg_field_name($result, $i); // to provide a default
				if (in_array(pg_field_type($result, $i),
						array("integer","int4","bigint","int8","decimal","number","float","double"))){
					$value_column = pg_field_name($result, $i);
					break;
				}
			}
		}
		if (is_null($value_column)){
			echo "Unknown value column";
			return;
		}

		$data = $hold
			?env::$db->fetch_all_assoc_hold(null, null, $result)
			:env::$db->fetch_all_assoc(null, null, $result);
		$c = new self($type, $width, $height);
		if (is_array($args)){
			foreach ($args as $key=>$val){
				$c->$key = $val;
			}
		}

		$c->caption = $caption;

		if (isset($paginator)){
			$c->set_paginator($paginator);
		}

		$min_value = 0; //count($data)>0 ?$data[0][$value_column] :0;
		$max_value = count($data)>0 ?$data[0][$value_column] :0;
		foreach ($data as $i => &$row){
			$params = array();
			$name = !is_null($label_column) ?self::escape($row[$label_column]) :"";
			if (!is_null($link_target)){
				$lparams = "";
				if (isset($link_params)){
					foreach ($link_params as $lkey=>$link_col){
						$paramname = is_numeric($lkey) ?$link_col :$lkey;
						if (isset($row[$link_col])){
							$lparams .= "&" . $paramname . "=" . $row[$link_col];
						}
					}
				}
				$params[] = "link=javascript:$.historyLoad('" . $link_target . $lparams . "')";
			}
			$param = join(";", $params);
			$value = $row[$value_column];
			$c->add_data($value, $name, $param);
			$min_value = min($min_value, $value);
			$max_value = max($max_value, $value);
		}
		if ($max_value - $min_value == 0){
			$y_axis_min = $min_value;
			$y_axis_max = $min_value + 100;
		}
		if (count($data) > 10) $c->addparams .= ";rotateNames=1";

		$legend = $show_legend ?$c->get_legend_html($legend_args) :"";

		if (isset($x_axis_min)) $c->x_axis_min = $x_axis_min;
		if (isset($x_axis_max)) $c->x_axis_max = $x_axis_max;
		if (isset($y_axis_min)) $c->y_axis_min = $y_axis_min;
		if (isset($y_axis_max)) $c->y_axis_max = $y_axis_max;

		if ($show_datatable){
			$table = yui_datatable::get_table_html($data, "datatable_".$c->fc->chartID, $datatable_args);
		}else{
			$table = "";
		}

		$chart = $c->fetch();

		$tpl = new pgwatch_smarty();
		$tpl->assign('chart' , $chart);
		$tpl->assign('legend', $legend);
		$tpl->assign('table' , $table);
		//$time = round(1000*(microtime(1)-$time),3);
		// $time = "<div style='border:1px solid red; float:left;'>Chartgen php:".$time."ms</div>";
		$ret = $tpl->fetch($chart_layout);//.$time;
		return $ret;
	}

	public static function escape($str){
		$str = str_replace(
			array("€",  "%",  "&",    ">",   "<",   "'"),
			array("%80","%25","&amp;","&gt;","&lt;","&apos;"),
			$str);
		return $str;
	}

	public static function format_duration($sec){
	
		$y  = floor($sec/31536000);
		$mo = floor($sec/2628000);
		$w  = floor($sec/604800);
		$d  = floor($sec/86400);
		$h  = floor($sec/3600);
		$m  = floor($sec/60);
		$s  = floor($sec);
		if ($y > 0){
			return "{$y}y".($mo%12)."m";
		}elseif ($mo > 0){
			return "{$mo}mo".($d>=30 ?(($d % 30)."d") :"");
		}elseif ($w > 0){
			return "{$w}w".($d>=7  ?(($d % 7)."d") :"");
		}elseif ($d > 0){
			return "{$d}d".($h>=24 ?(($h % 24)."h") :"");
		}elseif ($h > 0){
			return "{$h}h".($m>=60 ?(($m % 60)."m") :"");
		}elseif ($m > 0){
			return "{$m}m".($s>=60 ?(($s % 60)."s") :"");
		}else{
			return round($sec,2)."s";
		}
	
	}

	public static function get_param($key, $param_str){
		$params = explode(";",$param_str);
		foreach ($params as $param){
			list($k,$v) = explode("=",$param,2) + array(null,null);
			if (trim($k) == $key) return trim($v);
		}
		return null;
	}

	public function add_category($label, $params=""){
		$this->fc->addCategory($label, $params);
	}

	public function add_dataset($label, $params=""){
		$this->fc->addDataset($label, $params);
		$link = chart::get_param("link", $params);
		$href = preg_replace('/^.+historyLoad\(\'(.+)\'\).*$/', '\\1', $link);
		//if ($link) $this->legend[] = "<a class='history' href='#{$label}' onclick='".str_replace("'","&apos;",$link).";return false;'>{$label}</a>";
		if ($link) $this->legend[] = "<a class='history' href='#{$href}'>{$label}</a>";
		else $this->legend[] = $label;
	}

	public function add_data_ms($value, $params=""){
		$this->fc->addChartData($value, $params);
	}

	public static function get_ms_chart_html($data, $type, $args=null){
		//$time = microtime(1);
		$show_all_x_labels = true;
		$show_datatable = false;
		$datatable_args = null;
		$show_legend = true;
		$datatable_values_formatter = "number";
		$datatable_toggle_cols = true;
		$datatable_show_top_cols = 0;
		$datatable_show_bottom_cols = 0;
		$chart_layout = "chart_layout_default.tpl";
		$caption = "";
		$order = null;
		$extract_args = array("width", "height", "show_legend", "legend_args", "show_all_x_labels",
			"show_datatable", "datatable_args", "datatable_values_formatter", "datatable_show_top_cols",
			"datatable_show_bottom_cols", "datatable_toggle_cols", "paginator", "chart_layout",
			"caption", "order", "x_axis_min", "x_axis_max", "y_axis_min", "y_axis_max");
		foreach ($extract_args as $key){
			if (isset($args[$key])){
				${$key} = $args[$key];
				unset($args[$key]);
			}elseif (!isset(${$key})){
				${$key} = null;
			}
		}

		$c = new self($type, $width, $height);
		if (is_array($args)){
			foreach ($args as $key=>$val){
				$c->$key = $val;
			}
		}
		$c->caption = $caption;
		$c->addparams = "showLegend=0;rotateNames=".($show_all_x_labels?1:0);
		if (count($data)>0){
			if ($show_all_x_labels){
				foreach ($data[0]['dataset_cats_vals'] as $cat => &$val){
					$c->add_category($cat, "showName=1");
				}
			}else{
				$cat_count = count($data[0]['dataset_cats_vals']);
				$labels_show = array();
				$labels_show[0] = 1;
				$labels_show[round($cat_count/2)] = 1;
				$labels_show[$cat_count-1] = 1;
				$i=0;
				foreach ($data[0]['dataset_cats_vals'] as $cat => &$val){
					$c->add_category($cat, "showName=".(isset($labels_show[$i])?1:0));
					$i++;
				}
			}
		}else{
			$cat_count = 0;
			$labels_show = array();
		}

		if (isset($paginator)){
			$c->set_paginator($paginator);
		}

		if ($order){
			switch ($order){
				case "avg": usort($data, array("chart", "compare_vectors_avg"));break;
			}
		}

		$datacount = count($data);
		
		unset($val);
		$min_value = 0; //2000000000;
		$max_value = -2000000000;
		foreach ($data as $di => &$row){
			unset($dataset_name, $dataset_cats_vals, $params, $cat, $val);
			$dataset_name = &$row['dataset_name'];
			$dataset_cats_vals = &$row['dataset_cats_vals'];
			$params = $row['params'];
			$c->add_dataset($dataset_name, $params.";showValues=0");
			foreach ($dataset_cats_vals as $cat=>&$val){
				$c->add_data_ms($val, $params);
			}
			$min_value = min(min($dataset_cats_vals), $min_value);
			$max_value = max(max($dataset_cats_vals), $max_value);
		}

		if ($min_value == 2000000000) $min_value = 0;
		if ($max_value == -2000000000) $max_value = 0;

		if ($max_value - $min_value == 0){
			$y_axis_min = $min_value;
			$y_axis_max = $min_value + 100;
		}

		if (isset($x_axis_min)) $c->x_axis_min = $x_axis_min;
		if (isset($x_axis_max)) $c->x_axis_max = $x_axis_max;
		if (isset($y_axis_min)) $c->y_axis_min = $y_axis_min;
		if (isset($y_axis_max)) $c->y_axis_max = $y_axis_max;

		$legend = $show_legend ?$c->get_legend_html($legend_args) :"";
		
		if ($show_datatable){
			if ($datatable_show_top_cols > 0 || $datatable_show_bottom_cols > 0){
				$sorter = array();
				unset($dataset);
				foreach ($data as $di => &$dataset){
					unset($dkey, $dval);
					foreach ($dataset['dataset_cats_vals'] as $dkey=>&$dval){
						if (!is_null($dval)){
							if (!isset($sorter[$di])) $sorter[$di] = 0;
							$sorter[$di] += $dval;
						}
					}
				}
				unset($dataset, $dval);
				asort($sorter, SORT_NUMERIC);
				$i = 0;
				foreach ($sorter as $dindex => &$dsum){
					$i++;
					if ($datatable_show_top_cols > 0 && $i < $datacount-$datatable_show_top_cols){
						unset($data[$dindex]);
					}elseif ($datatable_show_bottom_cols > 0 && $i > $datatable_show_bottom_cols){
						unset($data[$dindex]);
					}
				}
			}
			unset($dsum);
			$formatters = array();
			$table_data = array();
			foreach ($data as $di => &$dataset){
				foreach ($dataset['dataset_cats_vals'] as $dkey=>&$dval){
					if (!isset($table_data[$dkey])) $table_data[$dkey] = array('Time'=>$dkey);
					$table_data[$dkey][$dataset['dataset_name']] = $dval;
					$formatters[$dataset['dataset_name']] = $datatable_values_formatter;
				}
			}
			if (isset($datatable_args['formatters'])){
				$datatable_args['formatters'] = array_merge($formatters, $datatable_args['formatters']);
			}else{
				$datatable_args['formatters'] = $formatters;
			}
			
			$table_data = array_values($table_data);
			$table = yui_datatable::get_table_html($table_data, "datatable_".$c->fc->chartID, $datatable_args);
		}else{
			$table = "";
		}
		
		$chart = $c->fetch();

		$tpl = new pgwatch_smarty();
		$tpl->assign('chart' , $chart);
		$tpl->assign('legend', $legend);
		$tpl->assign('table' , $table);
		//$time = round(1000*(microtime(1)-$time),3);
		//$time = "<div style='border:1px solid red; float:left;'>Chartgen php:".$time."ms</div>";
		$ret = $tpl->fetch($chart_layout);//.$time;
		return $ret;
	}

	public static function compare_vectors_avg(&$a, &$b){
		$aa = array_sum($a)/count($a);
		$ba = array_sum($b)/count($b);
		return $aa < $ba ? 	1 : ($aa > $ba ? -1 : 0);
	}

}

?>
