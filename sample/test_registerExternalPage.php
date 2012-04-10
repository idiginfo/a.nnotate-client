<?php

  //
  // Test registering an external URL for annotation -
  // only needed if you want to run annotate on external
  // web pages. If you run this several times with the 
  // same url, it will return the same date/code.
  //

require_once("annotateApi.php");
require_once("test_config.php");

$phpfn = "apiRegisterExternalPage.php";

$annotateuser = $sampleUser; // e.g. "joe3@textensor.com";

$request = signRequest($phpfn, $apiuser, $apikey, $annotateuser);

$url = "$annotateServer/php/$phpfn?$request";

// To make an annotate page for storing notes on an external
// url, set:
$sampleurl = "http://www.textensor.com";
$version = "1";
$title = "Sample external page";
$desc = "Sample desc";
$tags = "tag1, tag2";
$parent = ""; // or parent folder.

$url .= "&url=".rawurlencode($sampleurl);
$url .= "&version=".rawurlencode($version);
$url .= "&title=".rawurlencode($title);
$url .= "&desc=".rawurlencode($desc);
$url .= "&tags=".rawurlencode($tags);
$url .= "&parent=".rawurlencode($parent);

print "The request is: $url\n";

$ret = file_get_contents($url);

if (substr($ret, 0, 3) == "ERR") {
  print "Returned error: $ret\n";
 }
 else {
   print $ret;
 }




?>