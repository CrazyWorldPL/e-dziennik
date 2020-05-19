<?php
/*-------------------------------------------------------+
| PHP-Fusion Content Management System
| Copyright &copy; 2002 - 2008 Nick Jones
| http://www.php-fusion.co.uk/
+--------------------------------------------------------+
| Filename: user_skype_include_var.php
| Author: bartek124
| E-Mail: bartek124@php-fusion.pl
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
if (!defined("IN_FUSION")) { die("Access Denied"); }

$user_field_name = $locale['uf_skype'];
$user_field_desc = $locale['uf_skype_desc'];
$user_field_dbname = "user_skype";
$user_field_group = 1;
$user_field_dbinfo = "VARCHAR(20) NOT NULL DEFAULT ''";
?>