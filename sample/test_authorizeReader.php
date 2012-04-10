<?php

  //
  // Test adding a reader to a given document.
  //

require_once("annotateApi.php");
require_once("test_config.php");

$phpfn = "authorizeReader.php";

// This is the user who will be authorized to view the document:
$annotateuser = $sampleUser2; // "joe3@textensor.com";

// *** Replace these with the date and code of the document you want ***
$date = $sampleDate; // e.g. "2008-07-15";
$code = $sampleCode; // e.g. "jIKdZcO2";

$request = signRequest($phpfn, $apiuser, $apikey, $annotateuser);
$url = "$annotateServer/php/$phpfn?$request";

$url .= "&d=$date&c=$code";

print "The request is: $url\n";

$ret = file_get_contents($url);

if (substr($ret, 0, 3) == "ERR") {
  print "Returned error: $ret\n";
 }
 else {
   print $ret;
 }




?>