<?php
/*-------------------------------------------------------+
| e-dziennik
| Copyright (C) 2009-2010
| http://e-dziennik.xwp.pl/
+--------------------------------------------------------+
| Plik: notes.php
| Autor: Szymon (szygmon) Michalewicz
+----------------------------------------------------*/
require_once "maincore.php";
require_once THEMES."templates/header.php";
include LOCALE.LOCALESET."notes.php";

if (!iMEMBER) redirect("index.php");

if(isset($_GET['info'])) {
if($_GET['info'] == "zapisano"){
$tresctab = "".$locale['inf_01']."";
}elseif($_GET['info'] == "wyczyszczono"){
$tresctab = "".$locale['inf_02']."";
}
echo "<div class='admin-message'>".$tresctab."</div>";
}

opentable($locale['not_01']);

if (isset($_POST['save_note'])) {
  
$notatka = $_POST['notes'];
$notatka = str_replace("<?", "", $notatka);
$notatka = str_replace("?>", "", $notatka);
$notatka = str_replace("$", "", $notatka);
$notatka = str_replace("</", "", $notatka);

$result = dbquery("UPDATE ".DB_USERS." SET notes='".stripinput($notatka)."' WHERE user_id='".$userdata['user_id']."'");
redirect(FUSION_SELF."?info=zapisano");
}
if (isset($_POST['delete'])) {
$result = dbquery("UPDATE ".DB_USERS." SET notes='' WHERE user_id='".$userdata['user_id']."'");
redirect(FUSION_SELF."?info=wyczyszczono");
}

$pobinfo = dbarray(dbquery("SELECT notes FROM ".DB_USERS." WHERE user_id='".$userdata['user_id']."'"));
		
echo"".$locale['not_02']."<br/>";
echo"<br><form name='notka' method='post' action='".FUSION_SELF."'>
<table align='center' cellpadding='0' cellspacing='0' width='80%' border='0'>
<tr><td>
<center><TEXTAREA class='textbox' COLS='80%' ROWS='12' value='' name='notes' style='padding: 3px 6px 3px 6px;'>".$pobinfo['notes']."</TEXTAREA></td></tr></center>
<tr><td><center><br/><input type='submit' name='save_note' value='".$locale['not_03']."' class='button'>&nbsp;&nbsp;<input type='submit' name='delete' value='".$locale['not_04']."' class='button' onClick='return WyczyscNotke();'></center><br></form></td></tr></table>
</form>\n";
closetable();

echo "<script type='text/javascript'>
function WyczyscNotke() {
	return confirm('".$locale['zap_01']."');
}
</script>\n";

require_once THEMES."templates/footer.php";
?>
