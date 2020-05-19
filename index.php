<?php
/*-------------------------------------------------------+
| e-dziennik
| Copyright (C) 2009-2010
| http://e-dziennik.xwp.pl/
+--------------------------------------------------------+
| Plik: index.php
| Autor: Szymon (szygmon) Michalewicz
+--------------------------------------------------------*/
require_once "maincore.php";
require_once INCLUDES."theme_functions_include.php";
// Prace nad stron_
if ($settings['maintenance'] == "1" && !iADMIN) { redirect(BASEDIR."maintenance.php"); }

if (iMEMBER && ($userdata['surname'] == "" || $userdata['name'] == "")) {
  redirect(BASEDIR."edit_profile.php");
} elseif (iPARENT) {
  redirect(PARENT."index.php");
} elseif (iSTUDENT) {
  redirect(STUDENT."index.php");
} elseif (iTEACHER) {
  redirect(TEACHER."index.php");
} elseif (iADMIN) {
  redirect(ADMIN."index.php".$aidlink);
} else {
	// Logowanie
	echo"<head>
	<title>".$settings['sitename'].$locale['global_200'].$locale['global_100']."</title>
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
		<div id='cell'>
			<div id='cell-text'>
				<center>".(isset($loginerror) ? $loginerror : "")."
				<form name='loginform' method='post' action='".BASEDIR."setuser.php'>
				".$locale['global_101'].":<br />
				<input type='text' name='user_name' class='textbox' class='textbox'><br/>
				".$locale['global_102'].":<br />
				<input type='password' name='user_pass' class='textbox' class='textbox'><br/><br/>
				<input type='checkbox' name='remember_me' value='y'> ".$locale['global_103']."<br/><br/>
				<input type='submit' name='login' value='".$locale['global_104']."' class='button'><br/>
				</form>
				</center>
			</div>
		</div>
		<center><a href='lostpassword.php'>".$locale['global_108']."</a></center>
		<div id='footer' align='center'>".showcopyright()."</div>
	</div>
	</body>";
}
?>
