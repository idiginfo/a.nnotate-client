<?php

  //
  // Test authentication with A.nnotate server.
  //

require_once("annotateApi.php");
require_once("test_config.php");


$phpfn = "listDocuments.php";

$annotateuser = $sampleUser;

$request = signRequest($phpfn, $apiuser, $apikey, $annotateuser);

$url = "$annotateServer/php/$phpfn?$request";

// Fetch the basic list of document names, codes and dates.
$url .= "&from=2008_01&to=2020_01&withmeta=0&withnotes=0";

// To restrict to a particular immediate parent folder ID:
//$url .= "&from=2008_01&to=2020_01&withmeta=0&withnotes=0&p=2009-09-26/EhVPw6Dk";
//$url .= "&from=2008_01&to=2020_01&withmeta=0&withnotes=0&p=top";

// To include the metadata too, set withmeta=1:
//$url .= "&from=2008_05&to=2020_07&withmeta=1&withnotes=0";

// To include the notes on each document, set withnotes=1:
// $url .= "&from=2008_05&to=2020_07&withmeta=1&withnotes=1";

print "The request is: $url\n";


$ret = file_get_contents($url);

if (substr($ret, 0, 3) == "ERR") {
  print "Returned error: $ret\n";
 }
 else {
   print "Fetched list, parsing ...\n";
   $obj = json_decode($ret); 
   
   print "List documents returned: \n";
   if ($obj) {
     print_r($obj);
   }
   else {
     print "\nThere was a problem parsing the JSON text - plain text follows:\n";
     print $ret;
   }
 }




?>