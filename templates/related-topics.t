{if $related_topics}
<div class="sidebar">
<h3>Related Topics from across Get Satisfaction:</h3>
<ul>
{foreach from=$related_topics key=i item=topic}
<li><a href="{$topic.at_sfn}">{$topic.title}</a> in <strong>{$topic.company.fn}</strong></li>
{/foreach}
</ul>
</div>
{/if}
