<?php

// show version
echo fastdfs_client_version(). "<br />";

// test tracker
$tracker= fastdfs_tracker_get_connection();
if(!fastdfs_active_test($tracker)) {
	echo "ERROR: Trackers down!<br />";
	echo "(". fastdfs_get_last_error_no(). ")". fastdfs_get_last_error_info(). "<br />";
	exit(1);
}

// dump store list
$storelist= fastdfs_tracker_query_storage_store_list();
var_dump($storelist);
echo "<br />";

// test storage
$storage= fastdfs_tracker_query_storage_store();
if(!$storage) {
	echo "ERROR: None storage!<br />";
	echo "(". fastdfs_get_last_error_no(). ")". fastdfs_get_last_error_info(). "<br />";
	exit(1);
}
$serverStorage= fastdfs_connect_server($storage["ip_addr"], $storage["port"]);
if(!$serverStorage || !fastdfs_active_test($serverStorage)) {
	echo "ERROR: Storage down!<br />";
	echo "(". fastdfs_get_last_error_no(). ")". fastdfs_get_last_error_info(). "<br />";
	exit(1);
}

// upload test
//$storage["sock"]= $serverStorage["sock"];
$fileinfo= fastdfs_storage_upload_by_filename("/home/shb/temp/sample_file.txt",
	null, array(), null, $tracker, $storage);
if(!$fileinfo) {
	echo "ERROR: fastdfs_storage_upload_by_filename() failed!<br />";
	echo "(". fastdfs_get_last_error_no(). ")". fastdfs_get_last_error_info(). "<br />";
	exit(1);
}

// show file informations
$groupname= $fileinfo["group_name"];
$remotefilename= $fileinfo["filename"];
$finfo= fastdfs_get_file_info($groupname, $remotefilename);
if(!$finfo) {
	echo "ERROR: fastdfs_get_file_info() failed!<br />";
	echo "(". fastdfs_get_last_error_no(). ")". fastdfs_get_last_error_info(). "<br />";
	exit(1);
}
echo "file id: ". $groupname. "/". $remotefilename. "<br />";
var_dump($finfo);
echo "<br />";

// test slave file
$masterfilename= $remotefilename;
$prefixname= "-copy";
$sfinfo= fastdfs_storage_upload_slave_by_filename("/home/shb/temp/sample_file2.txt",
	$groupname, $masterfilename, $prefixname);
if(!$sfinfo) {
	echo "ERROR: fastdfs_storage_upload_slave_by_filename() failed!<br />";
	echo "(". fastdfs_get_last_error_no(). ")". fastdfs_get_last_error_info(). "<br />";
	// nothing
} else {
	// check the slave file
	var_dump($sfinfo);
	echo "<br />";
	$generated_filename= fastdfs_gen_slave_filename($masterfilename, $prefixname);
	echo "generated name : ". $generated_filename. "<br />";
	echo "slave file name: ". $sfinfo["filename"]. "<br />";

	// delete the slave file
	$return= fastdfs_storage_delete_file($sfinfo["group_name"], $sfinfo["filename"]);
	echo "fastdfs_storage_delete_file() returns ". $return. "<br />";
}

// delete the master file
$return= fastdfs_storage_delete_file($groupname, $masterfilename);
echo "fastdfs_storage_delete_file() returns ". $return. "<br />";


?>
