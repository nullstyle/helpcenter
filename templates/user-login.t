{include file="header.t"}

{include file="topnav.t"}

<h1>Login OR Sign Up</h1>

<table>
<tr>
<td>
<div>
<strong>Login</strong> with your Satisfaction account
<table>
<tr>
<td>
Email Address
</td>
<td>
<input name="user_email" />
</td>
</tr>
<tr>
<td>
Password
</td>
<td>
<input name="password" />
</td>
</tr>
<tr>
<td></td>
<td>
<input type="submit" value="Login" />
<div><a href="dead-end.php">Password gone missing?</a></div>
</td>
</tr>
</table>
</div>
<hr />
<div>
<strong>OR Sign up</strong> for a new account
<table>
<tr>
<td>
Email Address
</td>
<td>
<input name="user_email" />
</td>
</tr>
<tr>
<td></td>
<td>
<input type="submit" value="Sign Up" />
<div>
<strong>Yep, that's it</strong>. We'll send you a confirmation email
with an automatically generated password.
</div>
<hr />

<strong>OR</strong> add your own details now:
</td>
</tr>
<tr>
<td>
Password
</td>
<td>
<input name="password" />
</td>
</tr>
<tr>
<td>
User Name
</td>
<td>
<input name="user_name" />
</td>
</tr>
<tr>
<td>
Your Icon
</td>
<td>
<table>
<tr>
<td>
<img src="xxx" />
</td>
<td>
<input type="file" name="user_icon" value="Choose your own" />
<br />
OR randomize your icon
</td>
</tr>
</table>
<input type="submit" value="OK, now sign up" />
</td>
</tr>
</table>
</div>
</td>
<td>
<div id="what-satisfaction">
<h5>What is Satisfaction anywho?</h5>

<p>Satisfaction is an open discussion-based system providing better
customer service, with or without company involvement. Right now
you're using an extension of the Satisfaction parent site. This is
just one tiny branch off a whole forest of customer service <a
href="http://www.getsatisfaction.com/">Satisfaction</a>.
</p>
</div>
</td>
</tr>
</table>

{include file="footer.t"}
