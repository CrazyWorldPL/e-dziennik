<?php
/*-------------------------------------------------------+
| e-dziennik
| Copyright (C) 2009-2010
| http://e-dziennik.xwp.pl/
+--------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: settings_main.php
| Author: Nick Jones (Digitanium)
| Edit by: Szymon (szygmon) Michalewicz
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
require_once "../maincore.php";
require_once THEMES."templates/admin_header.php";
include LOCALE.LOCALESET."admin/settings.php";

if (!iADMIN || !defined("iAUTH") || $_GET['aid'] != iAUTH) { redirect("../index.php"); }

if (isset($_POST['savesettings'])) {
	$error = 0;
	$siteintro = descript(stripslash($_POST['intro']));
	$sitefooter = descript(stripslash($_POST['footer']));
	$localeset = stripinput($_POST['localeset']);
	$old_localeset = stripinput($_POST['old_localeset']);
	$result = dbquery("UPDATE ".DB_SETTINGS." SET
		sitename='".stripinput($_POST['sitename'])."',
		siteurl='".stripinput($_POST['siteurl']).(strrchr($_POST['siteurl'],"/") != "/" ? "/" : "")."',
		sitebanner='".stripinput($_POST['sitebanner'])."',
		siteemail='".stripinput($_POST['siteemail'])."',
		siteusername='".stripinput($_POST['username'])."',
		siteintro='".addslashes(addslashes($siteintro))."',
		description='".stripinput($_POST['description'])."',
		keywords='".stripinput($_POST['keywords'])."',
		footer='".addslashes(addslashes($sitefooter))."',
		locale='$localeset',
		theme='".stripinput($_POST['theme'])."',
		default_search='".stripinput($_POST['default_search'])."',
		exclude_left='".stripinput($_POST['exclude_left'])."',
		exclude_upper='".stripinput($_POST['exclude_upper'])."',
		exclude_lower='".stripinput($_POST['exclude_lower'])."',
		exclude_right='".stripinput($_POST['exclude_right'])."'
	");
	if (!$result) { $error = 1; }
	redirect(FUSION_SELF.$aidlink."&error=".$error);
}

$settings2 = dbarray(dbquery("SELECT * FROM ".DB_SETTINGS));
$theme_files = makefilelist(THEMES, ".|..|templates", true, "folders");
$locale_files = makefilelist(LOCALE, ".|..", true, "folders");

ob_start();
opentable($locale['400']);
require_once ADMIN."settings_links.php";
echo "<form name='settingsform' method='post' action='".FUSION_SELF.$aidlink."'>\n";
echo "<table cellpadding='0' cellspacing='0' width='500' class='center'>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['402']."</td>\n";
echo "<td width='50%' class='tbl'><input type='text' name='sitename' value='".$settings2['sitename']."' maxlength='255' class='textbox' style='width:230px;' /></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['403']."</td>\n";
echo "<td width='50%' class='tbl'><input type='text' name='siteurl' value='".$settings2['siteurl']."' maxlength='255' class='textbox' style='width:230px;' /></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['404']."</td>\n";
echo "<td width='50%' class='tbl'><input type='text' name='sitebanner' value='".$settings2['sitebanner']."' maxlength='255' class='textbox' style='width:230px;' /></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['405']."</td>\n";
echo "<td width='50%' class='tbl'><input type='text' name='siteemail' value='".$settings2['siteemail']."' maxlength='128' class='textbox' style='width:230px;' /></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['406']."</td>\n";
echo "<td width='50%' class='tbl'><input type='text' name='username' value='".$settings2['siteusername']."' maxlength='32' class='textbox' style='width:230px;' /></td>\n";
echo "</tr>\n<tr>\n";
echo "<td valign='top' width='50%' class='tbl'>".$locale['407']."<br /><span class='small2'>".$locale['408']."</span></td>\n";
echo "<td width='50%' class='tbl'><textarea name='intro' cols='50' rows='6' class='textbox' style='width:230px;'>".phpentities(stripslashes($settings2['siteintro']))."</textarea></td>\n";
echo "</tr>\n<tr>\n";
echo "<td valign='top' width='50%' class='tbl'>".$locale['409']."</td>\n";
echo "<td width='50%' class='tbl'><textarea name='description' cols='50' rows='6' class='textbox' style='width:230px;'>".$settings2['description']."</textarea></td>\n";
echo "</tr>\n<tr>\n";
echo "<td valign='top' width='50%' class='tbl'>".$locale['410']."<br /><span class='small2'>".$locale['411']."</span></td>\n";
echo "<td width='50%' class='tbl'><textarea name='keywords' cols='50' rows='6' class='textbox' style='width:230px;'>".$settings2['keywords']."</textarea></td>\n";
echo "</tr>\n<tr>\n";
echo "<td valign='top' width='50%' class='tbl'>".$locale['412']."</td>\n";
echo "<td width='50%' class='tbl'><textarea name='footer' cols='50' rows='6' class='textbox' style='width:230px;'>".phpentities(stripslashes($settings2['footer']))."</textarea></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['417']."</td>\n";
echo "<td width='50%' class='tbl'><select name='localeset' class='textbox'>\n";
echo makefileopts($locale_files, $settings2['locale'])."\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['418'];

  if ($settings2['theme'] != str_replace(THEMES, "", substr(THEME, 0, strlen(THEME)-1))) {
  	echo "<div class='admin-message'>".$locale['global_302']."</div>\n";
  }

echo "</td>\n";
echo "<td width='50%' class='tbl'><select name='theme' class='textbox'>\n";
echo makefileopts($theme_files, $settings2['theme'])."\n";
echo "</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td width='50%' class='tbl'>".$locale['419']."</td>\n";
echo "<td width='50%' class='tbl'><select name='default_search' class='textbox'>\n[DEFAULT_SEARCH]</select></td>\n";
echo "</tr>\n<tr>\n";
echo "<td valign='top' width='50%' class='tbl'>".$locale['420']."<br /><span class='small2'>".$locale['424']."</span></td>\n";
echo "<td width='50%' class='tbl'><textarea name='exclude_left' cols='50' rows='5' class='textbox' style='width:230px;'>".$settings2['exclude_left']."</textarea></td>\n";
echo "</tr>\n<tr>\n";
echo "<td valign='top' width='50%' class='tbl'>".$locale['421']."<br /><span class='small2'>".$locale['424']."</span></td>\n";
echo "<td width='50%' class='tbl'><textarea name='exclude_upper' cols='50' rows='5' class='textbox' style='width:230px;'>".$settings2['exclude_upper']."</textarea></td>\n";
echo "</tr>\n<tr>\n";
echo "<td valign='top' width='50%' class='tbl'>".$locale['422']."<br /><span class='small2'>".$locale['424']."</span></td>\n";
echo "<td width='50%' class='tbl'><textarea name='exclude_lower' cols='50' rows='5' class='textbox' style='width:230px;'>".$settings2['exclude_lower']."</textarea></td>\n";
echo "</tr>\n<tr>\n";
echo "<td valign='top' width='50%' class='tbl'>".$locale['423']."<br /><span class='small2'>".$locale['424']."</span></td>\n";
echo "<td width='50%' class='tbl'><textarea name='exclude_right' cols='50' rows='5' class='textbox' style='width:230px;'>".$settings2['exclude_right']."</textarea></td>\n";
echo "</tr>\n<tr>\n";
echo "<td align='center' colspan='2' class='tbl'><br />";
echo "<input type='hidden' name='old_localeset' value='".$settings2['locale']."' />\n";
echo "<input type='submit' name='savesettings' value='".$locale['750']."' class='button' /></td>\n";
echo "</tr>\n</table>\n</form>\n";
closetable();

$cache = ob_get_contents();

unset($locale);
$default_search = "";
$dh = opendir(LOCALE.LOCALESET."search");
while (false !== ($entry = readdir($dh))) {
	if ($entry != "." && $entry != ".." && $entry != "index.php") {
		include LOCALE.LOCALESET."search/".$entry;
		foreach ($locale as $key => $value) {
			if (preg_match("/400/", $key)) {
				$entry = str_replace(".php", "", $entry);
				$default_search .= "<option value='".$entry."'".($settings2['default_search'] == $entry ? " selected='selected'" : "").">".$value."</option>\n";
			}
		}
		unset($locale);
	}
}
closedir($dh); unset($locale);
include LOCALE.LOCALESET."search.php";
$default_search .= "<option value='all'".($settings2['default_search'] == 'all' ? " selected='selected'" : "").">".$locale['407']."</option>\n";
$cache = str_replace("[DEFAULT_SEARCH]", $default_search, $cache);
ob_end_clean();
echo $cache;

require LOCALE.LOCALESET."global.php";
require_once THEMES."templates/footer.php";
?>
