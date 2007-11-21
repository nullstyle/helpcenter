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

<h2>Your Recent {$company_name} Discussions</h2>

<div class="sidepane">
<div class="sidebar">
View discussions by:
xxx
</div>
<div class="sidebar">
Related Tpoics from your Satisfaction Dashboard:
xxx
</div>
</div>

<table class="discuss-topic-list">
{foreach from=$topics key=i item=topic}
    <tr><td><img src="" /></td>
    <td> <h3><a href="topic.php?id={$topic.ID}">{$topic.TITLE}</a></h3>
    xxx happened
    </td>
    </tr>
{/foreach}
</table>

</div>

{include file="footer.t"}

</div>
</body>
</html>
