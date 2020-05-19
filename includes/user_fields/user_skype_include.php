<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright ê 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: user_skype_include.php
| Author: bartek124
| E-Mail: bartek124@php-fusion.pl
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
	echo "<td class='tbl'>".$locale['uf_skype'].":</td>\n";
	echo "<td class='tbl'><input type='text' name='user_skype' value='".(isset($user_data['user_skype']) ? $user_data['user_skype'] : "")."' maxlength='20' class='textbox' style='width:200px;' /></td>\n";
	echo "</tr>\n";
} elseif ($profile_method == "display") {
	if ($user_data['user_skype']) {
		echo "<tr>\n";
		echo "<td width='1%' class='tbl1' style='white-space:nowrap'>".$locale['uf_skype']."</td>\n";
		echo "<td align='right' class='tbl1'><a href='callto://".str_replace(" ", "+", $user_data['user_skype'])."' title='".$locale['uf_skype']." ".$user_data['user_skype']."'>".$user_data['user_skype']."</a>&nbsp;<img style='vertical-align:middle;border:none' src='http://mystatus.skype.com/smallicon/".$user_data['user_skype']."' alt='".$user_data['user_skype']."' /></td>\n";
		echo "</tr>\n";
	}
} elseif ($profile_method == "validate_insert") {
	$db_fields .= ", user_skype";
	$db_values .= ", '".(isset($_POST['user_skype']) ? stripinput($_POST['user_skype']) : "")."'";
} elseif ($profile_method == "validate_update") {
	$db_values .= ", user_skype='".(isset($_POST['user_skype']) ? stripinput($_POST['user_skype']) : "")."'";
}
?>
