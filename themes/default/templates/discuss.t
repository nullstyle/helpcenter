{include file="header.t"}


<div id="container">
	<div id="content">

	{include file="topic-box.t"}
    <!-- <ul id="topic-filter"> <li><a href="" class="on">Latest</a></li> {if $topic_style=='question'} <li><a href="">Frequently Asked</a></li> {elseif $topic_style=='problem'} <li><a href="">Common</a></li> {else} <li><a href="">Popular</a></li> {/if} <li><a href="?style=unanswered{$filter_product_arg}{$filter_tag_arg}">Unanswered</a></li> </ul> -->
		{if $filter_style}<img src="{$sprinkles_root_url}/images/{$filter_style}_med.png" id="topic-style" alt="{$topic.topic_style}" />{/if}
		<div id="topic-list-head">
  		<h2>Recent {$friendly_style}s
    		{if $filter_product}
    		  about {$filter_product.name}
    		{elseif $filter_tag}
    		  tagged {$filter_tag}
    		{/if}
    		<span>({$topic_count})</span>
  		</h2>		
  		{if $top_topic_tags && !$filter_product}
  		<p class="topic-tag-list">View {$friendly_style}s about:
  		  {foreach from=$top_topic_tags key=i item=tag}
  		  <a href="discuss.php?style={$filter_style}&amp;tag={$tag}">{$tag}</a>{if $i != count($top_topic_tags)-1},{/if}
  		  {/foreach}
  		</p>
  		{elseif $filter_style && $filter_product}
  		  <p class="topic-tag-list"><a href="discuss.php?style={$filter_style}">Return to all {$friendly_style}s</a> | <a href="discuss.php?product={$filter_product.uri}">See all topics about this product</a></p>
  		{/if}
    </div>
    
    {if $filter_product}
      <div id="topic-product" style="clear:left; margin-left: 45px;">
      <img style="vertical-align: top; float: left;" src="{$filter_product.image}" id="topic-style" alt="{$filter_product.name} photo" />
      <!-- {$filter_product.description} -->
      Tagged:
      {foreach from=$filter_product.tags key=i item=tag}
        <a href="{discuss_tag_url tag=$tag}">{$tag}</a>{if $i != count($filter_product.tags)-1},{/if}
      {/foreach}
      </div>
  	{/if}

		<div class="topic-list{if !$filter_style} mixed{/if}">
		{foreach from=$topics key=i item=topic}
      {include file="$topic_list_template"}
    {/foreach}
		</div>
		
    {if $num_pages > 1}
    <div class="pager">
      {section name=page loop=$num_pages}
        <a href="discuss.php?style={$filter_style}&amp;page={$smarty.section.page.iteration-1}{$filter_tag_arg}{$filter_product_arg}"{if $smarty.section.page.iteration == $page_num+1} class="on"{/if}>{$smarty.section.page.iteration}</a>
      {/section}
    </div>
    {/if}
	</div><!-- #content -->
</div><!-- #container -->

{include file="sidebar.t"}

{include file="footer.t"}
