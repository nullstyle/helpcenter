<br style="clear: right;" />

</div>

<!-- into the blue -->

<div class="gutter">

  <div class="byline">
    {include file="powered-by.t"}
  </div>

{if !$no_admin_link}
  <div style="padding: 4px">
    <a href="admin.php">
{if $current_user.sprinkles_admin}Admin page
{else}Admin sign-in
{/if}
</a>
  </div>
{/if}
</div>

</div>
</body>
</html>
