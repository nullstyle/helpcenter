<!-- COMMENT -->
<div id="{$reply.id}" class="topic-comment" style="clear:both">
  <div class="comment-content">
    {$reply.content}
    <span class="topic-byline">
      <a href="minidashboard.php?user_url={$reply.author.url}">{$reply.author.name}</a>
      <strong>{if $reply.author.role}({$reply.author.role_name}){/if}</strong>
      {$reply.updated_relative}
    </span>
  </div>
  <div style="clear:both;"></div>
</div>
<!--/COMMENT-->