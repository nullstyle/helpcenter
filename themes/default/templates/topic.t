{include file="header.t"}


<div id="container">
	<div id="content">
    
    <!-- PAGE ERRORS -->
    
		{if $blank_reply_error}
		<div class="error-box">
		Enter some text to reply and click the 'Comment' or 'Reply' button directly below that text.
		</div>
		{/if}

		{if $blank_reply_error}
		<div class="error-box">
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
		<div class="message-box">
  		You've shared this topic with
  		{foreach from=$shared_with key=i item=email}{if $i != 0}, {/if}
  		{$email}{/foreach}.
		</div>
		{/if}

		{if $share_failed_msg}
		<div class="error-box">
		Oops! Something went wrong while trying to share this topic with
		{foreach from=$shared_with key=i item=email}{if $i != 0}, {/if}
		{$email}{/foreach}.
		</div>
		{/if}

		{if $me_too_failed_error}
		<div class="error-box">
		For some reason, we could not record the fact that you had this 
		question too. Perhaps you have already marked it that way, or
		perhaps there was just a glitch in Get Satisfaction. Sorry!
		</div>
		{/if}

		{if $me_tood_topic_msg}
		<div class="message-box">
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
		<div class="error-box">
		Note: You cannot mark your own reply as
		  {if $topic_head.topic_style == 'question'}
		    "answering the question."
		  {elseif $topic_head.topic_style == 'idea'}
		    "a good point."
		  {elseif $topic_head.topic_style == 'talk'}
		    "answering the question."
		  {elseif $topic_head.topic_style =='problem'}
		    "solving the problem."
		  {/if}
		</div>
		{/if}
		
		<!-- / PAGE ERRORS -->
		
    <!-- START TOPIC -->

  	<div id="topic-head" class="{$topic_head.topic_style}">
      <!-- Topic Creator -->
      <div class="creator">
        <a href="minidashboard.php?user_url={$topic_head.author.url}">
          <img src="{$topic_head.author.photo}" class="topic-author-pic" alt="{$topic_head.author.name}'s avatar" />
        </a>
        <div class="topic-author-caption">
          <span class="topic-byline">
          <a href="minidashboard.php?user_url={$topic_head.author.url}">
            {$topic_head.author.name}
          </a>
          </span>
          {if $topic_head.topic_style == 'question'} asked this question
          {elseif $topic_head.topic_style == 'idea'} shared this idea
          {elseif $topic_head.topic_style == 'talk'} asked this question
          {elseif $topic_head.topic_style == 'problem'} reported this problem
          {/if}
          {$topic_head.published_relative}
        </div>
      </div>
      
      <!-- Topic details -->
      <div id="topic-bubble">
        <img src="{$sprinkles_root_url}/images/{$topic_head.topic_style}_med.png" alt="{$topic_head.topic_style}" style="float: right" />
        <h1>{$topic_head.title}</h1>
        <div>{$topic_head.content}</div>
        
        {if $flagged_topic == $topic_head.sfn_id}
          <br />
          <span class="disabled flag-button">This is inappropriate</span>
        {else}
          <br />
          <a href="handle-flag.php?type=topic&amp;id={$topic_head.sfn_id|urlencode}&amp;topic_id={$topic_head.id|urlencode}" class="flag-button"> This is inappropriate </a>
        {/if}
        {if $topic_head.emotitag_face || $topic_head.emotitag_emotion}
          <div> 
            {if $topic_head.emotitag_face}
              <img src="images/{$topic_head.emotitag_face}.png" style="vertical-align:middle;" alt="{$topic_head.emotitag_emotion}" />
            {/if}
            {if $topic_head.emotitag_emotion}I'm {$topic_head.emotitag_emotion}{/if}
          </div>
        {/if}
        
        <!-- BEST REPLIES FROM THE COMPANY -->
        {if $company_promoted_replies}
        <div class="best-of">
          <h2>Best solution from the company</h2>
        	{foreach from=$company_promoted_replies key=i item=reply}
        	<div class="box">
        	  <div class="tight">{$reply.content}</div>
        	  <div class="light p">
        	    <img src="{$reply.author.photo}" class="small-author-pic" style="vertical-align:middle;" alt="{$reply.author.name}" />
          	  <strong>
          	    <a href="minidashboard.php?user_url={$reply.author.url}">{$reply.author.name}</a>
          	    {if $reply.author.role}({$reply.author.role_name}){/if}
          	  </strong>
          	  {$reply.updated_relative}
        	  </div>
        	</div>
        	{/foreach}
        </div>
        {/if}

        <!-- BEST REPLIES FROM EVERYONE -->
        {if $star_promoted_replies}
        <div class="best-of">
          <h2>Best solution from the people</h2>
          {foreach from=$star_promoted_replies key=i item=reply}
          <div class="box">
            <div class="tight">{$reply.content}</div>
            <div class="light p">
              <img src="{$reply.author.photo}" class="small-author-pic" style="vertical-align:middle;" alt="photo" />
              <strong>
                <a href="minidashboard.php?user_url={$reply.author.url}">{$reply.author.name}</a>
                {if $reply.author.role}({$reply.author.role_name}){/if}
              </strong>
              {$reply.updated_relative}
            </div>
          </div>
          {/foreach}
        </div>
        {/if}
      </div>

      <!-- REPLIES -->
      <div id="topic-replies">
        {if !$user_name}
          <h3><a href="user-login.php?return=topic.php%3fid={$topic_head.id|urlencode}">Login to reply</a></h3>
        {else}
          <h3><a href="#reply-form">Reply to this {$topic_head.topic_style}</a></h3>
        {/if}
        <br style="clear:both"/>
        {foreach from=$replies key=i item=reply}
    	    {if $reply.in_reply_to == $topic_head.id}
    	    {include file="topic-reply.t"}
    	    {else}
    	    {include file="topic-comment.t"}
    	    {/if}
    	  {/foreach}
    	  
    	  <!-- TOPIC REPLY FORM -->
        {if !$user_name}
          <h3><a href="user-login.php?return=topic.php%3fid={$topic_head.id|urlencode}">Login to reply</a></h3>
        {else}
    	  <div class="topic-reply" style="clear:both">
    	    <h3>Reply to this {$topic_head.topic_style}</h3><br />
          <div class="creator">
            <a href="minidashboard.php?user_url={$current_user.url}">
              <img src="{$current_user.photo}" class="topic-author-pic" alt="{$user_name}" />
            </a>
      	  </div>
      	  <div class="reply-content">
            <form id="reply-form" action="handle-reply.php" method="POST">
              <div><input type="hidden" name="replies_url" value="{$topic_head.replies_url}" />
              <input type="hidden" name="topic_id" value="{$topic_head.id}" /></div>
              <textarea name="content" cols="62" rows="5" style="display: block;"></textarea>
              <br />
              <button onclick="this.disabled='true'; this.form.submit()" type="button">Reply</button>
            </form>
          </div>
        </div>
  	    {/if}
  	  </div><!-- /topic-replies -->
	  </div>
	</div><!-- #content -->
</div><!-- #container -->

<div class="sidebar">
  <ul>
    <li><a href="share-topic.php?id={$topic_id}">Share this topic</a></li>
    <li>
      <h3>In this topic</h3>
      <ul class="topic-stats">
        <li><strong>{$particip.people}</strong> {if $particip.people != 1}people{else}person{/if}</li>
        <li><strong>{$particip.employees}</strong> employee{if $particip.employees != 1}s{/if}</li>
        <li><strong>{$reply_count}</strong> {if $reply_count != 1}replies{else}reply{/if}</li>
        <li>
          {if $particip.count_official_reps}
            <strong>{$particip.count_official_reps}</strong>
            {if count($particip.official_reps) != 1} 
            official reps {else}
            official rep {/if}
          {/if}
        </li>
      </ul>      
    </li>
    <li>
    {if $particip.count_official_reps}
      {foreach from=$particip.official_reps key=i item=rep}
        <a href="minidashboard.php?user_url={$rep.url}"><img src="{$rep.photo}" class="small-author-pic" alt="{$rep.name}" /></a>
      {/foreach}
    {/if}
    </li>
  </ul>
  {if $topic_head.tags}
  <ul>
    <li>
    <h3>Tags</h3>
    <ul>
    {foreach from=$topic_head.tags key=i item=tag}
      <li><a href="discuss.php?tag={$tag}">{$tag}</a></li>
    {/foreach} 
    </ul>
    </li>
  </ul>
  {/if}
  {if $topic_head.products}
  <ul>
    <li>
    <h3>Tags</h3>
    <ul>
    {foreach from=$topic_head.products key=i item=product}
      <li><a href="discuss.php?product={$product}">{$product}</a></li>
    {/foreach} 
    </ul>
    </li>
  </ul>
  {/if}
</div>

{include file="footer.t"}
