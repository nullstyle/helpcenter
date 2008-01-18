 <?php

    require_once 'HTTP/Request.php';

        function _hex2bin($hex) {
            $bin = '';
            
            for($i = 0; $i < strlen($hex); $i += 2)
                $bin .= chr(hexdec($hex{$i+1}) + hexdec($hex{$i}) * 16);
            
            return base64_encode($bin);
        }

    
    class HTTP_Request_OAuth
    extends HTTP_Request
    {
       /**
        * Oauth realm
        * @var string
        */
        var $_realm;
    
       /**
        * Oauth consumer key
        * @var string
        */
        var $_consumer_key;
    
       /**
        * OAuth consumer secret
        * @var string
        */
        var $_consumer_secret;
    
       /**
        * Oauth token
        * @var string
        */
        var $_token;
    
       /**
        * Oauth token secret
        * @var string
        */
        var $_token_secret;
    
       /**
        * Constructor
        *
        * Sets up the object
        * @param    string  The url to fetch/access
        * @param    array   Associative array of parameters which can have the following keys:
        * <ul>
        *   <li>consumer_key    - Oauth consumer key (string)</li>
        *   <li>consumer_secret - Oauth consumer secret (string)</li>
        *   <li>signature_method - TBD</li>
        *   <li>token           - Oauth session token or frob (string)</li>
        *   <li>token_secret    - Oauth session secret (string)</li>
        *   <li>realm           - Oauth realm (string)</li>
        *   <li>method          - Method to use, GET, POST etc (string)</li>
        *   <li>http            - HTTP Version to use, 1.0 or 1.1 (string)</li>
        *   <li>user            - Basic Auth username (string)</li>
        *   <li>pass            - Basic Auth password (string)</li>
        *   <li>proxy_host      - Proxy server host (string)</li>
        *   <li>proxy_port      - Proxy server port (integer)</li>
        *   <li>proxy_user      - Proxy auth username (string)</li>
        *   <li>proxy_pass      - Proxy auth password (string)</li>
        *   <li>timeout         - Connection timeout in seconds (float)</li>
        *   <li>allowRedirects  - Whether to follow redirects or not (bool)</li>
        *   <li>maxRedirects    - Max number of redirects to follow (integer)</li>
        *   <li>useBrackets     - Whether to append [] to array variable names (bool)</li>
        *   <li>saveBody        - Whether to save response body in response object property (bool)</li>
        *   <li>readTimeout     - Timeout for reading / writing data over the socket (array (seconds, microseconds))</li>
        *   <li>socketOptions   - Options to pass to Net_Socket object (array)</li>
        * </ul>
        * @access public
        */
        function HTTP_Request_OAuth($url = '', $params = array())
        {
            $this->_realm = null;

            $this->_token = null;
            $this->_token_secret = null;

            $this->_consumer_key = null;
            $this->_consumer_secret = null;

            $this->signature_method = $params['signature_method'];

            HTTP_Request::HTTP_Request($url, $params);

            $this->addHeader('User-Agent', 'PHP HTTP_Request_OAuth class');
        }
        
       /**
        * Signs the request, then sends it
        *
        * @access public
        * @param  bool   Whether to store response body in Response object property,
        *                set this to false if downloading a LARGE file and using a Listener
        * @return mixed  PEAR error on error, true otherwise
        */
        function sendRequest($saveBody=true, $authHeader=false)
        {
            $this->sign($authHeader);

            $r = HTTP_Request::sendRequest($saveBody);
            
            //error_log(print_r($this, 1));
            
            return $r;
        }
        
        
       /**
        * @return   array   Various oauth_* parameters, as defined in the spec.
        *                   Includes: oauth_token, oauth_consumer_key,
        *                   oauth_signature_method, oauth_signature,
        *                   oauth_timestamp, oauth_nonce, oauth_version.
        */
        function oauth_parameters()
        {
            // e.g.: https://photos.example.net/request_token?oauth_consumer_key=dpf43f3p2l4k3l03&oauth_signature_method=PLAINTEXT&oauth_signature=kd94hf93k423kf44%26&oauth_timestamp=1191242090&oauth_nonce=hsu94j3884jdopsl&oauth_version=1.0
            
            return array('oauth_token'            => $this->_token,
                         'oauth_consumer_key'     => $this->_consumer_key,
                         'oauth_signature_method' => $this->signature_method,
                         'oauth_signature'        => null,
                         'oauth_timestamp'        => time(),
                         'oauth_nonce'            => uniqid(''),
                         'oauth_version'          => '1.0');
        }
        
       /**
        * @param    array   $params Associative array of parameters to normalize for signing.
        *                           Note that values are expected to be urlencoded!
        *
        * @return   string  CGI query parameter string for signing.
        */
        function oauth_parametersToString($params)
        {
            $param_keys = array_keys($params);
            $param_values = array_values($params);
            
            // sort by name, then by value
            array_multisort($param_keys, SORT_ASC, $param_values, SORT_ASC);
            
            // pack parameters into a normalized string
            $normalized_keyvalues = array();
            
            for($i = 0; $i < count($param_keys); $i += 1) {
                $key = $param_keys[$i];
                $value = $param_values[$i];
            
                // don't urlencode the values - they are probably already urlencoded
                if($key != 'oauth_signature')
                    if($key != 'oauth_token' || $value)
                        $normalized_keyvalues[] = urlencode($key).'='.$value;
            }
            
            return join('&', $normalized_keyvalues);
        }
        
       /**
        * @return   string  Request URL as defined in the spec,
        *                   i.e. everything up to the query string.
        */
        function oauth_requestURL()
        {
            return  $this->_url->protocol . '://'
                  . $this->_url->user . (!empty($this->_url->pass) ? ':' : '')
                  . $this->_url->pass . (!empty($this->_url->user) ? '@' : '')
                  . $this->_url->host . ($this->_url->port == $this->_url->getStandardPort($this->_url->protocol) ? '' : ':' . $this->_url->port)
                  . $this->_url->path;
        }
        
        function _sha1($s, $consumer_secret, $token_secret) {
          $key = $consumer_secret . '&' . $token_secret;
          print("HMAC-SHA1ing " . $s . " with " . $key . "<br />");
          $digest_b64 = base64_encode(hash_hmac("sha1", $s, $key, TRUE));
          return $digest_b64;
        }

       /**
        * @return   string  Binary md5 digest, as distinct from PHP's built-in hexdigest.
        */
        function _md5($s, $consumer_secret, $token_secret)
        {
            $s = join('&', array_map('urlencode',
                              array($s, $consumer_secret, $token_secret)));
            $md5 = md5($s);
            $bin = '';
            
            for($i = 0; $i < strlen($md5); $i += 2)
                $bin .= chr(hexdec($md5{$i+1}) + hexdec($md5{$i}) * 16);
            
            return base64_encode($bin);
        }

       /**
        * Sign this here request.
        * 
        * @param    boolean $authHeader Where to put the signature:
        *                               true means Authorization HTTP header,
        *                               false means CGI query params.
        */
        function sign($authHeader=false)
        {
            $oauth_parameters = $this->oauth_parameters();
            
            // get any and all existing oauth_* params out of POST, GET
            foreach($oauth_parameters as $key => $value) {
                $this->_url->removeQueryString($key);
                unset($this->_postData[$key]);
            }
            
            // get a callback reference to the proper function for adding new oauth_* params
            // function should accept two arguments: key, value
            $parameter_adder = in_array($this->_requestHeaders['content-type'], array('application/x-www-form-urlencoded', 'multipart/form-data'))
                ? array(&$this, 'addPostData')
                : array(&$this->_url, 'addQueryString');
            
            // for later normalizing
            $parameters_to_normalize = array();
            
            // add new oauth_* parameters if they're not supposed to be in a header
            foreach($oauth_parameters as $key => $value)
                if($key != 'oauth_signature')
                    if($key != 'oauth_token' || $value) {
print "$key => $value<br />";
                        if(!$authHeader)
                            call_user_func($parameter_adder, $key, $value);

                        // these will later need to be normalized, and are expected to be urlencoded
                        $parameters_to_normalize[$key] = urlencode($value);
                    }

            // the master list of parameters to normalize, order matters
            $parameters_to_normalize = array_merge($this->_url->querystring,
                                                   $this->_postData,
                                                   $parameters_to_normalize);
            
            $normalized_params_string = $this->oauth_parametersToString($parameters_to_normalize);
            
            $signature_parts = array($this->_method,
                                     $this->oauth_requestURL(),
                                     $normalized_params_string);

            $signed_string = join('&', array_map('urlencode', $signature_parts));
            
            $oauth_parameters['oauth_signature'] = 
                	$this->signature_method == 'md5' ?
                                HTTP_Request_OAuth::_md5($signed_string,
                                     $this->_consumer_secret,
                                     $this->_token_secret) :
                	$this->signature_method == 'HMAC-SHA1' ?
                               HTTP_Request_OAuth::_sha1($signed_string,
                                                         $this->_consumer_secret,
                                                         $this->_token_secret) :
                        die('unknown signature method');

print "signature: " . $oauth_parameters['oauth_signature'] . "<br />";;

            if($authHeader) {
                // oauth_* params go into the Authorization request header
                $authorization_header = "OAuth ";

                $i = 0;
                if ($this->_realm) {
                  $authorization_header .= "realm=\"{$this->_realm}\"";
                  $i++;
                }
                

                foreach($oauth_parameters as $key => $value) {
# BLAH just want to join a list with ", "
                  if($key != 'oauth_token' || $value) {
                    if ($i++ > 0)
                      $authorization_header .= ", ";
                    $authorization_header .= "{$key}=\"{$value}\"";
                  }
                }
                print ($authorization_header . "<br />");
    
                $this->addHeader('Authorization', $authorization_header);
                
            } else {
                // oauth_* params go into the request body or URL, see above
                call_user_func($parameter_adder, 'oauth_signature', $oauth_parameters['oauth_signature']);

            }

            // for testing, or whatever - wildly insecure:
            /*
            $this->addHeader('X-Oauth-Params', $normalized_params_string);
            $this->addHeader('X-Oauth-String', $signed_string);
            $this->addHeader('X-Oauth-URL', $this->_url->getURL());
            */
        }
        
       /**
        * @return   array   Token and secret, for requests that return such values.
        */
        function getResponseTokenSecret()
        {
            if(preg_match('/\boauth_token=(\S+)\b/Uis', $this->getResponseBody(), $m))
                $token = $m[1];
            
            if(preg_match('/\boauth_token_secret=(\S+)\b/Uis', $this->getResponseBody(), $m))
                $secret = $m[1];
            
            return array($token, $secret);
        }
    }

?> 