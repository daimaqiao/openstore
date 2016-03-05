<!DOCTYPE html>
<html>
<head>
<title>OpenStore Demo</title>
<meta charset="utf-8">
</head>
<body><h1><?php
echo "demo: getfile.php";
?></h1>
<hr />
<p><i>根据“fileid”获取文件内容（通过302跳转到实际下载链接）</i></p>
<p>HTTP method: <b>GET</b></p>
<p>HTTP status: <b>302</b></p>
<hr />
<form action="/openstore/api/getfile.php" method="get">
<b>fildid</b>: <input type="text" name="fileid" /><br />
<p><input type="submit" /></p>
</form>
</body></html>
