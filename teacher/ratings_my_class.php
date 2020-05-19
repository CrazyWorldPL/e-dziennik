<?php
/*-------------------------------------------------------+
| e-dziennik
| Copyright (C) 2009-2010
| http://e-dziennik.xwp.pl
+--------------------------------------------------------+
| Plik: ratings.php
| Autor: Szymon (szygmon) Michalewicz
+-------------------------------------------------------*/
require_once "../maincore.php";
require_once THEMES."templates/header.php";
include LOCALE.LOCALESET."ratings.php";

if (!iTEACHER) { redirect("../index.php"); }

// Semestr
if (!isset($_GET['semestr'])) {
	$semestr = $settings['semestr']; 
} else {
	$semestr = $_GET['semestr'];
}

if (isset($_GET['status']) && $_GET['status'] == "updt") {
	echo "<div class='admin-message'>".$locale['r01']."</div>";
}

// Klasy i przedmioty, które uczy nauczyciel
if ($userdata['educator']) {
	opentable($locale['r10b']);
	
	$result = dbarray(dbquery("SELECT student_subjects FROM ".DB_USERS." WHERE student_class='".$userdata['educator']."' AND d_num='1'"));
	$classs = explode(".", $result['student_subjects']);
	
	// Wyci±ganie id przedmiotów
	$i = 0; 
	while (isset($classs[$i])) {
		$tmp = explode(":", $classs[$i]);
		$sub[$i] = $tmp[0];
		$i++;
	}	
	
	// Wyci±ganie nazw przedmiotów do tablicy
	$asub = "'".implode("','", $sub)."'";
	$result = dbquery("SELECT * FROM ".DB_SUBJECTS." WHERE subject_id IN ($asub)");
	while ($subdata = dbarray($result)) {
		$sn[$subdata['subject_id']] = $subdata['subject_name'];
	}
	
	// Tworzenie listy
	$i = 0; $editlist = "";
	while (isset($classs[$i])) {
		$a = explode(":", $classs[$i]);
		$editlist .= "<option value='".$userdata['educator'].":".$a[0]."'>".$userdata['educator']." - ".$sn[$a[0]]."</option>\n";
		$i++;
	}		
	if (!isset($ratings_id)) { $ratings_id = false; }
	echo "<form name='settingsform' method='get' action='".FUSION_SELF."?semestr=$semestr&ratings_id=$ratings_id'>\n
		<div style='text-align:center'>\n
			<select name='semestr' class='textbox'>\n
				<option value='1'".($settings['semestr'] == 1 ? " selected='selected'" : "").">".$locale['r11']." 1</option>\n
				<option value='2'".($settings['semestr'] == 2 ? " selected='selected'" : "").">".$locale['r11']." 2</option>\n
			</select>
			<select name='ratings_id' class='textbox' style='width:250px'>\n".$editlist."</select>\n
			<input type='submit' name='editratings' value='".$locale['r12']."' class='button' />\n
		</div>\n
	</form>\n";
	
	closetable();
} else {
  	opentable($locale['r10']);
  	echo "<div align='center'>".$locale['r20b']."</div>";
  	closetable();
}

// Oceny klasy
if (isset($_GET['ratings_id'])) {
	$tmp2 = explode(":", $_GET['ratings_id']);
	$class = $tmp2[0];
	$subject = $tmp2[1];
  
	$result = dbquery(
	"SELECT ts.*, user_id, surname, name, user_name FROM ".DB_PREFIX."sub$subject ts
	LEFT JOIN ".DB_USERS." tu ON ts.id_student=tu.user_id
	WHERE class='$class'
	ORDER BY id_student");
  
	opentable($locale['r13'].": ".$class." - ".$sn[$subject]." - ".$locale['r11']." ".$semestr);
	echo "<div class='admin-message'>".$locale['r14']."</div>";
  
	// Tabela ocen
	$cs = $class.":".$subject;
	echo "<form name='ratingsform' method='post' action='".FUSION_SELF."?semestr=$semestr&ratings_id=$cs'>\n
	<table cellpadding='0' cellspacing='1' width='100%' class='tbl-border center'>
	<tr>
		<td class='tbl2' rowspan='2'>".$locale['r15']."</td>
		<td class='tbl2' align='center' colspan='15'>".$locale['r13']."</td>
		<td class='tbl2' rowspan='3' align='center'>".$locale['r16']."</td>
		<td class='tbl2' rowspan='3' align='center'>".$locale['r17']."</td>
	</tr><tr>";
		
	// Opis oceny
	$res = dbquery("SELECT ".$semestr."sem FROM ".DB_PREFIX."sub".$subject."_descr WHERE d_class='$class'");
	$dat = dbarray($res);
	$arr = explode("::", $dat[$semestr.'sem']);
	for ($i = 1; $i <= 15; $i++) {
		$tmp = explode("!!", $arr[$i-1]);
		echo "<td class='tbl2' height='80px'>
			<div id='fr90'>
				<input type='text' id='r90' name='rdesc".$i."' value='".$tmp[0]."' style='width:60px;' />
			</div>
		</td>";
	}
	echo "</tr><tr>
		<td class='tbl2' align='right'>".$locale['r21']."</td>";
		// Waga oceny
		for ($i = 1; $i <= 15; $i++) {
			$tmp = explode("!!", $arr[$i-1]);
			$waga[$i] = $tmp[1];
			echo "<td class='tbl2' align='center'><input type='text' name='waga".$i."' value='".(isnum(substr($waga[$i], 0, 1)) ? $waga[$i] : 1)."' style='width:20px;' /></td>";
		}
	echo "</tr>";
	
	$ac1 = 0; $ac2 = 0; $dnum = 1; $as1 = 0; $as2 = 0;
	$uid = "";
	while ($data = dbarray($result)) {
		$rat = explode("::", $data[$semestr.'sem']);
	  	$uid .= (($uid != "") ? "." : "").$data['user_id'];
		// ¦rednia
	  	$q1 = 0; $q2 = 0;
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
		
		echo "<tr class='rat-student'>
			<td class='tbl1' width='20%'>".$dnum.". ".$data['surname']." ".$data['name']." (".$data['user_name'].")</td>";
			for ($oc = 1; $oc <= 15; $oc++) {
				if (($oc % 2) != 0) { $t = 2; } else { $t = 1; }
				echo "<td class='tbl".$t."' align='center' width='3%' ".((substr($waga[$oc], -1, 1) == "*") ? "style='background:#FF3333; color:#ffffff'" : "").">
					<input type='text' name='".$semestr."o".$oc."_".$data['user_id']."' value='".$rat[$oc-1]."' maxlength='5' class='textbox' style='width:24px; text-align:center;' />
				</td>";
			}
			echo "<td class='tbl1' align='center' width='5%' ".(($average < 2) ? "style='background:#FF3333; color:#ffffff'" : "").">".round($average, 2)."</td>
			<td class='tbl2' align='center' width='5%' ".(($data[$semestr.'os'] == "") ? "style='background:green; color:#ffffff'" : ((substr($data[$semestr.'os'], 0, 1) < 2 && $data[$semestr.'os'] != "" && $data[$semestr.'os'] != "nk") ? "style='background:#FF3333; color:#ffffff'" : ""))."><input type='text' name='".$semestr."os_".$data['user_id']."' value='".ratingsemestr(substr(($data[$semestr.'os'] == "" ? round($average) : $data[$semestr.'os']), 0, 1))."' maxlength='2' class='textbox' style='width:30px; text-align:center;' /></td>";
		echo "</tr>";
		$dnum++;
		
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
		<td class='tbl2' colspan='16' align='right'>".$locale['r18'].":</td>
		<td class='tbl2' align='center' width='5%'>".$ava."</td>
		<td class='tbl2' align='center' width='5%'>".$as."</td>
	</tr></table>";
  
	echo "<div align='center'><br /><input type='submit' name='saveratings' value='".$locale['r19']."' class='button' /></div>\n
	</form>";
		
	closetable();
}

// Zapisywanie ocen
if (isset($_POST['saveratings'])) {
	$tmp2 = explode(":", $_GET['ratings_id']);
	$subject = $tmp2[1];
  
	$userid = explode(".", $uid);
	for ($uq = 0; isset($userid[$uq]); $uq++) {
		if (isnum(substr($_POST[$semestr.'os_'.$userid[$uq]], 0, 1)) || $_POST[$semestr.'os_'.$userid[$uq]] == "nk") {
			$dbas = ",".$semestr."os='".$_POST[$semestr.'os_'.$userid[$uq]]."'";
		} else {
			$dbas = "";
		}
		for ($i = 1; $i <=15; $i++) {
			$tab[$i] = $_POST[$semestr.'o'.$i.'_'.$userid[$uq]];
		}
		$sem = implode("::", $tab);
		
		$result = dbquery("UPDATE ".DB_PREFIX."sub$subject SET 
			".$semestr."sem='".$sem."'
			".$dbas."
			WHERE id_student='$userid[$uq]'
		");
	}
  
	// Tabela z opisem i wag±
  	for ($i = 1; $i <=15; $i++) {
		$rdesc[$i] = $_POST['rdesc'.$i];
		$wg[$i] = $_POST['waga'.$i];
		$scal[] = $rdesc[$i]."!!".$wg[$i];
	}
	$alldescr = implode("::", $scal);
	
    $result = dbquery("UPDATE ".DB_PREFIX."sub".$subject."_descr SET ".$semestr."sem='".$alldescr."' WHERE d_class='$class'");
	redirect(FUSION_SELF."?semestr=$semestr&ratings_id=".$_GET['ratings_id']."&status=updt");
}

require_once THEMES."templates/footer.php";
?>
