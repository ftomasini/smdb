<div class="breadcrumb"><ul>
{{foreach from=$breadcrumb item="item" key="i"}}
	<li>
	{{if $i > 0}}&gt;&nbsp;{{/if}}
	{{if $item.1}}<a href="{{$item.1|escape:'html'}}" class="history">{{/if}}
	{{if !in_array($item.0,array("menu","root"))}}{{$item.0|ucfirst}}: {{/if}}
	{{$item.2|escape:"html"}}
	{{if $item.1}}</a>{{/if}}
	</li>
{{/foreach}}
</ul></div>
