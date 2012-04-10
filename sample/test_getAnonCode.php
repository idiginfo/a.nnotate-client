<?php

  //
  // Test getting the anon auth code.
  //

require_once("annotateApi.php");
require_once("test_config.php");


$phpfn = "apiGetAnonAuthCode.php";

$annotateuser = "";

$request = signRequest($phpfn, $apiuser, $apikey, $annotateuser);

$request .= "&d=$sampleDate&c=$sampleCode";

$url = "$annotateServer/php/$phpfn?$request";

print "The request is: $url\n";

$ret = file_get_contents($url);

if (substr($ret, 0, 2) == "OK") {
  $aac = trim(substr($ret, 3));
  print "Returned auth code: ".$aac."\n";
  print "  Use: pdfnotate.php?d=$sampleDate&c=$sampleCode&aac=$aac&asig=client123 \n";
 }
 else {
   print "Error - \n $ret \n";
 }




?>