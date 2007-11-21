{* Smarty *}

<html>
<head>
<link rel="stylesheet" type="text/css" href="sprinkles.css">
</head>
<body id="contactus">

<div id="content">
<div id="backdrop">

{include file="header.t"}

<h1>Contact Us</h1>

Can't find the ansewr in our <a href="discuss.php">Discussions</a> or 
  <a href="faq.php">FAQ</a>? Contact us:

<table>
<tr>
<td class="form-label"> * First and last name </td>
<td> <input class="contactus" name="name" /> </td>
</tr>
<tr>
<td class="form-label"> *Email address </td>
<td> <input class="contactus" name="email" /> </td>
</tr>
<tr>
<td class="form-label"> Phone number </td>
<td> <input class="contactus" name="phone" /> </td>
</tr>
</table>

<div>
<h3>Summary of your issue</h3>
<input name="summary" />
</div>

<div>
<h3>This is what I DID</h3>
<textarea rows="7" cols="40" name="action">
</textarea>
</div>

<div>
<h3>This is what I EXPECTED to happen</h3>
<textarea rows="7" cols="40" name="expectation">
</textarea>
</div>

<div>
<h3>This is what ACTUALLY happened</h3>
<textarea rows="7" cols="40" name="observed">
</textarea>
</div>

<div>
<h3>This is how I feel about it in 140 characters or less</h3>
<input name="feeling" />
</div>

<button type="submit">Send it</button


</div>

{include file="footer.t"}

</div>
</body>
</html>
