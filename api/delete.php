<?php
# api:delete.php
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

response_ok($result, "deleted");
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
