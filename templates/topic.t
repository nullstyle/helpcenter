{* Smarty *}

{include file="header.t"}

<div class="topic-head">
Hey { $company_name }!
<h3><strong> { $lead_item.AUTHOR.NAME }</strong> has a question.</h3>

<table>
<tr>
<td style="width:48pt;">
<div><img src="{$lead_item.AUTHOR.PHOTO}" class="topic-author-pic" /></div>
<div class="topic-author-caption">
<span class="topic-byline">
{ $lead_item.AUTHOR.NAME }
</span>
asked this question {$topic_updated_relative}
</div>
</td>

<td style="top; margin: 1pc;">
<h3><strong>{ $lead_item.TITLE }</strong></h3>
{ $lead_item.CONTENT }
</td>

<td style="width: 120pt;">
Share or follow this topic
<input style="width:120pt;" value="I have this question too!" />
<div>
In this topic<br />
x people<br />
x employees<br />
x replies<br />
x views<br />
</div>
<p>
x official rep is here <br />
<img src="" style="width:24pt; height:24pt;" />
</td>
</tr>
</table>

</div>

<div class="sidepane">
  <img style="padding: 6pt; margin: 6pt;" src="poweredbysmallStack.png" alt="Powered by: Satisfaction" />

  {include file="related-topics.t"}
</div>

<div>
({$reply_count} replies)
  <h2>Login to reply</h2>
  <ul class="topic-replies">
  {foreach from=$replies key=i item=reply}
  <li>
    <table><tr>
    <td>
      <img src="{$reply.AUTHOR.PHOTO}" class="reply-author-pic" />
    </td><td>
      {$reply.AUTHOR.NAME} (xxx credentials) replied {$reply.UPDATED_RELATIVE}:
        <p>{$reply.CONTENT}</p>
    </td>
    </tr></table>
  </li>
  {/foreach}
  </ul>

{if ($page_num > 0)}
<a href="topic.php?id={$topic_id}&page={$page_num-1}">&lt;</a>
{else}
&nbsp;
{/if}
Page {$page_num+1} of {$num_pages}
{if ($page_num+1 < $num_pages)}
<a href="topic.php?id={$topic_id}&page={$page_num+1}">&gt;</a>
{else}
&nbsp;
{/if}
</div>

{include file="footer.t"}
