{* Smarty *}

{include file="header.t"}

{if $filter_product}
<h2>Discuss the {$filter_product.name}</h2>
<a href="discuss.php">Return to all Discussions about all products &amp; services</a>
<table width="100%">
<tr>
<td><img style="vertical-align: top;" src="{$filter_product.image}" /> {$filter_product.description}</td>
<td>Product Tags</td>
<td class="tag-box">
    {foreach from=$filter_product.tags item=tag}<a href="{discuss_tag_url tag=$tag}">{$tag}</a>{if !$smarty.foreach.tag.last},{/if} {/foreach}
</td>
</tr>
</table>
{else}
<h1>Discuss with us</h1>
{/if}

{include file="question-box.t"}

{if !$filter_product && !$filter_tag}
<h2>Discuss our Products</h2>

<ul class="product-list">
{foreach from=$products key=i item=product}
<li><a href="discuss.php?product={$product.uri}{if $filter_style}&style={$filter_style}{/if}"> <img src="{$product.image}" />{$product.name}</a> </li>
{/foreach}
</ul>
{/if}

{if !$filter_tag && !filter_product}
<h4 style="display:inline;">Top Topic Tags</h4>
  {foreach from=$top_topic_tags key=i item=tag}
  <a href="discuss.php?tag={$tag}">{$tag}</a>
  {/foreach}
xxx
{/if}

<h2>Recent Discussions</h2>
<h3>All topics
{if $filter_product}
about the product: {$filter_product.name}
{elseif $filter_tag}
about the tag: {$filter_tag}
{/if}
 ({$topic_count})</h3>

<div class="sidepane">
<div class="sidebar">
<h3>View discussions by:</h3>
<ul class="straight">
<li><a href="discuss.php">All Topics xxxx{$all_count}</a></li>
<li><a href="?style=question{$filter_product_arg}{$filter_tag_arg}">Questions {$question_count}</a></li>
<li><a href="?style=idea{$filter_product_arg}{$filter_tag_arg}">Ideas {$idea_count}</a></li>
<li><a href="?style=problem{$filter_product_arg}{$filter_tag_arg}">Problems {$problem_count}</a></li>
<li><a href="?style=talk{$filter_product_arg}{$filter_tag_arg}">Talk Topics {$talk_count}</a></li>
<li><a href="?style=unanswered{$filter_product_arg}{$filter_tag_arg}">Unanswered {$unanswered_count}</a></li>
</ul>
</div>
{include file="related-topics.t"}
<div class="sidebar blue"><a href="http://getsatisfaction.com/me">Go to your Satisfaction Dashboard</a></div>
</div>

<table class="topic-list">
{foreach from=$topics key=i item=topic}
  <tr>
    <td><img src="images/{$topic.topic_style}_med.png"
                 alt="{$topic.topic_style}" /></td>
    <td class="content-col">
    <h3><a href="topic.php?id={$topic.id}">{$topic.title}</a></h3>
{if $topic.reply_count} Last reply
                 {else} Posted {/if} {$topic.updated_relative}.

    <p>{$topic.content}</p>

    <p><img class="tiny-author-pic" style="vertical-align: middle;"
            src="{$topic.author.photo}" /> {$topic.author.name} 
    {if $topic.topic_style == 'question'} asked this question
    {elseif $topic.topic_style == 'idea'} shared this idea
    {elseif $topic.topic_style == 'talk'} asked this question
    {elseif $topic.topic_style == 'problem'} reported this problem
    {/if}
    {$topic.updated_relative}.
    It's tagged {foreach from=$topic.tags key=i item=tag}{if ($i>0)},{/if}
    <a href="discuss.php?tag={$tag}">{$tag}</a>{/foreach} </p>
    </td>
    <td class="reply-count-col">
      <span class="huge">{$topic.reply_count}</a></span> <br />
        {if $topic.reply_count == 1} reply {else} replies {/if} </td>
  </tr>
{/foreach}
</table>

{include file="footer.t"}
