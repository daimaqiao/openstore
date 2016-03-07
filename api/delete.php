<?php
// api:delete.php
// 1/2016.0305, BY daimaqiao.
$result= array(
	"category"	=> "api:delete",
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

$bool= fastdfs_storage_delete_file1($fileid);
if(!$bool) {
	bad_request($result);
	exit(1);
}

$thumbnailFlag= array_key_exists("thumbnail", $_POST)? $_POST["thumbnail"]: false;
if($thumbnailFlag) {
	// also delete the thumbnail file
	$thumbnail= fastdfs_gen_slave_filename($fileid, "-thumbnail");
	$boolThumbnail= false;
	if($thumbnail)
		$boolThumbnail= fastdfs_storage_delete_file1($thumbnail);
	response_deleted_with_thumbnail($result, "deleted", $thumbnail ? "deleted" : "");
} else
	response_deleted($result, "deleted");
// done



/////////////////////////////////////// 
// output a json of 'deleted'
function response_deleted($result, $deleted) {
	$result["result"]= $deleted;
	echo json_encode($result, JSON_UNESCAPED_SLASHES);
}
function response_deleted_with_thumbnail($result, $deleted, $thumbnail) {
	$result["result"]= $deleted;
	$result["thumbnail"]= $thumbnail;
	echo json_encode($result, JSON_UNESCAPED_SLASHES);
}


/////////////////////////////////////// 
// output a json of 'bad request'
function bad_request($result) {
	$result["error"]= "bad request";
	echo json_encode($result, JSON_UNESCAPED_SLASHES);
}




?>
