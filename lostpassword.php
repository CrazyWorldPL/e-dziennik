<?php
/*-------------------------------------------------------+
| e-dziennik
| Copyright (C) 2009-2010
| http://e-dziennik.xwp.pl/
+--------------------------------------------------------+
| Plik: lostpassword.php
| Autor: Szymon (szygmon) Michalewicz
+-------------------------------------------------------*/
require_once "maincore.php";
require_once INCLUDES."theme_functions_include.php";
require_once INCLUDES."sendmail_include.php";
include LOCALE.LOCALESET."lostpassword.php";

if (iMEMBER) redirect("index.php");

echo"<head>
<title>".$settings['sitename'].$locale['global_200'].$locale['400']."</title>
<meta http-equiv='Content-Type' content='text/html; charset=".$locale['charset']."' />
<meta name='description' content='".$settings['description']."' />
<meta name='keywords' content='".$settings['keywords']."' />";
if (file_exists(IMAGES."favicon.ico")) { echo "<link rel='shortcut icon' href='".IMAGES."favicon.ico' type='image/x-icon' />\n"; }
echo "</head>
<body>

	<style type='text/css'>
		html, body { height: 100%; }
		body { background: #cae2f0; display: table; margin: 0 auto; font: normal 14px sans-serif; text-align: center; }
		#wrapper { display: table-cell; vertical-align: middle; }
		#cell { background: transparent url(images/logowanie.png) no-repeat; text-decoration: none;  margin:5px auto; width:341px; height:305px; }
		#cell-text { padding-top:110px; }
		#footer { margin-top: 30px; }
		.button { padding: 5px 10px; }
		.textbox { width: 220px; padding: 2px;}
		a { color: #000000; }
	</style>

<div id='wrapper'>
<div id='cell'><div id='cell-text'>";

if (isset($email) && isset($account)) {
	$error = 0;
	if (FUSION_QUERY != "email=".$email."&amp;account=".$account) redirect("index.php");
	$email = stripinput(trim(preg_replace_callback("/ +/i", "", $email)));
	if (!preg_match("/^[-0-9A-Z_\.]{1,50}@([-0-9A-Z_\.]+\.){1,50}([0-9A-Z]){2,4}$/i", $email)) $error = 1;
	if (!preg_match("/^[0-9a-z]{32}$/", $account)) $error = 1;
	if ($error == 0) {
		$result = dbquery("SELECT * FROM ".$db_prefix."users WHERE user_password='$account' AND user_email='$email'");
		if (dbrows($result) != 0) {
			$data = dbarray($result); $new_pass = "";
			for ($i=0;$i<=7;$i++) { $new_pass .= chr(rand(97, 122)); }
			$mailbody = str_replace("[NEW_PASS]", $new_pass, $locale['411']);
			$mailbody = str_replace("[USER_NAME]", $data['user_name'], $mailbody);
			sendemail($data['user_name'],$email,$settings['siteusername'],$settings['siteemail'],$locale['409'].$settings['sitename'],$mailbody);
			$result = dbquery("UPDATE ".$db_prefix."users SET user_password='".md5(md5($new_pass))."' WHERE user_id='".$data['user_id']."'");
			echo "<br/>\n".$locale['402']."<br/><br/>\n";
		} else {
			$error = 1;
		}
	}
	if ($error == 1) redirect("index.php");
} elseif (isset($_POST['send_password'])) {
	$email = stripinput(trim(preg_replace_callback("/ +/i", "", $_POST['email'])));
	if (preg_match("/^[-0-9A-Z_\.]{1,50}@([-0-9A-Z_\.]+\.){1,50}([0-9A-Z]){2,4}$/i", $email)) {
		$result = dbquery("SELECT * FROM ".$db_prefix."users WHERE user_email='$email'");
		if (dbrows($result) != 0) {
			$data = dbarray($result);
			$new_pass_link = $settings['siteurl']."lostpassword.php?email=".$data['user_email']."&account=".$data['user_password'];
			$mailbody = str_replace("[NEW_PASS_LINK]", $new_pass_link, $locale['410']);
			$mailbody = str_replace("[USER_NAME]", $data['user_name'], $mailbody);
			sendemail($data['user_name'],$email,$settings['siteusername'],$settings['siteemail'],$locale['409'].$settings['sitename'],$mailbody);
			echo "<br/>\n".$locale['401']."<br/><br/>\n";
		} else {
			echo "<br/>\n".$locale['404']."<br/><br/>\n<a href='".FUSION_SELF."'>".$locale['406']."</a><br/><br/>\n";
		}
	} else {
		echo "<div style='text-align:center'><br />\n".$locale['405']."<br /><br />\n<a href='".FUSION_SELF."'>".$locale['403']."</a><br /><br /></div>\n";
	}
} else {
	echo "<form name='passwordform' method='post' action='".FUSION_SELF."'>
<center>".$locale['407']."<br/>
<br/>
<input type='text' name='email' class='textbox' maxlength='100'><br />
<br/>
<input type='submit' name='send_password' value='".$locale['408']."' class='button'></center>
</form>";
}
echo "</div></div>
<center>
<a href='index.php'>".$locale['global_100']."</a>
</center>
<div id='footer' align='center'>".showcopyright()."</div>
</div>

</body>";
?>
