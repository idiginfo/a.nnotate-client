<?php

  //
  // Test deleting contacts using API.
  //

require_once("annotateApi.php");
require_once("test_config.php");

$phpfn = "apiDeleteContacts.php";

$annotateuser = $sampleUser2; // e.g. "joe3@textensor.com";

$request = signRequest($phpfn, $apiuser, $apikey, $annotateuser);

$url = "$annotateServer/php/$phpfn?$request";

$params = array(
                "e" => "contact0@example.com, contact1@example.com"
);

print "The request is: $url\n";

$ret = doPost($url, $params);

if (substr($ret, 0, 3) == "ERR") {
  print "Returned error: $ret\n";
 }
 else {
   print "$ret";
 }
print "\n";



?>