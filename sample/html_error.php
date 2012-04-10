<h3>Sample error page</h3>

<p> 
<?php
if (isset($_GET["msg"])) {
  print "Message: ".$_GET["msg"];
 }
 else {
   print "No error message set in the msg parameter";
 }
?>
</p>

