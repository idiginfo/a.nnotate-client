<?php

  //
  // Test renaming a folder.
  //

require_once("annotateApi.php");
require_once("test_config.php");

$phpfn = "apiRenameFolder.php";

// This is the user account to use:
$annotateuser = $sampleUser;

$request = signRequest($phpfn, $apiuser, $apikey, $annotateuser);
$url = "$annotateServer/php/$phpfn?$request";

?>

<h3>Renaming a folder.</h3>

<p>
This form tests the 'apiRenameFolder.php' call to 
rename a folder given an id.
</p>

<p>
On submit, it will return <code>"OK"</code> or <code>"ERR {error message}"</code>
</p>


<form name='upload' method='POST' action='<?php print $url; ?>' target='_blank'>
<table>
<tr><td>Folder id</td><td><input name='f' value=''></td></tr>
<tr><td>New name</td><td><input name='n' value='Test123'></td></tr>
</table>

<tr><td></td><td>  <input type='submit' value='Rename folder'></td></tr>
</table>
</form>

