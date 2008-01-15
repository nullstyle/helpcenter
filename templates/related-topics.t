<div class="sidebar">
<h3>Related Topics from across Satisfaction:</h3>
<ul>
{foreach from=$related_topics key=i item=topic}
<li>{$topic.title} in <strong>{$topic.company.fn}</strong></li>
{foreachelse}
BORKED: NO RELATED TOPICS IN STASH
{/foreach}
</ul>
</div>
