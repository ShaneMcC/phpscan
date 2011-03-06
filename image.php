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
	
	$source = dirname(__FILE__) . '/' . $file;

//	$data = file_get_contents($source);
//	$data = htmlentities($data);
//	$data = nl2br($data);
	
	doHeader('Image: '.$file);
	echo '	<div class="actions">';
	echo '	<a href="ocr.php?img=', $name, '.', $ext, '">View OCR</a>';
	echo '  <a href="pdf.php?img=', $name, '.', $ext, '">Download PDF</a>';
	echo '	</div>';
	echo '	<br>';
	echo '	<a href="img.php?img=', $file, '">';
	echo '		<img class="maxheight maxwidth" src="img.php?img=', $file, '">';
	echo '	</a>';
	doFooter();
?>
