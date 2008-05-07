{include file="header.t"}

<div id="container">
	<div id="content">
	
		{include file="topic-box.t"}
		{include file="taglist-box.t"}

		<div class="helpstart-topic-list" style="clear:both">
			<h4>Latest customer topics</h4>
      <div class="topic-list mixed">
			{foreach from=$entries key=i item=topic}
        {include file="mixed-topic-list.t"}
			{/foreach}
			</div>
			<br />
			<a href="discuss.php">See more topics</a>
		</div><!--end helpstart-topic-list-->

	</div><!-- #content -->
</div><!-- #container -->

{include file="sidebar.t"}
<div class="sidebar">
  <h3>We support open communitcation</h3>
  <a href="http://www.ccpact.com"><img src="{$sprinkles_root_url}/images/ccpact_badge.png" /></a>
</div>

{include file="footer.t"}
