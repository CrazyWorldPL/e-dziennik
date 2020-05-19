<?php
/*-------------------------------------------------------+
| e-dziennik
| Copyright (C) 2009-2010
| http://e-dziennik.xwp.pl/
+--------------------------------------------------------+
| Plik: theme.php
| Autor: Szymon (szygmon) Michalewicz
+-------------------------------------------------------*/
if (!defined("IN_FUSION")) { die("Access Denied"); }

define("THEME_BULLET", "<span class='bullet'>&middot;</span>");

require_once INCLUDES."theme_functions_include.php";

function render_page($license=false) {
	
	global $settings, $locale;
	//Header
	echo "<div class='full-header'>
		<div class='content' align='center'>
			<div class='menu'>".showsublinks(" ".THEME_BULLET." ", "white")."</div>
			<div class='logo'>".showbanners()."</div>";
	
			//Content
			echo "<table cellpadding='0' cellspacing='0' align='center' width='100%'>\n<tr>\n";
			if (LEFT) { echo "<td class='side-border-left' valign='top'>".LEFT."</td>"; }
			echo "<td class='main-bg' valign='top'>".U_CENTER.CONTENT.L_CENTER."</td>";
			if (RIGHT) { echo "<td class='side-border-right' valign='top'>".RIGHT."</td>"; }
			echo "</tr>\n</table>";

		echo "</div>
		<div class='footer'>".stripslashes($settings['footer'])."<br />\n".showcopyright()."</div>
	</div>";

}

function opentable($title) {

	echo "<div class='opentable'>
		<div class='opentable-left'>
			<div class='opentable-right'>
				<div class='opentable-text'>".$title."</div>
			</div>
		</div>
	</div>
	<div class='table'>
		<div class='table-left'>
			<div class='table-right'>
				<div class='table-text'>";

}

function closetable() {

					echo "<div class='clr'></div>
				</div>
			</div>
		</div>
	</div>
	<div class='closetable'>
		<div class='closetable-left'>
			<div class='closetable-right'>&nbsp;
			</div>
		</div>
	</div>";

}

function openside($title, $collapse = false, $state = "on") {

	global $panel_collapse; $panel_collapse = $collapse;
	
	echo "<div class='opentable'>
		<div class='opentable-left'>
			<div class='opentable-right'>
				<div class='opentable-text'>".$title."</div>";
				if ($collapse == true) {
					$boxname = str_replace(" ", "", $title);
						echo "<div align='right'>".panelbutton($state, $boxname)."</div>";
				}
			echo "</div>
		</div>
	</div>
	<div class='table'>
		<div class='table-left'>
			<div class='table-right'>
				<div class='table-text'>";	
	if ($collapse == true) { echo panelstate($state, $boxname); }

}

function closeside() {
	
	global $panel_collapse;

	if ($panel_collapse == true) { echo ""; }	
					echo "<div class='clr'></div>
				</div>
			</div>
		</div>
	</div>
	<div class='closetable'>
		<div class='closetable-left'>
			<div class='closetable-right'>&nbsp;
			</div>
		</div>
	</div>";

}
?>
