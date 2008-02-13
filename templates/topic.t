{* Smarty *}

{include file="header.t"}

<div class="topic-head {$topic_head.topic_style}">
Hey { $company_name }!
<h3>
<strong> { $topic_head.author.name }</strong> has
{if $topic_head.topic_style == 'question'}
a question{elseif $topic_head.topic_style == 'idea'}
an idea{elseif $topic_head.topic_style == 'talk'}
a question{elseif $topic_head.topic_style == 'problem'}
a problem{/if}
</h3>

<table style="width: 100%; table-layout: fixed;">
<tr>
<td class="topic-pic-column">
<div><img src="{$topic_head.author.photo}" class="topic-author-pic" /></div>
<div class="topic-author-caption" style="margin-top: 3pt;">
  <span class="topic-byline">
  { $topic_head.author.name }
  </span> 
  {if $topic_head.topic_style == 'question'} asked this question
  {elseif $topic_head.topic_style == 'idea'} shared this idea
  {elseif $topic_head.topic_style == 'talk'} asked this question
  {elseif $topic_head.topic_style == 'problem'} reported this problem
  {/if}
  {$topic_head.published_relative}
</div>
</td>

<td style="margin: 1pc; width: auto; height: 100%;">
<h3><strong>{ $topic_head.title }</strong></h3>

 <p>{ $topic_head.content }</p>

  <a href="handle-flag.php?type=topic&id={$topic_head.sfn_id|urlencode}&topic_id={$topic_head.id|urlencode}" class="flag_button float-right">
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
{if $company_promoted_replies}
<tr>
<td colspan="3">
<h2>Best solution from the company</h2>
{foreach from=$company_promoted_replies key=i item=reply}
<div class="box">
  <p>{$reply.content}</p>

  <div class="light p">
  <img src="{$reply.author.photo}" class="small-author-pic" style="vertical-align:middle;" />
  <strong><em>{$reply.author.name}</em>
  {if $reply.author.role}({$reply.author.role_name}){/if}</strong>
  {$reply.updated_relative}
  {if $reply.emotitag_face}
  <img src="images/{$reply.emotitag_face}.png" 
                alt="{$reply.emotitag_emotion}"
                class="emotitag_face">
            {if $reply.emotitag_emotion}I'm {$reply.emotitag_emotion}{/if}
  {/if}
  </div>
</div>
{/foreach}
</td>
</tr>
{/if}
{if $star_promoted_replies}
<tr>
<td colspan="3">
<h2>Best solution from people</h2>
{foreach from=$star_promoted_replies key=i item=reply}
<div class="box">
  <p>{$reply.content}</p>

  <div class="light p">
  <img src="{$reply.author.photo}" class="small-author-pic" style="vertical-align:middle;" />
  <strong><em>{$reply.author.name}</em>
  {if $reply.author.role}({$reply.author.role_name}){/if}</strong>
  {$reply.updated_relative}
  {if $reply.emotitag_face}
  <img src="images/{$reply.emotitag_face}.png" 
                alt="{$reply.emotitag_emotion}"
                class="emotitag_face">
            {if $reply.emotitag_emotion}I'm {$reply.emotitag_emotion}{/if}
  {/if}
  </div>
</div>
{/foreach}
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
  <h2><a href="user-login.php?return=topic.php%3fid={$topic_head.id}">
    Login to reply</a>
  </h2>
{/if}

<table class="topic-replies">
  {foreach from=$replies key=i item=reply}
  <tr class="{if $reply.in_reply_to == $topic_head.id}toplevel{else}subordinate{/if}">
    <td class="topic-pic-column">
      <div style="position:relative;">
      <img src="{$reply.author.photo}" class="reply-author-pic" />
      </div>
    </td><td>
    <div class="reply-core">
      <span class="light"><strong><em>{$reply.author.name}</em>
        {if $reply.author.role}({$reply.author.role_name}){/if}
        </strong>
        replied {$reply.updated_relative}:</span>
      
        <p>{$reply.content}</p>

        <div class="float-right">
          {if $reply.in_reply_to == $topic_head.id} {* A top-level reply. *}
          <form action="handle-star.php" style="display: inline; vertical-align: middle;">
            <input type="hidden" name="topic_id" value="{$topic_head.id}"></input>
            <input type="hidden" name="reply_id" value="{$reply.id}"></input>
            <button type="submit">
            {if $topic_head.topic_style == 'question'}
            This answered the question
            {elseif $topic_head.topic_style == 'idea'}
            Good point!
            {elseif $topic_head.topic_style == 'talk'}
            This answered the question
            {elseif $topic_head.topic_style =='problem'}
            This solved the problem!
            {/if}
            {if $reply.star_count}({$reply.star_count}){/if}
            </button>
          </form>
          {/if}
          <a href="handle-flag.php?type=reply&id={$reply.sfn_id|urlencode}&topic_id={$topic_head.id|urlencode}" class="flag_button">
          Flag
          </a>
        </div>
       {if $reply.emotitag_face}
       <div>
        <p><img src="images/{$reply.emotitag_face}.png" 
                alt="{$reply.emotitag_emotion}"
                class="emotitag_face">
            {if $reply.emotitag_emotion}I'm {$reply.emotitag_emotion}{/if}
        </p>
       </div>
       {/if}
       <div style="clear:both;"></div>
    </div>
    {if $reply.thread_end}
      {if !$user_name}
        <div style="clear:both; margin-top:4pt; font-weight:bold;">
        <a href="user-login.php?return=topic.php%3fid={$topic_head.id}">
         Login to comment
        </a></div>
      {else}
        <form action="handle-reply.php" method="POST">
        I say:
        <input type="hidden" name="topic_id" value="{$topic_head.id}" />
        <input type="hidden" name="parent_id" value="{$reply.sfn_id}" />
        <textarea name="content" cols="40" rows="5" style="display: block;"></textarea>
        <button type="submit">Post reply</button>
        </form>
      {/if}
    <hr />
    {/if}
    </td>
  </tr>
  {/foreach}
  {if $user_name}
  <tr>
  <td class="topic-pic-column"><img src="{$current_user.photo}" /></td>
  <td>
   <form action="handle-reply.php" method="POST">
   <input type="hidden" name="topic_id" value="{$topic_id}" />
   <input type="hidden" name="reply_url" value="{$reply_url}" />
   I say:
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
