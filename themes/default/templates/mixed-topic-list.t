<div class="topic">
  <a href="topic.php?id={$topic.id}#replies" class="topic-numbers">
		<span class="replies">
			<strong class="reply-count">{$topic.reply_count}</strong>
			<span class="reply-label">{if $topic.reply_count == 1} reply {else} replies{/if}</span>
		</span>
	</a>
	<span class="t-img"><img src="{$sprinkles_root_url}/images/{$topic.topic_style}_small.png" alt="{$topic.topic_style}"/></span>
  <div class="topic-list-content">
    <h3>
      <a href="topic.php?id={$topic.id}">{$topic.title}</a>
      <a href="#" class="show-toggle" onclick="document.getElementById('topic_{$i}_content').style.display = 'block'; this.style.display = 'none'; this.nextSibling.style.display = 'inline'; return false;">Show more...</a><a href="#" class="show-toggle" style="display:none;" onclick="document.getElementById('topic_{$i}_content').style.display = 'none'; this.style.display = 'none'; this.previousSibling.style.display = 'inline'; return false;">...Show less</a>
    </h3>
    <div id="topic_{$i}_content" class="topic-content-body" style="display: none">
      {$topic.content}
      <p class="topic-tags">{if $topic.tags}Tagged: {foreach from=$topic.tags key=i item=tag}{if ($i>0)},{/if} <a href="discuss.php?tag={$tag}">{$tag}</a>{/foreach} {/if}</p>
    </div>
    <div class="topic-meta">
      <img src="{$topic.author.photo}" alt="{$topic.author.name} photo" />
      <a href="minidashboard.php?user_url={$topic.author.url}">{$topic.author.name}</a> 
      posted this {$topic.published_relative}
			{if $topic.reply_count}
      <span class="last-reply"> | 
			  <a href="">Last reply</a> {$topic.updated_relative}.
		  </span>
		  {/if}
    </div>
	</div>
</div>