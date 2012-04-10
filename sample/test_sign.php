<?php

  // Test signing a basic message
require_once("annotateApi.php");
require_once("test_config.php");


$msg = "Hello world";

print "Signature of $msg is: ".signString($apikey, $msg);

?>