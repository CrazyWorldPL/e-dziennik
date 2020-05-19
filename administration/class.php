<?php
/*-------------------------------------------------------+
| e-dziennik
| Copyright (C) 2009-2010
| http://e-dziennik.xwp.pl/
+--------------------------------------------------------+
| Plik: class.php
| Autor: Szymon (szygmon) Michalewicz
+-------------------------------------------------------*/
require_once "../maincore.php";
require_once THEMES."templates/admin_header.php";
include LOCALE.LOCALESET."admin/class.php";

if (!iADMIN || !defined("iAUTH") || $_GET['aid'] != iAUTH) { redirect("../index.php"); }

function random_pass($limit) {
	return substr(md5(date("d.m.Y.H.i.s").rand(1,1000000)) , 0 , $limit);
}

if (isset($_GET['status'])) {
	if ($_GET['status'] == "sn") {
		$message = $locale['k30'];
	} elseif ($_GET['status'] == "bl") {
		$message = $locale['k31'];
	} elseif ($_GET['status'] == "del") {
		$message = $locale['k32'];
	} elseif ($_GET['status'] == "delerr") {
		$message = $locale['k33'];
	}
	if ($message) {	echo "<div class='admin-message'>".$message."</div>\n"; }
} else {
	$result = dbquery("DELETE FROM ".DB_PREFIX."tmp_pass");
}

if (isset($_POST['save'])) {
	$class = stripinput($_POST['class']);
	$q = isnum($_POST['q']) ? $_POST['q'] : "";
	$i = 0; $j = 1; $k = 1;
	$account_parents = isset($_POST['account_parents']) ? $_POST['account_parents'] : "0";
	
	// Sprawdzanie poprawno¶ci wprowadzonych danych
	if ($q != "" && $class != "") {
		while($i < $q) {
		  	if ($j < 10) { $zero = 0; } else { $zero = ""; }
		  	// Tworzenie kont uczniom
		  	$pass[$j] = random_pass(6);
			$result = dbquery("INSERT INTO ".DB_USERS." (user_name, user_password, user_level, d_num, student_class, user_joined) VALUES ('".$class.$zero.$j."', '".md5(md5($pass[$j]))."', '103', '$j', '$class', '".time()."')");
			
			// Tworzenie kont rodzicom
			if ($account_parents != 0) {
	    			$child = dbarray(dbquery("SELECT user_id FROM ".DB_USERS." ORDER BY user_id DESC LIMIT 1"));
   				$id_child = $child['user_id'];
				$pass2[$j] = random_pass(6);
			    	$result = dbquery("INSERT INTO ".DB_USERS." (user_name, user_password, user_level, id_child, user_joined) VALUES ('p".$class.$zero.$j."', '".md5(md5($pass2[$j]))."', '102', '$id_child', '".time()."')");
	  		}
	  		
			$i++; $j++;	
		}
		
		// Dodawanie tymczasowych hase³, aby mo¿na by³o je wydrukowaæ
		for ($s = 0; $s < $q; $s++) {
		  	if ($k < 10) { $zero = 0; } else { $zero = ""; }
    			$tmp_pass .= $locale['k44'].$k." ".$locale['k41'].$class.$zero.$k.$locale['k42'].$pass[$k]."<br />";
    			if ($account_parents != 0) {
  				$tmp_pass .= $locale['k45'].$k." ".$locale['k41']."p".$class.$zero.$k.$locale['k42'].$pass2[$k]."<br />";
			}
			$k++;
  		}
		$add_tmp_pass = dbquery("INSERT INTO ".DB_PREFIX."tmp_pass (pass) VALUES ('$tmp_pass')");
		
		// Przekierowanie po pomy¶lnej operacji
		redirect(FUSION_SELF.$aidlink."&nusers&status=sn");
	}
	// Przekierowanie po nieudanej operacji
	redirect(FUSION_SELF.$aidlink."&status=bl");
} else if (isset($_GET['delete']) && (isset($_GET['class']))) {
	// Sprawdzanie, czy klasa ma jaki¶ przedmiot
	$test = dbarray(dbquery("SELECT student_subjects FROM ".DB_USERS." WHERE student_class='".$_GET['class']."' LIMIT 1"));
	if ($test['student_subjects'] != "") { redirect(FUSION_SELF.$aidlink."&status=delerr"); }
	$students = dbquery("SELECT user_id FROM ".DB_USERS." WHERE student_class='".$_GET['class']."'");
	// Usuwanie kont rodziców
	while ($par = dbarray($students)) {
 		$result = dbquery("DELETE FROM ".DB_USERS." WHERE id_child='".$par['user_id']."'");	
  	}
  	// Usuwanie kont uczniom
	$result = dbquery("DELETE FROM ".DB_USERS." WHERE student_class='".$_GET['class']."'");
	redirect(FUSION_SELF.$aidlink."&status=del");
} else {
	opentable($locale['k01']);
	echo "<form name='inputform' method='post' action='".FUSION_SELF.$aidlink."' onsubmit='return ValidateForm(this);'>\n";
	echo "<table cellpadding='0' cellspacing='0' class='center'>\n<tr>\n";
	echo "<td width='100' class='tbl'>".$locale['k10']."</td>\n";
	echo "<td width='80%' class='tbl'><input type='text' name='class' value='' class='textbox' style='width: 250px' /></td>\n";
	echo "</tr>\n<tr>\n";
	echo "<td width='100' class='tbl'>".$locale['k11']."</td>\n";
	echo "<td width='80%' class='tbl'><input type='text' name='q' value='' class='textbox' style='width: 20px' /></td>\n";
	echo "</tr>\n<tr>\n";
	echo "<td width='100' class='tbl' colspan='2'><label><input type='checkbox' name='account_parents' value='1' /></label>".$locale['k12']."</td>\n";
	echo "</tr>\n<tr>\n";
	echo "<td align='center' colspan='2' class='tbl'><br />\n";
	echo "<input type='submit' name='save' value='".$locale['k20']."' class='button' /></td>\n";
	echo "</tr>\n</table>\n</form>\n";
	closetable();
	
	// Lista klas
	opentable($locale['k02']);
	$result = dbquery("SELECT student_class FROM ".DB_USERS." WHERE d_num='1'");
	if (dbrows($result)) {
		echo "<table cellpadding='0' cellspacing='1' width='300' class='tbl-border center'>\n<tr>
		<td class='tbl2'><strong>".$locale['k60']."</strong></td>
		<td align='center' width='1%' class='tbl2' style='white-space:nowrap'><strong>".$locale['k61']."</strong></td>
		</tr>";
		$i = 0;
		while ($ldata = dbarray($result)) {
    			$row_color = ($i % 2 == 0 ? "tbl1" : "tbl2");
  			echo "<tr>
			<td class='".$row_color."'>".$ldata['student_class']."</td>
			<td class='".$row_color."' align='center'><a href='".FUSION_SELF.$aidlink."&delete&class=".$ldata['student_class']."' onclick=\"return confirm('".$locale['k50']."');\"'>".$locale['k21']."</a></td>
			</tr>";
  		}
		echo "</table>";
 	} else {
    		echo "<div align='center'>".$locale['k03']."</div>";
  	}
	closetable();
	// Dane dostêpowe
	if (isset($_GET['nusers'])) {
   		opentable($locale['k40']);
   		$paswd = dbarray(dbquery("SELECT * FROM ".DB_PREFIX."tmp_pass ORDER BY id DESC LIMIT 1"));
 		echo $paswd['pass'];
 		echo "<div align='center'><a href='".FUSION_SELF.$aidlink."'>".$locale['k43']."</a></div>";
	   	closetable();
 	}
}

require_once THEMES."templates/footer.php";
?>
