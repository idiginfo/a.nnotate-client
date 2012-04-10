<?php

  //
  // Test adding a note to a given document.
  //

require_once("annotateApi.php");
require_once("test_config.php");

$phpfn = "addNote.php";

// This is the user who will be authorized to view the document:
$annotateuser = $sampleUser;

// *** Replace these with the date and code of the document you want ***
$date = $sampleDate; // "2008-07-15";
$code = $sampleCode; // "jIKdZcO2";

$request = signRequest($phpfn, $apiuser, $apikey, $annotateuser);
$url = "$annotateServer/php/$phpfn?$request";

$url .= "&d=$date&c=$code";

?>

<h3>Adding a note to a document</h3>

<p>
This form tests the 'addNote.php' call to add a note
to a document. The annotate user is specified in the 
<code>api-annotateuser</code> GET parameter, and the
  document is specified by the <code>d=2008-01-01&amp;c=abc123</code>
parameters. The note itself is saved using POST variables.
<p>
On submit, it will return <code>"OK {new note id}"</code> or <code>"ERR {error message}"</code>
</p>


<form name='upload' method='POST' action='<?php print $url; ?>' target='_blank'>
<table>
<tr><td>Note text</td><td><textarea name='notetext' rows='3' cols='50'>Some sample note text</textarea></td></tr>
<tr><td>Tags</td><td><i>Comma separated</i></td><td>  <input name='tags' value='tag1,tag2' size='20'></td></tr>


<tr><td>Subject</td><td><i>The highlighted text</i></td><td><input name='subject' value='sample subject' size='20'></td></tr>
<tr><td>Context</td><td><i>The surrounding sentence</i></td><td><input name='context' size='20' value='... sample context ...'></td></tr>
<tr><td>Type</td><td><i>"note" or "reply"</i></td><td><input name='type' value='note' size='8'></td></tr>

<tr><td>Match string</td><td><i>Where the note is attached. For PDF "page-1:10:13" means from word 10 to 13 on page 1. See API reference for attaching notes to rectangle / oval regions.</i></td><td><input name='match' value='page-1:10:13' size='15'></td></tr>

  <tr><td>Mark type</td><td><i>'h' - normal highlight, 's' - strikethrough, 'i' - insert</i></td><td><input name='mark' value='h' size='4'></td></tr>
  <tr><td>Visibility</td><td><i>'shared', 'private', 'feedback'</i></td><td><input name='visibility' value='shared' size='10'></td></tr>

  <tr><td>Border</td><td>(image notes only) the border type - see api reference</i></td><td><input name='border' value='' size='10'></td></tr>


<tr><td>State</td><td><i>"live" or "dead"; to delete a note, save with this set to 'dead'</i></td><td><input name='state' value='live' size='8'></td></tr>
<tr><td>GID</td><td><i>ID of note. Leave empty for new notes; set to GID of note to reply / edit. </i></td><td><input name='gid' value='' size='8'></td></tr>
<tr><td>replyid</td><td><i>ID of a reply, for editing / deleting existing replies. Leave empty for new replies. </i></td><td><input name='replyid' value='' size='8'></td></tr>
<tr><td>Signed</td><td><i>Short signature of note, e.g. 'joe'. Leave empty to use user's normal signature</i></td><td><input name='signed' value='' size='8'></td></tr>


<tr><td></td><td>  <input type='submit' value='Add note to document'></td></tr>
</table>
</form>


<p>
  After adding a note using the form above, you can view the <a href='html_viewSample.php'>sample document</a> again and 
reload to see it.
</p>
