<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html dir="ltr" xmlns="http://www.w3.org/1999/xhtml" lang="en-US">
<head profile="http://gmpg.org/xfn/11">
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<title>pgwatch - Cybertec Enterprise PostgreSQL Monitor</title>
<link rel="stylesheet" href="common/css/style.css" type="text/css" media="screen"/>
<!--link rel="stylesheet" href="common/jq-tsort-themes/current/style.css" type="text/css" media="screen"/-->
<!--link rel="stylesheet" href="common/css/jquery.tablesorter.pager.css" type="text/css" media="screen"/-->
<script type="text/javascript" src="common/js/jquery-1.6.4.min.js"></script>
<script type="text/javascript" src="common/js/jquery.history.js"></script>
<script type="text/javascript" src="common/js/jquery.form.js"></script>
<script type="text/javascript" src="common/ui/js/jquery-ui-1.7.2.custom.min.js"></script>
<link rel="stylesheet" type="text/css" href="common/ui/css/pepper-grinder/jquery-ui-1.7.2.custom.css"/>
<!--script type="text/javascript" src="common/js/jquery.metadata.js"></script>
<script type="text/javascript" src="common/js/jquery.tablesorter.pager.js"></script>
<script type="text/javascript" src="common/js/jquery.dimensions.min.js"></script>
<script type="text/javascript" src="common/js/jquery.tablesorter.min.js"></script-->
<!--script type="text/javascript" src="common/js/fontResizer.js"></script-->
<script type="text/javascript" src="common/js/superfish.js"></script>
<script type="text/javascript" src="common/js/supersubs.js"></script>
<script type="text/javascript" src="common/js/cookies.js"></script>
<script type="text/javascript" src="common/js/theme.js"></script>
<script type="text/javascript" src="common/js/common.js"></script>
<script type="text/javascript" src="common/js/FusionCharts.js"></script>
<!-- Individual YUI CSS files -->
<link rel="stylesheet" type="text/css" href="common/yui/build/tabview/assets/skins/sam/tabview.css"/>
<link rel="stylesheet" type="text/css" href="common/yui/build/button/assets/skins/sam/button.css"/>
<link rel="stylesheet" type="text/css" href="common/yui/build/calendar/assets/skins/sam/calendar.css"/>
<link rel="stylesheet" type="text/css" href="common/yui/build/paginator/assets/skins/sam/paginator.css"/>
<link rel="stylesheet" type="text/css" href="common/yui/build/datatable/assets/skins/sam/datatable.css"/>
<link rel="stylesheet" type="text/css" href="common/yui/build/autocomplete/assets/skins/sam/autocomplete.css"/>
<!-- Individual YUI JS files -->
<script type="text/javascript" src="common/yui/build/yahoo/yahoo-min.js"></script>
<script type="text/javascript" src="common/yui/build/dom/dom-min.js"></script>
<script type="text/javascript" src="common/yui/build/event/event-min.js"></script>
<script type="text/javascript" src="common/yui/build/element/element-min.js"></script>
<script type="text/javascript" src="common/yui/build/button/button-min.js"></script>
<script type="text/javascript" src="common/yui/build/calendar/calendar-min.js"></script>
<script type="text/javascript" src="common/yui/build/datasource/datasource-min.js"></script>
<script type="text/javascript" src="common/yui/build/dragdrop/dragdrop-min.js"></script>
<script type="text/javascript" src="common/yui/build/paginator/paginator-min.js"></script>
<script type="text/javascript" src="common/yui/build/datatable/datatable-min.js"></script>
<script type="text/javascript" src="common/yui/build/json/json-min.js"></script>
<script type="text/javascript" src="common/yui/build/tabview/tabview-min.js"></script>
<script type="text/javascript" src="common/yui/build/autocomplete/autocomplete-min.js"></script>

<!--yui debugger -->
<!--link type="text/css" rel="stylesheet" href="common/yui/build/logger/assets/skins/sam/logger.css">
<script type="text/javascript" src="common/yui/build/yahoo-dom-event/yahoo-dom-event.js"></script>
<script type="text/javascript" src="common/yui/build/logger/logger-min.js"></script-->

<script type="text/javascript">
function pageload(hash) {
	load_indicator_on();
	if( hash.match(/page=./) || hash.match(/set=./) ) {
		//if ($.browser.msie) hash = encodeURIComponent(hash);
		$("#content").load("index.php?"+hash+"&rnd="+Math.random(),{},load_indicator_off);
	}else{
		$("#content").load("index.php?page=dashboard&rnd="+Math.random(),{},load_indicator_off);
	}
}

$(document).ready(function(){
	$.historyInit(pageload, "index.php");
	$("a.history").live("click", function(ev){
		var hash = this.href;
		hash = hash.replace(/^.*#/, '');
		$.historyLoad(hash);
		return false;
	});
	var hash = location.href;
	if (hash.indexOf("#") == -1){
		hash = "#page=dashboard";
		hash = hash.replace(/^.*#/, '');
		pageload(hash);
	}
});

function load_indicator_on(){
	var cp = $("#content").position();
	var ww = $(window).width();
	var aw = $("#ajax-div").width();
	$("#ajax-div").css("left", ww/2-aw/2);
	$("#ajax-div").fadeIn(600);
}
function load_indicator_off(){
	$("#ajax-div").fadeOut(600);
}

var pre = new Image(32,32);
pre.src="common/img/ajax-loader.gif";
</script>
<style type="text/css">
body {background: #ffffff;}
div#content {background: #ffffff;}
div#appendix {background: #ffffff;}
</style>
<!--[if IE]>
<style type="text/css">
div.date {float:left; position:static; margin:0 10px 0px 0; padding:0;}
#search-submit {margin: 10px 0 0 0; height: 28px;}
</style>
<![endif]-->
</head>
<body class="yui-skin-sam">
<div id="outline">
<div id="blog-line">
<a href="index.php"><img src="common/img/header_monitor.jpg" alt="Cybertec PostgreSQL Monitor" height="120"></a>
</div>
<div id="contentwmenu">
{{include file="mainmenu.tpl" selected="ovr"}}
<!-- ending header template -->
<div id="content" class="clearfix">
</div>
</div>
<!-- footer template -->
<div class="kerncopy"> <a linkindex="17" href="http://www.postgresql-support.de/">PostgreSQL support, training, consulting</a></div>
</div>
<!-- wp_footer -->
<div id="ajax-div" style="position:absolute; width:200px; height: 100px; top:140px; left:500px; margin:0px; padding:0px; font-weight:bold; font-size: 12pt; z-index:100; background-image:url(common/img/200x100.png)">
<table width="100%" height="100%" cellspacing="3" cellpadding="5" border="0">
<tr><td align="center"><img src="common/img/ajax-loader.gif" width="64" height="64" alt=""/></td>
<td align="center">Please wait</td>
</tr></table></div>
</body>
</html>
