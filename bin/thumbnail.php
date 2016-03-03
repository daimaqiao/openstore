<?php
$command= "/home/shb/local/openstore/bin/thumbnail.sh /home/shb/local/openstore/bin/koala.jpg /tmp/koala-thumbnail.jpg";
$result= exec($command);
print "result: $result";
echo "\n";
?>
