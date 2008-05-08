<div class="taglist-box">
  <h2>Browse topics</h2>
  <ul style="list-style-type:none;float:left;margin-right:40px">
  {foreach from=$top_topic_tags key=i item=tag}
  <li><a href="discuss.php?tag={$tag}">{$tag}</a></li>
     {if ($i+1) % 5 == 0}</ul><ul style="list-style-type:none;float:left;margin-right:40px">{/if}
  {/foreach}
  </ul>
</div>