{* Smarty *}

{foreach from=$suggested_topics key=i item=topic}
{if $mode=='simple'}
<li><a href="topic.php?sfn_id={$topic.sfn_id}">{$topic.title}</a></li>
{elseif $mode == 'fancy'}
<li style="clear:left"> <img class="tiny-author-pic float-left" src="{$topic.author.photo}">
  <a href="minidashboard.php?user_url={$topic.author.url}">
  {$topic.author.name}
  </a>
     {if $topic.author.role}({$topic.author.role_name}){/if} 
     {if $topic.topic_style == 'question'}Asked:
     {elseif $topic.topic_style == 'talk'}Said:
     {elseif $topic.topic_style == 'problem'}Reported:
     {elseif $topic.topic_style == 'idea'}Said:
     {/if}
     <a href="topic.php?sfn_id={$topic.sfn_id|urlencode}">
     {$topic.title}</a>
</li>
{/if}
{/foreach}

