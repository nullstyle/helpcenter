{* Smarty *}

{if $first_config}
{include file="lite-header.t"}
{else}
{include file="header.t"}
{/if}

<div class="right" style="margin-top: 3pt; ">Hello <strong>{$user_name}</strong> from {$company_name}.
  Are you someone else?
  <a href="admin-login.php">Sign in as you</a>.
  <a href="handle-user-logout.php">Sign out</a>.
</div>

<h1>Admin Page</h1>

{if $errors}
<div class="error-box">
Your settings could not be saved. Please correct the errors below.
</div>

{else}

{if $hooked_msg}
<div class="message-box">
<strong>Got it!</strong> <br />
Your Sprinkles site is now hooked into: {$company_url}.
</div>
{/if}

{if $new_admins}
<div class="message-box">
<strong>Done!</strong>
{foreach from=$new_admins key=i item=name}
{if $i==count($new_admins)-1 && $i > 0} and {/if}
{$name}{if $i<(count($new_admins)-2)}, {/if} {/foreach} 
may now come to {$sprinkles_root_url}
and sign in as an admin and authenticate their account. Go let them know!
</div>
{/if}
{/if}

<div class="sidepane">
<div class="sidebar ">
<h3>What is this?</h3>

<p>
The Instant on Help Center (code named Sprinkles) is the first application 
built on top of the Get Satisfaction API. Now any company can Get Satisfaction 
on their site, under their terms while continuing to benefit from the network 
of companies and products on Get Satisfaction.
</p>

<h4>Need help? Want to hack it?</h4>
<p>
Visit the <a href="http://code.google.com/p/getsatisfaction/">API Project Home Page</a>
Talk about it on the <a href="http://code.google.com/p/getsatisfaction/w/list">Wiki</a>
</p>

<h4>Get help on
<a href="http://getsatisfaction.com/satisfaction/products/satisfaction_satisfaction_api">
Get Satisfaction</a></h4>
<ul>
{foreach from=$sprinkles_tagged_topics key=i item=topic}
<li> <a href="topic.php?id={$topic.id|urlencode}">{$topic.title}</a> </li>
{/foreach}
</ul>

</div>
</div>

<h2>Edit your current settings</h2>

<form action="handle-admin.php" method="POST" enctype="multipart/form-data">
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
{if $invalid.admin_users_str}
<div class="error-message">
You may not remove yourself as an admin!
</div>
{/if}
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
