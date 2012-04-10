<?php

  //
  // Test making a thumbnail of a page with freehand annotations.
  //

require_once("annotateApi.php");
require_once("test_config.php");


$phpfn = "apiFreehandThumb.php";

$date = $sampleDate;
$code = $sampleCode;
$page = 1;
$xsize = 200;
$ysize = 200;


$annotateuser = $sampleUser;

$request = signRequest($phpfn, $apiuser, $apikey, $annotateuser);

$url = "$annotateServer/php/$phpfn?$request";

$url .= "&d=$date&c=$code&x=$xsize&y=$ysize&p=$page";

print "The request is: $url\n";

$ret = file_get_contents($url);

//
// Return value on success:
//    'OK http://yoursite.com/annotate/docs/js/abc123445/tn-xxx-xxx.png 2000001'
//
// on error:
//    'ERR {error message}'
//
if (substr($ret, 0, 2) == "OK") {
  $bits = explode(" ", $ret);
  $imgurl = $bits[1];
  $modtime = $bits[2];
  print "Freehand thumbnail image available from:\n";
  print "  $imgurl\n";
  print "  Timestamp: $modtime\n";
 }
 else {
   print "Returned error: $ret\n";
 }




?>