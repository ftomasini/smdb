{{include file="rightmenuhead.tpl" text="Database activity"}}
{{capture assign="params"}}{{$page->inherit_params("page","node_id","db_id","date_from","date_until")}}{{/capture}}
{{include id="rm1" file="rightmenuitem.tpl" href="#set=dbactivity&amp;$params" text="Active queries"}}
{{include id="rm2" file="rightmenuitem.tpl" href="#set=checkpoints&amp;$params" text="Checkpoints done"}}
{{include id="rm3" file="rightmenuitem.tpl" href="#set=xactdiff&amp;$params" text="Commits and rollbacks"}}
{{include id="rm4" file="rightmenuitem.tpl" href="#set=xactdiffc&amp;$params" text="Commits"}}
{{include id="rm5" file="rightmenuitem.tpl" href="#set=xactdiffr&amp;$params" text="Rollbacks"}}


