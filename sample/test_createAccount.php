<?php

  //
  // Test creating new accounts.
  //

require_once("annotateApi.php");
require_once("test_config.php");

function createAccount($email, $sig) {
  global $apiuser, $apikey, $annotateServer;

  $phpfn = "createAccount.php";

  $annotateuser = $email; // e.g. "joe3@textensor.com";

  $request = signRequest($phpfn, $apiuser, $apikey, $annotateuser);
  $url = "$annotateServer/php/$phpfn?$request";

  // Optional: override default sig 
  // (if you don't set sig, it will default to the user email before the '@')
  $url .= "&sig=".rawurlencode($sig);
  
  // Optional: set the initial tags file for this user 
  // (it will look for php/{$tagsfile}.txt  - comma separated text file)
  $tagsfile = "inittags"; // this loads from annotate/php/inittags.txt
  $url .= "&tagsfile=".rawurlencode($tagsfile);
  
  print "The request is: $url\n";

  $ret = file_get_contents($url);
  return $ret;
}


//
// Try creating accounts for the two test users:
//
$ret = createAccount( $sampleUser, $sampleSig );

print "createAccount for $sampleUser returned: $ret\n\n";

$ret = createAccount( $sampleUser2, $sampleSig2 );

print "createAccount for $sampleUser2 returned: $ret\n\n";


?>