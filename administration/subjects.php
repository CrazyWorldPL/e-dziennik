<?php
/*-------------------------------------------------------+
| e-dziennik
| Copyright (C) 2009-2010 
| http://e-dziennik.xwp.pl/
+--------------------------------------------------------+
| Plik: subjects.php
| Autor: Szymon (szygmon) Michalewicz
+-------------------------------------------------------*/
require_once "../maincore.php";
require_once THEMES."templates/admin_header.php";
include LOCALE.LOCALESET."admin/subjects.php";

if (!iADMIN || !defined("iAUTH") || $_GET['aid'] != iAUTH) { redirect("../index.php"); }

// Komunikaty
if (isset($_GET['status']) && !isset($message)) {
	if ($_GET['status'] == "sn") {
		$message = $locale['410'];
	} elseif ($_GET['status'] == "bl2") {
		$message = $locale['411'];
	} elseif ($_GET['status'] == "del") {
		$message = $locale['412'];
	} elseif ($_GET['status'] == "bl") {
		$message = $locale['413'];
	}
	if ($message) {	echo "<div class='admin-message'>".$message."</div>\n"; }
}
// Usuwanie przedmiotu
if ((isset($_GET['action']) && $_GET['action'] == "delete") && (isset($_GET['subject_id']) && isnum($_GET['subject_id']))) {
	// Usuwanie info o przedmiocie z tabeli przedmioty
	$spr = dbquery("SELECT * FROM ".DB_PREFIX."sub".$_GET['subject_id']);
	if (dbrows($spr) != 0) { 
	  	redirect(FUSION_SELF.$aidlink."&status=bl");
 	} else {
		$result = dbquery("DELETE FROM ".DB_SUBJECTS." WHERE subject_id='".$_GET['subject_id']."'");
		// Usuwanie ca³ej tabeli z przedmiotem
		$result = dbquery("DROP TABLE ".DB_PREFIX."sub".$_GET['subject_id']);
		redirect(FUSION_SELF.$aidlink."&status=del");
	}
} else {
  	// Zapisywanie przedmiotu
	if (isset($_POST['savesubject'])) {
		$subject_name = stripinput($_POST['subject_name']);
		
		// Dodanie przedmiotu do tabeli subjects
		$result = dbquery("INSERT INTO ".DB_SUBJECTS." (subject_name) VALUES ('$subject_name')");
		
		// Pobieranie id nowego przedmiotu
		$id_s_res = dbquery("SELECT subject_id FROM ".DB_SUBJECTS." ORDER BY subject_id DESC LIMIT 1");
		$tmp = dbarray($id_s_res);
		$id_ns = $tmp['subject_id'];
		
		// Tworzenie tabeli z przedmiotem
		if ($subject_name != "") {
			$result = dbquery("DROP TABLE IF EXISTS ".DB_PREFIX."sub".$id_ns);
			$result = dbquery("CREATE TABLE ".DB_PREFIX."sub".$id_ns." (
			id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
			id_student MEDIUMINT(8) NOT NULL DEFAULT '0',
			class VARCHAR(10) NOT NULL DEFAULT '',
			1sem TEXT NOT NULL DEFAULT '',
			1os VARCHAR(5) NOT NULL,
			2sem TEXT NOT NULL DEFAULT '',
			2os VARCHAR(5) NOT NULL,
			PRIMARY KEY (id)
			) engine=innodb;");
			
			// Tabela do opisów ocen
			$result = dbquery("DROP TABLE IF EXISTS ".DB_PREFIX."sub".$id_ns."_descr");
			$result = dbquery("CREATE TABLE ".DB_PREFIX."sub".$id_ns."_descr (
			d_id MEDIUMINT(8) UNSIGNED NOT NULL AUTO_INCREMENT,
			d_class VARCHAR(10) NOT NULL DEFAULT '',
			1sem TEXT NOT NULL,
			2sem TEXT NOT NULL,
			PRIMARY KEY (d_id)
			) engine=innodb;");
			
			// Przekierowanie po pomy¶lnym wykonaniu operacji
			redirect(FUSION_SELF.$aidlink."&status=sn");
		}
		// Przekierowanie po ¼le wprowadzonych danych
		redirect(FUSION_SELF.$aidlink."&status=bl2");
		
	}
	// Tworzenie zmiennych dla nowego przedmiotu
	$subject_name = "";
	$formaction = FUSION_SELF.$aidlink;

	// Wygl±d strony	
	opentable($locale['400']);
	echo "<form name='layoutform' method='post' action='".$formaction."'>\n";
	echo "<table cellpadding='0' cellspacing='0' class='center'>\n<tr>\n";
	echo "<td class='tbl'>".$locale['420']."</td>\n";
	echo "<td class='tbl'><input type='text' name='subject_name' value='".$subject_name."' maxlength='100' class='textbox' style='width:240px;' /></td>\n";
	echo "</tr>\n<tr>\n";
	echo "<td align='center' colspan='2' class='tbl'>\n";
	echo "<input type='submit' name='savesubject' value='".$locale['429']."' class='button' /></td>\n";
	echo "</tr>\n</table>\n</form>\n";
	closetable();
	
	opentable($locale['402']);
	echo "<table cellpadding='0' cellspacing='1' width='300' class='tbl-border center'>\n<tr>\n";
	echo "<td class='tbl2'><strong>".$locale['440']."</strong></td>\n";
	echo "<td align='center' width='1%' class='tbl2' style='white-space:nowrap'><strong>".$locale['443']."</strong></td>\n";
	echo "</tr>\n";
	$result = dbquery("SELECT * FROM ".DB_SUBJECTS." ORDER BY subject_name");
	if (dbrows($result)) {
		$i = 0; $k = 1;
		while($data = dbarray($result)) {
			$row_color = ($i % 2 == 0 ? "tbl1" : "tbl2");
			echo "<tr>\n<td class='".$row_color."'>";
			echo $data['subject_name'];
			echo "</td>\n";
			$k++;
			echo "<td align='center' width='1%' class='".$row_color."' style='white-space:nowrap'>\n";
			echo "<a href='".FUSION_SELF.$aidlink."&amp;action=delete&amp;subject_id=".$data['subject_id']."&amp;subject_name=".$data['subject_name']."' onclick=\"return confirm('".$locale['460']."');\">".$locale['445']."</a></td>\n";
			echo "</tr>\n";
			$i++;
		}
	} else {
		echo "<tr>\n<td align='center' colspan='2' class='tbl1'>".$locale['446']."</td>\n</tr>\n";
	}
	echo "</table>\n";
	closetable();
}

require_once THEMES."templates/footer.php";
?>
