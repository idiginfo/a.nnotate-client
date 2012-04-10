<?php

  //
  // Test deleting a document from a user's list.
  //

require_once("annotateApi.php");
require_once("test_config.php");

$phpfn = "deleteDocument.php";

$annotateuser = $sampleUser; // e.g. "joe3@textensor.com";

$request = signRequest($phpfn, $apiuser, $apikey, $annotateuser);

$url = "$annotateServer/php/$phpfn?$request";

$date = $sampleDate; // e.g. "2008-07-15";
$code = $sampleCode; // e.g. "jIKdZcO2";

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