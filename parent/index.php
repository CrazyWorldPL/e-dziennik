<?php
/*-------------------------------------------------------+
| e-dziennik
| Copyright (C) 2009 PowerKomp Corporation
| http://e-dziennik.za.pl/
+--------------------------------------------------------+
| Filename: index.php
| Author: Szymon (szygmon) Michalewicz
+-------------------------------------------------------*/
require_once "../maincore.php";
require_once THEMES."templates/header.php";

if (!iPARENT) { redirect("../index.php"); }
if (!isset($_GET['pagenum']) || !isnum($_GET['pagenum'])) { $_GET['pagenum'] = 1; }

$admin_images = true;

// Display admin panels & pages
opentable("Panel rodzica");
echo "<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>\n<tr>\n";
echo "<td class='tbl1'>";
$result = dbquery("SELECT * FROM ".DB_ADMIN." WHERE admin_page='0' ORDER BY admin_title");
$rows = dbrows($result);
if ($rows != 0) {
	$counter = 0; $columns = 4;
	$align = $admin_images ? "center" : "left";
	echo "<table cellpadding='0' cellspacing='0' width='100%'>\n<tr>\n";
	while ($data = dbarray($result)) {
		if (admr($data['admin_rights'])) {
			if ($counter != 0 && ($counter % $columns == 0)) echo "</tr>\n<tr>\n";
			echo "<td align='$align' width='25%' class='tbl'>";
			if ($admin_images) {
				echo "<a href='".$data['admin_link']."'><img src='".get_image("ac_".$data['admin_title'])."' alt='".$data['admin_title']."' style='border:0px;' /><br />\n".$data['admin_title']."</a>";
			} else {
				echo "".THEME_BULLET." <a href='".$data['admin_link']."'>".$data['admin_title']."</a>";
			}
			echo "</td>\n";
			$counter++;
		}
	}
	echo "</tr>\n</table>\n";
}
echo "</td>\n</tr>\n</table>\n";
closetable();

require_once THEMES."templates/footer.php";
?>
