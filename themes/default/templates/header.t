<!-- BEGIN HEADER -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head profile="http://gmpg.org/xfn/11">
	<title>{$company_name} Help Center</title>
	<meta http-equiv="content-type" content="HTML TYPE; charset=utf-8" />
	<link rel="stylesheet" type="text/css" href="themes/default/style.css" />
</head>

<body {if $body_css_id} id="{$body_css_id}"{/if}>

<div id="wrapper" class="hfeed">
  <div id="navigation">
  	{include file="navigation.t"}
  	<ul id="aux-navigation">
  	  <li>{if $user_name}
  		Hi {$user_name} (<a href="minidashboard.php">Your dashboard</a>) | 	{if $user_is_admin}(<a href="admin.php">Admin</a>) | {/if}
  		<a href="handle-user-logout.php?return={$current_url|urlencode}">Log out</a>
  		{else}
  		  {if !$login_page}
  		  <a href="user-login.php?return=minidashboard.php">Log in to view your dashboard</a>
  		  {/if}
  		{/if}</li>
  	</ul>
  </div>
	<div id="header">
	  <a href="http://getsatisfaction.com" class="powered_by"><img src="images/powered_by.png" alt="Powered by Get Satisfaction" /></a>
		<h1 id="helpcenter-title"><span><a href="helpstart.php" title="" rel="home">{$company_name} Help Center</a></span></h1>		
	</div>
<!-- END HEADER -->