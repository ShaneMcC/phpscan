<?php
	require_once('functions.php');

	$query = $_REQUEST['query'];

	doHeader('Image Search: '.htmlspecialchars($_REQUEST['query']));
	
	if (!empty($query)) {
		$files = array();
		exec('egrep -lRiI --include "*.txt"  \''.escapeshellcmd($query).'\' '.dirname(__FILE__).' | sort', $output);
		foreach ($output as $line) {
			$files[]['name'] = preg_replace('#.txt$#', '', $line);
		}

		showItems($files);
		// var_dump($files);
	} else {
		echo 'No search query specified.';
	}
	doFooter();
?>
