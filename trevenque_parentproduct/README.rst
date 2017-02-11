*******************************
Configuración básica del modulo
*******************************

El modulo solo necesita ser instalado. Crea:

* Una nueva tabla
* Una nueva pestaña en productos

No necesita override, simplemente se ejecuta en el producto mediante el hook displayProductContent.

Para el html se necesitan dos modificaciones:

En header.tpl

.. code-block:: html 
	{if $product->id_productparent}
			<link rel="canonical" href="{$link->getProductLink($product->id_productparent, null, null, null, $id_lang, null, 0, false)}" />
	{else}
			<link rel="canonical" href="{$link->getProductLink($smarty.get.id_product, null, null, null, $id_lang, null, 0, false)}" />
	{/if}


En product.tpl

.. code-block:: html
	{if $product->siblings}
	        <select id="canonical_navigation" class="form-control">
	                {foreach from=$product->siblings item=sibling }
	                        <option {if $sibling.id_product == $product->id} selected="selected" {/if} value ="{$link->getProductLink($sibling.id_product, null, null, null, $cookie->id_lang, null, 0, false)}">{$sibling.name}</option>
	                {/foreach}
	        </select>
	        <script type="text/javascript">
	                {literal}
	                $("#canonical_navigation").change(function(){
	                        window.location = $(this).val();
	                        $(this).attr("disabled","disabled");
	                });
	                {/literal}
	        </script>
	{/if}

