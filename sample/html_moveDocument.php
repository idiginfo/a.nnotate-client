<?php

  //
  // Test moving a document to a folder
  //

require_once("annotateApi.php");
require_once("test_config.php");

$phpfn = "apiMoveDocument.php";

// This is the user account to use:
$annotateuser = $sampleUser;

// *** Replace these with the date and code of the document you want ***
$date = $sampleDate; // "2008-07-15";
$code = $sampleCode; // "jIKdZcO2";

$request = signRequest($phpfn, $apiuser, $apikey, $annotateuser);
$url = "$annotateServer/php/$phpfn?$request";

?>

<h3>Moving a document to a folder</h3>

<p>
This form tests the 'apiMoveDocument.php' call to move a document to a folder.
</p>

<p>
On submit, it will return <code>"OK"</code> or <code>"ERR {error message}"</code>
</p>


<form name='upload' method='POST' action='<?php print $url; ?>' target='_blank'>
<table>
<tr><td>Document date</td><td><input name='d' value='<?php print $date; ?>'></td></tr>
<tr><td>Document code</td><td><input name='c' value='<?php print $code; ?>'></td></tr>
<tr><td>Parent folderid</td><td><input name='p' value=''></td></tr>
</table>

<tr><td></td><td>  <input type='submit' value='Move document'></td></tr>
</table>
</form>

<p>
You may wish to visit <a href='html_loginAs.php'>html_loginAs.php</a> to log in 
as the test user's account to see the updates.
</p>
