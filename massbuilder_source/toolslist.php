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

$tools_list = NULL; // Initialize page object first

class ctools_list extends ctools {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{3554BCCA-7E88-4E52-9661-DF55D75275C9}";

	// Table name
	var $TableName = 'tools';

	// Page object name
	var $PageObjName = 'tools_list';

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

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = $this->james_url("toolsadd.php");
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "toolsdelete.php";
		$this->MultiUpdateUrl = "toolsupdate.php";

		// Table object (domains)
		if (!isset($GLOBALS['domains'])) $GLOBALS['domains'] = new cdomains();

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'tools', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

		// List options
		$this->ListOptions = new cListOptions();
		$this->ListOptions->TableVar = $this->TableVar;

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
		if (!$Security->CanList()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate("login.php");
		}
		$Security->UserID_Loading();
		if ($Security->IsLoggedIn()) $Security->LoadUserID();
		$Security->UserID_Loaded();

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();
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

			// Set up master detail parameters
			$this->SetUpMasterParms();

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
		if (!$Security->CanList())
			$sFilter = "(0=1)"; // Filter all records

		// Restore master/detail filter
		$this->DbMasterFilter = $this->GetMasterFilter(); // Restore master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Restore detail filter
		ew_AddFilter($sFilter, $this->DbDetailFilter);
		ew_AddFilter($sFilter, $this->SearchWhere);

		// Load master record
		if ($this->CurrentMode <> "add" && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "domains") {
			global $domains;
			$rsmaster = $domains->LoadRs($this->DbMasterFilter);
			$this->MasterRecordExists = ($rsmaster && !$rsmaster->EOF);
			if (!$this->MasterRecordExists) {
				$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record found
				$this->Page_Terminate("domainslist.php"); // Return to master page
			} else {
				$domains->LoadListRowValues($rsmaster);
				$domains->RowType = EW_ROWTYPE_MASTER; // Master row
				$domains->RenderListRow();
				$rsmaster->Close();
			}
		}

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
		if (count($arrKeyFlds) >= 1) {
			$this->idtools->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->idtools->FormValue))
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
			$this->UpdateSort($this->idtools); // idtools
			$this->UpdateSort($this->target_domain); // target_domain
			$this->UpdateSort($this->type); // type
			$this->UpdateSort($this->status); // status
			$this->UpdateSort($this->Descripcion); // Descripcion
			$this->UpdateSort($this->tags); // tags
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
				$this->status->setSort("DESC");
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

			// Reset master/detail keys
			if ($this->Command == "resetall") {
				$this->setCurrentMasterTable(""); // Clear master table
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
				$this->parent_domain->setSessionValue("");
			}

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->idtools->setSort("");
				$this->target_domain->setSort("");
				$this->type->setSort("");
				$this->status->setSort("");
				$this->Descripcion->setSort("");
				$this->tags->setSort("");
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// "view"
		$item = &$this->ListOptions->Add("view");
		$item->CssStyle = "white-spaceJAMES: nowrapJAMES;";
		$item->Visible = $Security->CanView();
		$item->OnLeft = FALSE;

		// "edit"
		$item = &$this->ListOptions->Add("edit");
		$item->CssStyle = "white-spaceJAMES: nowrapJAMES;";
		$item->Visible = $Security->CanEdit();
		$item->OnLeft = FALSE;

		// "copy"
		$item = &$this->ListOptions->Add("copy");
		$item->CssStyle = "white-spaceJAMES: nowrapJAMES;";
		$item->Visible = $Security->CanAdd();
		$item->OnLeft = FALSE;

		// Call ListOptions_Load event
		$this->ListOptions_Load();
	}

	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $objForm;
		$this->ListOptions->LoadDefault();

		// "view"
		$oListOpt = &$this->ListOptions->Items["view"];
		if ($Security->CanView())
			$oListOpt->Body = "<a class=\"ewRowLink label label-success\" href=\"" . $this->ViewUrl . "\"><i class='icon-search icon-white'></i> " . $Language->Phrase("ViewLink") . "</a>";

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		if ($Security->CanEdit()) {
			$oListOpt->Body = "<a class=\"ewRowLink label label-info\" href=\"" . $this->EditUrl . "\"><i class='icon-pencil icon-white'></i> " . $Language->Phrase("EditLink") . "</a>";
		}

		// "copy"
		$oListOpt = &$this->ListOptions->Items["copy"];
		if ($Security->CanAdd()) {
			$oListOpt->Body = "<a class=\"ewRowLink  label label-info\" href=\"" . $this->CopyUrl . "\">" . $Language->Phrase("CopyLink") . "</a>";
		}
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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("idtools")) <> "")
			$this->idtools->CurrentValue = $this->getKey("idtools"); // idtools
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

			// status
			$this->status->LinkCustomAttributes = "";
			$this->status->HrefValue = "";
			$this->status->TooltipValue = "";

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

	// Set up master/detail based on QueryString
	function SetUpMasterParms() {
		$bValidMaster = FALSE;

		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_MASTER])) {
			$sMasterTblVar = $_GET[EW_TABLE_SHOW_MASTER];
			if ($sMasterTblVar == "") {
				$bValidMaster = TRUE;
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
			}
			if ($sMasterTblVar == "domains") {
				$bValidMaster = TRUE;
				if (@$_GET["id_domains"] <> "") {
					$GLOBALS["domains"]->id_domains->setQueryStringValue($_GET["id_domains"]);
					$this->parent_domain->setQueryStringValue($GLOBALS["domains"]->id_domains->QueryStringValue);
					$this->parent_domain->setSessionValue($this->parent_domain->QueryStringValue);
					if (!is_numeric($GLOBALS["domains"]->id_domains->QueryStringValue)) $bValidMaster = FALSE;
				} else {
					$bValidMaster = FALSE;
				}
			}
		}
		if ($bValidMaster) {

			// Save current master table
			$this->setCurrentMasterTable($sMasterTblVar);

			// Reset start record counter (new master key)
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);

			// Clear previous master key from Session
			if ($sMasterTblVar <> "domains") {
				if ($this->parent_domain->QueryStringValue == "") $this->parent_domain->setSessionValue("");
			}
		}
		$this->DbMasterFilter = $this->GetMasterFilter(); //  Get master filter
		$this->DbDetailFilter = $this->GetDetailFilter(); // Get detail filter
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
if (!isset($tools_list)) $tools_list = new ctools_list();

// Page init
$tools_list->Page_Init();

// Page main
$tools_list->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var tools_list = new ew_Page("tools_list");
tools_list.PageID = "list"; // Page ID
var EW_PAGE_ID = tools_list.PageID; // For backward compatibility

// Form object
var ftoolslist = new ew_Form("ftoolslist");

// Form_CustomValidate event
ftoolslist.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftoolslist.ValidateRequired = true;
<?php } else { ?>
ftoolslist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ftoolslist.Lists["x_target_domain"] = {"LinkField":"x_id_domains","Ajax":true,"AutoFill":false,"DisplayFields":["x_dominio","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (($tools->Export == "") || (EW_EXPORT_MASTER_RECORD && $tools->Export == "print")) { ?>
<?php
$gsMasterReturnUrl = "domainslist.php";
if ($tools_list->DbMasterFilter <> "" && $tools->getCurrentMasterTable() == "domains") {
	if ($tools_list->MasterRecordExists) {
		if ($tools->getCurrentMasterTable() == $tools->TableVar) $gsMasterReturnUrl .= "?" . EW_TABLE_SHOW_MASTER . "=";
?>
<span class="ewTitle ewMasterTableTitle"><i class="icon-resize-small"></i> <?php echo $Language->Phrase("MasterRecord") ?><?php echo $domains->TableCaption() ?>&nbsp;&nbsp;</span>
<?php /* jamesjara
if(!ISSET($_GET['export']))echo '<div class="btn-group"><button class="btn btn-info dropdown-toggle" data-toggle="dropdown">Exportar2<span class="caret"></span></button><ul class="dropdown-menu">';
$tools_list->ExportOptions->Render("body"); 
if(!ISSET($_GET['export']))echo '</ul></div> ';*/
?>
<a href="<?php echo $gsMasterReturnUrl ?>" id="a_GoBack" class="ewLink label"><i class="icon-arrow-left icon-white"></i><?php echo $Language->Phrase("BackToMasterRecordPage") ?></a>
<?php include_once "domainsmaster.php" ?>
<?php
	}
}
?>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$tools_list->TotalRecs = $tools->SelectRecordCount();
	} else {
		if ($tools_list->Recordset = $tools_list->LoadRecordset())
			$tools_list->TotalRecs = $tools_list->Recordset->RecordCount();
	}
	$tools_list->StartRec = 1;
	if ($tools_list->DisplayRecs <= 0 || ($tools->Export <> "" && $tools->ExportAll)) // Display all records
		$tools_list->DisplayRecs = $tools_list->TotalRecs;
	if (!($tools->Export <> "" && $tools->ExportAll))
		$tools_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$tools_list->Recordset = $tools_list->LoadRecordset($tools_list->StartRec-1, $tools_list->DisplayRecs);
?>
<p style="white-spaceJAMES: nowrapJAMES;"><h4 id="ewPageCaption" class="page-header ewTitle ewTableTitle"> <?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $tools->TableCaption() ?>&nbsp;&nbsp;</h4>
<?php if ($tools->getCurrentMasterTable() == "") {  ?>
<?php /* jamesjara
if(!ISSET($_GET['export']))echo '<div class="btn-group"><button class="btn btn-info dropdown-toggle" data-toggle="dropdown">Exportar1<span class="caret"></span></button><ul class="dropdown-menu">';
$tools_list->ExportOptions->Render("body"); 
if(!ISSET($_GET['export']))echo '</ul></div> ';
*/?>
<?php } ?>
</p>
<?php $tools_list->ShowPageHeader(); ?>
<?php
$tools_list->ShowMessage();
?>
<form name="ftoolslist" id="ftoolslist" class="ewForm" action="" method="post">
<input type="hidden" name="t" value="tools" />
<div id="gmp_tools" class="ewGridMiddlePanel row-fluid">
<ul class="thumbnails">
<?php if ($tools_list->TotalRecs > 0) { ?>
<table id="tbl_toolslist" class="ewTable ewTableSeparate table table-striped table-bordered ">
<?php echo $tools->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$tools_list->RenderListOptions();

// Render list options (header, left)
$tools_list->ListOptions->Render("header", "left");
?>
<?php if ($tools->idtools->Visible) { // idtools ?>
	<?php if ($tools->SortUrl($tools->idtools) == "") { ?>
		<th><span id="elh_tools_idtools" class="tools_idtools">
		<div class="ewTableHeaderBtn"><?php echo $tools->idtools->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div onmousedown="ew_Sort(event,'<?php echo $tools->SortUrl($tools->idtools) ?>',1);"><span id="elh_tools_idtools" class="tools_idtools">
			<div class="ewTableHeaderBtn">			
			<?php echo $tools->idtools->FldCaption() ?>
			<?php if ($tools->idtools->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($tools->idtools->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($tools->target_domain->Visible) { // target_domain ?>
	<?php if ($tools->SortUrl($tools->target_domain) == "") { ?>
		<th><span id="elh_tools_target_domain" class="tools_target_domain">
		<div class="ewTableHeaderBtn"><?php echo $tools->target_domain->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div onmousedown="ew_Sort(event,'<?php echo $tools->SortUrl($tools->target_domain) ?>',1);"><span id="elh_tools_target_domain" class="tools_target_domain">
			<div class="ewTableHeaderBtn">			
			<?php echo $tools->target_domain->FldCaption() ?>
			<?php if ($tools->target_domain->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($tools->target_domain->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($tools->type->Visible) { // type ?>
	<?php if ($tools->SortUrl($tools->type) == "") { ?>
		<th><span id="elh_tools_type" class="tools_type">
		<div class="ewTableHeaderBtn"><?php echo $tools->type->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div onmousedown="ew_Sort(event,'<?php echo $tools->SortUrl($tools->type) ?>',1);"><span id="elh_tools_type" class="tools_type">
			<div class="ewTableHeaderBtn">			
			<?php echo $tools->type->FldCaption() ?>
			<?php if ($tools->type->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($tools->type->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($tools->status->Visible) { // status ?>
	<?php if ($tools->SortUrl($tools->status) == "") { ?>
		<th><span id="elh_tools_status" class="tools_status">
		<div class="ewTableHeaderBtn"><?php echo $tools->status->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div onmousedown="ew_Sort(event,'<?php echo $tools->SortUrl($tools->status) ?>',1);"><span id="elh_tools_status" class="tools_status">
			<div class="ewTableHeaderBtn">			
			<?php echo $tools->status->FldCaption() ?>
			<?php if ($tools->status->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($tools->status->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($tools->Descripcion->Visible) { // Descripcion ?>
	<?php if ($tools->SortUrl($tools->Descripcion) == "") { ?>
		<th><span id="elh_tools_Descripcion" class="tools_Descripcion">
		<div class="ewTableHeaderBtn"><?php echo $tools->Descripcion->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div onmousedown="ew_Sort(event,'<?php echo $tools->SortUrl($tools->Descripcion) ?>',1);"><span id="elh_tools_Descripcion" class="tools_Descripcion">
			<div class="ewTableHeaderBtn">			
			<?php echo $tools->Descripcion->FldCaption() ?>
			<?php if ($tools->Descripcion->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($tools->Descripcion->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($tools->tags->Visible) { // tags ?>
	<?php if ($tools->SortUrl($tools->tags) == "") { ?>
		<th><span id="elh_tools_tags" class="tools_tags">
		<div class="ewTableHeaderBtn"><?php echo $tools->tags->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div onmousedown="ew_Sort(event,'<?php echo $tools->SortUrl($tools->tags) ?>',1);"><span id="elh_tools_tags" class="tools_tags">
			<div class="ewTableHeaderBtn">			
			<?php echo $tools->tags->FldCaption() ?>
			<?php if ($tools->tags->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($tools->tags->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$tools_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($tools->ExportAll && $tools->Export <> "") {
	$tools_list->StopRec = $tools_list->TotalRecs;
} else {

	// Set the last record to display
	if ($tools_list->TotalRecs > $tools_list->StartRec + $tools_list->DisplayRecs - 1)
		$tools_list->StopRec = $tools_list->StartRec + $tools_list->DisplayRecs - 1;
	else
		$tools_list->StopRec = $tools_list->TotalRecs;
}
$tools_list->RecCnt = $tools_list->StartRec - 1;
if ($tools_list->Recordset && !$tools_list->Recordset->EOF) {
	$tools_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $tools_list->StartRec > 1)
		$tools_list->Recordset->Move($tools_list->StartRec - 1);
} elseif (!$tools->AllowAddDeleteRow && $tools_list->StopRec == 0) {
	$tools_list->StopRec = $tools->GridAddRowCount;
}

// Initialize aggregate
$tools->RowType = EW_ROWTYPE_AGGREGATEINIT;
$tools->ResetAttrs();
$tools_list->RenderRow();
while ($tools_list->RecCnt < $tools_list->StopRec) {
	$tools_list->RecCnt++;
	if (intval($tools_list->RecCnt) >= intval($tools_list->StartRec)) {
		$tools_list->RowCnt++;

		// Set up key count
		$tools_list->KeyCount = $tools_list->RowIndex;

		// Init row class and style
		$tools->ResetAttrs();
		$tools->CssClass = "";
		if ($tools->CurrentAction == "gridadd") {
		} else {
			$tools_list->LoadRowValues($tools_list->Recordset); // Load row values
		}
		$tools->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$tools->RowAttrs = array_merge($tools->RowAttrs, array('data-rowindex'=>$tools_list->RowCnt, 'id'=>'r' . $tools_list->RowCnt . '_tools', 'data-rowtype'=>$tools->RowType));

		// Render row
		$tools_list->RenderRow();

		// Render list options
		$tools_list->RenderListOptions();
?>
	<tr<?php echo $tools->RowAttributes() ?>>
<?php

// Render list options (body, left)
$tools_list->ListOptions->Render("body", "left", $tools_list->RowCnt);
?>
	<?php if ($tools->idtools->Visible) { // idtools ?>
		<td<?php echo $tools->idtools->CellAttributes() ?>><span id="el<?php echo $tools_list->RowCnt ?>_tools_idtools" class="tools_idtools">
<div id="orig<?php echo $tools_list->RowCnt ?>_tools_idtools" class="ewDisplayNone">
<span<?php echo $tools->idtools->ViewAttributes() ?>>
<?php echo $tools->idtools->ListViewValue() ?></span>
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
	<?php } ?>
<a id="<?php echo $tools_list->PageObjName . "_row_" . $tools_list->RowCnt ?>"></a>
	<?php if ($tools->target_domain->Visible) { // target_domain ?>
		<td<?php echo $tools->target_domain->CellAttributes() ?>><span id="el<?php echo $tools_list->RowCnt ?>_tools_target_domain" class="tools_target_domain">
<span<?php echo $tools->target_domain->ViewAttributes() ?>>
<?php echo $tools->target_domain->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($tools->type->Visible) { // type ?>
		<td<?php echo $tools->type->CellAttributes() ?>><span id="el<?php echo $tools_list->RowCnt ?>_tools_type" class="tools_type">
<span<?php echo $tools->type->ViewAttributes() ?>>
<?php echo $tools->type->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($tools->status->Visible) { // status ?>
		<td<?php echo $tools->status->CellAttributes() ?>><span id="el<?php echo $tools_list->RowCnt ?>_tools_status" class="tools_status">
<span<?php echo $tools->status->ViewAttributes() ?>>
<?php echo $tools->status->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($tools->Descripcion->Visible) { // Descripcion ?>
		<td<?php echo $tools->Descripcion->CellAttributes() ?>><span id="el<?php echo $tools_list->RowCnt ?>_tools_Descripcion" class="tools_Descripcion">
<span<?php echo $tools->Descripcion->ViewAttributes() ?>>
<?php echo $tools->Descripcion->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($tools->tags->Visible) { // tags ?>
		<td<?php echo $tools->tags->CellAttributes() ?>><span id="el<?php echo $tools_list->RowCnt ?>_tools_tags" class="tools_tags">
<span<?php echo $tools->tags->ViewAttributes() ?>>
<?php echo $tools->tags->ListViewValue() ?></span>
</span></td>
	<?php } ?>
<?php

// Render list options (body, right)
$tools_list->ListOptions->Render("body", "right", $tools_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($tools->CurrentAction <> "gridadd")
		$tools_list->Recordset->MoveNext();
}
?>
</tbody>
<!--</table>-->
<?php } ?>
<?php if ($tools->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</form>
<?php

// Close recordset
if ($tools_list->Recordset)
	$tools_list->Recordset->Close();
?>
<div class="ewGridLowerPanel">
<?php if ($tools->CurrentAction <> "gridadd" && $tools->CurrentAction <> "gridedit") { ?>
<div>
<form name="ewpagerform" id="ewpagerform" class="ewForm" action="<?php echo ew_CurrentPage() ?>">
<div id="paginador" class="pagination pull-right">
<?php if (!isset($tools_list->Pager)) $tools_list->Pager = new cNumericPager($tools_list->StartRec, $tools_list->DisplayRecs, $tools_list->TotalRecs, $tools_list->RecRange) ?>
<?php if ($tools_list->Pager->RecordCount > 0) { ?>
	<ul><?php if ($tools_list->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $tools_list->PageUrl() ?>start=<?php echo $tools_list->Pager->FirstButton->Start ?>"><b><?php echo $Language->Phrase("PagerFirst") ?></b></a></li>
	<?php } ?>
	<?php if ($tools_list->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $tools_list->PageUrl() ?>start=<?php echo $tools_list->Pager->PrevButton->Start ?>"><b><?php echo $Language->Phrase("PagerPrevious") ?></b></a></li>
	<?php } ?>
	<?php foreach ($tools_list->Pager->Items as $PagerItem) { //jamesjara ?>
		<?php $classs=""; if (!$PagerItem->Enabled) $classs = 'class="active"';

		//jamesjara if ($PagerItem->Enabled) { ?>
			<li <?php echo $classs; ?>><a href="<?php echo $tools_list->PageUrl() ?>start=<?php echo $PagerItem->Start ?>">
		<?php //jamesjara } ?>
			<b><?php echo $PagerItem->Text ?></b>
		<?php //jamesjara if ($PagerItem->Enabled) { ?> 
			</a></li><?php //jamesjara } 
		?>
	<?php } ?>
	<?php if ($tools_list->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $tools_list->PageUrl() ?>start=<?php echo $tools_list->Pager->NextButton->Start ?>"><b><?php echo $Language->Phrase("PagerNext") ?></b></a></li>
	<?php } ?>
	<?php if ($tools_list->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $tools_list->PageUrl() ?>start=<?php echo $tools_list->Pager->LastButton->Start ?>"><b><?php echo $Language->Phrase("PagerLast") ?></b></a></li>
	<?php } ?>
	</ul>
	<?php if ($tools_list->Pager->ButtonCount > 0) { ?><?php } ?>
	<div style=" margin-top: 1px; " class="pull-right"><span class="label label-info"><i class="icon-ok-sign icon-white"></i> <?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $tools_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $tools_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $tools_list->Pager->RecordCount ?></span></div>
<?php } else { ?>	
	<?php if ($Security->CanList()) { ?>
	<?php if ($tools_list->SearchWhere == "0=101") { ?>
	<?php echo $Language->Phrase("EnterSearchCriteria") ?>
	<?php } else { ?>
	<?php echo $Language->Phrase("NoRecord") ?>
	<?php } ?>
	<?php } else { ?>
	<?php echo $Language->Phrase("NoPermission") ?>
	<?php } ?>
<?php } ?>
</div>
</form>
<div>
<?php } ?>
<?php if ($Security->CanAdd()) { ?>
<?php if ($tools_list->AddUrl <> "") { ?>
<a class="ewGridLink btn btn-success" href="<?php echo $tools_list->AddUrl ?>"><?php echo $Language->Phrase("AddLink") ?></a>
<?php } ?>
<?php } ?>
</div>
<script type="text/javascript">
ftoolslist.Init();
</script>
<?php
$tools_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$tools_list->Page_Terminate();
?>
