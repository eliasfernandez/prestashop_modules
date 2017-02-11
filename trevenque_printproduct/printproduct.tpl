
<div id="trevenque_canonicalurl" class="panel product-tab">
    <input type="hidden" name="submitted_tabs[]" value="trevenque_canonicalurl" />
    <h3>{l s='Producto para inscripción'}</h3>
    <div class="form-group">
        <label class="control-label col-lg-3" for="id_printproduct">
            <span class="label-tooltip" data-toggle="tooltip"
            title="{l s='Producto que será indicado como inscripción de este artículo'}">
            {l s='Producto para inscripción'}
            </span>
        </label>
        <div class="col-lg-5">
      	  
           <select name="id_printproduct" class="chosen-select-deselect fixed-width-xl" id="id_printproduct">
                <option value="0"> &lt;Sin producto asociado&gt; </option>
                {foreach from=$products item=product}
                        <option value="{$product.id_product}" {if $id_printproduct == $product.id_product}selected="selected"{/if} >{$product.name}</option>
                {/foreach}
           </select>
        </div>
    </div>
    <script type="text/javascript">
    {literal} 
        $("#id_printproduct").chosen({"width":"400px", "allow_single_deselect": true});
    {/literal} 
    </script>
<div class="panel-footer">
        <a href="{$link->getAdminLink('AdminProducts')|escape:'html':'UTF-8'}{if isset($smarty.request.page) && $smarty.request.page > 1}&amp;submitFilterproduct={$smarty.request.page|intval}{/if}" class="btn btn-default"><i class="process-icon-cancel"></i> {l s='Cancel'}</a>
        <button type="submit" name="submitAddproduct" class="btn btn-default pull-right" disabled="disabled"><i class="process-icon-loading"></i> {l s='Save'}</button>
        <button type="submit" name="submitAddproductAndStay" class="btn btn-default pull-right" disabled="disabled"><i class="process-icon-loading"></i> {l s='Save and stay'}</button>
    </div>
</div>
