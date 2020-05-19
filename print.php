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
| Filename: print.php
| Author: Nick Jones (Digitanium)
| Edit by: szygmon
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
require_once "maincore.php";
include LOCALE.LOCALESET."print.php";

echo "<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>\n";
echo "<html>\n<head>\n";
echo "<title>".$settings['sitename']."</title>\n";
echo "<meta http-equiv='Content-Type' content='text/html; charset=".$locale['charset']."' />\n";
echo "<meta name='description' content='".$settings['description']."' />\n";
echo "<meta name='keywords' content='".$settings['keywords']."' />\n";
echo "<style type=\"text/css\">\n";
echo "body { font-family:Verdana,Tahoma,Arial,Sans-Serif;font-size:14px; }\n";
echo "hr { height:1px;color:#ccc; }\n";
echo ".small { font-family:Verdana,Tahoma,Arial,Sans-Serif;font-size:12px; }\n";
echo ".small2 { font-family:Verdana,Tahoma,Arial,Sans-Serif;font-size:12px;color:#666; }\n";
echo "</style>\n</head>\n<body>\n";
if ((isset($_GET['type']) && $_GET['type'] == "O") && (isset($_GET['semestr']) && isnum($_GET['semestr']))) {
	// Oceny ucznia ///
echo "<div style='width:600px'>";
	include LOCALE.LOCALESET."ratings.php";
	if (iSTUDENT) { $ud = $userdata; } elseif (iPARENT) { $ud = dbarray(dbquery("SELECT * FROM ".DB_USERS." WHERE user_id='".$userdata['id_child']."'")); }
	$semestr = $_GET['semestr'];
	
	// Wychowawca
	$educator = dbquery("SELECT name, surname FROM ".DB_USERS." WHERE educator='".$ud['student_class']."'");
	if ($ed = dbarray($educator)) {
		echo "<div style='float:right;' align='right'>".$locale['r35']."<br />".$ed['name']." ".$ed['surname']."</div>";
	}
	
	echo $locale['r40'].$ud['surname']." ".$ud['name']."<br />
	".$locale['r11']." ".$semestr."<hr style='border-bottom:1px solid #000; border-top:0px;' />";
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
	
	// Zachowanie
	echo "<div align='center' style='margin:5px;'>".$locale['r32']." - ".os($ud['os'.$semestr])."</div>";
	
	// Tabela ocen
	echo "<table cellpadding='1' border='1' cellspacing='0' width='100%'>
		<tr>
			<td>".$locale['r30']."</td>
			<td align='center' colspan='15'>".$locale['r13']."</td>
			<td align='center'>".$locale['r16']."</td>
			<td align='center'>".$locale['r17']."</td>
		</tr>";
	$j = 0;
	$ac1 = 0; $ac2 = 0; $as1 = 0; $as2 = 0;
	while (isset($sub[$j]) && $sub[$j] != "") {
    	$data = dbarray(dbquery("SELECT ".$semestr."sem, ".$semestr."os FROM ".DB_PREFIX."sub".$sub[$j]." WHERE id_student='".$ud['user_id']."'"));
 		$q1 = 0; $q2 = 0;
		$rat = explode("::", $data[$semestr.'sem']);
 		$q1 = 0; $q2 = 0;
		
		// Opis oceny
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
			<td width='20%'>".$sn[$sub[$j]]."</td>";
			for ($oc = 1; $oc <= 15; $oc++) {
				echo "<td align='center' width='4%' ".((substr($waga[$oc], -1, 1) == "*") ? "style='background:#FF3333; color:#ffffff'" : "").">".$rat[$oc-1]."</td>";
			}
  			echo "<td align='center' width='5%' ".(($average < 2) ? "style='background:#FF3333; color:#ffffff'" : "").">".round($average, 2)."</td>
			<td align='center' width='5%' ".($data[$semestr.'os'] == "" ? "style='background:green; color:#ffffff'" : ((substr($data[$semestr.'os'], 0, 1) < 2 && $data[$semestr.'os'] != "" && $data[$semestr.'os'] != "nk") ? "style='background:#FF3333; color:#ffffff'" : "")).">".ratingsemestr(substr($data[$semestr.'os'] == "" ? round($average) : $data[$semestr.'os'], 0, 1))."</td>
		</tr>";
		$j++;
		
		// _rednia ocen ko_cowych
		if ($data[$semestr.'os'] != "" && $data[$semestr.'os'] != "nk") {
			$as1 = $as1 + $data[$semestr.'os'];
			$as2++;
		}
	}
	// _rednia z wszystkich przedmiotów
	if ($ac2 != 0) {
	  	$ava = $ac1 / $ac2;
	  	$ava = round($ava, 2);
	} else {
 		$ava = 0;
 	}
 	// _rednia ocen semestralnych
 	if ($as2 != 0) {
	  	$as = $as1 / $as2;
	  	$as = round($as, 2);
	} else {
 		$as = 0;
 	}
	echo "<tr>
		<td colspan='16' align='right'>".$locale['r31'].":</td>
		<td align='center' width='5%'>".$ava."</td>
		<td align='center' width='5%'>".$as."</td>
	</tr>";
	echo "</table>";
echo "</div>";

} 
// Oceny ucznia - dla dyrektora
elseif ((isset($_GET['type']) && $_GET['type'] == "SR") && (isset($_GET['semestr']) && isnum($_GET['semestr'])) && (isset($_GET['user_id']) && isnum($_GET['user_id']))) {
	// Oceny ucznia ///
echo "<div style='width:600px'>";
	include LOCALE.LOCALESET."ratings.php";
	$ud = dbarray(dbquery("SELECT * FROM ".DB_USERS." WHERE user_id='".$_GET['user_id']."'"));
	$semestr = $_GET['semestr'];
	
	// Wychowawca
	$educator = dbquery("SELECT name, surname FROM ".DB_USERS." WHERE educator='".$ud['student_class']."'");
	if ($ed = dbarray($educator)) {
		echo "<div style='float:right;' align='right'>".$locale['r35']."<br />".$ed['name']." ".$ed['surname']."</div>";
	}
	
	echo $locale['r40'].$ud['surname']." ".$ud['name']."<br />
	".$locale['r11']." ".$semestr."<hr style='border-bottom:1px solid #000; border-top:0px;' />";
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
	
	// Zachowanie
	echo "<div align='center' style='margin:5px;'>".$locale['r32']." - ".os($ud['os'.$semestr])."</div>";
	
	// Tabela ocen
	echo "<table cellpadding='1' border='1' cellspacing='0' width='100%'>
		<tr>
			<td>".$locale['r30']."</td>
			<td align='center' colspan='15'>".$locale['r13']."</td>
			<td align='center'>".$locale['r16']."</td>
			<td align='center'>".$locale['r17']."</td>
		</tr>";
	$j = 0;
	$ac1 = 0; $ac2 = 0; $as1 = 0; $as2 = 0;
	while (isset($sub[$j]) && $sub[$j] != "") {
    	$data = dbarray(dbquery("SELECT ".$semestr."sem, ".$semestr."os FROM ".DB_PREFIX."sub".$sub[$j]." WHERE id_student='".$ud['user_id']."'"));
 		$q1 = 0; $q2 = 0;
		$rat = explode("::", $data[$semestr.'sem']);
 		$q1 = 0; $q2 = 0;
		
		// Opis oceny
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
			<td width='20%'>".$sn[$sub[$j]]."</td>";
			for ($oc = 1; $oc <= 15; $oc++) {
				echo "<td align='center' width='4%' ".((substr($waga[$oc], -1, 1) == "*") ? "style='background:#FF3333; color:#ffffff'" : "").">".$rat[$oc-1]."</td>";
			}
  			echo "<td align='center' width='5%' ".(($average < 2) ? "style='background:#FF3333; color:#ffffff'" : "").">".round($average, 2)."</td>
			<td align='center' width='5%' ".($data[$semestr.'os'] == "" ? "style='background:green; color:#ffffff'" : ((substr($data[$semestr.'os'], 0, 1) < 2 && $data[$semestr.'os'] != "" && $data[$semestr.'os'] != "nk") ? "style='background:#FF3333; color:#ffffff'" : "")).">".ratingsemestr(substr($data[$semestr.'os'] == "" ? round($average) : $data[$semestr.'os'], 0, 1))."</td>
		</tr>";
		$j++;
		
		// _rednia ocen ko_cowych
		if ($data[$semestr.'os'] != "" && $data[$semestr.'os'] != "nk") {
			$as1 = $as1 + $data[$semestr.'os'];
			$as2++;
		}
	}
	// _rednia z wszystkich przedmiotów
	if ($ac2 != 0) {
	  	$ava = $ac1 / $ac2;
	  	$ava = round($ava, 2);
	} else {
 		$ava = 0;
 	}
 	// _rednia ocen semestralnych
 	if ($as2 != 0) {
	  	$as = $as1 / $as2;
	  	$as = round($as, 2);
	} else {
 		$as = 0;
 	}
	echo "<tr>
		<td colspan='16' align='right'>".$locale['r31'].":</td>
		<td align='center' width='5%'>".$ava."</td>
		<td align='center' width='5%'>".$as."</td>
	</tr>";
	echo "</table>";
echo "</div>";

} 
// Wychowawca drukuje
elseif ((isset($_GET['type']) && $_GET['type'] == "OC") && (isset($_GET['semestr']) && isnum($_GET['semestr']))) {
	include LOCALE.LOCALESET."ratings.php";
	
	if (isset($_GET['class'])) {
		$cla = $_GET['class'];
	} else {
		$cla = $userdata['educator'];
	}
	$semestr = $_GET['semestr'];
	
	// Wychowawca
	$educator = dbquery("SELECT name, surname FROM ".DB_USERS." WHERE educator='".$cla."'");
	$ed = dbarray($educator);

	// Oceny ucznia ///
	echo "<div style='width:48%;float:left;'>";
	$co = 1;
	$resu = dbquery("SELECT user_id, name, surname, student_subjects, os".$semestr." FROM ".DB_USERS." WHERE student_class='".$cla."'"); 
	while ($ud = dbarray($resu)) {
		echo "<div style='float:right;' align='right'>".$locale['r35']."<br />".$ed['name']." ".$ed['surname']."</div>";

		echo $locale['r40'].$ud['surname']." ".$ud['name']."<br />
		".$locale['r11']." ".$semestr."<hr style='border-bottom:1px solid #000; border-top:0px;' />";
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
		
		// Zachowanie
		echo "<div align='center' style='margin:5px;'>".$locale['r32']." - ".os($ud['os'.$semestr])."</div>";
		
		// Tabela ocen
		echo "<table cellpadding='1' border='1' cellspacing='0' width='100%'>
			<tr>
				<td>".$locale['r30']."</td>
				<td align='center' colspan='15'>".$locale['r13']."</td>
				<td align='center'>".$locale['r16']."</td>
				<td align='center'>".$locale['r17']."</td>
			</tr>";
		$j = 0;
		$ac1 = 0; $ac2 = 0; $as1 = 0; $as2 = 0;
		while (isset($sub[$j]) && $sub[$j] != "") {
			$data = dbarray(dbquery("SELECT ".$semestr."sem, ".$semestr."os FROM ".DB_PREFIX."sub".$sub[$j]." WHERE id_student='".$ud['user_id']."'"));
			$q1 = 0; $q2 = 0;
			$rat = explode("::", $data[$semestr.'sem']);
			$q1 = 0; $q2 = 0;
			
			// Opis oceny
			$res = dbquery("SELECT ".$semestr."sem FROM ".DB_PREFIX."sub".$sub[$j]."_descr WHERE d_class='".$cla."'");
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
				<td width='20%'>".$sn[$sub[$j]]."</td>";
				for ($oc = 1; $oc <= 15; $oc++) {
					echo "<td align='center' width='4%' ".((substr($waga[$oc], -1, 1) == "*") ? "style='background:#FF3333; color:#ffffff'" : "").">".$rat[$oc-1]."</td>";
				}
				echo "<td align='center' width='5%' ".(($average < 2) ? "style='background:#FF3333; color:#ffffff'" : "").">".round($average, 2)."</td>
				<td align='center' width='5%' ".($data[$semestr.'os'] == "" ? "style='background:green; color:#ffffff'" : ((substr($data[$semestr.'os'], 0, 1) < 2 && $data[$semestr.'os'] != "" && $data[$semestr.'os'] != "nk") ? "style='background:#FF3333; color:#ffffff'" : "")).">".ratingsemestr(substr($data[$semestr.'os'] == "" ? round($average) : $data[$semestr.'os'], 0, 1))."</td>
			</tr>";
			$j++;
			
			// _rednia ocen ko_cowych
			if ($data[$semestr.'os'] != "" && $data[$semestr.'os'] != "nk") {
				$as1 = $as1 + $data[$semestr.'os'];
				$as2++;
			}
		}
		// _rednia z wszystkich przedmiotów
		if ($ac2 != 0) {
			$ava = $ac1 / $ac2;
			$ava = round($ava, 2);
		} else {
			$ava = 0;
		}
		// _rednia ocen semestralnych
		if ($as2 != 0) {
			$as = $as1 / $as2;
			$as = round($as, 2);
		} else {
			$as = 0;
		}
		echo "<tr>
			<td colspan='16' align='right'>".$locale['r31'].":</td>
			<td align='center' width='5%'>".$ava."</td>
			<td align='center' width='5%'>".$as."</td>
		</tr>";
		echo "</table>";
		if ($co % 2 == 1) {
			echo "</div>
			<div style='width:4%;height:10px;float:left'></div>
			<div style='width:48%;float:left'>";
		} else {
			echo "</div>
			<p style='page-break-after:always;'>&nbsp;</p>
			<div style='width:48%;float:left'>";
		}
		$co++;
	}
echo "</div>";

} elseif (isset($_GET['type']) && $_GET['type'] == "T" && $settings['enable_terms'] == 1) {
	echo "<strong>".$settings['sitename']." ".$locale['600']."</strong><br />\n";
	echo "<span class='small'>".$locale['601']." ".ucfirst(showdate("longdate", $settings['license_lastupdate']))."</span>\n";
	echo "<hr />".stripslashes($settings['license_agreement'])."\n";
} else {
	redirect("index.php");
}
echo "</body>\n</html>\n";
?>
