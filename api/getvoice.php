<?php
// api
// getvoice.php
// 1/2016.0415, BY daimaqiao.

include "../frag/openstore_1.0.php";
include "../frag/fastdfs.php";

// 初始化
$result= result_init("putvoice");

// 获取参数
$fileid= base64_decode($_GET["fileid"]);
if(!$fileid) {
	bad_request($result);
	exit(1);
}

// 生成下载链接
$baseurl= fdfs_dl_baseurl(false);
if(!$baseurl) {
	bad_request($result);
	exit(1);
}
$dlurl= $baseurl. "/". $fileid;

// 跳转并输出结果
header("Location: ". $dlurl);
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
// 是否https
function get_baseurl() {
	$url= check_https()? "https://": "http://";
	return $url. $_SERVER["HTTP_HOST"];
}


?>
