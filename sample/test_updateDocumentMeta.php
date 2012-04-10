<?php

  //
  // Test updating document meta (uses POST - see also html_updateDocumentMeta.php)
  //

require_once("annotateApi.php");

$phpfn = "updateDocumentMeta.php";

$annotateuser = $sampleUser; // e.g. "joe3@textensor.com";

$request = signRequest($phpfn, $apiuser, $apikey, $annotateuser);

$url = "$annotateServer/php/$phpfn?$request";

$date = $sampleDate; // e.g. "2008-07-15";
$code = $sampleCode; // e.g. "jIKdZcO2";

$url .= "&d=$date&c=$code";

// POST params:
$params = array(
                "title" => "Sample title",
                "authors" => "Joe Bloggs", 
                "notes" => "Some document level notes",
                "tags" => "tag1, tag2",
                "x_customfield123" => "some extra newer value"
                );

print "The request is: $url\n";
print "POST params:\n";
print_r ($params);

// We need to do a HTTP POST - here using the curl utility:
$ret = doPost($url, $params);

if (substr($ret, 0, 3) == "ERR") {
  print "Returned error: $ret\n";
 }
 else {
   print $ret;
 }




?>