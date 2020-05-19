<?php
/*-------------------------------------------------------+
| e-dziennik
| Copyright (C) 2009-2010
| http://e-dziennik.xwp.pl/
+--------------------------------------------------------+
| Filename: semestr.php
| Author: Szymon (szygmon) Michalewicz
+-------------------------------------------------------*/
require_once "../maincore.php";
require_once THEMES."templates/admin_header.php";
include LOCALE.LOCALESET."admin/settings.php";

if (!iADMIN || !defined("iAUTH") || $_GET['aid'] != iAUTH) { redirect("../index.php"); }

if (isset($_POST['savesettings'])) {
	$result = dbquery("UPDATE ".DB_SETTINGS." SET semestr='".$_POST['semestr']."'");
	redirect(FUSION_SELF.$aidlink."&error=0");
}

$settings2 = dbarray(dbquery("SELECT * FROM ".DB_SETTINGS));

opentable($locale['400']);
require_once ADMIN."settings_links.php";
echo "<form name='settingsform' method='post' action='".FUSION_SELF.$aidlink."'>\n";
echo "<table cellpadding='0' cellspacing='0' width='500' class='center'>\n<tr>\n";
echo "<td width='50%' class='tbl' align='right'>Aktualny semestr:</td>\n";
echo "<td width='50%' class='tbl'><select name='semestr' class='textbox'>\n";
echo "<option value='1'".($settings2['semestr'] == 1 ? " selected='selected'" : "").">".$locale['910']." 1</option>\n";
echo "<option value='2'".($settings2['semestr'] == 2 ? " selected='selected'" : "").">".$locale['910']." 2</option>\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td align='center' colspan='2' class='tbl'><br />";
echo "<input type='hidden' name='old_localeset' value='".$settings2['locale']."' />\n";
echo "<input type='submit' name='savesettings' value='".$locale['750']."' class='button' /></td>\n";
echo "</tr>\n</table>\n</form>\n";
closetable();

require LOCALE.LOCALESET."global.php";
require_once THEMES."templates/footer.php";
?>
