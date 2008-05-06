 // FIXME: credit this snippet
 var AJAX = function() {
  // (IE) XMLHttpRequest is an ActiveXObject in IE
  return {
    isLoaded : 2,
    isComplete : 4,
  
  newRequest : function() {
    var http_request;
    if(window.XMLHttpRequest) { // native XMLHttpRequest object (FireFox, etc.)
      try {
        http_request = new XMLHttpRequest();
      } catch(e) {
        throw ("Failed to create (native) XMLHttpRequest");
      }
    } else if(window.ActiveXObject) { //IE
      try {
          http_request = new ActiveXObject("Msxml2.XMLHTTP");
      } catch(e) {
        try {
          http_request = new ActiveXObject("Microsoft.XMLHTTP");
        } catch(e) {
          throw ("Failed to create (ActiveX) XMLHttpRequest object");
        }
      }
    }
    return http_request;
  }
  };
}();

function getHTMLAJAX(url) {
// TBD: async
  var req = AJAX.newRequest();
  req.open('GET', url, true);
  var result;
  req.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  req.send();
  return req.responseText;
}

var LOADED = 4;

function getHTMLAJAXAsync(url, kappa) {
// TBD: async
  var req = AJAX.newRequest();
  req.open('GET', url, true);
  var result;
  req.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
  req.onreadystatechange = function() {
    if (req.readyState == LOADED) {
      kappa(req.responseText);
    }
  }
  req.send(null);
}

function trim(str) {
  var result = str.replace(/^[\n\r\t]+/, '');
  result = result.replace(/[\n\r\t]+$/, '');
  return result;
}