{* Smarty *}

{include file="header.t"}

{if $blank_reply_error}
<div class="error-box" style="margin:8pt 6pt;">
Enter some text to reply and click the 'Comment' or 'Reply' button directly below that text.
</div>
{/if}

{if $blank_reply_error}
<div class="error-box" style="margin:8pt 6pt;">
We already know that you have this 
  {if $topic_head.topic_style == 'question'}
    question!
  {elseif $topic_head.topic_style == 'idea'}
    idea!
  {elseif $topic_head.topic_style == 'talk'}
    question!
  {elseif $topic_head.topic_style =='problem'}
    problem!
  {/if}
</div>
{/if}

{if $shared_with}
<div class="message-box" style="margin:8pt 6pt;">
You've shared this topic with
{foreach from=$shared_with key=i item=email}{if $i != 0}, {/if}
{$email}{/foreach}.
</div>
{/if}

{if $share_failed_msg}
<div class="error-box" style="margin:8pt 6pt;">
Oops! Something went wrong while trying to share this topic with
{foreach from=$shared_with key=i item=email}{if $i != 0}, {/if}
{$email}{/foreach}.
</div>
{/if}

{if $me_too_failed_error}
<div class="error-box" style="margin:8pt 6pt;">
For some reason, we could not record the fact that you had this 
question too. Perhaps you have already marked it that way, or
perhaps there was just a glitch in Get Satisfaction. Sorry!
</div>
{/if}

{if $me_tood_topic_msg}
<div class="message-box" style="margin:8pt 6pt;">
You have this
  {if $topic_head.topic_style == 'question'}
    question
  {elseif $topic_head.topic_style == 'idea'}
    idea
  {elseif $topic_head.topic_style == 'talk'}
    question
  {elseif $topic_head.topic_style =='problem'}
    problem
  {/if}
too&mdash;got it!
</div>
{/if}

{if $self_star_error}
<div class="error-box" style="margin:8pt 6pt;">
Note: That reply was either authored by you or it was already marked by you as
  {if $topic_head.topic_style == 'question'}
    "answering the question."
  {elseif $topic_head.topic_style == 'idea'}
    "a good point."
  {elseif $topic_head.topic_style == 'talk'}
    "answering the question."
  {elseif $topic_head.topic_style =='problem'}
    "solving the problem."
  {/if}
So we didn't take any action.
</div>
{/if}

<div class="topic-head {$topic_head.topic_style}">

<h3>
<strong> { $topic_head.author.name }</strong> has
  {if $topic_head.topic_style == 'question'}
    a question
  {elseif $topic_head.topic_style == 'idea'}
    an idea
  {elseif $topic_head.topic_style == 'talk'}
    a question
  {elseif $topic_head.topic_style == 'problem'}
    a problem
  {/if}
</h3>

<table style="width: 100%; table-layout: fixed;">
<tr>
<td class="topic-pic-column">
<div><img src="{$topic_head.author.photo}" class="topic-author-pic" /></div>
<div class="topic-author-caption" style="margin-top: 3pt;">
  <span class="topic-byline">
  <a href="minidashboard.php?user_url={$topic_head.author.url}">
  { $topic_head.author.name }
  </a>
  </span> 
  {if $topic_head.topic_style == 'question'} asked this question
  {elseif $topic_head.topic_style == 'idea'} shared this idea
  {elseif $topic_head.topic_style == 'talk'} asked this question
  {elseif $topic_head.topic_style == 'problem'} reported this problem
  {/if}
  {$topic_head.published_relative}
</div>
</td>

<td style="width: auto;">
<div id="topic-head-content">
<h3><strong>{ $topic_head.title }</strong></h3>

  <p>{ $topic_head.content }</p>

  {if $flagged_topic == $topic_head.sfn_id}
  <span class="disabled flag-button float-right">
    This is spam
  </span>
  {else}
  <a href="handle-flag.php?type=topic&id={$topic_head.sfn_id|urlencode}&topic_id={$topic_head.id|urlencode}"
     class="flag-button float-right">
    This is spam
  </a>
  {/if}

  {if $topic_head.emotitag_face || $topic_head.emotitag_emotion}
  <p> {if $topic_head.emotitag_face}
        <img src="images/{$topic_head.emotitag_face}.png"
             style="vertical-align:middle;"
             alt="{$topic_head.emotitag_emotion}">{/if}
      {if $topic_head.emotitag_emotion}I'm {$topic_head.emotitag_emotion}{/if}
  </p>
  {/if}

</div>
</td>

<td id="topic-summary">

<a href="share-topic.php?id={$topic_id}">Share</a> or follow this topic
{if $own_topic}
<div>
You asked this question
</div>
{elseif $current_user}
<form action="handle-me-too.php">
<button name="sfn_id" value="{$topic_head.sfn_id}">
  I have this 
  {if $topic_head.topic_style == 'question'}
    question
  {elseif $topic_head.topic_style == 'idea'}
    idea
  {elseif $topic_head.topic_style == 'talk'}
    question
  {elseif $topic_head.topic_style =='problem'}
    problem
  {/if}
  too!
</button>
</form>
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
  <p class="tight">{$reply.content}</p>

  <div class="light p">
  <img src="{$reply.author.photo}" class="small-author-pic" style="vertical-align:middle;" />
  <strong><em><a href="minidashboard.php?user_url={$reply.author.url}">{$reply.author.name}</a></em>
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
<h2>Best solution from the people</h2>
{foreach from=$star_promoted_replies key=i item=reply}
<div class="box">
  <p class="tight">{$reply.content}</p>

  <div class="light p">
  <img src="{$reply.author.photo}" class="small-author-pic" style="vertical-align:middle;" />
  <strong><em><a href="minidashboard.php?user_url={$reply.author.url}">{$reply.author.name}</a></em>
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
  <img style="padding: 6pt; margin: 6pt;" src="images/poweredbysmall.png"
       alt="Powered by: Get Satisfaction" />

  {include file="related-topics.t"}
</div>

<div style="padding: 0pt 8pt;">

{if !$user_name}
  <h2><a href="user-login.php?return=topic.php%3fid={$topic_head.id|urlencode}">
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
      <span class="light"><strong><em>
        <a href="minidashboard.php?user_url={$reply.author.url}">{$reply.author.name}</a>
        </em>
        {if $reply.author.role}({$reply.author.role_name}){/if}
        </strong>
        replied {$reply.updated_relative}:</span>
      
        <p class="tight">{$reply.content}</p>

        <div class="float-right">
          {if $reply.in_reply_to == $topic_head.id}  {* It's a top-level reply *}
          {if $reply.author.canonical_name != $current_user.canonical_name}
            {* current user is not the author. *}
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
          {else}
            {if $topic_head.topic_style == 'question'}
            This answered the question
            {elseif $topic_head.topic_style == 'idea'}
            Good point!
            {elseif $topic_head.topic_style == 'talk'}
            This answered the question
            {elseif $topic_head.topic_style =='problem'}
            This solved the problem!
            {/if}
            ({$reply.star_count})          
          {/if}
          {/if}
          {if $flagged_reply == $reply.sfn_id}
            <span class="disabled flag-button float-right">Spam</span>
          {else}
            <a href="handle-flag.php?type=reply&id={$reply.sfn_id|urlencode}&topic_id={$topic_head.id|urlencode}"
               class="flag-button float-right">
            Spam
            </a>
          {/if}
        </div>
       {if $reply.emotitag_face || $reply.emotitag_emotion}
       <div>
        <p>{if $reply.emotitag_face}
           <img src="images/{$reply.emotitag_face}.png" 
                alt="{$reply.emotitag_emotion}"
                class="emotitag_face" />
           {/if}
           {if $reply.emotitag_emotion}I'm {$reply.emotitag_emotion}{/if}
        </p>
       </div>
       {/if}
       <div style="clear:both;"></div>
    </div>
    {if $reply.thread_end}
      {if !$user_name}
        <div style="clear:both; margin-top:4pt; font-weight:bold;">
        <a href="user-login.php?return=topic.php%3fid={$topic_head.id|urlencode}">
         Login to comment
        </a></div>
      {else}
        <a href="#"
          onclick="document.getElementById('comment-form-{$reply.sfn_id}').style.display='block';this.style.display='none'">
          Comment</a>
        <form id="comment-form-{$reply.sfn_id}" action="handle-reply.php" method="POST" style="display:none;">
        I say:
        <input type="hidden" name="replies_url" value="{$topic_head.replies_url}" />
        <input type="hidden" name="topic_id" value="{$topic_head.id}" />
        <input type="hidden" name="parent_id" value="{$reply.sfn_id}" />
        <textarea name="content" cols="40" rows="5" style="display: block;"></textarea>
        <button onclick="this.disabled='true'; this.form.submit()" type="button">Comment</button>
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
   <input type="hidden" name="replies_url" value="{$topic_head.replies_url}" />
   <input type="hidden" name="topic_id" value="{$topic_id}" />
   I say:
   <textarea name="content" cols="40" rows="5" style="display: block;"></textarea>
   <button onclick="this.disabled='true'; this.form.submit();" type="button">Reply</button>
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