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
| Filename: messages.php
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
require_once THEMES."templates/header.php";
include LOCALE.LOCALESET."messages.php";

if (!iTEACHER) { redirect("index.php"); }

add_to_title($locale['global_200'].$locale['400']);

$msg_settings = dbarray(dbquery("SELECT * FROM ".DB_MESSAGES_OPTIONS." WHERE user_id='0'"));

if (!isset($_GET['folder']) || !preg_check("/^(inbox|outbox|archive|options)$/", $_GET['folder'])) { $_GET['folder'] = "inbox"; }
if (isset($_POST['msg_send']) && isnum($_POST['msg_send'])) { $_GET['msg_send'] = $_POST['msg_send']; }
if (isset($_POST['msg_to_group'])) { $_GET['msg_to_group'] = $_POST['msg_to_group']; }

$error = ""; $msg_ids = ""; $check_count = 0;

if (isset($_POST['send_message'])) {
	$result = dbquery("SELECT * FROM ".DB_MESSAGES_OPTIONS." WHERE user_id='".$userdata['user_id']."'");
	if (dbrows($result)) {
		$my_settings = dbarray($result);
	} else {
		$my_settings['pm_save_sent'] = $msg_settings['pm_save_sent'];
		$my_settings['pm_email_notify'] = $msg_settings['pm_email_notify'];
	}
	$subject = stripinput(trim($_POST['subject']));
	$message = stripinput(trim($_POST['message']));
	if ($subject == "" || $message == "") { redirect(FUSION_SELF."?folder=inbox"); }
	$smileys = isset($_POST['chk_disablesmileys']) ? "n" : "y";
	require_once INCLUDES."sendmail_include.php";

	$msg_to_group = $_POST['user_types'];
	if (isset($_GET['do']) && $_GET['do'] == "s") {
			// Wysy³anie wiad do wsyztskich uczniów
			$result = dbquery(
				"SELECT u.user_id, u.user_name, u.user_email, mo.pm_email_notify FROM ".DB_USERS." u
				LEFT JOIN ".DB_MESSAGES_OPTIONS." mo USING(user_id)
				WHERE student_class='".$msg_to_group."'"
			);
			if (dbrows($result)) {
				while ($data = dbarray($result)) {
					$result2 = dbquery("INSERT INTO ".DB_MESSAGES." (message_to, message_from, message_subject, message_message, message_smileys, message_read, message_datestamp, message_folder) VALUES('".$data['user_id']."','".$userdata['user_id']."','".$subject."','".$message."','".$smileys."','0','".time()."','0')");
					$message_content = str_replace("[SUBJECT]", $subject, $locale['626']);
					$message_content = str_replace("[USER]", $userdata['user_name'], $message_content);
					$send_email = isset($data['pm_email_notify']) ? $data['pm_email_notify'] : $msg_settings['pm_email_notify'];
					if ($send_email == "1") { sendemail($data['user_name'], $data['user_email'], $settings['siteusername'], $settings['siteemail'], $locale['625'], $data['user_name'].$message_content); }
				}
			} else {
				redirect("educator.php?status=error");
			}
	} elseif (isset($_GET['do']) && $_GET['do'] == "p") {
			// Wysy³anie wiad do wszytskich rodziców
			$result0 = dbquery("SELECT user_id FROM ".DB_USERS." WHERE student_class='".$msg_to_group."'");
			if (dbrows($result0)) {
	  			while($data0 = dbarray($result0)) {
					$result = dbquery(
					"SELECT u.user_id, u.user_name, u.user_email, mo.pm_email_notify FROM ".DB_USERS." u
					LEFT JOIN ".DB_MESSAGES_OPTIONS." mo USING(user_id)
					WHERE id_child='".$data0['user_id']."'"
					);
					if ($data = dbarray($result)) {
						$result2 = dbquery("INSERT INTO ".DB_MESSAGES." (message_to, message_from, message_subject, message_message, message_smileys, message_read, message_datestamp, message_folder) VALUES('".$data['user_id']."','".$userdata['user_id']."','".$subject."','".$message."','".$smileys."','0','".time()."','0')");
						$message_content = str_replace("[SUBJECT]", $subject, $locale['626']);
						$message_content = str_replace("[USER]", $userdata['user_name'], $message_content);
						$send_email = isset($data['pm_email_notify']) ? $data['pm_email_notify'] : $msg_settings['pm_email_notify'];
						if ($send_email == "1") { sendemail($data['user_name'], $data['user_email'], $settings['siteusername'], $settings['siteemail'], $locale['625'], $data['user_name'].$message_content); }
					}
				}
			} else {
				redirect("educator.php?status=error");
			}
	} else { redirect("educator.php?status=error"); }
 
	
//???	
	if (!$error) {
		$result = dbquery(
			"SELECT COUNT(message_id) AS outbox_count, MIN(message_id) AS last_message FROM ".DB_MESSAGES."
			WHERE message_to='".$userdata['user_id']."' AND message_folder='1' GROUP BY message_to"
		);
		$cdata = dbarray($result);
		if ($my_settings['pm_save_sent']) {
			if ($msg_settings['pm_sentbox'] != "0" && ($cdata['outbox_count'] + 1) > $msg_settings['pm_sentbox']) {
				$result = dbquery("DELETE FROM ".DB_MESSAGES." WHERE message_id='".$cdata['last_message']."' AND message_to='".$userdata['user_id']."'");
			}
			if (isset($_POST['chk_sendtoall']) && isnum($_POST['msg_to_group'])) {
				$outbox_user = $userdata['user_id'];
			} elseif (isset($_GET['msg_send']) && isnum($_GET['msg_send'])) {
				$outbox_user = $_GET['msg_send'];
			} else {
				$outbox_user = "";
			}
			if ($outbox_user) { $result = dbquery("INSERT INTO ".DB_MESSAGES." (message_to, message_from, message_subject, message_message, message_smileys, message_read, message_datestamp, message_folder) VALUES ('".$userdata['user_id']."','".$outbox_user."','".$subject."','".$message."','".$smileys."','1','".time()."','1')"); }
		}
	}
	redirect("educator.php?status=sendmsg");
}

if (isset($_GET['msg_send']) && isnum($_GET['msg_send'])) {
	require_once INCLUDES."bbcode_include.php";
	if (isset($_POST['send_preview'])) {
		$subject = stripinput($_POST['subject']);
		$message = stripinput($_POST['message']);
		$message_preview = $message;
		if (isset($_POST['chk_sendtoall']) && isnum($_POST['msg_to_group'])) {
			$msg_to_group = $_POST['msg_to_group'];
			$msg_to_group_state = "";
			$msg_send_state = " disabled";
		} else {
			$msg_to_group = "";
			$msg_to_group_state = " disabled";
			$msg_send_state = "";
		}
		$disablesmileys_chk = isset($_POST['chk_disablesmileys']) ? " checked='checked'" : "";
		if (!$disablesmileys_chk) $message_preview = parsesmileys($message_preview);
		opentable($locale['438']);
		echo "<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>\n<tr>\n";
		echo "<td class='tbl1'>".nl2br(parseubb($message_preview))."</td>\n</tr>\n";
		echo "</table>\n";
		closetable();
	} else {
		$subject = ""; $message = ""; $msg_send_state = ""; $msg_to_group = "";
		$msg_to_group_state = " disabled"; $sendtoall_chk = ""; $disablesmileys_chk = "";	
	}	
	
	$reply_message = "";
	$user_types = $_GET['class'];
	if (isset($_GET['do']) && $_GET['do'] == "p") { $pa = $locale['640']; } else { $pa = ""; }
	add_to_title($locale['global_201'].$locale['420']);
	opentable($locale['641'].$pa.$locale['642'].$_GET['class']);
	echo "<form name='inputform' method='post' action='".FUSION_SELF."?msg_send=0&do=".$_GET['do']."' onsubmit=\"return ValidateForm(this)\">\n";
	echo "<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border'>\n";
	echo "<input type='hidden' name='user_types' value='".$user_types."' />\n";
	echo "<tr>\n<td align='right' class='tbl2' style='white-space:nowrap'>".$locale['405'].":</td>\n";
	echo "<td class='tbl1' colspan='2'><input type='text' name='subject' value='".$subject."' maxlength='32' class='textbox' style='width:250px;' /></td>\n</tr>\n";
	echo "<tr>\n<td align='right' class='tbl2' valign='top' style='white-space:nowrap'>".($reply_message ? $locale['433'] : $locale['422']).":</td>\n";
	echo "<td class='tbl1' colspan='2'><textarea name='message' cols='75' rows='15' class='textbox' style='width:98%'>".$message."</textarea></td>\n</tr>\n";
	echo "<tr>\n<td align='right' class='tbl2' valign='top'></td>\n<td class='tbl1' colspan='2'>\n";
	echo display_bbcodes("98%", "message")."</td>\n</tr>\n";
	echo "<tr>\n<td align='right' class='tbl2' valign='top' style='white-space:nowrap'>".$locale['425'].":</td>\n";
	echo "<td class='tbl1' colspan='2'>\n<input type='checkbox' name='chk_disablesmileys' value='y'".$disablesmileys_chk." />".$locale['427']."</td>\n</tr>\n";
	echo "</table>\n";
	echo "<table border='0' cellpadding='0' cellspacing='0' width='100%'>\n";
	echo "<tr>\n<td class='tbl'><a href='educator.php'>".$locale['435']."</a></td>\n";
	echo "<td align='right' class='tbl'>";
	echo "<input type='submit' name='send_message' value='".$locale['430']."' class='button' />\n</td>\n</tr>\n";
	echo "</table>\n</form>\n";
	closetable();
	echo "<script type='text/javascript'>function ValidateForm(frm){\n";
	echo "if (frm.subject.value == \"\" || frm.message.value == \"\"){\n";
	echo "alert(\"".$locale['486']."\");return false;}\n";
	echo "}\n</script>\n";

} else {
	redirect("educator.php");
}

require_once THEMES."templates/footer.php";
?>
