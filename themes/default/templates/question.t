<div class="topic">
  <a href="topic.php?id={$topic.id}#replies" class="topic_numbers">
		<span class="replies">
			<strong class="reply_count">{$topic.reply_count}</strong>
			<span class="reply_label">{if $topic.reply_count == 1} reply {else} replies{/if}</span>
		</span>
    <!-- <span class="followers">
      <strong class="follower_count">1</strong>
      <span class="follower_label">Follower</span>
    </span> -->
	</a>
  <div class="topic-list-content">
    <h3>
      <a href="topic.php?id={$topic.id}">{$topic.title}</a>
      <a href="#" class="show_toggle" onclick="document.getElementById('topic_{$i}_content').style.display = 'block'; this.style.display = 'none'; this.nextSibling.style.display = 'inline'; return false;">Show more...</a><a href="#" class="show_toggle" style="display:none;" onclick="document.getElementById('topic_{$i}_content').style.display = 'none'; this.style.display = 'none'; this.previousSibling.style.display = 'inline'; return false;">...Show less</a>
    </h3>
    <div id="topic_{$i}_content" class="topic-content-body" style="display: none">{$topic.content}</div>
    <p style="margin-top:7px;color:#666">
      <img style="float:none;margin:0 5px 0 0;vertical-align: middle;width:16px;padding:0" src="{$topic.author.photo}" alt="{$topic.author.name} photo" />
      <a href="minidashboard.php?user_url={$topic.author.url}">{$topic.author.name}</a> 
      posted this {$topic.published_relative}
			{if $topic.reply_count}
      <span class="last-reply"> | 
			  <a href="">Last reply</a> {$topic.updated_relative}.
		  </span>
		  {/if}
		  <span style="text-transform:uppercase;border-bottom:1px solid #ccc;margin-bottom:10px;font-weight:bold;background-color:#ffffcc;padding:2px;border:1px solid #ccc;margin-right:10px;clear:both;text-align:center;font-size:10px"><a href="" style="text-decoration:none">Answer question</a></span>
    </p>
	</div>
</div>