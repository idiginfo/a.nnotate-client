<?php

  //
  // Test exporting Word .docx with track changes.
  //
require_once("annotateApi.php");
require_once("test_config.php");


$phpfn = "apiExportWord.php";

$date = $sampleDate;
$code = $sampleCode;

$annotateuser = $sampleUser;

$request = signRequest($phpfn, $apiuser, $apikey, $annotateuser);

$url = "$annotateServer/php/$phpfn?$request";

$url .= "&d=$date&c=$code";

print "The request is: $url\n";

$ret = file_get_contents($url);

//
// Return value on success:
//    'OK http://yoursite.com/annotate/docs/js/abc123445/somefile-export.docx 2000001'
//
// on error:
//    'ERR {error message}'
//
if (substr($ret, 0, 2) == "OK") {
  $bits = explode(" ", $ret);
  $wordurl = $bits[1];
  $modtime = $bits[2];
  print "Exported Word available from:\n";
  print "  $wordurl\n";
  print "  Timestamp: $modtime\n";
 }
 else {
   print "Returned error: $ret\n";
 }




?>