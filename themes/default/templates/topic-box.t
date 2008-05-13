<div class="topic-box">
	<h2>
	  {if $style_label}
	    {$style_label}
	  {else}
	    What do you want to ask?
	  {/if}
	</h2>
	<form style="width: 100%; position: relative;padding:0" action="submit.php">
		<textarea name="subject" class="text" rows="3" cols="40" style="height:48px;width:500px;font-family:inherit"></textarea>
		<button type="submit" style="vertical-align: top;font-size:18px;height:50px;width:50px">Go</button>
		<div style="padding-top: 4px;clear:both">
		  OR 
		  {if !$filter_style || $filter_style != 'question'}<a href="discuss.php?style=question">Ask a question</a>, {/if}
		  {if !$filter_style || $filter_style != 'idea'}<a href="discuss.php?style=idea">Share an idea</a>, {/if}
		  {if !$filter_style || $filter_style != 'problem'}<a href="discuss.php?style=problem">Report a problem</a>, {/if}
		  {if !$filter_style || $filter_style != 'talk'}<a href="discuss.php?style=talk">Start a discussion</a>{/if}
		  </div>
	</form>
</div><!--end topic-box-->