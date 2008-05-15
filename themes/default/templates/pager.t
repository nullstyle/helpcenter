{if $num_pages > 1}
<div class="pager">
  {if $page_num != 1}
    <a href="discuss.php?style={$filter_style}&amp;page={$page_num-1}{$filter_tag_arg}{$filter_product_arg}">&laquo; Prev</a>
  {/if}
  {section name=page loop=$num_pages}
    {if $smarty.section.page.iteration < ($page_num+10) && $smarty.section.page.iteration > $page_num-10}
    <a href="discuss.php?style={$filter_style}&amp;page={$smarty.section.page.iteration}{$filter_tag_arg}{$filter_product_arg}"{if $smarty.section.page.iteration == $page_num} class="on"{/if}>{$smarty.section.page.iteration}</a>
    {/if}
  {/section}
  {if $page_num != $num_pages}<a href="discuss.php?style={$filter_style}&amp;page={$page_num+1}{$filter_tag_arg}{$filter_product_arg}">Next &raquo;</a>{/if}
</div>
{/if}