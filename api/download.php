<?php
// api:download.php
// 1/2016.0305, BY shb.
$result= array(
	"category"	=> "api:download",
	"error"		=> "",
	"result"	=> ""
);

if($_SERVER["REQUEST_METHOD"] != "POST") {
	bad_request($result);
	exit(1);
}

$fileid= $_POST["fileid"];
if(!$fileid) {
	bad_request($result);
	exit(1);
}

$storage= fastdfs_tracker_query_storage_store();
if(!$storage) {
	bad_request($result);
	exit(1);
}

$fileurl= makeFileurl($storage["ip_addr"]. ":8888/", $fileid);
header("Location: ". $fileurl);
response_fileurl($result, $fileurl);
// done



/////////////////////////////////////// 
// output a json of 'fileurl'
function response_fileurl($result, $fileurl) {
	$result["result"]= $fileurl;
	echo json_encode($result, JSON_UNESCAPED_SLASHES);
}


/////////////////////////////////////// 
// output a json of 'bad request'
function bad_request($result) {
	$result["error"]= "bad request";
	echo json_encode($result, JSON_UNESCAPED_SLASHES);
}


/////////////////////////////////////// 
// makeFileurl
function makeFileurl($serverHost, $fileid) {
	$fileurl= $serverHost. "/". $fileid;
	if(array_key_exists("HTTPS", $_SERVER) && $_SERVER["HTTPS"])
		$fileurl= "https://". $fileurl;
	else
		$fileurl= "http://". $fileurl;

	return $fileurl;
}




?>
