<?php

  //
  // Test deleting a folder from a given user's account.
  //

require_once("annotateApi.php");
require_once("test_config.php");

$phpfn = "apiDeleteFolder.php";

// This is the user account to use:
$annotateuser = $sampleUser;

// *** Replace these with the date and code of the document you want ***
$date = $sampleDate; // "2008-07-15";
$code = $sampleCode; // "jIKdZcO2";

$request = signRequest($phpfn, $apiuser, $apikey, $annotateuser);
$url = "$annotateServer/php/$phpfn?$request";

?>

<h3>Deleting a folder</h3>

<p>
This form tests the 'apiDeleteFolder.php' call to delete a folder.
</p>

<p>
On submit, it will return <code>"OK"</code> or <code>"ERR {error message}"</code>

</p>


<form name='upload' method='POST' action='<?php print $url; ?>' target='_blank'>
<table>
<tr><td>Folder id </td><td><input name='f' value=''></td></tr>
</table>

<tr><td></td><td>  <input type='submit' value='Delete folder'></td></tr>
</table>
</form>

<p>
You may wish to visit <a href='html_loginAs.php'>html_loginAs.php</a> to log in 
as the test user's account to see the updates.
</p>