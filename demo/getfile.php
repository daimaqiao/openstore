<html>
<head><title>OpenStore Demo</title></head>
<body><h1><?php
echo "demo: getfile.php";
?></h1>
<hr />
<p>Http method: GET</p>
<p>Http status: 302</p>
<hr />
<form action="/openstore/api/getfile.php" method="get">
fildid: <input type="text" name="fileid" /><br />
<p><input type="submit" /></p>
</form>
</body></html>
