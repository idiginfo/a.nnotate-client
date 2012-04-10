<?php

  //
  // Test updating the metadata for a document.
  //

require_once("annotateApi.php");
require_once("test_config.php");

$phpfn = "updateDocumentMeta.php";

// This is the user who will be authorized to view the document:
$annotateuser = $sampleUser; // e.g. "joe3@textensor.com";

// *** Replace these with the date and code of the document you want ***
$date = $sampleDate; // e.g. "2008-07-15";
$code = $sampleCode; // e.g. "jIKdZcO2";

$request = signRequest($phpfn, $apiuser, $apikey, $annotateuser);
$url = "$annotateServer/php/$phpfn?$request";

$url .= "&d=$date&c=$code";

?>

<h3>Updating document metadata</h3>

<p>
This form tests the 'updateDocumentMeta.php' call to add 
document-level notes and tags.
The annotate user is specified in the 
<code>api-annotateuser</code> GET parameter, and the
  document is specified by the <code>d=2008-01-01&amp;c=abc123</code>
parameters. The metadata is saved using POST variables.
</p>

<p>
On submit, it will return <code>"OK"</code> or <code>"ERR {error message}"</code>.  You might want
  to <a href='html_viewSample.php'>view the sample</a>, reload and check the Tools &gt; Properties
  to check the settings have been updated. You can also view the <a target='_blank' href='<?php print $annotateServer."/docs/$sampleDate/$sampleCode/docmeta.js"; ?>'>docmeta.js</a> JSON file.
</p>

<form name='upload' method='POST' target="_blank" action='<?php print $url; ?>'>
<table>
<tr><td>Document title</td><td><input name='title' value='Sample title' size='20'></td></tr>
<tr><td>Document authors</td><td><input name='authors' value='Joe Bloggs, A.N. Other' size='20'></td></tr>
<tr><td>Document level notes</td><td><textarea name='notes' value='Some sample notes on the document' rows='3' cols='50'></textarea></td></tr>
<tr><td>Document Tags</td><td>  <input name='tags' value='important, accounts, pdf' size='20'></td></tr>

<tr><td>Custom field <b>x_myfield1</b></td><td>  <input name='x_myfield1' value='Sample extra field' size='20'></td></tr>

<tr><td></td><td>  <input type='submit'></td></tr>
</table>
</form>


  <h3 style='margin-top:8em'>Updating document metadata and permissions (advanced)</h3>

<p>
'updateDocumentMeta.php' can also be used to set advanced permissions on 
  a document. If you leave out particular fields in the HTTP POST                                                        
(e.g. the title and authors),
then they will be left unchanged.                                                       
  
</p>


<p>
On submit, it will return <code>"OK"</code> or <code>"ERR {error message}"</code>
</p>

<form name='upload' method='POST' target="_blank" action='<?php print $url; ?>'>
<table>

<tr>
  <td>Default note status</td>
  <td>shared / private / feedback</td>
  <td><input name='defaultNoteStatus' value='shared' size='10'></td>
</tr>

<tr>
  <td>Allow note status change</td>
  <td>1 / 0  ( whether to let annotators alter the status of their notes)</td>
  <td><input name='allowNoteStatusChange' value='1'  size='10'></td>
</tr>                                                        

<tr>
  <td>Allow new note tags</td>
  <td>1 / 0  (whether users can create new tags)</td>
  <td><input name='allowNewNoteTags' value='1'  size='10'></td>
</tr>
                                                        
<tr>
  <td>Close for comments</td>
  <td>1 / 0 (whether document is closed for further comments) </td>
  <td><input name='closedForComments' value='0' size='10'></td>
</tr>

<tr>
  <td>Add to contacts</td>
  <td>1 / 0 (whether to auto-add new annotators to owner's contacts list)</td>
  <td><input name='addToContacts' value='1' size='10'></td>
</tr>

<tr>
  <td>Allow annotation users</td>
  <td>List of emails of users allowed to annotate</td>
  <td>
<textarea name='allowAnnotationUsers' rows='3' cols='50'>
joe@example.com
jack@example.com
</textarea>
  </td>
</tr>

<tr>
  <td>Deny annotation users</td>
  <td>List of emails of users not allowed to annotate</td>
  <td>
<textarea name='denyAnnotationUsers' rows='3' cols='50'>
badguy@example.com
</textarea>
  </td>
</tr>

<tr>
  <td>Per page permissions (optional)</td>
  <td>For enabling given users to annotate particular pages.</td>
  <td>
<textarea name='perPagePermissions' rows='3' cols='50'>
joe@example.com:1,2,3-10,99,150-
jack@example.com:1-,!15
</textarea>
  </td>
</tr>



<tr><td></td><td>  <input type='submit'></td></tr>
</table>
</form>
