<?php

  //
  // Making a link to view a sample document.
  // You need to edit the $sampleDate and $sampleCode values in
  // test_config.php to match an uploaded document.
  //

require_once("annotateApi.php");
require_once("test_config.php");


?>

<h3>Viewing a sample document</h3>

<p>
You can make a link to view a document if you know its date and code
(returned on uploading a document). This link can be sent to anyone
for review.
</p>

<?php

$link = $annotateServer."/php/pdfnotate.php?d=".$sampleDate."&c=".$sampleCode;

print "<p><a href='".$link."'>$link</a></p>";

?>
