<div id="{$reply.id}" class="topic-reply" style="clear:both">
  
  <!-- Reply creator -->
  <div class="creator">
    <a href="minidashboard.php?user_url={$reply.author.url}">
      <img src="{$reply.author.photo}" class="topic-author-pic" alt="{$reply.author.name}" />
    </a>
    <div class="topic-author-caption" style="">
      <span class="topic-byline">
        <a href="minidashboard.php?user_url={$reply.author.url}">{$reply.author.name}</a><br />
        <strong>{if $reply.author.role}({$reply.author.role_name}){/if}</strong>
        replied {$reply.updated_relative}
      </span>
    </div>
  </div>
  
  <!-- Reply content -->
  <div class="reply-content">
    <div class="reply-text">{$reply.content|nl2br}</div>
    
    {if $reply.emotitag_face || $reply.emotitag_emotion}
      <div class="reply-emotion">
        {if $reply.emotitag_face}<img src="images/{$reply.emotitag_face}.png" alt="{$reply.emotitag_emotion}" class="emotitag_face" />{/if}
        {if $reply.emotitag_emotion}I'm {$reply.emotitag_emotion}{/if}
      </div>
    {/if}
    
    <!-- Starring and flagging -->
    <div style="float: right">
      {if $reply.author.canonical_name != $current_user.canonical_name} {* current user is not the author. *}
      <form action="handle-star.php" style="display: inline; vertical-align: middle;">
        <input type="hidden" name="topic_id" value="{$topic_head.id}"></input>
        <input type="hidden" name="reply_id" value="{$reply.id}"></input>
        
        {if $topic_head.topic_style == 'question'}
        <button type="submit">This answered the question</button>
        {elseif $topic_head.topic_style == 'idea'}
        <button type="submit">Good point</button>
        {elseif $topic_head.topic_style =='problem'}
        <button type="submit">This solved the problem</button>
        {/if}
        {if $reply.star_count && $topic_head.topic_style != "talk"}({$reply.star_count}){/if}
        </button>
      </form>
      {else}
        {if $topic_head.topic_style == 'question'}
        This answered the question
        {elseif $topic_head.topic_style == 'idea'}
        Good point
        {elseif $topic_head.topic_style =='problem'}
        This solved the problem
        {/if}
        ({$reply.star_count})          
      {/if}
      
      {if $flagged_reply == $reply.sfn_id}
        <span class="disabled flag-button float-right">Inappropriate?</span>
      {else}
        <a href="handle-flag.php?type=reply&amp;id={$reply.sfn_id|urlencode}&amp;topic_id={$topic_head.id|urlencode}" class="flag-button float-right">Inappropriate?</a>
      {/if}
    </div>
    <!-- Commenting -->
    {if $reply.in_reply_to == $topic_head.id}
    <div class="comment-form-container">
      {if !$user_name}
        <a href="user-login.php?return=topic.php%3fid={$topic_head.id|urlencode}">Login to comment</a>
      {else}
      <a href="#" id="comment-link-{$reply.sfn_id}" onclick="document.getElementById('comment-form-{$reply.sfn_id}').style.display='block';this.style.display='none';return false;">Comment</a>
      <form id="comment-form-{$reply.sfn_id}" action="handle-reply.php" method="POST" style="display:none;">
        I say:
        <br /><br />
        <div><input type="hidden" name="replies_url" value="{$topic_head.replies_url}" />
        <input type="hidden" name="topic_id" value="{$topic_head.id}" />
        <input type="hidden" name="parent_id" value="{$reply.sfn_id}" /></div>
        <textarea name="content" cols="62" rows="5" style="display: block;"></textarea>
        <br />
        <button onclick="this.disabled='true'; this.form.submit()" type="button">Comment</button>
        <a href="#" onclick="document.getElementById('comment-form-{$reply.sfn_id}').style.display='none';document.getElementById('comment-link-{$reply.sfn_id}').style.display='inline';return false;">Cancel</a>
      </form>
      {/if}
    </div>
    {/if}
  </div><!--/topic-content-->
  <div style="clear:both;"></div>
</div><!--/topic-reply-->