<div class="taglist-box">
  <h2>Browse topics</h2>
  {foreach from=$top_topic_tags key=i item=tag_array}
    <ul style="list-style-type:none;float:left;margin-right:40px">
    {foreach from=$tag_array key=j item=tag}
      <li><a href="discuss.php?tag={$tag}">{$tag}</a></li>
    {/foreach}
    </ul>
  {/foreach}
</div>