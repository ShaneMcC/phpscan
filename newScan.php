<?php
	require_once('functions.php');

	doHeader('New Scan');
	exec('scanimage -L | awk -F "\\\\\`" \'{print $2}\' | awk -F "\'" \'{print $1}\'', $output);
	$scanner = $output[0];
	$output = array();
	if (!file_exists(dirname(__FILE__).'/.temp')) {
		mkdir(dirname(__FILE__).'/.temp');
	}
	$scanSource = (isset($_REQUEST['flat']) ? 'Normal' : 'ADF Front');
	$extra = (isset($_REQUEST['flat']) ? '--batch-count 1' : '');
	$colour = (isset($_REQUEST['colour']) ? 'Color' : 'Gray');
	$cmd = 'scanimage --device "'.$scanner.'" --mode '.$colour.' --source "'.$scanSource.'" --format=tiff '.$extra.' --batch='.dirname(__FILE__).'/.temp/`date +%Y-%m-%d-%H-%M-%S-`%d.tif --resolution 300 -x 210 -y 297 --quality-cal=yes --quality-scan=yes 2>&1';

	echo '<pre>';
	echo $cmd;
	echo "\n", '--------------', "\n";
	
	exec($cmd, $output);
	foreach ($output as $line) {
		echo htmlspecialchars($line), "\n";
	}
	echo '</pre>';
	
	$scanned = directoryToArray(dirname(__FILE__).'/.temp/');
	foreach ($scanned as $file) {
		$output = array();
		echo '<h3>', $file['name'], '<h3>';
		exec('tesseract '.$file['name'].' '.dirname(__FILE__) . '/' . basename($file['name']). '.tesseract 2>&1', $output);

		exec('convert "'.$file['name'].'" "'.$file['name'].'.temp.jpeg" 2>&1', $output);
		exec('gocr -o '.dirname(__FILE__) . '/' . basename($file['name']).'.gocr.txt '.$file['name'].'.temp.jpeg 2>&1', $output);
		unlink($file['name'].'.temp.jpeg');

		rename($file['name'], dirname(__FILE__) . '/' . basename($file['name']));

		echo '<pre>';
		foreach ($output as $line) {
			echo htmlspecialchars($line), "\n";
		}
		echo '</pre>';
	}
	
	doFooter();
?>