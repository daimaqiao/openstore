<html>
<head><title>OpenStore Demo</title></head>
<body><h1><?php
echo "demo: upload.php";
?></h1>
<hr />
<p>Http method: POST</p>
<p>Form enctype: multipart/form-data</p>
<p>Http status: 200</p>
<hr />
<form action="/openstore/api/upload.php" method="post"
	enctype="multipart/form-data">
<input type="file" name="file" /><br />
<p><input type="checkbox" name="thumbnail">thumbnail</input><p>
<p><input type="submit" /></p>
</form>
</body></html>
