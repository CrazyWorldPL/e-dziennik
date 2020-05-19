<?php
/*-------------------------------------------------------+
| e-dziennik
| Copyright (C) 2009-2010
| http://e-dziennik.xwp.pl
+--------------------------------------------------------+
| Plik: educator.php
| Autor: Szymon (szygmon) Michalewicz
+-------------------------------------------------------*/
require_once "../maincore.php";
require_once THEMES."templates/header.php";
include LOCALE.LOCALESET."educator.php";

if (!iTEACHER) { redirect("../index.php"); }

// Informacje
if (isset($_GET['status'])) {
  if ($_GET['status'] == "error") {
    echo "<div class='admin-message'>".$locale['e01']."</div>";
  } elseif ($_GET['status'] == "sendmsg") {
    echo "<div class='admin-message'>".$locale['e02']."</div>";
  } elseif ($_GET['status'] == "updt") {
    echo "<div class='admin-message'>".$locale['e12']."</div>";
  }
}

// Zapisywanie zachowania
if (isset($_POST['saveos'])) {
  	$result = dbquery("SELECT user_id, os1, os2  FROM ".DB_USERS." WHERE student_class='".$userdata['educator']."'");
  	while ($datau = dbarray($result)) {
  	  	$upuser = $datau['user_id'];
  	  	// Sprawdzanie czy oceny maj± byæ nadpisane...
  	  	if (isnum($_POST['os1_'.$upuser])) {
        		$oss = "os1='".$_POST['os1_'.$upuser]."'";
      		} else {
      		  	$oss = "";
         	}
         	if (isnum($_POST['os2_'.$upuser]) && $oss != "") {
        		$oss .= ", os2='".$_POST['os2_'.$upuser]."'";
      		} elseif (isnum($_POST['os2_'.$upuser]) && $oss == "") {
        		$oss .= "os2='".$_POST['os2_'.$upuser]."'";
      		}
         	// Aktualizacja bazy
	  	$update = dbquery("UPDATE ".DB_USERS." SET ".$oss." WHERE user_id='".$upuser."'");
	}
	redirect(FUSION_SELF."?status=updt");
}

// Klasy i przedmioty, które uczy nauczyciel
opentable($locale['e03']);

$list = dbquery("SELECT user_id, user_name, surname, name, os1, os2  FROM ".DB_USERS." WHERE student_class='".$userdata['educator']."'");
if (dbrows($list)) {
  	
  	// Opcje
	echo "<div style='text-align:center;margin-bottom:10px;'>
	<a href='messages.php?msg_send=0&class=".$userdata['educator']."&do=s'>".$locale['e04'].$locale['e05']."</a> ::
	<a href='messages.php?msg_send=0&class=".$userdata['educator']."&do=p'>".$locale['e04'].$locale['e06']."</a> ::
	<a href='".BASEDIR."print.php?type=OC&semestr=".$settings['semestr']."' onclick='return InfoPrint();'>".$locale['e14']."</a>
	</div>\n";
  
  	// Info
  	echo "<div class='admin-message'>".$locale['e13']."</div>";
  	
  	// Tabela uczniów
  	echo "<form name='settingsform' method='post' action='".FUSION_SELF."'>
	<table cellpadding='0' cellspacing='1' width='600' class='tbl-border center'>\n<tr>
	<td class='tbl2' width='40%'><strong>".$locale['user3']."</strong></td>
	<td class='tbl2' width='20%' align='center'><strong>".$locale['e10']."</strong></td>
	<td align='center' width='1%' class='tbl2' style='white-space:nowrap'><strong>".$locale['global_057']."</strong></td>
	<td class='tbl2' width='40%'><strong>".$locale['user2']."</strong></td>
	<td align='center' width='1%' class='tbl2' style='white-space:nowrap'><strong>".$locale['global_057']."</strong></td>
	</tr>";
	
	$i = 1;
	while ($data = dbarray($list)) {
	  $row_color = ($i % 2 == 0 ? "tbl1" : "tbl2");
	  echo "<tr><td class='".$row_color."'>".$i.". ".$data['surname']." ".$data['name']." (".$data['user_name'].")</td>
	  <td class='".$row_color."' align='center'>
	  1 <input type='text' name='os1_".$data['user_id']."' value='".os($data['os1'])."' maxlength='1' class='textbox' style='width:90px; text-align:center;' />
	  <br />
	  2 <input type='text' name='os2_".$data['user_id']."' value='".os($data['os2'])."' maxlength='1' class='textbox' style='width:90px; text-align:center;' />
	  </td>
	  <td class='".$row_color."' align='center'><a href='".BASEDIR."messages.php?msg_send=".$data['user_id']."'>".$locale['e07']."</a></td>";
	  // Konta rodziców
	  if ($data2 = dbarray(dbquery("SELECT user_id, user_name, name, surname FROM ".DB_USERS." WHERE id_child='".$data['user_id']."'"))) {
	  	echo "<td class='".$row_color."'>".$i.". ".$data2['surname']." ".$data2['name']." (".$data2['user_name'].")</td>
		  <td class='".$row_color."' align='center'><a href='".BASEDIR."messages.php?msg_send=".$data2['user_id']."'>".$locale['e07']."</a></td></tr>";
	  // Je¶li brak kont rodziców
	  } else {
 		echo "<td class='".$row_color."'>".$locale['e08']."</td>
 		<td class='".$row_color."' align='center'>-</td></tr>";
	  }
	  $i++;
	}
	echo "</table>
	<br />
	<div align='center'>
	<input type='submit' name='saveos' value='".$locale['e11']."' class='button' />
	</div>
	</form>";
} else {
  	echo "<div align='center'>".$locale['e09']."</div>";
}	
closetable();

echo "<script type='text/javascript'>\n"."function InfoPrint() {\n
return confirm('".$locale['e15']."');\n}
</script>\n";

require_once THEMES."templates/footer.php";
?>
