<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg9.php" ?>
<?php include_once "ewmysql9.php" ?>
<?php include_once "phpfn9.php" ?>
<?php include_once "fb_group_entriesinfo.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "userfn9.php" ?>
<?php

//
// Page class
//

$fb_group_entries_view = NULL; // Initialize page object first

class cfb_group_entries_view extends cfb_group_entries {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{3554BCCA-7E88-4E52-9661-DF55D75275C9}";

	// Table name
	var $TableName = 'fb_group_entries';

	// Page object name
	var $PageObjName = 'fb_group_entries_view';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
		if ($this->UseTokenInUrl) $PageUrl .= "t=" . $this->TableVar . "&"; // Add page token
		return $PageUrl;
	}

	// Page URLs
	var $AddUrl;
	var $EditUrl;
	var $CopyUrl;
	var $DeleteUrl;
	var $ViewUrl;
	var $ListUrl;

	// Export URLs
	var $ExportPrintUrl;
	var $ExportHtmlUrl;
	var $ExportExcelUrl;
	var $ExportWordUrl;
	var $ExportXmlUrl;
	var $ExportCsvUrl;
	var $ExportPdfUrl;

	// Update URLs
	var $InlineAddUrl;
	var $InlineCopyUrl;
	var $InlineEditUrl;
	var $GridAddUrl;
	var $GridEditUrl;
	var $MultiDeleteUrl;
	var $MultiUpdateUrl;

	// Message
	function getMessage() {
		return @$_SESSION[EW_SESSION_MESSAGE];
	}

	function setMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_MESSAGE], $v);
	}

	function getFailureMessage() {
		return @$_SESSION[EW_SESSION_FAILURE_MESSAGE];
	}

	function setFailureMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_FAILURE_MESSAGE], $v);
	}

	function getSuccessMessage() {
		return @$_SESSION[EW_SESSION_SUCCESS_MESSAGE];
	}

	function setSuccessMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_SUCCESS_MESSAGE], $v);
	}

	function getWarningMessage() {
		return @$_SESSION[EW_SESSION_WARNING_MESSAGE];
	}

	function setWarningMessage($v) {
		ew_AddMessage($_SESSION[EW_SESSION_WARNING_MESSAGE], $v);
	}

	// Show message
	function ShowMessage() {
		$hidden = FALSE;
		$html = "";

		// Message
		$sMessage = $this->getMessage();
		$this->Message_Showing($sMessage, "");
		if ($sMessage <> "") { // Message in Session, display
			$html .= "<p class=\"ewMessage\">" . $sMessage . "</p>";
			$_SESSION[EW_SESSION_MESSAGE] = ""; // Clear message in Session
		}

		// Warning message
		$sWarningMessage = $this->getWarningMessage();
		$this->Message_Showing($sWarningMessage, "warning");
		if ($sWarningMessage <> "") { // Message in Session, display

			///$html .= "<table class=\"ewMessageTable\"><tr><td class=\"ewWarningIcon\"></td><td class=\"ewWarningMessage\">" . $sWarningMessage . "</td></tr></table>";
			//$html .= "<div class=\"ewWarningMessage\">" . $sWarningMessage . "</div>";

			$html .= '<div class="alert alert-info alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button><strong> Advertencia!</strong> ' . $sWarningMessage . '</div>';
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display

			//$html .= "<table class=\"ewMessageTable\"><tr><td class=\"ewSuccessIcon\"></td><td class=\"ewSuccessMessage\">" . $sSuccessMessage . "</td></tr></table>";
			$html .= '<div class="alert alert-success alert-block"><button type="button" class="close" data-dismiss="alert">&times;</button><strong> Felicidades!</strong> ' . $sSuccessMessage . '</div>';
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display

			//$html .= "<table class=\"ewMessageTable\"><tr><td class=\"ewErrorIcon\"></td><td class=\"ewErrorMessage\">" . $sErrorMessage . "</td></tr></table>";
			$html .= '<div class="alert  alert-error"><button type="button" class="close" data-dismiss="alert">&times;</button><strong> Error!</strong> ' . $sErrorMessage . '</div>';
			$_SESSION[EW_SESSION_FAILURE_MESSAGE] = ""; // Clear message in Session
		}
		echo "<div class=\"ewMessageDialog\"" . (($hidden) ? " style=\"display: none;\"" : "") . ">" . $html . "</div>";
	}
	var $PageHeader;
	var $PageFooter;

	// Show Page Header
	function ShowPageHeader() {
		$sHeader = $this->PageHeader;
		$this->Page_DataRendering($sHeader);
		if ($sHeader <> "") { // Header exists, display
			echo "<p class=\"phpmaker\">" . $sHeader . "</p>";
		}
	}

	// Show Page Footer
	function ShowPageFooter() {
		$sFooter = $this->PageFooter;
		$this->Page_DataRendered($sFooter);
		if ($sFooter <> "") { // Fotoer exists, display
			echo "<p class=\"phpmaker\">" . $sFooter . "</p>";
		}
	}

	// Validate page request
	function IsPageRequest() {
		global $objForm;
		if ($this->UseTokenInUrl) {
			if ($objForm)
				return ($this->TableVar == $objForm->GetValue("t"));
			if (@$_GET["t"] <> "")
				return ($this->TableVar == $_GET["t"]);
		} else {
			return TRUE;
		}
	}

	//
	// Page class constructor
	//
	function __construct() {
		global $conn, $Language, $UserAgent;

		// User agent
		$UserAgent = ew_UserAgent();
		$GLOBALS["Page"] = &$this;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();

		// Parent constuctor
		parent::__construct();

		// Table object (fb_group_entries)
		if (!isset($GLOBALS["fb_group_entries"])) {
			$GLOBALS["fb_group_entries"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["fb_group_entries"];
		}
		$KeyUrl = "";
		if (@$_GET["idfb_posts"] <> "") {
			$this->RecKey["idfb_posts"] = $_GET["idfb_posts"];
			$KeyUrl .= "&idfb_posts=" . urlencode($this->RecKey["idfb_posts"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'fb_group_entries', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "li";//jamesjara
		$this->ExportOptions->TagClassName = "ewExportOptionIgnore";
	}

			function james_url( $PageName ){
	$PageName_O = $PageName ;	$parts = parse_url($PageName);$PageName = $parts['path'];

	//todo, hot fixes too view urls	
	$PageName = str_ireplace( 'view1' , 'xxx1', $PageName);
	$PageName = str_ireplace( 'view2' , 'xxx2', $PageName);
	$PageName = str_ireplace( 'view3' , 'xxx3', $PageName);	

	//jamesjara , funciona en FORM Y EXPORTS
	$buscar = array('list','add','view','delete','info','edit','.php');	
	$pagina = str_ireplace( $buscar , '', $PageName, $encontrado);
	if( $encontrado > 0 ){

		//obtener la accion
		$buscar = array( $pagina , '.php');
		$accion = str_ireplace( $buscar , '', $PageName);	

		//hot fixed
		$pagina = str_ireplace( 'xxx1' , 'view1', $pagina);
		$pagina = str_ireplace( 'xxx2' , 'view2', $pagina);
		$pagina = str_ireplace( 'xxx3' , 'view3', $pagina);	
		return  $pagina.'-'.$accion ;
	}
	return $PageName_O;
}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// User profile
		$UserProfile = new cUserProfile();
		$UserProfile->LoadProfile(@$_SESSION[EW_SESSION_USER_PROFILE]);

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if (!$Security->IsLoggedIn()) {
			$Security->SaveLastUrl();
			$this->Page_Terminate("login.php");
		}
		$Security->TablePermission_Loading();
		$Security->LoadCurrentUserLevel($this->ProjectID . $this->TableName);
		$Security->TablePermission_Loaded();
		if (!$Security->IsLoggedIn()) {
			$Security->SaveLastUrl();
			$this->Page_Terminate("login.php");
		}
		if (!$Security->CanView()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate("fb_group_entrieslist.php");
		}
		$Security->UserID_Loading();
		if ($Security->IsLoggedIn()) $Security->LoadUserID();
		$Security->UserID_Loaded();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"];
		$this->idfb_posts->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

		// Global Page Loading event (in userfn*.php)
		Page_Loading();

		// Page Load event
		$this->Page_Load();
	}

	//
	// Page_Terminate
	//
	function Page_Terminate($url = "") {
		global $conn;

		// Page Unload event
		$this->Page_Unload();

		// Global Page Unloaded event (in userfn*.php)
		Page_Unloaded();
		$this->Page_Redirecting($url);

		 // Close connection
		$conn->Close();

		// Go to URL if specified
		if ($url <> "") {
			if (!EW_DEBUG_ENABLED && ob_get_length())
				ob_end_clean();
			header("Location: " . $url);
		}
		exit();
	}
	var $ExportOptions; // Export options
	var $DisplayRecs = 1;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $RecCnt;
	var $RecKey = array();
	var $Recordset;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;
		$sReturnUrl = "";
		$bMatchRecord = FALSE;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET["idfb_posts"] <> "") {
				$this->idfb_posts->setQueryStringValue($_GET["idfb_posts"]);
				$this->RecKey["idfb_posts"] = $this->idfb_posts->QueryStringValue;
			} else {
				$sReturnUrl = $this->james_url( "fb_group_entrieslist.php" ); // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = $this->james_url( "fb_group_entrieslist.php" ); // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = $this->james_url( "fb_group_entrieslist.php" ); // Not page request, return to list
		}
		if ($sReturnUrl <> "")
			$this->Page_Terminate($sReturnUrl);

		// Render row
		$this->RowType = EW_ROWTYPE_VIEW;
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Set up starting record parameters
	function SetUpStartRec() {
		if ($this->DisplayRecs == 0)
			return;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET[EW_TABLE_START_REC] <> "") { // Check for "start" parameter
				$this->StartRec = $_GET[EW_TABLE_START_REC];
				$this->setStartRecordNumber($this->StartRec);
			} elseif (@$_GET[EW_TABLE_PAGE_NO] <> "") {
				$PageNo = $_GET[EW_TABLE_PAGE_NO];
				if (is_numeric($PageNo)) {
					$this->StartRec = ($PageNo-1)*$this->DisplayRecs+1;
					if ($this->StartRec <= 0) {
						$this->StartRec = 1;
					} elseif ($this->StartRec >= intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1) {
						$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1;
					}
					$this->setStartRecordNumber($this->StartRec);
				}
			}
		}
		$this->StartRec = $this->getStartRecordNumber();

		// Check if correct start record counter
		if (!is_numeric($this->StartRec) || $this->StartRec == "") { // Avoid invalid start record counter
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} elseif (intval($this->StartRec) > intval($this->TotalRecs)) { // Avoid starting record > total records
			$this->StartRec = intval(($this->TotalRecs-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to last page first record
			$this->setStartRecordNumber($this->StartRec);
		} elseif (($this->StartRec-1) % $this->DisplayRecs <> 0) {
			$this->StartRec = intval(($this->StartRec-1)/$this->DisplayRecs)*$this->DisplayRecs+1; // Point to page boundary
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Load row based on key values
	function LoadRow() {
		global $conn, $Security, $Language;
		$sFilter = $this->KeyFilter();

		// Call Row Selecting event
		$this->Row_Selecting($sFilter);

		// Load SQL based on filter
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$res = FALSE;
		$rs = ew_LoadRecordset($sSql);
		if ($rs && !$rs->EOF) {
			$res = TRUE;
			$this->LoadRowValues($rs); // Load row values
			$rs->Close();
		}
		return $res;
	}

	// Load row values from recordset
	function LoadRowValues(&$rs) {
		global $conn;
		if (!$rs || $rs->EOF) return;

		// Call Row Selected event
		$row = &$rs->fields;
		$this->Row_Selected($row);
		$this->idfb_posts->setDbValue($rs->fields('idfb_posts'));
		$this->domain_id->setDbValue($rs->fields('domain_id'));
		$this->record_time->setDbValue($rs->fields('record_time'));
		$this->data->setDbValue($rs->fields('data'));
		$this->fid->setDbValue($rs->fields('fid'));
		$this->md5->setDbValue($rs->fields('md5'));
		$this->body->setDbValue($rs->fields('body'));
		$this->titulo->setDbValue($rs->fields('titulo'));
		$this->created_time->setDbValue($rs->fields('created_time'));
		$this->actions->setDbValue($rs->fields('actions'));
		$this->id->setDbValue($rs->fields('id'));
		$this->is_published->setDbValue($rs->fields('is_published'));
		$this->message->setDbValue($rs->fields('message'));
		$this->privacy->setDbValue($rs->fields('privacy'));
		$this->promotion_status->setDbValue($rs->fields('promotion_status'));
		$this->timeline_visibility->setDbValue($rs->fields('timeline_visibility'));
		$this->to->setDbValue($rs->fields('to'));
		$this->type->setDbValue($rs->fields('type'));
		$this->updated_time->setDbValue($rs->fields('updated_time'));
		$this->from->setDbValue($rs->fields('from'));
		$this->comments->setDbValue($rs->fields('comments'));
		$this->id_grupo->setDbValue($rs->fields('id_grupo'));
		$this->icon->setDbValue($rs->fields('icon'));
		$this->link->setDbValue($rs->fields('link'));
		$this->name->setDbValue($rs->fields('name'));
		$this->object_id->setDbValue($rs->fields('object_id'));
		$this->picture->setDbValue($rs->fields('picture'));
		$this->properties->setDbValue($rs->fields('properties'));
		$this->message_tags->setDbValue($rs->fields('message_tags'));
		$this->caption->setDbValue($rs->fields('caption'));
		$this->description->setDbValue($rs->fields('description'));
		$this->tool_id->setDbValue($rs->fields('tool_id'));
		$this->application->setDbValue($rs->fields('application'));
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		$this->AddUrl = $this->GetAddUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();
		$this->ListUrl = $this->GetListUrl();

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// idfb_posts
		// domain_id
		// record_time
		// data
		// fid
		// md5
		// body
		// titulo
		// created_time
		// actions
		// id
		// is_published
		// message
		// privacy
		// promotion_status
		// timeline_visibility
		// to
		// type
		// updated_time
		// from
		// comments
		// id_grupo
		// icon
		// link
		// name
		// object_id
		// picture
		// properties
		// message_tags
		// caption
		// description
		// tool_id
		// application

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// idfb_posts
			$this->idfb_posts->ViewValue = $this->idfb_posts->CurrentValue;
			$this->idfb_posts->ViewCustomAttributes = "";

			// domain_id
			$this->domain_id->ViewValue = $this->domain_id->CurrentValue;
			$this->domain_id->ViewCustomAttributes = "";

			// record_time
			$this->record_time->ViewValue = $this->record_time->CurrentValue;
			$this->record_time->ViewValue = ew_FormatDateTime($this->record_time->ViewValue, 5);
			$this->record_time->ViewCustomAttributes = "";

			// data
			$this->data->ViewValue = $this->data->CurrentValue;
			$this->data->ViewCustomAttributes = "";

			// fid
			$this->fid->ViewValue = $this->fid->CurrentValue;
			$this->fid->ViewCustomAttributes = "";

			// md5
			$this->md5->ViewValue = $this->md5->CurrentValue;
			$this->md5->ViewCustomAttributes = "";

			// body
			$this->body->ViewValue = $this->body->CurrentValue;
			$this->body->ViewCustomAttributes = "";

			// titulo
			$this->titulo->ViewValue = $this->titulo->CurrentValue;
			$this->titulo->ViewCustomAttributes = "";

			// created_time
			$this->created_time->ViewValue = $this->created_time->CurrentValue;
			$this->created_time->ViewCustomAttributes = "";

			// actions
			$this->actions->ViewValue = $this->actions->CurrentValue;
			$this->actions->ViewCustomAttributes = "";

			// id
			$this->id->ViewValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// is_published
			$this->is_published->ViewValue = $this->is_published->CurrentValue;
			$this->is_published->ViewCustomAttributes = "";

			// message
			$this->message->ViewValue = $this->message->CurrentValue;
			$this->message->ViewCustomAttributes = "";

			// privacy
			$this->privacy->ViewValue = $this->privacy->CurrentValue;
			$this->privacy->ViewCustomAttributes = "";

			// promotion_status
			$this->promotion_status->ViewValue = $this->promotion_status->CurrentValue;
			$this->promotion_status->ViewCustomAttributes = "";

			// timeline_visibility
			$this->timeline_visibility->ViewValue = $this->timeline_visibility->CurrentValue;
			$this->timeline_visibility->ViewCustomAttributes = "";

			// to
			$this->to->ViewValue = $this->to->CurrentValue;
			$this->to->ViewCustomAttributes = "";

			// type
			$this->type->ViewValue = $this->type->CurrentValue;
			$this->type->ViewCustomAttributes = "";

			// updated_time
			$this->updated_time->ViewValue = $this->updated_time->CurrentValue;
			$this->updated_time->ViewCustomAttributes = "";

			// from
			$this->from->ViewValue = $this->from->CurrentValue;
			$this->from->ViewCustomAttributes = "";

			// comments
			$this->comments->ViewValue = $this->comments->CurrentValue;
			$this->comments->ViewCustomAttributes = "";

			// id_grupo
			$this->id_grupo->ViewValue = $this->id_grupo->CurrentValue;
			$this->id_grupo->ViewCustomAttributes = "";

			// icon
			$this->icon->ViewValue = $this->icon->CurrentValue;
			$this->icon->ViewCustomAttributes = "";

			// link
			$this->link->ViewValue = $this->link->CurrentValue;
			$this->link->ViewCustomAttributes = "";

			// name
			$this->name->ViewValue = $this->name->CurrentValue;
			$this->name->ViewCustomAttributes = "";

			// object_id
			$this->object_id->ViewValue = $this->object_id->CurrentValue;
			$this->object_id->ViewCustomAttributes = "";

			// picture
			$this->picture->ViewValue = $this->picture->CurrentValue;
			$this->picture->ViewCustomAttributes = "";

			// properties
			$this->properties->ViewValue = $this->properties->CurrentValue;
			$this->properties->ViewCustomAttributes = "";

			// message_tags
			$this->message_tags->ViewValue = $this->message_tags->CurrentValue;
			$this->message_tags->ViewCustomAttributes = "";

			// caption
			$this->caption->ViewValue = $this->caption->CurrentValue;
			$this->caption->ViewCustomAttributes = "";

			// description
			$this->description->ViewValue = $this->description->CurrentValue;
			$this->description->ViewCustomAttributes = "";

			// tool_id
			$this->tool_id->ViewValue = $this->tool_id->CurrentValue;
			$this->tool_id->ViewCustomAttributes = "";

			// application
			$this->application->ViewValue = $this->application->CurrentValue;
			$this->application->ViewCustomAttributes = "";

			// idfb_posts
			$this->idfb_posts->LinkCustomAttributes = "";
			$this->idfb_posts->HrefValue = "";
			$this->idfb_posts->TooltipValue = "";

			// domain_id
			$this->domain_id->LinkCustomAttributes = "";
			$this->domain_id->HrefValue = "";
			$this->domain_id->TooltipValue = "";

			// record_time
			$this->record_time->LinkCustomAttributes = "";
			$this->record_time->HrefValue = "";
			$this->record_time->TooltipValue = "";

			// data
			$this->data->LinkCustomAttributes = "";
			$this->data->HrefValue = "";
			$this->data->TooltipValue = "";

			// fid
			$this->fid->LinkCustomAttributes = "";
			$this->fid->HrefValue = "";
			$this->fid->TooltipValue = "";

			// md5
			$this->md5->LinkCustomAttributes = "";
			$this->md5->HrefValue = "";
			$this->md5->TooltipValue = "";

			// body
			$this->body->LinkCustomAttributes = "";
			$this->body->HrefValue = "";
			$this->body->TooltipValue = "";

			// titulo
			$this->titulo->LinkCustomAttributes = "";
			$this->titulo->HrefValue = "";
			$this->titulo->TooltipValue = "";

			// created_time
			$this->created_time->LinkCustomAttributes = "";
			$this->created_time->HrefValue = "";
			$this->created_time->TooltipValue = "";

			// actions
			$this->actions->LinkCustomAttributes = "";
			$this->actions->HrefValue = "";
			$this->actions->TooltipValue = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// is_published
			$this->is_published->LinkCustomAttributes = "";
			$this->is_published->HrefValue = "";
			$this->is_published->TooltipValue = "";

			// message
			$this->message->LinkCustomAttributes = "";
			$this->message->HrefValue = "";
			$this->message->TooltipValue = "";

			// privacy
			$this->privacy->LinkCustomAttributes = "";
			$this->privacy->HrefValue = "";
			$this->privacy->TooltipValue = "";

			// promotion_status
			$this->promotion_status->LinkCustomAttributes = "";
			$this->promotion_status->HrefValue = "";
			$this->promotion_status->TooltipValue = "";

			// timeline_visibility
			$this->timeline_visibility->LinkCustomAttributes = "";
			$this->timeline_visibility->HrefValue = "";
			$this->timeline_visibility->TooltipValue = "";

			// to
			$this->to->LinkCustomAttributes = "";
			$this->to->HrefValue = "";
			$this->to->TooltipValue = "";

			// type
			$this->type->LinkCustomAttributes = "";
			$this->type->HrefValue = "";
			$this->type->TooltipValue = "";

			// updated_time
			$this->updated_time->LinkCustomAttributes = "";
			$this->updated_time->HrefValue = "";
			$this->updated_time->TooltipValue = "";

			// from
			$this->from->LinkCustomAttributes = "";
			$this->from->HrefValue = "";
			$this->from->TooltipValue = "";

			// comments
			$this->comments->LinkCustomAttributes = "";
			$this->comments->HrefValue = "";
			$this->comments->TooltipValue = "";

			// id_grupo
			$this->id_grupo->LinkCustomAttributes = "";
			$this->id_grupo->HrefValue = "";
			$this->id_grupo->TooltipValue = "";

			// icon
			$this->icon->LinkCustomAttributes = "";
			$this->icon->HrefValue = "";
			$this->icon->TooltipValue = "";

			// link
			$this->link->LinkCustomAttributes = "";
			$this->link->HrefValue = "";
			$this->link->TooltipValue = "";

			// name
			$this->name->LinkCustomAttributes = "";
			$this->name->HrefValue = "";
			$this->name->TooltipValue = "";

			// object_id
			$this->object_id->LinkCustomAttributes = "";
			$this->object_id->HrefValue = "";
			$this->object_id->TooltipValue = "";

			// picture
			$this->picture->LinkCustomAttributes = "";
			$this->picture->HrefValue = "";
			$this->picture->TooltipValue = "";

			// properties
			$this->properties->LinkCustomAttributes = "";
			$this->properties->HrefValue = "";
			$this->properties->TooltipValue = "";

			// message_tags
			$this->message_tags->LinkCustomAttributes = "";
			$this->message_tags->HrefValue = "";
			$this->message_tags->TooltipValue = "";

			// caption
			$this->caption->LinkCustomAttributes = "";
			$this->caption->HrefValue = "";
			$this->caption->TooltipValue = "";

			// description
			$this->description->LinkCustomAttributes = "";
			$this->description->HrefValue = "";
			$this->description->TooltipValue = "";

			// tool_id
			$this->tool_id->LinkCustomAttributes = "";
			$this->tool_id->HrefValue = "";
			$this->tool_id->TooltipValue = "";

			// application
			$this->application->LinkCustomAttributes = "";
			$this->application->HrefValue = "";
			$this->application->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Page Load event
	function Page_Load() {

		//echo "Page Load";
	}

	// Page Unload event
	function Page_Unload() {

		//echo "Page Unload";
	}

	// Page Redirecting event
	function Page_Redirecting(&$url) {

		// Example:
		//$url = "your URL";

	}

	// Message Showing event
	// $type = ''|'success'|'failure'|'warning'
	function Message_Showing(&$msg, $type) {
		if ($type == 'success') {

			//$msg = "your success message";
		} elseif ($type == 'failure') {

			//$msg = "your failure message";
		} elseif ($type == 'warning') {

			//$msg = "your warning message";
		} else {

			//$msg = "your message";
		}
	}

	// Page Data Rendering event
	function Page_DataRendering(&$header) {

		// Example:
		//$header = "your header";

	}

	// Page Data Rendered event
	function Page_DataRendered(&$footer) {

		// Example:
		//$footer = "your footer";

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($fb_group_entries_view)) $fb_group_entries_view = new cfb_group_entries_view();

// Page init
$fb_group_entries_view->Page_Init();

// Page main
$fb_group_entries_view->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var fb_group_entries_view = new ew_Page("fb_group_entries_view");
fb_group_entries_view.PageID = "view"; // Page ID
var EW_PAGE_ID = fb_group_entries_view.PageID; // For backward compatibility

// Form object
var ffb_group_entriesview = new ew_Form("ffb_group_entriesview");

// Form_CustomValidate event
ffb_group_entriesview.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ffb_group_entriesview.ValidateRequired = true;
<?php } else { ?>
ffb_group_entriesview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<h4 id="ewPageCaption" class="page-header ewTitle ewTableTitle"> <?php echo $Language->Phrase("View") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $fb_group_entries->TableCaption() ?>&nbsp;&nbsp;</h4>
<a href="<?php echo $fb_group_entries_view->ListUrl ?>" id="a_BackToList" class="ewLink label"><i class="icon-arrow-left icon-white"></i> <?php echo $Language->Phrase("BackToList") ?></a>
<?php //jamesjara
if(!ISSET($_GET['export']))echo '<div class="btn-group"><button class="btn btn-info dropdown-toggle" data-toggle="dropdown"><i class="icon-share icon-white"></i> Exportar<span class="caret"></span></button><ul class="dropdown-menu">';
$fb_group_entries_view->ExportOptions->Render("body"); 
if(!ISSET($_GET['export']))echo '</ul></div> ';
?>
<?php if ($Security->CanAdd()) { ?>
<?php if ($fb_group_entries_view->AddUrl <> "") { ?>
<a href="<?php echo $fb_group_entries_view->AddUrl ?>" id="a_AddLink" class="ewLink ewGridLink btn btn-success"><?php echo $Language->Phrase("ViewPageAddLink") ?></a>
<?php } ?>
<?php } ?>
<?php if ($Security->CanEdit()) { ?>
<?php if ($fb_group_entries_view->EditUrl <> "") { ?>
<a href="<?php echo $fb_group_entries_view->EditUrl ?>" id="a_EditLink" class="ewLink btn btn-primary"><?php echo $Language->Phrase("ViewPageEditLink") ?></a>
<?php } ?>
<?php } ?>
<?php if ($Security->CanAdd()) { ?>
<?php if ($fb_group_entries_view->CopyUrl <> "") { ?>
<a href="<?php echo $fb_group_entries_view->CopyUrl ?>" id="a_CopyLink" class="ewLink"><?php echo $Language->Phrase("ViewPageCopyLink") ?></a>
<?php } ?>
<?php } ?>
<?php if ($Security->CanDelete()) { ?>
<?php if ($fb_group_entries_view->DeleteUrl <> "") { ?>
<a href="<?php echo $fb_group_entries_view->DeleteUrl ?>" id="a_DeleteLink" class="ewLink  btn btn-danger"><?php echo $Language->Phrase("ViewPageDeleteLink") ?></a>
<?php } ?>
<?php } ?>
<?php $fb_group_entries_view->ShowPageHeader(); ?>
<?php
$fb_group_entries_view->ShowMessage();
?>
<form name="ffb_group_entriesview" id="ffb_group_entriesview" class="ewForm" action="" method="post">
<input type="hidden" name="t" value="fb_group_entries" />
<table id="tbl_fb_group_entriesview" class="ewTable ewTableSeparate table table-striped ">
<?php if ($fb_group_entries->idfb_posts->Visible) { // idfb_posts ?>
	<tr id="r_idfb_posts"<?php echo $fb_group_entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_group_entries_idfb_posts"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $fb_group_entries->idfb_posts->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $fb_group_entries->idfb_posts->CellAttributes() ?>><span id="el_fb_group_entries_idfb_posts">
<span<?php echo $fb_group_entries->idfb_posts->ViewAttributes() ?>>
<?php echo $fb_group_entries->idfb_posts->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($fb_group_entries->domain_id->Visible) { // domain_id ?>
	<tr id="r_domain_id"<?php echo $fb_group_entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_group_entries_domain_id"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $fb_group_entries->domain_id->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $fb_group_entries->domain_id->CellAttributes() ?>><span id="el_fb_group_entries_domain_id">
<span<?php echo $fb_group_entries->domain_id->ViewAttributes() ?>>
<?php echo $fb_group_entries->domain_id->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($fb_group_entries->record_time->Visible) { // record_time ?>
	<tr id="r_record_time"<?php echo $fb_group_entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_group_entries_record_time"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $fb_group_entries->record_time->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $fb_group_entries->record_time->CellAttributes() ?>><span id="el_fb_group_entries_record_time">
<span<?php echo $fb_group_entries->record_time->ViewAttributes() ?>>
<?php echo $fb_group_entries->record_time->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($fb_group_entries->data->Visible) { // data ?>
	<tr id="r_data"<?php echo $fb_group_entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_group_entries_data"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $fb_group_entries->data->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $fb_group_entries->data->CellAttributes() ?>><span id="el_fb_group_entries_data">
<span<?php echo $fb_group_entries->data->ViewAttributes() ?>>
<?php echo $fb_group_entries->data->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($fb_group_entries->fid->Visible) { // fid ?>
	<tr id="r_fid"<?php echo $fb_group_entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_group_entries_fid"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $fb_group_entries->fid->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $fb_group_entries->fid->CellAttributes() ?>><span id="el_fb_group_entries_fid">
<span<?php echo $fb_group_entries->fid->ViewAttributes() ?>>
<?php echo $fb_group_entries->fid->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($fb_group_entries->md5->Visible) { // md5 ?>
	<tr id="r_md5"<?php echo $fb_group_entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_group_entries_md5"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $fb_group_entries->md5->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $fb_group_entries->md5->CellAttributes() ?>><span id="el_fb_group_entries_md5">
<span<?php echo $fb_group_entries->md5->ViewAttributes() ?>>
<?php echo $fb_group_entries->md5->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($fb_group_entries->body->Visible) { // body ?>
	<tr id="r_body"<?php echo $fb_group_entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_group_entries_body"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $fb_group_entries->body->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $fb_group_entries->body->CellAttributes() ?>><span id="el_fb_group_entries_body">
<span<?php echo $fb_group_entries->body->ViewAttributes() ?>>
<?php echo $fb_group_entries->body->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($fb_group_entries->titulo->Visible) { // titulo ?>
	<tr id="r_titulo"<?php echo $fb_group_entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_group_entries_titulo"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $fb_group_entries->titulo->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $fb_group_entries->titulo->CellAttributes() ?>><span id="el_fb_group_entries_titulo">
<span<?php echo $fb_group_entries->titulo->ViewAttributes() ?>>
<?php echo $fb_group_entries->titulo->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($fb_group_entries->created_time->Visible) { // created_time ?>
	<tr id="r_created_time"<?php echo $fb_group_entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_group_entries_created_time"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $fb_group_entries->created_time->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $fb_group_entries->created_time->CellAttributes() ?>><span id="el_fb_group_entries_created_time">
<span<?php echo $fb_group_entries->created_time->ViewAttributes() ?>>
<?php echo $fb_group_entries->created_time->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($fb_group_entries->actions->Visible) { // actions ?>
	<tr id="r_actions"<?php echo $fb_group_entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_group_entries_actions"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $fb_group_entries->actions->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $fb_group_entries->actions->CellAttributes() ?>><span id="el_fb_group_entries_actions">
<span<?php echo $fb_group_entries->actions->ViewAttributes() ?>>
<?php echo $fb_group_entries->actions->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($fb_group_entries->id->Visible) { // id ?>
	<tr id="r_id"<?php echo $fb_group_entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_group_entries_id"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $fb_group_entries->id->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $fb_group_entries->id->CellAttributes() ?>><span id="el_fb_group_entries_id">
<span<?php echo $fb_group_entries->id->ViewAttributes() ?>>
<?php echo $fb_group_entries->id->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($fb_group_entries->is_published->Visible) { // is_published ?>
	<tr id="r_is_published"<?php echo $fb_group_entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_group_entries_is_published"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $fb_group_entries->is_published->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $fb_group_entries->is_published->CellAttributes() ?>><span id="el_fb_group_entries_is_published">
<span<?php echo $fb_group_entries->is_published->ViewAttributes() ?>>
<?php echo $fb_group_entries->is_published->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($fb_group_entries->message->Visible) { // message ?>
	<tr id="r_message"<?php echo $fb_group_entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_group_entries_message"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $fb_group_entries->message->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $fb_group_entries->message->CellAttributes() ?>><span id="el_fb_group_entries_message">
<span<?php echo $fb_group_entries->message->ViewAttributes() ?>>
<?php echo $fb_group_entries->message->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($fb_group_entries->privacy->Visible) { // privacy ?>
	<tr id="r_privacy"<?php echo $fb_group_entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_group_entries_privacy"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $fb_group_entries->privacy->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $fb_group_entries->privacy->CellAttributes() ?>><span id="el_fb_group_entries_privacy">
<span<?php echo $fb_group_entries->privacy->ViewAttributes() ?>>
<?php echo $fb_group_entries->privacy->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($fb_group_entries->promotion_status->Visible) { // promotion_status ?>
	<tr id="r_promotion_status"<?php echo $fb_group_entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_group_entries_promotion_status"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $fb_group_entries->promotion_status->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $fb_group_entries->promotion_status->CellAttributes() ?>><span id="el_fb_group_entries_promotion_status">
<span<?php echo $fb_group_entries->promotion_status->ViewAttributes() ?>>
<?php echo $fb_group_entries->promotion_status->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($fb_group_entries->timeline_visibility->Visible) { // timeline_visibility ?>
	<tr id="r_timeline_visibility"<?php echo $fb_group_entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_group_entries_timeline_visibility"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $fb_group_entries->timeline_visibility->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $fb_group_entries->timeline_visibility->CellAttributes() ?>><span id="el_fb_group_entries_timeline_visibility">
<span<?php echo $fb_group_entries->timeline_visibility->ViewAttributes() ?>>
<?php echo $fb_group_entries->timeline_visibility->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($fb_group_entries->to->Visible) { // to ?>
	<tr id="r_to"<?php echo $fb_group_entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_group_entries_to"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $fb_group_entries->to->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $fb_group_entries->to->CellAttributes() ?>><span id="el_fb_group_entries_to">
<span<?php echo $fb_group_entries->to->ViewAttributes() ?>>
<?php echo $fb_group_entries->to->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($fb_group_entries->type->Visible) { // type ?>
	<tr id="r_type"<?php echo $fb_group_entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_group_entries_type"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $fb_group_entries->type->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $fb_group_entries->type->CellAttributes() ?>><span id="el_fb_group_entries_type">
<span<?php echo $fb_group_entries->type->ViewAttributes() ?>>
<?php echo $fb_group_entries->type->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($fb_group_entries->updated_time->Visible) { // updated_time ?>
	<tr id="r_updated_time"<?php echo $fb_group_entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_group_entries_updated_time"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $fb_group_entries->updated_time->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $fb_group_entries->updated_time->CellAttributes() ?>><span id="el_fb_group_entries_updated_time">
<span<?php echo $fb_group_entries->updated_time->ViewAttributes() ?>>
<?php echo $fb_group_entries->updated_time->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($fb_group_entries->from->Visible) { // from ?>
	<tr id="r_from"<?php echo $fb_group_entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_group_entries_from"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $fb_group_entries->from->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $fb_group_entries->from->CellAttributes() ?>><span id="el_fb_group_entries_from">
<span<?php echo $fb_group_entries->from->ViewAttributes() ?>>
<?php echo $fb_group_entries->from->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($fb_group_entries->comments->Visible) { // comments ?>
	<tr id="r_comments"<?php echo $fb_group_entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_group_entries_comments"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $fb_group_entries->comments->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $fb_group_entries->comments->CellAttributes() ?>><span id="el_fb_group_entries_comments">
<span<?php echo $fb_group_entries->comments->ViewAttributes() ?>>
<?php echo $fb_group_entries->comments->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($fb_group_entries->id_grupo->Visible) { // id_grupo ?>
	<tr id="r_id_grupo"<?php echo $fb_group_entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_group_entries_id_grupo"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $fb_group_entries->id_grupo->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $fb_group_entries->id_grupo->CellAttributes() ?>><span id="el_fb_group_entries_id_grupo">
<span<?php echo $fb_group_entries->id_grupo->ViewAttributes() ?>>
<?php echo $fb_group_entries->id_grupo->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($fb_group_entries->icon->Visible) { // icon ?>
	<tr id="r_icon"<?php echo $fb_group_entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_group_entries_icon"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $fb_group_entries->icon->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $fb_group_entries->icon->CellAttributes() ?>><span id="el_fb_group_entries_icon">
<span<?php echo $fb_group_entries->icon->ViewAttributes() ?>>
<?php echo $fb_group_entries->icon->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($fb_group_entries->link->Visible) { // link ?>
	<tr id="r_link"<?php echo $fb_group_entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_group_entries_link"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $fb_group_entries->link->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $fb_group_entries->link->CellAttributes() ?>><span id="el_fb_group_entries_link">
<span<?php echo $fb_group_entries->link->ViewAttributes() ?>>
<?php echo $fb_group_entries->link->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($fb_group_entries->name->Visible) { // name ?>
	<tr id="r_name"<?php echo $fb_group_entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_group_entries_name"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $fb_group_entries->name->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $fb_group_entries->name->CellAttributes() ?>><span id="el_fb_group_entries_name">
<span<?php echo $fb_group_entries->name->ViewAttributes() ?>>
<?php echo $fb_group_entries->name->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($fb_group_entries->object_id->Visible) { // object_id ?>
	<tr id="r_object_id"<?php echo $fb_group_entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_group_entries_object_id"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $fb_group_entries->object_id->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $fb_group_entries->object_id->CellAttributes() ?>><span id="el_fb_group_entries_object_id">
<span<?php echo $fb_group_entries->object_id->ViewAttributes() ?>>
<?php echo $fb_group_entries->object_id->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($fb_group_entries->picture->Visible) { // picture ?>
	<tr id="r_picture"<?php echo $fb_group_entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_group_entries_picture"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $fb_group_entries->picture->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $fb_group_entries->picture->CellAttributes() ?>><span id="el_fb_group_entries_picture">
<span<?php echo $fb_group_entries->picture->ViewAttributes() ?>>
<?php echo $fb_group_entries->picture->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($fb_group_entries->properties->Visible) { // properties ?>
	<tr id="r_properties"<?php echo $fb_group_entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_group_entries_properties"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $fb_group_entries->properties->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $fb_group_entries->properties->CellAttributes() ?>><span id="el_fb_group_entries_properties">
<span<?php echo $fb_group_entries->properties->ViewAttributes() ?>>
<?php echo $fb_group_entries->properties->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($fb_group_entries->message_tags->Visible) { // message_tags ?>
	<tr id="r_message_tags"<?php echo $fb_group_entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_group_entries_message_tags"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $fb_group_entries->message_tags->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $fb_group_entries->message_tags->CellAttributes() ?>><span id="el_fb_group_entries_message_tags">
<span<?php echo $fb_group_entries->message_tags->ViewAttributes() ?>>
<?php echo $fb_group_entries->message_tags->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($fb_group_entries->caption->Visible) { // caption ?>
	<tr id="r_caption"<?php echo $fb_group_entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_group_entries_caption"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $fb_group_entries->caption->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $fb_group_entries->caption->CellAttributes() ?>><span id="el_fb_group_entries_caption">
<span<?php echo $fb_group_entries->caption->ViewAttributes() ?>>
<?php echo $fb_group_entries->caption->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($fb_group_entries->description->Visible) { // description ?>
	<tr id="r_description"<?php echo $fb_group_entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_group_entries_description"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $fb_group_entries->description->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $fb_group_entries->description->CellAttributes() ?>><span id="el_fb_group_entries_description">
<span<?php echo $fb_group_entries->description->ViewAttributes() ?>>
<?php echo $fb_group_entries->description->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($fb_group_entries->tool_id->Visible) { // tool_id ?>
	<tr id="r_tool_id"<?php echo $fb_group_entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_group_entries_tool_id"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $fb_group_entries->tool_id->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $fb_group_entries->tool_id->CellAttributes() ?>><span id="el_fb_group_entries_tool_id">
<span<?php echo $fb_group_entries->tool_id->ViewAttributes() ?>>
<?php echo $fb_group_entries->tool_id->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($fb_group_entries->application->Visible) { // application ?>
	<tr id="r_application"<?php echo $fb_group_entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_group_entries_application"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $fb_group_entries->application->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $fb_group_entries->application->CellAttributes() ?>><span id="el_fb_group_entries_application">
<span<?php echo $fb_group_entries->application->ViewAttributes() ?>>
<?php echo $fb_group_entries->application->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
</table>
</form>
<script type="text/javascript">
ffb_group_entriesview.Init();
</script>
<?php
$fb_group_entries_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$fb_group_entries_view->Page_Terminate();
?>
