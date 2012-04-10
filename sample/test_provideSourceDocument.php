<?php

  //
  // Test supplying source .docx prior to exportword if
  // originally uploaded PDF.
  //
require_once("annotateApi.php");
require_once("test_config.php");


$phpfn = "apiProvideSourceDocument.php";

$date = $sampleDate;
$code = $sampleCode;

$annotateuser = $sampleUser;

$docurl = $sampleDocURL; // "http://example.com/somedoc.docx";
$docname = $sampleDocName; // "somedoc.docx";
$docxmltype = $sampleDocXmlType; // "word2003" if uploading file.xml in word2003 xml format.


$request = signRequest($phpfn, $apiuser, $apikey, $annotateuser);

$url = "$annotateServer/php/$phpfn?$request";

$url .= "&d=$date&c=$code&docurl=".rawurlencode($docurl).
  "&docname=".rawurlencode($docname)."&docxmltype=".$docxmltype;

print "The request is: $url\n";

$ret = file_get_contents($url);

//
// Return value on success:
//    'OK '
//
// on error:
//    'ERR {error message}'
//
if (substr($ret, 0, 2) == "OK") {
  print "Supplied source document OK:\n";
  print "  $ret\n";
 }
 else {
   print "Returned error: $ret\n";
 }




?>