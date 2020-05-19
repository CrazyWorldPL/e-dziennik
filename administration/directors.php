<?php
/*-------------------------------------------------------+
| e-dziennik
| Copyright (C) 2009-2010
| http://e-dziennik.xwp.pl/
+--------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright (C) 2002 - 2010 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: directors.php
| Author: Nick Jones (Digitanium)
| Edit by: szygmon
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
include LOCALE.LOCALESET."admin/directors.php";

if (!iADMIN || !defined("iAUTH") || $_GET['aid'] != iAUTH) { redirect("../index.php"); }

if (isset($_GET['status']) && !isset($message)) {
	if ($_GET['status'] == "sn") {
		$message = $locale['400'];
	} elseif ($_GET['status'] == "su") {
		$message = $locale['401'];
	} elseif ($_GET['status'] == "del") {
		$message = $locale['402'];
	} elseif ($_GET['status'] == "pw") {
		$message = $locale['global_182'];
	}
	if ($message) { echo "<div id='close-message'><div class='admin-message'>".$message."</div></div>\n"; }
}

if (isset($_POST['cancel'])) {
	redirect(FUSION_SELF.$aidlink);
}

if (isset($_POST['add_admin']) && (isset($_POST['user_id']) && isnum($_POST['user_id']))) {
	if (check_admin_pass(isset($_POST['admin_password']) ? stripinput($_POST['admin_password']) : "")) {
		$result = dbquery("UPDATE ".DB_USERS." SET user_level='105' WHERE user_id='".$_POST['user_id']."'");
		set_admin_pass(isset($_POST['admin_password']) ? stripinput($_POST['admin_password']) : "");
		redirect(FUSION_SELF.$aidlink."&status=sn", true);
	} else {
		redirect(FUSION_SELF.$aidlink."&status=pw");
	}
}

if (isset($_GET['remove']) && (isset($_GET['remove']) && isnum($_GET['remove']) && $_GET['remove'] != 1)) {
	if (check_admin_pass(isset($_POST['admin_password']) ? stripinput($_POST['admin_password']) : "")) {
		$result = dbquery("UPDATE ".DB_USERS." SET user_admin_password='', user_level='104' WHERE user_id='".$_GET['remove']."'");
		set_admin_pass(isset($_POST['admin_password']) ? stripinput($_POST['admin_password']) : "");
		redirect(FUSION_SELF.$aidlink."&status=del", true);
	} else {
		if (isset($_POST['confirm'])) {
			echo "<div id='close-message'><div class='admin-message'>".$locale['global_182']."</div></div>\n";
		}
		opentable($locale['470']);
		echo "<div style='text-align:center'>\n";
		echo "<form action='".FUSION_SELF.$aidlink."&amp;remove=".$_GET['remove']."' method='post'>\n";
		echo $locale['471']."<br /><br />\n<input class='textbox' type='password' name='admin_password' /><br /><br />\n";
		echo "<input class='button' type='submit' name='confirm' value='".$locale['472']."' />\n";
		echo "<input class='button' type='submit' name='cancel' value='".$locale['473']."' />\n";
		echo "</form>\n</div>\n";
		closetable();
	}
}

	opentable($locale['410']);
	if (!isset($_POST['search_users']) || !isset($_POST['search_criteria'])) {
		echo "<form name='searchform' method='post' action='".FUSION_SELF.$aidlink."'>\n";
		echo "<table cellpadding='0' cellspacing='0' width='450' class='center'>\n";
		echo "<tr>\n<td align='center' class='tbl'>".$locale['411']."<br /><br />\n";
		echo "<input type='text' name='search_criteria' class='textbox' style='width:300px' />\n</td>\n";
		echo "</tr>\n<tr>\n<td align='center' class='tbl'>\n";
		echo "<label><input type='radio' name='search_type' value='user_name' checked='checked' />".$locale['413']."</label>\n";
		echo "<label><input type='radio' name='search_type' value='user_id' />".$locale['412']."</label></td>\n";
		echo "</tr>\n<tr>\n<td align='center' class='tbl'><input type='submit' name='search_users' value='".$locale['414']."' class='button' /></td>\n";
		echo "</tr>\n</table>\n</form>\n";
	} elseif (isset($_POST['search_users']) && isset($_POST['search_criteria'])) {
		$mysql_search = "";
		if ($_POST['search_type'] == "user_id" && isnum($_POST['search_criteria'])) {
			$mysql_search .= "user_id='".$_POST['search_criteria']."' ";
		} elseif ($_POST['search_type'] == "user_name" && preg_match("/^[-0-9A-Z_@\s]+$/i", $_POST['search_criteria'])) {
			$mysql_search .= "user_name LIKE '".$_POST['search_criteria']."%' ";
		}
		if ($mysql_search) {
			$result = dbquery("SELECT user_id, user_name FROM ".DB_USERS." WHERE ".$mysql_search." AND user_level='104' ORDER BY user_name");
		}
		if (isset($result) && dbrows($result)) {
			echo "<form name='add_users_form' method='post' action='".FUSION_SELF.$aidlink."'>\n";
			echo "<table cellpadding='0' cellspacing='1' width='450' class='tbl-border center'>\n";
			$i = 0; $users = "";
			while ($data = dbarray($result)) {
				$row_color = ($i % 2 == 0 ? "tbl1" : "tbl2"); $i++;
				$users .= "<tr>\n<td class='$row_color'><label><input type='radio' name='user_id' value='".$data['user_id']."' /> ".$data['user_name']."</label></td>\n</tr>";
			}
			if ($i > 0) {
				echo "<tr>\n<td class='tbl2'><strong>".$locale['413']."</strong></td>\n</tr>\n";
				echo $users."<tr>\n<td align='center' class='tbl'>\n";
				if (!check_admin_pass(isset($_POST['admin_password']) ? stripinput($_POST['admin_password']) : "")) {
					echo $locale['447']." <input type='password' name='admin_password' class='textbox' style='width:150px;' /><br /><br />\n";
				}
				echo "<br />\n<input type='submit' name='add_admin' value='".$locale['410']."' class='button' />\n";
				echo "</td>\n</tr>\n";
			} else {
				echo "<tr>\n<td align='center' class='tbl'>".$locale['418']."<br /><br />\n";
				echo "<a href='".FUSION_SELF.$aidlink."'>".$locale['419']."</a>\n</td>\n</tr>\n";
			}
			echo "</table>\n</form>\n";
		} else {
			echo "<table cellpadding='0' cellspacing='1' width='450' class='tbl-border center'>\n";
			echo "<tr>\n<td align='center' class='tbl'>".$locale['418']."<br /><br />\n";
			echo "<a href='".FUSION_SELF.$aidlink."'>".$locale['419']."</a>\n</td>\n</tr>\n</table>\n";
		}
	}
	closetable();

	opentable($locale['420']);
	$i = 0;
	$result = dbquery("SELECT user_id, user_name, name, surname, user_level FROM ".DB_USERS." WHERE user_level='105' ORDER BY user_level DESC, user_name");
	echo "<table cellpadding='0' cellspacing='1' width='450' class='tbl-border center'>\n<tr>\n";
	echo "<td class='tbl2'>".$locale['421']."</td>\n";
	echo "<td align='center' width='1%' class='tbl2' style='white-space:nowrap'>".$locale['423']."</td>\n";
	echo "</tr>\n";
	while ($data = dbarray($result)) {
		$row_color = $i % 2 == 0 ? "tbl1" : "tbl2";
		echo "<tr>\n<td class='$row_color'>".$data['name']." ".$data['surname']." (".$data['user_name'].")</td>\n";
		echo "<td align='center' width='1%' class='$row_color' style='white-space:nowrap'>\n";
		if ($data['user_level'] == "103" && $userdata['user_id'] == "1") { $can_edit = true;
		} elseif ($data['user_level'] != "103") { $can_edit = true;
		} else { $can_edit = false; }
		if ($can_edit == true && $data['user_id'] != "1") {
			echo "<a href='".FUSION_SELF.$aidlink."&amp;remove=".$data['user_id']."' onclick=\"return confirm('".$locale['460']."');\">".$locale['427']."</a>\n";
		}
		echo "</td>\n</tr>\n";
		$i++;
	}
	echo "</table>\n";
	closetable();


require_once THEMES."templates/footer.php";
?>
