<?php
	require_once('functions.php');

	$query = $_REQUEST['query'];

	doHeader('Image Search: '.htmlspecialchars($_REQUEST['query']));

	if (!empty($query)) {
		$files = array();
		$ofiles = array();
		exec('egrep -lRiI --include "*.txt"  \''.escapeshellcmd($query).'\' '.dirname(__FILE__).' | sort', $output);
		foreach ($output as $line) {
			$ofiles[] = preg_replace('#.(gocr|tesseract).txt$#', '', $line);
		}
		$ofiles = array_unique($ofiles);
		foreach ($ofiles as $f) { $files[]['name'] = $f; }

		showItems($files);
	} else {
		echo 'No search query specified.';
	}
	doFooter();
?>
