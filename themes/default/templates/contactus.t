{include file="header.t"}


<div id="container">
	<div id="content">


		<h1>Contact Us</h1>

		<p>Can't find the answer in our <a href="discuss.php">Discussions</a> or 
		  <a href="faq.php">FAQ</a>? Contact us:
		</p>

		<form action="handle-contactus.php" method="POST">

		<div id="daothPane"
		     style="float: right; border: 1px solid black; width: 270pt; padding: 6pt;">
		<h2>Do any of these help?</h2>

		<p id="absent-suggestions" class="light">
		We'll use the summary of your issue to search for similar topics that might be helpful.
		</p>

		<ul id="suggestions">
		</ul>
		<div class="float-right">
		</div>
		</div>

		<div style="width:270pt;">
		<table width="100%">
		<tr> <td class="form-label"> <img src="images/required.png" alt="*" />
		       First and last name </td>
		     <td> <input class="contactus" name="name" /> </td>
		</tr>
		<tr> <td class="form-label"> <img src="images/required.png" alt="*" />
		       Email address </td>
		     <td> <input class="contactus" name="email" /> </td>
		</tr>
		<tr> <td class="form-label"> Phone number </td>
		<td> <input class="contactus" name="phone" /> </td>
		</tr>
		</table>

		<script type="text/javascript" src="sprinkles.js"></script>

		<script type="text/javascript">
		<!--
		function updateSuggestions(queryText) {ldelim}
		  var suggestElem = document.getElementById('suggestions');
		  var url = 'topic-suggestions.php?query=' + queryText; // FIXME: url-encode
		  getHTMLAJAXAsync(url, function (suggestions) {ldelim}
		    var absentSuggestElem = document.getElementById('absent-suggestions');
		    if (trim(suggestions)) {ldelim}
		      suggestElem.innerHTML = suggestions;
		      absentSuggestElem.style.display = 'none';
		    {rdelim} else {ldelim}
		      absentSuggestElem.style.display = 'block';
		      suggestElem.style.display = 'none';
		    {rdelim}
		  {rdelim});
		{rdelim}
		-->
		</script>

		<div id="contact-form">
		<h4> <img src="images/required.png" alt="*" /> Summary of your issue</h4>
		<input name="summary" onblur="updateSuggestions(this.value)" />

		<h4>This is what I DID</h4>
		<textarea rows="7" cols="40" name="action">
		</textarea>

		<h4>This is what I EXPECTED to happen</h4>
		<textarea rows="7" cols="40" name="expectation">
		</textarea>

		<h4>This is what ACTUALLY happened</h4>
		<textarea rows="7" cols="40" name="observed">
		</textarea>

		<h4>This is how I feel about it in 140 characters or less</h4>
		<input name="feeling" />

		<br />
		<button class="align: center;" type="submit">Send it</button

		</div>
		</div>

		</form>

	</div><!-- #content -->
</div><!-- #container -->

{include file="sidebar.t"}

{include file="footer.t"}

