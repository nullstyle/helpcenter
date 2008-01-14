{include file="header.t"}

<h1>Discuss with us</h1>

{include file="question-box.t"}

<h2>Your Recent {$company_name} Discussions</h2>

<table width="100%">
<tr>
<td>
<table class="topic-list" >
{foreach from=$topics key=i item=topic}
    <tr><td><img src="images/{$topic.topic_style}_med.png"
                 alt="{$topic.topic_style}"
                 style="width: 16pt;" /></td>
    <td style="width:200pt;"> <h3><a href="topic.php?id={$topic.id}">{$topic.title}</a></h3>
    {$topic.content}
    </td>
    <td style="width:50pt;"> last update {$topic.updated_relative} </td>
    </tr>
{/foreach}
</table>

</td>
<td>

<div class="sidepane">
<div class="sidebar" style="background-color: white">
<a>Subscribe to your feed with RSS on getsatisfaction.com</a> (xxx).
</div>

<div class="sidebar">
Recent Topics from your Satisfaction Dashboard:
xxx
</div>
</div>

</td>
</tr>
</table>

{include file="footer.t"}
