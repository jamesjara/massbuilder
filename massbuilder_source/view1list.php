<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg9.php" ?>
<?php include_once "ewmysql9.php" ?>
<?php include_once "phpfn9.php" ?>
<?php include_once "view1info.php" ?>
<?php include_once "userfn9.php" ?>
<?php

//
// Page class
//

$view1_list = NULL; // Initialize page object first

class cview1_list extends cview1 {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{3554BCCA-7E88-4E52-9661-DF55D75275C9}";

	// Table name
	var $TableName = 'view1';

	// Page object name
	var $PageObjName = 'view1_list';

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
			$html .= "<table class=\"ewMessageTable\"><tr><td class=\"ewWarningIcon\"></td><td class=\"ewWarningMessage\">" . $sWarningMessage . "</td></tr></table>";
			$_SESSION[EW_SESSION_WARNING_MESSAGE] = ""; // Clear message in Session
		}

		// Success message
		$sSuccessMessage = $this->getSuccessMessage();
		$this->Message_Showing($sSuccessMessage, "success");
		if ($sSuccessMessage <> "") { // Message in Session, display
			$html .= "<table class=\"ewMessageTable\"><tr><td class=\"ewSuccessIcon\"></td><td class=\"ewSuccessMessage\">" . $sSuccessMessage . "</td></tr></table>";
			$_SESSION[EW_SESSION_SUCCESS_MESSAGE] = ""; // Clear message in Session
		}

		// Failure message
		$sErrorMessage = $this->getFailureMessage();
		$this->Message_Showing($sErrorMessage, "failure");
		if ($sErrorMessage <> "") { // Message in Session, display
			$html .= "<table class=\"ewMessageTable\"><tr><td class=\"ewErrorIcon\"></td><td class=\"ewErrorMessage\">" . $sErrorMessage . "</td></tr></table>";
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

		// Table object (view1)
		if (!isset($GLOBALS["view1"])) {
			$GLOBALS["view1"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["view1"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = "view1add.php";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "view1delete.php";
		$this->MultiUpdateUrl = "view1update.php";

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'view1', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

		// List options
		$this->ListOptions = new cListOptions();
		$this->ListOptions->TableVar = $this->TableVar;

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "span";
		$this->ExportOptions->TagClassName = "ewExportOption";
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Security
		$Security = new cAdvancedSecurity();
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if (!$Security->IsLoggedIn()) {
			$Security->SaveLastUrl();
			$this->Page_Terminate("login.php");
		}

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"];
		$this->id_domains->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();
		$this->bt1->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();
		$this->bt2->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();
		$this->bt3->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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

	// Class variables
	var $ListOptions; // List options
	var $ExportOptions; // Export options
	var $DisplayRecs = 200;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $SearchWhere = ""; // Search WHERE clause
	var $RecCnt = 0; // Record count
	var $EditRowCnt;
	var $StartRowCnt = 1;
	var $RowCnt = 0;
	var $Attrs = array(); // Row attributes and cell attributes
	var $RowIndex = 0; // Row index
	var $KeyCount = 0; // Key count
	var $RowAction = ""; // Row action
	var $RowOldKey = ""; // Row old key (for copy)
	var $RecPerRow = 0;
	var $ColCnt = 0;
	var $DbMasterFilter = ""; // Master filter
	var $DbDetailFilter = ""; // Detail filter
	var $MasterRecordExists;	
	var $MultiSelectKey;
	var $Command;
	var $Recordset;
	var $OldRecordset;

	//
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError, $gsSearchError, $Security;

		// Search filters
		$sSrchAdvanced = ""; // Advanced search filter
		$sSrchBasic = ""; // Basic search filter
		$sFilter = "";

		// Get command
		$this->Command = strtolower(@$_GET["cmd"]);
		if ($this->IsPageRequest()) { // Validate request

			// Handle reset command
			$this->ResetCmd();

			// Hide all options
			if ($this->Export <> "" ||
				$this->CurrentAction == "gridadd" ||
				$this->CurrentAction == "gridedit") {
				$this->ListOptions->HideAllOptions();
				$this->ExportOptions->HideAllOptions();
			}

			// Set up sorting order
			$this->SetUpSortOrder();
		}

		// Restore display records
		if ($this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 200; // Load default
		}

		// Load Sorting Order
		$this->LoadSortOrder();

		// Build filter
		$sFilter = "";
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Set up filter in session
		$this->setSessionWhere($sFilter);
		$this->CurrentFilter = "";
	}

	// Build filter for all keys
	function BuildKeyFilter() {
		global $objForm;
		$sWrkFilter = "";

		// Update row index and get row key
		$rowindex = 1;
		$objForm->Index = $rowindex;
		$sThisKey = strval($objForm->GetValue("k_key"));
		while ($sThisKey <> "") {
			if ($this->SetupKeyValues($sThisKey)) {
				$sFilter = $this->KeyFilter();
				if ($sWrkFilter <> "") $sWrkFilter .= " OR ";
				$sWrkFilter .= $sFilter;
			} else {
				$sWrkFilter = "0=1";
				break;
			}

			// Update row index and get row key
			$rowindex++; // next row
			$objForm->Index = $rowindex;
			$sThisKey = strval($objForm->GetValue("k_key"));
		}
		return $sWrkFilter;
	}

	// Set up key values
	function SetupKeyValues($key) {
		$arrKeyFlds = explode($GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"], $key);
		if (count($arrKeyFlds) >= 4) {
			$this->id_domains->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->id_domains->FormValue))
				return FALSE;
			$this->bt1->setFormValue($arrKeyFlds[1]);
			if (!is_numeric($this->bt1->FormValue))
				return FALSE;
			$this->bt2->setFormValue($arrKeyFlds[2]);
			if (!is_numeric($this->bt2->FormValue))
				return FALSE;
			$this->bt3->setFormValue($arrKeyFlds[3]);
			if (!is_numeric($this->bt3->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->dominio); // dominio
			$this->UpdateSort($this->id_domains); // id_domains
			$this->UpdateSort($this->bt1); // bt1
			$this->UpdateSort($this->bt2); // bt2
			$this->UpdateSort($this->bt3); // bt3
			$this->setStartRecordNumber(1); // Reset start position
		}
	}

	// Load sort order parameters
	function LoadSortOrder() {
		$sOrderBy = $this->getSessionOrderBy(); // Get ORDER BY from Session
		if ($sOrderBy == "") {
			if ($this->SqlOrderBy() <> "") {
				$sOrderBy = $this->SqlOrderBy();
				$this->setSessionOrderBy($sOrderBy);
			}
		}
	}

	// Reset command
	// cmd=reset (Reset search parameters)
	// cmd=resetall (Reset search and master/detail parameters)
	// cmd=resetsort (Reset sort parameters)
	function ResetCmd() {

		// Check if reset command
		if (substr($this->Command,0,5) == "reset") {

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->dominio->setSort("");
				$this->id_domains->setSort("");
				$this->bt1->setSort("");
				$this->bt2->setSort("");
				$this->bt3->setSort("");
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// Call ListOptions_Load event
		$this->ListOptions_Load();
	}

	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $objForm;
		$this->ListOptions->LoadDefault();
		$this->RenderListOptionsExt();

		// Call ListOptions_Rendered event
		$this->ListOptions_Rendered();
	}

	function RenderListOptionsExt() {
		global $Security, $Language;
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

	// Load recordset
	function LoadRecordset($offset = -1, $rowcnt = -1) {
		global $conn;

		// Call Recordset Selecting event
		$this->Recordset_Selecting($this->CurrentFilter);

		// Load List page SQL
		$sSql = $this->SelectSQL();
		if ($offset > -1 && $rowcnt > -1)
			$sSql .= " LIMIT $rowcnt OFFSET $offset";

		// Load recordset
		$rs = ew_LoadRecordset($sSql);

		// Call Recordset Selected event
		$this->Recordset_Selected($rs);
		return $rs;
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
		$this->dominio->setDbValue($rs->fields('dominio'));
		$this->id_domains->setDbValue($rs->fields('id_domains'));
		$this->bt1->setDbValue($rs->fields('bt1'));
		$this->bt2->setDbValue($rs->fields('bt2'));
		$this->bt3->setDbValue($rs->fields('bt3'));
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id_domains")) <> "")
			$this->id_domains->CurrentValue = $this->getKey("id_domains"); // id_domains
		else
			$bValidKey = FALSE;
		if (strval($this->getKey("bt1")) <> "")
			$this->bt1->CurrentValue = $this->getKey("bt1"); // bt1
		else
			$bValidKey = FALSE;
		if (strval($this->getKey("bt2")) <> "")
			$this->bt2->CurrentValue = $this->getKey("bt2"); // bt2
		else
			$bValidKey = FALSE;
		if (strval($this->getKey("bt3")) <> "")
			$this->bt3->CurrentValue = $this->getKey("bt3"); // bt3
		else
			$bValidKey = FALSE;

		// Load old recordset
		if ($bValidKey) {
			$this->CurrentFilter = $this->KeyFilter();
			$sSql = $this->SQL();
			$this->OldRecordset = ew_LoadRecordset($sSql);
			$this->LoadRowValues($this->OldRecordset); // Load row values
		} else {
			$this->OldRecordset = NULL;
		}
		return $bValidKey;
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		$this->ViewUrl = $this->GetViewUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->InlineEditUrl = $this->GetInlineEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->InlineCopyUrl = $this->GetInlineCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();

		// Call Row_Rendering event
		$this->Row_Rendering();

		// Common render codes for all row types
		// dominio
		// id_domains
		// bt1
		// bt2
		// bt3

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// dominio
			$this->dominio->ViewValue = $this->dominio->CurrentValue;
			$this->dominio->ViewCustomAttributes = "";

			// id_domains
			$this->id_domains->ViewValue = $this->id_domains->CurrentValue;
			$this->id_domains->ViewCustomAttributes = "";

			// bt1
			$this->bt1->ViewValue = $this->bt1->CurrentValue;
			$this->bt1->ViewCustomAttributes = "";

			// bt2
			$this->bt2->ViewValue = $this->bt2->CurrentValue;
			$this->bt2->ViewCustomAttributes = "";

			// bt3
			$this->bt3->ViewValue = $this->bt3->CurrentValue;
			$this->bt3->ViewCustomAttributes = "";

			// dominio
			$this->dominio->LinkCustomAttributes = "";
			$this->dominio->HrefValue = "";
			$this->dominio->TooltipValue = "";

			// id_domains
			$this->id_domains->LinkCustomAttributes = "";
			$this->id_domains->HrefValue = "";
			$this->id_domains->TooltipValue = "";

			// bt1
			$this->bt1->LinkCustomAttributes = test;
			if (!ew_Empty($this->id_domains->CurrentValue)) {
				$this->bt1->HrefValue = ((!empty($this->id_domains->ViewValue)) ? $this->id_domains->ViewValue : $this->id_domains->CurrentValue); // Add prefix/suffix
				$this->bt1->LinkAttrs["target"] = "_blank"; // Add target
				if ($this->Export <> "") $this->bt1->HrefValue = ew_ConvertFullUrl($this->bt1->HrefValue);
			} else {
				$this->bt1->HrefValue = "";
			}
			$this->bt1->TooltipValue = "";

			// bt2
			$this->bt2->LinkCustomAttributes = "";
			if (!ew_Empty($this->bt2->CurrentValue)) {
				$this->bt2->HrefValue = ((!empty($this->bt2->ViewValue)) ? $this->bt2->ViewValue : $this->bt2->CurrentValue); // Add prefix/suffix
				$this->bt2->LinkAttrs["target"] = "_blank"; // Add target
				if ($this->Export <> "") $this->bt2->HrefValue = ew_ConvertFullUrl($this->bt2->HrefValue);
			} else {
				$this->bt2->HrefValue = "";
			}
			$this->bt2->TooltipValue = "";

			// bt3
			$this->bt3->LinkCustomAttributes = "";
			if (!ew_Empty($this->bt3->CurrentValue)) {
				$this->bt3->HrefValue = ((!empty($this->bt3->ViewValue)) ? $this->bt3->ViewValue : $this->bt3->CurrentValue); // Add prefix/suffix
				$this->bt3->LinkAttrs["target"] = "_blank"; // Add target
				if ($this->Export <> "") $this->bt3->HrefValue = ew_ConvertFullUrl($this->bt3->HrefValue);
			} else {
				$this->bt3->HrefValue = "";
			}
			$this->bt3->TooltipValue = "";
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

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}

	// ListOptions Load event
	function ListOptions_Load() {

		// Example:
		//$opt = &$this->ListOptions->Add("new");
		//$opt->Header = "xxx";
		//$opt->OnLeft = TRUE; // Link on left
		//$opt->MoveTo(0); // Move to first column

	}

	// ListOptions Rendered event
	function ListOptions_Rendered() {

		// Example: 
		//$this->ListOptions->Items["new"]->Body = "xxx";

	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($view1_list)) $view1_list = new cview1_list();

// Page init
$view1_list->Page_Init();

// Page main
$view1_list->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var view1_list = new ew_Page("view1_list");
view1_list.PageID = "list"; // Page ID
var EW_PAGE_ID = view1_list.PageID; // For backward compatibility

// Form object
var fview1list = new ew_Form("fview1list");

// Form_CustomValidate event
fview1list.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fview1list.ValidateRequired = true;
<?php } else { ?>
fview1list.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$view1_list->TotalRecs = $view1->SelectRecordCount();
	} else {
		if ($view1_list->Recordset = $view1_list->LoadRecordset())
			$view1_list->TotalRecs = $view1_list->Recordset->RecordCount();
	}
	$view1_list->StartRec = 1;
	if ($view1_list->DisplayRecs <= 0 || ($view1->Export <> "" && $view1->ExportAll)) // Display all records
		$view1_list->DisplayRecs = $view1_list->TotalRecs;
	if (!($view1->Export <> "" && $view1->ExportAll))
		$view1_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$view1_list->Recordset = $view1_list->LoadRecordset($view1_list->StartRec-1, $view1_list->DisplayRecs);
?>
<p style="white-space: nowrap;"><span id="ewPageCaption" class="ewTitle ewTableTitle"><?php echo $Language->Phrase("TblTypeVIEW") ?><?php echo $view1->TableCaption() ?>&nbsp;&nbsp;</span>
<?php $view1_list->ExportOptions->Render("body"); ?>
</p>
<?php $view1_list->ShowPageHeader(); ?>
<?php
$view1_list->ShowMessage();
?>
<br />
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridUpperPanel">
<?php if ($view1->CurrentAction <> "gridadd" && $view1->CurrentAction <> "gridedit") { ?>
<form name="ewpagerform" id="ewpagerform" class="ewForm" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager"><tr><td>
<span class="phpmaker">
<?php if (!isset($view1_list->Pager)) $view1_list->Pager = new cNumericPager($view1_list->StartRec, $view1_list->DisplayRecs, $view1_list->TotalRecs, $view1_list->RecRange) ?>
<?php if ($view1_list->Pager->RecordCount > 0) { ?>
	<?php if ($view1_list->Pager->FirstButton->Enabled) { ?>
	<a href="<?php echo $view1_list->PageUrl() ?>start=<?php echo $view1_list->Pager->FirstButton->Start ?>"><b><?php echo $Language->Phrase("PagerFirst") ?></b></a>&nbsp;
	<?php } ?>
	<?php if ($view1_list->Pager->PrevButton->Enabled) { ?>
	<a href="<?php echo $view1_list->PageUrl() ?>start=<?php echo $view1_list->Pager->PrevButton->Start ?>"><b><?php echo $Language->Phrase("PagerPrevious") ?></b></a>&nbsp;
	<?php } ?>
	<?php foreach ($view1_list->Pager->Items as $PagerItem) { ?>
		<?php if ($PagerItem->Enabled) { ?><a href="<?php echo $view1_list->PageUrl() ?>start=<?php echo $PagerItem->Start ?>"><?php } ?><b><?php echo $PagerItem->Text ?></b><?php if ($PagerItem->Enabled) { ?></a><?php } ?>&nbsp;
	<?php } ?>
	<?php if ($view1_list->Pager->NextButton->Enabled) { ?>
	<a href="<?php echo $view1_list->PageUrl() ?>start=<?php echo $view1_list->Pager->NextButton->Start ?>"><b><?php echo $Language->Phrase("PagerNext") ?></b></a>&nbsp;
	<?php } ?>
	<?php if ($view1_list->Pager->LastButton->Enabled) { ?>
	<a href="<?php echo $view1_list->PageUrl() ?>start=<?php echo $view1_list->Pager->LastButton->Start ?>"><b><?php echo $Language->Phrase("PagerLast") ?></b></a>&nbsp;
	<?php } ?>
	<?php if ($view1_list->Pager->ButtonCount > 0) { ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php } ?>
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $view1_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $view1_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $view1_list->Pager->RecordCount ?>
<?php } else { ?>	
	<?php if ($view1_list->SearchWhere == "0=101") { ?>
	<?php echo $Language->Phrase("EnterSearchCriteria") ?>
	<?php } else { ?>
	<?php echo $Language->Phrase("NoRecord") ?>
	<?php } ?>
<?php } ?>
</span>
	</td>
</tr></table>
</form>
<?php } ?>
<span class="phpmaker">
</span>
</div>
<form name="fview1list" id="fview1list" class="ewForm" action="" method="post">
<input type="hidden" name="t" value="view1" />
<div id="gmp_view1" class="ewGridMiddlePanel">
<?php if ($view1_list->TotalRecs > 0) { ?>
<table id="tbl_view1list" class="ewTable ewTableSeparate">
<?php echo $view1->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$view1_list->RenderListOptions();

// Render list options (header, left)
$view1_list->ListOptions->Render("header", "left");
?>
<?php if ($view1->dominio->Visible) { // dominio ?>
	<?php if ($view1->SortUrl($view1->dominio) == "") { ?>
		<td><span id="elh_view1_dominio" class="view1_dominio"><table class="ewTableHeaderBtn"><thead><tr><td><?php echo $view1->dominio->FldCaption() ?></td></tr></thead></table></span></td>
	<?php } else { ?>
		<td><div onmousedown="ew_Sort(event,'<?php echo $view1->SortUrl($view1->dominio) ?>',1);"><span id="elh_view1_dominio" class="view1_dominio">
			<table class="ewTableHeaderBtn"><thead><tr><td class="ewTableHeaderCaption"><?php echo $view1->dominio->FldCaption() ?></td><td class="ewTableHeaderSort"><?php if ($view1->dominio->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($view1->dominio->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($view1->id_domains->Visible) { // id_domains ?>
	<?php if ($view1->SortUrl($view1->id_domains) == "") { ?>
		<td><span id="elh_view1_id_domains" class="view1_id_domains"><table class="ewTableHeaderBtn"><thead><tr><td><?php echo $view1->id_domains->FldCaption() ?></td></tr></thead></table></span></td>
	<?php } else { ?>
		<td><div onmousedown="ew_Sort(event,'<?php echo $view1->SortUrl($view1->id_domains) ?>',1);"><span id="elh_view1_id_domains" class="view1_id_domains">
			<table class="ewTableHeaderBtn"><thead><tr><td class="ewTableHeaderCaption"><?php echo $view1->id_domains->FldCaption() ?></td><td class="ewTableHeaderSort"><?php if ($view1->id_domains->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($view1->id_domains->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($view1->bt1->Visible) { // bt1 ?>
	<?php if ($view1->SortUrl($view1->bt1) == "") { ?>
		<td><span id="elh_view1_bt1" class="view1_bt1"><table class="ewTableHeaderBtn"><thead><tr><td><?php echo $view1->bt1->FldCaption() ?></td></tr></thead></table></span></td>
	<?php } else { ?>
		<td><div onmousedown="ew_Sort(event,'<?php echo $view1->SortUrl($view1->bt1) ?>',1);"><span id="elh_view1_bt1" class="view1_bt1">
			<table class="ewTableHeaderBtn"><thead><tr><td class="ewTableHeaderCaption"><?php echo $view1->bt1->FldCaption() ?></td><td class="ewTableHeaderSort"><?php if ($view1->bt1->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($view1->bt1->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($view1->bt2->Visible) { // bt2 ?>
	<?php if ($view1->SortUrl($view1->bt2) == "") { ?>
		<td><span id="elh_view1_bt2" class="view1_bt2"><table class="ewTableHeaderBtn"><thead><tr><td><?php echo $view1->bt2->FldCaption() ?></td></tr></thead></table></span></td>
	<?php } else { ?>
		<td><div onmousedown="ew_Sort(event,'<?php echo $view1->SortUrl($view1->bt2) ?>',1);"><span id="elh_view1_bt2" class="view1_bt2">
			<table class="ewTableHeaderBtn"><thead><tr><td class="ewTableHeaderCaption"><?php echo $view1->bt2->FldCaption() ?></td><td class="ewTableHeaderSort"><?php if ($view1->bt2->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($view1->bt2->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php if ($view1->bt3->Visible) { // bt3 ?>
	<?php if ($view1->SortUrl($view1->bt3) == "") { ?>
		<td><span id="elh_view1_bt3" class="view1_bt3"><table class="ewTableHeaderBtn"><thead><tr><td><?php echo $view1->bt3->FldCaption() ?></td></tr></thead></table></span></td>
	<?php } else { ?>
		<td><div onmousedown="ew_Sort(event,'<?php echo $view1->SortUrl($view1->bt3) ?>',1);"><span id="elh_view1_bt3" class="view1_bt3">
			<table class="ewTableHeaderBtn"><thead><tr><td class="ewTableHeaderCaption"><?php echo $view1->bt3->FldCaption() ?></td><td class="ewTableHeaderSort"><?php if ($view1->bt3->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($view1->bt3->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?></td></tr></thead></table>
		</span></div></td>		
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$view1_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($view1->ExportAll && $view1->Export <> "") {
	$view1_list->StopRec = $view1_list->TotalRecs;
} else {

	// Set the last record to display
	if ($view1_list->TotalRecs > $view1_list->StartRec + $view1_list->DisplayRecs - 1)
		$view1_list->StopRec = $view1_list->StartRec + $view1_list->DisplayRecs - 1;
	else
		$view1_list->StopRec = $view1_list->TotalRecs;
}
$view1_list->RecCnt = $view1_list->StartRec - 1;
if ($view1_list->Recordset && !$view1_list->Recordset->EOF) {
	$view1_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $view1_list->StartRec > 1)
		$view1_list->Recordset->Move($view1_list->StartRec - 1);
} elseif (!$view1->AllowAddDeleteRow && $view1_list->StopRec == 0) {
	$view1_list->StopRec = $view1->GridAddRowCount;
}

// Initialize aggregate
$view1->RowType = EW_ROWTYPE_AGGREGATEINIT;
$view1->ResetAttrs();
$view1_list->RenderRow();
while ($view1_list->RecCnt < $view1_list->StopRec) {
	$view1_list->RecCnt++;
	if (intval($view1_list->RecCnt) >= intval($view1_list->StartRec)) {
		$view1_list->RowCnt++;

		// Set up key count
		$view1_list->KeyCount = $view1_list->RowIndex;

		// Init row class and style
		$view1->ResetAttrs();
		$view1->CssClass = "";
		if ($view1->CurrentAction == "gridadd") {
		} else {
			$view1_list->LoadRowValues($view1_list->Recordset); // Load row values
		}
		$view1->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$view1->RowAttrs = array_merge($view1->RowAttrs, array('data-rowindex'=>$view1_list->RowCnt, 'id'=>'r' . $view1_list->RowCnt . '_view1', 'data-rowtype'=>$view1->RowType));

		// Render row
		$view1_list->RenderRow();

		// Render list options
		$view1_list->RenderListOptions();
?>
	<tr<?php echo $view1->RowAttributes() ?>>
<?php

// Render list options (body, left)
$view1_list->ListOptions->Render("body", "left", $view1_list->RowCnt);
?>
	<?php if ($view1->dominio->Visible) { // dominio ?>
		<td<?php echo $view1->dominio->CellAttributes() ?>><span id="el<?php echo $view1_list->RowCnt ?>_view1_dominio" class="view1_dominio">
<span<?php echo $view1->dominio->ViewAttributes() ?>>
<?php echo $view1->dominio->ListViewValue() ?></span>
</span></td>
	<?php } ?>
<a id="<?php echo $view1_list->PageObjName . "_row_" . $view1_list->RowCnt ?>"></a>
	<?php if ($view1->id_domains->Visible) { // id_domains ?>
		<td<?php echo $view1->id_domains->CellAttributes() ?>><span id="el<?php echo $view1_list->RowCnt ?>_view1_id_domains" class="view1_id_domains">
<span<?php echo $view1->id_domains->ViewAttributes() ?>>
<?php echo $view1->id_domains->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($view1->bt1->Visible) { // bt1 ?>
		<td<?php echo $view1->bt1->CellAttributes() ?>><span id="el<?php echo $view1_list->RowCnt ?>_view1_bt1" class="view1_bt1">
<div id="orig<?php echo $view1_list->RowCnt ?>_view1_bt1" class="ewDisplayNone">
<span<?php echo $view1->bt1->ViewAttributes() ?>>
<?php if (!ew_EmptyStr($view1->bt1->ListViewValue()) && $view1->bt1->LinkAttributes() <> "") { ?>
<a<?php echo $view1->bt1->LinkAttributes() ?>><?php echo $view1->bt1->ListViewValue() ?></a>
<?php } else { ?>
<?php echo $view1->bt1->ListViewValue() ?>
<?php } ?>
</span>
</div>
<?php                                          
echo urlencode(CurrentPage()->id_domains->CurrentValue) 
?>
</span></td>
	<?php } ?>
	<?php if ($view1->bt2->Visible) { // bt2 ?>
		<td<?php echo $view1->bt2->CellAttributes() ?>><span id="el<?php echo $view1_list->RowCnt ?>_view1_bt2" class="view1_bt2">
<span<?php echo $view1->bt2->ViewAttributes() ?>>
<?php if (!ew_EmptyStr($view1->bt2->ListViewValue()) && $view1->bt2->LinkAttributes() <> "") { ?>
<a<?php echo $view1->bt2->LinkAttributes() ?>><?php echo $view1->bt2->ListViewValue() ?></a>
<?php } else { ?>
<?php echo $view1->bt2->ListViewValue() ?>
<?php } ?>
</span>
</span></td>
	<?php } ?>
	<?php if ($view1->bt3->Visible) { // bt3 ?>
		<td<?php echo $view1->bt3->CellAttributes() ?>><span id="el<?php echo $view1_list->RowCnt ?>_view1_bt3" class="view1_bt3">
<span<?php echo $view1->bt3->ViewAttributes() ?>>
<?php if (!ew_EmptyStr($view1->bt3->ListViewValue()) && $view1->bt3->LinkAttributes() <> "") { ?>
<a<?php echo $view1->bt3->LinkAttributes() ?>><?php echo $view1->bt3->ListViewValue() ?></a>
<?php } else { ?>
<?php echo $view1->bt3->ListViewValue() ?>
<?php } ?>
</span>
</span></td>
	<?php } ?>
<?php

// Render list options (body, right)
$view1_list->ListOptions->Render("body", "right", $view1_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($view1->CurrentAction <> "gridadd")
		$view1_list->Recordset->MoveNext();
}
?>
</tbody>
</table>
<?php } ?>
<?php if ($view1->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</div>
</form>
<?php

// Close recordset
if ($view1_list->Recordset)
	$view1_list->Recordset->Close();
?>
<?php if ($view1_list->TotalRecs > 0) { ?>
<div class="ewGridLowerPanel">
<?php if ($view1->CurrentAction <> "gridadd" && $view1->CurrentAction <> "gridedit") { ?>
<form name="ewpagerform" id="ewpagerform" class="ewForm" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager"><tr><td>
<span class="phpmaker">
<?php if (!isset($view1_list->Pager)) $view1_list->Pager = new cNumericPager($view1_list->StartRec, $view1_list->DisplayRecs, $view1_list->TotalRecs, $view1_list->RecRange) ?>
<?php if ($view1_list->Pager->RecordCount > 0) { ?>
	<?php if ($view1_list->Pager->FirstButton->Enabled) { ?>
	<a href="<?php echo $view1_list->PageUrl() ?>start=<?php echo $view1_list->Pager->FirstButton->Start ?>"><b><?php echo $Language->Phrase("PagerFirst") ?></b></a>&nbsp;
	<?php } ?>
	<?php if ($view1_list->Pager->PrevButton->Enabled) { ?>
	<a href="<?php echo $view1_list->PageUrl() ?>start=<?php echo $view1_list->Pager->PrevButton->Start ?>"><b><?php echo $Language->Phrase("PagerPrevious") ?></b></a>&nbsp;
	<?php } ?>
	<?php foreach ($view1_list->Pager->Items as $PagerItem) { ?>
		<?php if ($PagerItem->Enabled) { ?><a href="<?php echo $view1_list->PageUrl() ?>start=<?php echo $PagerItem->Start ?>"><?php } ?><b><?php echo $PagerItem->Text ?></b><?php if ($PagerItem->Enabled) { ?></a><?php } ?>&nbsp;
	<?php } ?>
	<?php if ($view1_list->Pager->NextButton->Enabled) { ?>
	<a href="<?php echo $view1_list->PageUrl() ?>start=<?php echo $view1_list->Pager->NextButton->Start ?>"><b><?php echo $Language->Phrase("PagerNext") ?></b></a>&nbsp;
	<?php } ?>
	<?php if ($view1_list->Pager->LastButton->Enabled) { ?>
	<a href="<?php echo $view1_list->PageUrl() ?>start=<?php echo $view1_list->Pager->LastButton->Start ?>"><b><?php echo $Language->Phrase("PagerLast") ?></b></a>&nbsp;
	<?php } ?>
	<?php if ($view1_list->Pager->ButtonCount > 0) { ?>&nbsp;&nbsp;&nbsp;&nbsp;<?php } ?>
	<?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $view1_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $view1_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $view1_list->Pager->RecordCount ?>
<?php } else { ?>	
	<?php if ($view1_list->SearchWhere == "0=101") { ?>
	<?php echo $Language->Phrase("EnterSearchCriteria") ?>
	<?php } else { ?>
	<?php echo $Language->Phrase("NoRecord") ?>
	<?php } ?>
<?php } ?>
</span>
	</td>
</tr></table>
</form>
<?php } ?>
<span class="phpmaker">
</span>
</div>
<?php } ?>
</td></tr></table>
<script type="text/javascript">
fview1list.Init();
</script>
<?php
$view1_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$view1_list->Page_Terminate();
?>
