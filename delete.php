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

	unlink(dirname(__FILE__) . '/' . $file);	
	
	doHeader('Deleted: '.$file);
	echo 'Done.';
	echo '<br><br>[<a href="index.php">Return to list</a> ]';
	doFooter();
?>
