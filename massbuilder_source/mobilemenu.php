<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg9.php" ?>
<?php include_once "ewmysql9.php" ?>
<?php include_once "phpfn9.php" ?>
<?php include_once "userfn9.php" ?>
<?php
	$conn = ew_Connect();
	$Language = new cLanguage();

	// Security
	$Security = new cAdvancedSecurity();
	if (!$Security->IsLoggedIn()) $Security->AutoLogin();
	$Security->LoadUserLevel(); // load User Level
?>
<!DOCTYPE html>
<html>
<head>
<title><?php echo $Language->Phrase("MobileMenu") ?></title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" type="text/css" href="<?php echo EW_PROJECT_STYLESHEET_FILENAME ?>" />
<link rel="stylesheet" type="text/css" href="phpcss/ewmobile.css" />
<link rel="stylesheet" type="text/css" href="<?php echo ew_jQueryFile("jquery.mobile-%v.min.css") ?>" />
<script type="text/javascript" src="<?php echo ew_jQueryFile("jquery-%v.min.js") ?>"></script>
<script type="text/javascript">
	$(document).bind("mobileinit", function() {
		jQuery.mobile.ajaxEnabled = false;
		jQuery.mobile.ignoreContentEnabled = true;
	});
</script>
<script type="text/javascript" src="<?php echo ew_jQueryFile("jquery.mobile-%v.min.js") ?>"></script>
<meta name="generator" content="PHPMaker v9.0.4" />
</head>
<body>
<div data-role="page">
	<div data-role="header">
		<h1><?php echo $Language->ProjectPhrase("BodyTitle") ?></h1>
	</div>
	<div data-role="content">
<?php $RootMenu = new cMenu("RootMenu", TRUE); ?>
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(18, $Language->MenuPhrase("18", "MenuText"), "", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(31, $Language->MenuPhrase("31", "MenuText"), "mapingslist.php", 18, "", AllowListMenu('{3554BCCA-7E88-4E52-9661-DF55D75275C9}mapings'), FALSE);
$RootMenu->AddMenuItem(33, $Language->MenuPhrase("33", "MenuText"), "fb_group_entrieslist.php", 18, "", AllowListMenu('{3554BCCA-7E88-4E52-9661-DF55D75275C9}fb_group_entries'), FALSE);
$RootMenu->AddMenuItem(8, $Language->MenuPhrase("8", "MenuText"), "", 18, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(3, $Language->MenuPhrase("3", "MenuText"), "tools_translationlist.php?cmd=resetall", 8, "", AllowListMenu('{3554BCCA-7E88-4E52-9661-DF55D75275C9}tools_translation'), FALSE);
$RootMenu->AddMenuItem(9, $Language->MenuPhrase("9", "MenuText"), "tools_backupslist.php?cmd=resetall", 8, "", AllowListMenu('{3554BCCA-7E88-4E52-9661-DF55D75275C9}tools_backups'), FALSE);
$RootMenu->AddMenuItem(34, $Language->MenuPhrase("34", "MenuText"), "logslist.php", 18, "", AllowListMenu('{3554BCCA-7E88-4E52-9661-DF55D75275C9}logs'), FALSE);
$RootMenu->AddMenuItem(32, $Language->MenuPhrase("32", "MenuText"), "toolslist.php?cmd=resetall", 18, "", AllowListMenu('{3554BCCA-7E88-4E52-9661-DF55D75275C9}tools'), FALSE);
$RootMenu->AddMenuItem(1, $Language->MenuPhrase("1", "MenuText"), "domainslist.php?cmd=resetall", 18, "", AllowListMenu('{3554BCCA-7E88-4E52-9661-DF55D75275C9}domains'), FALSE);
$RootMenu->AddMenuItem(30, $Language->MenuPhrase("30", "MenuText"), "proyectoslist.php", 18, "", AllowListMenu('{3554BCCA-7E88-4E52-9661-DF55D75275C9}proyectos'), FALSE);
$RootMenu->AddMenuItem(2, $Language->MenuPhrase("2", "MenuText"), "entrieslist.php?cmd=resetall", 18, "", AllowListMenu('{3554BCCA-7E88-4E52-9661-DF55D75275C9}entries'), FALSE);
$RootMenu->AddMenuItem(29, $Language->MenuPhrase("29", "MenuText"), "", -1, "", IsLoggedIn(), FALSE);
$RootMenu->AddMenuItem(11, $Language->MenuPhrase("11", "MenuText"), "audittraillist.php", 29, "", AllowListMenu('{3554BCCA-7E88-4E52-9661-DF55D75275C9}audittrail'), FALSE);
$RootMenu->AddMenuItem(19, $Language->MenuPhrase("19", "MenuText"), "userslist.php", 29, "", AllowListMenu('{3554BCCA-7E88-4E52-9661-DF55D75275C9}users'), FALSE);
$RootMenu->AddMenuItem(20, $Language->MenuPhrase("20", "MenuText"), "userlevelpermissionslist.php", 29, "", (@$_SESSION[EW_SESSION_USER_LEVEL] & EW_ALLOW_ADMIN) == EW_ALLOW_ADMIN, FALSE);
$RootMenu->AddMenuItem(21, $Language->MenuPhrase("21", "MenuText"), "userlevelslist.php", 29, "", (@$_SESSION[EW_SESSION_USER_LEVEL] & EW_ALLOW_ADMIN) == EW_ALLOW_ADMIN, FALSE);

//$RootMenu->AddMenuItem(-1, $Language->Phrase("Logout"), "logout.php", -1, "", IsLoggedIn());
$RootMenu->AddMenuItem(-1, $Language->Phrase("Login"), "login.php", -1, "", !IsLoggedIn() && substr(@$_SERVER["URL"], -1 * strlen("login.php")) <> "login.php");
$RootMenu->Render();
?>
	</div><!-- /content -->
</div><!-- /page -->
</body>
</html>
<?php

	 // Close connection
	$conn->Close();
?>
