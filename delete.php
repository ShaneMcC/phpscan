<?php
	require_once('functions.php');
	if (!defined('ALLOWDELETE') || !ALLOWDELETE) {
		echo 'Deleting disabled.';
	}

	$file = isset($_REQUEST['img']) ? $_REQUEST['img'] : '';
	if ($file == '') { die('No File ('.$file.')'); }
	$file = str_replace('../', '', $file);
	$file = str_replace('..\\', '', $file);
	if (!file_exists(dirname(__FILE__) . '/' . $file)) { die('No Such File'); }

	if (!defined('ALLOWDELETE') || !SOFTDELETE) {
		@unlink(dirname(__FILE__) . '/' . $file);
		@unlink(dirname(__FILE__) . '/' . $file . '.tesseract.txt');
		@unlink(dirname(__FILE__) . '/' . $file . '.gocr.txt');
	} else {
		@rename(dirname(__FILE__) . '/' . $file, dirname(__FILE__) . '/deleted/' . $file);
		@rename(dirname(__FILE__) . '/' . $file . '.tesseract.txt', dirname(__FILE__) . '/deleted/' . $file . '.tesseract.txt');
		@rename(dirname(__FILE__) . '/' . $file . '.gocr.txt', dirname(__FILE__) . '/deleted/' . $file . '.gocr.txt');
	}
	
	doHeader('Deleted: '.$file);
	echo 'Done.';
	echo '<br><br>[<a href="index.php">Return to list</a> ]';
	doFooter();
?>
