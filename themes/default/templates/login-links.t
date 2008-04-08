{* Smarty *}

<div class="small float-right">
{if $user_name}
Hi {$user_name} (<a href="minidashboard.php">Your dashboard</a>) |
<a href="handle-user-logout.php?return={$current_url|urlencode}">Log out</a>
{else}
  {if !$login_page}
  <a href="user-login.php?return=minidashboard.php">Log in to view your dashboard</a>
  {/if}
{/if}
</div>
