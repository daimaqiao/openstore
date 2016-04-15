<?php
// frag
// fastdfs.php
// 1/2016.0413, BY daimaqiao.


// 保存文件
// 成功返回fileid
function fdfs_save_file($name, $path) {
	$tracker= fastdfs_tracker_get_connection();
	$storage= fastdfs_tracker_query_storage_store();
	$extn= fdfs_extname($name);
	$id= fastdfs_storage_upload_by_filename1($path,
		$extn, array(), null, $tracker, $storage);
	return $id;
}


// 删除文件
// 成功返回true
function fdfs_remove_file($fileid) {
	return fastdfs_storage_delete_file1($fileid);

}


// 保存附属文件
// 成功返回附属文件的fileid
function fdfs_save_slave_file($fileid, $name, $path) {
	$tracker= fastdfs_tracker_get_connection();
	$storage= fastdfs_tracker_query_storage_store();
	$extn= fdfs_extname($name);
	$prefix="-thumbnail";
	$id= fastdfs_storage_upload_slave_by_filename1($path,
		$fileid, $prefix, $extn, array(), $tracker, $storage);
	return $id;
}


// 返回文件名的扩展名，不包含点（如：jpg）
function fdfs_extname($name) {
	if(($str= strrchr($name, ".")))
		return substr($str, 1);
	return "";
}


// 重置属性列表
// 成功返回true
function fdfs_reset_properties($fileid, $properties= array(), $override= true) {
	$type= $override ? FDFS_STORAGE_SET_METADATA_FLAG_OVERWRITE
		: FDFS_STORAGE_SET_METADATA_FLAG_MERGE;
	fastdfs_storage_set_metadata1($fileid, $properties, $type);
	return true;
}


// 更新属性项
// 成功返回true
function fdfs_put_property($fileid, $key, $value) {
	return fastdfs_storage_set_metadata1($fileid, array($key=> $value),
		FDFS_STORAGE_SET_METADATA_FLAG_MERGE);
}


// 清除属性项
// 成功返回true
function fdfs_remove_property($fileid, $key) {
	$properties= fastdfs_storage_get_metadata1($fileid);
	if(is_array($properties))
		unset($properties[$key]);
	else
		$properties= array();
	return fdfs_reset_properties($fileid, $properties);
}


// 返回用于下载的baseurl，不含路径与查询
function fdfs_dl_baseurl($https= false) {
	$storage= fastdfs_tracker_query_storage_store();
	if($storage) {
		$schema= $https? "https": "http";
		$host= $storage["ip_addr"];
		$port= "8888";
		$dlurl= $schema. "://". $host;
		if($port != 80)
			$dlurl= $dlurl. ":". $port;

		return $dlurl;
	}
	return "";
}




?>
