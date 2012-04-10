<?php

  //
  // Test fetching the notes + meta for a single document
  // on behalf of a given user.

require_once("annotateApi.php");
require_once("test_config.php");

$date = $sampleDate;
$code = $sampleCode;

$phpfn = "listNotes.php";

$annotateuser = $sampleUser;

$request = signRequest($phpfn, $apiuser, $apikey, $annotateuser);

$url  = "$annotateServer/php/$phpfn?$request";
$url .= "&d=$date&c=$code";

$ret = file_get_contents($url);

if (substr($ret, 0, 3) == "ERR") {
  print "Returned error: $ret\n";
 }
 else {
   $obj = json_decode($ret); 
   
   print_r($obj);
 }




?>