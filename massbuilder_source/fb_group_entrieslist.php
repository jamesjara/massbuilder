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

$fb_group_entries_list = NULL; // Initialize page object first

class cfb_group_entries_list extends cfb_group_entries {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{3554BCCA-7E88-4E52-9661-DF55D75275C9}";

	// Table name
	var $TableName = 'fb_group_entries';

	// Page object name
	var $PageObjName = 'fb_group_entries_list';

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

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = $this->james_url("fb_group_entriesadd.php");
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "fb_group_entriesdelete.php";
		$this->MultiUpdateUrl = "fb_group_entriesupdate.php";

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'fb_group_entries', TRUE);

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

			// Get basic search values
			$this->LoadBasicSearchValues();

			// Restore search parms from Session if not searching / reset
			if ($this->Command <> "search" && $this->Command <> "reset" && $this->Command <> "resetall")
				$this->RestoreSearchParms();

			// Call Recordset SearchValidated event
			$this->Recordset_SearchValidated();

			// Set up sorting order
			$this->SetUpSortOrder();

			// Get basic search criteria
			if ($gsSearchError == "")
				$sSrchBasic = $this->BasicSearchWhere();
		}

		// Restore display records
		if ($this->getRecordsPerPage() <> "") {
			$this->DisplayRecs = $this->getRecordsPerPage(); // Restore from Session
		} else {
			$this->DisplayRecs = 200; // Load default
		}

		// Load Sorting Order
		$this->LoadSortOrder();

		// Load search default if no existing search criteria
		if (!$this->CheckSearchParms()) {

			// Load basic search from default
			$this->BasicSearch->LoadDefault();
			if ($this->BasicSearch->Keyword != "")
				$sSrchBasic = $this->BasicSearchWhere();
		}

		// Build search criteria
		ew_AddFilter($this->SearchWhere, $sSrchAdvanced);
		ew_AddFilter($this->SearchWhere, $sSrchBasic);

		// Call Recordset_Searching event
		$this->Recordset_Searching($this->SearchWhere);

		// Save search criteria
		if ($this->Command == "search") {
			$this->setSearchWhere($this->SearchWhere); // Save to Session
			$this->StartRec = 1; // Reset start record counter
			$this->setStartRecordNumber($this->StartRec);
		} else {
			$this->SearchWhere = $this->getSearchWhere();
		}

		// Build filter
		$sFilter = "";
		if (!$Security->CanList())
			$sFilter = "(0=1)"; // Filter all records
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
		if (count($arrKeyFlds) >= 1) {
			$this->idfb_posts->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->idfb_posts->FormValue))
				return FALSE;
		}
		return TRUE;
	}

	// Return basic search SQL
	function BasicSearchSQL($Keyword) {
		$sKeyword = ew_AdjustSql($Keyword);
		$sWhere = "";
		$this->BuildBasicSearchSQL($sWhere, $this->data, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->fid, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->md5, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->body, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->titulo, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->created_time, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->actions, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->id, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->is_published, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->message, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->privacy, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->promotion_status, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->timeline_visibility, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->to, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->type, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->updated_time, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->from, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->comments, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->id_grupo, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->icon, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->link, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->name, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->object_id, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->picture, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->properties, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->message_tags, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->caption, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->description, $Keyword);
		$this->BuildBasicSearchSQL($sWhere, $this->application, $Keyword);
		return $sWhere;
	}

	// Build basic search SQL
	function BuildBasicSearchSql(&$Where, &$Fld, $Keyword) {
		if ($Keyword == EW_NULL_VALUE) {
			$sWrk = $Fld->FldExpression . " IS NULL";
		} elseif ($Keyword == EW_NOT_NULL_VALUE) {
			$sWrk = $Fld->FldExpression . " IS NOT NULL";
		} else {
			$sFldExpression = ($Fld->FldVirtualExpression <> $Fld->FldExpression) ? $Fld->FldVirtualExpression : $Fld->FldBasicSearchExpression;
			$sWrk = $sFldExpression . ew_Like(ew_QuotedValue("%" . $Keyword . "%", EW_DATATYPE_STRING));
		}
		if ($Where <> "") $Where .= " OR ";
		$Where .= $sWrk;
	}

	// Return basic search WHERE clause based on search keyword and type
	function BasicSearchWhere() {
		global $Security;
		$sSearchStr = "";
		if (!$Security->CanSearch()) return "";
		$sSearchKeyword = $this->BasicSearch->Keyword;
		$sSearchType = $this->BasicSearch->Type;
		if ($sSearchKeyword <> "") {
			$sSearch = trim($sSearchKeyword);
			if ($sSearchType <> "=") {
				while (strpos($sSearch, "  ") !== FALSE)
					$sSearch = str_replace("  ", " ", $sSearch);
				$arKeyword = explode(" ", trim($sSearch));
				foreach ($arKeyword as $sKeyword) {
					if ($sSearchStr <> "") $sSearchStr .= " " . $sSearchType . " ";
					$sSearchStr .= "(" . $this->BasicSearchSQL($sKeyword) . ")";
				}
			} else {
				$sSearchStr = $this->BasicSearchSQL($sSearch);
			}
			$this->Command = "search";
		}
		if ($this->Command == "search") {
			$this->BasicSearch->setKeyword($sSearchKeyword);
			$this->BasicSearch->setType($sSearchType);
		}
		return $sSearchStr;
	}

	// Check if search parm exists
	function CheckSearchParms() {

		// Check basic search
		if ($this->BasicSearch->IssetSession())
			return TRUE;
		return FALSE;
	}

	// Clear all search parameters
	function ResetSearchParms() {

		// Clear search WHERE clause
		$this->SearchWhere = "";
		$this->setSearchWhere($this->SearchWhere);

		// Clear basic search parameters
		$this->ResetBasicSearchParms();
	}

	// Load advanced search default values
	function LoadAdvancedSearchDefault() {
		return FALSE;
	}

	// Clear all basic search parameters
	function ResetBasicSearchParms() {
		$this->BasicSearch->UnsetSession();
	}

	// Restore all search parameters
	function RestoreSearchParms() {

		// Restore basic search values
		$this->BasicSearch->Load();
	}

	// Set up sort parameters
	function SetUpSortOrder() {

		// Check for "order" parameter
		if (@$_GET["order"] <> "") {
			$this->CurrentOrder = ew_StripSlashes(@$_GET["order"]);
			$this->CurrentOrderType = @$_GET["ordertype"];
			$this->UpdateSort($this->idfb_posts); // idfb_posts
			$this->UpdateSort($this->domain_id); // domain_id
			$this->UpdateSort($this->record_time); // record_time
			$this->UpdateSort($this->data); // data
			$this->UpdateSort($this->fid); // fid
			$this->UpdateSort($this->md5); // md5
			$this->UpdateSort($this->body); // body
			$this->UpdateSort($this->titulo); // titulo
			$this->UpdateSort($this->created_time); // created_time
			$this->UpdateSort($this->actions); // actions
			$this->UpdateSort($this->id); // id
			$this->UpdateSort($this->is_published); // is_published
			$this->UpdateSort($this->message); // message
			$this->UpdateSort($this->privacy); // privacy
			$this->UpdateSort($this->promotion_status); // promotion_status
			$this->UpdateSort($this->timeline_visibility); // timeline_visibility
			$this->UpdateSort($this->to); // to
			$this->UpdateSort($this->type); // type
			$this->UpdateSort($this->updated_time); // updated_time
			$this->UpdateSort($this->from); // from
			$this->UpdateSort($this->comments); // comments
			$this->UpdateSort($this->id_grupo); // id_grupo
			$this->UpdateSort($this->icon); // icon
			$this->UpdateSort($this->link); // link
			$this->UpdateSort($this->name); // name
			$this->UpdateSort($this->object_id); // object_id
			$this->UpdateSort($this->picture); // picture
			$this->UpdateSort($this->properties); // properties
			$this->UpdateSort($this->message_tags); // message_tags
			$this->UpdateSort($this->caption); // caption
			$this->UpdateSort($this->description); // description
			$this->UpdateSort($this->tool_id); // tool_id
			$this->UpdateSort($this->application); // application
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

			// Reset search criteria
			if ($this->Command == "reset" || $this->Command == "resetall")
				$this->ResetSearchParms();

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->idfb_posts->setSort("");
				$this->domain_id->setSort("");
				$this->record_time->setSort("");
				$this->data->setSort("");
				$this->fid->setSort("");
				$this->md5->setSort("");
				$this->body->setSort("");
				$this->titulo->setSort("");
				$this->created_time->setSort("");
				$this->actions->setSort("");
				$this->id->setSort("");
				$this->is_published->setSort("");
				$this->message->setSort("");
				$this->privacy->setSort("");
				$this->promotion_status->setSort("");
				$this->timeline_visibility->setSort("");
				$this->to->setSort("");
				$this->type->setSort("");
				$this->updated_time->setSort("");
				$this->from->setSort("");
				$this->comments->setSort("");
				$this->id_grupo->setSort("");
				$this->icon->setSort("");
				$this->link->setSort("");
				$this->name->setSort("");
				$this->object_id->setSort("");
				$this->picture->setSort("");
				$this->properties->setSort("");
				$this->message_tags->setSort("");
				$this->caption->setSort("");
				$this->description->setSort("");
				$this->tool_id->setSort("");
				$this->application->setSort("");
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

	// Load basic search values
	function LoadBasicSearchValues() {
		$this->BasicSearch->Keyword = @$_GET[EW_TABLE_BASIC_SEARCH];
		if ($this->BasicSearch->Keyword <> "") $this->Command = "search";
		$this->BasicSearch->Type = @$_GET[EW_TABLE_BASIC_SEARCH_TYPE];
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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("idfb_posts")) <> "")
			$this->idfb_posts->CurrentValue = $this->getKey("idfb_posts"); // idfb_posts
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
if (!isset($fb_group_entries_list)) $fb_group_entries_list = new cfb_group_entries_list();

// Page init
$fb_group_entries_list->Page_Init();

// Page main
$fb_group_entries_list->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var fb_group_entries_list = new ew_Page("fb_group_entries_list");
fb_group_entries_list.PageID = "list"; // Page ID
var EW_PAGE_ID = fb_group_entries_list.PageID; // For backward compatibility

// Form object
var ffb_group_entrieslist = new ew_Form("ffb_group_entrieslist");

// Form_CustomValidate event
ffb_group_entrieslist.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ffb_group_entrieslist.ValidateRequired = true;
<?php } else { ?>
ffb_group_entrieslist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

var ffb_group_entrieslistsrch = new ew_Form("ffb_group_entrieslistsrch");
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$fb_group_entries_list->TotalRecs = $fb_group_entries->SelectRecordCount();
	} else {
		if ($fb_group_entries_list->Recordset = $fb_group_entries_list->LoadRecordset())
			$fb_group_entries_list->TotalRecs = $fb_group_entries_list->Recordset->RecordCount();
	}
	$fb_group_entries_list->StartRec = 1;
	if ($fb_group_entries_list->DisplayRecs <= 0 || ($fb_group_entries->Export <> "" && $fb_group_entries->ExportAll)) // Display all records
		$fb_group_entries_list->DisplayRecs = $fb_group_entries_list->TotalRecs;
	if (!($fb_group_entries->Export <> "" && $fb_group_entries->ExportAll))
		$fb_group_entries_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$fb_group_entries_list->Recordset = $fb_group_entries_list->LoadRecordset($fb_group_entries_list->StartRec-1, $fb_group_entries_list->DisplayRecs);
?>
<p style="white-spaceJAMES: nowrapJAMES;"><h4 id="ewPageCaption" class="page-header ewTitle ewTableTitle"> <?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $fb_group_entries->TableCaption() ?>&nbsp;&nbsp;</h4>
<?php /*jamesjara
if(!ISSET($_GET['export']))echo '<div class="btn-group"><button class="btn btn-info dropdown-toggle" data-toggle="dropdown">Exportar3<span class="caret"></span></button><ul class="dropdown-menu">';
$fb_group_entries_list->ExportOptions->Render("body"); 
if(!ISSET($_GET['export']))echo '</ul></div> ';
*/?>
</p>
<?php if ($Security->CanSearch()) { ?>
<?php if ($fb_group_entries->Export == "" && $fb_group_entries->CurrentAction == "") { ?>
<div class="accordion" id="accordion2">
<div class="accordion-group">
    <div class="accordion-heading">
      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne">
        <?php echo $Language->Phrase("Search") ?>
      </a>
    </div>
<div id="collapseOne" class="accordion-body collapse">
<div class="accordion-inner">
<form onsubmit="return ewForms[this.id].Submit();" name="ffb_group_entrieslistsrch" id="ffb_group_entrieslistsrch" class="ewForm navbar-form pull-left" action="<?php echo ew_CurrentPage() ?>">
<!--
<a href="javascript:ffb_group_entrieslistsrch.ToggleSearchPanel();" style="text-decoration: none;"><img id="ffb_group_entrieslistsrch_SearchImage" src="phpimages/collapse.gif" alt="" width="9" height="9" style="border: 0;" /></a><span class="phpmaker"><?php echo $Language->Phrase("Search") ?></span><br />
-->
<div id="ffb_group_entrieslistsrch_SearchPanel">
<input type="hidden" name="cmd" value="search" />
<input type="hidden" name="t" value="fb_group_entries" />
<div class="ewBasicSearch">
<div id="xsr_1" class="ewRow">
	<input type="text" name="<?php echo EW_TABLE_BASIC_SEARCH ?>" id="<?php echo EW_TABLE_BASIC_SEARCH ?>"  class="span2"  value="<?php echo ew_HtmlEncode($fb_group_entries_list->BasicSearch->getKeyword()) ?>" />
	<input type="submit" class="btn" name="btnsubmit" id="btnsubmit" value="<?php echo ew_BtnCaption($Language->Phrase("QuickSearchBtn")) ?>" />&nbsp;
	<a class="btn btn-warning" href="<?php echo $fb_group_entries_list->PageUrl() ?>cmd=reset" id="a_ShowAll" class="ewLink"><?php echo $Language->Phrase("ShowAll") ?></a>
	<label class="radio inline"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="="<?php if ($fb_group_entries_list->BasicSearch->getType() == "=") { ?> checked="checked"<?php } ?> /><?php echo $Language->Phrase("ExactPhrase") ?></label>&nbsp;&nbsp;
	<label class="radio inline"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="AND"<?php if ($fb_group_entries_list->BasicSearch->getType() == "AND") { ?> checked="checked"<?php } ?> /><?php echo $Language->Phrase("AllWord") ?></label>&nbsp;&nbsp;
	<label class="radio inline"><input type="radio" name="<?php echo EW_TABLE_BASIC_SEARCH_TYPE ?>" value="OR"<?php if ($fb_group_entries_list->BasicSearch->getType() == "OR") { ?> checked="checked"<?php } ?> /><?php echo $Language->Phrase("AnyWord") ?></label>
</div>
</div>
</form>
     </div>
    </div>
 </div>
 </div>
</div>
<?php } ?>
<?php } ?>
<?php $fb_group_entries_list->ShowPageHeader(); ?>
<?php
$fb_group_entries_list->ShowMessage();
?>
<form name="ffb_group_entrieslist" id="ffb_group_entrieslist" class="ewForm" action="" method="post">
<input type="hidden" name="t" value="fb_group_entries" />
<div id="gmp_fb_group_entries" class="ewGridMiddlePanel row-fluid">
<ul class="thumbnails">
<?php if ($fb_group_entries_list->TotalRecs > 0) { ?>
<table id="tbl_fb_group_entrieslist" class="ewTable ewTableSeparate table table-striped table-bordered ">
<?php echo $fb_group_entries->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$fb_group_entries_list->RenderListOptions();

// Render list options (header, left)
$fb_group_entries_list->ListOptions->Render("header", "left");
?>
<?php if ($fb_group_entries->idfb_posts->Visible) { // idfb_posts ?>
	<?php if ($fb_group_entries->SortUrl($fb_group_entries->idfb_posts) == "") { ?>
		<th><span id="elh_fb_group_entries_idfb_posts" class="fb_group_entries_idfb_posts">
		<div class="ewTableHeaderBtn"><?php echo $fb_group_entries->idfb_posts->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div onmousedown="ew_Sort(event,'<?php echo $fb_group_entries->SortUrl($fb_group_entries->idfb_posts) ?>',1);"><span id="elh_fb_group_entries_idfb_posts" class="fb_group_entries_idfb_posts">
			<div class="ewTableHeaderBtn">			
			<?php echo $fb_group_entries->idfb_posts->FldCaption() ?>
			<?php if ($fb_group_entries->idfb_posts->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($fb_group_entries->idfb_posts->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($fb_group_entries->domain_id->Visible) { // domain_id ?>
	<?php if ($fb_group_entries->SortUrl($fb_group_entries->domain_id) == "") { ?>
		<th><span id="elh_fb_group_entries_domain_id" class="fb_group_entries_domain_id">
		<div class="ewTableHeaderBtn"><?php echo $fb_group_entries->domain_id->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div onmousedown="ew_Sort(event,'<?php echo $fb_group_entries->SortUrl($fb_group_entries->domain_id) ?>',1);"><span id="elh_fb_group_entries_domain_id" class="fb_group_entries_domain_id">
			<div class="ewTableHeaderBtn">			
			<?php echo $fb_group_entries->domain_id->FldCaption() ?>
			<?php if ($fb_group_entries->domain_id->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($fb_group_entries->domain_id->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($fb_group_entries->record_time->Visible) { // record_time ?>
	<?php if ($fb_group_entries->SortUrl($fb_group_entries->record_time) == "") { ?>
		<th><span id="elh_fb_group_entries_record_time" class="fb_group_entries_record_time">
		<div class="ewTableHeaderBtn"><?php echo $fb_group_entries->record_time->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div onmousedown="ew_Sort(event,'<?php echo $fb_group_entries->SortUrl($fb_group_entries->record_time) ?>',1);"><span id="elh_fb_group_entries_record_time" class="fb_group_entries_record_time">
			<div class="ewTableHeaderBtn">			
			<?php echo $fb_group_entries->record_time->FldCaption() ?>
			<?php if ($fb_group_entries->record_time->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($fb_group_entries->record_time->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($fb_group_entries->data->Visible) { // data ?>
	<?php if ($fb_group_entries->SortUrl($fb_group_entries->data) == "") { ?>
		<th><span id="elh_fb_group_entries_data" class="fb_group_entries_data">
		<div class="ewTableHeaderBtn"><?php echo $fb_group_entries->data->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div onmousedown="ew_Sort(event,'<?php echo $fb_group_entries->SortUrl($fb_group_entries->data) ?>',1);"><span id="elh_fb_group_entries_data" class="fb_group_entries_data">
			<div class="ewTableHeaderBtn">			
			<?php echo $fb_group_entries->data->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?>
			<?php if ($fb_group_entries->data->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($fb_group_entries->data->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($fb_group_entries->fid->Visible) { // fid ?>
	<?php if ($fb_group_entries->SortUrl($fb_group_entries->fid) == "") { ?>
		<th><span id="elh_fb_group_entries_fid" class="fb_group_entries_fid">
		<div class="ewTableHeaderBtn"><?php echo $fb_group_entries->fid->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div onmousedown="ew_Sort(event,'<?php echo $fb_group_entries->SortUrl($fb_group_entries->fid) ?>',1);"><span id="elh_fb_group_entries_fid" class="fb_group_entries_fid">
			<div class="ewTableHeaderBtn">			
			<?php echo $fb_group_entries->fid->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?>
			<?php if ($fb_group_entries->fid->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($fb_group_entries->fid->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($fb_group_entries->md5->Visible) { // md5 ?>
	<?php if ($fb_group_entries->SortUrl($fb_group_entries->md5) == "") { ?>
		<th><span id="elh_fb_group_entries_md5" class="fb_group_entries_md5">
		<div class="ewTableHeaderBtn"><?php echo $fb_group_entries->md5->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div onmousedown="ew_Sort(event,'<?php echo $fb_group_entries->SortUrl($fb_group_entries->md5) ?>',1);"><span id="elh_fb_group_entries_md5" class="fb_group_entries_md5">
			<div class="ewTableHeaderBtn">			
			<?php echo $fb_group_entries->md5->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?>
			<?php if ($fb_group_entries->md5->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($fb_group_entries->md5->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($fb_group_entries->body->Visible) { // body ?>
	<?php if ($fb_group_entries->SortUrl($fb_group_entries->body) == "") { ?>
		<th><span id="elh_fb_group_entries_body" class="fb_group_entries_body">
		<div class="ewTableHeaderBtn"><?php echo $fb_group_entries->body->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div onmousedown="ew_Sort(event,'<?php echo $fb_group_entries->SortUrl($fb_group_entries->body) ?>',1);"><span id="elh_fb_group_entries_body" class="fb_group_entries_body">
			<div class="ewTableHeaderBtn">			
			<?php echo $fb_group_entries->body->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?>
			<?php if ($fb_group_entries->body->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($fb_group_entries->body->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($fb_group_entries->titulo->Visible) { // titulo ?>
	<?php if ($fb_group_entries->SortUrl($fb_group_entries->titulo) == "") { ?>
		<th><span id="elh_fb_group_entries_titulo" class="fb_group_entries_titulo">
		<div class="ewTableHeaderBtn"><?php echo $fb_group_entries->titulo->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div onmousedown="ew_Sort(event,'<?php echo $fb_group_entries->SortUrl($fb_group_entries->titulo) ?>',1);"><span id="elh_fb_group_entries_titulo" class="fb_group_entries_titulo">
			<div class="ewTableHeaderBtn">			
			<?php echo $fb_group_entries->titulo->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?>
			<?php if ($fb_group_entries->titulo->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($fb_group_entries->titulo->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($fb_group_entries->created_time->Visible) { // created_time ?>
	<?php if ($fb_group_entries->SortUrl($fb_group_entries->created_time) == "") { ?>
		<th><span id="elh_fb_group_entries_created_time" class="fb_group_entries_created_time">
		<div class="ewTableHeaderBtn"><?php echo $fb_group_entries->created_time->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div onmousedown="ew_Sort(event,'<?php echo $fb_group_entries->SortUrl($fb_group_entries->created_time) ?>',1);"><span id="elh_fb_group_entries_created_time" class="fb_group_entries_created_time">
			<div class="ewTableHeaderBtn">			
			<?php echo $fb_group_entries->created_time->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?>
			<?php if ($fb_group_entries->created_time->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($fb_group_entries->created_time->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($fb_group_entries->actions->Visible) { // actions ?>
	<?php if ($fb_group_entries->SortUrl($fb_group_entries->actions) == "") { ?>
		<th><span id="elh_fb_group_entries_actions" class="fb_group_entries_actions">
		<div class="ewTableHeaderBtn"><?php echo $fb_group_entries->actions->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div onmousedown="ew_Sort(event,'<?php echo $fb_group_entries->SortUrl($fb_group_entries->actions) ?>',1);"><span id="elh_fb_group_entries_actions" class="fb_group_entries_actions">
			<div class="ewTableHeaderBtn">			
			<?php echo $fb_group_entries->actions->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?>
			<?php if ($fb_group_entries->actions->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($fb_group_entries->actions->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($fb_group_entries->id->Visible) { // id ?>
	<?php if ($fb_group_entries->SortUrl($fb_group_entries->id) == "") { ?>
		<th><span id="elh_fb_group_entries_id" class="fb_group_entries_id">
		<div class="ewTableHeaderBtn"><?php echo $fb_group_entries->id->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div onmousedown="ew_Sort(event,'<?php echo $fb_group_entries->SortUrl($fb_group_entries->id) ?>',1);"><span id="elh_fb_group_entries_id" class="fb_group_entries_id">
			<div class="ewTableHeaderBtn">			
			<?php echo $fb_group_entries->id->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?>
			<?php if ($fb_group_entries->id->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($fb_group_entries->id->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($fb_group_entries->is_published->Visible) { // is_published ?>
	<?php if ($fb_group_entries->SortUrl($fb_group_entries->is_published) == "") { ?>
		<th><span id="elh_fb_group_entries_is_published" class="fb_group_entries_is_published">
		<div class="ewTableHeaderBtn"><?php echo $fb_group_entries->is_published->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div onmousedown="ew_Sort(event,'<?php echo $fb_group_entries->SortUrl($fb_group_entries->is_published) ?>',1);"><span id="elh_fb_group_entries_is_published" class="fb_group_entries_is_published">
			<div class="ewTableHeaderBtn">			
			<?php echo $fb_group_entries->is_published->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?>
			<?php if ($fb_group_entries->is_published->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($fb_group_entries->is_published->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($fb_group_entries->message->Visible) { // message ?>
	<?php if ($fb_group_entries->SortUrl($fb_group_entries->message) == "") { ?>
		<th><span id="elh_fb_group_entries_message" class="fb_group_entries_message">
		<div class="ewTableHeaderBtn"><?php echo $fb_group_entries->message->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div onmousedown="ew_Sort(event,'<?php echo $fb_group_entries->SortUrl($fb_group_entries->message) ?>',1);"><span id="elh_fb_group_entries_message" class="fb_group_entries_message">
			<div class="ewTableHeaderBtn">			
			<?php echo $fb_group_entries->message->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?>
			<?php if ($fb_group_entries->message->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($fb_group_entries->message->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($fb_group_entries->privacy->Visible) { // privacy ?>
	<?php if ($fb_group_entries->SortUrl($fb_group_entries->privacy) == "") { ?>
		<th><span id="elh_fb_group_entries_privacy" class="fb_group_entries_privacy">
		<div class="ewTableHeaderBtn"><?php echo $fb_group_entries->privacy->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div onmousedown="ew_Sort(event,'<?php echo $fb_group_entries->SortUrl($fb_group_entries->privacy) ?>',1);"><span id="elh_fb_group_entries_privacy" class="fb_group_entries_privacy">
			<div class="ewTableHeaderBtn">			
			<?php echo $fb_group_entries->privacy->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?>
			<?php if ($fb_group_entries->privacy->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($fb_group_entries->privacy->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($fb_group_entries->promotion_status->Visible) { // promotion_status ?>
	<?php if ($fb_group_entries->SortUrl($fb_group_entries->promotion_status) == "") { ?>
		<th><span id="elh_fb_group_entries_promotion_status" class="fb_group_entries_promotion_status">
		<div class="ewTableHeaderBtn"><?php echo $fb_group_entries->promotion_status->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div onmousedown="ew_Sort(event,'<?php echo $fb_group_entries->SortUrl($fb_group_entries->promotion_status) ?>',1);"><span id="elh_fb_group_entries_promotion_status" class="fb_group_entries_promotion_status">
			<div class="ewTableHeaderBtn">			
			<?php echo $fb_group_entries->promotion_status->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?>
			<?php if ($fb_group_entries->promotion_status->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($fb_group_entries->promotion_status->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($fb_group_entries->timeline_visibility->Visible) { // timeline_visibility ?>
	<?php if ($fb_group_entries->SortUrl($fb_group_entries->timeline_visibility) == "") { ?>
		<th><span id="elh_fb_group_entries_timeline_visibility" class="fb_group_entries_timeline_visibility">
		<div class="ewTableHeaderBtn"><?php echo $fb_group_entries->timeline_visibility->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div onmousedown="ew_Sort(event,'<?php echo $fb_group_entries->SortUrl($fb_group_entries->timeline_visibility) ?>',1);"><span id="elh_fb_group_entries_timeline_visibility" class="fb_group_entries_timeline_visibility">
			<div class="ewTableHeaderBtn">			
			<?php echo $fb_group_entries->timeline_visibility->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?>
			<?php if ($fb_group_entries->timeline_visibility->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($fb_group_entries->timeline_visibility->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($fb_group_entries->to->Visible) { // to ?>
	<?php if ($fb_group_entries->SortUrl($fb_group_entries->to) == "") { ?>
		<th><span id="elh_fb_group_entries_to" class="fb_group_entries_to">
		<div class="ewTableHeaderBtn"><?php echo $fb_group_entries->to->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div onmousedown="ew_Sort(event,'<?php echo $fb_group_entries->SortUrl($fb_group_entries->to) ?>',1);"><span id="elh_fb_group_entries_to" class="fb_group_entries_to">
			<div class="ewTableHeaderBtn">			
			<?php echo $fb_group_entries->to->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?>
			<?php if ($fb_group_entries->to->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($fb_group_entries->to->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($fb_group_entries->type->Visible) { // type ?>
	<?php if ($fb_group_entries->SortUrl($fb_group_entries->type) == "") { ?>
		<th><span id="elh_fb_group_entries_type" class="fb_group_entries_type">
		<div class="ewTableHeaderBtn"><?php echo $fb_group_entries->type->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div onmousedown="ew_Sort(event,'<?php echo $fb_group_entries->SortUrl($fb_group_entries->type) ?>',1);"><span id="elh_fb_group_entries_type" class="fb_group_entries_type">
			<div class="ewTableHeaderBtn">			
			<?php echo $fb_group_entries->type->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?>
			<?php if ($fb_group_entries->type->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($fb_group_entries->type->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($fb_group_entries->updated_time->Visible) { // updated_time ?>
	<?php if ($fb_group_entries->SortUrl($fb_group_entries->updated_time) == "") { ?>
		<th><span id="elh_fb_group_entries_updated_time" class="fb_group_entries_updated_time">
		<div class="ewTableHeaderBtn"><?php echo $fb_group_entries->updated_time->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div onmousedown="ew_Sort(event,'<?php echo $fb_group_entries->SortUrl($fb_group_entries->updated_time) ?>',1);"><span id="elh_fb_group_entries_updated_time" class="fb_group_entries_updated_time">
			<div class="ewTableHeaderBtn">			
			<?php echo $fb_group_entries->updated_time->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?>
			<?php if ($fb_group_entries->updated_time->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($fb_group_entries->updated_time->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($fb_group_entries->from->Visible) { // from ?>
	<?php if ($fb_group_entries->SortUrl($fb_group_entries->from) == "") { ?>
		<th><span id="elh_fb_group_entries_from" class="fb_group_entries_from">
		<div class="ewTableHeaderBtn"><?php echo $fb_group_entries->from->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div onmousedown="ew_Sort(event,'<?php echo $fb_group_entries->SortUrl($fb_group_entries->from) ?>',1);"><span id="elh_fb_group_entries_from" class="fb_group_entries_from">
			<div class="ewTableHeaderBtn">			
			<?php echo $fb_group_entries->from->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?>
			<?php if ($fb_group_entries->from->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($fb_group_entries->from->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($fb_group_entries->comments->Visible) { // comments ?>
	<?php if ($fb_group_entries->SortUrl($fb_group_entries->comments) == "") { ?>
		<th><span id="elh_fb_group_entries_comments" class="fb_group_entries_comments">
		<div class="ewTableHeaderBtn"><?php echo $fb_group_entries->comments->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div onmousedown="ew_Sort(event,'<?php echo $fb_group_entries->SortUrl($fb_group_entries->comments) ?>',1);"><span id="elh_fb_group_entries_comments" class="fb_group_entries_comments">
			<div class="ewTableHeaderBtn">			
			<?php echo $fb_group_entries->comments->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?>
			<?php if ($fb_group_entries->comments->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($fb_group_entries->comments->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($fb_group_entries->id_grupo->Visible) { // id_grupo ?>
	<?php if ($fb_group_entries->SortUrl($fb_group_entries->id_grupo) == "") { ?>
		<th><span id="elh_fb_group_entries_id_grupo" class="fb_group_entries_id_grupo">
		<div class="ewTableHeaderBtn"><?php echo $fb_group_entries->id_grupo->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div onmousedown="ew_Sort(event,'<?php echo $fb_group_entries->SortUrl($fb_group_entries->id_grupo) ?>',1);"><span id="elh_fb_group_entries_id_grupo" class="fb_group_entries_id_grupo">
			<div class="ewTableHeaderBtn">			
			<?php echo $fb_group_entries->id_grupo->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?>
			<?php if ($fb_group_entries->id_grupo->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($fb_group_entries->id_grupo->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($fb_group_entries->icon->Visible) { // icon ?>
	<?php if ($fb_group_entries->SortUrl($fb_group_entries->icon) == "") { ?>
		<th><span id="elh_fb_group_entries_icon" class="fb_group_entries_icon">
		<div class="ewTableHeaderBtn"><?php echo $fb_group_entries->icon->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div onmousedown="ew_Sort(event,'<?php echo $fb_group_entries->SortUrl($fb_group_entries->icon) ?>',1);"><span id="elh_fb_group_entries_icon" class="fb_group_entries_icon">
			<div class="ewTableHeaderBtn">			
			<?php echo $fb_group_entries->icon->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?>
			<?php if ($fb_group_entries->icon->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($fb_group_entries->icon->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($fb_group_entries->link->Visible) { // link ?>
	<?php if ($fb_group_entries->SortUrl($fb_group_entries->link) == "") { ?>
		<th><span id="elh_fb_group_entries_link" class="fb_group_entries_link">
		<div class="ewTableHeaderBtn"><?php echo $fb_group_entries->link->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div onmousedown="ew_Sort(event,'<?php echo $fb_group_entries->SortUrl($fb_group_entries->link) ?>',1);"><span id="elh_fb_group_entries_link" class="fb_group_entries_link">
			<div class="ewTableHeaderBtn">			
			<?php echo $fb_group_entries->link->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?>
			<?php if ($fb_group_entries->link->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($fb_group_entries->link->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($fb_group_entries->name->Visible) { // name ?>
	<?php if ($fb_group_entries->SortUrl($fb_group_entries->name) == "") { ?>
		<th><span id="elh_fb_group_entries_name" class="fb_group_entries_name">
		<div class="ewTableHeaderBtn"><?php echo $fb_group_entries->name->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div onmousedown="ew_Sort(event,'<?php echo $fb_group_entries->SortUrl($fb_group_entries->name) ?>',1);"><span id="elh_fb_group_entries_name" class="fb_group_entries_name">
			<div class="ewTableHeaderBtn">			
			<?php echo $fb_group_entries->name->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?>
			<?php if ($fb_group_entries->name->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($fb_group_entries->name->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($fb_group_entries->object_id->Visible) { // object_id ?>
	<?php if ($fb_group_entries->SortUrl($fb_group_entries->object_id) == "") { ?>
		<th><span id="elh_fb_group_entries_object_id" class="fb_group_entries_object_id">
		<div class="ewTableHeaderBtn"><?php echo $fb_group_entries->object_id->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div onmousedown="ew_Sort(event,'<?php echo $fb_group_entries->SortUrl($fb_group_entries->object_id) ?>',1);"><span id="elh_fb_group_entries_object_id" class="fb_group_entries_object_id">
			<div class="ewTableHeaderBtn">			
			<?php echo $fb_group_entries->object_id->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?>
			<?php if ($fb_group_entries->object_id->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($fb_group_entries->object_id->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($fb_group_entries->picture->Visible) { // picture ?>
	<?php if ($fb_group_entries->SortUrl($fb_group_entries->picture) == "") { ?>
		<th><span id="elh_fb_group_entries_picture" class="fb_group_entries_picture">
		<div class="ewTableHeaderBtn"><?php echo $fb_group_entries->picture->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div onmousedown="ew_Sort(event,'<?php echo $fb_group_entries->SortUrl($fb_group_entries->picture) ?>',1);"><span id="elh_fb_group_entries_picture" class="fb_group_entries_picture">
			<div class="ewTableHeaderBtn">			
			<?php echo $fb_group_entries->picture->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?>
			<?php if ($fb_group_entries->picture->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($fb_group_entries->picture->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($fb_group_entries->properties->Visible) { // properties ?>
	<?php if ($fb_group_entries->SortUrl($fb_group_entries->properties) == "") { ?>
		<th><span id="elh_fb_group_entries_properties" class="fb_group_entries_properties">
		<div class="ewTableHeaderBtn"><?php echo $fb_group_entries->properties->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div onmousedown="ew_Sort(event,'<?php echo $fb_group_entries->SortUrl($fb_group_entries->properties) ?>',1);"><span id="elh_fb_group_entries_properties" class="fb_group_entries_properties">
			<div class="ewTableHeaderBtn">			
			<?php echo $fb_group_entries->properties->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?>
			<?php if ($fb_group_entries->properties->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($fb_group_entries->properties->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($fb_group_entries->message_tags->Visible) { // message_tags ?>
	<?php if ($fb_group_entries->SortUrl($fb_group_entries->message_tags) == "") { ?>
		<th><span id="elh_fb_group_entries_message_tags" class="fb_group_entries_message_tags">
		<div class="ewTableHeaderBtn"><?php echo $fb_group_entries->message_tags->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div onmousedown="ew_Sort(event,'<?php echo $fb_group_entries->SortUrl($fb_group_entries->message_tags) ?>',1);"><span id="elh_fb_group_entries_message_tags" class="fb_group_entries_message_tags">
			<div class="ewTableHeaderBtn">			
			<?php echo $fb_group_entries->message_tags->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?>
			<?php if ($fb_group_entries->message_tags->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($fb_group_entries->message_tags->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($fb_group_entries->caption->Visible) { // caption ?>
	<?php if ($fb_group_entries->SortUrl($fb_group_entries->caption) == "") { ?>
		<th><span id="elh_fb_group_entries_caption" class="fb_group_entries_caption">
		<div class="ewTableHeaderBtn"><?php echo $fb_group_entries->caption->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div onmousedown="ew_Sort(event,'<?php echo $fb_group_entries->SortUrl($fb_group_entries->caption) ?>',1);"><span id="elh_fb_group_entries_caption" class="fb_group_entries_caption">
			<div class="ewTableHeaderBtn">			
			<?php echo $fb_group_entries->caption->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?>
			<?php if ($fb_group_entries->caption->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($fb_group_entries->caption->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($fb_group_entries->description->Visible) { // description ?>
	<?php if ($fb_group_entries->SortUrl($fb_group_entries->description) == "") { ?>
		<th><span id="elh_fb_group_entries_description" class="fb_group_entries_description">
		<div class="ewTableHeaderBtn"><?php echo $fb_group_entries->description->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div onmousedown="ew_Sort(event,'<?php echo $fb_group_entries->SortUrl($fb_group_entries->description) ?>',1);"><span id="elh_fb_group_entries_description" class="fb_group_entries_description">
			<div class="ewTableHeaderBtn">			
			<?php echo $fb_group_entries->description->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?>
			<?php if ($fb_group_entries->description->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($fb_group_entries->description->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($fb_group_entries->tool_id->Visible) { // tool_id ?>
	<?php if ($fb_group_entries->SortUrl($fb_group_entries->tool_id) == "") { ?>
		<th><span id="elh_fb_group_entries_tool_id" class="fb_group_entries_tool_id">
		<div class="ewTableHeaderBtn"><?php echo $fb_group_entries->tool_id->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div onmousedown="ew_Sort(event,'<?php echo $fb_group_entries->SortUrl($fb_group_entries->tool_id) ?>',1);"><span id="elh_fb_group_entries_tool_id" class="fb_group_entries_tool_id">
			<div class="ewTableHeaderBtn">			
			<?php echo $fb_group_entries->tool_id->FldCaption() ?>
			<?php if ($fb_group_entries->tool_id->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($fb_group_entries->tool_id->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($fb_group_entries->application->Visible) { // application ?>
	<?php if ($fb_group_entries->SortUrl($fb_group_entries->application) == "") { ?>
		<th><span id="elh_fb_group_entries_application" class="fb_group_entries_application">
		<div class="ewTableHeaderBtn"><?php echo $fb_group_entries->application->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div onmousedown="ew_Sort(event,'<?php echo $fb_group_entries->SortUrl($fb_group_entries->application) ?>',1);"><span id="elh_fb_group_entries_application" class="fb_group_entries_application">
			<div class="ewTableHeaderBtn">			
			<?php echo $fb_group_entries->application->FldCaption() ?><?php echo $Language->Phrase("SrchLegend") ?>
			<?php if ($fb_group_entries->application->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($fb_group_entries->application->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$fb_group_entries_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($fb_group_entries->ExportAll && $fb_group_entries->Export <> "") {
	$fb_group_entries_list->StopRec = $fb_group_entries_list->TotalRecs;
} else {

	// Set the last record to display
	if ($fb_group_entries_list->TotalRecs > $fb_group_entries_list->StartRec + $fb_group_entries_list->DisplayRecs - 1)
		$fb_group_entries_list->StopRec = $fb_group_entries_list->StartRec + $fb_group_entries_list->DisplayRecs - 1;
	else
		$fb_group_entries_list->StopRec = $fb_group_entries_list->TotalRecs;
}
$fb_group_entries_list->RecCnt = $fb_group_entries_list->StartRec - 1;
if ($fb_group_entries_list->Recordset && !$fb_group_entries_list->Recordset->EOF) {
	$fb_group_entries_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $fb_group_entries_list->StartRec > 1)
		$fb_group_entries_list->Recordset->Move($fb_group_entries_list->StartRec - 1);
} elseif (!$fb_group_entries->AllowAddDeleteRow && $fb_group_entries_list->StopRec == 0) {
	$fb_group_entries_list->StopRec = $fb_group_entries->GridAddRowCount;
}

// Initialize aggregate
$fb_group_entries->RowType = EW_ROWTYPE_AGGREGATEINIT;
$fb_group_entries->ResetAttrs();
$fb_group_entries_list->RenderRow();
while ($fb_group_entries_list->RecCnt < $fb_group_entries_list->StopRec) {
	$fb_group_entries_list->RecCnt++;
	if (intval($fb_group_entries_list->RecCnt) >= intval($fb_group_entries_list->StartRec)) {
		$fb_group_entries_list->RowCnt++;

		// Set up key count
		$fb_group_entries_list->KeyCount = $fb_group_entries_list->RowIndex;

		// Init row class and style
		$fb_group_entries->ResetAttrs();
		$fb_group_entries->CssClass = "";
		if ($fb_group_entries->CurrentAction == "gridadd") {
		} else {
			$fb_group_entries_list->LoadRowValues($fb_group_entries_list->Recordset); // Load row values
		}
		$fb_group_entries->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$fb_group_entries->RowAttrs = array_merge($fb_group_entries->RowAttrs, array('data-rowindex'=>$fb_group_entries_list->RowCnt, 'id'=>'r' . $fb_group_entries_list->RowCnt . '_fb_group_entries', 'data-rowtype'=>$fb_group_entries->RowType));

		// Render row
		$fb_group_entries_list->RenderRow();

		// Render list options
		$fb_group_entries_list->RenderListOptions();
?>
	<tr<?php echo $fb_group_entries->RowAttributes() ?>>
<?php

// Render list options (body, left)
$fb_group_entries_list->ListOptions->Render("body", "left", $fb_group_entries_list->RowCnt);
?>
	<?php if ($fb_group_entries->idfb_posts->Visible) { // idfb_posts ?>
		<td<?php echo $fb_group_entries->idfb_posts->CellAttributes() ?>><span id="el<?php echo $fb_group_entries_list->RowCnt ?>_fb_group_entries_idfb_posts" class="fb_group_entries_idfb_posts">
<span<?php echo $fb_group_entries->idfb_posts->ViewAttributes() ?>>
<?php echo $fb_group_entries->idfb_posts->ListViewValue() ?></span>
</span></td>
	<?php } ?>
<a id="<?php echo $fb_group_entries_list->PageObjName . "_row_" . $fb_group_entries_list->RowCnt ?>"></a>
	<?php if ($fb_group_entries->domain_id->Visible) { // domain_id ?>
		<td<?php echo $fb_group_entries->domain_id->CellAttributes() ?>><span id="el<?php echo $fb_group_entries_list->RowCnt ?>_fb_group_entries_domain_id" class="fb_group_entries_domain_id">
<span<?php echo $fb_group_entries->domain_id->ViewAttributes() ?>>
<?php echo $fb_group_entries->domain_id->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($fb_group_entries->record_time->Visible) { // record_time ?>
		<td<?php echo $fb_group_entries->record_time->CellAttributes() ?>><span id="el<?php echo $fb_group_entries_list->RowCnt ?>_fb_group_entries_record_time" class="fb_group_entries_record_time">
<span<?php echo $fb_group_entries->record_time->ViewAttributes() ?>>
<?php echo $fb_group_entries->record_time->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($fb_group_entries->data->Visible) { // data ?>
		<td<?php echo $fb_group_entries->data->CellAttributes() ?>><span id="el<?php echo $fb_group_entries_list->RowCnt ?>_fb_group_entries_data" class="fb_group_entries_data">
<span<?php echo $fb_group_entries->data->ViewAttributes() ?>>
<?php echo $fb_group_entries->data->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($fb_group_entries->fid->Visible) { // fid ?>
		<td<?php echo $fb_group_entries->fid->CellAttributes() ?>><span id="el<?php echo $fb_group_entries_list->RowCnt ?>_fb_group_entries_fid" class="fb_group_entries_fid">
<span<?php echo $fb_group_entries->fid->ViewAttributes() ?>>
<?php echo $fb_group_entries->fid->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($fb_group_entries->md5->Visible) { // md5 ?>
		<td<?php echo $fb_group_entries->md5->CellAttributes() ?>><span id="el<?php echo $fb_group_entries_list->RowCnt ?>_fb_group_entries_md5" class="fb_group_entries_md5">
<span<?php echo $fb_group_entries->md5->ViewAttributes() ?>>
<?php echo $fb_group_entries->md5->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($fb_group_entries->body->Visible) { // body ?>
		<td<?php echo $fb_group_entries->body->CellAttributes() ?>><span id="el<?php echo $fb_group_entries_list->RowCnt ?>_fb_group_entries_body" class="fb_group_entries_body">
<span<?php echo $fb_group_entries->body->ViewAttributes() ?>>
<?php echo $fb_group_entries->body->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($fb_group_entries->titulo->Visible) { // titulo ?>
		<td<?php echo $fb_group_entries->titulo->CellAttributes() ?>><span id="el<?php echo $fb_group_entries_list->RowCnt ?>_fb_group_entries_titulo" class="fb_group_entries_titulo">
<span<?php echo $fb_group_entries->titulo->ViewAttributes() ?>>
<?php echo $fb_group_entries->titulo->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($fb_group_entries->created_time->Visible) { // created_time ?>
		<td<?php echo $fb_group_entries->created_time->CellAttributes() ?>><span id="el<?php echo $fb_group_entries_list->RowCnt ?>_fb_group_entries_created_time" class="fb_group_entries_created_time">
<span<?php echo $fb_group_entries->created_time->ViewAttributes() ?>>
<?php echo $fb_group_entries->created_time->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($fb_group_entries->actions->Visible) { // actions ?>
		<td<?php echo $fb_group_entries->actions->CellAttributes() ?>><span id="el<?php echo $fb_group_entries_list->RowCnt ?>_fb_group_entries_actions" class="fb_group_entries_actions">
<span<?php echo $fb_group_entries->actions->ViewAttributes() ?>>
<?php echo $fb_group_entries->actions->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($fb_group_entries->id->Visible) { // id ?>
		<td<?php echo $fb_group_entries->id->CellAttributes() ?>><span id="el<?php echo $fb_group_entries_list->RowCnt ?>_fb_group_entries_id" class="fb_group_entries_id">
<span<?php echo $fb_group_entries->id->ViewAttributes() ?>>
<?php echo $fb_group_entries->id->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($fb_group_entries->is_published->Visible) { // is_published ?>
		<td<?php echo $fb_group_entries->is_published->CellAttributes() ?>><span id="el<?php echo $fb_group_entries_list->RowCnt ?>_fb_group_entries_is_published" class="fb_group_entries_is_published">
<span<?php echo $fb_group_entries->is_published->ViewAttributes() ?>>
<?php echo $fb_group_entries->is_published->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($fb_group_entries->message->Visible) { // message ?>
		<td<?php echo $fb_group_entries->message->CellAttributes() ?>><span id="el<?php echo $fb_group_entries_list->RowCnt ?>_fb_group_entries_message" class="fb_group_entries_message">
<span<?php echo $fb_group_entries->message->ViewAttributes() ?>>
<?php echo $fb_group_entries->message->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($fb_group_entries->privacy->Visible) { // privacy ?>
		<td<?php echo $fb_group_entries->privacy->CellAttributes() ?>><span id="el<?php echo $fb_group_entries_list->RowCnt ?>_fb_group_entries_privacy" class="fb_group_entries_privacy">
<span<?php echo $fb_group_entries->privacy->ViewAttributes() ?>>
<?php echo $fb_group_entries->privacy->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($fb_group_entries->promotion_status->Visible) { // promotion_status ?>
		<td<?php echo $fb_group_entries->promotion_status->CellAttributes() ?>><span id="el<?php echo $fb_group_entries_list->RowCnt ?>_fb_group_entries_promotion_status" class="fb_group_entries_promotion_status">
<span<?php echo $fb_group_entries->promotion_status->ViewAttributes() ?>>
<?php echo $fb_group_entries->promotion_status->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($fb_group_entries->timeline_visibility->Visible) { // timeline_visibility ?>
		<td<?php echo $fb_group_entries->timeline_visibility->CellAttributes() ?>><span id="el<?php echo $fb_group_entries_list->RowCnt ?>_fb_group_entries_timeline_visibility" class="fb_group_entries_timeline_visibility">
<span<?php echo $fb_group_entries->timeline_visibility->ViewAttributes() ?>>
<?php echo $fb_group_entries->timeline_visibility->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($fb_group_entries->to->Visible) { // to ?>
		<td<?php echo $fb_group_entries->to->CellAttributes() ?>><span id="el<?php echo $fb_group_entries_list->RowCnt ?>_fb_group_entries_to" class="fb_group_entries_to">
<span<?php echo $fb_group_entries->to->ViewAttributes() ?>>
<?php echo $fb_group_entries->to->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($fb_group_entries->type->Visible) { // type ?>
		<td<?php echo $fb_group_entries->type->CellAttributes() ?>><span id="el<?php echo $fb_group_entries_list->RowCnt ?>_fb_group_entries_type" class="fb_group_entries_type">
<span<?php echo $fb_group_entries->type->ViewAttributes() ?>>
<?php echo $fb_group_entries->type->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($fb_group_entries->updated_time->Visible) { // updated_time ?>
		<td<?php echo $fb_group_entries->updated_time->CellAttributes() ?>><span id="el<?php echo $fb_group_entries_list->RowCnt ?>_fb_group_entries_updated_time" class="fb_group_entries_updated_time">
<span<?php echo $fb_group_entries->updated_time->ViewAttributes() ?>>
<?php echo $fb_group_entries->updated_time->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($fb_group_entries->from->Visible) { // from ?>
		<td<?php echo $fb_group_entries->from->CellAttributes() ?>><span id="el<?php echo $fb_group_entries_list->RowCnt ?>_fb_group_entries_from" class="fb_group_entries_from">
<span<?php echo $fb_group_entries->from->ViewAttributes() ?>>
<?php echo $fb_group_entries->from->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($fb_group_entries->comments->Visible) { // comments ?>
		<td<?php echo $fb_group_entries->comments->CellAttributes() ?>><span id="el<?php echo $fb_group_entries_list->RowCnt ?>_fb_group_entries_comments" class="fb_group_entries_comments">
<span<?php echo $fb_group_entries->comments->ViewAttributes() ?>>
<?php echo $fb_group_entries->comments->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($fb_group_entries->id_grupo->Visible) { // id_grupo ?>
		<td<?php echo $fb_group_entries->id_grupo->CellAttributes() ?>><span id="el<?php echo $fb_group_entries_list->RowCnt ?>_fb_group_entries_id_grupo" class="fb_group_entries_id_grupo">
<span<?php echo $fb_group_entries->id_grupo->ViewAttributes() ?>>
<?php echo $fb_group_entries->id_grupo->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($fb_group_entries->icon->Visible) { // icon ?>
		<td<?php echo $fb_group_entries->icon->CellAttributes() ?>><span id="el<?php echo $fb_group_entries_list->RowCnt ?>_fb_group_entries_icon" class="fb_group_entries_icon">
<span<?php echo $fb_group_entries->icon->ViewAttributes() ?>>
<?php echo $fb_group_entries->icon->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($fb_group_entries->link->Visible) { // link ?>
		<td<?php echo $fb_group_entries->link->CellAttributes() ?>><span id="el<?php echo $fb_group_entries_list->RowCnt ?>_fb_group_entries_link" class="fb_group_entries_link">
<span<?php echo $fb_group_entries->link->ViewAttributes() ?>>
<?php echo $fb_group_entries->link->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($fb_group_entries->name->Visible) { // name ?>
		<td<?php echo $fb_group_entries->name->CellAttributes() ?>><span id="el<?php echo $fb_group_entries_list->RowCnt ?>_fb_group_entries_name" class="fb_group_entries_name">
<span<?php echo $fb_group_entries->name->ViewAttributes() ?>>
<?php echo $fb_group_entries->name->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($fb_group_entries->object_id->Visible) { // object_id ?>
		<td<?php echo $fb_group_entries->object_id->CellAttributes() ?>><span id="el<?php echo $fb_group_entries_list->RowCnt ?>_fb_group_entries_object_id" class="fb_group_entries_object_id">
<span<?php echo $fb_group_entries->object_id->ViewAttributes() ?>>
<?php echo $fb_group_entries->object_id->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($fb_group_entries->picture->Visible) { // picture ?>
		<td<?php echo $fb_group_entries->picture->CellAttributes() ?>><span id="el<?php echo $fb_group_entries_list->RowCnt ?>_fb_group_entries_picture" class="fb_group_entries_picture">
<span<?php echo $fb_group_entries->picture->ViewAttributes() ?>>
<?php echo $fb_group_entries->picture->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($fb_group_entries->properties->Visible) { // properties ?>
		<td<?php echo $fb_group_entries->properties->CellAttributes() ?>><span id="el<?php echo $fb_group_entries_list->RowCnt ?>_fb_group_entries_properties" class="fb_group_entries_properties">
<span<?php echo $fb_group_entries->properties->ViewAttributes() ?>>
<?php echo $fb_group_entries->properties->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($fb_group_entries->message_tags->Visible) { // message_tags ?>
		<td<?php echo $fb_group_entries->message_tags->CellAttributes() ?>><span id="el<?php echo $fb_group_entries_list->RowCnt ?>_fb_group_entries_message_tags" class="fb_group_entries_message_tags">
<span<?php echo $fb_group_entries->message_tags->ViewAttributes() ?>>
<?php echo $fb_group_entries->message_tags->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($fb_group_entries->caption->Visible) { // caption ?>
		<td<?php echo $fb_group_entries->caption->CellAttributes() ?>><span id="el<?php echo $fb_group_entries_list->RowCnt ?>_fb_group_entries_caption" class="fb_group_entries_caption">
<span<?php echo $fb_group_entries->caption->ViewAttributes() ?>>
<?php echo $fb_group_entries->caption->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($fb_group_entries->description->Visible) { // description ?>
		<td<?php echo $fb_group_entries->description->CellAttributes() ?>><span id="el<?php echo $fb_group_entries_list->RowCnt ?>_fb_group_entries_description" class="fb_group_entries_description">
<span<?php echo $fb_group_entries->description->ViewAttributes() ?>>
<?php echo $fb_group_entries->description->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($fb_group_entries->tool_id->Visible) { // tool_id ?>
		<td<?php echo $fb_group_entries->tool_id->CellAttributes() ?>><span id="el<?php echo $fb_group_entries_list->RowCnt ?>_fb_group_entries_tool_id" class="fb_group_entries_tool_id">
<span<?php echo $fb_group_entries->tool_id->ViewAttributes() ?>>
<?php echo $fb_group_entries->tool_id->ListViewValue() ?></span>
</span></td>
	<?php } ?>
	<?php if ($fb_group_entries->application->Visible) { // application ?>
		<td<?php echo $fb_group_entries->application->CellAttributes() ?>><span id="el<?php echo $fb_group_entries_list->RowCnt ?>_fb_group_entries_application" class="fb_group_entries_application">
<span<?php echo $fb_group_entries->application->ViewAttributes() ?>>
<?php echo $fb_group_entries->application->ListViewValue() ?></span>
</span></td>
	<?php } ?>
<?php

// Render list options (body, right)
$fb_group_entries_list->ListOptions->Render("body", "right", $fb_group_entries_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($fb_group_entries->CurrentAction <> "gridadd")
		$fb_group_entries_list->Recordset->MoveNext();
}
?>
</tbody>
<!--</table>-->
<?php } ?>
<?php if ($fb_group_entries->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</form>
<?php

// Close recordset
if ($fb_group_entries_list->Recordset)
	$fb_group_entries_list->Recordset->Close();
?>
<div class="ewGridLowerPanel">
<?php if ($fb_group_entries->CurrentAction <> "gridadd" && $fb_group_entries->CurrentAction <> "gridedit") { ?>
<div>
<form name="ewpagerform" id="ewpagerform" class="ewForm" action="<?php echo ew_CurrentPage() ?>">
<div id="paginador" class="pagination pull-right">
<?php if (!isset($fb_group_entries_list->Pager)) $fb_group_entries_list->Pager = new cNumericPager($fb_group_entries_list->StartRec, $fb_group_entries_list->DisplayRecs, $fb_group_entries_list->TotalRecs, $fb_group_entries_list->RecRange) ?>
<?php if ($fb_group_entries_list->Pager->RecordCount > 0) { ?>
	<ul><?php if ($fb_group_entries_list->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $fb_group_entries_list->PageUrl() ?>start=<?php echo $fb_group_entries_list->Pager->FirstButton->Start ?>"><b><?php echo $Language->Phrase("PagerFirst") ?></b></a></li>
	<?php } ?>
	<?php if ($fb_group_entries_list->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $fb_group_entries_list->PageUrl() ?>start=<?php echo $fb_group_entries_list->Pager->PrevButton->Start ?>"><b><?php echo $Language->Phrase("PagerPrevious") ?></b></a></li>
	<?php } ?>
	<?php foreach ($fb_group_entries_list->Pager->Items as $PagerItem) { //jamesjara ?>
		<?php $classs=""; if (!$PagerItem->Enabled) $classs = 'class="active"';

		//jamesjara if ($PagerItem->Enabled) { ?>
			<li <?php echo $classs; ?>><a href="<?php echo $fb_group_entries_list->PageUrl() ?>start=<?php echo $PagerItem->Start ?>">
		<?php //jamesjara } ?>
			<b><?php echo $PagerItem->Text ?></b>
		<?php //jamesjara if ($PagerItem->Enabled) { ?> 
			</a></li><?php //jamesjara } 
		?>
	<?php } ?>
	<?php if ($fb_group_entries_list->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $fb_group_entries_list->PageUrl() ?>start=<?php echo $fb_group_entries_list->Pager->NextButton->Start ?>"><b><?php echo $Language->Phrase("PagerNext") ?></b></a></li>
	<?php } ?>
	<?php if ($fb_group_entries_list->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $fb_group_entries_list->PageUrl() ?>start=<?php echo $fb_group_entries_list->Pager->LastButton->Start ?>"><b><?php echo $Language->Phrase("PagerLast") ?></b></a></li>
	<?php } ?>
	</ul>
	<?php if ($fb_group_entries_list->Pager->ButtonCount > 0) { ?><?php } ?>
	<div style=" margin-top: 1px; " class="pull-right"><span class="label label-info"><i class="icon-ok-sign icon-white"></i> <?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $fb_group_entries_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $fb_group_entries_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $fb_group_entries_list->Pager->RecordCount ?></span></div>
<?php } else { ?>	
	<?php if ($Security->CanList()) { ?>
	<?php if ($fb_group_entries_list->SearchWhere == "0=101") { ?>
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
<?php if ($fb_group_entries_list->AddUrl <> "") { ?>
<a class="ewGridLink btn btn-success" href="<?php echo $fb_group_entries_list->AddUrl ?>"><?php echo $Language->Phrase("AddLink") ?></a>
<?php } ?>
<?php } ?>
</div>
<script type="text/javascript">
ffb_group_entrieslistsrch.Init();
ffb_group_entrieslist.Init();
</script>
<?php
$fb_group_entries_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$fb_group_entries_list->Page_Terminate();
?>
