<?php

  //
  // Test Folder Sharing API calls: 
  // - create a folder
  // - share it with another user.

require_once("annotateApi.php");
require_once("test_config.php");

// Create signed URL.
function createSignedRequest($phpfn, $user) {
  global $apiuser, $apikey, $annotateServer;
  $phpfn = $phpfn;
  $request = signRequest($phpfn, $apiuser, $apikey, $user);
  $url = "$annotateServer/php/$phpfn?$request";
  return $url;
}

// Create a folder as sampleUser:
$createReq = createSignedRequest("apiCreateFolder.php", $sampleUser);
$params = array("n" => "Sample folder", "p"=>"");
$ret = doPost($createReq, $params);
print "Create folder returned: ".$ret."\n";

if (substr($ret, 0, 2)=="OK") {
  $bits = explode(" ", $ret);
  $folderid = $bits[1];

  // Share it directly with sampleUser2
  $acceptReq = createSignedRequest("apiAcceptFolderInvite.php", $sampleUser2);
  $params = array("f" => $folderid, 
		  "s"=> $sampleUser); // sender of invitation
  $ret = doPost($acceptReq, $params);
  print "Accept folder invite returned: ".$ret."\n";

  // Send out another invite (which they can accept or decline on their home page)
  // to a list of recipients:
  $inviteReq = createSignedRequest("apiInviteToShareFolder.php", $sampleUser);
  $params = array("f" => $folderid, 
		  "e" => "invitee1@example.com,invitee2@example.com",
		  "m"=> "Here is the shared folder");

  $ret = doPost($inviteReq, $params);
  print "Invite to share folder returned: ".$ret."\n";


 }




?>