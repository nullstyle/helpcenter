{include file="header.t"}

<div id="container">
	<div id="content">
	
	<h1><a href="topic.php?id={$topic_id}">Back to the topic page</a></h1>

	<h2>Email this topic to a friend</h2>

	<form action="handle-share-topic.php">
	  <input type="hidden" name="id" value="{$topic_id}" />

	{if !$user_name}
	Enter your name:
	<input name="sender_name"
	       onblur="var author_name_elem = getElementById('author_name');
	               author_name_elem.textContent = this.value;
	               author_name_elem.className = '';" />
	{else}
	<input name="sender_name" type="hidden" value="{$current_user.fn}" />
	{/if}


	<div class="box" style="padding: 4pt;">
	{if !$user_name}
	<span id="author_name" class="small-note">(your name goes here)</span>
	{else}
	{$user_name}
	{/if}
	thinks you might be interested in this discussion from Satisfaction:

	<p>
	&ldquo;{$topic_head.title}
	</p>

	<p>
	{$topic_head.content}&rdquo;
	</p>

	<p>{$topic_head.author.name} asked this on
	{$topic_head.published|date_format:"%B %e, %y"}
	</p>

	<textarea name="personal_message">
	</textarea>
	<div class="small-note">(Add a personal note if you like.)</div>

	</div>

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
	<td>
	  <div class="small-note">Comma-separate multiple e-mail addresses. Limited to 5 addresses.</div>

	<button>Send it</button> OR <a href="topic.php?id={$topic_id}">Cancel</a>

	</td>
	</tr>
	</table>
	</form>

	</div><!-- #content -->
</div><!-- #container -->

{include file="sidebar.t"}

{include file="footer.t"}

