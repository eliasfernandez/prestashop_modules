{extends file="helpers/list/list_header.tpl"}


{block name=override_header}
	<ul class="breadcrumb cat_bar2">
		<li>
			{assign var=params_url value="configure=trevenque_attachment&id_attachment_category=0&viewattachment_category"}
			<i class="icon-home"></i>
			<a href="{$current|escape:'html':'UTF-8'}&amp;{$params_url|escape:'html':'UTF-8'}&amp;token={$token|escape:'html':'UTF-8'}">Inicio</a>
			
		</li>
		{assign var=i value=0}
		{foreach $att_category_parents key=key item=category}
		<li>
			{assign var=params_url value="configure=trevenque_attachment&id_attachment_category={$category->id}&viewattachment_category"}
			<a href="{$current|escape:'html':'UTF-8'}&amp;{$params_url|escape:'html':'UTF-8'}&amp;token={$token|escape:'html':'UTF-8'}">{$category->name|escape:'html':'UTF-8'}</a>
			
		</li>
		{/foreach}
	</ul>
{/block}

