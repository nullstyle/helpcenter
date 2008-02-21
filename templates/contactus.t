{* Smarty *}

{include file="header.t"}

<h1>Contact Us</h1>

<p>Can't find the answer in our <a href="discuss.php">Discussions</a> or 
  <a href="faq.php">FAQ</a>? Contact us:
</p>

<form action="handle-contactus.php" method="POST">

<div id="daothPane"
     style="float: right; border: 1px solid black; width: 270pt; padding: 6pt; display:none;">
<h2>Do any of these help?</h2>
<ul id="suggestions">
</ul>
</div>

<div style="width:270pt;">
<table width="100%">
<tr> <td class="form-label"> <img src="images/required.png" alt="*" />
       First and last name </td>
     <td> <input class="contactus" name="name" /> </td>
</tr>
<tr> <td class="form-label"> <img src="images/required.png" alt="*" />
       Email address </td>
     <td> <input class="contactus" name="email" /> </td>
</tr>
<tr> <td class="form-label"> Phone number </td>
<td> <input class="contactus" name="phone" /> </td>
</tr>
</table>

<script type="text/javascript">
<!--
var AJAX = function() {ldelim}
  // (IE) XMLHttpRequest is an ActiveXObject in IE
  return {ldelim}
    isLoaded : 2,
    isComplete : 4,
  
  newRequest : function() {ldelim}
    var http_request;
    if(window.XMLHttpRequest) {ldelim} // native XMLHttpRequest object (FireFox, etc.)
      try {ldelim}
        http_request = new XMLHttpRequest();
      {rdelim} catch(e) {ldelim}
        throw ("Failed to create (native) XMLHttpRequest");
      {rdelim}
    {rdelim} else if(window.ActiveXObject) {ldelim} //IE
      try {ldelim}
          http_request = new ActiveXObject("Msxml2.XMLHTTP");
      {rdelim} catch(e) {ldelim}
        try {ldelim}
          http_request = new ActiveXObject("Microsoft.XMLHTTP");
        {rdelim} catch(e) {ldelim}
          throw ("Failed to create (ActiveX) XMLHttpRequest object");
        {rdelim}
      {rdelim}
    {rdelim}
    return http_request;
  {rdelim}
  {rdelim};
{rdelim}();

function getHTMLAJAX(url) {ldelim}
// TBD: async
  var req = AJAX.newRequest();
  req.open('GET', url);
  var result;
  req.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  req.send();
  return req.responseText;
{rdelim}

function trim(str) {ldelim}
  var result = str.replace(/^[\n\r\t]+/, '');
  result = result.replace(/[\n\r\t]+$/, '');
  return result;
{rdelim}

function updateSuggestions(queryText) {ldelim}
  var suggestElem = document.getElementById('suggestions');
  var url = 'proxy.php?query=' + queryText; // FIXME: url-encode
  var suggestions = getHTMLAJAX(url);
  var daothPane = document.getElementById('daothPane');
  if (trim(suggestions)) {ldelim}
    suggestElem.innerHTML = suggestions;
    daothPane.style.display = 'block';
  {rdelim} else {ldelim}
    daothPane.style.display = 'none';  
  {rdelim}
{rdelim}
-->
</script>

<div id="contact-form">
<h4> <img src="images/required.png" alr="*" /> Summary of your issue</h4>
<input name="summary" onblur="updateSuggestions(this.value)" />

<h4>This is what I DID</h4>
<textarea rows="7" cols="40" name="action">
</textarea>

<h4>This is what I EXPECTED to happen</h4>
<textarea rows="7" cols="40" name="expectation">
</textarea>

<h4>This is what ACTUALLY happened</h4>
<textarea rows="7" cols="40" name="observed">
</textarea>

<h4>This is how I feel about it in 140 characters or less</h4>
<input name="feeling" />

<br />
<button class="align: center;" type="submit">Send it</button

</div>
</div>

</form>

{include file="footer.t"}
