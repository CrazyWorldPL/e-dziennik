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
| Filename: multisite_include.php
| Author: Nick Jones (Digitanium)
| Edit by: Szymon (szygmon) Michalewicz
+--------------------------------------------------------+
| This program is released as free software under the
| Affero GPL license. You can redistribute it and/or
| modify it under the terms of this license which you
| can read by viewing the included agpl.txt or online
| at www.gnu.org/licenses/agpl.html. Removal of this
| copyright header is strictly prohibited without
| written permission from the original author(s).
+--------------------------------------------------------*/
// Cookie prefix
define("COOKIE_PREFIX", "ed_");
// Database table definitions
define("DB_ADMIN", DB_PREFIX."admin");
define("DB_BBCODES", DB_PREFIX."bbcodes");
define("DB_CAPTCHA", DB_PREFIX."captcha");
define("DB_FLOOD_CONTROL", DB_PREFIX."flood_control");
define("DB_INFUSIONS", DB_PREFIX."infusions");
define("DB_MESSAGES", DB_PREFIX."messages");
define("DB_MESSAGES_OPTIONS", DB_PREFIX."messages_options");
define("DB_ONLINE", DB_PREFIX."online");
define("DB_PANELS", DB_PREFIX."panels");
define("DB_SETTINGS", DB_PREFIX."settings");
define("DB_SHOUTBOX", DB_PREFIX."shoutbox");
define("DB_SITE_LINKS", DB_PREFIX."site_links");
define("DB_SMILEYS", DB_PREFIX."smileys");
define("DB_USER_FIELDS", DB_PREFIX."user_fields");
define("DB_USERS", DB_PREFIX."users");

define("DB_SUBJECTS", DB_PREFIX."subjects");
?>
