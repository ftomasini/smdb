var byte_units = {
	TB: 1024 * 1024 * 1024 * 1024,
	GB: 1024 * 1024 * 1024,
	MB: 1024 * 1024,
	KB: 1024
}
var second_units = {
	y:  60 * 60 * 24 * 365,
	mth: 60 * 60 * 24 * 31,
	w:  60 * 60 * 24 * 7,
	d:   60 * 60 * 24,
	h:  60 * 60,
	m:   60
}
var usec_units = {
	y:   1000 * 1000 * 60 * 60 * 24 * 365,
	mth: 1000 * 1000 * 60 * 60 * 24 * 31,
	w:   1000 * 1000 * 60 * 60 * 24 * 7,
	d:   1000 * 1000 * 60 * 60 * 24,
	h:   1000 * 1000 * 60 * 60,
	m:   1000 * 1000 * 60,
	s:   1000 * 1000,
	ms:  1000
}
var number_units = {
	M: 1000 * 1000,
	K: 1000
	
}

function byte_formatter(x){
	var i, amount;
	if (x === null) return "";
	for (i in byte_units){
		amount = byte_units[i];
		if (x === null){
			// x = 0;
		}else if (x > amount){
			return "" + Math.round(x*10 / amount)/10 + i;
		}
	}
	return x + "B";
}

function _number_formatter(x){
	var a, b, y;
	if (x === null) return "";
	if ((""+x).match(/\./)){
		var arr = (""+x).split(/\./);
		a = arr[0].replace(/^0*(\d)$/, "$1");
		b = arr[1].replace(/^(\d)0*$/, "$1");
		if (b == "0") b = null;
	}else{
		a = (""+x).replace(/^0*(\d)$/, "$1");
		b = null;
	}
	var i = 0;
	while (a.match(/\d\d\d\d/) && i++ < 10){
		a = a.replace(/(\d)(\d\d\d)([^0-9]|$)/, "$1 $2$3");
	}
	i = 0;
	if (b !== null){
		while (b.match(/\d\d\d\d/) && i++ < 10){
			b = b.replace(/([^0-9]|^)(\d\d\d)(\d)/, "$1$2 $3");
		}
		y = a + "." + b;
	}else{
		y = a;
	}
	return y;
}
function number_formatter(x){
	var i, amount;
	if (x === null) return "";
	for (i in number_units){
		amount = number_units[i];
		if (x === null){
			// x = 0;
		}else if (x > amount){
			return "" + _number_formatter(Math.round(x*10 / amount)/10) + i;
		}
	}
	return _number_formatter(x);
}

function percent_formatter(x){
	if (x === null) return "";
	return _number_formatter(x)+"%";
}

function pre_formatter(x){
	return "<pre>" + x + "</pre>";
}

function second_formatter(x){
	var i, amount;
	if (x === null) return "";
	for (i in second_units){
		amount = second_units[i];
		if (x === null){
			// x = 0;
		}else if (x > amount){
			return "" + Math.round(x*10 / amount)/10 + i;
		}
	}
	return x + "s";
}
function usec_formatter(x){
	var i, amount;
	if (x === null) return "";
	for (i in usec_units){
		amount = usec_units[i];
		if (x === null){
			// x = 0;
		}else if (x > amount){
			return "" + Math.round(x*10 / amount)/10 + i;
		}
	}
	return x + "&micro;s";
}

function yui_number_cellformatter(el, oRecord, oColumn, oData){
	el.innerHTML = number_formatter(oData);
	el.style.textAlign = "right";
}

function yui_byte_cellformatter(el, oRecord, oColumn, oData){
	el.innerHTML = byte_formatter(oData);
	el.title = _number_formatter(oData)+" bytes";
	el.style.textAlign = "right";
}

function yui_percent_cellformatter(el, oRecord, oColumn, oData){
	el.innerHTML = percent_formatter(oData);
	el.style.textAlign = "right";
}

function yui_pre_cellformatter(el, oRecord, oColumn, oData){
	el.innerHTML = pre_formatter(oData);
}

function yui_second_cellformatter(el, oRecord, oColumn, oData){
	el.innerHTML = second_formatter(oData);
	el.style.textAlign = "right";
}

function yui_usec_cellformatter(el, oRecord, oColumn, oData){
	el.innerHTML = usec_formatter(oData);
	el.style.textAlign = "right";
}

legst=[];
function chart_legend_toggle(obj,id){
	var l1=document.getElementById(id).getElementsByTagName("span");
	var o1, l2, cid;
	for (var i=0; i<l1.length; i++){
		o1 = l1[i];
		l2 = l1[i].getElementsByClassName("chart-legend-label");
		for (var j=0; j<l2.length; j++){
			cid = id + "-" + i;
			if (legst[cid] == undefined){
				legst[cid] = 0;
			}
			l2[j].style.display = legst[cid] ? "inline" : "none";
			legst[cid] = 1 - legst[cid];
		}
	}
	obj.className = obj.className == "shrink" ?"expand" :"shrink";
}

colst=[];
colwi=[];
function yui_datatable_cols_toggle(dt,button){
	var cs=dt.getColumnSet();
	var id=cs.getId(),cid;
	for (var i=1; i<cs.flat.length; i++){
		cid = id + "-" + i;
		if (colst[cid] == undefined){
			colst[cid] = 0;
			colwi[cid] = $(cs.getColumn(i).getThLinerEl()).width();
		}
		dt.setColumnWidth(cs.getColumn(i), colst[cid] ?colwi[cid] :30);
		colst[cid] = 1-colst[cid];
	}
	button.className = button.className == "shrink" ?"expand" :"shrink";
}

function add_to_dashboard(target){
	$.ajax({
		url: "index.php?page=dashboard&op=add&target="+escape(target),
		complete: function(xhr, status){
			if (status == "success"){
				alert(xhr.responseText);
			}else{
				alert(xhr.statusText);
			}
		}
	});
}

function del_from_dashboard(id,obj){
	if (!confirm("Really remove item from dashboard?")) return false;
	$.ajax({
		url: "index.php?page=dashboard&op=del&id="+id,
		complete: function(xhr, status){
			if (status == "success"){
				alert(xhr.responseText);
				pageload("page=dashboard");
			}else{
				alert(xhr.statusText);
			}
		}
	});
	return true;
}