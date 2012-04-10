<?php

  //
  // Test logging in to a.nnotate as a given user.
  //

require_once("annotateApi.php");
require_once("test_config.php");

// Make a login link for given $user email, then redirect
// to $loc. If there is a problem, go to $errloc instead.
//
function makeLoginLink($user, $loc, $errloc, $create="", $sig="", $licensed="") {
  global $annotateServer, $apiuser, $apikey;

  $phpfn = "loginAs.php";

  $annotateuser = $user; // e.g. "joe3@textensor.com";

  $validfor = 60 * 30; // Make this request valid for 30 mins.

  $request = signRequest($phpfn, $apiuser, $apikey, $annotateuser, $validfor);
  $url = "$annotateServer/php/$phpfn?$request";

  // After login, display the document list:
  // $loc = "documents.php"; // the list of documents

  // Possible alternative pages to display after login:
  // $loc = "help.php";     // the help page
  // $loc = "account.php";  // the account page
  // $loc = "notes.php";    // the notes index
  // $loc = "pdfnotate.php?d=$sampleDate&c=$sampleCode";    // to login and jump to a document.

  $url .= "&loc=".rawurlencode($loc);

  // On login error - go to this URL and set ?msg={error message}
  // $errloc = "../api/html_error.php";
  $url .= "&errloc=".rawurlencode($errloc);

  // Store the password + username in a cookie:
  $url .= "&remember=1"; 
      
  // If you don't want to store the login info in the browser cookie, change to:
  // $url .= "&remember=0"; 
  
  // New in v3.0.30:

  if ($create) {
    $url .= "&create=1";
    if ($sig) {
      $url .= "&sig=".rawurlencode($sig);
    }
    if ($licensed) {
      $url .= "&licensed=1";
    }
  }

  return $url;
}



?>
<!doctype html>
<html>
<body>
<h3>Auto-logging in users to their A.nnotate account</h3>

<?php

// If something goes wrong with the link, redirect to an error page:
$errloc = $annotateServer."/api/html_error.php";

$login1 = makeLoginLink( $sampleUser, "documents.php", $errloc );
$login2 = makeLoginLink( $sampleUser2, "documents.php", $errloc );
$login3 = makeLoginLink( $sampleUser, "notes.php", $errloc );
$login4 = makeLoginLink( $sampleUser2, "pdfnotate.php?d=$sampleDate&c=$sampleCode", $errloc );
$login5 = makeLoginLink( $sampleUser2, "pdfnotate.php?d=$sampleDate&c=$sampleCode&nobanner=1#page1", $errloc );


$random = rand(100, 1000);
$newuser = "newuser" . $random."@example.com";
$login6 = makeLoginLink( $newuser, "home.php", $errloc, 1, "", false);

$licenseduser = "licenseduser" . $random."@example.com";
$login7 = makeLoginLink( $licenseduser, "home.php", $errloc, 1, "", 1 );
//$random = 317;
$mixeduser = "Mixed.User" . $random."@example.com";
$login8 = makeLoginLink( $mixeduser, "home.php", $errloc, 1, "", 1 );

print "<h4>Logging in as the given user</h4>";

print "<p><a target='_blank' href='".$login1."'>Click to log in as $sampleUser and view documents list</a></p>";
print "<p><a target='_blank' href='".$login2."'>Click to log in as $sampleUser2 and view documents list</a></p>";
print "<p><a target='_blank' href='".$login3."'>Click to log in as $sampleUser and view notes index</a></p>";

print "<h4>Logging in as the given user, and creating a new account for them if necessary</h4>";

print "<p><a target='_blank' href='".$login6."'>Click to create new account for $newuser (if needed) and view their home page</a></p>";
print "<p><a target='_blank' href='".$login7."'>Click to create new account for $licenseduser (if needed, license them to upload new documents) and view their home page</a></p>";

print "<p><a target='_blank' href='".$login8."'>Click to create new account for $mixeduser (if needed, license them to upload new documents) and view their home page</a></p>";

?>
<h4>Logging in as a user and viewing a document</h4>
<p>
<em>[note - the links below will only work once you've edited the sampleDate 
 and sampleCode settings in the config file]</em>
</p>

<?php

print "<p><a target='_blank' href='".$login4."'>Click to log in as $sampleUser2 and view document $sampleDate $sampleCode</a> in a new window </p>";
print "<p><a target='iframeid' href='".$login5."'>Click to log in as $sampleUser2 and show document $sampleDate $sampleCode</a> in the iframe below without the menu banner:</p>";


?>


<iframe src='about:blank' style='border:1px solid blue' width='100%' height='500px' id='iframeid' name='iframeid' >
</iframe>

</body>
</html>
