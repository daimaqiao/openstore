<?php
$dir= dirname(__FILE__);

# for normal
$command= "$dir/thumbnail.sh $dir/koala.jpg /tmp/koala-normal-thumbnail.jpg normal";
$result= exec($command);
print "result: $result\n";
unlink("/tmp/koala-normal-thumbnail.jpg");
echo "";

# for avastar
$command= "$dir/thumbnail.sh $dir/koala.jpg /tmp/koala-avastar-thumbnail.jpg avastar";
$result= exec($command);
print "result: $result\n";
unlink("/tmp/koala-avastar-thumbnail.jpg");
echo "";

?>
