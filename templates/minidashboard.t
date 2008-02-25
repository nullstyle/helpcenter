{include file="header.t"}

<h1>{$user_possessive|capitalize} Recent {$company_name} Discussions</h1>

{include file="question-box.t"}

<h2>{$user_possessive|capitalize} Recent {$company_name} Discussions</h2>

<table width="100%">
<tr>
<td>
<table class="topic-list" style="width: 100%;">
{foreach from=$company_topics key=i item=topic}
  <tr>
    <td><img src="images/{$topic.topic_style}_med.png"
                 alt="{$topic.topic_style}"
                 style="width: 16pt;" /></td>
    <td class="content-col"> <h3><a href="topic.php?id={$topic.id}">{$topic.title}</a></h3>
    {$topic.content}
    </td>
    <td style="width: 75pt;"> last update <br /> {$topic.updated_relative} </td>
  </tr>
{foreachelse}
  <tr><td></td>
    <td class="content-col">
      You haven't participated in any {$company_name} discussions so far.
    </td><td></td>
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
<h3>Recent Topics from your Satisfaction Dashboard:</h3>
<ul>
{foreach from=$noncompany_topics key=i item=topic}
<li>{$topic.title} in <strong>{$topic.company.fn}</strong></li>
{/foreach}
</ul>
</div>
</div>

</td>
</tr>
</table>

{include file="footer.t"}
