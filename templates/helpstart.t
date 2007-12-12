{include file="header.t"}

<h1>Help Start</h1>

<div class="question">
<h2>How can we help you?</h2>

<form style="width: 100%; position: relative;" action="dead">
<input name="question" class="questionbox" />
<button type="submit">Go</button>

<img class="pin-right" alt="Powered by Satisfaction" 
     src="poweredbysmallStack.png" width="113" height="30" />
</form>
</div>

<div class="sidepane">
<div class="sidebar">
  <h2>Our Contact info</h2>
<h3>Phone</h3>
  {$contact_info.contact_phone}

<h3>Email</h3>
  {$contact_info.contact_email}

<h3>Physical</h3>
  {$contact_info.contact_address}
  {$contact_info.map_url}
</div>
</div>

<div class="topic-list">

<h2>Get answers <span class="lighter">(check out these recently answered topics)</span></h2>

<ul class="topic-list">
{foreach from=$entries key=i item=entry}
    <li><a href="topic.php?id={$entry.id}">{$entry.title}</a></li>
{/foreach}
</ul>
<div class="right"><a href="discuss.php">See more topics of discussion</a></div>
</div>

<div>
<h2>We're here to help</h2>

<table>
<tr>
{foreach from=$company_people key=i item=person}
<td><img class="topic-author-pic" style="float:left;" src="{$person.photo}" /> {$person.fn}</td>
{if $i % 2 == 1 && $i != count($company_people)-1}
</tr>
<tr>
{/if}
{/foreach}
</tr>
</table>
</div>

<hr />

<div id="what-satisfaction">
<h4>Welcome to {$company_name}'s customer services Satisfaction site.</h4>

<h5>What is Satisfaction anywho?</h5>

<p>Satisfaction is an open discussion-based system providing better
customer service, with or without company involvement. Right now
you're using an extension of the Satisfaction parent site. This is
just one tiny branch off a whole forest of customer service <a
href="http://www.getsatisfaction.com/">Satisfaction</a>.
</p>
</div>

{include file="footer.t"}
