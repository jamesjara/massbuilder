<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg9.php" ?>
<?php include_once "ewmysql9.php" ?>
<?php include_once "phpfn9.php" ?>
<?php include_once "toolsinfo.php" ?>
<?php include_once "domainsinfo.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "userfn9.php" ?>
<?php

//
// Page class
//

$tools_view = NULL; // Initialize page object first

class ctools_view extends ctools {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{3554BCCA-7E88-4E52-9661-DF55D75275C9}";

	// Table name
	var $TableName = 'tools';

	// Page object name
	var $PageObjName = 'tools_view';

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

		// Table object (tools)
		if (!isset($GLOBALS["tools"])) {
			$GLOBALS["tools"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["tools"];
		}
		$KeyUrl = "";
		if (@$_GET["idtools"] <> "") {
			$this->RecKey["idtools"] = $_GET["idtools"];
			$KeyUrl .= "&idtools=" . urlencode($this->RecKey["idtools"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Table object (domains)
		if (!isset($GLOBALS['domains'])) $GLOBALS['domains'] = new cdomains();

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'tools', TRUE);

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
			$this->Page_Terminate("toolslist.php");
		}
		$Security->UserID_Loading();
		if ($Security->IsLoggedIn()) $Security->LoadUserID();
		$Security->UserID_Loaded();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"];
		$this->idtools->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
			if (@$_GET["idtools"] <> "") {
				$this->idtools->setQueryStringValue($_GET["idtools"]);
				$this->RecKey["idtools"] = $this->idtools->QueryStringValue;
			} else {
				$sReturnUrl = $this->james_url( "toolslist.php" ); // Return to list
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					if (!$this->LoadRow()) { // Load record based on key
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = $this->james_url( "toolslist.php" ); // No matching record, return to list
					}
			}
		} else {
			$sReturnUrl = $this->james_url( "toolslist.php" ); // Not page request, return to list
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
		$this->idtools->setDbValue($rs->fields('idtools'));
		$this->target_domain->setDbValue($rs->fields('target_domain'));
		$this->type->setDbValue($rs->fields('type'));
		$this->url->setDbValue($rs->fields('url'));
		$this->time->setDbValue($rs->fields('time'));
		$this->status->setDbValue($rs->fields('status'));
		$this->log->setDbValue($rs->fields('log'));
		$this->parent_domain->setDbValue($rs->fields('parent_domain'));
		$this->Descripcion->setDbValue($rs->fields('Descripcion'));
		$this->tags->setDbValue($rs->fields('tags'));
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
		// idtools
		// target_domain
		// type
		// url
		// time
		// status
		// log
		// parent_domain
		// Descripcion
		// tags

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// idtools
			$this->idtools->ViewValue = $this->idtools->CurrentValue;
			$this->idtools->ViewCustomAttributes = "";

			// target_domain
			if (strval($this->target_domain->CurrentValue) <> "") {
				$sFilterWrk = "`id_domains`" . ew_SearchString("=", $this->target_domain->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `id_domains`, `dominio` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `domains`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->target_domain->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->target_domain->ViewValue = $this->target_domain->CurrentValue;
				}
			} else {
				$this->target_domain->ViewValue = NULL;
			}
			$this->target_domain->ViewCustomAttributes = "";

			// type
			if (strval($this->type->CurrentValue) <> "") {
				switch ($this->type->CurrentValue) {
					case $this->type->FldTagValue(1):
						$this->type->ViewValue = $this->type->FldTagCaption(1) <> "" ? $this->type->FldTagCaption(1) : $this->type->CurrentValue;
						break;
					case $this->type->FldTagValue(2):
						$this->type->ViewValue = $this->type->FldTagCaption(2) <> "" ? $this->type->FldTagCaption(2) : $this->type->CurrentValue;
						break;
					case $this->type->FldTagValue(3):
						$this->type->ViewValue = $this->type->FldTagCaption(3) <> "" ? $this->type->FldTagCaption(3) : $this->type->CurrentValue;
						break;
					default:
						$this->type->ViewValue = $this->type->CurrentValue;
				}
			} else {
				$this->type->ViewValue = NULL;
			}
			$this->type->ViewCustomAttributes = "";

			// url
			$this->url->ViewValue = $this->url->CurrentValue;
			$this->url->ViewCustomAttributes = "";

			// time
			$this->time->ViewValue = $this->time->CurrentValue;
			$this->time->ViewValue = ew_FormatDateTime($this->time->ViewValue, 5);
			$this->time->ViewCustomAttributes = "";

			// status
			if (strval($this->status->CurrentValue) <> "") {
				$this->status->ViewValue = "";
				$arwrk = explode(",", strval($this->status->CurrentValue));
				$cnt = count($arwrk);
				for ($ari = 0; $ari < $cnt; $ari++) {
					switch (trim($arwrk[$ari])) {
						case $this->status->FldTagValue(1):
							$this->status->ViewValue .= $this->status->FldTagCaption(1) <> "" ? $this->status->FldTagCaption(1) : trim($arwrk[$ari]);
							break;
						default:
							$this->status->ViewValue .= trim($arwrk[$ari]);
					}
					if ($ari < $cnt-1) $this->status->ViewValue .= ew_ViewOptionSeparator($ari);
				}
			} else {
				$this->status->ViewValue = NULL;
			}
			$this->status->ViewCustomAttributes = "";

			// log
			$this->log->ViewValue = $this->log->CurrentValue;
			$this->log->ViewCustomAttributes = "";

			// parent_domain
			if (strval($this->parent_domain->CurrentValue) <> "") {
				$sFilterWrk = "`id_domains`" . ew_SearchString("=", $this->parent_domain->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `id_domains`, `dominio` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `domains`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->parent_domain->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->parent_domain->ViewValue = $this->parent_domain->CurrentValue;
				}
			} else {
				$this->parent_domain->ViewValue = NULL;
			}
			$this->parent_domain->ViewCustomAttributes = "";

			// Descripcion
			$this->Descripcion->ViewValue = $this->Descripcion->CurrentValue;
			$this->Descripcion->ViewCustomAttributes = "";

			// tags
			$this->tags->ViewValue = $this->tags->CurrentValue;
			$this->tags->ViewCustomAttributes = "";

			// idtools
			$this->idtools->LinkCustomAttributes = "";
			$this->idtools->HrefValue = "";
			$this->idtools->TooltipValue = "";

			// target_domain
			$this->target_domain->LinkCustomAttributes = "";
			$this->target_domain->HrefValue = "";
			$this->target_domain->TooltipValue = "";

			// type
			$this->type->LinkCustomAttributes = "";
			$this->type->HrefValue = "";
			$this->type->TooltipValue = "";

			// url
			$this->url->LinkCustomAttributes = "";
			$this->url->HrefValue = "";
			$this->url->TooltipValue = "";

			// time
			$this->time->LinkCustomAttributes = "";
			$this->time->HrefValue = "";
			$this->time->TooltipValue = "";

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";

			// log
			$this->log->LinkCustomAttributes = "";
			$this->log->HrefValue = "";
			$this->log->TooltipValue = "";

			// parent_domain
			$this->parent_domain->LinkCustomAttributes = "";
			$this->parent_domain->HrefValue = "";
			$this->parent_domain->TooltipValue = "";

			// Descripcion
			$this->Descripcion->LinkCustomAttributes = "";
			$this->Descripcion->HrefValue = "";
			$this->Descripcion->TooltipValue = "";

			// tags
			$this->tags->LinkCustomAttributes = "";
			$this->tags->HrefValue = "";
			$this->tags->TooltipValue = "";
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
if (!isset($tools_view)) $tools_view = new ctools_view();

// Page init
$tools_view->Page_Init();

// Page main
$tools_view->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var tools_view = new ew_Page("tools_view");
tools_view.PageID = "view"; // Page ID
var EW_PAGE_ID = tools_view.PageID; // For backward compatibility

// Form object
var ftoolsview = new ew_Form("ftoolsview");

// Form_CustomValidate event
ftoolsview.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftoolsview.ValidateRequired = true;
<?php } else { ?>
ftoolsview.ValidateRequired = false; 
<?php } ?>

// Multi-Page properties
ftoolsview.MultiPage = new ew_MultiPage("ftoolsview");
ftoolsview.MultiPage.Elements = [["x_idtools",1],["x_target_domain",1],["x_type",1],["x_url",1],["x_status",1],["x_log",1],["x_parent_domain",1],["x_Descripcion",1],["x_tags",2]];

// Dynamic selection lists
ftoolsview.Lists["x_target_domain"] = {"LinkField":"x_id_domains","Ajax":true,"AutoFill":false,"DisplayFields":["x_dominio","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
ftoolsview.Lists["x_parent_domain"] = {"LinkField":"x_id_domains","Ajax":true,"AutoFill":false,"DisplayFields":["x_dominio","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<h4 id="ewPageCaption" class="page-header ewTitle ewTableTitle"> <?php echo $Language->Phrase("View") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $tools->TableCaption() ?>&nbsp;&nbsp;</h4>
<a href="<?php echo $tools_view->ListUrl ?>" id="a_BackToList" class="ewLink label"><i class="icon-arrow-left icon-white"></i> <?php echo $Language->Phrase("BackToList") ?></a>
<?php //jamesjara
if(!ISSET($_GET['export']))echo '<div class="btn-group"><button class="btn btn-info dropdown-toggle" data-toggle="dropdown"><i class="icon-share icon-white"></i> Exportar<span class="caret"></span></button><ul class="dropdown-menu">';
$tools_view->ExportOptions->Render("body"); 
if(!ISSET($_GET['export']))echo '</ul></div> ';
?>
<?php if ($Security->CanAdd()) { ?>
<?php if ($tools_view->AddUrl <> "") { ?>
<a href="<?php echo $tools_view->AddUrl ?>" id="a_AddLink" class="ewLink ewGridLink btn btn-success"><?php echo $Language->Phrase("ViewPageAddLink") ?></a>
<?php } ?>
<?php } ?>
<?php if ($Security->CanEdit()) { ?>
<?php if ($tools_view->EditUrl <> "") { ?>
<a href="<?php echo $tools_view->EditUrl ?>" id="a_EditLink" class="ewLink btn btn-primary"><?php echo $Language->Phrase("ViewPageEditLink") ?></a>
<?php } ?>
<?php } ?>
<?php if ($Security->CanAdd()) { ?>
<?php if ($tools_view->CopyUrl <> "") { ?>
<a href="<?php echo $tools_view->CopyUrl ?>" id="a_CopyLink" class="ewLink"><?php echo $Language->Phrase("ViewPageCopyLink") ?></a>
<?php } ?>
<?php } ?>
<?php $tools_view->ShowPageHeader(); ?>
<?php
$tools_view->ShowMessage();
?>
<form name="ftoolsview" id="ftoolsview" class="ewForm" action="" method="post">
<input type="hidden" name="t" value="tools" />
<?php if ($tools->Export == "") { ?>
<table class="ewStdTable"><tbody><tr><td>
<div id="tools_view" class="y-ui-navset">
	<ul class="y-ui-nav">
		<li class="selected"><a href="#tab_tools1"><em><?php echo $tools->PageCaption(1) ?></em></a></li>
		<li><a href="#tab_tools2"><em><?php echo $tools->PageCaption(2) ?></em></a></li>
	</ul>
	<div class="y-ui-content">
<?php } ?>
		<div id="tab_tools1">
<table id="tbl_toolsview1" class="ewTable ewTableSeparate table table-striped ">
<?php if ($tools->idtools->Visible) { // idtools ?>
	<tr id="r_idtools"<?php echo $tools->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tools_idtools"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $tools->idtools->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $tools->idtools->CellAttributes() ?>><span id="el_tools_idtools">
<div id="orig_tools_idtools" class="ewDisplayNone">
<span<?php echo $tools->idtools->ViewAttributes() ?>>
<?php echo $tools->idtools->ViewValue ?></span>
</div>
<?php    
                     
if( BASENAME($_SERVER['PHP_SELF']) == 'toolslist.php' ){   // solo si es entrieslist.php
   $data  = '<a class="operacion btn btn-success" name="facebook_get_entries" id="'.CurrentTable()->idtools->CurrentValue.'" href="#">Process</a> ';  
   echo $data;
} else    {
   //$data  = '<a class="operacion btn btn-success" name="BackUp"    id="'.$_GET['id_domains'].'" href="#">BackUp</a> ';  
   //$data .= '<a class="operacion btn btn-primary" name="Traducion" id="'.$_GET['id_domains'].'" href="#">Traducion</a>';    
   //echo $data;          
}      
?> 
                                                                        
<?php if (debug01) echo CurrentTable()->idtools->CurrentValue;   ?>
</span></td>
	</tr>
<?php } ?>
<?php if ($tools->target_domain->Visible) { // target_domain ?>
	<tr id="r_target_domain"<?php echo $tools->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tools_target_domain"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $tools->target_domain->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $tools->target_domain->CellAttributes() ?>><span id="el_tools_target_domain">
<span<?php echo $tools->target_domain->ViewAttributes() ?>>
<?php echo $tools->target_domain->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($tools->type->Visible) { // type ?>
	<tr id="r_type"<?php echo $tools->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tools_type"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $tools->type->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $tools->type->CellAttributes() ?>><span id="el_tools_type">
<span<?php echo $tools->type->ViewAttributes() ?>>
<?php echo $tools->type->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($tools->url->Visible) { // url ?>
	<tr id="r_url"<?php echo $tools->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tools_url"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $tools->url->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $tools->url->CellAttributes() ?>><span id="el_tools_url">
<span<?php echo $tools->url->ViewAttributes() ?>>
<?php echo $tools->url->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($tools->time->Visible) { // time ?>
	<tr id="r_time"<?php echo $tools->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tools_time"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $tools->time->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $tools->time->CellAttributes() ?>><span id="el_tools_time">
<span<?php echo $tools->time->ViewAttributes() ?>>
<?php echo $tools->time->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($tools->status->Visible) { // status ?>
	<tr id="r_status"<?php echo $tools->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tools_status"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $tools->status->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $tools->status->CellAttributes() ?>><span id="el_tools_status">
<span<?php echo $tools->status->ViewAttributes() ?>>
<?php echo $tools->status->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($tools->log->Visible) { // log ?>
	<tr id="r_log"<?php echo $tools->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tools_log"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $tools->log->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $tools->log->CellAttributes() ?>><span id="el_tools_log">
<span<?php echo $tools->log->ViewAttributes() ?>>
<?php echo $tools->log->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($tools->parent_domain->Visible) { // parent_domain ?>
	<tr id="r_parent_domain"<?php echo $tools->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tools_parent_domain"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $tools->parent_domain->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $tools->parent_domain->CellAttributes() ?>><span id="el_tools_parent_domain">
<span<?php echo $tools->parent_domain->ViewAttributes() ?>>
<?php echo $tools->parent_domain->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($tools->Descripcion->Visible) { // Descripcion ?>
	<tr id="r_Descripcion"<?php echo $tools->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tools_Descripcion"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $tools->Descripcion->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $tools->Descripcion->CellAttributes() ?>><span id="el_tools_Descripcion">
<span<?php echo $tools->Descripcion->ViewAttributes() ?>>
<?php echo $tools->Descripcion->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
</table>
		</div>
		<div id="tab_tools2">
<table id="tbl_toolsview2" class="ewTable ewTableSeparate table table-striped ">
<?php if ($tools->tags->Visible) { // tags ?>
	<tr id="r_tags"<?php echo $tools->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tools_tags"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $tools->tags->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $tools->tags->CellAttributes() ?>><span id="el_tools_tags">
<span<?php echo $tools->tags->ViewAttributes() ?>>
<?php echo $tools->tags->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
</table>
		</div>
<?php if ($tools->Export == "") { ?>
	</div>
</div>
</td></tr></tbody></table>
<?php } ?>
</form>
<script type="text/javascript">
ftoolsview.Init();
</script>
<?php
$tools_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$tools_view->Page_Terminate();
?>
