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
$tmpname= $file["tmp_name"];
$extname= getExtName1($file["name"]);
$fileid= fastdfs_storage_upload_by_filename1($tmpname,
	$extname, array(), null, $tracker, $storage);
if(!$fileid) {
	bad_request($result);
	exit(1);
}

$thumbnailFlag= array_key_exists("thumbnail", $_POST)? $_POST["thumbnail"]: false;
if($thumbnailFlag && checkImage($file)) {
	if(($thumbnail= makeThumbnail($tmpname))) {
		$prefix="-thumbnail";
		$fileid2= fastdfs_storage_upload_slave_by_filename1($thumbnail,
			$fileid, $prefix, $extname, array(), $tracker, $storage);
		if(!$fileid2) {
			bad_request($result);
			exit(1);
		}
		response_ok2($result, $fileid, $fileid2);
	} else {
		bad_request($result);
		exit(1);
	}
} else
	response_ok($result, $fileid);
// done


/////////////////////////////////////// 
// output a json of 'bad request'
function response_ok($result, $ok) {
	$result["result"]= $ok;
	echo json_encode($result, JSON_UNESCAPED_SLASHES);
}
function response_ok2($result, $ok, $ok2) {
	$result["result"]= $ok;
	$result["result2"]= $ok2;
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


/////////////////////////////////////// 
// return true if $file is a image file
function checkImage($file) {
	$type= $file["type"];
	if($type == "image/jpeg" || $type == "image/pjgeg" ||
		$type == "image/png" || $type == "image/gif")
			return true;

	$ext= getExtName1($file["name"]);
	switch(strtolower($ext)) {
	case "jpg":
	case "jpeg":
	case "png":
	case "gif":
		return true;
	}// switch

	return false;
}

/////////////////////////////////////// 
// return true if ok
function makeThumbnail($input_name, $output_name="") {
	$root= $_SERVER["CONTEXT_DOCUMENT_ROOT"];
	$tool= $root. "/bin/thumbnail.sh";
	$command= $tool. " ". $input_name. " ". $output_name;
	return exec($command);
}






?>
