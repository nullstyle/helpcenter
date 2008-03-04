{* Smarty *}

{if $site_configured}
{include file="header.t"}
{else}
{include file="lite-header.t"}
{/if}

<h1>Welcome to the Admin Page</h1>

<hr />

<p>
Only the creator of this Instant on Help Center, and those admin designated
by the creator, can access this page. Authorize your Get Satisfaction
account to access this page, or sign up for an account.
</p>

<button onclick="location='handle-user-login.php?return=admin.php'">Go to Get Satisfaction</button>

<p>
To gain access to this page you will need to be approved by the creator of
this Instant on Help Center and have an account over at Get Satisfaction.
Don't worry! Sign up takes less than a minute, and we promise never to spam
you or share your information without your authorization. Once you're
logged in, we'll also ask you to authorize your account for this site, so
you never have to see this message again.
</p>


{include file="footer.t"}
