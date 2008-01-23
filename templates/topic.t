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

<table style="width: 100%; table-layout: fixed;">
<tr>
<td class="topic-pic-column">
<div><img src="{$lead_item.author.photo}" class="topic-author-pic" /></div>
<div class="topic-author-caption" style="margin-top: 3pt;">
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

<td style="margin: 1pc; width: auto;">
<h3><strong>{ $lead_item.title }</strong></h3>

 <p>{ $lead_item.content }</p>

  <a href="dead-end.php" class="flag_button float-right" style="bottom: 0;">
    Flag this topic
  </a>
  {if $topic.emotitag_face || $topic.emotitag_emotion}
  <p> {if $topic.emotitag_face}
        <img src="images/{$topic.emotitag_face}.png"
             style="vertical-align:middle;"
             alt="{$topic.emotitag_emotion}">{/if}
      {$topic.emotitag_emotion}
  </p>
  {/if}

</td>

<td id="topic-summary">

{if $user_name}
<a href="share_topic.php?id={$topic_id}">Share</a> or follow this topic
<input style="width:120pt;" value="I have this question too!" />
{/if}

<h3>In this topic</h3>
<p>
<strong>{$particip.people}</strong>
   {if $particip.people != 1}people{else}person{/if}<br />
<strong>{$particip.employees}</strong>
   employee{if $particip.employees != 1}s{/if}<br />
<strong>{$reply_count}</strong>
   {if $reply_count != 1}replies{else}reply{/if}<br />
</p>
{if $particip.count_official_reps}
<p>
<strong>{$particip.count_official_reps}</strong>
{if count($particip.official_reps) != 1} 
official reps {else}
official rep {/if}
<br />
{foreach from=$particip.official_reps key=i item=rep}
<img src="{$rep.photo}" class="small-author-pic" />
{/foreach}
</p>
{/if}
</td>
</tr>
{if $best_solution}
<tr>
<td colspan="2">
Best solution from the company
</td>
</tr>
{/if}
</table>

</div>

<div class="sidepane">
  <img style="padding: 6pt; margin: 6pt;" src="poweredbysmallStack.png"
       alt="Powered by: Satisfaction" />

  {include file="related-topics.t"}
</div>

<div style="padding: 0pt 8pt;">

{if !$user_name}
  <h2><a href="user-login.php?return=topic.php%3fid={$lead_item.id}">
    Login to reply</a>
  </h2>
{/if}

  <table class="topic-replies" style="width: 342pt;">
  {foreach from=$replies key=i item=reply}
  <tr class="{if $reply.in_reply_to == $lead_item.id}toplevel{else}subordinate{/if}">
    <td class="topic-pic-column">
      <div style="position:relative;">
      <img src="{$reply.author.photo}" class="reply-author-pic" />
      </div>
    </td><td class="reply-core">
      {$reply.author.name}
      {if $reply.author.role}({$reply.author.role_name}){/if}
      replied {$reply.updated_relative}:
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
  {if $user_name}
  <tr>
  <td class="topic-pic-column"><img src="{$current_user.photo}" /></td>
  <td>
  <form action="handle-reply.php" method="POST">
  I say:
  <input type="hidden" name="topic_id" value="{$topic_id}" />
  <textarea name="content" cols="40" rows="5" style="display: block;"></textarea>
  <button type="submit">Post reply</button>
  </form>
  </td>
  </tr>
  {/if}
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
