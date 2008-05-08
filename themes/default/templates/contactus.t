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
		<p>Can't find the answer in our <a href="discuss.php">Discussions</a> or <a href="discuss.php?style=question">FAQ</a>? Contact us: </p>
		<form action="handle-contactus.php" method="post">
      <fieldset>
        <div style="float: right; width:240px" id="live_results">
          <p id="absent-suggestions" class="light">We'll use the summary of your issue to search for similar topics that might be helpful.</p>
    		  <p id="suggestions-title" style="display:none">Do any of these help?</p>
      		<ul id="suggestions"><li></li></ul>
        </div>
        <ul class="rows t-al">
          <li>
        		<label style="font-size:1.2em;">Summary of your issue</label>
        		<input name="summary" onkeyup="updateSuggestions(this.value)" />            
          </li>
          <li>
        		<label style="font-size:1.2em;">Tell us the details: (optional)</label>
        		<textarea rows="4" cols="40" name="observed" onclick="this.focus();this.select()">What did you do? What did you expect to happen? What actually happened?</textarea><br />
          </li>
          <li>
            <label style="font-size:1.2em;">This is how I feel about it: (optional)</label>
        		<input name="feeling" /><br />
        		<small>140 characters or less please</small>
          </li>
        </ul>
      </fieldset>
      
      <fieldset>
        <legend>Some information about yourself</legend>
        <ul class="rows t-al">
          <li>
            <label>First and last name:</label>
            <input class="contactus" name="name" />
          </li>
          <li>
            <label>Email address:</label>
            <input class="contactus" name="email" />    
          </li>
          <li>
            <label>Phone number: (optional)</label>
            <input class="contactus" name="phone" />
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

