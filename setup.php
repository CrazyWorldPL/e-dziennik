<?php
/*-------------------------------------------------------+
| e-dziennik
| Copyright (C) 2009-2010
| http://e-dziennik.xwp.pl/
+--------------------------------------------------------+
| Plik: setup.php
| Autor: Szymon (szygmon) Michalewicz
+----------------------------------------------------*/
define("FUSION_SELF", basename($_SERVER['PHP_SELF']));
$db_connect = false;
$db_select = false;
$step = (isset($_GET['step']) ? $_GET['step'] : "0");
if (isset($_POST['localeset']) && file_exists("locale/".$_POST['localeset']) && is_dir("locale/".$_POST['localeset'])) {
	include "locale/".$_POST['localeset']."/setup.php";
} else {
	$_POST['localeset'] = "Polish";
	include "locale/Polish/setup.php";
}

if (isset($_POST['step']) && $_POST['step'] == "7") {
	header("Location: index.php");
}

echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">\n";
echo "<html>\n<head>\n";
echo "<title>".$locale['title']."</title>\n";
echo "<meta http-equiv='Content-Type' content='text/html; charset=".$locale['charset']."'>\n";
echo "<link rel='stylesheet' href='themes/templates/setup_styles.css' type='text/css' />\n";
echo "</head>\n<body>\n";

echo "<table cellpadding='0' cellspacing='0' width='100%'>\n<tr>\n";
echo "<td class='full-header'><img src='images/e-dziennik.png'></td>\n";
echo "</tr>\n</table>\n";

echo "<table cellpadding='0' cellspacing='0' width='100%'>\n<tr>\n";
echo "<td class='sub-header'>".$locale['sub-title']."</td>\n";
echo "</tr>\n<tr>\n";
echo "<td colspan='2' style='height:10px;background-color:#f6a504;'></td>\n";
echo "</tr>\n</table>\n";

echo "<br><br>\n";

echo "<form name='setupform' method='post' action='setup.php'>\n";
echo "<table align='center' cellpadding='0' cellspacing='1' width='450' class='tbl-border'>\n<tr>\n";
echo "<td class='tbl2'><strong>";

if (!isset($_POST['step']) || $_POST['step'] == "" || $_POST['step'] == "1") {
	echo $locale['001'];
} elseif (isset($_POST['step']) && $_POST['step'] == "2") {
	echo $locale['002'];
} elseif (isset($_POST['step']) && $_POST['step'] == "3") {
	echo $locale['003'];
} elseif (isset($_POST['step']) && $_POST['step'] == "4") {
	echo $locale['004'];
} elseif (isset($_POST['step']) && $_POST['step'] == "5") {
	echo $locale['005'];
} elseif (isset($_POST['step']) && $_POST['step'] == "6") {
	echo $locale['006'];
} 

echo "</strong></td>\n</tr>\n<tr>\n<td class='tbl1'style='text-align:center'>\n";

// J_zyk
if (!isset($_POST['step']) || $_POST['step'] == "" || $_POST['step'] == "1") {
	$locale_files = makefilelist("locale/", ".|..", true, "folders");
	$locale_list = makefileopts($locale_files);
	echo $locale['010']."<br /><br />";
	echo "<select name='localeset' class='textbox' style='margin-top:5px'>\n";
	echo $locale_list."</select><br/><br/>\n";
	echo "</td>\n</tr>\n<tr>\n<td class='tbl2' style='text-align:center'>\n";
	echo "<input type='hidden' name='step' value='2'>\n";
	echo "<input type='submit' name='next' value='".$locale['007']."' class='button'>\n";
}
//
// Chmody

if (isset($_POST['step']) && $_POST['step'] == "2") {
	if (is_writable("administration/db_backups") && 
	is_writable("images") && 
	is_writable("images/avatars") && 
	is_writable("conf.php")) {
		$write_check = true;
	} else {
		$write_check = false;
	}
	echo $locale['020']."<br /><br />\n";
	echo "<table align='center' cellpadding='0' cellspacing='0' width='100%'>\n<tr>\n";
	echo "<tr>\n<td class='tbl1'>administration/db_backups</td>\n";
	echo "<td class='tbl1' style='text-align:right'>".(is_writable("administration/db_backups") ? "<span class='passed'>".$locale['023']."</span>" : "<span class='failed'>".$locale['024']."</span>")."</td>\n</tr>\n";
	echo "<tr>\n<td class='tbl1'>images</td>\n";
	echo "<td class='tbl1' style='text-align:right'>".(is_writable("images") ? "<span class='passed'>".$locale['023']."</span>" : "<span class='failed'>".$locale['024']."</span>")."</td>\n</tr>\n";
	echo "<tr>\n<td class='tbl1'>images/imagelist.js</td>\n";
	echo "<td class='tbl1' style='text-align:right'>".(is_writable("images/imagelist.js") ? "<span class='passed'>".$locale['023']."</span>" : "<span class='failed'>".$locale['024']."</span>")."</td>\n</tr>\n";
	echo "<tr>\n<td class='tbl1'>images/avatars</td>\n";
	echo "<td class='tbl1' style='text-align:right'>".(is_writable("images/avatars") ? "<span class='passed'>".$locale['023']."</span>" : "<span class='failed'>".$locale['024']."</span>")."</td>\n</tr>\n";
	echo "<tr>\n<td class='tbl1'>config.php</td>\n";
	echo "<td class='tbl1' style='text-align:right'>".(is_writable("conf.php") ? "<span class='passed'>".$locale['023']."</span>" : "<span class='failed'>".$locale['024']."</span>")."</td>\n</tr>\n";
	echo "</table><br><br>\n";
	if ($write_check) {
		echo $locale['021']."\n";
		echo "</td>\n</tr>\n<tr>\n<td class='tbl2' style='text-align:center'>\n";
		echo "<input type='hidden' name='localeset' value='".stripinput($_POST['localeset'])."'>\n";
		echo "<input type='hidden' name='step' value='3'>\n";
		echo "<input type='submit' name='next' value='".$locale['007']."' class='button'>\n";
	} else {
		echo $locale['022']."\n";
		echo "</td>\n</tr>\n<tr>\n<td class='tbl2' style='text-align:center'>\n";
		echo "<input type='hidden' name='localeset' value='".stripinput($_POST['localeset'])."'>\n";
		echo "<input type='hidden' name='step' value='2'>\n";
		echo "<input type='submit' name='next' value='".$locale['008']."' class='button'>\n";
	}
}
//

// Dane SQL - instalacja norm
if (isset($_POST['step']) && $_POST['step'] == "3") {
	echo $locale['030']."<br /><br />\n";
	echo "<table align='center' cellpadding='0' cellspacing='0' width='100%'>\n<tr>\n";
	echo "<td class='tbl1'>".$locale['031']."</td>\n";
	echo "<td class='tbl1' style='text-align:right'><input type='text' value='localhost' name='db_host' class='textbox' style='width:200px'></td>\n</tr>\n";
	echo "<tr>\n<td class='tbl1'>".$locale['032']."</td>\n";
	echo "<td class='tbl1' style='text-align:right'><input type='text' value='' name='db_user' class='textbox' style='width:200px'></td>\n</tr>\n";
	echo "<tr>\n<td class='tbl1'>".$locale['033']."</td>\n";
	echo "<td class='tbl1' style='text-align:right'><input type='password' value='' name='db_pass' class='textbox' style='width:200px'></td>\n</tr>\n";
	echo "<tr>\n<td class='tbl1'>".$locale['034']."</td>\n";
	echo "<td class='tbl1' style='text-align:right'><input type='text' value='' name='db_name' class='textbox' style='width:200px'></td>\n</tr>\n";
	echo "<tr>\n<td class='tbl1'>".$locale['035']."</td>\n";
	echo "<td class='tbl1' style='text-align:right'><input type='text' value='ed_' name='db_prefix' class='textbox' style='width:200px'></td>\n</tr>\n";
	echo "</table>\n";
	echo "</td>\n</tr>\n<tr>\n<td class='tbl2' style='text-align:center'>\n";
	echo "<input type='hidden' name='localeset' value='".stripinput($_POST['localeset'])."'>\n";
	echo "<input type='hidden' name='step' value='4'>\n";
	echo "<input type='submit' name='next' value='".$locale['007']."' class='button'>\n";
}
// Instalacja SQL
if (isset($_POST['step']) && $_POST['step'] == "4") {
	$db_host = stripinput(trim($_POST['db_host']));
	$db_user = stripinput(trim($_POST['db_user']));
	$db_pass = stripinput(trim($_POST['db_pass']));
	$db_name = stripinput(trim($_POST['db_name']));
	$db_prefix = stripinput(trim($_POST['db_prefix']));
	$db_connect = mysqli_connect($db_host, $db_user, $db_pass);
	$db_select = @mysqli_select_db($db_connect,$db_name);
	if (!$db_connect) {
		die ("Error database connect!");
	} 
	if ($db_connect) {
		$config = "<?php\n";
		$config .= "// database settings\n";
		$config .= "$"."db_host = "."\"".$db_host."\";\n";
		$config .= "$"."db_user = "."\"".$db_user."\";\n";
		$config .= "$"."db_pass = "."\"".$db_pass."\";\n";
		$config .= "$"."db_name = "."\"".$db_name."\";\n";
		$config .= "$"."db_prefix = "."\"".$db_prefix."\";\n";
		$config .= "define("."\""."DB_PREFIX"."\"".", "."\"".$db_prefix."\");\n";
		$config .= "?>";
		$temp = fopen("conf.php","w");
		if (fwrite($temp, $config)) {
			fclose($temp);
			$fail = false;
			$result = dbquery($db_connect, "DROP TABLE IF EXISTS ".$db_prefix."admin");
			$result = dbquery($db_connect, "CREATE TABLE ".$db_prefix."admin (
			admin_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
			admin_rights VARCHAR(3) NOT NULL DEFAULT '',
			admin_image VARCHAR(50) NOT NULL DEFAULT '',
			admin_title VARCHAR(50) NOT NULL DEFAULT '',
			admin_link VARCHAR(100) NOT NULL DEFAULT 'reserved',
			admin_page TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
			PRIMARY KEY (admin_id)
			) engine = innodb;");
			
			if (!$result) { $fail = true; }
			
			$result = dbquery($db_connect, "DROP TABLE IF EXISTS ".$db_prefix."bbcodes");
			$result = dbquery($db_connect, "CREATE TABLE ".$db_prefix."bbcodes (
			bbcode_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
			bbcode_name VARCHAR(20) NOT NULL DEFAULT '',
			bbcode_order SMALLINT(5) UNSIGNED NOT NULL,
			PRIMARY KEY (bbcode_id),
			KEY bbcode_order (bbcode_order)
			) engine = innodb;");
		
			if (!$result) { $fail = true; }
		
			$result = dbquery($db_connect, "DROP TABLE IF EXISTS ".$db_prefix."captcha");
			$result = dbquery($db_connect, "CREATE TABLE ".$db_prefix."captcha (
			captcha_datestamp INT(10) UNSIGNED NOT NULL DEFAULT '0',
			captcha_ip VARCHAR(20) NOT NULL,
			captcha_encode VARCHAR(32) NOT NULL DEFAULT '',
			captcha_string VARCHAR(15) NOT NULL DEFAULT '',
			KEY captcha_datestamp (captcha_datestamp)
			) engine = innodb;");
			
			if (!$result) { $fail = true; }
		
			$result = dbquery($db_connect, "DROP TABLE IF EXISTS ".$db_prefix."flood_control");
			$result = dbquery($db_connect, "CREATE TABLE ".$db_prefix."flood_control (
			flood_ip VARCHAR(20) NOT NULL DEFAULT '0.0.0.0',
			flood_timestamp INT(5) UNSIGNED NOT NULL DEFAULT '0',
			KEY flood_timestamp (flood_timestamp)
			) engine = innodb;");
			
			if (!$result) { $fail = true; }
			
			$result = dbquery($db_connect, "DROP TABLE IF EXISTS ".$db_prefix."infusions");
			$result = dbquery($db_connect, "CREATE TABLE ".$db_prefix."infusions (
			inf_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
			inf_title VARCHAR(100) NOT NULL DEFAULT '',
			inf_folder VARCHAR(100) NOT NULL DEFAULT '',
			inf_version VARCHAR(10) NOT NULL DEFAULT '0',
			PRIMARY KEY (inf_id)
			) engine = innodb;");
			
			if (!$result) { $fail = true; }
			
			$result = dbquery($db_connect, "DROP TABLE IF EXISTS ".$db_prefix."messages");
			$result = dbquery($db_connect, "CREATE TABLE ".$db_prefix."messages (
			message_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
			message_to MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
			message_from MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
			message_subject VARCHAR(100) NOT NULL DEFAULT '',
			message_message TEXT NOT NULL,
			message_smileys CHAR(1) NOT NULL DEFAULT '',
			message_read TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
			message_datestamp INT(10) UNSIGNED NOT NULL DEFAULT '0',
			message_folder TINYINT(1) UNSIGNED NOT NULL DEFAULT  '0',
			PRIMARY KEY (message_id),
			KEY message_datestamp (message_datestamp)
			) engine = innodb;");
			
			$result = dbquery($db_connect, "DROP TABLE IF EXISTS ".$db_prefix."messages_options");
			$result = dbquery($db_connect, "CREATE TABLE ".$db_prefix."messages_options (
			user_id MEDIUMINT(8) UNSIGNED NOT NULL DEFAULT '0',
			pm_email_notify tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
			pm_save_sent tinyint(1) UNSIGNED NOT NULL DEFAULT '0',
			pm_inbox SMALLINT(5) UNSIGNED DEFAULT '0' NOT NULL,
			pm_savebox SMALLINT(5) UNSIGNED DEFAULT '0' NOT NULL,
			pm_sentbox SMALLINT(5) UNSIGNED DEFAULT '0' NOT NULL,
			PRIMARY KEY (user_id)
			) engine = innodb;");
			
			if (!$result) { $fail = true; }
			
			$result = dbquery($db_connect, "DROP TABLE IF EXISTS ".$db_prefix."online");
			$result = dbquery($db_connect, "CREATE TABLE ".$db_prefix."online (
			online_user VARCHAR(50) NOT NULL DEFAULT '',
			online_ip VARCHAR(20) NOT NULL DEFAULT '',
			online_lastactive INT(10) UNSIGNED NOT NULL DEFAULT '0'
			) engine = innodb;");
			
			if (!$result) { $fail = true; }
			
			$result = dbquery($db_connect, "DROP TABLE IF EXISTS ".$db_prefix."panels");
			$result = dbquery($db_connect, "CREATE TABLE ".$db_prefix."panels (
			panel_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
			panel_name VARCHAR(100) NOT NULL DEFAULT '',
			panel_filename VARCHAR(100) NOT NULL DEFAULT '',
			panel_content TEXT NOT NULL,
			panel_side TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
			panel_order SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
			panel_type VARCHAR(20) NOT NULL DEFAULT '',
			panel_access TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
			panel_display TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
			panel_status TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
			PRIMARY KEY (panel_id),
			KEY panel_order (panel_order)
			) engine = innodb;");
			
			if (!$result) { $fail = true; }
			
			$result = dbquery($db_connect, "DROP TABLE IF EXISTS ".$db_prefix."settings");
			$result = dbquery($db_connect, "CREATE TABLE ".$db_prefix."settings (
			sitename VARCHAR(200) NOT NULL DEFAULT '',
			siteurl VARCHAR(200) NOT NULL DEFAULT '',
			sitebanner VARCHAR(200) NOT NULL DEFAULT '',
			siteemail VARCHAR(100) NOT NULL DEFAULT '',
			siteusername VARCHAR(30) NOT NULL DEFAULT '',
			siteintro TEXT NOT NULL,
			description TEXT NOT NULL,
			keywords TEXT NOT NULL,
			footer TEXT NOT NULL,
			locale VARCHAR(20) NOT NULL DEFAULT 'Polish',
			theme VARCHAR(100) NOT NULL DEFAULT '',
			default_search VARCHAR(100) NOT NULL DEFAULT 'users',
			exclude_left TEXT NOT NULL,
			exclude_upper TEXT NOT NULL,
			exclude_lower TEXT NOT NULL,
			exclude_right TEXT NOT NULL,
			shortdate VARCHAR(50) NOT NULL DEFAULT '',
			longdate VARCHAR(50) NOT NULL DEFAULT '',
			subheaderdate VARCHAR(50) NOT NULL DEFAULT '',
			timeoffset VARCHAR(3) NOT NULL DEFAULT '0',
			smtp_host VARCHAR(200) NOT NULL DEFAULT '' ,
			smtp_username VARCHAR(100) NOT NULL DEFAULT '',
			smtp_password VARCHAR(100) NOT NULL DEFAULT '',
			bad_words_enabled TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
			bad_words TEXT NOT NULL,
			bad_word_replace VARCHAR(20) DEFAULT '[censored]' NOT NULL,
			numofshouts TINYINT(2) UNSIGNED NOT NULL DEFAULT '10',
			flood_interval TINYINT(2) UNSIGNED NOT NULL DEFAULT '15',
			counter BIGINT(20) UNSIGNED NOT NULL DEFAULT '0',
			version VARCHAR(10) NOT NULL DEFAULT '7.00.07',
			ed_version VARCHAR(10) NOT NULL DEFAULT '2.01',
			aktualizacje TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
			data_actual INT(10) NOT NULL DEFAULT '0',
			actual_version VARCHAR(10) NOT NULL DEFAULT '0',
			maintenance TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
			maintenance_message TEXT NOT NULL,
			semestr TINYINT(1) UNSIGNED NOT NULL DEFAULT '1'
			) engine = innodb;");
			
			if (!$result) { $fail = true; }
			
			$result = dbquery($db_connect, "DROP TABLE IF EXISTS ".$db_prefix."shoutbox");
			$result = dbquery($db_connect, "CREATE TABLE ".$db_prefix."shoutbox (
			shout_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
			shout_name VARCHAR(50) NOT NULL DEFAULT '',
			shout_message VARCHAR(200) NOT NULL DEFAULT '',
			shout_datestamp INT(10) UNSIGNED NOT NULL DEFAULT '0',
			shout_ip VARCHAR(20) NOT NULL DEFAULT '0.0.0.0',
			PRIMARY KEY (shout_id),
			KEY shout_datestamp (shout_datestamp)
			) engine = innodb;");
			
			if (!$result) { $fail = true; }
			
			$result = dbquery($db_connect, "DROP TABLE IF EXISTS ".$db_prefix."site_links");
			$result = dbquery($db_connect, "CREATE TABLE ".$db_prefix."site_links (
			link_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
			link_name VARCHAR(100) NOT NULL DEFAULT '',
			link_url VARCHAR(200) NOT NULL DEFAULT '',
			link_visibility TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
			link_position TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
			link_window TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
			link_order SMALLINT(2) UNSIGNED NOT NULL DEFAULT '0',
			PRIMARY KEY (link_id)
			) engine = innodb;");
		
			if (!$result) { $fail = true; }
			
			$result = dbquery($db_connect, "DROP TABLE IF EXISTS ".$db_prefix."smileys");
			$result = dbquery($db_connect, "CREATE TABLE ".$db_prefix."smileys (
			smiley_id MEDIUMINT(8) UNSIGNED NOT NULL auto_increment,
			smiley_code VARCHAR(50) NOT NULL,
			smiley_image VARCHAR(100) NOT NULL,
			smiley_text VARCHAR(100) NOT NULL,
			PRIMARY KEY (smiley_id)
			) engine = innodb;");
			
			if (!$result) { $fail = true; }
			
			$result = dbquery($db_connect, "DROP TABLE IF EXISTS ".$db_prefix."subjects");
			$result = dbquery($db_connect, "CREATE TABLE ".$db_prefix."subjects (
			subject_id MEDIUMINT(8) UNSIGNED NOT NULL auto_increment,
			subject_name VARCHAR(100) NOT NULL,
			PRIMARY KEY (subject_id)
			) engine = innodb;");
			
			if (!$result) { $fail = true; }
			
			$result = dbquery($db_connect, "DROP TABLE IF EXISTS ".$db_prefix."tmp_pass");
			$result = dbquery($db_connect, "CREATE TABLE ".$db_prefix."tmp_pass (
			id MEDIUMINT(8) UNSIGNED NOT NULL auto_increment,
			pass TEXT NOT NULL,
			PRIMARY KEY (id)
			) engine = innodb;");
			
			if (!$result) { $fail = true; }
			$result = dbquery($db_connect, "DROP TABLE IF EXISTS ".$db_prefix."user_fields");
			$result = dbquery($db_connect, "CREATE TABLE ".$db_prefix."user_fields (
			field_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
			field_name VARCHAR(50) NOT NULL,
			field_group SMALLINT(1) UNSIGNED NOT NULL DEFAULT '1',
			field_order SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
			PRIMARY KEY (field_id),
			KEY field_order (field_order)
			) engine = innodb;");
		
			if (!$result) { $fail = true; }
			
			$result = dbquery($db_connect, "DROP TABLE IF EXISTS ".$db_prefix."users");
			$result = dbquery($db_connect, "CREATE TABLE ".$db_prefix."users (
			user_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
			user_name VARCHAR(30) NOT NULL DEFAULT '',
			name VARCHAR(100) NOT NULL DEFAULT '',
			surname VARCHAR(100) NOT NULL DEFAULT '',
			user_password VARCHAR(32) NOT NULL DEFAULT '',
			user_admin_password VARCHAR(32) NOT NULL DEFAULT '',
			user_email VARCHAR(100) NOT NULL DEFAULT '',
			user_hide_email TINYINT(1) UNSIGNED NOT NULL DEFAULT '1',
			user_offset CHAR(3) NOT NULL DEFAULT '0',
			user_avatar VARCHAR(100) NOT NULL DEFAULT '',
			user_joined INT(10) UNSIGNED NOT NULL DEFAULT '0',
			user_lastvisit INT(10) UNSIGNED NOT NULL DEFAULT '0',
			user_ip VARCHAR(20) NOT NULL DEFAULT '0.0.0.0',
			user_level TINYINT(3) UNSIGNED NOT NULL DEFAULT '101',
			user_status TINYINT(1) UNSIGNED NOT NULL DEFAULT '0',
			student_class VARCHAR(10) NOT NULL DEFAULT 'Default',
			d_num INT(3) NOT NULL,
			student_subjects TEXT NOT NULL DEFAULT '',
			educator VARCHAR(10) NOT NULL DEFAULT '0',
			teacher_subjects TEXT NOT NULL DEFAULT '',
			id_child MEDIUMINT(8) UNSIGNED NOT NULL,
			os1 VARCHAR(1) NOT NULL,
			os2 VARCHAR(1) NOT NULL,
			notes TEXT NOT NULL,
			PRIMARY KEY (user_id),
			KEY user_name (user_name),
			KEY user_joined (user_joined),
			KEY user_lastvisit (user_lastvisit)
			) engine = innodb;");
			
			if (!$result) { $fail = true; }
	
			if (!$fail) {
				echo "<br />\n".$locale['040']."<br /><br />\n";
				echo $locale['041']."<br /><br />\n";
				echo $locale['042']."<br /><br />\n";
				$success = true;
			} else {
				echo "<br />\n".$locale['040']."<br /><br />\n";
				echo $locale['041']."<br /><br />\n";
				echo "<strong>".$locale['043']."</strong> ".$locale['048']."<br /><br />\n";
				$success = false;
			}
		} else {
			echo "<br />\n".$locale['040']."<br /><br />\n";
			echo "<strong>".$locale['043']."</strong> ".$locale['046']."<br />\n";
			echo "<span class='small'>".$locale['047']."</span><br /><br />\n";
			$success = false;
		}
	} else {
		echo "<br />\n<strong>".$locale['043']."<strong> ".$locale['044']."<br />\n";
		echo "<span class='small'>".$locale['045']."</span><br /><br />\n";
		$success = false;
	}
	echo "</td>\n</tr>\n<tr>\n<td class='tbl2' style='text-align:center'>\n";
	echo "<input type='hidden' name='localeset' value='".stripinput($_POST['localeset'])."'>\n";
	if ($success) {
		echo "<input type='hidden' name='step' value='5'>\n";
		echo "<input type='submit' name='next' value='".$locale['007']."' class='button'>\n";
	} else {
		echo "<input type='hidden' name='step' value='3'>\n";
		echo "<input type='submit' name='next' value='".$locale['008']."' class='button'>\n";
	}
}
//
// Dane admina
if (isset($_POST['step']) && $_POST['step'] == "5") {
	echo $locale['060']."<br /><br />\n";
	echo "<table align='center' cellpadding='0' cellspacing='0' width='100%'>\n<tr>\n";
	echo "<td class='tbl1'>".$locale['061']."</td>\n";
	echo "<td class='tbl1' style='text-align:right'><input type='text' name='username' maxlength='30' class='textbox' style='width:200px'></td></tr>\n";
	echo "<tr>\n<td class='tbl1'>".$locale['062']."</td>\n";
	echo "<td class='tbl1' style='text-align:right'><input type='password' name='password1' maxlength='20' class='textbox' style='width:200px'></td></tr>\n";
	echo "<tr>\n<td class='tbl1'>".$locale['063']."</td>\n";
	echo "<td class='tbl1' style='text-align:right'><input type='password' name='password2' maxlength='20' class='textbox' style='width:200px'></td></tr>\n";
	echo "<tr>\n<td class='tbl1'>".$locale['064']."</td>\n";
	echo "<td class='tbl1' style='text-align:right'><input type='password' name='admin_password1' maxlength='20' class='textbox' style='width:200px'></td></tr>\n";
	echo "<tr>\n<td class='tbl1'>".$locale['065']."</td>\n";
	echo "<td class='tbl1' style='text-align:right'><input type='password' name='admin_password2' maxlength='20' class='textbox' style='width:200px'></td></tr>\n";
	echo "<tr>\n<td class='tbl1'>".$locale['066']."</td>\n";
	echo "<td class='tbl1' style='text-align:right'><input type='text' name='email' maxlength='100' class='textbox' style='width:200px'></td></tr>\n";
	echo "</table>\n";
	echo "</td>\n</tr>\n<tr>\n<td class='tbl2' style='text-align:center'>\n";
	echo "<input type='hidden' name='localeset' value='".stripinput($_POST['localeset'])."'>\n";
	echo "<input type='hidden' name='step' value='6'>\n";
	echo "<input type='submit' name='next' value='".$locale['007']."' class='button'>\n";
}
// Instalacja admina i jego praw
if (isset($_POST['step']) && $_POST['step'] == "6") {
	require_once "conf.php";
	
	$db_connect = mysqli_connect($db_host, $db_user, $db_pass);
	$db_select = @mysqli_select_db($db_connect, $db_name);
	
	$error = "";	
	
	$username = stripinput($_POST['username']);
	$password1 = stripinput($_POST['password1']);
	$password2 = stripinput($_POST['password2']);
	$admin_password1 = stripinput($_POST['admin_password1']);
	$admin_password2 = stripinput($_POST['admin_password2']);
	$email = stripinput($_POST['email']);
	
	if (!preg_match("/^[-0-9A-Z_@\s]+$/i", $username)) {
		$error .= $locale['070']."<br><br>\n";
	}
	
	if (preg_match("/^[0-9A-Z@]{6,20}$/i", $password1)) {
		if ($password1 != $password2) {
			$error .= $locale['071']."<br><br>\n";
		}
	} else {
		$error .= $locale['072']."<br><br>\n";
	}

	if (preg_match("/^[0-9A-Z@]{6,20}$/i", $admin_password1)) {
		if ($admin_password1 != $admin_password2) {
			$error .= $locale['073']."<br><br>\n";
		} elseif ($admin_password1 == $password1) {
			$error .= $locale['074']."<br><br>\n";
		}
	} else {
		$error .= $locale['075']."<br><br>\n";
	}
	
 	if (!preg_match("/^[-0-9A-Z_\.]{1,50}@([-0-9A-Z_\.]+\.){1,50}([0-9A-Z]){2,4}$/i", $email)) {
		$error .= $locale['076']."<br><br>\n";
	}


			
	if ($error == "") {
		$result = dbquery($db_connect, "INSERT INTO ".$db_prefix."settings 
		(sitename, siteurl, 
		sitebanner,
		siteemail,
		siteusername,	
		siteintro, 
		description, 
		keywords,
		footer,		
		locale,	
		theme, 
		default_search, 
		exclude_left, 
		exclude_upper, 
		exclude_lower, 
		exclude_right,
		shortdate, longdate, subheaderdate, timeoffset,
		smtp_host, smtp_username, smtp_password, bad_words_enabled, bad_words, bad_word_replace, numofshouts, flood_interval,
		counter, version, ed_version, aktualizacje, data_actual, actual_version, maintenance, maintenance_message, semestr) 
		VALUES 
		('Nowy e-dziennik', 'http://www.twojastrona.pl/',
		'images/e-dziennik.png', 'you@yourdomain.com', '$username', '<center>".$locale['210']."</center>', '', '', 
		'<center>Copyright &copy; ".date("Y")."</center>', '".stripinput($_POST['localeset'])."', 'edziennik', 'users', '', '', '', '',
		'%d/%m/%Y %H:%M', '%B %d %Y %H:%M:%S', '%B %d %Y %H:%M:%S', '0',
		'', '', '', '0', '', '***', '10', '10',
		'0', '7.00.07', '2.00', '1', '0', '0', '0', '', '1')");
		
		$result = dbquery($db_connect, "INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('101', 'pm.png', '".$locale['080']."', '../messages.php', 0)");
		$result = dbquery($db_connect, "INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('102', 'ratings2.png', '".$locale['081']."', 'ratings.php', 0)");
		$result = dbquery($db_connect, "INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('103', 'ratings2.png', '".$locale['082']."', 'ratings.php', 0)");
		$result = dbquery($db_connect, "INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('104', 'ratings1.png', '".$locale['083']."', 'ratings.php', 0)");
		$result = dbquery($db_connect, "INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('106', 'bbcodes.png', '".$locale['084']."', 'bbcodes.php', 3)");
		$result = dbquery($db_connect, "INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('106', 'subjects.png', '".$locale['085']."', 'subjects.php', 1)");
		$result = dbquery($db_connect, "INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('106', 'users.png', '".$locale['086']."', 'class.php', 1)");
		$result = dbquery($db_connect, "INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('106', 'class_subjects.png', '".$locale['087']."', 'class_subjects.php', 1)");
		$result = dbquery($db_connect, "INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('106', 'db_backup.png', '".$locale['088']."', 'db_backup.php', 3)");
		$result = dbquery($db_connect, "INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('106', 'teachers.png', '".$locale['089']."', 'teachers.php', 2)");
		$result = dbquery($db_connect, "INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('106', 'semestr.png', '".$locale['090']."', 'semestr.php', 4)");
		$result = dbquery($db_connect, "INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('106', 'teachers.png', '".$locale['091']."', 'educators.php', 1)");
		$result = dbquery($db_connect, "INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('104', 'users.png', '".$locale['092']."', 'educator.php', 0)");
		$result = dbquery($db_connect, "INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('106', 'images.png', '".$locale['093']."', 'images.php', 1)");
		$result = dbquery($db_connect, "INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('106', 'infusions.png', '".$locale['094']."', 'infusions.php', 3)");
		$result = dbquery($db_connect, "INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('106', 'users.png', '".$locale['096']."', 'members.php', 2)");
		$result = dbquery($db_connect, "INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('106', 'panels.png', '".$locale['099']."', 'panels.php', 3)");
		$result = dbquery($db_connect, "INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('104', 'ratings1.png', '".$locale['100']."', 'ratings_my_class.php', 0)");
		$result = dbquery($db_connect, "INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('106', 'phpinfo.png', '".$locale['101']."', 'phpinfo.php', 3)");
		$result = dbquery($db_connect, "INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('106', 'shout.png', '".$locale['103']."', 'shoutbox.php', 2)");
		$result = dbquery($db_connect, "INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('106', 'site_links.png', '".$locale['104']."', 'site_links.php', 3)");
		$result = dbquery($db_connect, "INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('106', 'smileys.png', '".$locale['105']."', 'smileys.php', 3)");
		$result = dbquery($db_connect, "INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('106', 'upgrade.png', '".$locale['107']."', 'upgrade.php', 3)");
		$result = dbquery($db_connect, "INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('106', 'settings.png', '".$locale['111']."', 'settings_main.php', 4)");
		$result = dbquery($db_connect, "INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('106', 'settings_time.png', '".$locale['112']."', 'settings_time.php', 4)");
		$result = dbquery($db_connect, "INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('106', 'settings_misc.png', '".$locale['116']."', 'settings_misc.php', 4)");
		$result = dbquery($db_connect, "INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('106', 'settings_pm.png', '".$locale['117']."', 'settings_messages.php', 4)");
		$result = dbquery($db_connect, "INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('106', 'user_fields.png', '".$locale['118']."', 'user_fields.php', 2)");
		$result = dbquery($db_connect, "INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('106', 'reset.png', '".$locale['119']."', 'reset.php', 1)");
		$result = dbquery($db_connect, "INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('106', 'directors.png', '".$locale['120']."', 'directors.php', 2)");
		$result = dbquery($db_connect, "INSERT INTO ".$db_prefix."admin (admin_rights, admin_image, admin_title, admin_link, admin_page) VALUES ('105', 'users.png', '".$locale['098']."', 'studentsr.php', 0)");

		$result = dbquery($db_connect, "INSERT INTO ".$db_prefix."users (user_name, user_password, user_admin_password, user_email, user_joined, user_level) VALUES ('".$username."', '".md5(md5($password1))."', '".md5(md5($admin_password1))."', '".$email."', '".time()."', '106')");

		$result = dbquery($db_connect, "INSERT INTO ".$db_prefix."messages_options (user_id, pm_email_notify, pm_save_sent, pm_inbox, pm_savebox, pm_sentbox) VALUES ('0', '0', '1', '20', '20', '20')");

		$result = dbquery($db_connect, "INSERT INTO ".$db_prefix."bbcodes (bbcode_name, bbcode_order) VALUES ('smiley', '1')");
		$result = dbquery($db_connect, "INSERT INTO ".$db_prefix."bbcodes (bbcode_name, bbcode_order) VALUES ('b', '2')");
		$result = dbquery($db_connect, "INSERT INTO ".$db_prefix."bbcodes (bbcode_name, bbcode_order) VALUES ('i', '3')");
		$result = dbquery($db_connect, "INSERT INTO ".$db_prefix."bbcodes (bbcode_name, bbcode_order) VALUES ('u', '4')");
		$result = dbquery($db_connect, "INSERT INTO ".$db_prefix."bbcodes (bbcode_name, bbcode_order) VALUES ('url', '5')");
		$result = dbquery($db_connect, "INSERT INTO ".$db_prefix."bbcodes (bbcode_name, bbcode_order) VALUES ('mail', '6')");
		$result = dbquery($db_connect, "INSERT INTO ".$db_prefix."bbcodes (bbcode_name, bbcode_order) VALUES ('img', '7')");
		$result = dbquery($db_connect, "INSERT INTO ".$db_prefix."bbcodes (bbcode_name, bbcode_order) VALUES ('center', '8')");
		$result = dbquery($db_connect, "INSERT INTO ".$db_prefix."bbcodes (bbcode_name, bbcode_order) VALUES ('small', '9')");
		$result = dbquery($db_connect, "INSERT INTO ".$db_prefix."bbcodes (bbcode_name, bbcode_order) VALUES ('code', '10')");
		$result = dbquery($db_connect, "INSERT INTO ".$db_prefix."bbcodes (bbcode_name, bbcode_order) VALUES ('quote', '11')");

		$result = dbquery($db_connect, "INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES (':)', 'smile.gif', 'Smile')");
		$result = dbquery($db_connect, "INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES (';)', 'wink.gif', 'Wink')");
		$result = dbquery($db_connect, "INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES (':(', 'sad.gif', 'Sad')");
		$result = dbquery($db_connect, "INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES (':|', 'frown.gif', 'Frown')");
		$result = dbquery($db_connect, "INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES (':o', 'shock.gif', 'Shock')");
		$result = dbquery($db_connect, "INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES (':P', 'pfft.gif', 'Pfft')");
		$result = dbquery($db_connect, "INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES ('B)', 'cool.gif', 'Cool')");
		$result = dbquery($db_connect, "INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES (':D', 'grin.gif', 'Grin')");
		$result = dbquery($db_connect, "INSERT INTO ".$db_prefix."smileys (smiley_code, smiley_image, smiley_text) VALUES (':@', 'angry.gif', 'Angry')");

		$result = dbquery($db_connect, "INSERT INTO ".$db_prefix."panels (panel_name, panel_filename, panel_content, panel_side, panel_order, panel_type, panel_access, panel_display, panel_status) VALUES ('".$locale['160']."', 'navigation_panel', '', '1', '2', 'file', '0', '0', '1')");
		$result = dbquery($db_connect, "INSERT INTO ".$db_prefix."panels (panel_name, panel_filename, panel_content, panel_side, panel_order, panel_type, panel_access, panel_display, panel_status) VALUES ('".$locale['164']."', 'welcome_message_panel', '', '2', '1', 'file', '0', '0', '1')");
		$result = dbquery($db_connect, "INSERT INTO ".$db_prefix."panels (panel_name, panel_filename, panel_content, panel_side, panel_order, panel_type, panel_access, panel_display, panel_status) VALUES ('".$locale['166']."', 'user_info_panel', '', '1', 1, 'file', '0', '0', '1')");
		$result = dbquery($db_connect, "INSERT INTO ".$db_prefix."panels (panel_name, panel_filename, panel_content, panel_side, panel_order, panel_type, panel_access, panel_display, panel_status) VALUES ('".$locale['168']."', 'shoutbox_panel', '', '1', '3', 'file', '0', '0', '1')");

		$result = dbquery($db_connect, "INSERT INTO ".$db_prefix."site_links (link_name, link_url, link_visibility, link_position, link_window, link_order) VALUES ('".$locale['130']."', 'index.php', '0', '2', '0', '1')");
		$result = dbquery($db_connect, "INSERT INTO ".$db_prefix."site_links (link_name, link_url, link_visibility, link_position, link_window, link_order) VALUES ('".$locale['135']."', 'contact.php', '0', '2', '0', '2')");
		$result = dbquery($db_connect, "INSERT INTO ".$db_prefix."site_links (link_name, link_url, link_visibility, link_position, link_window, link_order) VALUES ('".$locale['139']."', 'search.php', '0', '1', '0', '3')");

   
   	echo "<br />\n".$locale['220']."<br /><br />\n";
		echo "</td>\n</tr>\n<tr>\n<td class='tbl2' style='text-align:center'>\n";
		echo "<input type='hidden' name='localeset' value='".stripinput($_POST['localeset'])."'>\n";
		echo "<input type='hidden' name='step' value='7'>\n";
		echo "<input type='submit' name='next' value='".$locale['009']."' class='button'>\n";
	} else {
		echo "<br />\n".$locale['077']."<br /><br />\n".$error;
		echo "</td>\n</tr>\n<tr>\n<td class='tbl2' style='text-align:center'>\n";
		echo "<input type='hidden' name='localeset' value='".stripinput($_POST['localeset'])."'>\n";
		echo "<input type='hidden' name='step' value='5'>\n";
		echo "<input type='submit' name='back' value='".$locale['008']."' class='button'>\n";
	}
}
//


echo "</td>\n</tr>\n";
echo "</table>\n</form>\n";
echo "</body>\n</html>\n";

// mySQL database functions
function dbquery($db_connect, $query) {
	$result = @mysqli_query($db_connect,$query);
	if (!$result) {
		echo mysqli_error($db_connect);
		return false;
	} else {
		return $result;
	}
}

// Strip Input Function, prevents HTML in unwanted places
function stripinput($text) {
	if (ini_get('magic_quotes_gpc')) $text = stripslashes($text);
	$search = array("\"", "'", "\\", '\"', "\'", "<", ">", "&nbsp;");
	$replace = array("&quot;", "&#39;", "&#92;", "&quot;", "&#39;", "&lt;", "&gt;", " ");
	$text = str_replace($search, $replace, $text);
	return $text;
}

// Create a list of files or folders and store them in an array
function makefilelist($folder, $filter, $sort=true, $type="files") {
	$res = array();
	$filter = explode("|", $filter); 
	$temp = opendir($folder);
	while ($file = readdir($temp)) {
		if ($type == "files" && !in_array($file, $filter)) {
			if (!is_dir($folder.$file)) $res[] = $file;
		} elseif ($type == "folders" && !in_array($file, $filter)) {
			if (is_dir($folder.$file)) $res[] = $file;
		}
	}
	closedir($temp);
	if ($sort) sort($res);
	return $res;
}

// Create a selection list from an array created by makefilelist()
function makefileopts($files, $selected = "") {
	$res = "";
	for ($i=0; $i < count($files); $i++) {
		$sel = ($selected == $files[$i] ? " selected='selected'" : "");
		$res .= "<option value='".$files[$i]."'$sel>".$files[$i]."</option>\n";
	}
	return $res;
}
?>
