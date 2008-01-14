{include file="header.t"}

<h1>Do any of these help?</h1>

<ul class="topic-list">
{foreach from=$suggested key=i item=topic}
{* FIXME the img isn't really properly layed out *}
<li style="clear:left"> <img class="tiny-author-pic float-left" src="{$topic.author.photo}">
     {$topic.author.name}
     {if $topic.author.role}({$topic.author.role}){/if} 
     {if $topic.topic_style == 'question'}Asked:
     {elseif $topic.topic_style == 'talk'}Said:
     {elseif $topic.topic_style == 'problem'}Reported:
     {elseif $topic.topic_style == 'idea'}Said:
     {/if}
     {$topic.title} </li>
{/foreach}
</ul>

<h3 class="left-hed"> Nope? </h3>

<p>
<img class="float-right" 
     alt="Powered by Satisfaction" src="poweredbysmallStack.png" />
Well then, fill in the details below and submit your topic for
everyone to see and answer <br />
OR reword your topic and redo the search
</p>

<h3> Your Topic * </h3>

<form style="width: 100%; position: relative;" action="handle-submit.php">

<div>
<div class="float-right" style="width: 180pt;">
If you re-word your topic you can also 
  <a href="dead-end.php">re-do the search</a>
</div>
<input name="subject" class="questionbox" value="{$subject}" />
<button type="submit">Go</button>
</div>

<h4>Details</h4>
<textarea name="details" rows="4" cols="50">
</textarea>

<h4>Tell everyone how this makes you feel </h4>
<div onclick="event.target.style.border='1px solid black';
              var emoticonElem = document.getElementById('emoticon');
              emoticonElem.value=event.target.id">
<input id="emoticon" type="hidden" name="emoticon" value="" />
<img id="happy" src="images/happy.png">
<img id="sad" src="images/sad.png">
<img id="indifferent" src="images/indifferent.png">
<img id="silly" src="images/silly.png">
</div>

<h4>Add tags</h4>

<table>
<tr>
<td>
<input name="tags" value="" style="width: 150pt" /> <br />

<span class="small-note">Comma-separated. (e.g. hot dogs, cake, pie)</span>

<h4>Or choose from popular tags:</h4>
xxx

<p>
<input name="tag-search" style="width: 120pt;" /> <button>+</button><br />
<span class="small-note">Start typing and we'll make suggestions</span>
</p>

</td>
<td>
<h4>What are tags?</h4>
You can give your photos a "tag", which is like a keyword. Tags help you find photos which have something in common. You can assign up to 75 tags to each photo.

</td>
</tr>
</table>

</form>

</div>

{include file="footer.t"}

</div>
</body>
</html>
