<?php

class pgwatch_xml {

	public $sxml;
	/** internal variables dynamically set based on xml data into $vars */
	public $input;
	public $vars;
	public $allowed_elements;
	public $query_time;

	public function __construct(&$xml, $input, $allowed_elements=null){
		if ($xml instanceof SimpleXMLElement){
			$this->sxml = $xml;
		}elseif (is_string($xml)){
			$this->sxml = new SimpleXMLElement($xml /*, LIBXML_XINCLUDE */);
		}else{
			trigger_error("pgwatch_xml: given parameter is not a valid xml.", E_USER_ERROR);
		}
		$this->vars = array();
		$timeline = (string) $this->sxml['timeline'] == "true";
		if ($timeline){
			$this->vars['dates'] = new dates($input,1);
		}
		if (!is_null($this->sxml['inputs'])){
			$this->input = array();
			$input_keys = preg_replace('/\s+/', ' ', trim((string) $this->sxml['inputs']));
			foreach (explode(" ", $input_keys) as $key){
				if (isset($input[$key])){
					$this->input[$key] = $input[$key];
				}
			}
		}else{
			$this->input = $input;
		}
		$this->allowed_elements = $allowed_elements;
		$this->query_time = 0;
	}

	public function substitute($text){
		while (true) {
			preg_match('/({{([^}]+)}})/', $text, $match);
			if (count($match) < 2) break;
			$parts = explode(":", $match[2]);
			$replace = "";
			switch ($parts[0]){
				case "input":
					if (count($parts) != 2)
						trigger_error("Substitute:{$parts[0]}: Wrong part count in {$match[1]}.", E_USER_ERROR);
					$optional = false;
					if ($parts[1][0] == "@"){
						$parts[1] = substr($parts[1], 1);
						$optional = true;
					}
					if ($optional && !isset($this->input[$parts[1]])){
						$this->input[$parts[1]] = "";
					}
					if (isset($this->input[$parts[1]])){
						$replace = isset($this->input[$parts[1]]) ?$this->input[$parts[1]] :"";
					}else{
						trigger_error("Substitute:{$parts[0]}: Input not found: {$parts[1]}.", E_USER_ERROR);
					}
					break;
				case "db_result":
					if (!isset($this->vars[$parts[1]])){
						trigger_error("Substitute:{$parts[0]}: No query with given varname: {$parts[1]}.", E_USER_ERROR);
					}
					if (count($parts) != 3 && count($parts) != 4)
						trigger_error("Substitute:{$parts[0]}: Wrong part count in {$match[1]}.", E_USER_ERROR);
					if (!$this->vars[$parts[1]] instanceof database)
						trigger_error("Substitute:{$parts[0]}: Not a valid query result: {$match[1]}.", E_USER_ERROR);
					$db = $this->vars[$parts[1]];
					$pos = isset($parts[3]) ?$parts[3] :"";
					$pos = $pos == "" ?"nostep" :$pos;
					if ($pos == "step"){
						$replace = $db->fetch_col($parts[2]);
					}elseif ($pos == "nostep"){
						$pos = $db->pos;
						$replace = $db->fetch_col($parts[2]);
						$db->seek($pos);
					}elseif (is_numeric($pos)){
						$db->seek($pos);
						$replace = $db->fetch_col($parts[2]);
					}else{ // same as "step"
						$replace = $db->fetch_col($parts[2]);
					}
					break;
				case "db_method":
					if (!isset($this->vars[$parts[1]]))
						trigger_error("Substitute:{$parts[0]}: No query with given varname: {$parts[1]}.", E_USER_ERROR);
					if (count($parts) != 3)
						trigger_error("Substitute:{$parts[0]}: Wrong part count in {$match[1]}.", E_USER_ERROR);
					if (!$this->vars[$parts[1]] instanceof database)
						trigger_error("Substitute:{$parts[0]}: Not a valid query result: {$match[1]}.", E_USER_ERROR);
					$db_method = $parts[2];
					$db = $this->vars[$parts[1]];
					$replace = $db->{$db_method}();
					break;
				case "var":
					if (count($parts)!=2 && count($parts)!=3)
						trigger_error("Substitute:{$parts[0]}: Wrong part count in {$match[1]}.", E_USER_ERROR);
					$optional = false;
					if ($parts[1][0] == "@"){
						$parts[1] = substr($parts[1], 1);
						$optional = true;
					}
					if ($optional && !isset($this->vars[$parts[1]])){
						$this->vars[$parts[1]] = "";
					}
					if(!isset($this->vars[$parts[1]])){
						trigger_error("Substitute:{$parts[0]}: No var with given varname: {$parts[1]}.", E_USER_ERROR);
					}
					if (isset($parts[2])){
						if (is_object($this->vars[$parts[1]])){
							$replace = $this->vars[$parts[1]]->{$parts[2]};
						}elseif (is_array($this->vars[$parts[1]])){
							$replace = $this->vars[$parts[1]][$parts[2]];
						}else{
							trigger_error("Substitute:{$parts[0]}: With 2 parameters var must be object or array: {$parts[1]}.", E_USER_ERROR);
						}
					}else{
						$replace = $this->vars[$parts[1]];
					}
					break;
				case "config":
					if (count($parts)!=2 && count($parts)!=3)
						trigger_error("Substitute:{$parts[0]}: Wrong part count in {$match[1]}.", E_USER_ERROR);
					$replace = env::sget_conf($parts[1], env::$db);
					if(is_null($replace)){
						if (count($parts)==3){ // default value is set
							$replace = $parts[2];
						}else{
							trigger_error("Substitute:{$parts[0]}: No config setting with given name: {$parts[1]}.", E_USER_ERROR);
						}
					}
					break;
				default:
					break;
			}
			$text = str_replace($match[1], $replace, $text);
		}
		return $text;
	}

	public function get_param_value($param){
		$value = $param['value'];
		$value = is_null($value) ?(string) $param :$value;
		$value = $this->substitute($value);
		$type = (string) $param['type'];
		$append = (string) $param['append'];
		if ($append != "" && $type != "array") trigger_error("Attribute 'append' needs type 'array'.", E_USER_ERROR);
		switch ($type){
			default:
			case "string": $value = (string) $value; break;
			case "int": $value = (integer) $value; break;
			case "bool": $value = $value=="true" ?true :false; break;
			case "array":
				$name = (string) $param['name'];
				if ($append != "" && $name == "") trigger_error("Attribute 'name' is neccessary to know what to append.", E_USER_ERROR);
				if ($append == "true"){
					if (!isset($this->vars[$name])){
						$value = array();
					}elseif (!is_array($this->vars[$name])){
						$value = (array) $this->vars[$name];
					}else{
						$value = $this->vars[$name];
					}
				}else{
					$value = array();
				}
				foreach ($param->param as $p){
					$key = $p['name'];
					if (!is_null($key)){
						$value[(string) $key] = $this->get_param_value($p);
					}else{
						$value[] = $this->get_param_value($p);
					}
				}
				break;
			case "eval": eval('$value = ' . $value . ';');break;
			case "db_method":
				$source = (string) $param['source'];
				if ($source == "")
					trigger_error("Missing 'source' attribute from parameter of type: db_method.", E_USER_ERROR);
				$method = (string) $param['method'];
				if ($method == "")
					trigger_error("Missing 'method' attribute from parameter of type: db_method.", E_USER_ERROR);
				$func_params = array();
				if (count($param->param) > 0){
					foreach ($param->param as $p){
						$key = $p['name'];
						if (!is_null($key)){
							$func_params[(string) $key] = $this->get_param_value($p);
						}else{
							$func_params[] = $this->get_param_value($p);
						}
					}
				}
				$value = call_user_func_array(array($this->vars[$source], $method), $func_params);
				break;
			case "db_result":
				$source = (string) $param['source'];
				if ($source == "")
					trigger_error("Missing 'source' attribute from parameter of type: db_result.", E_USER_ERROR);
				$column = (string) $param['column'];
				if ($column == "")
					trigger_error("Missing 'column' attribute from parameter of type: db_result.", E_USER_ERROR);
				$db = $this->vars[$source];
				$pos = (string) $param['pos'];
				$pos = $pos == "" ?"nostep" :$pos;
				if ($pos == "step"){
					$value = $db->fetch_col($column);
				}elseif ($pos == "nostep"){
					$pos = $db->pos;
					$value = $db->fetch_col($column);
					$db->seek($pos);
				}elseif (is_numeric($pos)){
					$db->seek($pos);
					$value = $db->fetch_col($column);
				}else{ // same as step
					$value = $db->fetch_col($column);
				}
				break;
			case "input": $value = $this->input[(string) $value]; break;
			case "var":
				$source = (string) $param['source'];
				if ($source == ""){
					$source = (string) $param['name'];
					if ($source == ""){
						trigger_error("Missing 'source' or 'name' attribute from parameter of type: var.", E_USER_ERROR);
					}
				}
				$value = $this->vars[$source];
				break;
			case "config":
				$source = (string) $param['source'];
				if ($source == ""){
					$source = (string) $param['name'];
					if ($source == ""){
						trigger_error("Missing 'source' or 'name' attribute from parameter of type: config.", E_USER_ERROR);
					}
				}
				$value = env::sget_conf($source, env::$db);
				break;
		}
		return $value;
	}

	public function process_query($q){
		$cond = (string) $q['sql_condition'];

		// sql_condition if false, the whole query element is skipped
		if ($cond != ""){
			$cond = $this->substitute($cond);
			$tmp = env::$db->query("SELECT CASE WHEN {$cond} THEN 1 ELSE 0 END");
			if (!$tmp) return false;
			$result = env::$db->fetch_1();
			if ($result == "0") {
				// if false, skip query element evaluation
				
				// log query for debug purpose
				if (env::sget_conf('log_xml_queries')){
					$res = env::$db->query_params("INSERT INTO log.t_query_log (url, cond, started) VALUES ($1, $2, now())",
						array($_SERVER['REQUEST_URI'], $cond));
				}
				return;
			}
		}
		$log = (string) $q['log'];
		if ($log != ""){
			$log = $this->substitute($log);
			$loglevel = (string) $q['loglevel'];
			if (!$loglevel) $loglevel = "NOTICE";
			new log($loglevel, $log);
		}
		$varname = (string) $q['varname'];
		$debug = (string) $q['debug'] == "true";
		$query = trim((string) $q);
		$query = $this->substitute($query);
		//$time = microtime(1);echo "<div style='float:left;border:1px dotted blue;padding 2px;margin-right:10px' title='".str_replace("'","&apos;",$query)."'>q:";
		// log query for debug purpose
		if (env::sget_conf('log_xml_queries')){
			$res = env::$db->query_params("INSERT INTO log.t_query_log (url, cond, query, started) VALUES ($1, $2, $3, now()) RETURNING id",
				array($_SERVER['REQUEST_URI'], $cond, $query));
			if (!$res) return false;
			$log_id = env::$db->fetch_1();
		}
		$db = new pgwatch_database(env::$db->dbconn);
		$db->paginate = ((string) $q['paginate']) == "true";
		if ($db->paginate){
			$db->rows_per_page = (string) $q['rows_per_page'];
			$db->rows_per_page = !empty($db->rows_per_page) ?$db->rows_per_page :env::sget_conf('chart_rows_per_page');
			$db->rows_per_page = max($db->rows_per_page, 1);
			$pagername = (string) $q['pager'];
			$pagername = empty($pagername) ?"pager" :$pagername;
			$db->pager = !empty($this->input[$pagername]) ?$this->input[$pagername] :1;
			$db->pager = max($db->pager, 1);
			$query = $db->paginate_query($query);
		}
		if ($debug)
			$tmp = @$db->queryd($query);
		else
			$tmp = @$db->query($query);
		if (!$tmp) return false;
		$this->query_time += $db->time;
		if ($db->paginate) $db->get_paginated_total();
		// log query for debug purpose
		if (env::sget_conf('log_xml_queries')){
			$num_rows = $db->num_rows($tmp);
			$affected = $db->affected();
			$res = env::$db->query_params("UPDATE log.t_query_log SET duration=clock_timestamp()-started, retrieved=$2, affected=$3 WHERE id=$1",
				array($log_id, $num_rows, $affected));
			if (!$res) return false;
		}
		if ($varname != "") $this->vars[$varname] = $db;
		$sub = false;
		$subret = false;
		// loop through all rows of result
		for ($i = 0; $i < $db->num_rows(); $i++){
			// process subqueries, vars, etc (any subelements)
			foreach ($q->children() as $c){
				$db->seek($i);
				$subret = $this->process_element($c);
				if ($subret) $sub = true;
			}
		}
		// log query for debug purpose
		if ($sub && env::sget_conf('log_xml_queries')){
			$res = env::$db->query("INSERT INTO log.t_query_log (query, started, duration, retrieved, affected) ".
				"VALUES ('Total since {$log_id}', (SELECT started FROM log.t_query_log WHERE id={$log_id}), ".
				"clock_timestamp()-(SELECT started FROM log.t_query_log WHERE id={$log_id}), ".
				"(SELECT sum(retrieved) FROM log.t_query_log WHERE id>{$log_id}), ".
				"(SELECT sum(affected) FROM log.t_query_log WHERE id>{$log_id}))");
			if (!$res) return false;
		}
		//echo round(1000*(microtime(1)-$time),3),"ms</div>";
		return true;
	}

	public function process_var($v){
		$name = (string) $v['name'];
		if ($name == "") trigger_error("Missing 'name' attribute from var element.", E_USER_ERROR);
		$this->vars[$name] = $this->get_param_value($v);
	}

	public function process_if(SimpleXMLElement $e){
		$cond = (string) $e['sql_condition'];
		$bool_result = true;
		// sql_condition if false, the whole query element is skipped
		if ($cond != ""){
			$cond = $this->substitute($cond);
			$tmp = env::$db->query("SELECT CASE WHEN {$cond} THEN 1 ELSE 0 END");
			if (!$tmp) return false;
			else {
				$result = env::$db->fetch_1();
				if ($result == "0") {
					$bool_result = false;
					
					// log query for debug purpose
					if (env::sget_conf('log_xml_queries')){
						$res = env::$db->query_params("INSERT INTO log.t_query_log (url, cond, started) VALUES ($1, $2, now())",
							array($_SERVER['REQUEST_URI'], $cond));
					}
				}
			}
		}

		$cond2 = (string) $e['condition'];
		// condition (eval as php) if false, the whole query element is skipped
		if ($bool_result == true && $cond2 != ""){
			$cond2 = $this->substitute($cond2);
			eval('$value=' . $cond2 . ';');
			if (!$value) $bool_result = false;
		}

		$log = (string) $e['log'];
		if ($log != ""){
			$log = $this->substitute($log);
			$loglevel = (string) $e['loglevel'];
			if (!$loglevel) $loglevel = "NOTICE";
			new log($loglevel, $log);
		}

		if ($bool_result){
			$body = $e->xpath("then");
		}else{
			$body = $e->xpath("else");
		}
		
		if (count($body)>0)
			$this->process_children($body[0]);
	
		return true;
	}

	public function process_case(SimpleXMLElement $e){
		$sql_expr = (string) $e['sql_expression'];
		$expr = (string) $e['expression'];
		if ($sql_expr != "" && $expr != "")
			trigger_error("In case element only one of 'expression' and 'sql_expression' may be set.", E_USER_ERROR);

		$result = "";
		if ($sql_expr != ""){
			$sql_expr = $this->substitute($sql_expr);
			$tmp = env::$db->query("SELECT ({$sql_expr})");
			if (!$tmp) return false;
			else {
				$value = env::$db->fetch_1();
			}
		}

		if ($expr != ""){
			$expr = $this->substitute($expr);
			eval('$value=' . $expr . ';');
		}

		$log = (string) $e['log'];
		if ($log != ""){
			$log = $this->substitute($log);
			$loglevel = (string) $e['loglevel'];
			if (!$loglevel) $loglevel = "NOTICE";
			new log($loglevel, $log);
		}

		$when_lst = $e->xpath("when[@value='{$value}']");
		if (count($when_lst) == 0){
			$when_lst = $e->xpath("when[@default='true']");
		}
		
		foreach ($when_lst as $when){
			$this->process_children($when);
		}
		
		return true;
	}

	public function process_breadcrumb(SimpleXMLElement $e){
		$this->vars['breadcrumb'] = isset($this->vars['breadcrumb']) ?$this->vars['breadcrumb'] :array();
		foreach ($e->item as $item){
			$this->vars['breadcrumb'][] = array(
				$this->substitute((string) $item['type']),
				$this->substitute((string) $item['href']),
				$this->substitute((string) $item['label'] ?(string) $item['label'] :(string) $item)
			);
		}
		return true;
	}

	public function process_rightmenu(SimpleXMLElement $e){
		$this->vars['rightmenu'] = array();
		foreach ($e->param as $param){
			$mname = (string) (string) $param['name'];
			if (is_null($mname)) trigger_error("No 'name' attribute found in rightmenu parameter.", E_USER_ERROR);
			$this->vars['rightmenu'][$mname] = $this->get_param_value($param);
		}
		return true;
	}

	public function process_title(SimpleXMLElement $e){
		$this->vars['title'] = $this->get_param_value($e);
		return true;
	}

	public function process_chart(SimpleXMLElement $e){
		$chart_xml = &$e;
		$args = array();
		foreach ($chart_xml->param as $param_xml){
			$pname = (string) $param_xml['name'];
			if (is_null($pname)) trigger_error("No 'name' attribute found in chart parameter.", E_USER_ERROR);
			$args[$pname] = $this->get_param_value($param_xml);
		}
		$source = (string) $chart_xml['source'];
		if (is_null($source)) trigger_error("Chart tag needs a 'source' attribute.", E_USER_ERROR);
		$type = (string) $chart_xml['type'];
		if (is_null($type)) trigger_error("Chart tag needs a 'type' attribute.", E_USER_ERROR);
		$varname = (string) $chart_xml['varname'];
		if (is_null($varname)) trigger_error("Chart tag needs a 'varname' attribute.", E_USER_ERROR);
		$hold = (string) $chart_xml['hold'] == "true";
		if ((string) $chart_xml['multiseries'] != "true"){
			$this->vars[$varname] = $this->vars[$source]->get_fusion_chart($type, $args, $hold);
		}else{
			$this->vars[$varname] = chart::get_ms_chart_html($this->vars[$source], $type, $args);
		}
		return true;
	}

	public function process_datatable(SimpleXMLElement $e){
		$table_xml = &$e;
		$args = array();
		foreach ($table_xml->param as $param_xml){
			$pname = (string) $param_xml['name'];
			if (is_null($pname)) trigger_error("No 'name' attribute found in table parameter.", E_USER_ERROR);
			$args[$pname] = $this->get_param_value($param_xml);
		}
		$source = (string) $table_xml['source'];
		if (is_null($source)) trigger_error("Table tag needs a 'source' attribute.", E_USER_ERROR);
		$type = (string) $table_xml['type'];
		$varname = (string) $table_xml['varname'];
		if (is_null($varname)) trigger_error("Table tag needs a 'varname' attribute.", E_USER_ERROR);
		$html_id = (string) $table_xml['html_id'];
		if (is_null($html_id)) $html_id = $varname;
		switch ($type){
			case null:
			case "":
			case "yui":
				$this->vars[$varname] = $this->vars[$source]->get_yui_table($html_id, $args);
				break;
			default:
				trigger_error("Unknown datatable type: '{$type}'", E_USER_ERROR);
				break;
		}
		return true;
	}

	public function process_description(SimpleXMLElement $e){
		$this->vars['description'] = $this->substitute($e->asXML());
		return true;
	}

	public function process_template(SimpleXMLElement $e){
		$file = $this->substitute((string) $e['file']);
		if (is_null($file)) trigger_error("template tag needs a 'file' attribute.", E_USER_ERROR);
		env::$smarty->assign($this->vars);
		env::$smarty->assign("input", $this->input);
		env::$smarty->assign("query_time", $this->query_time);
		foreach ($e->param as $param_xml){
			$name = (string) $param_xml['name'];
			if (is_null($name)) trigger_error("Param tag needs a 'name' attribute.", E_USER_ERROR);
			env::$smarty->assign($name, $this->get_param_value($param_xml));
		}
		env::$smarty->display($file);
	}

	public function process_element(SimpleXMLElement $e){
		$name = $e->getName();
		if (is_array($this->allowed_elements) && !in_array($name, $this->allowed_elements)) continue;
		$time = microtime(1);
		switch ($name){
			case "breadcrumb":
				$ret |= $this->process_breadcrumb($e);
				break;
			case "rightmenu":
				$ret |= $this->process_rightmenu($e);
				break;
			case "title":
				$ret |= $this->process_title($e);
				break;
			case "var":
				$ret |= $this->process_var($e);
				break;
			case "query":
				$ret |= $this->process_query($e);
				break;
			case "if":
				$ret |= $this->process_if($e);
				break;
			case "case":
				$ret |= $this->process_case($e);
				break;
			case "chart":
				$ret |= $this->process_chart($e);
				break;
			case "datatable":
				$ret |= $this->process_datatable($e);
				break;
			case "description":
				$ret |= $this->process_description($e);
				break;
			/*case "template":
				$ret |= $this->process_template($e);
				break;*/
		}
		$time = microtime(1)-$time;
#		echo $name," time:",$time,"<br/>";
		return $ret;
	}

	public function process_children($sxml=null){
		if (is_null($sxml)) $sxml = $this->sxml;
		$ret = false;
		foreach ($sxml->children() as $e){
			$name = $e->getName();
			if (is_array($this->allowed_elements) && !in_array($name, $this->allowed_elements)) continue;
			$ret |= $this->process_element($e);
		}
		return $ret;
	}

	public function process_templates($for=null, $sxml=null){
		if (is_null($sxml)) $sxml = $this->sxml;
		$ret = false;
		$filter = $for ?"@for='{$for}'" :"@default='true'";
		foreach ($sxml->xpath("template[{$filter}]") as $e){
			$ret |= $this->process_template($e);
		}
		return $ret;
	}

}

?>
