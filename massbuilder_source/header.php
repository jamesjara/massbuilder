<?php

//obtiene el subdominio si no existe, toma los datos por default.
$domain = array_shift(explode(".",$_SERVER['HTTP_HOST']));
if( file_exists( sprintf ('clients/%s.a',$domain) ) ){
	define("conf_domain",  $domain );
	define("conf_css",    sprintf ('clients/%s.c',$domain)  );
	define("conf_a",     sprintf ('clients/%s.a',$domain)  );
} else {
	define("conf_domain", 'default' );
	define("conf_css",    'clients/default.c' );
	define("conf_a",      'clients/default.a' );
	if(!file_exists( sprintf ('clients/%s.a', conf_domain ) ))die("error app");
}

//obtener brand css
$CSS_CUSTOM = file_get_contents( conf_css );
$ini_array 	= parse_ini_file(  conf_a  );

//obtener brand title
define("conf_brand", 	$ini_array['brand'] );

//obtener brand logo
define("conf_logo", 	$ini_array['logo'] );

//obtener maximo de fondos
define("conf_max", 		$ini_array['max'] );
define("EW_CONN_USER", 	$ini_array['u']     );
define("EW_CONN_DB", 	$ini_array['d']     );

//conf smtp
define("conf_max", 		$ini_array['max'] );
define("EW_CONN_USER", 	$ini_array['u']     );
define("EW_CONN_DB", 	$ini_array['d']     );

//API Key - see http://admin.mailchimp.com/account/api
define("conf_apikey", 		$ini_array['apikey'] );

// A List Id to run examples against. use lists() to view all
// Also, login to MC account, go to List, then List Tools, and look for the List ID entry

define("conf_listId", 		$ini_array['listId'] );

// A Campaign Id to run examples against. use campaigns() to view all
define("conf_campaignId", 	$ini_array['campaignId'] );
define("conf_apiUrl", 		$ini_array['apiUrl'] );

// Compatibility with PHP Report Maker
if (!isset($Language)) {
	include_once "ewcfg9.php";
	include_once "ewshared9.php";
	$Language = new cLanguage();
}
?>
<!doctype html>
<html>
<head>
<script type="text/javascript" src="<?php echo ew_jQueryFile("jquery-%v.min.js") ?>"></script>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?php echo conf_brand;//$Language->ProjectPhrase("BodyTitle"); ?></title>
<link rel="stylesheet" type="text/css" href="<?php echo ew_YuiHost() ?>build/button/assets/skins/sam/button.css" />
<link rel="stylesheet" type="text/css" href="<?php echo ew_YuiHost() ?>build/container/assets/skins/sam/container.css" />
<?php if (ew_IsMobile()) { ?>
<link rel="stylesheet" type="text/css" href="<?php echo ew_jQueryFile("jquery.mobile-%v.min.css") ?>" />
<script type="text/javascript">
jQuery(document).bind("mobileinit", function() {
	jQuery.mobile.ajaxEnabled = false;
	jQuery.mobile.ignoreContentEnabled = true;
});
</script>
<script type="text/javascript" src="<?php echo ew_jQueryFile("jquery.mobile-%v.min.js") ?>"></script>
<?php } ?>
<script type="text/javascript" src="<?php echo ew_YuiHost() ?>build/utilities/utilities.js"></script>
<script type="text/javascript" src="<?php echo ew_YuiHost() ?>build/json/json-min.js"></script>
<script type="text/javascript" src="<?php echo ew_YuiHost() ?>build/tabview/tabview-min.js"></script>
<script type="text/javascript" src="<?php echo ew_YuiHost() ?>build/button/button-min.js"></script>
<script type="text/javascript" src="<?php echo ew_YuiHost() ?>build/container/container-min.js"></script>
<script type="text/javascript" src="<?php echo ew_YuiHost() ?>build/datasource/datasource-min.js"></script>
<script type="text/javascript" src="<?php echo ew_YuiHost() ?>build/autocomplete/autocomplete-min.js"></script>
<link href="calendar/calendar-win2k-cold-1.css" rel="stylesheet" type="text/css" media="all" title="win2k-1">
<style type="text/css">.ewCalendar {cursor: pointer; cursor: hand;}</style>
<script type="text/javascript" src="calendar/calendar.js"></script>
<script type="text/javascript" src="calendar/lang/calendar-en.js"></script>
<script type="text/javascript" src="calendar/calendar-setup.js"></script>
<script type="text/javascript">

// Create calendar
function ew_CreateCalendar(formid, id, format) {
	if (id.indexOf("$rowindex$") > -1)
		return;
	Calendar.setup({
		inputField: ew_GetElement(id, formid), // input field
		showsTime: / %H:%M:%S$/.test(format), // shows time
		ifFormat: format, // date format
		button: ew_ConcatId(formid, id) // button ID
	});
}

// Custom event
var ewSelectDateEvent = new YAHOO.util.CustomEvent("SelectDate");
</script>
<script type="text/javascript" src="ckeditor/ckeditor.js"></script>
<script type="text/javascript">

// update value from editor to textarea
function ew_UpdateTextArea() {
	if (typeof CKEDITOR == "undefined")	
		return;
	for (var inst in CKEDITOR.instances)
		CKEDITOR.instances[inst].updateElement();
}

// update value from textarea to editor
function ew_UpdateEditor(name) {
	if (typeof CKEDITOR == "undefined")
		return;
	var inst = CKEDITOR.instances[name];		
	if (inst)
		inst.setData(inst.element.value);
}

// focus editor
function ew_FocusEditor(name) {
	if (typeof CKEDITOR == "undefined")
		return;
	var inst = CKEDITOR.instances[name];	
	if (inst)
		inst.focus();
}

// create editor
function ew_CreateEditor(formid, name, cols, rows, readonly) {
	if (typeof CKEDITOR == "undefined" || name.indexOf("$rowindex$") > -1)
		return;
	var form = document.getElementById(formid);	
	var el = ew_GetElement(name, form);
	if (!el)
		return;
	var args = {"id": name, "form": form, "enabled": true};
	ewCreateEditorEvent.fire(args);
	if (!args.enabled)
		return;
	if (cols <= 0)
		cols = 35;
	if (rows <= 0)
		rows = 4;
	var w = cols * 20; // width multiplier
	var h = rows * 60; // height multiplier
	if (readonly) {
		new ew_ReadOnlyTextArea(el, w, h);
	} else {
		name = ew_ConcatId(formid, name);
		ewForms[formid].Editors.push(new ew_Editor(name, function() {
			var inst = CKEDITOR.replace(el, { width: w, height: h,
				autoUpdateElement: false,
				baseHref: 'ckeditor/'})
			CKEDITOR.instances[name] = inst;
			delete CKEDITOR.instances[el.id];
			this.active = true;
		}));
	}
}
</script>
<script type="text/javascript">
var EW_LANGUAGE_ID = "<?php echo $gsLanguage ?>";
var EW_DATE_SEPARATOR = "/" || "/"; // Default date separator
var EW_DECIMAL_POINT = "<?php echo $DEFAULT_DECIMAL_POINT ?>";
var EW_THOUSANDS_SEP = "<?php echo $DEFAULT_THOUSANDS_SEP ?>";
var EW_UPLOAD_ALLOWED_FILE_EXT = "gif,jpg,jpeg,bmp,png,doc,xls,pdf,zip"; // Allowed upload file extension

// Ajax settings
var EW_RECORD_DELIMITER = "\r";
var EW_FIELD_DELIMITER = "|";
var EW_LOOKUP_FILE_NAME = "ewlookup9.php"; // Lookup file name
var EW_AUTO_SUGGEST_MAX_ENTRIES = <?php echo EW_AUTO_SUGGEST_MAX_ENTRIES ?>; // Auto-Suggest max entries

// Common JavaScript messages
var EW_ADDOPT_BUTTON_SUBMIT_TEXT = "<?php echo ew_JsEncode2(ew_BtnCaption($Language->Phrase("AddBtn"))) ?>";
var EW_EMAIL_EXPORT_BUTTON_SUBMIT_TEXT = "<?php echo ew_JsEncode2(ew_BtnCaption($Language->Phrase("SendEmailBtn"))) ?>";
var EW_BUTTON_CANCEL_TEXT = "<?php echo ew_JsEncode2(ew_BtnCaption($Language->Phrase("CancelBtn"))) ?>";
var EW_DISABLE_BUTTON_ON_SUBMIT = true;
var EW_IMAGE_FOLDER = "phpimages/"; // Image folder
</script>
<script type="text/javascript" src="phpjs/jsrender.js"></script>
<script type="text/javascript" src="phpjs/ewp9.js"></script>
<script type="text/javascript" src="phpjs/userfn9.js"></script>
<script type="text/javascript">
<?php echo $Language->ToJSON() ?>
</script>
<script type="text/javascript">
$('a.operacion').live('click', function() {  
	var tipo    =  this.name    ;    
	var id      =  this.id      ; 
	$('<div class="modal hide fade"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button></div> <div class="modal-body">  <div id="status"> ... Espere, mientras se realiza la operacion ... </div><div id="msg"></div></div><div class="modal-footer"><a href="#" class="btn close">Continue</a></div>   </div>').modal();
	$.ajax({                                                                                                            
			url: 'operaciones.php',                                   
			data: { tipo : tipo , id: id },            
			success: function(msg) { 
			   $("[id=status]").html( 'DONE<BR>' );   
			   $("[id=msg]").html( msg );   

			   //Unblock all           
			},                                        
			failure: function() {
			   $("[id=status]").html( 'Error<BR>' ); 

			   //Unblock all   
			}                                                          
	});                                   
	return false;
});
</script>
<meta charset="utf-8">
<?php echo $CSS_CUSTOM; ?>
<link href="more/calendar/css/no-theme/jquery-ui-1.9.2.custom.css" rel="stylesheet">
<script src="more/calendar/js/jquery-ui-1.9.2.custom.js"></script>
<script src="bootstrap/js/bootstrap.js"></script>
<link href="bootstrap/css/bootstrap.css" rel="stylesheet" media="screen">
<style>body { padding-top: 60px; } @media screen and (max-width: 768px) {    body { padding-top: 0px; }} .ewTemplate {	display: none;} .ewMenuColumn {background-color: #F1F1F1;color: ;width: 180px;vertical-align: top;padding: 1px;display: none;} .ewFooterText {font-family: Verdana;font-size: x-small;display: none;} .ewGridUpperPanel {margin-top: 5px;margin-bottom: 4px;} .ewGridMiddlePanel{margin-top: 4px;} #xsr_1{padding-bottom: 10px;text-align: center;} #paginador{margin-top: 1px;margin-bottom: 2px;text-align: right;} .ewMessageDialog {margin-top: 20px;} .ewTableHeader .btn .ewTableHeaderBtn{text-transform:capitalize;} .ewTableHeader{ text-transform: capitalize; } .btn { text-transform: capitalize; } .ewTableHeaderBtn{ text-transform: capitalize; } .ewTable{margin-top: 10px;} .navbar .brand {padding: 0px;} .ewListOptionBody2{text-align: center;text-transform: capitalize;} .ewLayout{background-image: url('uploads/tx1.png');} em {font-weight: bold;}
<?php if (!$_GET['export']){echo 'body {background: url("uploads/'.conf_domain.'/bk'.rand(0,conf_max).'.png") no-repeat fixed right bottom #FBFBFB;}';} ?>
</style>
<link rel="stylesheet" href="more/c/c.css" />
<script src="more/c/c.js" type="text/javascript"></script>   
<script src="more/d/ckeditor.js" type="text/javascript"></script>   
<meta name="generator" content="PHPMaker v9.0.4" />
</head>
<body class="container yui-skin-sam">
<?php if (  !ISSET($_GET['export'])   ) { ?>
			<!-- left column (begin) -->
<?php include_once "ewmenu.php" ?>
			<!-- left column (end) -->
<?php } ?>
<?php if (ew_IsMobile()) { ?>
<div data-role="page">
	<div data-role="header">
		<a href="mobilemenu.php"><?php echo $Language->Phrase("MobileMenu") ?></a>
		<h1 id="ewPageTitle"> </h1>
	<?php if (IsLoggedIn()) { ?>
		<a href="logout.php"><?php echo $Language->Phrase("Logout") ?></a>
	<?php } elseif (substr(ew_ScriptName(), 0 - strlen("login.php")) <> "login.php") { ?>
		<a href="login.php"><?php echo $Language->Phrase("Login") ?></a>
	<?php } ?>
	</div>
<?php } ?>
<?php if (@!$gbSkipHeaderFooter) { ?>
<div class="ewLayout">
<?php if (!ew_IsMobile()) { ?>
	<!-- header (begin) --><!-- *** Note: Only licensed users are allowed to change the logo *** -->
 <!-- <div class="ewHeaderRow"><img src="phpimages/phpmkrlogo9.png" alt="" border="0" /></div>-->
	<!-- header (end) -->
<?php } ?>
<?php if (ew_IsMobile()) { ?>
	<div data-role="content" data-enhance="false">
	<table class="ewContentTable">
		<tr>
<?php } else { ?>
	<!-- content (begin)
	<table cellspacing="0" class="ewContentTable">
		<tr>	
			<td class="ewMenuColumn">
			</td> -->
<?php } ?>
	  <!--  <td class="ewContentColumn">
			 right column (begin) -->
			<!--	<p><span class="ewSiteTitle"> <?php echo $Language->ProjectPhrase("BodyTitle") ?></span></p>-->
<?php } ?>
