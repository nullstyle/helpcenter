{* Smarty *}

{foreach from=$suggested_topics key=i item=topic}
<li><a href="topic.php?id={$topic.id}">{$topic.title}</a></li>
{/foreach}
