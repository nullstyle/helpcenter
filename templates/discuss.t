{* Smarty *}

{include file="header.t"}

<h1>Discuss with us</h1>

{include file="question-box.t"}

<h2>Discuss our Products</h2>

<ul class="product-list">
{foreach from=$products key=i item=product}
<li> <img src="{$product.image}" />{$product.name} </li>
{/foreach}
</ul>

<h2>Recent Discussions</h2>
<h3>All Topics ({$topic_count})</h3>

<div class="sidepane">
<div class="sidebar">
<h4>View discussions by:</h4>
<ul class="straight">
<li>All Topics xxx</li>
<li>Questions xxx</li>
<li>Ideas xxx</li>
<li>Problems xxx</li>
<li>Talk Topics xxx</li>
<li>Unanswered xxx</li>
</ul>
</div>
{include file="related-topics.t"}
<div class="sidebar blue">Go to your Satisfaction Dashboard</div>
</div>

<table class="discuss-topic-list">
{foreach from=$topics key=i item=topic}
    <tr><td><img src="images/{$topic.TOPIC_STYLE}_med.png" alt="{$topic.TOPIC_STYLE}" /></td>
    <td> <h3><a href="topic.php?id={$topic.ID}">{$topic.TITLE}</a></h3>
    Last reply xxx ago.

   <p>{$topic.CONTENT}</p>
   </td>
   <td> <span class="huge">{$topic.REPLY_COUNT}</a></span> <br />replies </td>
   </tr>
{/foreach}
</table>

{include file="footer.t"}
