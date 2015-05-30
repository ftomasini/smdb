<div class="dashboard-item" {{if $i % 2 == 0}}style="clear:both"{{/if}}>
	{{capture assign="breadcrumb"}}
	<ul>
	{{foreach from=$breadcrumb item="item" key="i"}}
		<li>
		{{if $item.1}}<a href="{{$item.1|escape:'html'}}" class="history {{if $item.0=="root"}}bold{{/if}}">{{/if}}
		{{if $item.0=="menu"}}
		{{elseif $item.0=="root"}}{{assign var="bctitle" value=$item.2}}
		{{elseif $item.0=="instance"}}{{$item.2|escape:"html"}}
		{{else}}&gt;&nbsp;{{$item.0|ucfirst}}:&nbsp;{{$item.2|escape:"html"}}
			{{/if}}
		{{if $item.1}}</a>{{/if}}
		</li>
	{{/foreach}}
	<li></li>
	</ul>
	{{/capture}}
	
	<div class="breadcrumb">
	<h3><a class="history" href="index.php#{{$url_self_params}}">{{$bctitle|escape:"html"}}</a> ({{math equation="round(x*1000,3)" x=$query_time}}ms)</h3>
	<div style="display:block;clear:both;">{{$breadcrumb}}</div>
	</div>
	<button class="x" onclick="del_from_dashboard('{{$list.dashboard_item_id}}')" title="Remove">X</button>
	<div class="clear">{{$content}}</div>
</div>
