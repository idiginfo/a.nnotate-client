<?php

  //
  // Test adding a note using API
  //

require_once("annotateApi.php");
require_once("test_config.php");

$phpfn = "addNote.php";

$annotateuser = $sampleUser2; // e.g. "joe3@textensor.com";

$request = signRequest($phpfn, $apiuser, $apikey, $annotateuser);

$url = "$annotateServer/php/$phpfn?$request";

$url .= "&d=".$sampleDate."&c=".$sampleCode;

$params = array(
                "subject" => "Test note",
                "contect" => "this is the <b>context</b> and some more",
                "tags" => "tag1, tag2",
                "notetext" => "Sample note text",
                "type"=> "note",
                "mark" => "r",
                "match" => "R1:1:200:200:300:300",
                "drawing" => "[{color:'#ff0000', width:2, np:59, time:0, xpts:[32.61,30.21,26.61,20,14.6,9.8,2.6,2,5,15.2,36.81,51.21,65.62,77.02,87.22,87.22,83.62,80.02,77.02,72.22,68.02,65.01,62.01,61.41,62.61,71.02,78.82,97.42,107.02,114.83,117.23,111.83,107.63,102.22,99.22,95.02,92.62,92.02,92.62,106.42,114.83,125.03,133.43,134.03,131.03,124.43,120.23,112.43,108.23,105.82,102.82,104.62,116.03,130.43,142.43,146.63,148.43,148.43,148.43], ypts:[32.01,30.81,30.81,30.81,32.01,35.01,38.61,43.41,56.61,71.02,87.22,88.42,82.42,72.82,57.81,52.41,45.81,42.81,41.01,39.81,39.81,39.81,42.21,45.81,51.21,57.21,59.01,54.21,45.21,35.01,23,8.6,4.4,3.2,2,2,6.2,13.4,23.61,36.81,38.01,36.81,29.61,23,15.8,8,4.4,2,2,3.2,11,20,27.81,29.01,23.61,18.2,15.8,14,12.2]}]"
                
               
                
);

print "The request is: $url\n";

$ret = doPost($url, $params);

if (substr($ret, 0, 3) == "ERR") {
  print "Returned error: $ret\n";
 }
 else {
   print $ret;
 }
print "\n";



?>