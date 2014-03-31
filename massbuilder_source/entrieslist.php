<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg9.php" ?>
<?php include_once "ewmysql9.php" ?>
<?php include_once "phpfn9.php" ?>
<?php include_once "entriesinfo.php" ?>
<?php include_once "domainsinfo.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "userfn9.php" ?>
<?php

//
// Page class
//

$entries_list = NULL; // Initialize page object first

class centries_list extends centries {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{3554BCCA-7E88-4E52-9661-DF55D75275C9}";

	// Table name
	var $TableName = 'entries';

	// Page object name
	var $PageObjName = 'entries_list';

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

		// Table object (entries)
		if (!isset($GLOBALS["entries"])) {
			$GLOBALS["entries"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["entries"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = $this->james_url("entriesadd.php");
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "entriesdelete.php";
		$this->MultiUpdateUrl = "entriesupdate.php";

		// Table object (domains)
		if (!isset($GLOBALS['domains'])) $GLOBALS['domains'] = new cdomains();

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'entries', TRUE);

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

		// Create form object
		$objForm = new cFormObj();

		// Get grid add count
		$gridaddcnt = @$_GET[EW_TABLE_GRID_ADD_ROW_COUNT];
		if (is_numeric($gridaddcnt) && $gridaddcnt > 0)
			$this->GridAddRowCount = $gridaddcnt;

		// Set up list options
		$this->SetupListOptions();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"];
		$this->identries->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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

			// Check QueryString parameters
			if (@$_GET["a"] <> "") {
				$this->CurrentAction = $_GET["a"];

				// Clear inline mode
				if ($this->CurrentAction == "cancel")
					$this->ClearInlineMode();

				// Switch to inline add mode
				if ($this->CurrentAction == "add" || $this->CurrentAction == "copy")
					$this->InlineAddMode();
			} else {
				if (@$_POST["a_list"] <> "") {
					$this->CurrentAction = $_POST["a_list"]; // Get action

					// Insert Inline
					if ($this->CurrentAction == "insert" && @$_SESSION[EW_SESSION_INLINE_MODE] == "add")
						$this->InlineInsert();
				}
			}

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

	//  Exit inline mode
	function ClearInlineMode() {
		$this->LastAction = $this->CurrentAction; // Save last action
		$this->CurrentAction = ""; // Clear action
		$_SESSION[EW_SESSION_INLINE_MODE] = ""; // Clear inline mode
	}

	// Switch to Inline Add mode
	function InlineAddMode() {
		global $Security, $Language;
		if (!$Security->CanAdd())
			$this->Page_Terminate("login.php"); // Return to login page
		if ($this->CurrentAction == "copy") {
			if (@$_GET["identries"] <> "") {
				$this->identries->setQueryStringValue($_GET["identries"]);
				$this->setKey("identries", $this->identries->CurrentValue); // Set up key
			} else {
				$this->setKey("identries", ""); // Clear key
				$this->CurrentAction = "add";
			}
			if (@$_GET["id"] <> "") {
				$this->id->setQueryStringValue($_GET["id"]);
				$this->setKey("id", $this->id->CurrentValue); // Set up key
			} else {
				$this->setKey("id", ""); // Clear key
				$this->CurrentAction = "add";
			}
		}
		$_SESSION[EW_SESSION_INLINE_MODE] = "add"; // Enable inline add
	}

	// Perform update to Inline Add/Copy record
	function InlineInsert() {
		global $Language, $objForm, $gsFormError;
		$this->LoadOldRecord(); // Load old recordset
		$objForm->Index = 0;
		$this->LoadFormValues(); // Get form values

		// Validate form
		if (!$this->ValidateForm()) {
			$this->setFailureMessage($gsFormError); // Set validation error message
			$this->EventCancelled = TRUE; // Set event cancelled
			$this->CurrentAction = "add"; // Stay in add mode
			return;
		}
		$this->SendEmail = TRUE; // Send email on add success
		if ($this->AddRow($this->OldRecordset)) { // Add record
			if ($this->getSuccessMessage() == "")
				$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up add success message
			$this->ClearInlineMode(); // Clear inline add mode
		} else { // Add failed
			$this->EventCancelled = TRUE; // Set event cancelled
			$this->CurrentAction = "add"; // Stay in add mode
		}
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
		if (count($arrKeyFlds) >= 2) {
			$this->identries->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->identries->FormValue))
				return FALSE;
			$this->id->setFormValue($arrKeyFlds[1]);
			if (!is_numeric($this->id->FormValue))
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
			$this->UpdateSort($this->identries); // identries
			$this->UpdateSort($this->titulo); // titulo
			$this->UpdateSort($this->id); // id
			$this->UpdateSort($this->islive); // islive
			$this->UpdateSort($this->tool_id); // tool_id
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

			// Reset master/detail keys
			if ($this->Command == "resetall") {
				$this->setCurrentMasterTable(""); // Clear master table
				$this->DbMasterFilter = "";
				$this->DbDetailFilter = "";
				$this->domain_id->setSessionValue("");
			}

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->identries->setSort("");
				$this->titulo->setSort("");
				$this->id->setSort("");
				$this->islive->setSort("");
				$this->tool_id->setSort("");
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

		// "delete"
		$item = &$this->ListOptions->Add("delete");
		$item->CssStyle = "white-spaceJAMES: nowrapJAMES;";
		$item->Visible = $Security->CanDelete();
		$item->OnLeft = FALSE;

		// Call ListOptions_Load event
		$this->ListOptions_Load();
	}

	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $objForm;
		$this->ListOptions->LoadDefault();

		// Set up row action and key
		if (is_numeric($this->RowIndex)) {
			$objForm->Index = $this->RowIndex;
			if ($this->RowAction <> "")
				$this->MultiSelectKey .= "<input type=\"hidden\" name=\"k" . $this->RowIndex . "_action\" id=\"k" . $this->RowIndex . "_action\" value=\"" . $this->RowAction . "\" />";
			if ($this->RowAction == "delete") {
				$rowkey = $objForm->GetValue("k_key");
				$this->SetupKeyValues($rowkey);
			}
			if ($this->RowAction == "insert" && $this->CurrentAction == "F" && $this->EmptyRow())
				$this->MultiSelectKey .= "<input type=\"hidden\" name=\"k" . $this->RowIndex . "_blankrow\" id=\"k" . $this->RowIndex . "_blankrow\" value=\"1\">";
		}

		// "copy"
		$oListOpt = &$this->ListOptions->Items["copy"];
		if (($this->CurrentAction == "add" || $this->CurrentAction == "copy") && $this->RowType == EW_ROWTYPE_ADD) { // Inline Add/Copy
			$this->ListOptions->CustomItem = "copy"; // Show copy column only
			$oListOpt->Body = "<div" . (($oListOpt->OnLeft) ? " style=\"text-align: right\"" : "") . ">" .
				"<a class=\"ewGridLink btn btn-success\" href=\"\" onclick=\"return ewForms['fentrieslist'].Submit();\">" . $Language->Phrase("InsertLink") . "</a>" .
				"<a class=\"ewGridLink btn btn-success\" href=\"" . $this->PageUrl() . "a=cancel\">" . $Language->Phrase("CancelLink") . "</a>" .
				"<input type=\"hidden\" name=\"a_list\" id=\"a_list\" value=\"insert\" /></div>";
			return;
		}

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

		// "delete"
		$oListOpt = &$this->ListOptions->Items["delete"];
		if ($Security->CanDelete())
			$oListOpt->Body = "<a class=\"ewRowLink  label label-important\"" . "" . " href=\"" . $this->DeleteUrl . "\">" . $Language->Phrase("DeleteLink") . "</a>";
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

	// Load default values
	function LoadDefaultValues() {
		$this->identries->CurrentValue = NULL;
		$this->identries->OldValue = $this->identries->CurrentValue;
		$this->titulo->CurrentValue = NULL;
		$this->titulo->OldValue = $this->titulo->CurrentValue;
		$this->id->CurrentValue = NULL;
		$this->id->OldValue = $this->id->CurrentValue;
		$this->islive->CurrentValue = NULL;
		$this->islive->OldValue = $this->islive->CurrentValue;
		$this->tool_id->CurrentValue = NULL;
		$this->tool_id->OldValue = $this->tool_id->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->identries->FldIsDetailKey && $this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->identries->setFormValue($objForm->GetValue("x_identries"));
		if (!$this->titulo->FldIsDetailKey) {
			$this->titulo->setFormValue($objForm->GetValue("x_titulo"));
		}
		if (!$this->id->FldIsDetailKey) {
			$this->id->setFormValue($objForm->GetValue("x_id"));
		}
		if (!$this->islive->FldIsDetailKey) {
			$this->islive->setFormValue($objForm->GetValue("x_islive"));
		}
		if (!$this->tool_id->FldIsDetailKey) {
			$this->tool_id->setFormValue($objForm->GetValue("x_tool_id"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		if ($this->CurrentAction <> "gridadd" && $this->CurrentAction <> "add")
			$this->identries->CurrentValue = $this->identries->FormValue;
		$this->titulo->CurrentValue = $this->titulo->FormValue;
		$this->id->CurrentValue = $this->id->FormValue;
		$this->islive->CurrentValue = $this->islive->FormValue;
		$this->tool_id->CurrentValue = $this->tool_id->FormValue;
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
		$this->identries->setDbValue($rs->fields('identries'));
		$this->domain_id->setDbValue($rs->fields('domain_id'));
		$this->hash_content->setDbValue($rs->fields('hash_content'));
		$this->fuente->setDbValue($rs->fields('fuente'));
		$this->published->setDbValue($rs->fields('published'));
		$this->updated->setDbValue($rs->fields('updated'));
		$this->categorias->setDbValue($rs->fields('categorias'));
		$this->titulo->setDbValue($rs->fields('titulo'));
		$this->contenido->setDbValue($rs->fields('contenido'));
		$this->id->setDbValue($rs->fields('id'));
		$this->islive->setDbValue($rs->fields('islive'));
		$this->thumbnail->setDbValue($rs->fields('thumbnail'));
		$this->reqdate->setDbValue($rs->fields('reqdate'));
		$this->author->setDbValue($rs->fields('author'));
		$this->trans_en->setDbValue($rs->fields('trans_en'));
		$this->trans_es->setDbValue($rs->fields('trans_es'));
		$this->trans_fr->setDbValue($rs->fields('trans_fr'));
		$this->trans_it->setDbValue($rs->fields('trans_it'));
		$this->fid->setDbValue($rs->fields('fid'));
		$this->fmd5->setDbValue($rs->fields('fmd5'));
		$this->tool_id->setDbValue($rs->fields('tool_id'));
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("identries")) <> "")
			$this->identries->CurrentValue = $this->getKey("identries"); // identries
		else
			$bValidKey = FALSE;
		if (strval($this->getKey("id")) <> "")
			$this->id->CurrentValue = $this->getKey("id"); // id
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
		// identries
		// domain_id
		// hash_content
		// fuente
		// published
		// updated
		// categorias
		// titulo
		// contenido
		// id
		// islive
		// thumbnail
		// reqdate
		// author
		// trans_en
		// trans_es
		// trans_fr
		// trans_it
		// fid
		// fmd5
		// tool_id

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// identries
			$this->identries->ViewValue = $this->identries->CurrentValue;
			$this->identries->ViewCustomAttributes = "";

			// domain_id
			if (strval($this->domain_id->CurrentValue) <> "") {
				$sFilterWrk = "`id_domains`" . ew_SearchString("=", $this->domain_id->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `id_domains`, `dominio` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `domains`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->domain_id->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->domain_id->ViewValue = $this->domain_id->CurrentValue;
				}
			} else {
				$this->domain_id->ViewValue = NULL;
			}
			$this->domain_id->ViewCustomAttributes = "";

			// hash_content
			$this->hash_content->ViewValue = $this->hash_content->CurrentValue;
			$this->hash_content->ViewCustomAttributes = "";

			// fuente
			$this->fuente->ViewValue = $this->fuente->CurrentValue;
			$this->fuente->ViewCustomAttributes = "";

			// published
			$this->published->ViewValue = $this->published->CurrentValue;
			$this->published->ViewValue = ew_FormatDateTime($this->published->ViewValue, 5);
			$this->published->ViewCustomAttributes = "";

			// updated
			$this->updated->ViewValue = $this->updated->CurrentValue;
			$this->updated->ViewValue = ew_FormatDateTime($this->updated->ViewValue, 5);
			$this->updated->ViewCustomAttributes = "";

			// categorias
			$this->categorias->ViewValue = $this->categorias->CurrentValue;
			$this->categorias->ViewCustomAttributes = "";

			// titulo
			$this->titulo->ViewValue = $this->titulo->CurrentValue;
			$this->titulo->ViewCustomAttributes = "";

			// id
			$this->id->ViewValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// islive
			$this->islive->ViewValue = $this->islive->CurrentValue;
			$this->islive->ViewCustomAttributes = "";

			// thumbnail
			$this->thumbnail->ViewValue = $this->thumbnail->CurrentValue;
			$this->thumbnail->ViewCustomAttributes = "";

			// reqdate
			$this->reqdate->ViewValue = $this->reqdate->CurrentValue;
			$this->reqdate->ViewValue = ew_FormatDateTime($this->reqdate->ViewValue, 5);
			$this->reqdate->ViewCustomAttributes = "";

			// author
			$this->author->ViewValue = $this->author->CurrentValue;
			$this->author->ViewCustomAttributes = "";

			// trans_en
			$this->trans_en->ViewValue = $this->trans_en->CurrentValue;
			$this->trans_en->ViewCustomAttributes = "";

			// trans_es
			$this->trans_es->ViewValue = $this->trans_es->CurrentValue;
			$this->trans_es->ViewCustomAttributes = "";

			// trans_fr
			$this->trans_fr->ViewValue = $this->trans_fr->CurrentValue;
			$this->trans_fr->ViewCustomAttributes = "";

			// trans_it
			$this->trans_it->ViewValue = $this->trans_it->CurrentValue;
			$this->trans_it->ViewCustomAttributes = "";

			// fid
			$this->fid->ViewValue = $this->fid->CurrentValue;
			$this->fid->ViewCustomAttributes = "";

			// fmd5
			$this->fmd5->ViewValue = $this->fmd5->CurrentValue;
			$this->fmd5->ViewCustomAttributes = "";

			// tool_id
			$this->tool_id->ViewValue = $this->tool_id->CurrentValue;
			$this->tool_id->ViewCustomAttributes = "";

			// identries
			$this->identries->LinkCustomAttributes = "";
			$this->identries->HrefValue = "";
			$this->identries->TooltipValue = "";

			// titulo
			$this->titulo->LinkCustomAttributes = "";
			$this->titulo->HrefValue = "";
			$this->titulo->TooltipValue = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// islive
			$this->islive->LinkCustomAttributes = "";
			$this->islive->HrefValue = "";
			$this->islive->TooltipValue = "";

			// tool_id
			$this->tool_id->LinkCustomAttributes = "";
			$this->tool_id->HrefValue = "";
			$this->tool_id->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// identries
			// titulo

			$this->titulo->EditCustomAttributes = "";
			$this->titulo->EditValue = ew_HtmlEncode($this->titulo->CurrentValue);

			// id
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = ew_HtmlEncode($this->id->CurrentValue);

			// islive
			$this->islive->EditCustomAttributes = "";
			$this->islive->EditValue = ew_HtmlEncode($this->islive->CurrentValue);

			// tool_id
			$this->tool_id->EditCustomAttributes = "";
			$this->tool_id->EditValue = ew_HtmlEncode($this->tool_id->CurrentValue);

			// Edit refer script
			// identries

			$this->identries->HrefValue = "";

			// titulo
			$this->titulo->HrefValue = "";

			// id
			$this->id->HrefValue = "";

			// islive
			$this->islive->HrefValue = "";

			// tool_id
			$this->tool_id->HrefValue = "";
		}
		if ($this->RowType == EW_ROWTYPE_ADD ||
			$this->RowType == EW_ROWTYPE_EDIT ||
			$this->RowType == EW_ROWTYPE_SEARCH) { // Add / Edit / Search row
			$this->SetupFieldTitles();
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	// Validate form
	function ValidateForm() {
		global $Language, $gsFormError;

		// Initialize form error message
		$gsFormError = "";

		// Check if validation required
		if (!EW_SERVER_VALIDATE)
			return ($gsFormError == "");
		if (!is_null($this->id->FormValue) && $this->id->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->id->FldCaption());
		}
		if (!ew_CheckInteger($this->id->FormValue)) {
			ew_AddMessage($gsFormError, $this->id->FldErrMsg());
		}

		// Return validate result
		$ValidateForm = ($gsFormError == "");

		// Call Form_CustomValidate event
		$sFormCustomError = "";
		$ValidateForm = $ValidateForm && $this->Form_CustomValidate($sFormCustomError);
		if ($sFormCustomError <> "") {
			ew_AddMessage($gsFormError, $sFormCustomError);
		}
		return $ValidateForm;
	}

	// Add record
	function AddRow($rsold = NULL) {
		global $conn, $Language, $Security;
		$rsnew = array();

		// titulo
		$this->titulo->SetDbValueDef($rsnew, $this->titulo->CurrentValue, NULL, FALSE);

		// id
		$this->id->SetDbValueDef($rsnew, $this->id->CurrentValue, 0, FALSE);

		// islive
		$this->islive->SetDbValueDef($rsnew, $this->islive->CurrentValue, NULL, FALSE);

		// tool_id
		$this->tool_id->SetDbValueDef($rsnew, $this->tool_id->CurrentValue, NULL, FALSE);

		// domain_id
		if ($this->domain_id->getSessionValue() <> "") {
			$rsnew['domain_id'] = $this->domain_id->getSessionValue();
		}

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && $this->id->CurrentValue == "" && $this->id->getSessionValue() == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
		}
		if ($bInsertRow) {
			$conn->raiseErrorFn = 'ew_ErrorFn';
			$AddRow = $this->Insert($rsnew);
			$conn->raiseErrorFn = '';
			if ($AddRow) {
			}
		} else {
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("InsertCancelled"));
			}
			$AddRow = FALSE;
		}

		// Get insert id if necessary
		if ($AddRow) {
			$this->identries->setDbValue($conn->Insert_ID());
			$rsnew['identries'] = $this->identries->DbValue;
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);
		}
		return $AddRow;
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
					$this->domain_id->setQueryStringValue($GLOBALS["domains"]->id_domains->QueryStringValue);
					$this->domain_id->setSessionValue($this->domain_id->QueryStringValue);
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
				if ($this->domain_id->QueryStringValue == "") $this->domain_id->setSessionValue("");
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
if (!isset($entries_list)) $entries_list = new centries_list();

// Page init
$entries_list->Page_Init();

// Page main
$entries_list->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var entries_list = new ew_Page("entries_list");
entries_list.PageID = "list"; // Page ID
var EW_PAGE_ID = entries_list.PageID; // For backward compatibility

// Form object
var fentrieslist = new ew_Form("fentrieslist");

// Validate form
fentrieslist.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	this.PostAutoSuggest();	
	if (fobj.a_confirm && fobj.a_confirm.value == "F")
		return true;
	var elm, aelm;
	var rowcnt = (fobj.key_count) ? Number(fobj.key_count.value) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // rowcnt == 0 => Inline-Add
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = (fobj.key_count) ? String(i) : "";
		elm = fobj.elements["x" + infix + "_id"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($entries->id->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_id"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($entries->id->FldErrMsg()) ?>");

		// Set up row object
		ew_ElementsToRow(fobj, infix);

		// Fire Form_CustomValidate event
		if (!this.Form_CustomValidate(fobj))
			return false;
	}
	return true;
}

// Form_CustomValidate event
fentrieslist.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fentrieslist.ValidateRequired = true;
<?php } else { ?>
fentrieslist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (($entries->Export == "") || (EW_EXPORT_MASTER_RECORD && $entries->Export == "print")) { ?>
<?php
$gsMasterReturnUrl = "domainslist.php";
if ($entries_list->DbMasterFilter <> "" && $entries->getCurrentMasterTable() == "domains") {
	if ($entries_list->MasterRecordExists) {
		if ($entries->getCurrentMasterTable() == $entries->TableVar) $gsMasterReturnUrl .= "?" . EW_TABLE_SHOW_MASTER . "=";
?>
<span class="ewTitle ewMasterTableTitle"><i class="icon-resize-small"></i> <?php echo $Language->Phrase("MasterRecord") ?><?php echo $domains->TableCaption() ?>&nbsp;&nbsp;</span>
<?php /* jamesjara
if(!ISSET($_GET['export']))echo '<div class="btn-group"><button class="btn btn-info dropdown-toggle" data-toggle="dropdown">Exportar2<span class="caret"></span></button><ul class="dropdown-menu">';
$entries_list->ExportOptions->Render("body"); 
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
		$entries_list->TotalRecs = $entries->SelectRecordCount();
	} else {
		if ($entries_list->Recordset = $entries_list->LoadRecordset())
			$entries_list->TotalRecs = $entries_list->Recordset->RecordCount();
	}
	$entries_list->StartRec = 1;
	if ($entries_list->DisplayRecs <= 0 || ($entries->Export <> "" && $entries->ExportAll)) // Display all records
		$entries_list->DisplayRecs = $entries_list->TotalRecs;
	if (!($entries->Export <> "" && $entries->ExportAll))
		$entries_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$entries_list->Recordset = $entries_list->LoadRecordset($entries_list->StartRec-1, $entries_list->DisplayRecs);
?>
<p style="white-spaceJAMES: nowrapJAMES;"><h4 id="ewPageCaption" class="page-header ewTitle ewTableTitle"> <?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $entries->TableCaption() ?>&nbsp;&nbsp;</h4>
<?php if ($entries->getCurrentMasterTable() == "") {  ?>
<?php /* jamesjara
if(!ISSET($_GET['export']))echo '<div class="btn-group"><button class="btn btn-info dropdown-toggle" data-toggle="dropdown">Exportar1<span class="caret"></span></button><ul class="dropdown-menu">';
$entries_list->ExportOptions->Render("body"); 
if(!ISSET($_GET['export']))echo '</ul></div> ';
*/?>
<?php } ?>
</p>
<?php $entries_list->ShowPageHeader(); ?>
<?php
$entries_list->ShowMessage();
?>
<div class="ewGridUpperPanel">
<?php if ($entries->CurrentAction <> "gridadd" && $entries->CurrentAction <> "gridedit") { ?>
<div>
<form name="ewpagerform" id="ewpagerform" class="ewForm" action="<?php echo ew_CurrentPage() ?>">
<div id="paginador" class="pagination pull-right">
<?php if (!isset($entries_list->Pager)) $entries_list->Pager = new cNumericPager($entries_list->StartRec, $entries_list->DisplayRecs, $entries_list->TotalRecs, $entries_list->RecRange) ?>
<?php if ($entries_list->Pager->RecordCount > 0) { ?>
	<ul><?php if ($entries_list->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $entries_list->PageUrl() ?>start=<?php echo $entries_list->Pager->FirstButton->Start ?>"><b><?php echo $Language->Phrase("PagerFirst") ?></b></a></li>
	<?php } ?>
	<?php if ($entries_list->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $entries_list->PageUrl() ?>start=<?php echo $entries_list->Pager->PrevButton->Start ?>"><b><?php echo $Language->Phrase("PagerPrevious") ?></b></a></li>
	<?php } ?>
	<?php foreach ($entries_list->Pager->Items as $PagerItem) { //jamesjara ?>
		<?php $classs=""; if (!$PagerItem->Enabled) $classs = 'class="active"';

		//jamesjara if ($PagerItem->Enabled) { ?>
			<li <?php echo $classs; ?>><a href="<?php echo $entries_list->PageUrl() ?>start=<?php echo $PagerItem->Start ?>">
		<?php //jamesjara } ?>
			<b><?php echo $PagerItem->Text ?></b>
		<?php //jamesjara if ($PagerItem->Enabled) { ?> 
			</a></li><?php //jamesjara } 
		?>
	<?php } ?>
	<?php if ($entries_list->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $entries_list->PageUrl() ?>start=<?php echo $entries_list->Pager->NextButton->Start ?>"><b><?php echo $Language->Phrase("PagerNext") ?></b></a></li>
	<?php } ?>
	<?php if ($entries_list->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $entries_list->PageUrl() ?>start=<?php echo $entries_list->Pager->LastButton->Start ?>"><b><?php echo $Language->Phrase("PagerLast") ?></b></a></li>
	<?php } ?>
	</ul>
	<?php if ($entries_list->Pager->ButtonCount > 0) { ?><?php } ?>
	<div style=" margin-top: 1px; " class="pull-right"><span class="label label-info"><i class="icon-ok-sign icon-white"></i> <?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $entries_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $entries_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $entries_list->Pager->RecordCount ?></span></div>
<?php } else { ?>	
	<?php if ($Security->CanList()) { ?>
	<?php if ($entries_list->SearchWhere == "0=101") { ?>
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
<?php //jamesjara
if(!ISSET($_GET['export'])) if ($_GET['a']!='gridadd') echo '<div class="btn-group"><button class="btn btn-info dropdown-toggle" data-toggle="dropdown"><i class="icon-share icon-white"></i> Exportar<span class="caret"></span></button><ul class="dropdown-menu">';
$entries_list->ExportOptions->Render("body"); 
if(!ISSET($_GET['export'])) if ($_GET['a']!='gridadd') echo '</ul></div> ';
?>
<?php if ($Security->CanAdd()) { ?>
<?php if ($entries_list->AddUrl <> "") { ?>
<a class="ewGridLink btn btn-success" href="<?php echo $entries_list->AddUrl ?>"><?php echo $Language->Phrase("AddLink") ?></a>
<?php } ?>
<?php } ?>
</div>
<form name="fentrieslist" id="fentrieslist" class="ewForm" action="<?php echo ew_CurrentPage() ?>" method="post"  onsubmit="return ewForms[this.id].Submit();">
<input type="hidden" name="t" value="entries" />
<div id="gmp_entries" class="ewGridMiddlePanel row-fluid">
<ul class="thumbnails">
<?php if ($entries_list->TotalRecs > 0 || $entries->CurrentAction == "add" || $entries->CurrentAction == "copy") { ?>
<table id="tbl_entrieslist" class="ewTable ewTableSeparate table table-striped table-bordered ">
<?php echo $entries->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$entries_list->RenderListOptions();

// Render list options (header, left)
$entries_list->ListOptions->Render("header", "left");
?>
<?php if ($entries->identries->Visible) { // identries ?>
	<?php if ($entries->SortUrl($entries->identries) == "") { ?>
		<th><span id="elh_entries_identries" class="entries_identries">
		<div class="ewTableHeaderBtn"><?php echo $entries->identries->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div onmousedown="ew_Sort(event,'<?php echo $entries->SortUrl($entries->identries) ?>',1);"><span id="elh_entries_identries" class="entries_identries">
			<div class="ewTableHeaderBtn">			
			<?php echo $entries->identries->FldCaption() ?>
			<?php if ($entries->identries->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($entries->identries->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($entries->titulo->Visible) { // titulo ?>
	<?php if ($entries->SortUrl($entries->titulo) == "") { ?>
		<th><span id="elh_entries_titulo" class="entries_titulo">
		<div class="ewTableHeaderBtn"><?php echo $entries->titulo->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div onmousedown="ew_Sort(event,'<?php echo $entries->SortUrl($entries->titulo) ?>',1);"><span id="elh_entries_titulo" class="entries_titulo">
			<div class="ewTableHeaderBtn">			
			<?php echo $entries->titulo->FldCaption() ?>
			<?php if ($entries->titulo->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($entries->titulo->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($entries->id->Visible) { // id ?>
	<?php if ($entries->SortUrl($entries->id) == "") { ?>
		<th><span id="elh_entries_id" class="entries_id">
		<div class="ewTableHeaderBtn"><?php echo $entries->id->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div onmousedown="ew_Sort(event,'<?php echo $entries->SortUrl($entries->id) ?>',1);"><span id="elh_entries_id" class="entries_id">
			<div class="ewTableHeaderBtn">			
			<?php echo $entries->id->FldCaption() ?>
			<?php if ($entries->id->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($entries->id->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($entries->islive->Visible) { // islive ?>
	<?php if ($entries->SortUrl($entries->islive) == "") { ?>
		<th><span id="elh_entries_islive" class="entries_islive">
		<div class="ewTableHeaderBtn"><?php echo $entries->islive->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div onmousedown="ew_Sort(event,'<?php echo $entries->SortUrl($entries->islive) ?>',1);"><span id="elh_entries_islive" class="entries_islive">
			<div class="ewTableHeaderBtn">			
			<?php echo $entries->islive->FldCaption() ?>
			<?php if ($entries->islive->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($entries->islive->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($entries->tool_id->Visible) { // tool_id ?>
	<?php if ($entries->SortUrl($entries->tool_id) == "") { ?>
		<th><span id="elh_entries_tool_id" class="entries_tool_id">
		<div class="ewTableHeaderBtn"><?php echo $entries->tool_id->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div onmousedown="ew_Sort(event,'<?php echo $entries->SortUrl($entries->tool_id) ?>',1);"><span id="elh_entries_tool_id" class="entries_tool_id">
			<div class="ewTableHeaderBtn">			
			<?php echo $entries->tool_id->FldCaption() ?>
			<?php if ($entries->tool_id->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($entries->tool_id->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$entries_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
	if ($entries->CurrentAction == "add" || $entries->CurrentAction == "copy") {
		$entries_list->RowIndex = 0;
		$entries_list->KeyCount = $entries_list->RowIndex;
		if ($entries->CurrentAction == "copy" && !$entries_list->LoadRow())
				$entries->CurrentAction = "add";
		if ($entries->CurrentAction == "add")
			$entries_list->LoadDefaultValues();
		if ($entries->EventCancelled) // Insert failed
			$entries_list->RestoreFormValues(); // Restore form values

		// Set row properties
		$entries->ResetAttrs();
		$entries->RowAttrs = array_merge($entries->RowAttrs, array('data-rowindex'=>0, 'id'=>'r0_entries', 'data-rowtype'=>EW_ROWTYPE_ADD));
		$entries->RowType = EW_ROWTYPE_ADD;

		// Render row
		$entries_list->RenderRow();

		// Render list options
		$entries_list->RenderListOptions();
		$entries_list->StartRowCnt = 0;
?>
	<tr<?php echo $entries->RowAttributes() ?>>
<?php

// Render list options (body, left)
$entries_list->ListOptions->Render("body", "left", $entries_list->RowCnt);
?>
	<?php if ($entries->identries->Visible) { // identries ?>
		<td><span2 id="el<?php echo $entries_list->RowCnt ?>_entries_identries" class="entries_identries">
<input type="hidden" name="o<?php echo $entries_list->RowIndex ?>_identries" id="o<?php echo $entries_list->RowIndex ?>_identries" value="<?php echo ew_HtmlEncode($entries->identries->OldValue) ?>" />
</span></td>
	<?php } ?>
	<?php if ($entries->titulo->Visible) { // titulo ?>
		<td><span2 id="el<?php echo $entries_list->RowCnt ?>_entries_titulo" class="entries_titulo">
<input type="text" name="x<?php echo $entries_list->RowIndex ?>_titulo" id="x<?php echo $entries_list->RowIndex ?>_titulo" size="30" maxlength="245" value="<?php echo $entries->titulo->EditValue ?>"<?php echo $entries->titulo->EditAttributes() ?> />
<input type="hidden" name="o<?php echo $entries_list->RowIndex ?>_titulo" id="o<?php echo $entries_list->RowIndex ?>_titulo" value="<?php echo ew_HtmlEncode($entries->titulo->OldValue) ?>" />
</span></td>
	<?php } ?>
	<?php if ($entries->id->Visible) { // id ?>
		<td><span2 id="el<?php echo $entries_list->RowCnt ?>_entries_id" class="entries_id">
<input type="text" name="x<?php echo $entries_list->RowIndex ?>_id" id="x<?php echo $entries_list->RowIndex ?>_id" size="30" value="<?php echo $entries->id->EditValue ?>"<?php echo $entries->id->EditAttributes() ?> />
<input type="hidden" name="o<?php echo $entries_list->RowIndex ?>_id" id="o<?php echo $entries_list->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($entries->id->OldValue) ?>" />
</span></td>
	<?php } ?>
	<?php if ($entries->islive->Visible) { // islive ?>
		<td><span2 id="el<?php echo $entries_list->RowCnt ?>_entries_islive" class="entries_islive">
<input type="text" name="x<?php echo $entries_list->RowIndex ?>_islive" id="x<?php echo $entries_list->RowIndex ?>_islive" size="30" maxlength="245" value="<?php echo $entries->islive->EditValue ?>"<?php echo $entries->islive->EditAttributes() ?> />
<input type="hidden" name="o<?php echo $entries_list->RowIndex ?>_islive" id="o<?php echo $entries_list->RowIndex ?>_islive" value="<?php echo ew_HtmlEncode($entries->islive->OldValue) ?>" />
</span></td>
	<?php } ?>
	<?php if ($entries->tool_id->Visible) { // tool_id ?>
		<td><span2 id="el<?php echo $entries_list->RowCnt ?>_entries_tool_id" class="entries_tool_id">
<input type="text" name="x<?php echo $entries_list->RowIndex ?>_tool_id" id="x<?php echo $entries_list->RowIndex ?>_tool_id" size="30" maxlength="235" value="<?php echo $entries->tool_id->EditValue ?>"<?php echo $entries->tool_id->EditAttributes() ?> />
<input type="hidden" name="o<?php echo $entries_list->RowIndex ?>_tool_id" id="o<?php echo $entries_list->RowIndex ?>_tool_id" value="<?php echo ew_HtmlEncode($entries->tool_id->OldValue) ?>" />
</span></td>
	<?php } ?>
<?php

// Render list options (body, right)
$entries_list->ListOptions->Render("body", "right", $entries_list->RowCnt);
?>
<script type="text/javascript">
fentrieslist.UpdateOpts(<?php echo $entries_list->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
<?php
if ($entries->ExportAll && $entries->Export <> "") {
	$entries_list->StopRec = $entries_list->TotalRecs;
} else {

	// Set the last record to display
	if ($entries_list->TotalRecs > $entries_list->StartRec + $entries_list->DisplayRecs - 1)
		$entries_list->StopRec = $entries_list->StartRec + $entries_list->DisplayRecs - 1;
	else
		$entries_list->StopRec = $entries_list->TotalRecs;
}

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue("key_count") && ($entries->CurrentAction == "gridadd" || $entries->CurrentAction == "gridedit" || $entries->CurrentAction == "F")) {
		$entries_list->KeyCount = $objForm->GetValue("key_count");
		$entries_list->StopRec = $entries_list->KeyCount;
	}
}
$entries_list->RecCnt = $entries_list->StartRec - 1;
if ($entries_list->Recordset && !$entries_list->Recordset->EOF) {
	$entries_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $entries_list->StartRec > 1)
		$entries_list->Recordset->Move($entries_list->StartRec - 1);
} elseif (!$entries->AllowAddDeleteRow && $entries_list->StopRec == 0) {
	$entries_list->StopRec = $entries->GridAddRowCount;
}

// Initialize aggregate
$entries->RowType = EW_ROWTYPE_AGGREGATEINIT;
$entries->ResetAttrs();
$entries_list->RenderRow();
while ($entries_list->RecCnt < $entries_list->StopRec) {
	$entries_list->RecCnt++;
	if (intval($entries_list->RecCnt) >= intval($entries_list->StartRec)) {
		$entries_list->RowCnt++;

		// Set up key count
		$entries_list->KeyCount = $entries_list->RowIndex;

		// Init row class and style
		$entries->ResetAttrs();
		$entries->CssClass = "";
		if ($entries->CurrentAction == "gridadd") {
			$entries_list->LoadDefaultValues(); // Load default values
		} else {
			$entries_list->LoadRowValues($entries_list->Recordset); // Load row values
		}
		$entries->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$entries->RowAttrs = array_merge($entries->RowAttrs, array('data-rowindex'=>$entries_list->RowCnt, 'id'=>'r' . $entries_list->RowCnt . '_entries', 'data-rowtype'=>$entries->RowType));

		// Render row
		$entries_list->RenderRow();

		// Render list options
		$entries_list->RenderListOptions();
?>
	<tr<?php echo $entries->RowAttributes() ?>>
<?php

// Render list options (body, left)
$entries_list->ListOptions->Render("body", "left", $entries_list->RowCnt);
?>
	<?php if ($entries->identries->Visible) { // identries ?>
		<td<?php echo $entries->identries->CellAttributes() ?>><span id="el<?php echo $entries_list->RowCnt ?>_entries_identries" class="entries_identries">
<div id="orig<?php echo $entries_list->RowCnt ?>_entries_identries" class="ewDisplayNone">
<span<?php echo $entries->identries->ViewAttributes() ?>>
<?php echo $entries->identries->ListViewValue() ?></span>
</div>
<?php    
       
if( BASENAME($_SERVER['PHP_SELF']) == 'entrieslist.php' ){   // solo si es entrieslist.php
   $data  = '<a class="operacion btn btn-success" name="ppt" id="'.CurrentTable()->identries->CurrentValue.'" href="#">ppt</a> ';  
   $data .= '<a class="operacion btn btn-primary" name="pdf" id="'.CurrentTable()->identries->CurrentValue.'" href="#">pdf</a> ';  
   $data .= '<a class="operacion btn btn-primary" name="mp4" id="'.CurrentTable()->identries->CurrentValue.'" href="#">mp4</a> ';    
   $data .= '<a class="operacion btn btn-primary" name="imagen" id="'.CurrentTable()->identries->CurrentValue.'" href="#">imagen</a> ';    
   echo $data;                                                                                                          
} else    {
   //$data  = '<a class="operacion btn btn-success" name="BackUp"    id="'.$_GET['id_domains'].'" href="#">BackUp</a> ';  
   //$data .= '<a class="operacion btn btn-primary" name="Traducion" id="'.$_GET['id_domains'].'" href="#">Traducion</a>';    
   //echo $data;          
} 
?>                  
<?php if (debug01) echo CurrentTable()->identries->CurrentValue;   ?>   
<script>   
/*  NO SE VA A USAR
Todo : mostrar solo si esta con showmaster
var ewpagerform  =  $("#ewpagerformTOOLS");
if ($("#ewpagerformTOOLS").length == 0) {        
        tools = '<a class="operacion btn btn-success" name="push_entries" id="<?php CurrentTable()->id_domains->CurrentValue ?>" href="#">Actualizar Blog</a>';
        tools += ' <a class="operacion btn btn-success" name="get_entries" id="<?php CurrentTable()->id_domains->CurrentValue ?>" href="#">Obtener Nuevas</a>';
        <?php          
        if( isset($_GET['showmaster']) ){
            echo " ewpagerformTOOLS = $('<div id=\"ewpagerformTOOLS\">'+tools+'</div>').appendTo('#ewpagerform');"; 
            }
        ?>        
}         
*/                  
</script>
</span></td>
	<?php } ?>
<a id="<?php echo $entries_list->PageObjName . "_row_" . $entries_list->RowCnt ?>"></a>
	<?php if ($entries->titulo->Visible) { // titulo ?>
		<td<?php echo $entries->titulo->CellAttributes() ?>><span id="el<?php echo $entries_list->RowCnt ?>_entries_titulo" class="entries_titulo">
<span<?php echo $entries->titulo->ViewAttributes() ?>>
<?php echo $entries->titulo->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($entries->id->Visible) { // id ?>
		<td<?php echo $entries->id->CellAttributes() ?>><span id="el<?php echo $entries_list->RowCnt ?>_entries_id" class="entries_id">
<span<?php echo $entries->id->ViewAttributes() ?>>
<?php echo $entries->id->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($entries->islive->Visible) { // islive ?>
		<td<?php echo $entries->islive->CellAttributes() ?>><span id="el<?php echo $entries_list->RowCnt ?>_entries_islive" class="entries_islive">
<span<?php echo $entries->islive->ViewAttributes() ?>>
<?php echo $entries->islive->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($entries->tool_id->Visible) { // tool_id ?>
		<td<?php echo $entries->tool_id->CellAttributes() ?>><span id="el<?php echo $entries_list->RowCnt ?>_entries_tool_id" class="entries_tool_id">
<span<?php echo $entries->tool_id->ViewAttributes() ?>>
<?php echo $entries->tool_id->ListViewValue() ?></span>
</span></td>
	<?php } ?>
<?php

// Render list options (body, right)
$entries_list->ListOptions->Render("body", "right", $entries_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($entries->CurrentAction <> "gridadd")
		$entries_list->Recordset->MoveNext();
}
?>
</tbody>
<!--</table>-->
<?php } ?>
<?php if ($entries->CurrentAction == "add" || $entries->CurrentAction == "copy") { ?>
<input type="hidden" name="key_count" id="key_count" value="<?php echo $entries_list->KeyCount ?>" />
<?php } ?>
<?php if ($entries->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</form>
<?php

// Close recordset
if ($entries_list->Recordset)
	$entries_list->Recordset->Close();
?>
<script type="text/javascript">
fentrieslist.Init();
</script>
<?php
$entries_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$entries_list->Page_Terminate();
?>
