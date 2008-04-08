{* Smarty *}

{include file="lite-header.t"}

<h1>Welcome to your Get Satisfaction Sprinkles</h1>

{if $msg == 'missing_sprinkles_root_url'}
<div class="error-box">
You must enter a local URL of this installation of the Get Satisfaction 
Instant-On Help Center. It is enough to copy the URL of this page.
</div>
{elseif $msg == 'missing_company_sfnid'}
<div class="error-box">
You must enter a company site that already exists on Get Satisfaction.
</div>
{elseif $msg == 'missing_oauth'}
<div class="error-box">
You must enter your OAuth consumer key and secret. These should have been given
to you when you downloaded the Instant-On Help Center from Get Satisfaction.
</div>
{/if}

<h2>Start by finding your Get Satisfaction site.<br />
Then it's only two more simple steps and you're good to go.</h2>

<p>
What is the address of your company's Get Satisfaction site?
<form action="handle-admin-findsite.php" method="POST">

<table style="padding: 0pt 8pt;">
<tr>
  <td class="form-label oneliner">http://getsatisfaction.com/</td>
  <td><input class="admin" name="company_sfnid" value="{$company_sfnid}" /></td>
</tr>
<tr>
  <td class="form-label oneliner">OAuth Consumer Key:</td>
  <td><input class="admin" name="oauth_consumer_key" value="{$oauth_consumer_key}" /></td>
</tr>
<tr>
  <td class="form-label oneliner">OAuth Consumer Secret:</td>
  <td><input class="admin" name="oauth_consumer_secret" value="{$oauth_consumer_secret}" /></td>
</tr>
<tr>
  <td class="form-label oneliner">Sprinkles URL (this page):</td>
  <td><input class="admin" name="sprinkles_root_url" value="{$sprinkles_root_url}" /></td>
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
