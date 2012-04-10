<?php

  //
  // Test creating a folder.
  //

require_once("annotateApi.php");
require_once("test_config.php");

$phpfn = "apiCreateFolder.php";

// This is the user account to use:
$annotateuser = $sampleUser;


$request = signRequest($phpfn, $apiuser, $apikey, $annotateuser);
$url = "$annotateServer/php/$phpfn?$request";

?>

<h3>Creating a folder</h3>

<p>
This form tests the 'apiCreateFolder.php' call to create a folder in <?php print $annotateuser; ?>'s account.
</p>

<p>
On submit, it will return <code>"OK {folder id}"</code> or <code>"ERR {error message}"</code>

</p>


<form name='upload' method='POST' action='<?php print $url; ?>' target='_blank'>
<table>
<tr><td>New folder name</td><td><input name='n' value='Test folder'></td></tr>
<tr><td>Parent folder id </td><td><input name='p' value=''></td></tr>
</table>

<tr><td></td><td>  <input type='submit' value='Create folder'></td></tr>
</table>
</form>

<p>
You may wish to visit <a href='html_loginAs.php'>html_loginAs.php</a> to log in 
as the test user's account to see the updates.
</p>