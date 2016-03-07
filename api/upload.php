<?php
// api:upload.php
// 1/2016.0305, BY daimaqiao.
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
	if(($tmpthumbnail= makeThumbnail($tmpname))) {
		$prefix="-thumbnail";
		$thumbnail= fastdfs_storage_upload_slave_by_filename1($tmpthumbnail,
			$fileid, $prefix, $extname, array(), $tracker, $storage);
		// delete the temp file
		unlink($tmpthumbnail);
		if(!$thumbnail) {
			// the master file should be deleted
			fastdfs_storage_delete_file1($fileid);
			bad_request($result);
			exit(1);
		}
		response_fileid_with_thumbnail($result, $fileid, $thumbnail);
	} else {
		bad_request($result);
		exit(1);
	}
} else
	response_fileid($result, $fileid);
// done


/////////////////////////////////////// 
// output a json of 'fileid'
function response_fileid($result, $fileid) {
	$result["result"]= $fileid;
	echo json_encode($result, JSON_UNESCAPED_SLASHES);
}
function response_fileid_with_thumbnail($result, $fileid, $thumbnail) {
	$result["result"]= $fileid;
	$result["thumbnail"]= $thumbnail;
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
	if($type == "image/jpeg" || $type == "image/pjgeg" || $type == "image/png")
		return true;

	$ext= getExtName1($file["name"]);
	switch(strtolower($ext)) {
	case "jpg":
	case "jpeg":
	case "png":
		return true;
	}// switch

	return false;
}

/////////////////////////////////////// 
// return true if ok
function makeThumbnail($input_name, $output_name="") {
	$php= $_SERVER["SCRIPT_FILENAME"];
	$path1= getDirectory($php);
	$path2= getDirectory($path1);
	if($path2) {
		$tool= $path2. "/bin/thumbnail.sh";
		$command= $tool. " ". $input_name. " ". $output_name;
		return exec($command);
	}
	return "";
}

/////////////////////////////////////// 
// return the directory
function getDirectory($filepath) {
	$pos= strrpos($filepath, "/");
	if($pos)
		return substr($filepath, 0, $pos);
	return $filepath;
}




?>
