<?php
# api:querydl.php
$result= array(
	"category"	=> "api:querydl",
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

$fileurl= "http://". $storage["ip_addr"]. ":8888/". $fileid;
response_ok($result, $fileurl);
// done



/////////////////////////////////////// 
// output a json of 'bad request'
function response_ok($result, $ok) {
	$result["result"]= $ok;
	echo json_encode($result, JSON_UNESCAPED_SLASHES);
}


/////////////////////////////////////// 
// output a json of 'bad request'
function bad_request($result) {
	$result["error"]= "bad request";
	echo json_encode($result, JSON_UNESCAPED_SLASHES);
}




?>
