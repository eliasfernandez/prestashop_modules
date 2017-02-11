

<ul class="list-inline">
    <li>
        <a href="{$link->getModuleLink("trevenque_attachment", 'attachment')}">{l s="Downloads"}</a>
    </li>
    {if $attachment_breadcrumb}

    {foreach from=$attachment_breadcrumb item=att_cat }
        {if $att_cat->id != $attachment_category->id}
            <li><a href="{$link->getModuleLink("trevenque_attachment", 'attachment', ['id_attachment_category'=>$att_cat->id ])}">{$att_cat->name}</a></li>       
        {/if}
    {/foreach}
    {/if}
</ul>


{if $attachment_category}
    <h1> {$attachment_category->name}</h1>
{else}
    <h1>{l s="Downloads"}</h1>
{/if}

{if $attachment_categories}
<ul class="list-inline">

{foreach from=$attachment_categories item=att_cat }
    
<li><a href="{$link->getModuleLink("trevenque_attachment", 'attachment', ['id_attachment_category'=>$att_cat['id_attachment_category']])}">{$att_cat['name']}</a></li>

{/foreach}
</ul>
{/if}


{if $attachments}

<div class="row">

{foreach from=$attachments item=att }
    
  <div class="col-sm-6 col-md-4">
    <div class="thumbnail">
        {if $att->image}
            <img src="{$att->image}" alt="{$att->file_name}">
        {/if}
      <div class="caption">
        <h3>{$att->name}</h3>
        <p>{$att->description}</p>
        <p><a href="{$link->getModuleLink("trevenque_attachment", 'attachment', ['id_attachment'=>$att->id])}" class="btn btn-primary" role="button">Leer</a> <a href="{$link->getModuleLink("trevenque_attachment", 'attachment', ['id_attachment'=>$att->id])}" class="btn btn-default" role="button">Descargar</a></p>
      </div>
    </div>
  </div>



{/foreach}
</div>

{/if}


