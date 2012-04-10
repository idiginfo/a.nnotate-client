<?php

  //
  // Test exporting PDF with notes
  //

require_once("annotateApi.php");
require_once("test_config.php");


$phpfn = "apiExportPdf.php";

$date = $sampleDate;
$code = $sampleCode;

$subset = "0"; 
// $subset="1"; // uncomment to just export pages with notes.

// $exportFormat = "margin";
$exportFormat = "hand";  // hand-written notes only at full size

$annotateuser = $sampleUser;

$request = signRequest($phpfn, $apiuser, $apikey, $annotateuser);

$url = "$annotateServer/php/$phpfn?$request";

$url .= "&d=$date&c=$code&subset=$subset&exportFormat=$exportFormat";

print "The request is: $url\n";

$ret = file_get_contents($url);

//
// Return value on success:
//    'OK http://yoursite.com/annotate/docs/js/abc123445/welcome-notes-all.pdf 2000001'
//
// on error:
//    'ERR {error message}'
//
if (substr($ret, 0, 2) == "OK") {
  $bits = explode(" ", $ret);
  $pdfurl = $bits[1];
  $modtime = $bits[2];
  print "Exported PDF available from:\n";
  print "  $pdfurl\n";
  print "  Timestamp: $modtime\n";
 }
 else {
   print "Returned error: $ret\n";
 }




?>