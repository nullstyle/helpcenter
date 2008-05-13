{include file="header.t"}

<div id="container">
  <script type="text/javascript">
  <!-- //
  {literal}
    update_preview = function() {
      var author_name_elem = document.getElementById('author_name');
      var auther_name_input = document.getElementById('sender_name');
      if(author_name_elem && auther_name_input.value != "") {
        author_name_elem.textContent = auther_name_input.value;
      } else if (author_name_elem) {
        author_name_elem.textContent = "(your name goes here)"
      }
    }
  {/literal}
  // -->
  </script>
	<div id="content">
  	<h1>Email this {$topic_head.topic_style} to friends</h1>

  	<form action="handle-share-topic.php">
  	  <div><input type="hidden" name="id" value="{$topic_id}" /></div>
  	  <ul class="rows t-al">
  	    <li class="clearfix">
  	      <label for="from_email">Your email:</label>
    	    <input name="from_email" />
    	  </li>
  	    <li class="clearfix">
    	    <label for="to_email">Email to:</label>
  	      <input name="to_email" />
          <small class="helper">Comma-separate multiple e-mail addresses.</small>
        </li>
      </ul>
      <div id="share-preview">

      	{if !$user_name}
      	<ul class="rows t-al">
    	    <li class="clearfix">
          	<label for="sender_name">Enter your name:</label>
          	<input name="sender_name" id="sender_name" onkeyup="update_preview()" />
      	  </li>
        </ul>
      	{else}
      	<div><input name="sender_name" id="sender_name" type="hidden" value="{$current_user.fn}" /></div>
      	{/if}
        
        <p id="email_message">{if !$user_name}
        <strong id="author_name" class="small-note">(your name goes here)</strong>
        {else}
        {$user_name}
        {/if}
        thinks you might be interested in this {$topic_head.topic_style} from {$company_name}:</p>
              	
      	<ul class="rows t-al">
    	    <li class="clearfix">
          	<label for="personal_message">Add a personal note:</label>
          	<textarea name="personal_message" id="personal_message"></textarea>
      	  </li>
        </ul>
        <br />
        <br />
        <hr />
        <br />

        <p><strong>{$topic_head.title}</strong></p>
        
        <p>{$topic_head.content}</p>

        <p>&mdash;{$topic_head.author.name} asked this on {$topic_head.published|date_format:"%B %e, %y"}</p>
      </div>
      

      <button>Send it</button> OR <a href="topic.php?id={$topic_id}">Cancel and go back to the topic</a>
      
  	</form>

	</div><!-- #content -->
</div><!-- #container -->

{include file="sidebar.t"}

{include file="footer.t"}

