{* Smarty *}

{include file="lite-header.t"}

<h1>Welcome to your Satisfaction Sprinkles</h1>

{if $errors}
<div class="error-box">
The site you entered could not be found on Satisfaction. Try again?
</div>
{/if}

<h2>Start by finding your Satisfaction site.<br />
Then it's only two more simple steps and you're good to go.</h2>

<p>
What is the address of your company's Satisfaction site?
<form action="handle-admin-findsite.php" method="POST">

<table style="padding: 0pt 8pt;">
<tr>
  <td class="form-label oneliner">http://getsatisfaction.com/</td>
  <td><input name="site" class="admin" /></td>
</tr>
<tr>
  <td class="form-label oneliner">OAuth Consumer Key:</td>
  <td><input name="oauth_consumer_key" class="admin" /></td>
</tr>
<tr>
  <td class="form-label oneliner">OAuth Consumer Secret:</td>
  <td><input name="oauth_consumer_secret" class="admin" /></td>
</tr>
<tr>
  <td class="form-label oneliner">Sprinkles URL (this page):</td>
  <td><input name="sprinkles_root_url" value="{$script_uri}" class="admin" /></td>
</tr>
<tr>
<td></td>
<td>
  <button type="submit">Find site</button>
</td>
</tr>
</table>

</form>
</p>

{include file="footer.t"}
