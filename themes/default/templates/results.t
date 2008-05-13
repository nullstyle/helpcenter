{include file="header.t"}

<div id="container">
	<h1>{$topic_count} results found for "{$query}"</h1>
	
	<form action="results.php">
  	 <ul class="rows t-al">
  	   <li>
  	     <label for="search-input">Search:</label>
         <select name="style" style="width:110px;">
    	     <option value="">All Topics</option>
    	     <option value="question"{if $style == "question"} selected="selected"{/if}>Questions</option>
    	     <option value="problem"{if $style == "problem"} selected="selected"{/if}>Problems</option>
    	     <option value="idea"{if $style == "idea"} selected="selected"{/if}>Ideas</option>
    	     <option value="talk"{if $style == "talk"} selected="selected"{/if}>Discussions</option>
  	     </select>
  	     &nbsp;&nbsp;&nbsp;for:
  	     <input type="text" id="search-input" name="query" value="{$query}" />
  	     <button>Search</button>
  	   </li>
  	 </ul>
	</form>
	
  <div class="topic-list mixed">
		{foreach from=$topics key=i item=topic}
      {include file="mixed-topic-list.t"}
		{/foreach}
	</div>
	
  {include file="pager.t"}
	
</div><!-- #container -->

{include file="footer.t"}