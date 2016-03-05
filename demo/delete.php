<!DOCTYPE html>
<html>
<head>
<title>OpenStore Demo</title>
<meta charset="utf-8">
</head>
<body><h1><?php
echo "demo: delete.php";
?></h1>
<hr />
<p><i>通过“fileid”删除服务器上的文件<br />
“thumbnail”表示一并删除与之相关的thumbnail文件（仅当存在该文件时）
</i></p>
<p>HTTP method: <b>POST</b></p>
<p>FORM enctype: <b>application/x-www-form-urlencoded</b></p>
<p>HTTP status: <b>200</b></p>
<hr />
<form action="/openstore/api/delete.php" method="post">
<b>fildid</>: <input type="text" name="fileid" /><br />
<p><input type="checkbox" name="thumbnail"><b>thumbnail</b></input><p>
<p><input type="submit" /></p>
</form>
</body></html>
