{* Smarty *}

{if $site_configured}
{include file="header.t"}
{else}
{include file="lite-header.t"}
{/if}

<h1>Welcome to your Satisfaction Sprinkles</h1>

<hr />

{if !$site_configured}
<div style="float:right;">
<a href="admin_findsite.php">Not the right company? Go back and try again.</a>
</div>

<h3>Excellent, you chose the Satisfaction {$company_name} site.</h3>
{/if}

<p>Next, <a href="handle-user-login.php?return=admin.php">Login to your {$company_name} Satisfaction Admin account</a>.</p>

<!--
{if $message}
<div class="error-box">{$message}</div>
{/if}

<form action="handle-user-login.php">
<input type="hidden" name="return" value="admin.php" />
<table width="100%">
<tr>
<td>
<table>
<tr>
<td class="form-label">Choose your account:</td>
<td> <select name="username">
{foreach from=$accts key=i item=acct}
       <option value="{$acct}">{$acct}</option>
{/foreach}
     </select>
</td>
</tr>
<!--
<tr>
<td class="form-label">Password:</td>
<td><input name="password" type="password" /></td>
</tr>
->
<tr>
<td></td>
<td><button type="submit">Login</button> 
    <p>Password gone missing? xxxLink-somewhere</p> </td>
</tr>
</table>
</form>

</td>
<td style="width: 200pt;">Can't find your account? Ask one of the people listed here to give you Admin status on Satisfaction <br />
<strong>OR</strong> <a href="">apply to be an Admin here</a>. </td>
</tr>
</table>
-->

{include file="footer.t"}
