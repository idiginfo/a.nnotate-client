<?php

  //
  // Test updating an annotate account.
  //

require_once("annotateApi.php");
require_once("test_config.php");

$phpfn = "updateAccount.php";

$annotateuser = $sampleUser; // e.g. "joe3@textensor.com";

$newsig = "joe2";
$newpassword = "sesame";
$newlicensed = "1"; // or "0" for not licensed.

$request = signRequest($phpfn, $apiuser, $apikey, $annotateuser);

$url = "$annotateServer/php/$phpfn?$request";

// Update account's signature, password and upgrade to full license
// (to enable uploading documents)
$url .= "&sig=$newsig&passwd=$newpassword&licensed=$newlicensed";

print "The request is: $url\n";

$ret = file_get_contents($url);

if (substr($ret, 0, 3) == "ERR") {
  print "Returned error: $ret\n";
 }
 else {
   print $ret;
 }
print "\n";



?>