<?php

class yui_datatable {

	public static function get_table_html(&$result, $id, $args=null){
		$rows_per_page = env::sget_conf('datatable_pagesize');
		$rows_per_page = $rows_per_page ?$rows_per_page :5;
		$reversed = env::sget_conf('datatable_reversed');
		$reversed = $reversed ?$reversed :false;
		if (!is_array($args)) $args = array();
		$link_target = isset($args['link_target']) ?$args['link_target'] :null;
		$link_column = isset($args['link_column']) ?$args['link_column'] :null;
		$link_params = isset($args['link_params']) ?(array)$args['link_params'] :null;
		$formatters = isset($args['formatters']) ?(array)$args['formatters'] :null;
		$width = isset($args['width']) ?$args['width'] :env::sget_conf('default_datatable_width');
		$hidden_cols = isset($args['hidden_cols']) ?(array)$args['hidden_cols'] :array();
		$nopaginator = isset($args['nopaginator']) ?$args['nopaginator'] :false;
		$rows_per_page = isset($args['rows_per_page']) ?$args['rows_per_page'] :$rows_per_page;
		$nosort = isset($args['nosort']) ?$args['nosort'] :false;
		$editors = isset($args['editors']) && is_array($args['editors']) ?$args['editors'] :null;
		$allow_insdel = isset($args['allow_insdel']) ?$args['allow_insdel'] :false;
		$template = isset($args['template']) ?$args['template'] :"yui_datatable.tpl";
		$only_inside = isset($args['only_inside']) ?$args['only_inside'] :false;
		$message = isset($args['message']) ?$args['message'] :"";
		$autorun_js = isset($args['autorun_js']) ?$args['autorun_js'] :null;
		$col_heads = isset($args['col_heads']) ?(array)$args['col_heads'] :array();
		$editable = isset($args['editable']) ?$args['editable'] :false;
		$reversed = isset($args['reversed']) ?$args['reversed'] :$reversed;
		$format_col_heads = isset($args['format_col_heads']) ?$args['format_col_heads'] :null;
		$sort_col = isset($args['sort_col']) ?$args['sort_col'] :null;
		$sort_dir = isset($args['sort_dir']) ?$args['sort_dir'] :null;
		$min_width = isset($args['min_width']) ?$args['min_width'] :env::sget_conf('datatable_min_width_px');
		$format_row = isset($args['format_row']) ?$args['format_row'] :null;
		
		$tpl = new pgwatch_smarty();
		$tpl->assign("id", $id);
		$coldefs = array();
		$fields = array();

		$hidden_cols[] = "__total_rows__";
		$hidden_cols = array_unique($hidden_cols);

		if (is_resource($result)){
			pg_result_seek($result, 0);
			$row1 = array();
			$row = pg_fetch_row($result);
			for ($i = 0; $i < pg_num_fields($result); $i++){
				$field = pg_field_name($result, $i);
				$row1[$field] = isset($row[$field]) ?$row[$field] :null;
			}
			pg_result_seek($result, 0);
		}elseif (is_array($result)){
			$row1 = count($result)>0 ?$result[0] :array();
		}else{
			trigger_error(E_USER_ERROR, "Unknown data type");
		}

		$numcols = count($row1);
		
		foreach ($row1 as $field => $val){
			if ($link_target && $link_column == $field){
				$target = $link_target;
				if (is_array($link_params)){
					foreach ($link_params as $link_key => $fld){
					$_fld = $fld;
						if (!is_numeric($link_key)) $_fld = $link_key;
						$_fld = urlencode($_fld);
						$target .= "&amp;{$_fld}='+escape(oRecord.getData('{$fld}'))+'";
					}
				}
				list($formatter_,$params) = explode(":",isset($formatters[$field]) ?$formatters[$field] :"",2)+array(null,null);
				switch ($formatter_){
				case "yui_byte_formatter":
					$formatter = ", formatter: function(elLiner, oRecord, oColumn, oData)\{".
						"elLiner.innerHTML = '<a href=\"#{$target}\" class=\"history\">'+byte_formatter(oData)+'</a>';\}";
					break;
				case "edit_dropdown":
					$opts = explode(",",$params);
					$formatter = ", formatter: 'dropdown', dropdownOptions:['".join("','",$opts)."']";
					break;
				case "edit_number":
					if ($params){
						$formatter = ", formatter: function(el, oRecord, oColumn, oData) \{".
							"el.innerHTML = '<input type=\'text\' value=\''+oData+'\' {$params} ".
							"onblur=\'var r=dt_{$id}.getRecord(this);".
								"if(!isNaN(this.value))r.setData(\"{$field}\",this.value);".
								"else this.value=r.getData(\"{$field}\");\'".
							"/>'\}";
					}else{
						$formatter = ", formatter: 'textbox'";
					}
					break;
				case "edit_text":
					if ($params){
						$formatter = ", formatter: function(el, oRecord, oColumn, oData) \{".
							"el.innerHTML = '<input type=\'text\' value=\''+oData+'\' {$params} ".
							"onblur=\'var r=dt_{$id}.getRecord(this);".
								"if(!isNaN(this.value))r.setData(\"{$field}\",this.value);".
								"else this.value=r.getData(\"{$field}\");\'".
							"/>'\}";
					}else{
						$formatter = ", formatter: 'textbox'";
					}
					break;
				case "number":
					$formatter = ", formatter:yui_number_cellformatter, sortOptions:{sortFunction:function(a,b,desc){var av=a.getData('{$field}'),bv=b.getData('{$field}');if(isNaN(av)||isNaN(bv))return YAHOO.util.Sort.compare(av,bv,desc);if(1*av==1*bv)return 0;return 1*av<1*bv?-1:1;}}";
					break;
				case "byte":
					$formatter = ", formatter:yui_byte_cellformatter, sortOptions:{sortFunction:function(a,b,desc){var sgn=desc?-1:1;av=a.getData('{$field}'),bv=b.getData('{$field}');if(isNaN(av)||isNaN(bv))return YAHOO.util.Sort.compare(av,bv,desc);if(1*av==1*bv)return 0;return sgn*(1*av<1*bv?-1:1);}}";
					break;
				case "percent":
					$formatter = ", formatter:yui_percent_cellformatter, sortOptions:{sortFunction:function(a,b,desc){var sgn=desc?-1:1;av=a.getData('{$field}'),bv=b.getData('{$field}');if(isNaN(av)||isNaN(bv))return YAHOO.util.Sort.compare(av,bv,desc);if(1*av==1*bv)return 0;return sgn*(1*av<1*bv?-1:1);}}";
					break;
				case "pre":
					$formatter = ", formatter:yui_pre_cellformatter";
					break;
				case "second":
					$formatter = ", formatter:yui_second_cellformatter";
					break;
				case "usec":
					$formatter = ", formatter:yui_usec_cellformatter";
					break;
				case "custom":
					if ($params){
						$formatter = ", formatter: function(el, oRecord, oColumn, oData) \{".$params."\}";
					}else{
						$formatter = ", formatter: 'textbox'";
					}
					break;
				default:
					$formatter = ", formatter: function(elLiner, oRecord, oColumn, oData){".
						"elLiner.innerHTML = '<a href=\"#{$target}\" class=\"history\">'+oData+'</a>';}";
					break;
				}
				$editor = "";
			}else{ // if current column is not to be a link
				list($formatter_,$params) = explode(":",isset($formatters[$field]) ?$formatters[$field] :"",2)+array(null,null);
				switch ($formatter_){
				case "yui_byte_formatter":
					$formatter = ", formatter: function(elLiner, oRecord, oColumn, oData)\{".
						"elLiner.innerHTML = byte_formatter(oData);\}";
					break;
				case "edit_dropdown":
					$opts = explode(",",$params);
					$formatter = ", formatter: 'dropdown', dropdownOptions:['".join("','",$opts)."']";
					break;
				case "edit_number":
					if ($params){
						$formatter = ", formatter: function(el, oRecord, oColumn, oData) \{".
							"el.innerHTML = '<input type=\'text\' value=\''+oData+'\' {$params} ".
							"onblur=\'var r=dt_{$id}.getRecord(this);".
								"if(!isNaN(this.value))r.setData(\"{$field}\",this.value);".
								"else this.value=r.getData(\"{$field}\");\'".
							"/>'\}";
					}else{
						$formatter = ", formatter: 'textbox'";
					}
					break;
				case "edit_text":
					if ($params){
						$formatter = ", formatter: function(el, oRecord, oColumn, oData) \{".
							"el.innerHTML = '<input type=\'text\' value=\''+oData+'\' {$params} ".
							"onblur=\'var r=dt_{$id}.getRecord(this);".
								"if(!isNaN(this.value))r.setData(\"{$field}\",this.value);".
								"else this.value=r.getData(\"{$field}\");\'".
							"/>'\}";
					}else{
						$formatter = ", formatter: 'textbox'";
					}
					break;
				case "number":
					$formatter = ", formatter:yui_number_cellformatter, sortOptions:{sortFunction:function(a,b,desc){var sgn=desc?-1:1;av=a.getData('{$field}'),bv=b.getData('{$field}');if(isNaN(av)||isNaN(bv))return YAHOO.util.Sort.compare(av,bv,desc);if(1*av==1*bv)return 0;return sgn*(1*av<1*bv?-1:1);}}";
					break;
				case "byte":
					$formatter = ", formatter:yui_byte_cellformatter, sortOptions:{sortFunction:function(a,b,desc){var sgn=desc?-1:1;av=a.getData('{$field}'),bv=b.getData('{$field}');if(isNaN(av)||isNaN(bv))return YAHOO.util.Sort.compare(av,bv,desc);if(1*av==1*bv)return 0;return sgn*(1*av<1*bv?-1:1);}}";
					break;
				case "percent":
					$formatter = ", formatter:yui_percent_cellformatter, sortOptions:{sortFunction:function(a,b,desc){var sgn=desc?-1:1;av=a.getData('{$field}'),bv=b.getData('{$field}');if(isNaN(av)||isNaN(bv))return YAHOO.util.Sort.compare(av,bv,desc);if(1*av==1*bv)return 0;return sgn*(1*av<1*bv?-1:1);}}";
					break;
				case "pre":
					$formatter = ", formatter:yui_pre_cellformatter";
					break;
				case "second":
					$formatter = ", formatter:yui_second_cellformatter";
					break;
				case "usec":
					$formatter = ", formatter:yui_usec_cellformatter";
					break;
				case "custom":
					if ($params){
						$formatter = ", formatter: function(el, oRecord, oColumn, oData) \{".$params."\}";
					}else{
						$formatter = ", formatter: 'textbox'";
					}
					break;
				default:
					$formatter = "";
					break;
				}
				if (is_array($editors) && isset($editors[$field])){
					list($type, $param) = explode(":",$editors[$field],2);
					switch ($type){
					case "text":
						if ($params){
							$p = array();
							foreach (explode(",",$params) as $pi){
								list($pk,$pv) = explode("=",$pi);
								$p[$pk] = $pv;
							}
						}
						$editor = ", editor: new YAHOO.widget.TextboxCellEditor({defaultValue:'', disableBtns:true})";
						break;
					case "textarea":
						$editor = ", editor: new YAHOO.widget.TextareaCellEditor({defaultValue:'', disableBtns:true})";
						break;
					case "date":
						$editor = ", editor: new YAHOO.widget.DateCellEditor({defaultValue:'', disableBtns:true})";
						break;
					case "number":
						$editor = ", editor: new YAHOO.widget.TextboxCellEditor({defaultValue:'', disableBtns:true, validator:YAHOO.widget.DataTable.validateNumber})";
						break;
					case "dropdown":
						$opts = "'" . str_replace(",", "','", $param) . "'";
						$editor = ", editor: new YAHOO.widget.DropdownCellEditor({defaultValue:'text', disableBtns:true, dropdownOptions:[{$opts}]})";
						break;
					default:
						$editor = "";
						break;
					}
				}else{
					$editor = "";
				}
			}
			if (is_resource($result)){
				switch (pg_field_type($result, pg_field_num($result, $field))){
				case "integer": case "bigint":
				case "int4": case "int8":
				case "float": case "double":
					$fields[] = '{key:"'.$field.'",parser:YAHOO.util.DataSource.parseNumber}';
					break;
				case "date": case "datetime":
				case "timestamp": case "timestamptz":
					$fields[] = '{key:"'.$field.'",parser:YAHOO.util.DataSource.parseDate}';
					break;
				default:
					$fields[] = '{key:"'.$field.'",parser:YAHOO.util.DataSource.parseString}';
					break;
				}
			}else{
				if (is_numeric($val)){
					$fields[] = '{key:"'.$field.'",parser:YAHOO.util.DataSource.parseNumber}';
				}else{
					$fields[] = '{key:"'.$field.'",parser:YAHOO.util.DataSource.parseString}';
				}
			}
			$label = $field;
			if (isset($format_col_heads)){
				foreach (explode("|", $format_col_heads) as $fch){
					$parts = explode(":", $fch);
					switch ($parts[0]){
						case "ucfirst": $label = ucfirst($label); break;
						case "ucwords": $label = ucwords($label); break;
						case "replace": $label = str_replace($parts[1], $parts[2], $label); break;
					}
				}
			}
			$label = isset($col_heads[$field]) ?$col_heads[$field] :$label;
			if (is_array($hidden_cols) && array_search($field, $hidden_cols) !== false){
				$hidden = ", hidden:true ";
				$sortable = "";
				$resizeable = "";
			}else{
				$hidden = "";
				$sortable = $nosort ?"" :", sortable:true ";
				$resizeable = ", resizeable:true ";
			}
			$colwidth = $min_width ?(", minWidth:".round($min_width/$numcols)) :"";
			$coldefs[] = '{key:"'.$field.'", label:"'.$label.'"' . $sortable . $resizeable . $hidden  . $formatter . $editor . $colwidth . '}';
		}
		if (is_resource($result)) $data = env::$db->fetch_all_assoc(null, null, $result);
		else $data = &$result;
		if ($reversed){
			$dlen = count($data);
			for ($i=0; $i<$dlen/2; $i++){
				$x = $data[$i];
				$data[$i] = $data[$dlen-$i-1];
				$data[$dlen-$i-1] = $x;
			}
		}
		$tpl->assign("fields", "[".join(",",$fields)."]");
		$tpl->assign("coldefs", "[".join(",",$coldefs)."]");
		$tpl->assign("link_target", $link_target);
		$tpl->assign("link_column", $link_column);
		$tpl->assign("nopaginator", $nopaginator);
		$tpl->assign("rows_per_page", $rows_per_page);
		$tpl->assign("nosort", $nosort);
		$tpl->assign("width", $width);
		$tpl->assign("editors", $editors);
		$tpl->assign("allow_insdel", $allow_insdel);
		$tpl->assign("only_inside", $only_inside);
		$tpl->assign("message", $message);
		$tpl->assign("autorun_js", $autorun_js);
		$tpl->assign("editable", $editable);
		$tpl->assign("sort_col", $sort_col);
		$tpl->assign("sort_dir", $sort_dir);
		$tpl->assign("format_row", $format_row);
		$tpl->assign_by_ref("data", $data);
		return $tpl->fetch($template);
	}

}

?>
