<?php

  //
  // Test setting the available folder tags.
  //

require_once("annotateApi.php");
require_once("test_config.php");

$phpfn = "apiSetFolderTags.php";

// This is the user account to use:
$annotateuser = $sampleUser;

$request = signRequest($phpfn, $apiuser, $apikey, $annotateuser);
$url = "$annotateServer/php/$phpfn?$request";

?>

<h3>Setting the available note tags for a folder.</h3>

<p>
This form tests the 'apiSetFolderTags.php' call to set the available
note tags for items in a folder.
</p>

<p>
On submit, it will return <code>"OK"</code> or <code>"ERR {error message}"</code>
</p>


<form name='upload' method='POST' action='<?php print $url; ?>' target='_blank'>
<table>
<tr><td>Folder id</td><td><input name='f' value=''></td></tr>
<tr><td>Tags (one per line)</td><td><textarea name='t'>
note
comment
important
version123
</textarea>
</td></tr>
</table>

<tr><td></td><td>  <input type='submit' value='Set folder tags'></td></tr>
</table>
</form>

