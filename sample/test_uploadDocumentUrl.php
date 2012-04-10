<?php

  //
  // Test uploading document from URL, giving a new filename.
  //

require_once("annotateApi.php");

$phpfn = "uploadDocument.php";

$annotateuser = $sampleUser; // e.g. "joe3@textensor.com";

$validfor = 60 * 30; // valid for 30 mins

$request = signRequest($phpfn, $apiuser, $apikey, $annotateuser, $validfor);

$url = "$annotateServer/php/$phpfn?$request";

// POST params:
$params = array(
		"urlfilename" => "welcome123.pdf",
                "desc" => "This is a sample document",
                "tags" => "tag1, tag2",
		"url" => "$annotateServer/welcome3/welcome.pdf"
                );
  

print "The request is: $url\n";
print "POST params:\n";
print_r ($params);

// We need to do a HTTP POST - here using the curl utility:
$ret = doPost($url, $params, $files);

if (substr($ret, 0, 3) == "ERR") {
  print "Returned error: $ret\n";
 }
 else {
   print $ret."\n";
 }




?>