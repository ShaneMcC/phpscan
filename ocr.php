<?php
	require_once('functions.php');
	$file = isset($_REQUEST['img']) ? $_REQUEST['img'] : '';
	if ($file == '') { die('No File ('.$file.')'); }
	$file = str_replace('../', '', $file);
	$file = str_replace('..\\', '', $file);
	if (!file_exists(dirname(__FILE__) . '/' . $file)) { die('No Such File'); }
	
	$name = explode('.', $file);
	$ext = array_pop($name);
	$name = implode('.', $name);
	
	$source = dirname(__FILE__) . '/' . $file . '.txt';

	$data = file_get_contents($source);
	$data = htmlentities($data);
	
	doHeader('OCR For: '.$file);
	echo '	<div class="actions">';
	echo '	<a href="image.php?img=', $name, '.', $ext, '">View Image</a>';
	echo '	</div>';
	echo '	<br>';
	echo '<pre>';
	echo $data;
	echo '</pre>';
	doFooter();
?>