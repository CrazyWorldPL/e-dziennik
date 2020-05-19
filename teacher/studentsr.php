<?php
/*-------------------------------------------------------+
| e-dziennik
| Copyright (C) 2009-2010
| http://e-dziennik.xwp.pl/
+--------------------------------------------------------+
| Plik: ratings.php
| Autor: Szymon (szygmon) Michalewicz
+-------------------------------------------------------*/
require_once "../maincore.php";
require_once THEMES."templates/header.php";
include LOCALE.LOCALESET."studentsr.php";

// JS
add_to_head ("<script type='text/javascript' src='".INCLUDES."jscripts/boxover.js'></script>");

if (!iDIRECTOR) { redirect("../index.php"); }

// Semestr
if (!isset($_GET['semestr'])) { 
	$semestr = $settings['semestr']; 
} else {
	$semestr = $_GET['semestr'];
}

// Content
opentable($locale['r10']);
	// Tworzenie listy
	$result = dbquery("SELECT user_id, user_name, name, surname, student_class FROM ".DB_USERS." WHERE user_level='103' ORDER BY student_class");
	$i = 0; $editlist = "";
	while ($data = dbarray($result)) {
		$editlist .= "<option value='".$data['user_id']."'>".$data['student_class']." - ".$data['surname']." ".$data['name']." (".$data['user_name'].")</option>\n";
	}		
	if (!isset($user_id)) { $user_id = false; }
	echo "<form name='settingsform' method='get' action='".FUSION_SELF."?semestr=$semestr&user_id=$user_id'>\n
		<div style='text-align:center'>
			<select name='semestr' class='textbox'>\n
				<option value='1'".($settings['semestr'] == 1 ? " selected='selected'" : "").">".$locale['r11']." 1</option>\n
				<option value='2'".($settings['semestr'] == 2 ? " selected='selected'" : "").">".$locale['r11']." 2</option>\n
			</select>
			<select name='user_id' class='textbox' style='width:250px'>\n".$editlist."</select>\n
			<input type='submit' name='editratings' value='".$locale['r12']."' class='button' />\n
		</div>\n
	</form>\n";
	closetable();

if (isset($_GET['user_id']) && isnum($_GET['user_id'])) {
$ud = dbarray(dbquery("SELECT student_subjects, os".$semestr.", student_class FROM ".DB_USERS." WHERE user_id='".$_GET['user_id']."'"));
// Przedmioty
$subjects = $ud['student_subjects'];
$subjects = explode(".", $subjects);
$i = 0;
while (isset($subjects[$i])) {
	$tmp = explode(":", $subjects[$i]);
	$sub[$i] = $tmp[0];
	$i++;
}

// Wyci±ganie nazw przedmiotów do tablicy
$asub = "'".implode("','", $sub)."'";
$result = dbquery("SELECT * FROM ".DB_SUBJECTS." WHERE subject_id IN ($asub)");
while ($subdata = dbarray($result)) {
	$sn[$subdata['subject_id']] = $subdata['subject_name'];
}

	
	
	opentable($locale['r14']);
	// Zachowanie
	echo "<div align='center' style='margin:5px;'>".$locale['r32']." - ".os($ud['os'.$semestr])."</div>";
	
	// Tabela ocen
	echo "<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border center'>
	<tr>
		<td class='tbl2'>".$locale['r30']."</td>
		<td class='tbl2' align='center' colspan='15'>".$locale['r13']."</td>
		<td class='tbl2' align='center'>".$locale['r16']."</td>
		<td class='tbl2' align='center'>".$locale['r17']."</td>
	</tr>";
		
	$j = 0; 
	$ac1 = 0; $ac2 = 0; $as1 = 0; $as2 = 0;
	while (isset($sub[$j]) && $sub[$j] != "") {
    	$data = dbarray(dbquery("SELECT ".$semestr."sem, ".$semestr."os FROM ".DB_PREFIX."sub".$sub[$j]." WHERE id_student='".$_GET['user_id']."'"));
 		$q1 = 0; $q2 = 0;
		$rat = explode("::", $data[$semestr.'sem']);
		
		// Opis oceny - po najechaniu zrobic
		$res = dbquery("SELECT ".$semestr."sem FROM ".DB_PREFIX."sub".$sub[$j]."_descr WHERE d_class='".$ud['student_class']."'");
		$dat = dbarray($res);
		$arr = explode("::", $dat[$semestr.'sem']);
		for ($i = 1; $i <= 15; $i++) {
			$tmp = explode("!!", $arr[$i-1]);
			$descr[$i] = $tmp[0];
			$waga[$i] = $tmp[1];
		}
		
 		for ($k = 1; $k<=15; $k++) {
     		if ($rat[$k-1] != "" && isnum(substr($rat[$k-1], 0, 1))) {
				if (substr($rat[$k-1], 1, 1) == "/") {
		    		$q1 = $q1 + (((substr($rat[$k-1], 0, 1) + substr($rat[$k-1], 2, 1)) / 2) * $waga[$k]);
				} else {
			   		$q1 = $q1 + (substr($rat[$k-1], 0, 1) * $waga[$k]);
			   	}
				$q2 = $q2 + $waga[$k];
			}
   		}
   		if ($q2 != 0) {
 			$average = $q1 / $q2;
		} else {
    		$average = 0;
  		}
  		$ac1 = $ac1 + $average;
  		$ac2++;
 		echo "<tr>
			<td class='tbl1' width='20%'>".$sn[$sub[$j]]."</td>";
			for ($oc = 1; $oc <= 15; $oc++) {
				if (($oc % 2) != 0) { $t = 2; } else { $t = 1; }
				$dane = ($descr[$oc] != "" ? "title=\"header=[<div style='text-align:center'><strong>".$locale['r34']."</strong></div>] body=[<div style='text-align:center'><br />".$descr[$oc]."</div>] fade=[on]\" style='cursor:pointer;'" : "");
				echo "<td class='tbl".$t."' align='center' width='4%' ".((substr($waga[$oc], -1, 1) == "*") ? "style='background:#FF3333; color:#ffffff'" : "")."><span ".$dane.">".$rat[$oc-1]."</span></td>";
			}
  			echo "<td class='tbl1' align='center' width='5%' ".(($average < 2) ? "style='background:#FF3333; color:#ffffff'" : "").">".round($average, 2)."</td>
			<td class='tbl2' align='center' width='5%' ".($data[$semestr.'os'] == "" ? "style='background:green; color:#ffffff'" : ((substr($data[$semestr.'os'], 0, 1) < 2 && $data[$semestr.'os'] != "" && $data[$semestr.'os'] != "nk") ? "style='background:#FF3333; color:#ffffff'" : "")).">".ratingsemestr(substr(($data[$semestr.'os'] == "" ? round($average) : $data[$semestr.'os']), 0, 1))."</td>
		</tr>";
		$j++;
		
		// ¦rednia ocen koñcowych
		if ($data[$semestr.'os'] != "" && $data[$semestr.'os'] != "nk") {
			$as1 = $as1 + $data[$semestr.'os'];
			$as2++;
		}
	}
	// ¦rednia z wszystkich przedmiotów
	if ($ac2 != 0) {
	  	$ava = $ac1 / $ac2;
	  	$ava = round($ava, 2);
	} else {
 		$ava = 0;
 	}
 	// ¦rednia ocen semestralnych
 	if ($as2 != 0) {
	  	$as = $as1 / $as2;
	  	$as = round($as, 2);
	} else {
 		$as = 0;
 	}
	echo "<tr>
		<td class='tbl2' colspan='16' align='right'>".$locale['r31'].":</td>
		<td class='tbl2' align='center' width='5%'>".$ava."</td>
		<td class='tbl2' align='center' width='5%'>".$as."</td>
	</tr>";
	echo "</table>
	<div align='center' style='margin-top:5px;'><a href='".BASEDIR."print.php?type=SR&semestr=$semestr&user_id=".$_GET['user_id']."'>".$locale['r33']."</a></div>";
	closetable();
}

require_once THEMES."templates/footer.php";
?>
