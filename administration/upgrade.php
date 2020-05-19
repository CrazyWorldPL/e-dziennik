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
| Filename: upgrade.php
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
if (file_exists(LOCALE.LOCALESET."admin/upgrade.php")) {
	include LOCALE.LOCALESET."admin/upgrade.php";
} else {
	include LOCALE."English/admin/upgrade.php";
}

if (!iADMIN || !defined("iAUTH") || $_GET['aid'] != iAUTH) { redirect("../index.php"); }

opentable($locale['400']);
echo "<div style='text-align:center'><br />\n";
echo "<form name='upgradeform' method='post' action='".FUSION_SELF.$aidlink."'>\n";

if (str_replace(".", "", $settings['ed_version']) == "101") {
	if (!isset($_POST['stage'])) {
		echo $locale['416'];
		echo "<input type='hidden' name='stage' value='2'>\n";
		echo "<input type='submit' name='upgrade' value='".$locale['417']."' class='button'><br /><br />\n";
	} elseif (isset($_POST['upgrade']) && isset($_POST['stage']) && $_POST['stage'] == 2) {
		$result = dbquery("UPDATE ".DB_SETTINGS." SET ed_version='1.02', data_actual='0'");
		$result = dbquery("UPDATE ".DB_SETTINGS." SET version='7.00.07', data_actual='0'");
		
		// Info po aktualizacji - b³±d/ok
		if (!$result) { 
	  		echo $locale['418']."<br /><br />\n"; 
	  	} else {
			echo $locale['419']."<br /><br />\n";
		}
	}
echo "</form>";
} elseif ($settings['aktualizacje'] == "1"){
	//Sprawdzanie aktualnej wersji	
	$myver = $settings['ed_version'];
	$myver = str_replace(".","",$myver);
	$aver = str_replace(".","",$settings['actual_version']);
	
	if($aver > $myver){
		echo "<img src='images/new_version.png' align='center' class='actual-img' />
		<a href='http://e-dziennik.xwp.pl' target='_blank' style='color:red;'><b><big>".$locale['402']."</big></b></a>
		<div align='center' width='100%' style='background-color:red; padding:6px; color:white;'>".$locale['410'].$settings['ed_version']."<br />
		".$locale['411'].$settings['actual_version']."</div><br />".$locale['412'];
	} else if($aver == $myver){
		echo "<img src='images/aktual_version.png' align='center' class='actual-img' />
		<b><big><font style='color:green;'>".$locale['403']."</font></big></b>
		<div align='center' width='100%' style='background-color:green; padding:6px; color:white;'>".$locale['410'].$settings['ed_version']."<br />
		".$locale['411'].$settings['actual_version']."</div><br />";
	} else {
		echo "<img src='images/b_download_ver.png' align='center' class='actual-img' />
		<a href='http://e-dziennik.xwp.pl' target='_blank' style='color:orange;'><b><big>".$locale['404']."</big></b></a>";
			if ($settings['aktualizacje'] == "1"){
		  		echo "<br/><div align='center' width='100%' style='background-color:orange; padding:6px; color:white;'>".$locale['500']."</div>";
 			}
	}
	//----------
} else {
  echo "<img src='images/b_download_ver.png' align='center' class='actual-img' />
  <a href='http://e-dziennik.xwp.pl/' target='_blank' style='color:orange;'><b><big>".$locale['414']."</big></b></a>
  <div align='center' width='100%' style='background-color:orange; padding:6px; color:white;'>".$locale['410'].$settings['ed_version']."</div><br />
  ".$locale['415'];

}

echo "<hr/><big><b>".$locale['408']."</b></big><br/><br/>";

if (isset($_POST['savesettings'])) {
	$result = dbquery("UPDATE ".DB_SETTINGS." SET
		aktualizacje='".(isNum($_POST['aktualizacje']) ? $_POST['aktualizacje'] : "0")."'
	");
	redirect(FUSION_SELF.$aidlink);
}

echo "<form name='settingsform' method='post' action='".FUSION_SELF.$aidlink."'>
<table align='center' cellpadding='0' cellspacing='0' width='500'>
<tr>
<td width='50%' class='tbl'>".$locale['405']."</td>
<td width='50%' class='tbl'>
<select name='aktualizacje' class='textbox'>
<option value='1'".($settings['aktualizacje'] == "1" ? " selected" : "").">".$locale['406']."</option>
<option value='0'".($settings['aktualizacje'] == "0" ? " selected" : "").">".$locale['407']."</option>
</select></td>
</tr>
<tr>
<td align='center' colspan='2' class='tbl'><br>
<input type='submit' name='savesettings' value='".$locale['409']."' class='button'></td>
</tr>
</table>
</form></div>";

closetable();

require_once THEMES."templates/footer.php";
?>
