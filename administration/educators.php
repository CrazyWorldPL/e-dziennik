<?php
/*-------------------------------------------------------+
| e-dziennik
| Copyright (C) 2009-2010
| http://e-dziennik.xwp.pl/
+--------------------------------------------------------+
| Plik: educators.php
| Autor: Szymon (szygmon) Michalewicz
+-------------------------------------------------------*/
require_once "../maincore.php";
require_once THEMES."templates/admin_header.php";
include LOCALE.LOCALESET."admin/educators.php";

if (!iADMIN || !defined("iAUTH") || $_GET['aid'] != iAUTH) { redirect("../index.php"); }

// Informacje
if (isset($_GET['status'])) {
  if ($_GET['status'] == "del") {
    echo "<div class='admin-message'>".$locale['e03']."</div>";
  } elseif ($_GET['status'] == "add") {
    echo "<div class='admin-message'>".$locale['e02']."</div>";
  }
}

if ((isset($_GET['action']) && $_GET['action'] == "del") && (isset($_GET['teacher']) && isnum($_GET['teacher']))) {
	$result = dbquery("UPDATE ".DB_USERS." SET educator='0' WHERE user_id='".$_GET['teacher']."'");
	redirect(FUSION_SELF.$aidlink."&status=del");
} 


if (isset($_POST['save'])) {
 	$result = dbquery("UPDATE ".DB_USERS." SET educator='".$_POST['class']."' WHERE user_id='".$_POST['teacher']."'");
	redirect(FUSION_SELF.$aidlink."&status=add");
}

// Lista
opentable($locale['e01']);
$lteachers = dbquery("SELECT user_id, user_name, name, surname FROM ".DB_USERS." WHERE user_level>='104' AND user_level<'106' AND educator='0'");
$lclass = dbquery("SELECT student_class FROM ".DB_USERS." WHERE user_level='103' AND d_num='1'");
if (dbrows($lteachers) != 0 && dbrows($lclass) != 0) {
	$teacherslist = "";
	$classlist = "";
	while ($data = dbarray($lteachers)) {
		$teacherslist .= "<option value='".$data['user_id']."'>".$data['surname']." ".$data['name']." (".$data['user_name'].")</option>";
	}
	while ($data2 = dbarray($lclass)) {
		$classlist .= "<option value='".$data2['student_class']."'>".$data2['student_class']."</option>";
	}
	echo "<form name='inputform' method='post' action='".FUSION_SELF.$aidlink."'>
	<table cellpadding='0' cellspacing='0' class='center'>\n<tr>
	<td width='100' class='tbl'>".$locale['e04']."</td>
	<td width='80%' class='tbl'><select name='teacher' class='textbox'>
	<option value='0'>".$locale['e06']."</option>\n".$teacherslist."</select></td>
	</tr>\n<tr>
	<td width='100' class='tbl'>".$locale['e05']."</td>
	<td width='80%' class='tbl'><select name='class' class='textbox'>
	<option value='0'>".$locale['e06']."</option>\n".$classlist."</select></td>
	</tr>\n<tr>
	<td align='center' colspan='2' class='tbl'><br />
	<input type='submit' name='save' value='".$locale['e07']."' class='button' /></td>
	</tr>\n</table>\n</form>";
} else { echo "<div align='center'>".$locale['e08']."</div>"; }

closetable();


// Aktualni wychowawcy
opentable($locale['e09']);
$educators = dbquery("SELECT user_id, user_name, name, surname, educator FROM ".DB_USERS." WHERE user_level>='104' AND educator!='0'");
if (dbrows($educators) != 0) {
  	echo "<table cellpadding='0' cellspacing='1' width='400' class='tbl-border center'>\n<tr>
	<td class='tbl2' width='79%'><strong>".$locale['user4']."</strong></td>
	<td class='tbl2' width='20%'><strong>".$locale['e12']."</strong></td>
	<td align='center' width='1%' class='tbl2' style='white-space:nowrap'><strong>".$locale['global_057']."</strong></td>
	</tr>";
	$i = 0;
  	while($edu = dbarray($educators)) {
  	  	$row_color = ($i % 2 == 0 ? "tbl1" : "tbl2");
  	  	echo "<tr>
		<td class='".$row_color."'>".$edu['surname']." ".$edu['name']." (".$edu['user_name'].")</td>
		<td class='".$row_color."'>".$edu['educator']."</td>
		<td class='".$row_color."'><a href='".FUSION_SELF.$aidlink."&action=del&teacher=".$edu['user_id']."'>".$locale['e11']."</a></td>
		</tr>";
		$i++;
  	}
  	echo "</table>";
} else { echo "<div align='center'>".$locale['e10']."</div>"; }
closetable();

require_once THEMES."templates/footer.php";
?>
