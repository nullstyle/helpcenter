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
		</div><!--end helpstart-topic-list-->

	</div><!-- #content -->
</div><!-- #container -->

{include file="sidebar.t"}

{include file="footer.t"}
