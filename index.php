<?php
	require_once('functions.php');

	doHeader();
	showItems(directoryToArray(dirname(__FILE__)));
	doFooter();
?>