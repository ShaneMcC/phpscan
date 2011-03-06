<?php
	$file = isset($_REQUEST['thumb']) ? $_REQUEST['thumb'] : (isset($_REQUEST['img']) ? $_REQUEST['img'] : '');
	
	if ($file == '') { die('No File ('.$file.')'); }
	
	$file = str_replace('../', '', $file);
	$file = str_replace('..\\', '', $file);
	
	if (!file_exists(dirname(__FILE__) . '/' . $file)) { die('No Such File'); }
	
	if (!file_exists(dirname(__FILE__).'/.cache')) {
		mkdir(dirname(__FILE__).'/.cache');
	}
	
	$name = explode('.', $file);
	$ext = array_pop($name);
	$name = implode('.', $name);
	
	$type = isset($_REQUEST['thumb']) ? 'thumb' : 'img';
	$thisfile = dirname(__FILE__) . '/.cache/' . $type . '.' . $file . '.jpg';
	
	$source = dirname(__FILE__) . '/' . $file;
	$thumb = dirname(__FILE__) . '/.cache/thumb.' . $file . '.jpg';
	$img = dirname(__FILE__) . '/.cache/img.' . $file . '.jpg';

	if (!file_exists($img)) {
		exec('convert '.$source.' '.$img);
	}
	if (!file_exists($thumb)) {
		exec('convert -resize 210x297! '.$img.' '.$thumb);
	}
	
	header('Content-Type: image/jpeg');
        header('Content-Length: '.filesize($thisfile));
        readfile($thisfile);
?>
