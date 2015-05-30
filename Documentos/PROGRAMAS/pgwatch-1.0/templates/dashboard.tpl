<style>
.dashboard#left {width: 928px !important;}
.dashboard-item {width:450px; float:left; margin: 0px 0px 20px 10px;}
.dashboard-item button.x {
	border: 1px solid transparent;
	color: black;
	padding:0;
	margin:0 20px 0 0;
	font-family: "lucida console", "lucida typewriter", arial, helvetica;
	font-size: 8px;
	float:right;
}
.dashboard-item button.x:hover {
	border:1px dotted #cccccc;
}
.dashboard-item h3 {float:left}
</style>
<div id="left" class="dashboard">
<h1 class="page_headline">Dashboard</h1>
{{foreach from=$items item="item" key="i"}}
{{$dashboard->display_pageset($item,"dashboard")}}
{{foreachelse}}
<div class="clear">No items to display</div>
{{/foreach}}
</div>
{{*[total:{{$dashboard->get_time()}} pagesets:{{$dashboard->get_pagesets_time()}}]*}}