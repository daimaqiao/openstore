<?php
// api:upload.php
$result= array(
	"category"	=> "api:upload",
	"error"		=> "",
	"result"	=> ""
);

if($_SERVER["REQUEST_METHOD"] != "POST") {
	bad_request($result);
	exit(1);
}

$file= $_FILES["file"];
if($file["error"] > 0 || $file["size"] <= 0) {
	bad_request($result);
	exit(1);
}

$tracker= fastdfs_tracker_get_connection();
$storage= fastdfs_tracker_query_storage_store();
$fileid= fastdfs_storage_upload_by_filename1($file["tmp_name"],
	getExtName1($file["name"]), array(), null, $tracker, $storage);
if(!$fileid) {
	bad_request($result);
	exit(1);
}

response_ok($result, $fileid);
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


/////////////////////////////////////// 
// return the ext-name of a file-name,
// without the dot
function getExtName1($name) {
	if(($str= strrchr($name, ".")))
		return substr($str, 1);
	return null;
}



?>
