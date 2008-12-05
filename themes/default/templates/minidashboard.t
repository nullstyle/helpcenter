{include file="header.t"}

<div id="container">
	<div id="content">
		<a href="http://getsatisfaction.com/people/{$user.canonical_name}" style="margin-bottom:20px; float: left;" ><img src="{$user.photo}" alt="user photo" id="topic-style" /></a>
		<div id="topic-list-head">
  		<h1>
  		  {if $current_user.canonical_name != $user.canonical_name}
          {$user_possessive|capitalize} Activity
  		  {else}
          Hello {$user_name}
  		  {/if}
  		</h1>
  		<a href="http://getsatisfaction.com/people/{$user.canonical_name}">See all of {$user_possessive} participation on Get Satisfaction</a>
    </div>
		<div class="topic-list mixed">
		{foreach from=$company_topics key=i item=topic}
		  {include file="mixed-topic-list.t"}
		{foreachelse}
      {if $current_user.canonical_name != $user.canonical_name}
        {$user.fn} hasn't
      {else}
        You haven't
      {/if}
       participated in any {$company_name} discussions so far.
		{/foreach}			
		</div>
	</div><!-- #content -->
</div><!-- #container -->

<div id="primary" class="sidebar">
  {if !$noncompany_topics}
    {if $current_user.canonical_name != $user.canonical_name}
      {$user.fn} hasn't
    {else}
      You haven't
    {/if}
    participated in any other discussions so far.
  {else}
  <h3>
    Recent topics from<br />
    {if $current_user.canonical_name != $user.canonical_name}
      {$user.fn}
    {else}
      You
    {/if}
    on <a href="http://getsatisfaction.com">Get Satisfaction</a>
  </h3>
  <ul>
    {foreach from=$noncompany_topics key=i item=topic}
      <li><a href="{$topic.at_sfn}">{$topic.title}</a> in <strong>{$topic.company.fn}</strong></li>
    {/foreach}
  </ul>
  {/if}
  
</div>


{include file="footer.t"}
