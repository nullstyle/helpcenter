{include file="header.t"}

<script type="text/javascript" src="sprinkles.js"></script>

<script type="text/javascript">
<!--
{literal}
function updateSuggestions(queryText) {
  var suggestElem = document.getElementById('suggestions');
  var url = 'topic-suggestions.php?query=' + queryText; // FIXME: url-encode
  getHTMLAJAXAsync(url, function (suggestions) {
    var absentSuggestElem = document.getElementById('absent-suggestions');
    var presentSuggestElm = document.getElementById('suggestions-title');
    if (trim(suggestions)) {
      suggestElem.innerHTML = suggestions;
      absentSuggestElem.style.display = 'none';
      suggestElem.style.display = 'block';
      presentSuggestElm.style.display = 'block';
    } else {
      absentSuggestElem.style.display = 'block';
      suggestElem.style.display = 'none';
      presentSuggestElm.style.display = 'none';
    }
 });
}
{/literal}
-->
</script>

<div id="container">
	<div id="content">

		<h1>Contact Us</h1>
		<p>
		  Can't find the answer in our <a href="discuss.php">Discussions</a> or 
		  <a href="discuss.php?style=question">FAQ</a>? Contact us:
		</p>
    <br />
    
    
		<form action="handle-contactus.php" method="post">
      <fieldset>
        <div style="float: right; width:240px" id="live_results">
          <p id="absent-suggestions" class="light">We'll use the summary of your issue to search for similar topics that might be helpful.</p>
    		  <p id="suggestions-title" style="display:none">Do any of these help?</p>
      		<ul id="suggestions"><li></li></ul>
        </div>
        <ul class="rows">
          <li>
        		<label style="font-size:1.2em;display:block;margin-bottom:0.5em">Summary of your issue</label>
        		<input name="summary" style="width:420px; padding:3px 0" onkeyup="updateSuggestions(this.value)" />            
          </li>
          <li>
        		<label style="font-size:1.2em;display:block;margin-bottom:0.5em">Tell us the details: (optional)</label>
        		<textarea rows="4" cols="40" style="width:420px" name="observed" onclick="this.focus();this.select()">What did you do? What did you expect to happen? What actually happened?</textarea><br />
          </li>
          <li>
            <label style="font-size:1.2em;display:block;margin-bottom:0.5em">This is how I feel about it: (optional)</label>
        		<input name="feeling" style="width:420px; padding:3px 0" /><br />
        		<small>140 characters or less please</small>
          </li>
        </ul>
      </fieldset>
      
      <fieldset>
        <legend>Some information about yourself</legend>
        <ul class="rows">
          <li>
            <label>First and last name:</label><br />
            <input class="contactus" style="width:180px" name="name" />
          </li>
          <li>
            <label>Email address:</label><br />
            <input class="contactus" style="width:180px" name="email" />    
          </li>
          <li>
            <label>Phone number: (optional)</label><br />
            <input class="contactus" style="width:180px" name="phone" />
          </li>
          <li>
            <button class="align: center;" type="submit">Send it</button>
          </li>
        </ul>
      </fieldset>
		</form>

	</div><!-- #content -->
</div><!-- #container -->

{include file="footer.t"}

