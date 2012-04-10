<?php

/**
 * HTTP Client Class 
 * 
 * Adapated from drupal_http_request function
 * 
 * @author Casey McLaughlin
 * @license GPLv2  http://www.gnun.org/licenses/old-licenses/gpl-2.0.txt
 */
class HttpClient {
  
  /**
   * @var array
   */
  private $timers = array();

  // -------------------------------------------------------------------- 
  
  /**
   * Do a HTTP Request
   * 
   * @param string $url
   * A string containing a fully qualified URI.
   *
   * @param array $options
   * headers: An array containing request headers to send as name/value pairs.
   * method: A string containing the request method. Defaults to 'GET'.
   * data: An array of key/value pairs, or a string containing the request body, formatted as 'param=value&param=value&...'. Defaults to NULL.
   * max_redirects: An integer representing how many times a redirect may be followed. Defaults to 3.
   * timeout: A float representing the maximum number of seconds the function call may take. The default is 30 seconds. If a timeout occurs, the error code is set to the HTTP_REQUEST_TIMEOUT constant.
   * context: A context resource created with stream_context_create().
   * query_params: An array of key/value pairs that will be converted into a query string (?key=value&key=value)
   * 
   * @return \stdClass 
   * An object with the following components
   * request: A string containing the request body that was sent.
   * code: An integer containing the response status code, or the error code if an error occurred.
   * protocol: The response protocol (e.g. HTTP/1.1 or HTTP/1.0).
   * status_message: The status message from the response, if a response was received.
   * redirect_code: If redirected, an integer containing the initial response status code.
   * redirect_url: If redirected, a string containing the URL of the redirect target.
   * error: If an error occurred, the error message. Otherwise not set.
   * headers: An array containing the response headers as name/value pairs. HTTP header names are case-insensitive (RFC 2616, section 4.2), so for easy access the array keys are returned in lower case.
   * data: A string containing the response body that was received.
   * content_type: The content type, parsed from the response headers
   * time: How long the request took
   * 
   */
   public function go($url, array $options = array()) {

    $result = (object) array();

    //Append the query string
    if (isset($options['query_params'])) {
      $url = $this->append_query_params($url, $options['query_params']);
    }
    
    //If the data is in array form, fix it
    if (isset($options['data']) && is_array($options['data']) && ! empty($options['data'])) {
      $options['data'] = $this->url_serialize($options['data']);
    }
    
    // Parse the URL and make sure we can handle the schema.
    $uri = @parse_url($url);

    if ($uri == FALSE) {
      $result->error = 'unable to parse URL';
      $result->code = -1001;
      return $result;
    }

    if (!isset($uri['scheme'])) {
      $result->error = 'missing schema';
      $result->code = -1002;
      return $result;
    }

    $this->timer_start(__FUNCTION__);

    // Merge the default options.
    $options += array(
      'headers' => array(), 
      'method' => 'GET', 
      'data' => NULL, 
      'max_redirects' => 3, 
      'timeout' => 30.0, 
      'context' => NULL,
    );
    // stream_socket_client() requires timeout to be a float.
    $options['timeout'] = (float) $options['timeout'];

    switch ($uri['scheme']) {
      case 'http':
      case 'feed':
        $port = isset($uri['port']) ? $uri['port'] : 80;
        $socket = 'tcp://' . $uri['host'] . ':' . $port;
        // RFC 2616: "non-standard ports MUST, default ports MAY be included".
        // We don't add the standard port to prevent from breaking rewrite rules
        // checking the host that do not take into account the port number.
        $options['headers']['Host'] = $uri['host'] . ($port != 80 ? ':' . $port : '');
        break;
      case 'https':
        // Note: Only works when PHP is compiled with OpenSSL support.
        $port = isset($uri['port']) ? $uri['port'] : 443;
        $socket = 'ssl://' . $uri['host'] . ':' . $port;
        $options['headers']['Host'] = $uri['host'] . ($port != 443 ? ':' . $port : '');
        break;
      default:
        $result->error = 'invalid schema ' . $uri['scheme'];
        $result->code = -1003;
        return $result;
    }

    if (empty($options['context'])) {
      $fp = @stream_socket_client($socket, $errno, $errstr, $options['timeout']);
    }
    else {
      // Create a stream with context. Allows verification of a SSL certificate.
      $fp = @stream_socket_client($socket, $errno, $errstr, $options['timeout'], STREAM_CLIENT_CONNECT, $options['context']);
    }

    // Make sure the socket opened properly.
    if (!$fp) {
      // When a network error occurs, we use a negative number so it does not
      // clash with the HTTP status codes.
      $result->code = -$errno;
      $result->error = trim($errstr) ? trim($errstr) : t('Error opening socket @socket', array('@socket' => $socket));

      return $result;
    }

    // Construct the path to act on.
    $path = isset($uri['path']) ? $uri['path'] : '/';
    if (isset($uri['query'])) {
      $path .= '?' . $uri['query'];
    }

    // Merge the default headers.
    $options['headers'] += array(
      'User-Agent' => 'APPNAME',
    );

    // Only add Content-Length if we actually have any content or if it is a POST
    // or PUT request. Some non-standard servers get confused by Content-Length in
    // at least HEAD/GET requests, and Squid always requires Content-Length in
    // POST/PUT requests.
    $content_length = strlen($options['data']);
    if ($content_length > 0 || $options['method'] == 'POST' || $options['method'] == 'PUT') {
      $options['headers']['Content-Length'] = $content_length;
    }

    // If the server URL has a user then attempt to use basic authentication.
    if (isset($uri['user'])) {
      $options['headers']['Authorization'] = 'Basic ' . base64_encode($uri['user'] . (isset($uri['pass']) ? ':' . $uri['pass'] : ''));
    }

    $request = $options['method'] . ' ' . $path . " HTTP/1.0\r\n";
    foreach ($options['headers'] as $name => $value) {
      $request .= $name . ': ' . trim($value) . "\r\n";
    }
    $request .= "\r\n" . $options['data'];
    $result->request = $request;
    // Calculate how much time is left of the original timeout value.
    $timeout = $options['timeout'] - $this->timer_read(__FUNCTION__) / 1000;
    if ($timeout > 0) {
      stream_set_timeout($fp, floor($timeout), floor(1000000 * fmod($timeout, 1)));
      fwrite($fp, $request);
    }

    // Fetch response. Due to PHP bugs like http://bugs.php.net/bug.php?id=43782
    // and http://bugs.php.net/bug.php?id=46049 we can't rely on feof(), but
    // instead must invoke stream_get_meta_data() each iteration.
    $info = stream_get_meta_data($fp);
    $alive = !$info['eof'] && !$info['timed_out'];
    $response = '';

    while ($alive) {
      // Calculate how much time is left of the original timeout value.
      $timeout = $options['timeout'] - $this->timer_read(__FUNCTION__) / 1000;
      if ($timeout <= 0) {
        $info['timed_out'] = TRUE;
        break;
      }
      stream_set_timeout($fp, floor($timeout), floor(1000000 * fmod($timeout, 1)));
      $chunk = fread($fp, 1024);
      $response .= $chunk;
      $info = stream_get_meta_data($fp);
      $alive = !$info['eof'] && !$info['timed_out'] && $chunk;
    }
    fclose($fp);

    if ($info['timed_out']) {
      $result->code = HTTP_REQUEST_TIMEOUT;
      $result->error = 'request timed out';
      return $result;
    }
    // Parse response headers from the response body.
    // Be tolerant of malformed HTTP responses that separate header and body with
    // \n\n or \r\r instead of \r\n\r\n.
    list($response, $result->data) = preg_split("/\r\n\r\n|\n\n|\r\r/", $response, 2);
    $response = preg_split("/\r\n|\n|\r/", $response);

    // Parse the response status line.
    list($protocol, $code, $status_message) = explode(' ', trim(array_shift($response)), 3);
    $result->protocol = $protocol;
    $result->status_message = $status_message;

    $result->headers = array();

    // Parse the response headers.
    while ($line = trim(array_shift($response))) {
      list($name, $value) = explode(':', $line, 2);
      $name = strtolower($name);
      if (isset($result->headers[$name]) && $name == 'set-cookie') {
        // RFC 2109: the Set-Cookie response header comprises the token Set-
        // Cookie:, followed by a comma-separated list of one or more cookies.
        $result->headers[$name] .= ',' . trim($value);
      }
      else {
        $result->headers[$name] = trim($value);
      }
    }

    $responses = array(
      100 => 'Continue', 
      101 => 'Switching Protocols', 
      200 => 'OK', 
      201 => 'Created', 
      202 => 'Accepted', 
      203 => 'Non-Authoritative Information', 
      204 => 'No Content', 
      205 => 'Reset Content', 
      206 => 'Partial Content', 
      300 => 'Multiple Choices', 
      301 => 'Moved Permanently', 
      302 => 'Found', 
      303 => 'See Other', 
      304 => 'Not Modified', 
      305 => 'Use Proxy', 
      307 => 'Temporary Redirect', 
      400 => 'Bad Request', 
      401 => 'Unauthorized', 
      402 => 'Payment Required', 
      403 => 'Forbidden', 
      404 => 'Not Found', 
      405 => 'Method Not Allowed', 
      406 => 'Not Acceptable', 
      407 => 'Proxy Authentication Required', 
      408 => 'Request Time-out', 
      409 => 'Conflict', 
      410 => 'Gone', 
      411 => 'Length Required', 
      412 => 'Precondition Failed', 
      413 => 'Request Entity Too Large', 
      414 => 'Request-URI Too Large', 
      415 => 'Unsupported Media Type', 
      416 => 'Requested range not satisfiable', 
      417 => 'Expectation Failed', 
      500 => 'Internal Server Error', 
      501 => 'Not Implemented', 
      502 => 'Bad Gateway', 
      503 => 'Service Unavailable', 
      504 => 'Gateway Time-out', 
      505 => 'HTTP Version not supported',
    );
    // RFC 2616 states that all unknown HTTP codes must be treated the same as the
    // base code in their class.
    if (!isset($responses[$code])) {
      $code = floor($code / 100) * 100;
    }
    $result->code = $code;

    switch ($code) {
      case 200: // OK
      case 304: // Not modified
        break;
      case 301: // Moved permanently
      case 302: // Moved temporarily
      case 307: // Moved temporarily
        $location = $result->headers['location'];
        $options['timeout'] -= $this->timer_read(__FUNCTION__) / 1000;
        if ($options['timeout'] <= 0) {
          $result->code = HTTP_REQUEST_TIMEOUT;
          $result->error = 'request timed out';
        }
        elseif ($options['max_redirects']) {
          // Redirect to the new location.
          $options['max_redirects']--;
          $result = $this->go($location, $options);
          $result->redirect_code = $code;
        }
        if (!isset($result->redirect_url)) {
          $result->redirect_url = $location;
        }
        break;
      default:
        $result->error = $status_message;
    }

    //Add some additional info
    if (isset($result->headers) && $result->headers) {
      $result->content_type = $this->parse_content_type($result->headers);
    }

    $result->time = (float) ($this->timer_read(__FUNCTION__) / 1000);

    return $result;
  }

  // -----------------------------------------------------------
  
  /**
   * Append an array of query parameters to a URL
   * 
   * @param string $url  The original URL
   * @param array $params  An array of key/value pairs to convert
   * @return string
   */
  public function append_query_params($url, $params) {
    
    //If there is already a '?' in the URL, assume an existing query string
    $url .= (strpos($url, '?')) ? '&' : '?';
    
    return $url . $this->url_serialize($params);
  }
  
  // -----------------------------------------------------------

  /**
   * URL Serialize an array of key/value pairs
   * 
   * @param array $params
   * @return string 
   */
  private function url_serialize($params) {
        
    foreach($params as $k => &$v) {
      $v = $k . '=' . urlencode($v);
    }
    
    return implode('&', $params);
  }
  
  // -----------------------------------------------------------
 
  /**
   * Helper function (timer start) - Adapted from Drupal's timer_start
   * 
   * @param string $name 
   */
  private function timer_start($name) {

    $this->timers[$name]['start'] = microtime(TRUE);
    $this->timers[$name]['count'] = isset($this->timers[$name]['count']) ? ++$this->timers[$name]['count'] : 1;
  }

  // -----------------------------------------------------------

  /**
   * Helper function (timer read) - Adapted from Drupal's timer_read
   * @param string $name
   * @return int
   */
  private function timer_read($name) {

    if (isset($this->timers[$name]['start'])) {
      $stop = microtime(TRUE);
      $diff = round(($stop - $this->timers[$name]['start']) * 1000, 2);

      if (isset($this->timers[$name]['time'])) {
        $diff += $this->timers[$name]['time'];
      }
      return $diff;
    }
    return $this->timers[$name]['time'];
  }
  
  // -----------------------------------------------------------

  /**
   * Parse content type from the headers
   * 
   * @param array $headers
   * @return string
   */
  private function parse_content_type($headers) {

    $type = FALSE;
    $headers = (array) $headers;

    if (isset($headers['content-type'])) {

      $content_type = $headers['content-type'];
      if (strpos($content_type, ';'))
        list($type, $enc) = array_map('trim', explode(';', $content_type));
      else
        list($type, $enc) = array($content_type, NULL);
    }

    return $type;

  }  
  
}

/* EOF: HttpClient.php */