<?php

  //
  // Test deleting an annotate account.
  //

require_once("annotateApi.php");
require_once("test_config.php");

$phpfn = "apiDeleteAccount.php";

$annotateuser = $sampleUser2; // e.g. "joe3@textensor.com";

$request = signRequest($phpfn, $apiuser, $apikey, $annotateuser);

$url = "$annotateServer/php/$phpfn?$request";

$params = array("delete"=>"1");

print "The request is: $url\n";

$ret = doPost($url, $params);

if (substr($ret, 0, 3) == "ERR") {
  print "Returned error: $ret\n";
 }
 else {
   print $ret;
 }
print "\n";



?>