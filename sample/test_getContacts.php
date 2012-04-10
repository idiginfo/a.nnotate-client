<?php

  //
  // Test getting contacts using API.
  //

require_once("annotateApi.php");
require_once("test_config.php");

$phpfn = "apiGetContacts.php";

$annotateuser = $sampleUser2; // e.g. "joe3@textensor.com";

$request = signRequest($phpfn, $apiuser, $apikey, $annotateuser);

$url = "$annotateServer/php/$phpfn?$request";


print "The request is: $url\n";

$ret = file_get_contents($url);

if (substr($ret, 0, 3) == "ERR") {
  print "Returned error: $ret\n";
 }
 else { 
   $obj = json_decode($ret);
   
   print_r($obj);
 }



?>