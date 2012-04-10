<?php

/**
 * AnnotateApiException 
 */
class AnnotateApiException extends Exception { /* Pass */ }
  
// ---------------------------------------------------------------------------
  
/**
 * AnnotateClient Class
 * 
 * @author Casey McLaughlin 
 */
class AnnotateClient {
  
  /**
   * HTTPClient Object
   * @var HttpClient
   */
  private $http_client;
  
  /**
   * @var string
   */
  private $api_url = NULL;
  
  /**
   * @var string
   */
  private $api_user = NULL;
  
  /**
   * @var string
   */
  private $api_key = NULL;
  
  // ---------------------------------------------------------------------------
  
  /**
   * Constructor 
   * 
   * @param HttpClient $http_client
   * HTTPClient Object
   * 
   * @param string $annotate_url
   * Annotate URL, usually ends in "/php/" (e.g. http://example.com/annotate/php/)
   * 
   * @param string $api_user
   * API User - The Administrative Email of the Annotate installation
   * 
   * @param string $api_key 
   * API Key - Listed on the account page of the Annotate installation
   */
  public function __construct($http_client, $annotate_url, $api_user, $api_key) {

    //Add trailing slash to the URL
    if (substr($annotate_url, -1) != '/')
      $annotate_url .= '/';
    
    $this->http_client = $http_client;
    $this->api_url = $annotate_url;
    $this->api_user = $api_user;
    $this->api_key = $api_key;
  }
  
  // ---------------------------------------------------------------------------
  
  /**
   * Perform a request to the A.nnotate server
   * 
   * @param string $function
   * @param array $params Query Parameters (key/value)
   * @param string $annotate_user
   * @return string|array  Depending on function call (usually an array)
   * @throws AnnotateApiException
   */
  public function request($function, $params = array(), $annotate_user = NULL) {
    
    //Fix the function name
    if (substr($function, -4) != '.php') {
      $function .= '.php';
    }

    //Generate the signed request URL
    $signature = $this->generate_req_signature($function, $annotate_user);
    $req = $this->api_url . $function . '?' . $signature; 
    if (count($params) > 0) {
      $req = $this->http_client->append_query_params($req, $params);
    }
        
    //Do It
    $result = $this->http_client->go($req);
    
    //Handle Errors
    if (strlen($result->data) > 4 && substr($result->data, 0, 3) == 'ERR') {
      throw new AnnotateApiException(trim(substr($result->data, 4)));
    }
    elseif ($result->code{0} != 2) {
      throw new AnnotateApiException("HTTP Error: " . $result->code . ' - ' . $result->status_message);
    }
    
    //Try to decode the JSON response
    $json_out = json_decode($result->data);
    
    if ($json_out) {
      return $json_out;
    }
    else {
      return $result->data;  
    }
  }
  
  // ---------------------------------------------------------------------------
  
  /**
   * Generate API Request Signature
   * 
   * Per the API documentation: http://a.nnotate.com/api-reference.html
   * 
   * @param string $function
   * @param string $annotate_user
   * @return string 
   */
  private function generate_req_signature($function, $annotate_user = NULL) {
    
    //Annotate user defaults to api_key (admin user email)
    if (is_null($annotate_user)) {
      $annotate_user = $this->api_user;
    }
    
    //Get time
    $req_time = time();
    
    //Build string
    $str_to_sign = "$function\n{$this->api_user}\n$req_time";
    if ($annotate_user)
      $str_to_sign .= "\n$annotate_user";

    //Build Hash Signature
    $sig = $this->hex2b64(hash_hmac('sha1', $str_to_sign, $this->api_key));
    
    //Return the request
    $out_str  = "api-user=" . rawurlencode($this->api_user);
    $out_str .= "&api-requesttime=" . $req_time;
    $out_str .= "&api-auth=" . rawurlencode($sig);
    if ($annotate_user)
      $out_str .= "&api-annotateuser=" . rawurlencode ($annotate_user);
    
    return $out_str;
  }
  
  // ---------------------------------------------------------------------------
  
  /**
   * Helper Function (hex2b64)
   * 
   * @param string $str
   * @return string 
   */
  private function hex2b64($str) {
    
    $raw = '';

    for ($i=0; $i < strlen($str); $i+=2) {
      $raw .= chr(hexdec(substr($str, $i, 2)));
    }
    return base64_encode($raw);
  }
  
}

/* EOF */