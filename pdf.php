<?php
	$file = isset($_REQUEST['img']) ? $_REQUEST['img'] : '';
	
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

        $source = dirname(__FILE__) . '/' . $file;
        $img = dirname(__FILE__) . '/.cache/img.' . $file . '.jpg';
	
	$thisfile = dirname(__FILE__) . '/.cache/img'. $file . '.pdf';

        if (!file_exists($source)) {
                exec('convert '.$source.' '.$img);
        }
	if (!file_exists($thisfile)) {
		exec('convert -density 75 -page A4 -compress jpeg '.$img.' '.$thisfile);
	}
	
	header('Content-Type: application/pdf');
	header('Content-Disposition: attachment; filename="'.$file.'.pdf"');
	header('Content-Length: '.filesize($thisfile));
	readfile($thisfile);
?>
