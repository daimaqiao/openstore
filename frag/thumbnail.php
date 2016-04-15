<?php
// frag
// thumbnail.php
// 1/2016.0412, BY daimaqiao.


// 调用生成缩略图脚本
// 成功返回缩略图文件路径
function thumbnail_make($input, $output="", $type="") {
	$php= $_SERVER["SCRIPT_FILENAME"];
	$path1= thumbnail_dirname($php);
	$path2= thumbnail_dirname($path1);
	if($path2) {
		$tool= $path2. "/bin/thumbnail.sh";
		$command= $tool. " ". $input. " ". $output. " ". $type;
		return exec($command);
	}
	return "";
}


// 返回目录路径，不含结尾“/”
function thumbnail_dirname($filepath) {
	$pos= strrpos($filepath, "/");
	if($pos)
		return substr($filepath, 0, $pos);
	if($pos != "/")
		return $filepath;
	return "";
}


// 返回文件名的扩展名，不包含点（如：jpg）
function thumbnail_extname($name) {
	if(($str= strrchr($name, ".")))
		return substr($str, 1);
	return "";
}


// 检查是否为image类型的文件
// 是返回true
function thumbnail_check_image($file) {
	$type= $file["type"];
	if($type == "image/jpeg" || $type == "image/pjgeg" || $type == "image/png")
		return true;

	$ext= thumbnail_extname($file["name"]);
	switch(strtolower($ext)) {
	case "jpg":
	case "jpeg":
	case "png":
		return true;
	}// switch

	return false;
}






?>
