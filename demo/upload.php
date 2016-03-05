<!DOCTYPE html>
<html>
<head>
<title>OpenStore Demo</title>
<meta charset="utf-8">
</head>
<body><h1><?php
echo "demo: upload.php";
?></h1>
<hr />
<p><i>通过“file”上传文件到服务器<br />
“thumbnail”仅在文件类型为图片时有效，表示自动生成缩略图
</i></p>
<p>HTTP method: <b>POST</b></p>
<p>FORM enctype: <b>multipart/form-data</b></p>
<p>HTTP status: <b>200</b></p>
<hr />
<form action="/openstore/api/upload.php" method="post"
	enctype="multipart/form-data">
<b>file</b>: <input type="file" name="file" /><br />
<p><input type="checkbox" name="thumbnail"><b>thumbnail</b></input><p>
<p><input type="submit" /></p>
</form>
</body></html>
