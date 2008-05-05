{include file="header.t"}


<div id="container">
	<div id="content">
	
		<script type="text/javascript" src="sprinkles.js"></script>

		<script type="text/javascript">
		<!--
		function redoSearch() {ldelim}
		  var subjectField = document.getElementById('submit-subject');
		  var queryText = subjectField.value;
		  var url = 'topic-suggestions.php?mode=fancy&query=' + queryText; // FIXME: url-encode
		  getHTMLAJAXAsync(url, function (suggestions) {ldelim}
		    var suggestionsElem = document.getElementById('suggestions');
		    var suggestionsLegendElem = document.getElementById('suggestions-legend');
		    var absentSuggestionsElem = document.getElementById('absent-suggestions');
		    if (trim(suggestions)) {ldelim}
		      suggestionsElem.innerHTML = suggestions;
		      suggestionsElem.style.display = 'block';
		      suggestionsLegendElem.style.display = 'block';
		      absentSuggestionsElem.style.display = 'none';
		    {rdelim} else {ldelim}
		      absentSuggestionsElem.style.display = 'block';
		      suggestionsElem.style.display = 'none';
		      suggestionsLegendElem.style.display = 'none';
		    {rdelim}
		  {rdelim});
		{rdelim}
		-->
		</script>	
		
		<form id="new_topic_form" class="question" method="get" action="submit.php">
  		<fieldset>
		    <legend><strong>1)</strong> I have a...</legend>
		    <div style="float: right; width:240px" id="live_results">
		      <p><strong>These topics may help:</strong></p><br />
	        <ul class="results">
        		{foreach from=$suggested key=i item=topic}
        		<li style="background-image: none; background-color: transparent;"><a href="topic.php?id={$topic.id|urlencode}">{$topic.title}</a></li>
        		{/foreach}
		      </ul>
	      </div>
        <ul class="rows">
          <li>
  		      <select name="style" style="width: 400px">
  		        <option value="question">Question that needs an answer</option>
  		        <option value="idea">Idea that I'd like to share</option>
  		        <option value="problem">Problem that needs solving</option>
  		        <option value="talk">Discussion I want to start</option>
  		      </select>
		      </li>
		      <li>
    		    <label id="question_prompt" class="prompt">What's your question? (One or two paragraphs work best.)</label>
  		      <label id="idea_prompt" class="prompt" style="display: none;">Tell us about this idea. (One or two paragraphs work best.)</label>
  		      <label id="problem_prompt" class="prompt" style="display: none;">What seems to be the problem? (One or two paragraphs work best.)</label>
  		      <label id="talk_prompt" class="prompt" style="display: none;">What's on your mind? (One or two paragraphs work best.)</label>
            <br />
  		      <textarea id="topic_additional_detail" name="topic[additional_detail]" rows="6" cols="36" style="width: 400px"></textarea>  		        
		      </li>
		      <li>
  		      <label>Give your <span class="dyn_style">question</span> a great title:</label><br />
  		      <input id="topic_subject" name="topic[subject]" value="{$subject}" type="text" style="width: 400px" />
  		      <br /><br />
		        <div class="alert">
  		        <small>Great: <strong>Why won't my iPhone's calendar sync with Outlook 2007?</strong></small><br />
  		        <small>Not so great: <strong>syncing calendar?????</strong></small>
  		      </div>
		      </li>
	      </ul>
  		</fieldset>

  		<fieldset>
  		  <legend><strong>2)</strong> Which {$company_name} product(s) is this topic about? </legend>
  		  
	      <ul id="topic_product_list" class="clearfix">
	    	{foreach from=$products key=i item=product}
  			  <li>
			      <input type="checkbox" id="product_{$i}" name="product[]" value="{$product.name}" {if $product.selected}checled="checked"{/if} />
  			    <label for="product_{$i}">{$product.name}</label>
  			  </li>
  			{/foreach}
	      </ul>
  		</fieldset>

  		<fieldset>
	      <legend><strong>3)</strong> Tag it with words...</legend>
        
	      <div id="new_topic_tags">
	        <label for="topic_keywords">Add words that describe your <span class="dyn_style">question</span> (optional)</label><br />
	        <textarea class="text" id="topic_keywords" name="topic[keywords]" rows="2" cols="40"></textarea>
	        <br />
	        <small>Comma-separated (e.g. hot dogs, cake, pie)</small>
	        <br /><br />
	        <p>Or choose from these popular tags:</p>
          {foreach from=$top_tags key=i item=tag}
  		      <span class="tag_toggle"><a href="#" id="tag_{$i}" onclick='return false;'>{$tag}</a></span>{if $i+1 < $top_tags_count}, {/if}
  		    {/foreach}
	      </div><!-- End Tag with words -->
	    </fieldset>
	    
	    <fieldset>
		    <legend>...and feelings. How does this make you feel? </legend>

	      <div id="satisfactometer">
    			<script type="text/javascript">
    			<!--
    			function setEmoticonPicker() {ldelim}
    			  var emoticonElem = document.getElementById('emoticon');
    			  var newVal = emoticonElem.value;
    			  if (newVal) {ldelim}
    			    var picker = document.getElementById('emoticon_picker');
    			    for (var i in picker.childNodes) {ldelim}
    			      if (picker.childNodes[i].id == newVal)
    			        picker.childNodes[i].src = 'images/' + picker.childNodes[i].id + '_on.png'
    			      else
    			        picker.childNodes[i].src = 'images/' + picker.childNodes[i].id + '.png'
    			    {rdelim}
    			  {rdelim}
    			{rdelim}

    			window.onload = setEmoticonPicker;
    			-->
    			</script>
    			<span id="emoticon_picker"
    			      onclick="for (var i in this.childNodes) {ldelim}
    			                 if (this.childNodes[i].tagName == 'IMG')
    			                   this.childNodes[i].src = 'images/' + this.childNodes[i].id + '.png';
    			               {rdelim}
    			               event.target.src='images/' + event.target.id + '_on.png';
    			               var emoticonElem = document.getElementById('emoticon');
    			               emoticonElem.value=event.target.id">
    			  <input id="emoticon" type="hidden" name="emoticon" value="{$emoticon}" />
      			<img id="happy" src="images/happy.png" style="vertical-align:middle;" alt="happy" />
      			<img id="sad" src="images/sad.png" style="vertical-align:middle;" alt="sad" />
      			<img id="indifferent" src="images/indifferent.png" style="vertical-align:middle;" alt="indifferent" />
      			<img id="silly" src="images/silly.png" style="vertical-align:middle;" alt="silly" />
    			</span>
		      <br />
		      <br />
		      <span>I'm: <input name="emotion" value="{$emotion}" /></span>
		    </div><!-- End Emoticon Picker -->
        <div class="clear"></div>
  		</fieldset>

  		<fieldset>
  			<input type="submit" value="Post your topic" style="font-size:1.4em;border:3px solid #ccc; padding: 5px 10px;"/>
  		</fieldset>
	  </form>

	</div><!-- #content -->
</div><!-- #container -->

{include file="footer.t"}
