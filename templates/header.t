{include file="lite-header.t"}

<div class="header">

{include file="topnav.t"}

<a href="helpstart.php">
<img class="header-logo" src="logo.php" alt="{ $company_name } Powered by: Satisfaction" />
</a>

{if $username}
<a href="handle-user-logout.php?return={$current_url}">Logout</a>
{/if}

<div style="clear: right"> </div>
</div>
