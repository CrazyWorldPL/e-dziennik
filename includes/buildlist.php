<?php
/*---------------------------------------------------+
| e-dziennik
| Copyright (C) 2009-2010
| http://e-dziennik.xwp.pl/
+----------------------------------------------------+
| buildlist.php - iLister enginge.
+----------------------------------------------------+
| Copyright (C) 2005 Johs Lind
| http://www.geltzer.dk/
| Inspired by: Php-fusion 6 coding
| Edit by: Szymon (szygmon) Michalewicz
+----------------------------------------------------+
| Released under the terms & conditions of v2 of the
| GNU General Public License. For details refer to
| the included gpl.txt file or visit http://gnu.org
+----------------------------------------------------*/
if (!defined("IN_FUSION")) { die("Access Denied"); }

// images ------------------------
$temp = opendir(IMAGES);
while ($file = readdir($temp)) {
	if (!in_array($file, array(".", "..", "/", "index.php")) && !is_dir(IMAGES.$file)) {
		$image_files[] = "['Images: ".$file."','".$settings['siteurl']."images/".$file."'],\n";
	}
}
closedir($temp);
		
// compile list -----------------
if (isset($image_files)) {
	$indhold = "var tinyMCEImageList = new Array(\n";
	for ($i = 0; $i < count($image_files); $i++){
		$indhold .= $image_files[$i];
	}
	$lang = strlen($indhold) - 2;
	$indhold = substr($indhold, 0, $lang);
	$indhold = $indhold.");\n\n";
	$fp = fopen(IMAGES."imagelist.js", "w");
	fwrite($fp, $indhold);
	fclose($fp);
}
?>
