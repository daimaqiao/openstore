<?php
// api
// puttext.php
// 1/2016.0412, BY daimaqiao.

include "../frag/openstore_1.0.php";
include "../frag/fastdfs.php";
include "../frag/openim_1.0.php";

// 初始化
$result= result_init("puttext");

// 要求方法为POST
if($_SERVER["REQUEST_METHOD"] != "POST") {
	bad_request($result);
	exit(1);
}

// 要求带有文件数据
$file= $_FILES["file"];
if($file["error"] > 0 || $file["size"] <= 0) {
	bad_request($result);
	exit(1);
}

// 生成oim信息
$oim= oim_init("text");
$oim_size= array_key_exists("size", $_POST)? (int)$_POST["size"]: $file["size"];
oim_set_size($oim, $oim_size);
$oim_base64= oim_to_string_base64($oim);

// 保存文件到FastDFS
$fileid= fdfs_save_file($file["name"], $file["tmp_name"]);
if(!$fileid) {
	bad_request($result);
	exit(1);
}
$fileid_base64= base64_encode($fileid);

// 保存oim信息到FastDFS
if(!fdfs_put_property($fileid, "oim", $oim_base64)) {
	fdfs_remove_file($fileid);
	bad_request($result);
	exit(1);
}

// 输出结果
$dlurl= result_make_dlurl(get_baseurl(), "puttext", $fileid_base64, $oim_base64);
response_ok($result, $dlurl);
// done




/////////////////////////////////////// 
// 输出包含“bad request”的报错结果
function bad_request($result) {
	echo result_bad_request_to_string($result);
}


/////////////////////////////////////// 
// 输出包含内容的结果
function response_ok($result, $ok) {
	echo result_ok_to_string($result, $ok);
}


/////////////////////////////////////// 
// 是否https
function check_https() {
	return array_key_exists("HTTPS", $_SERVER) && $_SERVER["HTTPS"];
}


/////////////////////////////////////// 
// 获取baseurl，不含路径与查询
function get_baseurl() {
	$url= check_https()? "https://": "http://";
	return $url. $_SERVER["HTTP_HOST"];
}




?>
