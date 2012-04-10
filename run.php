<?php

/**
 * @file Annotate Client Test 
 * @author Casey McLaughlin 
 */

//Require stuff
require_once('libs/HttpClient.php');
require_once('libs/AnnotateClient.php');

//Run!
main();

// ---------------------------------------------------------------------------
  
/**
 * Main Execution Function
 */
function main() {
    
  //Load Config
  $config = array();
  if ( ! @include('config.local.php')) {
    die("Error: Setup your configuration file (see config.sample.php for instructions)!");
  }

  $api_user = $config['api_user'];
  $api_key  = $config['api_key'];
  $api_url  = $config['api_url'];
  
  //Build Client Object
  $aclient = new AnnotateClient(new HttpClient(), $api_url, $api_user, $api_key);  
  
  //Test - List Users
  $users = $aclient->request('listUsers');
  foreach($users->members as $user) {
    
    $docs = $aclient->request('listDocuments', array(), $user);    
    foreach($docs[0] as $doc) {
      
      $docinfo = $aclient->request('listNotes', array('d' => $doc->date, 'c' => $doc->code, $user));
      echo json_format(json_encode($docinfo));
    }
    
    echo "\n\n============================================================\n\n";
  }
}

// ---------------------------------------------------------------------------
  
/**
 * Indents a flat JSON string to make it more human-readable.
 *
 * @param string $json The original JSON string to process.
 * @link http://recursive-design.com/blog/2008/03/11/format-json-with-php/
 * @return string Indented version of the original JSON string.
 */
function json_format($json) {

    $result      = '';
    $pos         = 0;
    $strLen      = strlen($json);
    $indentStr   = '  ';
    $newLine     = "\n";
    $prevChar    = '';
    $outOfQuotes = true;

    for ($i=0; $i<=$strLen; $i++) {

        // Grab the next character in the string.
        $char = substr($json, $i, 1);

        // Are we inside a quoted string?
        if ($char == '"' && $prevChar != '\\') {
            $outOfQuotes = !$outOfQuotes;
        
        // If this character is the end of an element, 
        // output a new line and indent the next line.
        } else if(($char == '}' || $char == ']') && $outOfQuotes) {
            $result .= $newLine;
            $pos --;
            for ($j=0; $j<$pos; $j++) {
                $result .= $indentStr;
            }
        }
        
        // Add the character to the result string.
        $result .= $char;

        // If the last character was the beginning of an element, 
        // output a new line and indent the next line.
        if (($char == ',' || $char == '{' || $char == '[') && $outOfQuotes) {
            $result .= $newLine;
            if ($char == '{' || $char == '[') {
                $pos ++;
            }
            
            for ($j = 0; $j < $pos; $j++) {
                $result .= $indentStr;
            }
        }
        
        $prevChar = $char;
    }

    return $result;
}

/* EOF: run.php */