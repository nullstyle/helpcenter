{* Smarty *}

{include file="header.t"}

<h1>Contact Us</h1>

<p>Can't find the answer in our <a href="discuss.php">Discussions</a> or 
  <a href="faq.php">FAQ</a>? Contact us:
</p>

<form action="handle-contactus.php" method="POST">

<div style="width:270pt;">
<table width="100%">
<tr>
<td class="form-label"> <img src="images/required.png" alr="*" />
 First and last name </td>
<td> <input class="contactus" name="name" /> </td>
</tr>
<tr>
<td class="form-label">  <img src="images/required.png" alr="*" />
 Email address </td>
<td> <input class="contactus" name="email" /> </td>
</tr>
<tr>
<td class="form-label"> Phone number </td>
<td> <input class="contactus" name="phone" /> </td>
</tr>
</table>

<div id="contact-form">
<h4> <img src="images/required.png" alr="*" /> Summary of your issue</h4>
<input name="summary" />

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

{include file="footer.t"}
