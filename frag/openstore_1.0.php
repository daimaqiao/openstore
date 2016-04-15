<?php
// frag
// openstore_1.0.php
// 1/2016.0411, BY daimaqiao.


// 创建一个初始的结果对象
function result_init($apicall, $error="", $result="") {
	return array(
		"namespace"	=> "http://oim.daimaqiao.net/openstore_1.0",
		"version"	=> "1.0.1",
		"apicall"	=> $apicall,
		"error"		=> $error,
		"result"	=> $result
	);
}


// 重置结果对象
function result_reset(&$result, $error= "", $result= "") {
	$result["error"]= $error;
	$result["result"]= $result;
	return $result;
}


// 输出对象的JSON字符串
function result_to_string($result) {
	return json_encode($result,
		JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
}


// bad request
function result_bad_request_to_string($result, $error= "bad request") {
	$result["error"]= $error;
	$result["result"]= "";
	return json_encode($result,
		JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
}


// 输出对象的JSON字符串
function result_ok_to_string($result, $response= "ok") {
	$result["error"]= "";
	$result["result"]= $response;
	return json_encode($result,
		JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
}


// 根据apicall等信息，生成可以下载fileid对应内容的url（含oim）
// 成功返回url
function result_make_dlurl($baseurl, $apicall, $fileid, $oim= "") {
	$page= "";
	switch($apicall) {
	case "puttext":
	case "gettext":
		$page= "gettext.php";
		break;
	case "putimage":
	case "getimage":
		$page= "getimage.php";
		break;
	case "putvoice":
	case "getvoice":
		$page= "getvoice.php";
		break;
	case "putlocation":
	case "getlocation":
		$page= "getlocation.php";
		break;
	case "putavastar":
	case "getavastar":
		$page= "getavastar.php";
		break;
	default:
		return "";
	}// switch

	$url= $baseurl. "/openstore/api/". $page.
		"?fileid=". $fileid;
	if($oim)
		return $url. "&oim=". $oim;
	return $url;
}



?>
