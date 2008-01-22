{include file="header.t"}

{include file="topnav.t"}

<h1><a href="topic.php?id={$topic_id}">Back to the topic page</a></h1>

<div>
<h2>You can: Digg Delicious Print Subscribe Twitter it | Email to a friend</h2>
</div>

<h2>OR Twitter this topic</h2>

What are you doing?
<div>
<textarea rows="2" cols="80"
 onkeyup="alert(this.textContent); var cntElem = document.getElementById('tweet-chars-left'); cntElem.textContent = 140 - this.textContent.length;"
name="tweet-content"
>{if $topic_lead.style=='problem'}Reporting:
{elseif $topic_lead.style=='idea'}Sharing: 
{elseif $topic_lead.style=='question'}Asking:
{elseif $topic_lead.style=='talk'}Talking:
{/if}>
{$topic_lead.title}</textarea>
</div>
<div class="float-right">Characters left: <span id="tweet-chars-left">140</span></div>
We'll add this link to the end of your tweet: xxx

<table>
<tr>
<td>
Twitter username
</td>
<td>
<input name="twitter_name" />
</td>
</tr>
<tr>
<td>
Twitter password
</td>
<td>
<input name="twitter_password" />
</td>
</tr>
</table>
<button>Send your tweet</button> OR Cancel

<hr>

<h2>OR Email this topic to a friend</h2>

<div style="border: 1px solid black; padding: 4pt; ">
{$user_name} thinks you might be interested in this discussion from Satisfaction:
&ldquo;{$topic_lead.title}

<p>
{$topic_lead.content}&rdquo;
</p>

{$topic_lead.author.name} asked this on
{$topic_lead.updated|date_format:"%B %e, %y"}
</div>

<form>
<table>
<tr>
<td><label for="from_email">Your email</label></td>
<td><input id="from_email" name="from_email" /> </td>
</tr>
<tr>
<td><label for="to_email">Email to</label></td>
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
