<?php
// api
// putlocation.php
// 1/2016.0415, BY daimaqiao.

include "../frag/openstore_1.0.php";
include "../frag/fastdfs.php";
include "../frag/openim_1.0.php";
include "../frag/thumbnail.php";

// 初始化
$result= result_init("putlocation");

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

// 检查文件类型
if(!thumbnail_check_image($file)) {
	not_supported($result);
	exit(1);
}

// 生成oim信息
$oim= oim_init("location");
$oim_size= array_key_exists("size", $_POST)? (int)$_POST["size"]: $file["size"];
$longitude= array_key_exists("longitude", $_POST)? (double)$_POST["longitude"]: 0;
$latitude= array_key_exists("latitude", $_POST)? (double)$_POST["latitude"]: 0;
$accuracy= array_key_exists("accuracy", $_POST)? (int)$_POST["accuracy"]: 0;
$description= array_key_exists("description", $_POST)? $_POST["description"]: "";
$properties= array(
	"longitude"		=> $longitude,
	"latitude"		=> $latitude,
	"accuracy"		=> $accuracy,
	"description"	=> $description
);
oim_reset($oim, $oim_size, $properties);
$oim_base64= oim_to_string_base64($oim);

// 保存文件到FastDFS
$tmp_name= $file["tmp_name"];
$name= $file["name"];
$fileid= fdfs_save_file($name, $tmp_name);
if(!$fileid) {
	bad_request($result);
	exit(1);
}
$fileid_base64= base64_encode($fileid);

// 生成缩略图
$tmp_thumbnail= thumbnail_make($tmp_name, "", "normal");
if(!$tmp_thumbnail) {
	fdfs_remove_file($fileid);
	bad_request($result);
	exit(1);
}

// 保存缩略图文件到FastDFS
$fileid_thumbnail= fdfs_save_slave_file($fileid, $name, $tmp_thumbnail);
unlink($tmp_thumbnail);
if(!$fileid_thumbnail) {
	fdfs_remove_file($fileid);
	bad_request($result);
	exit(1);
}
$fileid_thumbnail_base64= base64_encode($fileid_thumbnail);

// 更新oim信息
$dlurl_thumbnail= get_dlurl($fileid_thumbnail_base64);
oim_put_property($oim, "thumbnail", $dlurl_thumbnail);
$oim_base64= oim_to_string_base64($oim);

// 保存oim信息到FastDFS
if(!fdfs_put_property($fileid, "oim", $oim_base64)) {
	fdfs_remove_file($fileid);
	bad_request($result);
	exit(1);
}

// 输出结果
$dlurl= get_dlurl($fileid_base64, $oim_base64);
response_ok($result, $dlurl);
// done



/////////////////////////////////////// 
// 获取dlurl
// 成功返回true
function get_dlurl($fileid_base64, $oim_base64= "") {
	return result_make_dlurl(get_baseurl(), "putlocation", $fileid_base64, $oim_base64);
}


/////////////////////////////////////// 
// 输出包含“bad request”的报错结果
function bad_request($result) {
	echo result_bad_request_to_string($result);
}

/////////////////////////////////////// 
// 输出包含“not supported”的报错结果
function not_supported($result) {
	echo result_bad_request_to_string($result, "not supported");
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
