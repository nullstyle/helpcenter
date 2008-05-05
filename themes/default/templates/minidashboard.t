{include file="header.t"}


<div id="container">
	<div id="content">
		<h1>
		  <img src="{$user.photo}" alt="user photo" style="float:left;margin:0 20px 20px 0" />
		  {if $current_user.canonical_name != $user.canonical_name}
        {$user_possessive|capitalize} Activity
		  {else}
        Hello {$user_name}
		  {/if}
		</h1>

		<div class="topic-list mixed">
		{foreach from=$company_topics key=i item=topic}
		  {include file="mixed-topic-list.t"}
		{foreachelse}
      You haven't participated in any {$company_name} discussions so far.
		{/foreach}			
		</div>
		
	</div><!-- #content -->
</div><!-- #container -->

{include file="sidebar.t"}
<div class="sidebar">
  <h3>
    <a href="http://getsatisfaction.com/people/{$user.canonical_name}">Recent topics from 
    {if $current_user.canonical_name != $user.canonical_name}
      {$user.user_name}
    {else}
      your
    {/if}
    Satisfaction Dashboard:</a>
  </h3>
  <ul>
  {foreach from=$noncompany_topics key=i item=topic}
    <li>{$topic.title} in <strong><a href="{$topic.company.uri}">{$topic.company.fn}</a></strong></li>
    {foreachelse}
    <li>You haven't participated in any other discussions so far.</li>
    {/foreach}
  </ul>
</div>


{include file="footer.t"}
