<?php
// Locale Settings
setlocale(LC_TIME, "pl", "pl_PL", "polish"); // Linux Server (Windows may differ)
$locale['charset'] = "utf-8";
$locale['xml_lang'] = "pl";
$locale['tinymce'] = "pl";
$locale['phpmailer'] = "pl";

// Full & Short Months
$locale['months'] = "&nbsp|styczeń|luty|marzec|kwiecień|maj|czerwiec|lipiec|sierpień|wrzesień|paĽdziernik|listopad|grudzień";
$locale['shortmonths'] = "&nbsp|st.|lt.|mar.|kwi.|maj|czer.|lip.|sier.|wrz.|paĽ.|lis.|gru.";

// Standard User Levels
$locale['user0'] = "Gość";
$locale['user1'] = "Użytkownik";
$locale['user2'] = "Rodzic";
$locale['user3'] = "Uczeń";
$locale['user4'] = "Nauczyciel";
$locale['user5'] = "Dyrektor";
$locale['user6'] = "Admin";

// Oceny semestralne
$locale['rat0'] = "-";
$locale['rat1'] = "ndst";
$locale['rat2'] = "dop";
$locale['rat3'] = "dst";
$locale['rat4'] = "db";
$locale['rat5'] = "bdb";
$locale['rat6'] = "cel";
$locale['ratn'] = "nk";

// Oceny z zachowania
$locale['os1'] = "Naganne";
$locale['os2'] = "Nieodpowiednie";
$locale['os3'] = "Poprawne";
$locale['os4'] = "Dobre";
$locale['os5'] = "Bardzo dobre";
$locale['os6'] = "Wzorowe";

// Forum Moderator Level(s)
$locale['userf1'] = "Moderator";
// Navigation
$locale['global_001'] = "Nawigacja";
$locale['global_002'] = "Brak linków\n";
// Users Online
$locale['global_010'] = "Aktualnie online";
$locale['global_011'] = "Gości online";
$locale['global_012'] = "Użytkowników online";
$locale['global_013'] = "Brak użytkowników online";
$locale['global_014'] = "Łącznie użytkowników";
$locale['global_015'] = "Nieaktywnych użytkowników";
$locale['global_016'] = "Najnowszy użytkownik";
// Welcome panel
$locale['global_035'] = "Powitanie";
// Latest Active Forum Threads panel
$locale['global_044'] = "Temat";
$locale['global_045'] = "Obejrzeń";
$locale['global_046'] = "Odpowiedzi";
$locale['global_047'] = "Ostatni post";
$locale['global_048'] = "Notatki";
$locale['global_049'] = "Napisane przez";
$locale['global_050'] = "Autor";
$locale['global_051'] = "Ankieta";
$locale['global_052'] = "Przesunięty";
$locale['global_053'] = "Brak rozpoczętych przez Ciebie tematów.";
$locale['global_054'] = "Brak napisanych przez Ciebie postów.";
$locale['global_055'] = "Nowych postów od Twojej ostatniej wizyty: %u";
$locale['global_056'] = "Moje obserwowane tematy";
$locale['global_057'] = "Opcje";
$locale['global_058'] = "Przestań <br /> obserwować";
$locale['global_059'] = "Brak obserwowanych przez Ciebie tematów.";
$locale['global_060'] = "Przestać obserwować temat?";
// News & Articles
$locale['global_070'] = "Napisane przez ";
$locale['global_071'] = "dnia ";
$locale['global_072'] = "Czytaj więcej";
$locale['global_074'] = " czytań";
$locale['global_075'] = "Drukuj";
$locale['global_076'] = "Edytuj";
$locale['global_077'] = "Informacje";
$locale['global_078'] = "Brak opublikowanych informacji";
// Page Navigation
$locale['global_090'] = "Poprzednia";
$locale['global_091'] = "Następna";
$locale['global_092'] = "Strona ";
$locale['global_093'] = " z ";
// Guest User Menu
$locale['global_100'] = "Logowanie";
$locale['global_101'] = "Nazwa użytkownika";
$locale['global_102'] = "Hasło";
$locale['global_103'] = "Zapamiętaj mnie";
$locale['global_104'] = "Zaloguj";
$locale['global_105'] = "Nie masz jeszcze konta? <br /><a href='".BASEDIR."register.php' class='side'>Zarejestruj się</a>";
$locale['global_106'] = "Nie możesz się zalogować?<br /> Poproś o <a href='".BASEDIR."lostpassword.php' class='side'>nowe hasło</a>";
$locale['global_107'] = "Rejestracja";
$locale['global_108'] = "Zapomniane hasło";
// Member User Menu
$locale['global_120'] = "Edytuj profil";
$locale['global_121'] = "Prywatne wiadomości";
$locale['global_122'] = "Lista kont";
$locale['global_123'] = "Panel administratora";
$locale['global_124'] = "Wyloguj";
$locale['global_125'] = "Nieprzeczytanych wiadomości: %u";
$locale['global_126'] = "";
$locale['global_127'] = "";
// Shoutbox
$locale['global_150'] = "Shoutbox";
$locale['global_151'] = "Nick:";
$locale['global_152'] = "Wiadomość:";
$locale['global_153'] = "Wyślij";
$locale['global_154'] = "Musisz zalogować się, aby móc dodać wiadomość.";
$locale['global_155'] = "Archiwum shoutboksa";
$locale['global_156'] = "Brak wiadomości. Może czas dodać własną?";
$locale['global_157'] = "Usuń";
$locale['global_158'] = "Kod potwierdzający:";
$locale['global_159'] = "Wpisz kod potwierdzający:";
// Footer Counter
$locale['global_170'] = "unikalna wizyta";
$locale['global_171'] = "Unikalnych wizyt";
$locale['global_172'] = "Wygenerowano w sekund: %s";
// Admin Navigation
$locale['global_180'] = "Powróć do panelu administratora";
$locale['global_181'] = "Powróć do strony głównej";
$locale['global_182'] = "<strong>Uwaga:</strong> Nie podano hasła Administratora, lub podane jest błędne.";
// Miscellaneous
$locale['global_190'] = "Aktywowano tryb prac na serwerze.";
$locale['global_192'] = "Wylogowuję jako: ";
$locale['global_193'] = "Loguję jako: ";
$locale['global_194'] = "Konto zostało zablokowane.";
$locale['global_195'] = "Konto nie jest aktywne.";
$locale['global_196'] = "Nieprawidłowa nazwa użytkownika lub hasło.";
$locale['global_197'] = "Proszę czekać na przekierowanie...<br /><br />
[ <a href='index.php'>Nie chcę czekać</a> ]";
$locale['global_198'] = "<strong>Ostrzeżenie:</strong> Wykryto plik setup.php. Proszę, usuń go natychmiast.";
$locale['global_199'] = "<strong>Ostrzeżenie:</strong> Nie ustawiono hasła administratora, <a href='".BASEDIR."edit_profile.php'>ustaw je</a> natychmiast.";
//Titles
$locale['global_200'] = " - ";
$locale['global_201'] = ": ";
$locale['global_202'] = $locale['global_200']."Szukaj";
//Themes
$locale['global_210'] = "PrzejdĽ do treści";
// No themes found
$locale['global_300'] = "nie znaleziono skórki.";
$locale['global_301'] = "Nie można wyświetlić strony. Jest to spowodowane brakiem plików odpowiadających za wygląd strony. Jeśli jesteś administratorem strony, uruchom swojego klienta FTP i wgraj do katalogu <em>/themes</em> jakąkolwiek skórkę zaprojektowaną dla <em>PHP-Fusion v7</em>. Następnie sprawdĽ w <em>Głównych ustawieniach</em> w <em>Panelu administratora</em> oraz upewnij się, że wybrana tam skórka jest w Twoim katalogu <em>/themes</em>. Jeśli tak nie jest, sprawdĽ, czy wgrana skórka ma taką samą nazwę (wliczając w to wielkość znaków, ważne na serwerach uniksowych) jak ta wybrana w <em>Głównych ustawieniach</em>.<br /><br />Jeśli jesteś użytkownikiem tej strony, skontaktuj się z administracją strony poprzez wysłanie e-maila na adres ".hide_email($settings['siteemail'])." oraz poinformuj o istniejącym problemie.";
$locale['global_302'] = "Wybrana przez Ciebie skórka nie istnieje lub jest niekompletna!";

?>
