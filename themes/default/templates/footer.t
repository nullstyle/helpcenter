<!-- BEGIN FOOTER -->
<br style="clear: both;" />
</div>

<div id="footer">
  <div id="footer_content">
    
    <div id="footer_links">
      <h3>Site Links</h3>
      <ul>
        <li><a href="http://getsatisfaction.com">Get Satisfaction Home</a></li>
        <li><a href="http://getsatisfaction.com/people/new">Sign up for Get Satisfaction</a></li>
        <li><a href="http://blog.getsatisfaction.com">The Get Satisfaction blog</a></li>
        <li><a href="http://www.ccpact.com">The Customer-Company pact</a></li>
      </ul>
    </div>
    
    <div id="footer_about">
      <h3>About</h3>
      <p>
        {if $company_name}{$company_name}<br /><br />{/if}
        {if $contact_phone}{$contact_phone}<br />{/if}
        {if ($contact_email|strip) != ""}<a href="mailto:{$contact_email}">{$contact_email}</a><br /><br />{/if}
        {if ($contact_address|strip) != ""}{$contact_address|nl2br}{/if}
	    </p>
    </div>

    <div id="start_a_topic">
      <ul class="post_links">
        <li><a href="discuss.php?style=question" class="question">Ask a question</a></li>
        <li><a href="discuss.php?style=idea" class="idea">Share an idea</a></li>
        <li><a href="discuss.php?style=problem" class="problem">Report a problem</a></li>
        <li><a href="discuss.php?style=talk" class="talk">Start a discussion</a></li>
      </ul>
    </div>
    
    <div id="blurb">
      <strong style="font-size: 130%">Help Center is powered <br />by Get Satisfaction</strong><br />
      <p>The support network for people to get the most from the products they use. Casual users, dedicated experts, and company employees all come together to answer questions, solve problems, and share ideas.</p>
    </div>

    <p id="footer_copy">
      <a href="http://getsatisfaction.com/terms_of_service">Terms of service</a> | <a href="http://getsatisfaction.com/for_companies/help_center">Learn more about Help Center</a> | <a href="http://getsatisfaction.com/satisfaction/products/satisfaction_help_center">Give us feedback, bug reports, or just say hi</a>
    </p>
  </div>
</div><!-- END FOOTER -->
<!-- rendered in {$page_timer} seconds, with {$api_calls} full api calls and {$cached_api_calls} cached api calls-->
</body>
</html>
