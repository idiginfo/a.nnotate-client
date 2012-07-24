<?php

/**
 * @file Annotate Client Test 
 * @author Casey McLaughlin 
 */

//Require stuff
require_once('libs/HttpClient.php');
require_once('libs/AnnotateClient.php');
require_once('helpers/jsonformat.php');

//Run!
main();

// ---------------------------------------------------------------------------
  
/**
 * Main Execution Function
 */
function main() {

    if (php_sapi_name() != 'cli') {
        echo "<pre>";
    }

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

    //Test - List Notes
    $noteCount = 0;

    $users = $aclient->request('listUsers');
    foreach($users->members as $user) {

      $docs = $aclient->request('listDocuments', array(), $user);    
      foreach($docs[0] as $doc) {

          $docinfo = $aclient->request('listNotes', array('d' => $doc->date, 'c' => $doc->code, $user));
          echo json_format(json_encode($docinfo));
          $noteCount++;
      }

      echo "\n\n============================================================\n\n";
    }

    echo "\nNote Count: {$noteCount}\n\n";  

    if (php_sapi_name() != 'cli') {
       echo "</pre>";
    }  

}

// ---------------------------------------------------------------------------

/* EOF: run.php */