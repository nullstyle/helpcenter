<html>
<head>
<link rel="stylesheet" type="text/css" href="sprinkles.css">
</head>
<body>
<div>
Company logo
Help Start
FAQs
Contact Us
Discussions
View you dashboard
</div>

<h1>Help Start</h1>

<div class="question">
<h2>How can we help you?</h2>

<form action>
<input name="question" /> <button type="submit">Go</button>
<img alt="Powered by Satisfaction" src="satisfaction-logo.gif" />
</form>
</div>

<div>
<h2>Get answers (check out these recently answered topics)</h2>

<ul>
<?
foreach (array("a", "b", "c") as $topic) {
  print ("<li>" . $topic. "</li>\n\n");
}
?>
</ul>

<h2>We're here to help</h2>

... people ...
</div>

<div class="sidebar">
... contact info...
</div>
</body>
</html>