<?php

  //
  // Test setting a document's sentences
  //

require_once("annotateApi.php");
require_once("test_config.php");

$phpfn = "apiSetDocumentSentences.php";

$annotateuser = $sampleUser;

$request = signRequest($phpfn, $apiuser, $apikey, $annotateuser);

$url = "$annotateServer/php/$phpfn?$request";

$date = $sampleDate; // e.g. "2008-07-15";
$code = $sampleCode; // e.g. "jIKdZcO2";

$url .= "&d=$date&c=$code";


// Note: you can also pass the sentences= parameter using HTTP POST
$sentences = "Here is sentence 1\nHere is sentence 2";
$url .= "&sentences=".rawurlencode($sentences);

print "The request is: $url\n";

$ret = file_get_contents($url);

if (substr($ret, 0, 3) == "ERR") {
  print "Returned error: $ret\n";
 }
 else {
   print $ret;
 }




?>