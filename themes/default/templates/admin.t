{* Smarty *}

{include file="header.t"}

<div id="container">
  
  <div id="content">
    <h1>Admin Page</h1>

    {if $errors}
    <div class="error-box">
      Your settings could not be saved. Please correct the errors below.
    </div>
    {elseif $hooked_msg}
      <div class="message-box">
        <strong>Got it!</strong> <br />
        Your Sprinkles site is now hooked into: {$company_url}.
      </div>
      {elseif $settings_saved}
      <div class="message-box">
        Done! Your settings have been saved.
      </div>
      {/if}

      {if $new_admins}
      <div class="message-box">
        <strong>Done!</strong>
        {foreach from=$new_admins key=i item=name}
        {if $i==count($new_admins)-1 && $i > 0} and {/if}
        {$name}{if $i<(count($new_admins)-2)}, {/if} 
        {/foreach} 
        may now come to {$sprinkles_root_url}
        and sign in as an admin and authenticate their account. Go let them know!
      </div>
    {/if}

    <form action="handle-admin.php" method="post" enctype="multipart/form-data">
      <fieldset>
      <legend>Edit your current settings</legend>
      <ul class="rows left-aligned-labels">
        <li class="clearfix">
          {if $invalid.background_color}
          <div class="error-message">
            Background color should indicate a color in RGB hex format: #xxx or #XxXxXx
          </div>
          {/if}
          <label for="background-input">Background color:</label>
          <input id="background-input" name="background_color" value="{$settings.background_color}"> 
        </li>
        <li class="clearfix">
          <label for="logo-input">Your logo:</label>
          <input name="logo" id="logo-input" size="14" type="file">
          <span class="helper">Max. size 64K</span>
        </li>
        <li class="clearfix">
          <label for="link-input">Logo link:</label>
          <input name="logo_link" id="link-input" value="{$settings.logo_link}">
          <span class="helper">links to helpstart.php by default</span>
        </li>
        <li class="clearfix">
          {if $invalid.contact_email}
          <div class="error-message">
            Contact email must be a valid email address.
          </div>
          {/if}
          <label for="contact-email">Company contact email:</label>
          <input name="contact_email" id="contact-email" value="{$settings.contact_email}"> 
        </li>
        <li class="clearfix">
          <label for="contact-phone">Contact phone number:</label>
          <input name="contact_phone" id="contact-phone" value="{$settings.contact_phone}">
        </li>
        <li class="clearfix">
          <label for="address-input">Mailing address:</label>
          <textarea name="contact_address" id="address-input" rows="2">{$settings.contact_address}</textarea>
        </li>
        <li class="clearfix">
          {if $invalid.admin_users_str}
          <div class="error-message">
            You may not remove yourself as an admin!
          </div>
          {/if}
          <label for="admin-textarea">Add additional admin:</label>
          <textarea name="admin_users_str" id="admin-textarea" rows="2">{if $settings.admin_users_str}{$settings.admin_users_str} {else}{foreach from=$admin_users key=i item=user}{$user.username} {/foreach} {/if}</textarea>
          <span class="helper">
            Comma or space separated
          </span>
        </li>
        <li class="clearfix">
          <button name="save" type="submit" style="margin-top: 6pt;">{if $site_configured} Save changes {else} OK, Start up Sprinkles! {/if}</button>
        </li>
      </ul>
      </fieldset>
    </form>
  </div>  
</div>

<div id="primary" class="sidebar">
  <ul>
    <li>
      <h3>What is this?</h3>
      <p>Help Center is the first application built on top of the Get Satisfaction API. Now any company can Get Satisfaction on their site, under their terms while continuing to benefit from the network of companies and products on Get Satisfaction. </p>
    </li>
    <li>
      <h3>Need help? Want to hack it?</h3>
      <p>Visit the <a href="http://code.google.com/p/getsatisfaction/">API Project Home Page</a> Talk about it on the <a href="http://code.google.com/p/getsatisfaction/w/list">Wiki</a> </p>
    </li>
  </ul>
</div>


{include file="footer.t"}
