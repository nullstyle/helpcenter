{* Smarty *}

{if $first_config}
{include file="lite-header.t"}
{else}
{include file="header.t"}
{/if}

<div class="right">Hello <strong>{$username}</strong> from {$company_name}.
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

<div class="sidepane">
<div class="sidebar ">
<h3>Need help?</h3>

Sprinkles is...

<div>
API information
</div>

Helpful topics from Satisfaction
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
<td class="form-label">
Set up your FAQ section
</td>
<td>

  <table>
  <tr>
  <td><input id="faq_type_auto" name="faq_type" type="radio" value="auto"> </input></td>
  <td><label for="faq_type_auto">Auto-generate FAQs using Satisfaction</label></td>
  </tr><tr>
   <td></td>
   <td>  Use the most frequented questions from Satisfaction
   </td>
  </tr><tr>
   <td>
    <input id="faq_type_manual" name="faq_type" type="radio" value="manual"> </input>
   </td>
   <td>
    <label for="faq_type_manual"> Manually enter FAQs </label>
   </td>
  </tr><tr>
   <td></td>
    <td>
     Add my own FAQs on Satisfaction and show them here (we'll tell you
     how in an extra step)
    </td>
  </tr>
  </table>

</td>
</tr>
<tr>
<td>
</td>
<td>
<button type="submit" name="save">
{if $site_dirty}
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
