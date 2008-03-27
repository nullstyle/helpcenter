{include file="header.t"}

<h1>{$user_possessive|capitalize} (Mini) Dashboard
{if $user_is_self}
<a href="http://getsatisfaction.com/me">See your full dashboard on Get Satisfaction</a>
{else}
<a href="http://getsatisfaction.com/people/{$username_canonical}">See their full dashboard on Get Satisfaction</a>
{/if}
</h1>

{include file="question-box.t"}

<h2>{$user_possessive|capitalize} Recent {$company_name} Discussions</h2>

<div class="sidepane">
<div class="sidebar" style="background-color: white">
<a>Subscribe to 
{if $user_is_self}
your
{else}
their
{/if}
feed with RSS on getsatisfaction.com</a> (xxx).
</div>

<div class="sidebar">
<h3>Recent Topics from {$user_possessive} Get Satisfaction Dashboard:</h3>
<ul>
{foreach from=$noncompany_topics key=i item=topic}
<li>{$topic.title} in <strong>{$topic.company.fn}</strong></li>
{foreachelse}
{if $user_is_self} You haven't
{else}             {$user.fn} hasn't
{/if}
participated in any other discussions so far.
{/foreach}

</ul>
</div>
</div>

<table class="topic-list">
{foreach from=$company_topics key=i item=topic}
  <tr>
    <td><img src="images/{$topic.topic_style}_med.png"
                 alt="{$topic.topic_style}" /></td>
    <td class="content-col">
    <h3><a href="topic.php?id={$topic.id}">{$topic.title}</a></h3>
    <p>{if $topic.reply_count} Last reply
                 {else} Posted {/if} {$topic.updated_relative}.</p>

    <p>{$topic.content}</p>

    <table class="p-margin">
    <tr>
    <td>
    <img class="tiny-author-pic" style="vertical-align: middle;"
            src="{$topic.author.photo}" />
    </td>
    <td>
   <a href="minidashboard.php?user_url={$topic.author.url}">
   {$topic.author.name} 
   </a>
    {if $topic.topic_style == 'question'} asked this question
    {elseif $topic.topic_style == 'idea'} shared this idea
    {elseif $topic.topic_style == 'talk'} asked this question
    {elseif $topic.topic_style == 'problem'} reported this problem
    {/if}
    {$topic.published_relative}.
{if $topic.tags}
    It's tagged {foreach from=$topic.tags key=i item=tag}{if ($i>0)},{/if}
    <a href="discuss.php?tag={$tag}">{$tag}</a>{/foreach}
{/if}
    </td>
    </tr>
    </table>
    </td>
    <td class="reply-count-col">
      <span class="huge">{$topic.reply_count}</a></span> <br />
        {if $topic.reply_count == 1} reply {else} replies {/if} </td>
  </tr>
{foreachelse}
  <tr><td></td>
    <td class="content-col">
      {if $user_is_self} You haven't
      {else}             {$user.fn} hasn't
      {/if}
      participated in any {$company_name} discussions so far.
    </td><td></td>
  </tr>
{/foreach}
</table>

{include file="footer.t"}
