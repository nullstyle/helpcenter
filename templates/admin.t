{* Smarty *}

{include file="header.t"}

<div class="right">Hello {$username} from {$company_name}.
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
Need help?

Sprinkles is...

API information

Helpful topics from Satisfaction
<ul>
<li>...</li>
</ul>
</div>
</div>

<h2>Edit your current settings</h2>

<form action="handle_admin.php" method="POST">
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
<td> <textarea class="admin" name="contact_address" rows="2"></textarea> </td>
</tr>
<tr>
<td class="form-label"> Link to a map </td>
<td> <input class="admin" name="map_url" /> </td>
</tr>
<tr>
<td class="form-label"> Add another link </td>
<td> xxx </td>
</tr>
</table>

<table>
<tr>
<td>
Set up your FAQ section
</td>
<td>
xxx
</td>
</tr>
<tr>
<td>
</td>
<td>
<button type="submit" name="save">OK, Start up Sprinkles! </button>
</td>
</tr>
</table>

</form>

{include file="footer.t"}
