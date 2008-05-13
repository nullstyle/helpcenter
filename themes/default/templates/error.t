{include file="header.t"}

<div id="container">
	<div id="content">
	
<h2>It looks like something went wrong.</h2>

<h3>Start over at the {$company_name} <a href="{$sprinkles_root_url}">Help Center Home Page</a>.</h3>

<p>
Or <a href="http://getsatisfaction.com/satisfaction/products/satisfaction_help_center">
report this problem
</a>
to Get Satisfaction.

{if $error_msg}
Include this error message with your post:
<pre>
{$error_msg}
</pre>
{/if}
</p>


	</div><!-- #content -->
</div><!-- #container -->


{include file="footer.t"}
