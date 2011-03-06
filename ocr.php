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
	
	$tesseractsource = dirname(__FILE__) . '/' . $file . '.tesseract.txt';
	$gocrsource = dirname(__FILE__) . '/' . $file . '.gocr.txt';

	if (file_exists($tesseractsource)) {
		$tesseract = file_get_contents($tesseractsource);
		$tesseract = htmlentities($tesseract);
	} else { $tesseract = '<strong>No Tesseract text found.</strong>'; }

	if (file_exists($gocrsource)) {
		$gocr = file_get_contents($gocrsource);
		$gocr = htmlentities($gocr);
	} else { $gocr = '<strong>No GOCR text found.</strong>'; }
	
	doHeader('OCR For: '.$file);
	echo '	<div class="actions">';
	echo '	<a href="image.php?img=', $name, '.', $ext, '">View Image</a>';
	echo '	</div>';
	echo '	<br>';
	echo '<h2>Tesseract</h2>';
	echo '<pre>';
	echo $tesseract;
	echo '</pre>';
	echo '<br><br>';
	echo '<h2>GOCR</h2>';
	echo '<pre>';
	echo $gocr;
	echo '</pre>';
	doFooter();
?>