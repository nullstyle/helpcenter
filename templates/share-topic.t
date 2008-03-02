{include file="header.t"}

<h1><a href="topic.php?id={$topic_id}">Back to the topic page</a></h1>

<h2>Email this topic to a friend</h2>

<div class="box" style="padding: 4pt;">
{$user_name} thinks you might be interested in this discussion from Satisfaction:

<p>
&ldquo;{$topic_head.title}
</p>

<p>
{$topic_head.content}&rdquo;
</p>

<p>{$topic_head.author.name} asked this on
{$topic_head.published|date_format:"%B %e, %y"}
</p>
</div>

<form action="handle-share-topic.php">
<input type="hidden" name="id" value="{$topic_id}" />
<table>
<tr>
<td class="form-label oneliner right"><label for="from_email">Your email</label></td>
<td><input name="from_email" /> </td>
</tr>
<tr>
<td class="form-label oneliner right"><label for="to_email">Email to</label></td>
<td><input name="to_email" /> </td>
</tr>
<tr>
<td></td>
<td><div class="small-note">Comma-separate multiple e-mail addresses. Limited to 5 addresses.</div>

<button>Send it</button> OR <a href="topic.php?id={$topic_id}">Cancel</a>

</td>
</tr>
</table>
</form>

{include file="footer.t"}
