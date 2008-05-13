<!--BEGIN SIDEBAR -->
	<div id="primary" class="sidebar">
		<ul class="xoxo">
			{if $view=='activity'}
			<li id="dashboards">
				<h3>More on this user</h3>
				<ul>
					<li>{if $user_is_self}
					<a href="http://getsatisfaction.com/me">See your Get Satisfaction dashboard</a>
					{else}
					<a href="http://getsatisfaction.com/people/{$username_canonical}">See their full dashboard on Get Satisfaction</a>
					{/if}
					</li>
				</ul>
			</li>
			{/if}
      {if !$topic_head}
			<li id="topic-types">
				<h3>Find Topics</h3>
				<ul>
					<li><a href="discuss.php?style=question">Questions</a> ({$totals.questions})</li>
					<li><a href="discuss.php?style=problem">Problems</a> ({$totals.problems})</li>
					<li><a href="discuss.php?style=idea">Ideas</a> ({$totals.ideas})</li>
					<li><a href="discuss.php?style=talk">Discussions</a> ({$totals.talk})</li>
					<li id="search">
						<form id="searchform" method="get" action="results.php">
							<div>
								<input id="s" name="query" class="text-input" type="text" value="" tabindex="1" accesskey="S" />
								<input id="searchsubmit" class="button" type="submit" value="Search" tabindex="2" />
							</div>
						</form>
					</li>
				</ul>
			</li>
      {/if}
			{if $products}
			<li id="product-list">
				<h3>
				{if $filter_style == 'question'}
				  Questions about products
				{elseif $filter_style == 'idea'}
				  Ideas for products
				{elseif $filter_style == 'problem'}
				  Problems with products
				{elseif $filter_style == 'talk'}
				  Discussions about products
				{else}
				  Topics about products
				{/if}</h3>
				<ul>
				{foreach from=$products key=i item=product}
					<li {if $product.name == $filter_product.name}class="on"{/if}><a href="discuss.php?product={$product.uri}{if $filter_style}&amp;style={$filter_style}{/if}">{$product.name}</a></li>
				{/foreach}				
				</ul>
			</li>
			{/if}
		</ul>
	</div><!-- #primary .sidebar -->

	{if $related_topics}
	<div id="secondary" class="sidebar">
		<ul class="xoxo">
		  <li>
		    <h3>Related Topics from across Satisfaction:</h3>
			  <ul>
			  {foreach from=$related_topics key=i item=topic}
  			  <li><a href="{$topic.at_sfn}">{$topic.title}</a> in <strong>{$topic.company.fn}</strong></li>
  			{/foreach}
			  </ul>
		  </li>
		</ul>
	</div><!-- #secondary .sidebar -->
	{/if}

<!-- END SIDEBAR-->