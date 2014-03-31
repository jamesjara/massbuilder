<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg9.php" ?>
<?php include_once "ewmysql9.php" ?>
<?php include_once "phpfn9.php" ?>
<?php include_once "domainsinfo.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "proyectosinfo.php" ?>
<?php include_once "entriesgridcls.php" ?>
<?php include_once "tools_translationgridcls.php" ?>
<?php include_once "tools_backupsgridcls.php" ?>
<?php include_once "toolsgridcls.php" ?>
<?php include_once "userfn9.php" ?>
<?php

//
// Page class
//

$domains_list = NULL; // Initialize page object first

class cdomains_list extends cdomains {

	// Page ID
	var $PageID = 'list';

	// Project ID
	var $ProjectID = "{3554BCCA-7E88-4E52-9661-DF55D75275C9}";

	// Table name
	var $TableName = 'domains';

	// Page object name
	var $PageObjName = 'domains_list';

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

		// Table object (domains)
		if (!isset($GLOBALS["domains"])) {
			$GLOBALS["domains"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["domains"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html";
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml";
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv";
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf";
		$this->AddUrl = $this-> james_url("domainsadd.php") . '?'.EW_TABLE_SHOW_DETAIL . "=";
		$this->InlineAddUrl = $this->PageUrl() . "a=add";
		$this->GridAddUrl = $this->PageUrl() . "a=gridadd";
		$this->GridEditUrl = $this->PageUrl() . "a=gridedit";
		$this->MultiDeleteUrl = "domainsdelete.php";
		$this->MultiUpdateUrl = "domainsupdate.php";

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Table object (proyectos)
		if (!isset($GLOBALS['proyectos'])) $GLOBALS['proyectos'] = new cproyectos();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'list', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'domains', TRUE);

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
		$this->id_domains->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
	var $entries_Count;
	var $tools_translation_Count;
	var $tools_backups_Count;
	var $tools_Count;
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
		if ($this->CurrentMode <> "add" && $this->GetMasterFilter() <> "" && $this->getCurrentMasterTable() == "proyectos") {
			global $proyectos;
			$rsmaster = $proyectos->LoadRs($this->DbMasterFilter);
			$this->MasterRecordExists = ($rsmaster && !$rsmaster->EOF);
			if (!$this->MasterRecordExists) {
				$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record found
				$this->Page_Terminate("proyectoslist.php"); // Return to master page
			} else {
				$proyectos->LoadListRowValues($rsmaster);
				$proyectos->RowType = EW_ROWTYPE_MASTER; // Master row
				$proyectos->RenderListRow();
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
			$this->id_domains->setFormValue($arrKeyFlds[0]);
			if (!is_numeric($this->id_domains->FormValue))
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
			$this->UpdateSort($this->hosted_in); // hosted_in
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
				$this->dominio->setSort("ASC");
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
				$this->id_proyecto->setSessionValue("");
			}

			// Reset sorting order
			if ($this->Command == "resetsort") {
				$sOrderBy = "";
				$this->setSessionOrderBy($sOrderBy);
				$this->dominio->setSort("");
				$this->id_domains->setSort("");
				$this->hosted_in->setSort("");
			}

			// Reset start position
			$this->StartRec = 1;
			$this->setStartRecordNumber($this->StartRec);
		}
	}

	// Set up list options
	function SetupListOptions() {
		global $Security, $Language;

		// "edit"
		$item = &$this->ListOptions->Add("edit");
		$item->CssStyle = "white-spaceJAMES: nowrapJAMES;";
		$item->Visible = $Security->CanEdit();
		$item->OnLeft = FALSE;

		// "delete"
		$item = &$this->ListOptions->Add("delete");
		$item->CssStyle = "white-spaceJAMES: nowrapJAMES;";
		$item->Visible = $Security->CanDelete();
		$item->OnLeft = FALSE;

		// "detail_entries"
		$item = &$this->ListOptions->Add("detail_entries");
		$item->CssStyle = "white-spaceJAMES: nowrapJAMES;";
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'entries');
		$item->OnLeft = FALSE;
		if (!isset($GLOBALS["entries_grid"])) $GLOBALS["entries_grid"] = new centries_grid;

		// "detail_tools_translation"
		$item = &$this->ListOptions->Add("detail_tools_translation");
		$item->CssStyle = "white-spaceJAMES: nowrapJAMES;";
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'tools_translation');
		$item->OnLeft = FALSE;
		if (!isset($GLOBALS["tools_translation_grid"])) $GLOBALS["tools_translation_grid"] = new ctools_translation_grid;

		// "detail_tools_backups"
		$item = &$this->ListOptions->Add("detail_tools_backups");
		$item->CssStyle = "white-spaceJAMES: nowrapJAMES;";
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'tools_backups');
		$item->OnLeft = FALSE;
		if (!isset($GLOBALS["tools_backups_grid"])) $GLOBALS["tools_backups_grid"] = new ctools_backups_grid;

		// "detail_tools"
		$item = &$this->ListOptions->Add("detail_tools");
		$item->CssStyle = "white-spaceJAMES: nowrapJAMES;";
		$item->Visible = $Security->AllowList(CurrentProjectID() . 'tools');
		$item->OnLeft = FALSE;
		if (!isset($GLOBALS["tools_grid"])) $GLOBALS["tools_grid"] = new ctools_grid;

		// Call ListOptions_Load event
		$this->ListOptions_Load();
	}

	// Render list options
	function RenderListOptions() {
		global $Security, $Language, $objForm;
		$this->ListOptions->LoadDefault();

		// "edit"
		$oListOpt = &$this->ListOptions->Items["edit"];
		if ($Security->CanEdit()) {
			$oListOpt->Body = "<a class=\"ewRowLink label label-info\" href=\"" . $this->EditUrl . "\"><i class='icon-pencil icon-white'></i> " . $Language->Phrase("EditLink") . "</a>";
		}

		// "delete"
		$oListOpt = &$this->ListOptions->Items["delete"];
		if ($Security->CanDelete())
			$oListOpt->Body = "<a class=\"ewRowLink  label label-important\"" . "" . " href=\"" . $this->DeleteUrl . "\">" . $Language->Phrase("DeleteLink") . "</a>";

		// "detail_entries"
		$oListOpt = &$this->ListOptions->Items["detail_entries"];
		if ($Security->AllowList(CurrentProjectID() . 'entries')) {
			$oListOpt->Body = $Language->Phrase("DetailLink") . $Language->TablePhrase("entries", "TblCaption");
			$oListOpt->Body .= str_replace("%c", $this->entries_Count, $Language->Phrase("DetailCount"));
			$oListOpt->Body = "<a class=\"ewRowLink label label-warning\" href=\"entrieslist.php?" . EW_TABLE_SHOW_MASTER . "=domains&id_domains=" . urlencode(strval($this->id_domains->CurrentValue)) . "\"><i class='icon-list-alt icon-white'></i> " . $oListOpt->Body . "</a>";
			$links = "";
			if ($GLOBALS["entries_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 'entries'))
				$links .= "<a class=\"ewRowLink label label-warning \" href=\"" . $this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=entries") . "\"><i class='icon-list-alt icon-white'></i> " . $Language->Phrase("EditLink") . "</a>";
			if ($links <> "") $oListOpt->Body .= "<br />" . $links;
		}

		// "detail_tools_translation"
		$oListOpt = &$this->ListOptions->Items["detail_tools_translation"];
		if ($Security->AllowList(CurrentProjectID() . 'tools_translation')) {
			$oListOpt->Body = $Language->Phrase("DetailLink") . $Language->TablePhrase("tools_translation", "TblCaption");
			$oListOpt->Body .= str_replace("%c", $this->tools_translation_Count, $Language->Phrase("DetailCount"));
			$oListOpt->Body = "<a class=\"ewRowLink label label-warning\" href=\"tools_translationlist.php?" . EW_TABLE_SHOW_MASTER . "=domains&id_domains=" . urlencode(strval($this->id_domains->CurrentValue)) . "\"><i class='icon-list-alt icon-white'></i> " . $oListOpt->Body . "</a>";
			$links = "";
			if ($GLOBALS["tools_translation_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 'tools_translation'))
				$links .= "<a class=\"ewRowLink label label-warning \" href=\"" . $this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=tools_translation") . "\"><i class='icon-list-alt icon-white'></i> " . $Language->Phrase("EditLink") . "</a>";
			if ($links <> "") $oListOpt->Body .= "<br />" . $links;
		}

		// "detail_tools_backups"
		$oListOpt = &$this->ListOptions->Items["detail_tools_backups"];
		if ($Security->AllowList(CurrentProjectID() . 'tools_backups')) {
			$oListOpt->Body = $Language->Phrase("DetailLink") . $Language->TablePhrase("tools_backups", "TblCaption");
			$oListOpt->Body .= str_replace("%c", $this->tools_backups_Count, $Language->Phrase("DetailCount"));
			$oListOpt->Body = "<a class=\"ewRowLink label label-warning\" href=\"tools_backupslist.php?" . EW_TABLE_SHOW_MASTER . "=domains&id_domains=" . urlencode(strval($this->id_domains->CurrentValue)) . "\"><i class='icon-list-alt icon-white'></i> " . $oListOpt->Body . "</a>";
			$links = "";
			if ($GLOBALS["tools_backups_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 'tools_backups'))
				$links .= "<a class=\"ewRowLink label label-warning \" href=\"" . $this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=tools_backups") . "\"><i class='icon-list-alt icon-white'></i> " . $Language->Phrase("EditLink") . "</a>";
			if ($links <> "") $oListOpt->Body .= "<br />" . $links;
		}

		// "detail_tools"
		$oListOpt = &$this->ListOptions->Items["detail_tools"];
		if ($Security->AllowList(CurrentProjectID() . 'tools')) {
			$oListOpt->Body = $Language->Phrase("DetailLink") . $Language->TablePhrase("tools", "TblCaption");
			$oListOpt->Body .= str_replace("%c", $this->tools_Count, $Language->Phrase("DetailCount"));
			$oListOpt->Body = "<a class=\"ewRowLink label label-warning\" href=\"toolslist.php?" . EW_TABLE_SHOW_MASTER . "=domains&id_domains=" . urlencode(strval($this->id_domains->CurrentValue)) . "\"><i class='icon-list-alt icon-white'></i> " . $oListOpt->Body . "</a>";
			$links = "";
			if ($GLOBALS["tools_grid"]->DetailEdit && $Security->CanEdit() && $Security->AllowEdit(CurrentProjectID() . 'tools'))
				$links .= "<a class=\"ewRowLink label label-warning \" href=\"" . $this->GetEditUrl(EW_TABLE_SHOW_DETAIL . "=tools") . "\"><i class='icon-list-alt icon-white'></i> " . $Language->Phrase("EditLink") . "</a>";
			if ($links <> "") $oListOpt->Body .= "<br />" . $links;
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
		$this->dominio->setDbValue($rs->fields('dominio'));
		$this->id_domains->setDbValue($rs->fields('id_domains'));
		$this->id_proyecto->setDbValue($rs->fields('id_proyecto'));
		$this->hosted_in->setDbValue($rs->fields('hosted_in'));
		$this->map->setDbValue($rs->fields('map'));
		$this->bid->setDbValue($rs->fields('bid'));
		$this->_language->setDbValue($rs->fields('language'));
		if (!isset($GLOBALS["entries_grid"])) $GLOBALS["entries_grid"] = new centries_grid;
		$sDetailFilter = $GLOBALS["entries"]->SqlDetailFilter_domains();
		$sDetailFilter = str_replace("@domain_id@", ew_AdjustSql($this->id_domains->DbValue), $sDetailFilter);
		$GLOBALS["entries"]->setCurrentMasterTable("domains");
		$sDetailFilter = $GLOBALS["entries"]->ApplyUserIDFilters($sDetailFilter);
		$this->entries_Count = $GLOBALS["entries"]->LoadRecordCount($sDetailFilter);
		if (!isset($GLOBALS["tools_translation_grid"])) $GLOBALS["tools_translation_grid"] = new ctools_translation_grid;
		$sDetailFilter = $GLOBALS["tools_translation"]->SqlDetailFilter_domains();
		$sDetailFilter = str_replace("@domain_id@", ew_AdjustSql($this->id_domains->DbValue), $sDetailFilter);
		$GLOBALS["tools_translation"]->setCurrentMasterTable("domains");
		$sDetailFilter = $GLOBALS["tools_translation"]->ApplyUserIDFilters($sDetailFilter);
		$this->tools_translation_Count = $GLOBALS["tools_translation"]->LoadRecordCount($sDetailFilter);
		if (!isset($GLOBALS["tools_backups_grid"])) $GLOBALS["tools_backups_grid"] = new ctools_backups_grid;
		$sDetailFilter = $GLOBALS["tools_backups"]->SqlDetailFilter_domains();
		$sDetailFilter = str_replace("@domain_id@", ew_AdjustSql($this->id_domains->DbValue), $sDetailFilter);
		$GLOBALS["tools_backups"]->setCurrentMasterTable("domains");
		$sDetailFilter = $GLOBALS["tools_backups"]->ApplyUserIDFilters($sDetailFilter);
		$this->tools_backups_Count = $GLOBALS["tools_backups"]->LoadRecordCount($sDetailFilter);
		if (!isset($GLOBALS["tools_grid"])) $GLOBALS["tools_grid"] = new ctools_grid;
		$sDetailFilter = $GLOBALS["tools"]->SqlDetailFilter_domains();
		$sDetailFilter = str_replace("@parent_domain@", ew_AdjustSql($this->id_domains->DbValue), $sDetailFilter);
		$GLOBALS["tools"]->setCurrentMasterTable("domains");
		$sDetailFilter = $GLOBALS["tools"]->ApplyUserIDFilters($sDetailFilter);
		$this->tools_Count = $GLOBALS["tools"]->LoadRecordCount($sDetailFilter);
	}

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("id_domains")) <> "")
			$this->id_domains->CurrentValue = $this->getKey("id_domains"); // id_domains
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
		// id_proyecto
		// hosted_in
		// map
		// bid
		// language

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// dominio
			$this->dominio->ViewValue = $this->dominio->CurrentValue;
			$this->dominio->ViewCustomAttributes = "";

			// id_domains
			$this->id_domains->ViewValue = $this->id_domains->CurrentValue;
			$this->id_domains->ViewCustomAttributes = "";

			// id_proyecto
			$this->id_proyecto->ViewValue = $this->id_proyecto->CurrentValue;
			$this->id_proyecto->ViewCustomAttributes = "";

			// hosted_in
			if (strval($this->hosted_in->CurrentValue) <> "") {
				switch ($this->hosted_in->CurrentValue) {
					case $this->hosted_in->FldTagValue(1):
						$this->hosted_in->ViewValue = $this->hosted_in->FldTagCaption(1) <> "" ? $this->hosted_in->FldTagCaption(1) : $this->hosted_in->CurrentValue;
						break;
					case $this->hosted_in->FldTagValue(2):
						$this->hosted_in->ViewValue = $this->hosted_in->FldTagCaption(2) <> "" ? $this->hosted_in->FldTagCaption(2) : $this->hosted_in->CurrentValue;
						break;
					case $this->hosted_in->FldTagValue(3):
						$this->hosted_in->ViewValue = $this->hosted_in->FldTagCaption(3) <> "" ? $this->hosted_in->FldTagCaption(3) : $this->hosted_in->CurrentValue;
						break;
					default:
						$this->hosted_in->ViewValue = $this->hosted_in->CurrentValue;
				}
			} else {
				$this->hosted_in->ViewValue = NULL;
			}
			$this->hosted_in->ViewCustomAttributes = "";

			// map
			if (strval($this->map->CurrentValue) <> "") {
				$sFilterWrk = "`idmapings`" . ew_SearchString("=", $this->map->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `idmapings`, `username` AS `DispFld`, `kind` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `mapings`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->map->ViewValue = $rswrk->fields('DispFld');
					$this->map->ViewValue .= ew_ValueSeparator(1,$domains->map) . $rswrk->fields('Disp2Fld');
					$rswrk->Close();
				} else {
					$this->map->ViewValue = $this->map->CurrentValue;
				}
			} else {
				$this->map->ViewValue = NULL;
			}
			$this->map->ViewCustomAttributes = "";

			// bid
			$this->bid->ViewValue = $this->bid->CurrentValue;
			$this->bid->ViewCustomAttributes = "";

			// language
			$this->_language->ViewValue = $this->_language->CurrentValue;
			$this->_language->ViewCustomAttributes = "";

			// dominio
			$this->dominio->LinkCustomAttributes = "";
			$this->dominio->HrefValue = "";
			$this->dominio->TooltipValue = "";

			// id_domains
			$this->id_domains->LinkCustomAttributes = "";
			$this->id_domains->HrefValue = "";
			$this->id_domains->TooltipValue = "";

			// hosted_in
			$this->hosted_in->LinkCustomAttributes = "";
			$this->hosted_in->HrefValue = "";
			$this->hosted_in->TooltipValue = "";
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
			if ($sMasterTblVar == "proyectos") {
				$bValidMaster = TRUE;
				if (@$_GET["idproyectos"] <> "") {
					$GLOBALS["proyectos"]->idproyectos->setQueryStringValue($_GET["idproyectos"]);
					$this->id_proyecto->setQueryStringValue($GLOBALS["proyectos"]->idproyectos->QueryStringValue);
					$this->id_proyecto->setSessionValue($this->id_proyecto->QueryStringValue);
					if (!is_numeric($GLOBALS["proyectos"]->idproyectos->QueryStringValue)) $bValidMaster = FALSE;
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
			if ($sMasterTblVar <> "proyectos") {
				if ($this->id_proyecto->QueryStringValue == "") $this->id_proyecto->setSessionValue("");
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
if (!isset($domains_list)) $domains_list = new cdomains_list();

// Page init
$domains_list->Page_Init();

// Page main
$domains_list->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var domains_list = new ew_Page("domains_list");
domains_list.PageID = "list"; // Page ID
var EW_PAGE_ID = domains_list.PageID; // For backward compatibility

// Form object
var fdomainslist = new ew_Form("fdomainslist");

// Form_CustomValidate event
fdomainslist.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fdomainslist.ValidateRequired = true;
<?php } else { ?>
fdomainslist.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php if (($domains->Export == "") || (EW_EXPORT_MASTER_RECORD && $domains->Export == "print")) { ?>
<?php
$gsMasterReturnUrl = "proyectoslist.php";
if ($domains_list->DbMasterFilter <> "" && $domains->getCurrentMasterTable() == "proyectos") {
	if ($domains_list->MasterRecordExists) {
		if ($domains->getCurrentMasterTable() == $domains->TableVar) $gsMasterReturnUrl .= "?" . EW_TABLE_SHOW_MASTER . "=";
?>
<span class="ewTitle ewMasterTableTitle"><i class="icon-resize-small"></i> <?php echo $Language->Phrase("MasterRecord") ?><?php echo $proyectos->TableCaption() ?>&nbsp;&nbsp;</span>
<?php /* jamesjara
if(!ISSET($_GET['export']))echo '<div class="btn-group"><button class="btn btn-info dropdown-toggle" data-toggle="dropdown">Exportar2<span class="caret"></span></button><ul class="dropdown-menu">';
$domains_list->ExportOptions->Render("body"); 
if(!ISSET($_GET['export']))echo '</ul></div> ';*/
?>
<a href="<?php echo $gsMasterReturnUrl ?>" id="a_GoBack" class="ewLink label"><i class="icon-arrow-left icon-white"></i><?php echo $Language->Phrase("BackToMasterRecordPage") ?></a>
<?php include_once "proyectosmaster.php" ?>
<?php
	}
}
?>
<?php } ?>
<?php
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$domains_list->TotalRecs = $domains->SelectRecordCount();
	} else {
		if ($domains_list->Recordset = $domains_list->LoadRecordset())
			$domains_list->TotalRecs = $domains_list->Recordset->RecordCount();
	}
	$domains_list->StartRec = 1;
	if ($domains_list->DisplayRecs <= 0 || ($domains->Export <> "" && $domains->ExportAll)) // Display all records
		$domains_list->DisplayRecs = $domains_list->TotalRecs;
	if (!($domains->Export <> "" && $domains->ExportAll))
		$domains_list->SetUpStartRec(); // Set up start record position
	if ($bSelectLimit)
		$domains_list->Recordset = $domains_list->LoadRecordset($domains_list->StartRec-1, $domains_list->DisplayRecs);
?>
<p style="white-spaceJAMES: nowrapJAMES;"><h4 id="ewPageCaption" class="page-header ewTitle ewTableTitle"> <?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $domains->TableCaption() ?>&nbsp;&nbsp;</h4>
<?php if ($domains->getCurrentMasterTable() == "") {  ?>
<?php /* jamesjara
if(!ISSET($_GET['export']))echo '<div class="btn-group"><button class="btn btn-info dropdown-toggle" data-toggle="dropdown">Exportar1<span class="caret"></span></button><ul class="dropdown-menu">';
$domains_list->ExportOptions->Render("body"); 
if(!ISSET($_GET['export']))echo '</ul></div> ';
*/?>
<?php } ?>
</p>
<?php $domains_list->ShowPageHeader(); ?>
<?php
$domains_list->ShowMessage();
?>
<div class="ewGridUpperPanel">
<?php if ($domains->CurrentAction <> "gridadd" && $domains->CurrentAction <> "gridedit") { ?>
<div>
<form name="ewpagerform" id="ewpagerform" class="ewForm" action="<?php echo ew_CurrentPage() ?>">
<div id="paginador" class="pagination pull-right">
<?php if (!isset($domains_list->Pager)) $domains_list->Pager = new cNumericPager($domains_list->StartRec, $domains_list->DisplayRecs, $domains_list->TotalRecs, $domains_list->RecRange) ?>
<?php if ($domains_list->Pager->RecordCount > 0) { ?>
	<ul><?php if ($domains_list->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $domains_list->PageUrl() ?>start=<?php echo $domains_list->Pager->FirstButton->Start ?>"><b><?php echo $Language->Phrase("PagerFirst") ?></b></a></li>
	<?php } ?>
	<?php if ($domains_list->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $domains_list->PageUrl() ?>start=<?php echo $domains_list->Pager->PrevButton->Start ?>"><b><?php echo $Language->Phrase("PagerPrevious") ?></b></a></li>
	<?php } ?>
	<?php foreach ($domains_list->Pager->Items as $PagerItem) { //jamesjara ?>
		<?php $classs=""; if (!$PagerItem->Enabled) $classs = 'class="active"';

		//jamesjara if ($PagerItem->Enabled) { ?>
			<li <?php echo $classs; ?>><a href="<?php echo $domains_list->PageUrl() ?>start=<?php echo $PagerItem->Start ?>">
		<?php //jamesjara } ?>
			<b><?php echo $PagerItem->Text ?></b>
		<?php //jamesjara if ($PagerItem->Enabled) { ?> 
			</a></li><?php //jamesjara } 
		?>
	<?php } ?>
	<?php if ($domains_list->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $domains_list->PageUrl() ?>start=<?php echo $domains_list->Pager->NextButton->Start ?>"><b><?php echo $Language->Phrase("PagerNext") ?></b></a></li>
	<?php } ?>
	<?php if ($domains_list->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $domains_list->PageUrl() ?>start=<?php echo $domains_list->Pager->LastButton->Start ?>"><b><?php echo $Language->Phrase("PagerLast") ?></b></a></li>
	<?php } ?>
	</ul>
	<?php if ($domains_list->Pager->ButtonCount > 0) { ?><?php } ?>
	<div style=" margin-top: 1px; " class="pull-right"><span class="label label-info"><i class="icon-ok-sign icon-white"></i> <?php echo $Language->Phrase("Record") ?>&nbsp;<?php echo $domains_list->Pager->FromIndex ?>&nbsp;<?php echo $Language->Phrase("To") ?>&nbsp;<?php echo $domains_list->Pager->ToIndex ?>&nbsp;<?php echo $Language->Phrase("Of") ?>&nbsp;<?php echo $domains_list->Pager->RecordCount ?></span></div>
<?php } else { ?>	
	<?php if ($Security->CanList()) { ?>
	<?php if ($domains_list->SearchWhere == "0=101") { ?>
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
$domains_list->ExportOptions->Render("body"); 
if(!ISSET($_GET['export'])) if ($_GET['a']!='gridadd') echo '</ul></div> ';
?>
<?php if ($Security->CanAdd()) { ?>
<?php if ($domains_list->AddUrl <> "") { ?>
<a class="ewGridLink btn btn-success" href="<?php echo $domains_list->AddUrl ?>"><?php echo $Language->Phrase("AddLink") ?></a>
<?php } ?>
<?php if ($entries_grid->DetailAdd && $Security->AllowAdd(CurrentProjectID() . 'entries')) { ?>
<a class="ewGridLink  btn btn-success" href="<?php echo $domains->GetAddUrl() . "?" . EW_TABLE_SHOW_DETAIL . "=entries" ?>"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $domains->TableCaption() ?>/<?php echo $entries->TableCaption() ?></a>
<?php } ?>
<?php if ($tools_translation_grid->DetailAdd && $Security->AllowAdd(CurrentProjectID() . 'tools_translation')) { ?>
<a class="ewGridLink  btn btn-success" href="<?php echo $domains->GetAddUrl() . "?" . EW_TABLE_SHOW_DETAIL . "=tools_translation" ?>"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $domains->TableCaption() ?>/<?php echo $tools_translation->TableCaption() ?></a>
<?php } ?>
<?php if ($tools_backups_grid->DetailAdd && $Security->AllowAdd(CurrentProjectID() . 'tools_backups')) { ?>
<a class="ewGridLink  btn btn-success" href="<?php echo $domains->GetAddUrl() . "?" . EW_TABLE_SHOW_DETAIL . "=tools_backups" ?>"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $domains->TableCaption() ?>/<?php echo $tools_backups->TableCaption() ?></a>
<?php } ?>
<?php if ($tools_grid->DetailAdd && $Security->AllowAdd(CurrentProjectID() . 'tools')) { ?>
<a class="ewGridLink  btn btn-success" href="<?php echo $domains->GetAddUrl() . "?" . EW_TABLE_SHOW_DETAIL . "=tools" ?>"><?php echo $Language->Phrase("AddLink") ?>&nbsp;<?php echo $domains->TableCaption() ?>/<?php echo $tools->TableCaption() ?></a>
<?php } ?>
<?php } ?>
</div>
<form name="fdomainslist" id="fdomainslist" class="ewForm" action="" method="post">
<input type="hidden" name="t" value="domains" />
<div id="gmp_domains" class="ewGridMiddlePanel row-fluid">
<ul class="thumbnails">
<?php if ($domains_list->TotalRecs > 0) { ?>
<table id="tbl_domainslist" class="ewTable ewTableSeparate table table-striped table-bordered ">
<?php echo $domains->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$domains_list->RenderListOptions();

// Render list options (header, left)
$domains_list->ListOptions->Render("header", "left");
?>
<?php if ($domains->dominio->Visible) { // dominio ?>
	<?php if ($domains->SortUrl($domains->dominio) == "") { ?>
		<th><span id="elh_domains_dominio" class="domains_dominio">
		<div class="ewTableHeaderBtn"><?php echo $domains->dominio->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div onmousedown="ew_Sort(event,'<?php echo $domains->SortUrl($domains->dominio) ?>',1);"><span id="elh_domains_dominio" class="domains_dominio">
			<div class="ewTableHeaderBtn">			
			<?php echo $domains->dominio->FldCaption() ?>
			<?php if ($domains->dominio->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($domains->dominio->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($domains->id_domains->Visible) { // id_domains ?>
	<?php if ($domains->SortUrl($domains->id_domains) == "") { ?>
		<th><span id="elh_domains_id_domains" class="domains_id_domains">
		<div class="ewTableHeaderBtn"><?php echo $domains->id_domains->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div onmousedown="ew_Sort(event,'<?php echo $domains->SortUrl($domains->id_domains) ?>',1);"><span id="elh_domains_id_domains" class="domains_id_domains">
			<div class="ewTableHeaderBtn">			
			<?php echo $domains->id_domains->FldCaption() ?>
			<?php if ($domains->id_domains->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($domains->id_domains->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($domains->hosted_in->Visible) { // hosted_in ?>
	<?php if ($domains->SortUrl($domains->hosted_in) == "") { ?>
		<th><span id="elh_domains_hosted_in" class="domains_hosted_in">
		<div class="ewTableHeaderBtn"><?php echo $domains->hosted_in->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div onmousedown="ew_Sort(event,'<?php echo $domains->SortUrl($domains->hosted_in) ?>',1);"><span id="elh_domains_hosted_in" class="domains_hosted_in">
			<div class="ewTableHeaderBtn">			
			<?php echo $domains->hosted_in->FldCaption() ?>
			<?php if ($domains->hosted_in->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($domains->hosted_in->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$domains_list->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
if ($domains->ExportAll && $domains->Export <> "") {
	$domains_list->StopRec = $domains_list->TotalRecs;
} else {

	// Set the last record to display
	if ($domains_list->TotalRecs > $domains_list->StartRec + $domains_list->DisplayRecs - 1)
		$domains_list->StopRec = $domains_list->StartRec + $domains_list->DisplayRecs - 1;
	else
		$domains_list->StopRec = $domains_list->TotalRecs;
}
$domains_list->RecCnt = $domains_list->StartRec - 1;
if ($domains_list->Recordset && !$domains_list->Recordset->EOF) {
	$domains_list->Recordset->MoveFirst();
	if (!$bSelectLimit && $domains_list->StartRec > 1)
		$domains_list->Recordset->Move($domains_list->StartRec - 1);
} elseif (!$domains->AllowAddDeleteRow && $domains_list->StopRec == 0) {
	$domains_list->StopRec = $domains->GridAddRowCount;
}

// Initialize aggregate
$domains->RowType = EW_ROWTYPE_AGGREGATEINIT;
$domains->ResetAttrs();
$domains_list->RenderRow();
while ($domains_list->RecCnt < $domains_list->StopRec) {
	$domains_list->RecCnt++;
	if (intval($domains_list->RecCnt) >= intval($domains_list->StartRec)) {
		$domains_list->RowCnt++;

		// Set up key count
		$domains_list->KeyCount = $domains_list->RowIndex;

		// Init row class and style
		$domains->ResetAttrs();
		$domains->CssClass = "";
		if ($domains->CurrentAction == "gridadd") {
		} else {
			$domains_list->LoadRowValues($domains_list->Recordset); // Load row values
		}
		$domains->RowType = EW_ROWTYPE_VIEW; // Render view

		// Set up row id / data-rowindex
		$domains->RowAttrs = array_merge($domains->RowAttrs, array('data-rowindex'=>$domains_list->RowCnt, 'id'=>'r' . $domains_list->RowCnt . '_domains', 'data-rowtype'=>$domains->RowType));

		// Render row
		$domains_list->RenderRow();

		// Render list options
		$domains_list->RenderListOptions();
?>
	<tr<?php echo $domains->RowAttributes() ?>>
<?php

// Render list options (body, left)
$domains_list->ListOptions->Render("body", "left", $domains_list->RowCnt);
?>
	<?php if ($domains->dominio->Visible) { // dominio ?>
		<td<?php echo $domains->dominio->CellAttributes() ?>><span id="el<?php echo $domains_list->RowCnt ?>_domains_dominio" class="domains_dominio">
<span<?php echo $domains->dominio->ViewAttributes() ?>>
<?php echo $domains->dominio->ListViewValue() ?></span>
</span></td>
	<?php } ?>
<a id="<?php echo $domains_list->PageObjName . "_row_" . $domains_list->RowCnt ?>"></a>
	<?php if ($domains->id_domains->Visible) { // id_domains ?>
		<td<?php echo $domains->id_domains->CellAttributes() ?>><span id="el<?php echo $domains_list->RowCnt ?>_domains_id_domains" class="domains_id_domains">
<div id="orig<?php echo $domains_list->RowCnt ?>_domains_id_domains" class="ewDisplayNone">
<span<?php echo $domains->id_domains->ViewAttributes() ?>>
<?php echo $domains->id_domains->ListViewValue() ?></span>
</div>
<?php
if( BASENAME($_SERVER['PHP_SELF']) == 'domainslist.php' ){   // solo si es domainslist.php
   //$data  = '<a class="operacion btn btn-success" name="BackUp"    id="'.CurrentTable()->id_domains->CurrentValue.'" href="#">BackUp</a> ';  
   //$data .= '<a class="operacion btn btn-primary" name="Traducion" id="'.CurrentTable()->id_domains->CurrentValue.'" href="#">Traducion</a>';   
   //echo $data;
} else    {
   //$data  = '<a class="operacion btn btn-success" name="BackUp"    id="'.$_GET['id_domains'].'" href="#">BackUp</a> ';  
   //$data .= '<a class="operacion btn btn-primary" name="Traducion" id="'.$_GET['id_domains'].'" href="#">Traducion</a>';    
   //echo $data;  
}     
                                               
if( BASENAME($_SERVER['PHP_SELF']) == 'tools_backupslist.php' ){   // solo si es tools_backupslist.php   
   $data  = '<a class="operacion btn btn-success" name="blogger_backup"    id="'.$_GET['id_domains'].'" href="#">BackUp</a> ';   
   echo $data;                                                                    
}                                           
if( BASENAME($_SERVER['PHP_SELF']) == 'tools_translationlist.php' ){   // solo si es tools_translationlist.php   
   $data  = '<a class="operacion btn btn-success" name="blogger_translate" id="'.$_GET['id_domains'].'" href="#">Traducion</a> ';   
   echo $data;                                                                    
}                                          
if( BASENAME($_SERVER['PHP_SELF']) == 'entrieslist.php' ){   // solo si es entrieslist.php                            
   $data   = '<a class="operacion btn btn-success" name="push_entries" id="'.$_GET['id_domains'].'" href="#">Push</a> ';   
   $data  .= '<a class="operacion btn btn-success" name="blogger_refresh_entries" id="'.$_GET['id_domains'].'" href="#">Refresh</a> ';    
   echo $data;                                                                    
}  
         
?>                  
<?php if (debug01) echo CurrentTable()->id_domains->CurrentValue; ?>
</span></td>
	<?php } ?>
	<?php if ($domains->hosted_in->Visible) { // hosted_in ?>
		<td<?php echo $domains->hosted_in->CellAttributes() ?>><span id="el<?php echo $domains_list->RowCnt ?>_domains_hosted_in" class="domains_hosted_in">
<span<?php echo $domains->hosted_in->ViewAttributes() ?>>
<?php echo $domains->hosted_in->ListViewValue() ?></span>
</span></td>
	<?php } ?>
<?php

// Render list options (body, right)
$domains_list->ListOptions->Render("body", "right", $domains_list->RowCnt);
?>
	</tr>
<?php
	}
	if ($domains->CurrentAction <> "gridadd")
		$domains_list->Recordset->MoveNext();
}
?>
</tbody>
<!--</table>-->
<?php } ?>
<?php if ($domains->CurrentAction == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
</form>
<?php

// Close recordset
if ($domains_list->Recordset)
	$domains_list->Recordset->Close();
?>
<script type="text/javascript">
fdomainslist.Init();
</script>
<?php
$domains_list->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$domains_list->Page_Terminate();
?>
