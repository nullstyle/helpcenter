<html>
<head>
<link rel="stylesheet" type="text/css" href="sprinkles.css">
</head>
<body>

<div id="content">
<div id="backdrop">

{include file="header.t"}

<h1>Discuss with us</h1>

{include file="question-box.t"}

<h2>Discuss our Products</h2>

<ul class="product-list">
{foreach from=$products key=i item=product}
<li> <img src="{$product.image}" /> {$product.name} </li>
{/foreach}
</ul>

<h2>Recent Discussions</h2>
<h3>All Topics ({$topic_count})</h3>

<div class="sidepane">
<div class="sidebar">
View discussions by:
xxx
</div>
<div class="sidebar">
Related Tpoics from across Satisfaction:
xxx
</div>
</div>

<table class="discuss-topic-list">
{foreach from=$topics key=i item=topic}
    <tr><td><img src="" /></td>
    <td> <h3><a href="topic.php?id={$topic.ID}">{$topic.TITLE}</a></h3>
    Last reply xxx ago.

   <p>{$topic.CONTENT}</p>
   </td>
   <td> <span class="huge">{$topic.REPLY_COUNT}</a></span> <br />replies </td>
   </tr>
{/foreach}
</table>

</div>

{include file="footer.t"}

</div>
</body>
</html>
