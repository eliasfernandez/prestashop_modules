{*
* 2007-2015 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2015 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

{extends file="helpers/list/list_content.tpl"}

{block name="open_td"}
	<td
		{if isset($params.type) && $params.type == 'tposition'}
			id="td_{if !empty($position_group_identifier)}{$position_group_identifier|escape:'html':'UTF-8'}{else}0{/if}_{$tr.$identifier|escape:'html':'UTF-8'}{if $smarty.capture.tr_count > 1}_{($smarty.capture.tr_count - 1)|intval}{/if}"
		{/if}
		class="{strip}{if !$no_link}pointer{/if}
		{if isset($params.type) && $params.type == 'tposition'} dragHandle{/if}
		{if isset($params.class)} {$params.class|escape:'html':'UTF-8'}{/if}
		{if isset($params.align)} {$params.align|escape:'html':'UTF-8'}{/if}{/strip}"
		{if (!isset($params.position) && !$no_link && !isset($params.remove_onclick))}
			onclick="document.location = '{$current_index|escape:'html':'UTF-8'}&amp;{$identifier|escape:'html':'UTF-8'}={$tr.$identifier|escape:'html':'UTF-8'}{if $view}&amp;view{else}&amp;update{/if}{$table|escape:'html':'UTF-8'}&amp;token={$token|escape:'html':'UTF-8'}'">
		{else}
	>
		{/if}
{/block}

{block name="td_content"}
	{if isset($params.type) && $params.type == 'tposition'}
		<div class="dragGroup">
			<div class="positions">
				{$tr.$key.position|intval}
			</div>
		</div>
	{elseif isset($params.type) && $params.type == 'tid_menu'}
		#{$tr.id_tmenu|escape:'htmlall':'UTF-8'}
	{elseif isset($params.type) && $params.type == 'tid_dropdown'}
		#{$tr.id_tdropdown|escape:'htmlall':'UTF-8'}

	{elseif isset($params.type) && $params.type == 'tmenu'}
		<span data-toggle="tooltip" class="tmenu label-tooltip" data-original-title="{$tr.link|escape:'htmlall':'UTF-8'}" data-html="true" data-placement="top">
			<span>{$tr.name|escape:'htmlall':'UTF-8'} </span>
			{if $tr.label != ''}<sup style="background-color: {$tr.label_color|escape:'htmlall':'UTF-8'}">{$tr.label|escape:'htmlall':'UTF-8'}</sup>{/if}
		</span>
		
	{elseif isset($params.type) && $params.type == 'tdropdown'}
		<span class="tdropdown">{if $tr.drop_column == 0}{l s='No Dropdown' mod='trevenque_menu'}{else}{$tr.drop_column|escape:'htmlall':'UTF-8'} {if $tr.drop_column == 1}{l s='column' mod='trevenque_menu'}{else}{l s='columns' mod='trevenque_menu'}{/if}{/if}</span>	
		<div class="btn-group">
			<a href="{$current_index}&amp;listtrevenquedropdown&amp;id_tmenu={$tr.id_tmenu|escape:'htmlall':'UTF-8'}&amp;token={$token}" class="btn" title="{l s='View Contents' mod='trevenque_menu'}"><i class="icon-search-plus"></i> {l s='View Contents' mod='trevenque_menu'}</a>
		</div>
	
	{elseif isset($params.type) && $params.type == 'tdropdowncolumn'}
		<span>{$tr.column|escape:'htmlall':'UTF-8'} {if $tr.column == 1}{l s='column' mod='trevenque_menu'}{else}{l s='columns' mod='trevenque_menu'}{/if}</span>
		
	{elseif isset($params.type) && $params.type == 'tdropdowntype'}
		{if $tr.content_type == 'category'}
			<span data-toggle="tooltip" class="label-tooltip" data-original-title="{if $tr.categories}<div class='text-left'>{foreach from=$tr.categories item=category} #{$category.id_category|escape:'htmlall':'UTF-8'} - {$category.name|escape:'htmlall':'UTF-8'}<br/>{/foreach}</div>{/if}" data-html="true" data-placement="top">
			{l s='Categories' mod='trevenque_menu'}
			</span>			
		{elseif $tr.content_type == 'product'}
			<span data-toggle="tooltip" class="label-tooltip" data-original-title="{if $tr.products}<div class='text-left'>{foreach from=$tr.products item=product} #{$product.id_product|escape:'htmlall':'UTF-8'} - {$product.name|escape:'htmlall':'UTF-8'}(ref: {$product.reference|escape:'htmlall':'UTF-8'})<br/>{/foreach}</div>{/if}" data-html="true" data-placement="top">
			{l s='Products' mod='trevenque_menu'}
			</span>
		{elseif $tr.content_type == 'html'}
			<span data-toggle="tooltip" class="label-tooltip" data-original-title="{if $tr.static_content}<div class='text-left'>{$tr.static_content|escape:'htmlall':'UTF-8'}</div>{/if}" data-html="true" data-placement="top">
			{l s='Custom HTML' mod='trevenque_menu'}
			</span>
		{elseif $tr.content_type == 'manufacturer'}
			<span data-toggle="tooltip" class="label-tooltip" data-original-title="{if $tr.manufacturers|@count gt 0}<div class='text-left'>{foreach from=$tr.manufacturers item=manufacturer} #{$manufacturer.id_manufacturer|escape:'htmlall':'UTF-8'} - {$manufacturer.name|escape:'htmlall':'UTF-8'}<br/>{/foreach}</div>{/if}" data-html="true" data-placement="top">
			{l s='Manufacturers' mod='trevenque_menu'}
			</span>
		{else}
			{l s='No Content' mod='trevenque_menu'}
		{/if}		
	{else}
		{$smarty.block.parent}
	{/if}
{/block}
