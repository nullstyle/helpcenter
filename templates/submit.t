{include file="header.t"}

<h1>Do any of these help?</h1>

<ul class="topic-list">
{foreach from=$suggested key=i item=topic}
{* FIXME the img isn't really properly layed out *}
<li style="clear:left"> <img class="tiny-author-pic float-left" src="{$topic.author.photo}">
  <a href="minidashboard.php?user_url={$topic.author.url}">
  {$topic.author.name}
  </a>
     {if $topic.author.role}({$topic.author.role_name}){/if} 
     {if $topic.topic_style == 'question'}Asked:
     {elseif $topic.topic_style == 'talk'}Said:
     {elseif $topic.topic_style == 'problem'}Reported:
     {elseif $topic.topic_style == 'idea'}Said:
     {/if}
     <a href="topic.php?id={$topic.id|urlencode}">
     {$topic.title}</a> </li>
{/foreach}
</ul>

<script type="text/javascript">
<!--
function redoSearch() {ldelim}
  var redoField = document.getElementById('invisible-redo-field');
  var subjectField = document.getElementById('submit-subject');
  redoField.value = subjectField.value;
  var redoForm = document.getElementById('invisible-redo-form');
  redoForm.submit();
{rdelim}
-->
</script>

{if $suggested}
<table style="width:100%;">
<tr><td class="left-hed"> Nope? </td>
<td>
<p> <img class="float-right" 
         alt="Powered by Satisfaction" src="images/poweredbysmall.png" />
  Well then, fill in the details below and submit your topic for
  everyone to see and answer <br />
  OR reword your topic and
  <a href="#" onclick="redoSearch(); return false;">re-do the search</a>
</td>
</tr>
</table>
{else}
<p> <img class="float-right" 
         alt="Powered by Satisfaction" src="images/poweredbysmall.png" />
Sorry, we looked for similar topics in our system and didn't find any.
If you re-word your topic you can also 
<a href="#" onclick="redoSearch(); return false;">re-do the search</a>,
OR fill in the details below and submit your topic
</p>
{/if}

<form id="invisible-redo-form" style="display: none; width: 100%; position: relative;" action="submit.php">
<input id="invisible-redo-field" name="subject" />
</form>

<h4> Your Topic <img src="images/required.png" alt="*" /> </h4>

<form style="width: 100%; position: relative;" action="handle-submit.php">

<input id="submit-subject" name="subject" style="width:350pt;" value="{$subject}" />

<h4>Details</h4>
<textarea name="details" rows="4" cols="50" style="width:350pt;">
</textarea>

<h4>Tell everyone how this makes you feel </h4>
<div>
<span onclick="for (var i in this.childNodes) {ldelim}
                if (this.childNodes[i].tagName == 'IMG')
                  this.childNodes[i].src = 'images/' + this.childNodes[i].id + '.png';
              {rdelim}
              event.target.src='images/' + event.target.id + '_on.png';
              var emoticonElem = document.getElementById('emoticon');
              emoticonElem.value=event.target.id">
<input id="emoticon" type="hidden" name="emoticon" value="" />
<img id="happy" src="images/happy.png" onsrc="images/happy_on.png" style="vertical-align:middle;" />
<img id="sad" src="images/sad.png" style="vertical-align:middle;" />
<img id="indifferent" src="images/indifferent.png" style="vertical-align:middle;" />
<img id="silly" src="images/silly.png" style="vertical-align:middle;" />
</span>
<span>I'm: <input name="emotion" /></span>
</div>

<h4>Add tags</h4>

<table>
<tr>
<td>
<input name="tags" value="" style="width: 150pt" /> <br />

<span class="small-note">Comma-separated. (e.g. hot dogs, cake, pie)</span>

</td>

<td>
<h4>What are tags?</h4>
You can give your topic a "tag". This is a keyword that will 
help people find your topic and get you good responses.

</td>
</tr>
</table>

<div>
<h4>Associate products & services</h4>
<ul class="tight">
{foreach from=$products key=i item=product}
<li><label><input type="checkbox" name="product[]" value="{$product.sfn_id}" /> {$product.name}</label></li>
{/foreach}
</ul>
</div>

<h1 style="margin-top: 1pc;">Done? Submit your topic as one of these four types (xxxTBD): </h1>
<button type="submit">Ask as a Question</button>
<button type="submit">Share as an Idea</button>
<button type="submit">Report as a Problem</button>
<button type="submit">Just Talk</button>

</form>

</div>

{include file="footer.t"}

</div>
</body>
</html>
