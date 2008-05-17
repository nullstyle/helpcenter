{include file="header.t"}


<div id="container">
	<div id="content">
	
		<script type="text/javascript" src="sprinkles.js"></script>

		<script type="text/javascript">
		<!--
    {literal}

    LiveSearch = function() {
      this.initialize();
    }

    LiveSearch.prototype = {
      initialize: function() {
        this.searcher = null;
        this.last_value = null;
      },
      start: function(on, to) {
        this.on = on;
        this.to = document.getElementById(to);
        object = this;
        this.searcher = setInterval(function() {object.search.apply(object, [])}, 2500);
      },
      stop: function() {
        clearInterval(this.searcher);
      },
      search: function() {
        if(this.last_value != this.on.value && this.on.value != "") {
          this.last_value = this.on.value;
          var url = 'topic-suggestions.php?query=' + this.on.value;
          object = this;
          getHTMLAJAXAsync(url, function (results) {
            object.update_results(trim(results));
          });
        }
      },
      update_results: function(results) {
        this.to.innerHTML = results;
      }
    }

    live_search = new LiveSearch();

		function addTag(tag) {
		  var tagField = document.getElementById('topic-keywords');
		  var flattened = tagField.value.replace(/\s+/g,'');
		  var prefix = flattened.charAt(flattened.length-1) != "," && tagField.value != "" ? ", " : "";
		  tagField.value = tagField.value + prefix + tag;
		}
		{/literal}
		-->
		</script>	
		
		<form id="new_topic_form" class="question" method="post" action="handle-submit.php">
  		<fieldset>
		    <legend><strong>1)</strong> I have a...</legend>
		    <div style="float: right; width:240px" id="live_results">
		      <p><strong>These topics may help:</strong></p>
	        <ul id="suggestions">
        		{foreach from=$suggested key=i item=topic}
        		<li><a href="topic.php?id={$topic.id|urlencode}">{$topic.title}</a></li>
        		{/foreach}
		      </ul>
	      </div>
        <ul class="rows t-al">
          <li>
  		      <select name="style" style="width: 400px">
  		        <option value="question"{if $style == "question"} selected="selected"{/if}>Question that needs an answer</option>
  		        <option value="idea"{if $style == "idea"} selected="selected"{/if}>Idea that I'd like to share</option>
  		        <option value="problem"{if $style == "problem"} selected="selected"{/if}>Problem that needs solving</option>
  		        <option value="talk"{if $style == "talk"} selected="selected"{/if}>Discussion I want to start</option>
  		      </select>
		      </li>
		      <li>
    		    <label id="question_prompt" class="prompt"{if $style != "question"} style="display:none;"{/if}>What's your question? (One or two paragraphs work best.)</label>
  		      <label id="idea_prompt" class="prompt"{if $style != "idea"} style="display:none;"{/if}>Tell us about this idea. (One or two paragraphs work best.)</label>
  		      <label id="problem_prompt" class="prompt"{if $style != "problem"} style="display:none;"{/if}>What seems to be the problem? (One or two paragraphs work best.)</label>
  		      <label id="talk_prompt" class="prompt"{if $style != "talk"} style="display:none;"{/if}>What's on your mind? (One or two paragraphs work best.)</label>
  		      <textarea id="topic-additional-detail" name="additional_detail" onfocus="live_search.start(this, 'suggestions')" onblur="live_search.stop()" rows="6" cols="36" style="width: 400px"></textarea>  		        
		      </li>
		      <li>
  		      <label>Give your <span class="dyn_style">{$friendly_style}</span> a great title:</label>
  		      <input id="topic-subject" name="subject" value="{$subject}" type="text" style="width: 400px" />
		        <div class="helper">
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
			      <input type="checkbox" id="product_{$i}" name="product[]" value="{$product.name}" {if $product.selected}checked="checked"{/if} />
  			    <label for="product_{$i}">{$product.name}</label>
  			  </li>
  			{/foreach}
	      </ul>
  		</fieldset>

  		<fieldset>
	      <legend><strong>3)</strong> Tag it with words...</legend>
        
	      <div id="new_topic_tags" class="t-al">
	        <label for="topic_keywords">Add words that describe your <span class="dyn_style">question</span> (optional)</label>
	        <textarea class="text" id="topic_keywords" name="keywords" rows="2" cols="40"></textarea>
	        <small class="helper">Comma-separated (e.g. hot dogs, cake, pie)</small>
	        <p>Or choose from these popular tags:</p>
          {foreach from=$top_tags key=i item=tag}
  		      <span class="tag_toggle"><a href="#" id="tag_{$i}" onclick="addTag('{$tag}'); return false;">{$tag}</a></span>{if $i+1 < $top_tags_count}, {/if}
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
    			               var target = window.event ? window.event.srcElement : event.target;
    			               target.src='images/' + target.id + '_on.png';
    			               var emoticonElem = document.getElementById('emoticon');
    			               emoticonElem.value=target.id">
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
  			<button style="height:40px; font-size:1.4em;border:3px solid #ccc; padding: 5px 10px;">Post your topic</button>
  		</fieldset>
	  </form>

	</div><!-- #content -->
</div><!-- #container -->

{include file="footer.t"}
