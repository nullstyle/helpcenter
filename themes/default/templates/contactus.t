{include file="header.t"}

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
        		<label style="font-size:1.2em;" for="summary-input">Summary of your issue</label>
        		<input name="summary" id="summary-input" onfocus="live_search.start(this, 'suggestions')" onblur="live_search.stop()" />            
          </li>
          <li>
        		<label style="font-size:1.2em;" for="observed-input">Tell us the details: (optional)</label>
        		<textarea rows="4" cols="40" id="observed-input" name="observed" onclick="this.focus();this.select()">What did you do? What did you expect to happen? What actually happened?</textarea><br />
          </li>
          <li>
            <label style="font-size:1.2em;" for="feeling-input">This is how I feel about it: (optional)</label>
        		<input name="feeling" id="feeling-input" /><br />
        		<small>140 characters or less please</small>
          </li>
        </ul>
      </fieldset>
      
      <fieldset>
        <legend>Some information about yourself</legend>
        <ul class="rows t-al">
          <li>
            <label for="name-input">First and last name:</label>
            <input name="name" id="name-input" />
          </li>
          <li>
            <label for="email-input">Email address:</label>
            <input name="email" id="email-input" />    
          </li>
          <li>
            <label for="phone-input">Phone number: (optional)</label>
            <input name="phone" id="phone-input" />
          </li>
          <li>
            <button type="submit">Send it</button>
          </li>
        </ul>
      </fieldset>
		</form>

	</div><!-- #content -->
</div><!-- #container -->

{include file="footer.t"}

