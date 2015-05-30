<button id="bt_refresh" style="float:left">Refresh chart</button>
{{if $show_dashboard_button}}<button id="bt_dashboard" style="float:left">Add chart to dashboard</button>
<script language="javascript">
var bt_dashboard = new YAHOO.widget.Button("bt_dashboard", {onclick:{fn:function(){add_to_dashboard('{{$url_fixed_params}}')}}});
</script>
{{assign var="show_dashboard_button" value=0}}
{{/if}}
<script language="javascript">
function apply_dates(target){
	return $.historyLoad(target+"&date_from="+$("#date_from").val()+"&date_until="+$("#date_until").val());
}
var bt_refresh = new YAHOO.widget.Button("bt_refresh", {onclick:{fn:function(){apply_dates('{{$url_fixed_params}}')}}});
</script>
<div class="clear" id="date-input-container" style="width:650px;margin-bottom:4px">
<div id="date_interval"></div>
<div width="100%" class="dpickerd">
<label style="float:left">From&nbsp;<input type="text" id="date_from" class="dpicker"/></label>
<label style="float:left">&nbsp;&nbsp;&nbsp;Until&nbsp;<input type="text" id="date_until" class="dpicker"/></label> 
&nbsp;
<input type="button" id="bt_1d" value="1d" title="Last 1 day"/>
<input type="button" id="bt_2d" value="2d" title="Last 2 days"/>
<input type="button" id="bt_3d" value="3d" title="Last 3 days"/>
<input type="button" id="bt_1w" value="1w" title="Last week"/>
<input type="button" id="bt_2w" value="2w" title="Last 2 weeks"/>
<input type="button" id="bt_1m" value="1m" title="Last month"/>
<input type="button" id="bt_2m" value="2m" title="Last 2 months"/>
<input type="button" id="bt_3m" value="3m" title="Last 3 months"/>
<input type="button" id="bt_6m" value="6m" title="Last 6 months"/>
<input type="button" id="bt_1y" value="1y" title="Last year"/>
</div>
<div id="date_selector_room"></div>
<style type="text/css">
#date_interval .ui-slider-range {background:#806020;}
#date_interval .ui-slider-handle {border-color:#806020;}
#date_selector_room {height: 220px}
.dpicker {font-size:9pt; padding:1px; width:7em}
.ui-datepicker-div {z-index:100 !important}
.dpickerd input, .dpickerd button, .dpickerd span {padding-left:1px !important; padding-right:1px !important;}
</style>
<script language="javascript">
new YAHOO.widget.Button("bt_1d", {onclick:{fn:function(){set_dates(   0,0);set_slider(   0,0);bt_refresh.fireEvent("click");}}});
new YAHOO.widget.Button("bt_2d", {onclick:{fn:function(){set_dates(  -1,0);set_slider(  -1,0);bt_refresh.fireEvent("click");}}});
new YAHOO.widget.Button("bt_3d", {onclick:{fn:function(){set_dates(  -2,0);set_slider(  -2,0);bt_refresh.fireEvent("click");}}});
new YAHOO.widget.Button("bt_1w", {onclick:{fn:function(){set_dates(  -6,0);set_slider(  -6,0);bt_refresh.fireEvent("click");}}});
new YAHOO.widget.Button("bt_2w", {onclick:{fn:function(){set_dates( -13,0);set_slider( -13,0);bt_refresh.fireEvent("click");}}});
new YAHOO.widget.Button("bt_1m", {onclick:{fn:function(){set_dates( -30,0);set_slider( -30,0);bt_refresh.fireEvent("click");}}});
new YAHOO.widget.Button("bt_2m", {onclick:{fn:function(){set_dates( -60,0);set_slider( -60,0);bt_refresh.fireEvent("click");}}});
new YAHOO.widget.Button("bt_3m", {onclick:{fn:function(){set_dates( -91,0);set_slider( -91,0);bt_refresh.fireEvent("click");}}});
new YAHOO.widget.Button("bt_6m", {onclick:{fn:function(){set_dates(-182,0);set_slider(-182,0);bt_refresh.fireEvent("click");}}});
new YAHOO.widget.Button("bt_1y", {onclick:{fn:function(){set_dates(-365,0);set_slider(-365,0);bt_refresh.fireEvent("click");}}});
$("#date_selector_room").hide();
var farthest_day = {{$farthest_day|default:-650}};
var closest_day = {{$closest_day|default:0}};
function show_selector_room(bool){
	if (bool) $("#date_selector_room").show("blind", {direction:"vertical"}, 400);
	else      $("#date_selector_room").hide("blind", {direction:"vertical"}, 400);
}
function lin2exp(x){
	var abs_x = Math.abs(x);
	var sgn_x = x==0 ?0 :x/abs_x;
	x = Math.round(Math.pow(abs_x/15,2)) * sgn_x;
	return x;
}
function exp2lin(y){
	var abs_y = Math.abs(y)
	var sgn_y = y==0 ?0 :y/abs_y;
	y = Math.round(15*Math.pow(abs_y,1/2)) * sgn_y;
	return y;
}
function set_dates(a,b){
	$("#date_from").datepicker('setDate', (a>0?"+":(a<0?"-":""))+a+"d", "dateFormat", "yyyy-mm-dd", "autoSize", true);
	$("#date_until").datepicker('setDate', (b>0?"+":(b<0?"-":""))+b+"d", "dateFormat", "yyyy-mm-dd", "autoSize", true);
}
function set_datei(idx,val){
	return;
	var a=val.split("-");
	if (idx==0)$("#date_from").datepicker('setDate', val);
	if (idx==1)$("#date_until").datepicker('setDate', val);
}
function set_slider(a,b){
	$("#date_interval").slider('values',0,a);
	$("#date_interval").slider('values',1,b);
}
$("#date_interval").slider({range:true, min:farthest_day, max:closest_day, values:[exp2lin({{$diff_from}}),exp2lin({{$diff_until}})],
	slide: function(event, ui) {set_dates(lin2exp(ui.values[0]), lin2exp(ui.values[1]))}
});
$(".dpicker").datepicker({showOn: 'button', buttonImage: 'common/img/calendar.gif', buttonImageOnly: true,
	dateFormat:'yy-mm-dd', showAnim:'slideDown',
	beforeShow:function(){show_selector_room(true)},
	onClose:function(){show_selector_room(false)},
	onSelect:function(dateText,inst){set_datei(this.id=="date_from"?0:1, dateText)}
});
$(".dpicker").attr("readonly", "readonly");
set_dates({{$diff_from}},{{$diff_until}});
</script>
</div>
<div id="chartinfo">Calculated over <b>{{$dates->gap}}</b> long intervals.</div>
<div class="clearfix"></div>

