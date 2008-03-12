<ul class="topnav">
<li><a href="helpstart.php">Help Start</a></li>
<li><a href="faq.php">FAQs</a></li>
<li><a href="contactus.php">Contact Us</a></li>
<li><a href="discuss.php">Discussions</a></li>
<li class="small">
{if $user_name}
Hi {$user_name} (<a href="minidashboard.php">Your dashboard</a>) |
<a href="handle-user-logout.php?return={$current_url|urlencode}">Log out</a>
{else}
  {if !$login_page}
  <a href="user-login.php?return=minidashboard.php">Log in to view your dashboard</a>
  {/if}
{/if}
</li>
</ul>
