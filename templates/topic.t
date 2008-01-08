{* Smarty *}

{include file="header.t"}

<div class="topic-head">
Hey { $company_name }!
<h3>
<strong> { $lead_item.author.name }</strong> has
{if $lead_item.topic_style == 'question'}
a question{elseif $lead_item.topic_style == 'idea'}
an idea{elseif $lead_item.topic_style == 'talk'}
a question{elseif $lead_item.topic_style == 'problem'}
a problem{/if}
</h3>

<table width="100%">
<tr>
<td style="width:48pt;">
<div><img src="{$lead_item.author.photo}" class="topic-author-pic" /></div>
<div class="topic-author-caption">
<span class="topic-byline">
{ $lead_item.author.name }
</span> 
{if $lead_item.topic_style == 'question'} asked this question
{elseif $lead_item.topic_style == 'idea'} shared this idea
{elseif $lead_item.topic_style == 'talk'} asked this question
{elseif $lead_item.topic_style == 'problem'} reported this problem
{/if}
{$lead_item.published_relative}
</div>
</td>

<td style="top; margin: 1pc;">
<h3><strong>{ $lead_item.title }</strong></h3>

 <p>{ $lead_item.content }</p>

 <p><img src="images/{$topic.emotitag_face}.png" style="vertical-align:middle;"
         alt="{$topic.emotitag_emotion}"> {$topic.emotitag_emotion} </p>

</td>

<td style="width: 120pt;">

{if $username}
<a href="share_topic?id={$topic_id}">Share</a> or follow this topic
<input style="width:120pt;" value="I have this question too!" />
{/if}

<div>
In this topic<br />
{$particip.people} people<br />
{$particip.employees} employees<br />
{$reply_count} replies<br />
</div>
{if $particip.official_reps}
<p>
{$particip.count_official_reps} official rep{if count($particip.official_reps) > 1}s{else}{/if} is here
<ul class="straight">
{foreach from=$particip.official_reps key=i item=rep}
<li>
<img src="{$rep.photo}" class="small-author-pic" />
{$rep.fn}
</li>
{/foreach}
</ul>
</p>
{/if}
</td>
</tr>
</table>

</div>

<div class="sidepane">
  <img style="padding: 6pt; margin: 6pt;" src="poweredbysmallStack.png" alt="Powered by: Satisfaction" />

  {include file="related-topics.t"}
</div>

<div>
  <h2><a href="user-login.php">Login to reply</a></h2>

  <table class="topic-replies">
  {foreach from=$replies key=i item=reply}
  <tr class="{if $reply.in_reply_to == $lead_item.id}toplevel{else}subordinate{/if}">
    <td class="reply-author-column">
      <div style="position:relative; width:34pt;">
      <img src="{$reply.author.photo}" class="reply-author-pic" />
      </div>
    </td><td class="reply-core" width="100%">
      {$reply.author.name} {if $reply.author.role}({$reply.author.role}){/if} replied {$reply.updated_relative}:
        <p>{$reply.content}</p>
        <div class="float-right">
          {if $reply.in_reply_to == $lead_item.id} {* A top-level reply. *}
          <form action="star-it.php" style="display: inline; vertical-align: middle;">
            <input type="hidden" name="topic_id" value="{$lead_item.id}"></input>
            <button href="dead-end.php">
            {if $lead_item.topic_style == 'question'}
            This answered the question
            {elseif $lead_item.topic_style == 'idea'}
            Good point!{elseif $lead_item.topic_style == 'talk'}
            This answered the question{elseif $lead_item.topic_style =='problem'}
            This solved the problem!
            {/if}
            {if $reply.star_count}({$reply.star_count}){/if}
            </button>
          </form>
          {/if}
          <a href="dead-end.php" class="flag_button">
          Flag
          </a>
        </div>
       {if $reply.emotitag_face}
        <p><img src="images/{$reply.emotitag_face}.png" 
                alt="{$reply.emotitag_emotion}"
                class="emotitag_face"> 
            {if $reply.enotitag_emotion}I'm {$reply.emotitag_emotion}{/if}
        </p>
        {/if}
    </td>
  </tr>
  {/foreach}
  </table>

{if $num_pages > 1}
{if ($page_num > 0)}
<a href="topic.php?id={$topic_id}&page={$page_num-1}">&lt;</a>
{else}
&nbsp;
{/if}
Page {$page_num+1} of {$num_pages}
{if ($page_num+1 < $num_pages)}
<a href="topic.php?id={$topic_id}&page={$page_num+1}">&gt;</a>
{else}
&nbsp;
{/if}
{/if}

</div>

{include file="footer.t"}
