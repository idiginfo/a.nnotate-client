<?php

  //
  // Test uploading a document.
  //

require_once("annotateApi.php");
require_once("test_config.php");

$phpfn = "uploadDocument.php";

$annotateuser = $sampleUser; // e.g. "joe3@textensor.com";
$sig = $sampleSig; // e.g. "joe3";

$validfor = 60 * 30; // Make this request valid for 30 mins.

$request = signRequest($phpfn, $apiuser, $apikey, $annotateuser, $validfor);
$url = "$annotateServer/php/$phpfn?$request";

// default fmt=txt - returns plain text "OK {date} {code}" or "ERR {msg}" 

// for fmt=redir, after upload it will
// redirect to one of the urls below, with d=...&c=...&annotateuser=...&status=OK 
// redirect to one of the urls below, or   errmsg=...&annotateuser=...&status=ERR
$loc    = "$annotateServer/php/pdfnotate.php?myarg=123";  // could replace with URL on your site
$errloc = "$annotateServer/php/help.php?myarg=err";       // or yoursite.com/error.php etc


// for fmt=js, after upload it will callback parent.documentUploaded(date, code, user)

?>

<h2>Upload a document to: <?php print $annotateuser; ?>&apos;s account</h2>

<p>
This API call tests posting files to the A.nnotate server.
You can do this either with a HTTP POST file upload 
(here using a HTML form, but you can simulate the call
 using libraries / programs like CURL), or by passing in the 
URL of the document.  The API return value is <code>"OK {date} {code}"</code> or
   <code>"ERR {error message}"</code>. You can view the document
   by logging in as the user, or pointing the browser at
  <code><?php print $annotateServer;?>/php/pdfnotate.php?d={date}&amp;c={code}</code>
</p>

<p>
   If the upload works correctly, on pressing 'submit' you will                                             
   see a plain text message like: <code>OK 2008-01-02 abc123</code>
</p>

<p>
  You can enter the date and code in the 'test_config.php' file
  to use that document for subsequent tests.
</p>

<p>
  An alternative callback mechanism is also available: if you set <b>fmt=redir</b>
  then on successful upload, it will redirect to the url provided in the <b>loc</b>
  POST parameter (and to <b>errloc</b> on error).  This can be useful if you
  want to record the date / code on upload from a browser to store
  in your own database.
</p>


<h3> (1) upload a local file using multipart/form-data</h3>

<form name='upload' target='_blank' enctype="multipart/form-data" method='POST' action='<?php print $url; ?>'>
<table>
<tr><td>File (.pdf, .doc)</td><td><input type='file' name='Filedata'></td></tr>
<tr><td>Description</td><td><textarea name='desc' rows='3' cols='50'></textarea></td></tr>
<tr><td>Tags</td><td>  <input name='tags' size='20'></td></tr>
<tr><td></td><td>  <input type='submit'></td></tr>
</table>
</form>

<h3> (2) by passing the URL of a document</h3>

<form name='upload' target='_blank' method='POST' action='<?php print $url; ?>'>
<table>
<tr><td>URL of document</td><td><input name='url' size='40'></td></tr>
<tr><td>Description</td><td><textarea name='desc' rows='3' cols='50'></textarea></td></tr>
<tr><td>Tags</td><td>  <input name='tags' size='20'></td></tr>
<tr><td></td><td>  <input type='submit'></td></tr>
</table>
</form>


<h3> (3) upload a file and redirect to given URL </h3>


<form name='upload' target='_blank' enctype="multipart/form-data" method='POST' action='<?php print $url; ?>'>

<!-- configure redir params -->
<input type='hidden' name='fmt' value='redir' />
<input type='hidden' name='loc' value='<?php print $loc; ?>' />
<input type='hidden' name='errloc' value='<?php print $errloc; ?>' />

<table>
<tr><td>File (.pdf, .doc)</td><td><input type='file' name='Filedata'></td></tr>
<tr><td>Description</td><td><textarea name='desc' rows='3' cols='50'></textarea></td></tr>
<tr><td>Tags</td><td>  <input name='tags' size='20'></td></tr>
<tr><td></td><td>  <input type='submit'></td></tr>
</table>
</form>


<h3> (4) upload a file, target an iframe, and callback javascript in parent window </h3>


<form name='upload' target='targetid' enctype="multipart/form-data" method='POST' action='<?php print $url; ?>'>

<!-- configure redir params -->
<input type='hidden' name='fmt' value='js' />
<input type='hidden' name='cb' value='parent.documentUploaded' />
<input type='hidden' name='errcb' value='parent.uploadError' />

<table>
<tr><td>File (.pdf, .doc)</td><td><input type='file' name='Filedata'></td></tr>
<tr><td>Description</td><td><textarea name='desc' rows='3' cols='50'></textarea></td></tr>
<tr><td>Tags</td><td>  <input name='tags' size='20'></td></tr>
<tr><td></td><td>  <input type='submit'></td></tr>
</table>
</form>

<script>
 // Callbacks on finishing uploading.
function documentUploaded(date, code, user) {
  alert('Document uploaded ok: ' + date + ' '+code+' '+user);
}
function uploadError(errmsg) {
  alert('Document upload error: ' + errmsg);
}
</script>


<iframe width='100%' id='targetid' name='targetid' src='about:blank' />