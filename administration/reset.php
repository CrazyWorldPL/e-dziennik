<?php
/*-------------------------------------------------------+
| e-dziennik
| Copyright (C) 2009-2010
| http://e-dziennik.xwp.pl/
+--------------------------------------------------------+
| Plik: reset.php
| Autor: Szymon (szygmon) Michalewicz
+-------------------------------------------------------*/
require_once "../maincore.php";
require_once THEMES."templates/admin_header.php";
include LOCALE.LOCALESET."admin/reset.php";

if (!iADMIN || !defined("iAUTH") || $_GET['aid'] != iAUTH) { redirect("../index.php"); }

if (isset($_GET['status']) && $_GET['status'] == "ok") {
	echo "<div class='admin-message'>".$locale['res01']."</div>\n";
}

if (isset($_POST['reset'])) {
	// usuwanie uczniów i rodziców
	$res = dbquery("DELETE FROM ".DB_USERS." WHERE user_level='103' OR user_level='102'");
	// czyszczenie nauczanych klas u nauczycieli
	$res = dbquery("UPDATE ".DB_USERS." SET teacher_subjects='', educator='0' WHERE user_level>='104'");
	// usuwanie przedmiotów
	$res = dbquery("SELECT * FROM ".DB_SUBJECTS);
	while ($data = dbarray($res)) {
		$result = dbquery("DROP TABLE ".DB_PREFIX."sub".$data['subject_id']);
		$result = dbquery("DROP TABLE ".DB_PREFIX."sub".$data['subject_id']."_descr");
	}
	$res = dbquery("DELETE FROM ".DB_SUBJECTS);
	redirect(FUSION_SELF.$aidlink."&amp;status=ok");
} else {
	opentable($locale['res02']);
	echo "<div class='admin-message'>".$locale['res03']."</div>\n";
	echo "<div align='center'>
	<form name='form' method='post' action='".FUSION_SELF.$aidlink."&amp;action=reset'>
	<input type='submit' name='reset' onclick='return Reset();' class='button' value='".$locale['res05']."' />
	</form>
	</div>";
	closetable();
	
	echo "<script type='text/javascript'>\n"."function Reset() {\n";
	echo "return confirm('".$locale['res04']."');\n}\n";
	echo "</script>\n";
}

require_once THEMES."templates/footer.php";
?>
