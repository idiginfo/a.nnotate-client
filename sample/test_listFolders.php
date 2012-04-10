<?php

  //
  // Test listing folders for a user
  //

require_once("annotateApi.php");
require_once("test_config.php");


$phpfn = "listFolders.php";

$annotateuser = $sampleUser;

$request = signRequest($phpfn, $apiuser, $apikey, $annotateuser);

$url = "$annotateServer/php/$phpfn?$request";

print "The request is: $url\n";


$ret = file_get_contents($url);

if (substr($ret, 0, 3) == "ERR") {
  print "Returned error: $ret\n";
 }
 else {
   print "Fetched folders list, parsing ...\n";
   $obj = json_decode($ret); 
   
   print "List folders returned: \n";
   if ($obj) {
     print_r($obj);
   }
   else {
     print "\nThere was a problem parsing the JSON text - plain text follows:\n";
     print $ret;
   }
 }




?>