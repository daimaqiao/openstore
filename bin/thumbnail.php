<?php
$dir= dirname(__FILE__);
$command= "$dir/thumbnail.sh $dir/koala.jpg /tmp/koala-thumbnail.jpg";

$result= exec($command);
print "result: $result\n";
unlink("/tmp/koala-thumbnail.jpg");
?>
