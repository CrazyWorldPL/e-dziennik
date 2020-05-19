<?php
/*-------------------------------------------------------+
| e-dziennik
| Copyright (C) 2009-2010 
| http://e-dziennik.xwp.pl/
+--------------------------------------------------------+
| Plik: class_subjects.php
| Autor: Szymon (szygmon) Michalewicz
+-------------------------------------------------------*/
require_once "../maincore.php";
require_once THEMES."templates/admin_header.php";
include LOCALE.LOCALESET."admin/class_subjects.php";

if (!iADMIN || !defined("iAUTH") || $_GET['aid'] != iAUTH) { redirect("../index.php"); }

// Komunikaty
if (isset($_GET['status'])) {
	if ($_GET['status'] == "sn") {
		$message = $locale['410'];
	} elseif ($_GET['status'] == "err") {
		$message = $locale['411'];
	} elseif ($_GET['status'] == "del") {
		$message = $locale['412'];
	}
	if ($message) {	echo "<div class='admin-message'>".$message."</div>\n"; }
}

// Je¶li nie wybrano klasy
if (!isset($_GET['class'])) {
  opentable($locale['400']);
  $result = dbquery("SELECT student_class FROM ".DB_USERS." WHERE d_num='1'");
  if (dbrows($result)!=0) {
  	echo "<table cellpadding='0' cellspacing='1' width='300' class='tbl-border center'><tr>
  	<td class='tbl2'><strong>".$locale['445']."</strong></td>
  	</tr>";
	$i = 0;
    	while($data_class = dbarray($result)) {
    		$row_color = ($i % 2 == 0 ? "tbl1" : "tbl2");
	    	echo "<tr><td class='".$row_color."'><a href='".FUSION_SELF.$aidlink."&class=".$data_class['student_class']."'>".$locale['445'].$data_class['student_class']."</a></td></tr>";
  	}
  	echo "</table>";
  } else {
  	echo "<div align='center'>".$locale['403']."</div>";
  }
  closetable();
} else {

//
// Zapisywanie
// 
if (isset($_POST['save'])) {
	$class = stripinput($_GET['class']);
	$subject_id = $_POST['subject_id'];
	$teacher = isnum($_POST['teacher']) ? $_POST['teacher'] : "";
		
	if ($subject_id != "" && $teacher != "") {
		// Dodawanie przedmiotu i klasy do nauczyciela
		$old_teacher = dbarray(dbquery("SELECT teacher_subjects FROM ".DB_USERS." WHERE user_id='$teacher'"));
		$addclass = $old_teacher['teacher_subjects'].(($old_teacher['teacher_subjects']) != "" ? "." : "").$class.":".$subject_id;
		$result = dbquery("UPDATE ".DB_USERS." SET teacher_subjects='".$addclass."' WHERE user_id='$teacher'");
		
		// Dodanie przedmiotu do uczniów
		$old_student = dbarray(dbquery("SELECT student_subjects FROM ".DB_USERS." WHERE student_class='".$_GET['class']."' LIMIT 1"));
		$addsubject = $old_student['student_subjects'].(($old_student['student_subjects']) != "" ? "." : "").$subject_id.":".$teacher;
		$result = dbquery("UPDATE ".DB_USERS." SET student_subjects='".$addsubject."' WHERE student_class='".$_GET['class']."'");    
		
		// Dodawanie uczniów do przedmiotów
		$students = dbquery("SELECT user_id, student_class FROM ".DB_USERS." WHERE student_class='".$_GET['class']."'");
		for ($i = 0; $data = dbarray($students); $i++) {
    			$addstudent = dbquery("INSERT INTO ".DB_PREFIX."sub".$subject_id." (id_student, class, 1sem, 2sem) VALUES ('".$data['user_id']."', '".$data['student_class']."', '::::::::::::::::::::::::::::', '::::::::::::::::::::::::::::')");
  		}
		
		// Dodawanie klasy do tabeli z opisami i wagami ocen
		$result = dbquery("INSERT INTO ".DB_PREFIX."sub".$subject_id."_descr (d_class, 1sem, 2sem) VALUES ('".$_GET['class']."', '!!::!!::!!::!!::!!::!!::!!::!!::!!::!!::!!::!!::!!::!!::!!', '!!::!!::!!::!!::!!::!!::!!::!!::!!::!!::!!::!!::!!::!!::!!')");
  				
		// Przekierowanie po pomy¶lnym wykonaniu operacji
		redirect(FUSION_SELF.$aidlink."&class=".$class."&status=sn");
	}
	// Przekierowanie po ¼le wprowadzonych danych
	redirect(FUSION_SELF.$aidlink."&class=".$class."&status=err");
} 
//
// Usuwanie
//
else if (isset($_GET['delete']) && isset($_GET['sid']) && isset($_GET['class'])) {
		$tsn = DB_PREFIX."sub".$_GET['sid'];

		// Usuwanie przedmiotu do uczniów
		$old_student = dbarray(dbquery("SELECT student_subjects FROM ".DB_USERS." WHERE student_class='".$_GET['class']."' LIMIT 1"));

		$ss = $old_student['student_subjects'];
		$ss = explode(".", $ss);
		$a = 0;
		$ns = "";
		while (isset($ss[$a])) {
		  	$tmp = explode(":", $ss[$a]);
   			if ($tmp[0] == $_GET['sid']) {
	       			$tid = $tmp[1];
	     		} else {
	         		$ns .= ($ns != "" ? "." : "").$ss[$a];
	       		}
			$a++;
		}
		$update_students = dbquery("UPDATE ".DB_USERS." SET student_subjects='".$ns."' WHERE student_class='".$_GET['class']."'");    

		// Usuwanie przedmiotu i klasy do nauczyciela
		$old_teacher = dbarray(dbquery("SELECT teacher_subjects FROM ".DB_USERS." WHERE user_id='$tid'"));
			
		$ts = $old_teacher['teacher_subjects'];
		$ts = explode(".", $ts);
		$b = 0;
		$nts = "";
		$sts = $_GET['class'].":".$_GET['sid'];
		while (isset($ts[$b])) {
   			if ($ts[$b] != $sts) {
	         		$nts .= ($nts != "" ? "." : "").$ts[$b];
	       		}
			$b++;
		}		
		$update_teacher = dbquery("UPDATE ".DB_USERS." SET teacher_subjects='".$nts."' WHERE user_id='$tid'");
		
		// Usuwanie uczniów do przedmiotów
		$del_students = dbquery("DELETE FROM ".$tsn." WHERE class='".$_GET['class']."'");
		
		// Usuwanie klasy z opisu ocen
		$res = dbquery("DELETE FROM ".$tsn."_descr WHERE d_class='".$_GET['class']."'");

	redirect(FUSION_SELF.$aidlink."&class=".$_GET['class']."&status=del");
} 
//
// Wy¶wietlanie strony
//
else {
	opentable($locale['401'].$_GET['class']);
	// lista przedmiotów (wywaliæ przedmioty które klasa ju¿ ma...)
	$lsubjects = dbquery("SELECT * FROM ".DB_SUBJECTS." ORDER BY subject_name");
	$lteachers = dbquery("SELECT user_id, name, surname, user_name FROM ".DB_USERS." WHERE user_level>='104' AND user_level<'106' ORDER BY surname DESC, name DESC");
	if (dbrows($lsubjects) != 0 && dbrows($lteachers) != 0) {
		$subjectslist = "";
		while ($data1 = dbarray($lsubjects)) {
			$subjectslist .= "<option value='".$data1['subject_id']."'>".$data1['subject_name']."</option>\n";
		}
		$teacherslist = "";
		while ($data2 = dbarray($lteachers)) {
			$teacherslist .= "<option value='".$data2['user_id']."'>".$data2['surname']." ".$data2['name']." (".$data2['user_name'].")</option>\n";
		}
		echo "<form name='inputform' method='post' action='".FUSION_SELF.$aidlink."&class=".$_GET['class']."' onsubmit='return ValidateForm(this);'>\n";
		echo "<table cellpadding='0' cellspacing='0' class='center'>\n<tr>\n";
		echo "<td width='100' class='tbl'>".$locale['422']."</td>\n";
		echo "<td width='80%' class='tbl'><select name='subject_id' class='textbox'>\n";
		echo "<option value='0'>".$locale['424']."</option>\n".$subjectslist."</select></td>\n";
		echo "</tr>\n<tr>\n";
		echo "<td width='100' class='tbl'>".$locale['423']."</td>\n";
		echo "<td width='80%' class='tbl'><select name='teacher' class='textbox'>\n";
		echo "<option value='0'>".$locale['424']."</option>\n".$teacherslist."</select></td>\n";
		echo "</tr>\n<tr>\n";
		echo "<td align='center' colspan='2' class='tbl'><br />\n";
		echo "<input type='submit' name='save' value='".$locale['437']."' class='button' /></td>\n";
		echo "</tr>\n</table>\n</form>\n";
	} else {
   		echo "<div align='center'>".$locale['404']."</div>";
 	}
	closetable();
	
	// Przedmioty danej klasy
	opentable($locale['425'].$_GET['class']);
	$subjects_class = dbarray(dbquery("SELECT student_subjects FROM ".DB_USERS." WHERE student_class='".$_GET['class']."' LIMIT 1"));
	$sc = $subjects_class['student_subjects'];
	$sc = explode(".", $sc);
	$i = 0; 
	$classes = "";

	while (isset($sc[$i])) {
		$row_color = ($i % 2 == 0 ? "tbl1" : "tbl2");
 		$ssub= explode(":", $sc[$i]);
		$result = dbquery("SELECT subject_name FROM ".DB_SUBJECTS." WHERE subject_id='".$ssub[0]."' LIMIT 1");
		$data = dbarray($result);
		$classes .= "<tr><td class='".$row_color."'>".$data['subject_name']."</td>
		<td align='center' width='1%' class='".$row_color."' style='white-space:nowrap'>
		<a href='".FUSION_SELF.$aidlink."&sid=".$ssub[0]."&class=".$_GET['class']."&delete'>".$locale['421']."</a></td>
		</tr>";
		$i++;
	}
	if ($sc[0] != "") {
		echo "<table cellpadding='0' cellspacing='1' width='300' class='tbl-border center'>\n<tr>
		<td class='tbl2'><strong>".$locale['440']."</strong></td>
		<td align='center' width='1%' class='tbl2' style='white-space:nowrap'><strong>".$locale['443']."</strong></td>
		</tr>
		".$classes."</table>";
 	} else {
	   	echo "<div align='center'>".$locale['444']."</div>";
 	}
	closetable();
}
}
require_once THEMES."templates/footer.php";
?>
