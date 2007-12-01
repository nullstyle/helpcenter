{* Smarty *}

{include file="header.t"}

{if $filter_product}
<h2>Discuss the {$filter_product.name}</h2>
<a href="discuss.php">Return to all Discussions about all products &amp; services</a>
<table>
<tr>
<td><img src="{$filter_product.image}" /> {$filter_product.description}</td>
<td>Product Tags xxx</td>
</tr>
</table>
{else}
<h1>Discuss with us</h1>
{/if}

{include file="question-box.t"}

{if !$filter_product}
<h2>Discuss our Products</h2>

<ul class="product-list">
{foreach from=$products key=i item=product}
<li><a href="discuss.php?product={$product.uri}{if $filter_style}&style={$filter_style}{/if}"> <img src="{$product.image}" />{$product.name}</a> </li>
{/foreach}
</ul>
{/if}

<h4 style="display:inline;">Top Topic Tags</h4>
{foreach from=$top_topic_tags key=i item=tag}
<a href="discuss.php?tag={$tag}">{$tag}</a>
{/foreach}
xxx

<h2>Recent Discussions</h2>
<h3>All Topics ({$topic_count})</h3>

<div class="sidepane">
<div class="sidebar">
<h4>View discussions by:</h4>
<ul class="straight">
<li><a href="discuss.php">All Topics xxx</a></li>
<li><a href="?style=question{$filter_product_arg}{$filter_tag_arg}">Questions xxx</a></li>
<li><a href="?style=idea{$filter_product_arg}{$filter_tag_arg}">Ideas xxx</a></li>
<li><a href="?style=problem{$filter_product_arg}{$filter_tag_arg}">Problems xxx</a></li>
<li><a href="?style=talk{$filter_product_arg}{$filter_tag_arg}">Talk Topics xxx</a></li>
<li><a href="?style=unanswered{$filter_product_arg}{$filter_tag_arg}">Unanswered xxx</a></li>
</ul>
</div>
{include file="related-topics.t"}
<div class="sidebar blue">Go to your Satisfaction Dashboard</div>
</div>

<table class="discuss-topic-list">
{foreach from=$topics key=i item=topic}
    <tr><td><img src="images/{$topic.TOPIC_STYLE}_med.png" alt="{$topic.TOPIC_STYLE}" /></td>
    <td> <h3><a href="topic.php?id={$topic.ID}">{$topic.TITLE}</a></h3>
{if $topic.REPLY_COUNT}
    Last reply
{else}
    Posted
{/if}
{$topic.UPDATED_RELATIVE}.

   <p>{$topic.CONTENT}</p>
   </td>
   <td> <span class="huge">{$topic.REPLY_COUNT}</a></span> <br />replies </td>
   </tr>
{/foreach}
</table>

{include file="footer.t"}
