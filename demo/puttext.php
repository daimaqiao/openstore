<?php
// demo
// puttext.php
// 1/2016.0413, BY daimaqiao.
?>
<!DOCTYPE html>
<html>
<head>
<title>OpenStore Demo</title>
<meta charset="utf-8">
<script>
function showSize() {
	var f= document.getElementById("file");
	var s= document.getElementById("size");
	var size= 0;
	if(f.files.length> 0)
		size= f.files[0].size;
	s.value= size;
}
</script>
</head>
<body><h1><?php
echo "demo: puttext.php";
?></h1>
<hr />
<p><i>在表单中通过“file”上传文件到服务器<br />
通过“size”指定文件大小（字节）
</i></p>
<p>HTTP method: <b>POST</b></p>
<p>FORM enctype: <b>multipart/form-data</b></p>
<p>HTTP status: <b>200</b></p>
<hr />
<form action="/openstore/api/puttext.php" method="post"
	enctype="multipart/form-data">
<b>file</b>: <input type="file" name="file" id="file" onchange="showSize()" /><br />
<p><b>size</b>: <input type="text" name="size" id="size" readonly="true" value="0" />(auto)<p>
<p><input type="submit" /></p>
</form>
</body></html>
