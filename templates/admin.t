{* Smarty *}

{if $first_config}
{include file="lite-header.t"}
{else}
{include file="header.t"}
{/if}

<div class="right">Hello <strong>{$user_name}</strong> from {$company_name}.
  Are you someone else?
  <a href="admin_login.php">Sign in as you</a>.
  <a href="sign_out.php">Sign out</a>.
</div>

<h1>Admin Page</h1>

{if $errors}
<div class="error-box">
Your settings could not be saved. Please correct the errors below.
</div>
{/if}

{if $hooked_msg}
<div class="message-box">
<strong>Got it!</strong> <br />
Your Sprinkles site is now hooked into: {$company_url}.
</div>
{/if}

{if $admins_changed}
<div class="message-box">
<strong>Done!</strong>
{foreach from=$admin_users key=i item=user}
{if $i==count($admin_users)-1 && $i > 0} and {/if}
{$user.username}{if $i<count($admin_users)-2}, {/if} {/foreach} 
may now come to [TBD: this url]
and sign in as an admin and authenticate their account. Go let them know!
</div>
{/if}

<div class="sidepane">
<div class="sidebar ">
<h3>Need help?</h3>

Sprinkles is...

<h4>API information</h4>

<h4>Helpful topics from Satisfaction</h4>
<ul class="straight">
<li>xxx</li>
</ul>
</div>
</div>

<h2>Edit your current settings</h2>

<form action="handle_admin.php" method="POST" enctype="multipart/form-data">
<table>
<tr>
<td class="form-label"> Background color </td>
<td> <input class="admin" name="background_color" value="{$settings.background_color}"/>
{if $invalid.background_color}
<div class="error-message">
Background color should indicate a color in RGB hex format: #xxx or #XxXxXx
</div>
{/if}
</td>
</tr>
<tr>
<td class="form-label"> <strong>*Your logo</strong> </td>
<td> <input class="admin" name="logo" type="file" /> </td>
</tr>
<tr>
<td class="form-label"> <strong>*Company contact email</strong> </td>
<td> <input class="admin" name="contact_email" value="{$settings.contact_email}" />
{if $invalid.contact_email}
<div class="error-message">
Contact email must be a valid email address.
</div>
{/if}
 </td>
</tr>
<tr>
<td class="form-label"> Contact Phone Number </td>
<td> <input class="admin" name="contact_phone" value="{$settings.contact_phone}" /> </td>
</tr>
<tr>
<td class="form-label"> Mailing Address </td>
<td> <textarea class="admin" name="contact_address" rows="2">{$settings.contact_address}</textarea> </td>
</tr>
<tr>
<td class="form-label"> Link to a map </td>
<td> <input class="admin" name="map_url" value="{$settings.map_url}" /> </td>
</tr>
<tr>
<td class="form-label"> Add another link </td>
<td> xxx </td>
</tr>
<tr>
<td class="form-label"> Add additional admin </td>
<td>
  <textarea name="admin_users_str" class="admin" rows="2">
{if $settings.admin_users_str}{$settings.admin_users_str}
{else}{foreach from=$admin_users key=i item=user}{$user.username}
{/foreach}
{/if}</textarea>
<p>
Comma or space separated. Example: If the person's GS URL is http://getsatisfaction.com/people/scott then just enter <strong>scott</strong> above.
</p>
</td>
</tr>
<tr>
<td>
</td>
<td>
<button name="save" type="submit" style="margin-top: 6pt;">
{if $site_configured}
Save changes
{else}
OK, Start up Sprinkles!
{/if}
</button>
</td>
</tr>
</table>

</form>

{include file="footer.t"}
