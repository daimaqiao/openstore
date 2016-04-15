<?php
// frag
// openim_1.0.php
// 1/2016.0413, BY daimaqiao.


// 创建一个初始的oim对象
function oim_init($type, $size=0, $properties=array()) {
	return array(
		"namespace"	=> "http://oim.daimaqiao.net/openim.1.0",
		"version"	=> "1.0.0",
		"type"		=> $type,
		"size"		=> $size,
		"properties"=> $properties
	);
}


// 重置oim对象
function oim_reset(&$oim, $size= 0, $properties= array()) {
	$oim["size"]= $size;
	$oim["properties"]= $properties;
	return $oim;
}


// oim设置size
function oim_set_size(&$oim, $size) {
	$oim["size"]= $size;
	return $oim;
}
// oim设置properties
function oim_set_properties(&$oim, $properties) {
	$oim["properties"]= $properties;
	return $oim;
}
// oim加入property
function oim_put_property(&$oim, $key, $value) {
	if(!is_array($oim["properties"]))
		$oim["properties"]= array();
	$oim["properties"][$key]= $value;
	return $oim;
}
// oim移除property
function oim_remove_property(&$oim, $key) {
	if(is_array($oim["properties"]))
		unset($oim["properties"][$key]);
	return $oim;
}




// 输出oim的JSON字符串
function oim_to_string($oim) {
	return json_encode($oim,
		JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
}

// 输出oim的JSON字符串的base64转义
function oim_to_string_base64($oim) {
	$str= oim_to_string($oim);
	return base64_encode($str);
}




?>
