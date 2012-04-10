<?php

  //
  // Test moving a folder
  //

require_once("annotateApi.php");
require_once("test_config.php");

$phpfn = "apiMoveFolder.php";

// This is the user account to use:
$annotateuser = $sampleUser;

$request = signRequest($phpfn, $apiuser, $apikey, $annotateuser);
$url = "$annotateServer/php/$phpfn?$request";

?>

<h3>Moving a folder</h3>

<p>
This form tests the 'apiMoveFolder.php' call to move a folder.
</p>

<p>
On submit, it will return <code>"OK"</code> or <code>"ERR {error message}"</code>
</p>


<form name='upload' method='POST' action='<?php print $url; ?>' target='_blank'>
<table>
<tr><td>Folder id to move</td><td><input name='f' value=''></td></tr>
<tr><td>New parent folderid</td><td><input name='p' value=''></td></tr>
</table>

<tr><td></td><td>  <input type='submit' value='Move folder'></td></tr>
</table>
</form>

<p>
You may wish to visit <a href='html_loginAs.php'>html_loginAs.php</a> to log in 
as the test user's account to see the updates.
</p>
