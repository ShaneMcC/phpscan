<?php
	define('ALLOWDELETE', true);
	define('SOFTDELETE', true);
//	define('LOCALSCANNER', true);

	function dtaSort($a, $b) {
		return strnatcmp($a['name'], $b['name']);
	}

	/**
	 * Get a listing of a directory as an array.
	 * Based on http://snippets.dzone.com/posts/show/155
	 *
	 * @param $directory Directory to list
	 * @param $recursive (Default = true) Should subdirectories be recursed into?
	 * @param $fullpath (Default = true) Should full path be stored for each entry
	 *                  or just relative to the above entry?
	 * @param $includedirectories (Default = true) If recursive is false, should
	 *                            directories be included in the list?
	 */
	function directoryToArray($directory, $recursive = true, $fullpath = true, $includedirectories = true) {
		$array_items = array();
		if ($handle = opendir($directory)) {
			while (false !== ($file = readdir($handle))) {
				if ($file != '.' && $file != '..') {
					$nicefile = (($fullpath) ? $directory.'/' : '') . $file;
					$nicefile = preg_replace('@//@si', '/', $nicefile);
					$fullfile = $directory.'/'.$file;
					$array_item = array();
					
					if (is_dir($fullfile)) {
						if ($includedirectories || $recursive) {
							$array_item['name'] = $nicefile;
							$array_item['contents'] = ($recursive) ? directoryToArray($fullfile, $recursive) : array();
							$array_items[] = $array_item;
						}
					} else {
						$array_item['name'] = $nicefile;
						
						$array_items[] = $array_item;
					}
				}
			}
			closedir($handle);
		}
		
		usort($array_items, "dtaSort");
		return $array_items;
	}
	
	function doHeader($title = 'Image Viewer') {
		header('Content-Type: text/html; charset=UTF-8');
		echo '<html>';
		echo '<head>';
		echo '<title>', $title,'</title>';
		?>
			<style type="text/css">
				div.imagewrapper {
					float: left;
					width: 210px;
					padding: 10px;
					/* border: 1px dotted red; */
				}
				
				div.imagewrapper img {
					display: block;
					width: 210px;
					height: 297px;
					/* border: 1px dotted green; */
				}
				
				div.imagewrapper div.title {
					font-weight: bold;
					text-align: center;
					/* border: 1px dotted blue; */
				}
				
				div.imagewrapper div.actions {
					text-align: center;
					/* border: 1px dotted blue; */
				}
				
				div.clear {
					clear: both;
				}
				
				div.body {
					margin-left: 20px;
					margin-right: 20px;
					min-height: 100%;
					position:relative;
					height:auto !important; /* real browsers */
					height:100%; /* IE6: treaded as min-height*/
					min-height:100%; /* real browsers */
				}
				
				div.head h1 {
					color: #17355c;
					border-bottom: solid 2px #4f80bd;
					margin-bottom: 2px;
					padding-left: 10px;
				}
				
				div.foot {
					border-top: solid 2px #4f80bd;
					margin-top: 50px;
					color: #4f80bd;
					text-align: right;
					width: 100%;
					padding-right: 10px;
					clear: right;
					float: right;
					font-size: small;
/*					position:absolute;
					bottom:0;*/
					padding-bottom: 10px;
				}
				
			div.head div.subtext {
				color: #4f80bd;
				text-align: right;
				width: 100%;
				margin-bottom: 10px;
			}
				
				div.links {
					color: #4f80bd;
					font-size: small;
					float: left;
					clear: left;
					margin-left: 10px;
				}
				
				span.subtext {
					font-style: italic;
				}
				
				img.maxheight {
					height: 100% - 100px;
				}
				
				img.maxwidth {
					max-width: 100%
				}
				
				img {
					border: 0;
				}
				
				pre {
					border: dotted 1px #17355c;
					background: #85c1e3;
					margin-left: 50px;
					margin-right: 50px;
					padding: 10px;
				}
				
				pre.prettyprint {
					border: dotted 1px #17355c;
					background: #adf;
					margin-left: 50px;
					margin-right: 50px;
					padding: 10px;
				}
			</style>
		<?php
		echo '</head>';
		echo '<body>';
		echo '<div class="content">';
		echo '<div class="head">';
		echo '<h1>', $title, '</h1>';
		echo '<div class="links">';
		echo 'Navigation: <a href="index.php">Home</a>';
		if (defined('LOCALSCANNER') && LOCALSCANNER) {
			echo ' | <a href="newScan.php">Scan Files (ADF, Gray)</a>';
			echo ' | <a href="newScan.php?flat">Scan Files (Flatbed, Gray)</a>';
			echo ' | <a href="newScan.php?colour">Scan Files (ADF, Colour)</a>';
			echo ' | <a href="newScan.php?flat&colour">Scan Files (Flatbed, Colour)</a>';
		}
		echo '</div>';
		echo '<div class="subtext">';
		echo '<form name="search" action="search.php" method="post">';
		echo '<input name="query" width="200px">';
		echo ' ';
		echo '<input type="submit" value="Search">';
		echo '</form>';
		echo '</div>';
		echo '</div>';
		echo '<div class="clear"></div>';
		echo '<div class="body">';
	}
	
	function doFooter() {
		echo '</div>';
		echo '<div class="clear"></div>';
		echo '<div class="foot">';
		echo 'Image Viewier (c) 2009 - ', date('Y'), ' Shane Mc Cormack';
		echo '</div>';
		echo '</div>';
		echo '</body>';
		echo '</html>';
	}
	
	$validExts = array('tif');

	function showItems($listing) {
		global $validExts;

		$listing = array_reverse($listing);
		
		foreach ($listing as $item) {
			if (isset($item['contents'])) {
				if ($item['name'] != dirname(__FILE__).'/.cache' && $item['name'] != dirname(__FILE__).'/deleted' && $item['name'] != dirname(__FILE__).'/.git') {
					showItems($item['contents']);
				}
			} else {
				// Preserve full name
				$fullname = $item['name'];
				// Remove the base directory name from the file name
				$name = str_replace(dirname(__FILE__), '', $item['name']);
				// And any leading slashes
				$name = preg_replace('#[/\\\]+#', '', $name);
				// Now get the extention and the name/dir of the file.
				$name = explode('.', $name);
				$ext = array_pop($name);
				$name = implode('.', $name);
				
				if (in_array($ext, $validExts)) {
					echo '<div class="imagewrapper">';
					echo '	<a href="image.php?img=', $name, '.', $ext, '">';
					echo '		<img src="img.php?thumb=', $name, '.', $ext, '">';
					echo '	</a>';
/*					echo '	<div class="title">';
					echo '		', $name;
					echo '	</div>'; */
					echo '	<div class="actions">';
					$ocrs = array();
					if (file_exists($fullname.'.tesseract.txt')) { $ocrs[] = 'T'; }
					if (file_exists($fullname.'.gocr.txt')) { $ocrs[] = 'G'; }
					echo '	<a href="ocr.php?img=', $name, '.', $ext, '">View OCR</a> (', implode('/', $ocrs), ')';
					if (defined('ALLOWDELETE') && ALLOWDELETE) {
						echo ' - <a href="delete.php?img=', $name, '.', $ext, '">Delete</a>';
					}
					echo '	</div>';
					echo '</div>';
				}
			}
		}
	}
?>
