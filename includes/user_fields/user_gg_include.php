<?php
/*-------------------------------------------------------+
| e-dziennik
| Copyright (C) 2009 PowerKomp Corporation
| http://e-dziennik.za.pl/
+--------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: user_gg_include.php
| Author: bartek124
| E-Mail: bartek124@php-fusion.pl
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
if (!defined("IN_FUSION")) { die("Access Denied"); }

if ($profile_method == "input") {
	echo "<tr>\n";
	echo "<td class='tbl'>".$locale['uf_gg'].":</td>\n";
	echo "<td class='tbl'><input type='text' name='user_gg' value='".(isset($user_data['user_gg']) ? $user_data['user_gg'] : "")."' maxlength='20' class='textbox' style='width:200px;' /></td>\n";
	echo "</tr>\n";
} elseif ($profile_method == "display") {
	if ($user_data['user_gg']) {
		echo "<tr>\n";
		echo "<td width='1%' class='tbl1' style='white-space:nowrap'>".$locale['uf_gg']."</td>\n";
		echo "<td align='right' class='tbl1'><a href='gg:".$user_data['user_gg']."' title='".$locale['uf_gg']." ".$user_data['user_gg']."'>".$user_data['user_gg']."</a>&nbsp;<img style='vertical-align:middle;border:none' src='http://status.gadu-gadu.pl/users/status.asp?id=".$user_data['user_gg']."&styl=1' alt='".$user_data['user_gg']."' /></td>\n";
		echo "</tr>\n";
	}
} elseif ($profile_method == "validate_insert") {
	$db_fields .= ", user_gg";
	$db_values .= ", '".(isset($_POST['user_gg']) && isnum($_POST['user_gg']) ? $_POST['user_gg'] : "")."'";
} elseif ($profile_method == "validate_update") {
	$db_values .= ", user_gg='".(isset($_POST['user_gg']) && isnum($_POST['user_gg']) ? $_POST['user_gg'] : "")."'";
}
?>
