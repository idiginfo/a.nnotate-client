<?php

  //
  // Test setting a document's verbs
  //

require_once("annotateApi.php");
require_once("test_config.php");

$phpfn = "apiSetDocumentVerbs.php";

$annotateuser = $sampleUser;

$request = signRequest($phpfn, $apiuser, $apikey, $annotateuser);

$url = "$annotateServer/php/$phpfn?$request";

$date = $sampleDate; // e.g. "2008-07-15";
$code = $sampleCode; // e.g. "jIKdZcO2";

$url .= "&d=$date&c=$code";


// Note: you can also pass the verbs= parameter using HTTP POST
$verbs = "describe, explain, write an essay on, calculate, think, discuss";
$url .= "&verbs=".rawurlencode($verbs);

print "The request is: $url\n";

$ret = file_get_contents($url);

if (substr($ret, 0, 3) == "ERR") {
  print "Returned error: $ret\n";
 }
 else {
   print $ret;
 }




?>