{* Smarty *}

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	<title>Help Center Setup</title>
	{literal}
	<style type="text/css">
	  body {margin:0;font: 0.85em/1.2 Arial, Helvetica, sans-serif; text-align: center;}
	  #container { width: 640px; margin: 5% auto;text-align:left;}
	  *{margin:0;padding:0}
	  ul {list-style:none}
	  h1 {font-size:250%;letter-spacing:-1px;text-align:center;color:DodgerBlue;margin-bottom:0.8em;}
	  /* ----  Forms  ----  */
    form { padding: 20px 100px; background:#EEE; border-top:5px solid #999;}
    fieldset{margin-bottom:2.5em;border:none;}
    legend{font-size:1.3em;display:block;margin:0 0 1em 0; text-indent:0; color:black;}
    button {overflow:visible; background:#99CC66; border:1px solid #75A848;color:#FFFFCC !important;cursor:pointer;padding:3px 6px;font-size:135%; }
    button:hover, .button:hover {border-color:#535353;}
    ul.rows li{margin:1.5em 0;}
    /* top aligned labels */
    .t-al label{display:block; padding-bottom: 0.3em; font-size:140%; font-weight:bold; color:#444}
    .t-al input{width:280px;padding:4px 0;}
    .t-al .helper{clear:both;font-size:0.9em;color:gray;display:block;}
    .t-al .error-message {color:red; font-weight:bold; margin:1em 0 0.5em 0;}    
	</style>
	{/literal}
</head>

<body>

<div id="container">
  
  <h1>Welcome to Get Satisfaction Help Center</h1>

  {if $msg == 'missing_sprinkles_root_url'}
  <div class="error-box">
  You must enter a local URL of this installation of the Get Satisfaction Help Center. It is enough to copy the URL of this page.
  </div>
  {elseif $msg == 'missing_company_sfnid'}
  <div class="error-box">
  You must enter a company site that already exists on Get Satisfaction.
  </div>
  {elseif $msg == 'missing_oauth'}
  <div class="error-box">
  You must enter your OAuth consumer key and secret. These should have been given
  to you when you downloaded the Help Center from Get Satisfaction.
  </div>
  {/if}

  <form action="handle-admin-findsite.php" method="POST">
    
    <h2>Step 1. Configure Help Center for your site.</h2>
    <p style="text-indent:74px;color:#666;">Then it's only two more simple steps and you're good to go.</p>
    
    <ul class="rows t-al" style="padding-left:75px">
      <li class="clearfix">
        <label>Get Satisfaction URL</label>
        <p style="color:#666">http://getsatisfaction.com/</p>
        <input class="admin" name="company_sfnid" value="{$company_sfnid}" />
      </li>
      <li class="clearfix">
        <label>OAuth Consumer Key</label>
        <input class="admin" name="oauth_consumer_key" value="{$oauth_consumer_key}" />
      </li>
      <li class="clearfix">
        <label>OAuth Consumer Secret</label>
        <input class="admin" name="oauth_consumer_secret" value="{$oauth_consumer_secret}" />
      </li>
      <li class="clearfix">
        <label>Help Center URL (this page)</label>
        <input class="admin" name="sprinkles_root_url" value="{$sprinkles_root_url}" />
      </li>
      <button type="submit">Continue to authorization</button>
    </ul>
  </form>
</div>

</body>
</html>