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
    <br /><br />

  	<form action="handle-share-topic.php">
  	  <input type="hidden" name="id" value="{$topic_id}" />
  	  
  	  <label for="from_email">Your email:</label><br />
    	<input name="from_email" style="width:250px" /><br /><br />
    	
    	<label for="to_email">Email to:</label><br />
  	  <input name="to_email" style="width:250px" /><br />
      <small>Comma-separate multiple e-mail addresses.</small><br /><br />

      <div id="share-preview">

      	{if !$user_name}
      	<label>Enter your name:</label><br />
      	<input name="sender_name" id="sender_name" style="width:250px" onkeyup="update_preview()" /><br /><br />
      	{else}
      	<div><input name="sender_name" id="sender_name" type="hidden" value="{$current_user.fn}" /></div>
      	{/if}
        
        <p id="email_message">{if !$user_name}
        <strong id="author_name" class="small-note">(your name goes here)</strong>
        {else}
        {$user_name}
        {/if}
        thinks you might be interested in this {$topic_head.topic_style} from COMPANY NAME:</p>

      	<label>Add a personal note:</label><br />
      	<textarea name="personal_message" id="personal_message" style="width:250px"></textarea>
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

