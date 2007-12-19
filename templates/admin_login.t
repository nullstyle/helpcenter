{* Smarty *}

{if $first_config}
{include file="lite-header.t"}
{else}
{include file="header.t"}
{/if}

<h1>Welcome to your Satisfaction Sprinkles</h1>

<hr />

<div style="float:right;">Not the right company? Go back and try again.</div>

<h3>Excellent, you chose the Satisfaction Twitter site.</h3>

<p>Next, Login to your Twitter Satisfaction Admin account.</p>

{if $message}
<div class="error-box">{$message}</div>
{/if}

<form action="handle_admin_login.php">
<table width="100%">
<tr>
<td>
<table>
<tr>
<td class="form-label">Choose your account:</td>
<td> <select name="username">
{foreach from=$accts key=i item=acct}
       <option value="{$acct.name}">{$acct.name}</option>
{/foreach}
     </select>
</td>
</tr>
<tr>
<td class="form-label">Password:</td>
<td><input name="password" type="password" /></td>
</tr>
<tr>
<td></td>
<td><button type="submit">Login</button> <br />
Password gone missing? </td>
</tr>
</table>
</form>

</td>
<td style="width: 200pt;">Can't find your account? Ask one of the people listed here to give you Admin status on Satisfaction <br />
<strong>OR</strong> <a href="">apply to be an Admin here</a>. </td>
</tr>
</table>

{include file="footer.t"}
