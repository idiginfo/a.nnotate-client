<?php

  //
  // List the activity annotation timestamps for a user.
  //

require_once("annotateApi.php");
require_once("test_config.php");


$phpfn = "listActivity.php";

$annotateuser = $sampleUser;

$request = signRequest($phpfn, $apiuser, $apikey, $annotateuser);

$url = "$annotateServer/php/$phpfn?$request";


// Set allusers=1 to return activity for all users in account.
$allusers = 1;
if ($allusers) {
  $url .= "&allusers=$allusers";
 }


print "The request is: $url\n";

$ret = file_get_contents($url);

if (substr($ret, 0, 3) == "ERR") {
  print "Returned error: $ret\n";
 }
 else {
   print "Fetched list, parsing ...\n";
   $obj = json_decode($ret); 
   
   print "List activity returned: \n";
   print_r($obj);
 }




?>