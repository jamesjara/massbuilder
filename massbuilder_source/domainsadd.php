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

$domains_add = NULL; // Initialize page object first

class cdomains_add extends cdomains {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{3554BCCA-7E88-4E52-9661-DF55D75275C9}";

	// Table name
	var $TableName = 'domains';

	// Page object name
	var $PageObjName = 'domains_add';

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

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Table object (proyectos)
		if (!isset($GLOBALS['proyectos'])) $GLOBALS['proyectos'] = new cproyectos();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'add', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'domains', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();
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
		if (!$Security->CanAdd()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate("domainslist.php");
		}
		$Security->UserID_Loading();
		if ($Security->IsLoggedIn()) $Security->LoadUserID();
		$Security->UserID_Loaded();

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"];

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
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $Priv = 0;
	var $OldRecordset;
	var $CopyRecord;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Set up master/detail parameters
		$this->SetUpMasterParms();

		// Process form if post back
		if (@$_POST["a_add"] <> "") {
			$this->CurrentAction = $_POST["a_add"]; // Get form action
			$this->CopyRecord = $this->LoadOldRecord(); // Load old recordset
			$this->LoadFormValues(); // Load form values
		} else { // Not post back

			// Load key values from QueryString
			$this->CopyRecord = TRUE;
			if (@$_GET["id_domains"] != "") {
				$this->id_domains->setQueryStringValue($_GET["id_domains"]);
				$this->setKey("id_domains", $this->id_domains->CurrentValue); // Set up key
			} else {
				$this->setKey("id_domains", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
				$this->LoadDefaultValues(); // Load default values
			}
		}

		// Set up detail parameters
		$this->SetUpDetailParms();

		// Validate form if post back
		if (@$_POST["a_add"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues(); // Restore form values
				$this->setFailureMessage($gsFormError);
			}
		}

		// Perform action based on action code
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "C": // Copy an existing record
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate($this->james_url( "domainslist.php" )); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					if ($this->getCurrentDetailTable() <> "") // Master/detail add
						$sReturnUrl = $this->GetDetailUrl();
					else
						$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "domainsview.php")
						$sReturnUrl = $this->GetViewUrl(); // View paging, return to view page with keyurl directly
					$this->Page_Terminate($sReturnUrl); // Clean up and return
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Add failed, restore form values
				}
		}

		// Render row based on row type
		$this->RowType = EW_ROWTYPE_ADD;  // Render add type

		// Render row
		$this->ResetAttrs();
		$this->RenderRow();
	}

	// Get upload files
	function GetUploadFiles() {
		global $objForm;

		// Get upload data
		$index = $objForm->Index; // Save form index
		$objForm->Index = -1;
		$confirmPage = (strval($objForm->GetValue("a_confirm")) <> "");
		$objForm->Index = $index; // Restore form index
	}

	// Load default values
	function LoadDefaultValues() {
		$this->dominio->CurrentValue = NULL;
		$this->dominio->OldValue = $this->dominio->CurrentValue;
		$this->id_proyecto->CurrentValue = NULL;
		$this->id_proyecto->OldValue = $this->id_proyecto->CurrentValue;
		$this->hosted_in->CurrentValue = NULL;
		$this->hosted_in->OldValue = $this->hosted_in->CurrentValue;
		$this->map->CurrentValue = NULL;
		$this->map->OldValue = $this->map->CurrentValue;
		$this->bid->CurrentValue = NULL;
		$this->bid->OldValue = $this->bid->CurrentValue;
		$this->_language->CurrentValue = NULL;
		$this->_language->OldValue = $this->_language->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->dominio->FldIsDetailKey) {
			$this->dominio->setFormValue($objForm->GetValue("x_dominio"));
		}
		if (!$this->id_proyecto->FldIsDetailKey) {
			$this->id_proyecto->setFormValue($objForm->GetValue("x_id_proyecto"));
		}
		if (!$this->hosted_in->FldIsDetailKey) {
			$this->hosted_in->setFormValue($objForm->GetValue("x_hosted_in"));
		}
		if (!$this->map->FldIsDetailKey) {
			$this->map->setFormValue($objForm->GetValue("x_map"));
		}
		if (!$this->bid->FldIsDetailKey) {
			$this->bid->setFormValue($objForm->GetValue("x_bid"));
		}
		if (!$this->_language->FldIsDetailKey) {
			$this->_language->setFormValue($objForm->GetValue("x__language"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->dominio->CurrentValue = $this->dominio->FormValue;
		$this->id_proyecto->CurrentValue = $this->id_proyecto->FormValue;
		$this->hosted_in->CurrentValue = $this->hosted_in->FormValue;
		$this->map->CurrentValue = $this->map->FormValue;
		$this->bid->CurrentValue = $this->bid->FormValue;
		$this->_language->CurrentValue = $this->_language->FormValue;
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

			// id_proyecto
			$this->id_proyecto->LinkCustomAttributes = "";
			$this->id_proyecto->HrefValue = "";
			$this->id_proyecto->TooltipValue = "";

			// hosted_in
			$this->hosted_in->LinkCustomAttributes = "";
			$this->hosted_in->HrefValue = "";
			$this->hosted_in->TooltipValue = "";

			// map
			$this->map->LinkCustomAttributes = "";
			$this->map->HrefValue = "";
			$this->map->TooltipValue = "";

			// bid
			$this->bid->LinkCustomAttributes = "";
			$this->bid->HrefValue = "";
			$this->bid->TooltipValue = "";

			// language
			$this->_language->LinkCustomAttributes = "";
			$this->_language->HrefValue = "";
			$this->_language->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// dominio
			$this->dominio->EditCustomAttributes = "";
			$this->dominio->EditValue = ew_HtmlEncode($this->dominio->CurrentValue);

			// id_proyecto
			$this->id_proyecto->EditCustomAttributes = "";
			if ($this->id_proyecto->getSessionValue() <> "") {
				$this->id_proyecto->CurrentValue = $this->id_proyecto->getSessionValue();
			$this->id_proyecto->ViewValue = $this->id_proyecto->CurrentValue;
			$this->id_proyecto->ViewCustomAttributes = "";
			} else {
			$this->id_proyecto->EditValue = ew_HtmlEncode($this->id_proyecto->CurrentValue);
			}

			// hosted_in
			$this->hosted_in->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->hosted_in->FldTagValue(1), $this->hosted_in->FldTagCaption(1) <> "" ? $this->hosted_in->FldTagCaption(1) : $this->hosted_in->FldTagValue(1));
			$arwrk[] = array($this->hosted_in->FldTagValue(2), $this->hosted_in->FldTagCaption(2) <> "" ? $this->hosted_in->FldTagCaption(2) : $this->hosted_in->FldTagValue(2));
			$arwrk[] = array($this->hosted_in->FldTagValue(3), $this->hosted_in->FldTagCaption(3) <> "" ? $this->hosted_in->FldTagCaption(3) : $this->hosted_in->FldTagValue(3));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->hosted_in->EditValue = $arwrk;

			// map
			$this->map->EditCustomAttributes = "";
			$sFilterWrk = "";
			$sSqlWrk = "SELECT `idmapings`, `username` AS `DispFld`, `kind` AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `mapings`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->map->EditValue = $arwrk;

			// bid
			$this->bid->EditCustomAttributes = "";
			$this->bid->EditValue = ew_HtmlEncode($this->bid->CurrentValue);

			// language
			$this->_language->EditCustomAttributes = "";
			$this->_language->EditValue = ew_HtmlEncode($this->_language->CurrentValue);

			// Edit refer script
			// dominio

			$this->dominio->HrefValue = "";

			// id_proyecto
			$this->id_proyecto->HrefValue = "";

			// hosted_in
			$this->hosted_in->HrefValue = "";

			// map
			$this->map->HrefValue = "";

			// bid
			$this->bid->HrefValue = "";

			// language
			$this->_language->HrefValue = "";
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
		if (!is_null($this->id_proyecto->FormValue) && $this->id_proyecto->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->id_proyecto->FldCaption());
		}
		if (!ew_CheckInteger($this->id_proyecto->FormValue)) {
			ew_AddMessage($gsFormError, $this->id_proyecto->FldErrMsg());
		}
		if (!is_null($this->hosted_in->FormValue) && $this->hosted_in->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->hosted_in->FldCaption());
		}
		if (!is_null($this->map->FormValue) && $this->map->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->map->FldCaption());
		}

		// Validate detail grid
		if ($this->getCurrentDetailTable() == "entries" && $GLOBALS["entries"]->DetailAdd) {
			if (!isset($GLOBALS["entries_grid"])) $GLOBALS["entries_grid"] = new centries_grid(); // get detail page object
			$GLOBALS["entries_grid"]->ValidateGridForm();
		}
		if ($this->getCurrentDetailTable() == "tools_translation" && $GLOBALS["tools_translation"]->DetailAdd) {
			if (!isset($GLOBALS["tools_translation_grid"])) $GLOBALS["tools_translation_grid"] = new ctools_translation_grid(); // get detail page object
			$GLOBALS["tools_translation_grid"]->ValidateGridForm();
		}
		if ($this->getCurrentDetailTable() == "tools_backups" && $GLOBALS["tools_backups"]->DetailAdd) {
			if (!isset($GLOBALS["tools_backups_grid"])) $GLOBALS["tools_backups_grid"] = new ctools_backups_grid(); // get detail page object
			$GLOBALS["tools_backups_grid"]->ValidateGridForm();
		}
		if ($this->getCurrentDetailTable() == "tools" && $GLOBALS["tools"]->DetailAdd) {
			if (!isset($GLOBALS["tools_grid"])) $GLOBALS["tools_grid"] = new ctools_grid(); // get detail page object
			$GLOBALS["tools_grid"]->ValidateGridForm();
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

		// Begin transaction
		if ($this->getCurrentDetailTable() <> "")
			$conn->BeginTrans();
		$rsnew = array();

		// dominio
		$this->dominio->SetDbValueDef($rsnew, $this->dominio->CurrentValue, NULL, FALSE);

		// id_proyecto
		$this->id_proyecto->SetDbValueDef($rsnew, $this->id_proyecto->CurrentValue, 0, FALSE);

		// hosted_in
		$this->hosted_in->SetDbValueDef($rsnew, $this->hosted_in->CurrentValue, NULL, FALSE);

		// map
		$this->map->SetDbValueDef($rsnew, $this->map->CurrentValue, NULL, FALSE);

		// bid
		$this->bid->SetDbValueDef($rsnew, $this->bid->CurrentValue, NULL, FALSE);

		// language
		$this->_language->SetDbValueDef($rsnew, $this->_language->CurrentValue, NULL, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);
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
			$this->id_domains->setDbValue($conn->Insert_ID());
			$rsnew['id_domains'] = $this->id_domains->DbValue;
		}

		// Add detail records
		if ($AddRow) {
			if ($this->getCurrentDetailTable() == "entries" && $GLOBALS["entries"]->DetailAdd) {
				$GLOBALS["entries"]->domain_id->setSessionValue($this->id_domains->CurrentValue); // Set master key
				if (!isset($GLOBALS["entries_grid"])) $GLOBALS["entries_grid"] = new centries_grid(); // get detail page object
				$AddRow = $GLOBALS["entries_grid"]->GridInsert();
				if (!$AddRow)
					$GLOBALS["entries"]->domain_id->setSessionValue(""); // Clear master key if insert failed
			}
			if ($this->getCurrentDetailTable() == "tools_translation" && $GLOBALS["tools_translation"]->DetailAdd) {
				$GLOBALS["tools_translation"]->domain_id->setSessionValue($this->id_domains->CurrentValue); // Set master key
				if (!isset($GLOBALS["tools_translation_grid"])) $GLOBALS["tools_translation_grid"] = new ctools_translation_grid(); // get detail page object
				$AddRow = $GLOBALS["tools_translation_grid"]->GridInsert();
				if (!$AddRow)
					$GLOBALS["tools_translation"]->domain_id->setSessionValue(""); // Clear master key if insert failed
			}
			if ($this->getCurrentDetailTable() == "tools_backups" && $GLOBALS["tools_backups"]->DetailAdd) {
				$GLOBALS["tools_backups"]->domain_id->setSessionValue($this->id_domains->CurrentValue); // Set master key
				if (!isset($GLOBALS["tools_backups_grid"])) $GLOBALS["tools_backups_grid"] = new ctools_backups_grid(); // get detail page object
				$AddRow = $GLOBALS["tools_backups_grid"]->GridInsert();
				if (!$AddRow)
					$GLOBALS["tools_backups"]->domain_id->setSessionValue(""); // Clear master key if insert failed
			}
			if ($this->getCurrentDetailTable() == "tools" && $GLOBALS["tools"]->DetailAdd) {
				$GLOBALS["tools"]->parent_domain->setSessionValue($this->id_domains->CurrentValue); // Set master key
				if (!isset($GLOBALS["tools_grid"])) $GLOBALS["tools_grid"] = new ctools_grid(); // get detail page object
				$AddRow = $GLOBALS["tools_grid"]->GridInsert();
				if (!$AddRow)
					$GLOBALS["tools"]->parent_domain->setSessionValue(""); // Clear master key if insert failed
			}
		}

		// Commit/Rollback transaction
		if ($this->getCurrentDetailTable() <> "") {
			if ($AddRow) {
				$conn->CommitTrans(); // Commit transaction
			} else {
				$conn->RollbackTrans(); // Rollback transaction
			}
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

	// Set up detail parms based on QueryString
	function SetUpDetailParms() {

		// Get the keys for master table
		if (isset($_GET[EW_TABLE_SHOW_DETAIL])) {
			$sDetailTblVar = $_GET[EW_TABLE_SHOW_DETAIL];
			$this->setCurrentDetailTable($sDetailTblVar);
		} else {
			$sDetailTblVar = $this->getCurrentDetailTable();
		}
		if ($sDetailTblVar <> "") {
			if ($sDetailTblVar == "entries") {
				if (!isset($GLOBALS["entries_grid"]))
					$GLOBALS["entries_grid"] = new centries_grid;
				if ($GLOBALS["entries_grid"]->DetailAdd) {
					if ($this->CopyRecord)
						$GLOBALS["entries_grid"]->CurrentMode = "copy";
					else
						$GLOBALS["entries_grid"]->CurrentMode = "add";
					$GLOBALS["entries_grid"]->CurrentAction = "gridadd";

					// Save current master table to detail table
					$GLOBALS["entries_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["entries_grid"]->setStartRecordNumber(1);
					$GLOBALS["entries_grid"]->domain_id->FldIsDetailKey = TRUE;
					$GLOBALS["entries_grid"]->domain_id->CurrentValue = $this->id_domains->CurrentValue;
					$GLOBALS["entries_grid"]->domain_id->setSessionValue($GLOBALS["entries_grid"]->domain_id->CurrentValue);
				}
			}
			if ($sDetailTblVar == "tools_translation") {
				if (!isset($GLOBALS["tools_translation_grid"]))
					$GLOBALS["tools_translation_grid"] = new ctools_translation_grid;
				if ($GLOBALS["tools_translation_grid"]->DetailAdd) {
					if ($this->CopyRecord)
						$GLOBALS["tools_translation_grid"]->CurrentMode = "copy";
					else
						$GLOBALS["tools_translation_grid"]->CurrentMode = "add";
					$GLOBALS["tools_translation_grid"]->CurrentAction = "gridadd";

					// Save current master table to detail table
					$GLOBALS["tools_translation_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["tools_translation_grid"]->setStartRecordNumber(1);
					$GLOBALS["tools_translation_grid"]->domain_id->FldIsDetailKey = TRUE;
					$GLOBALS["tools_translation_grid"]->domain_id->CurrentValue = $this->id_domains->CurrentValue;
					$GLOBALS["tools_translation_grid"]->domain_id->setSessionValue($GLOBALS["tools_translation_grid"]->domain_id->CurrentValue);
				}
			}
			if ($sDetailTblVar == "tools_backups") {
				if (!isset($GLOBALS["tools_backups_grid"]))
					$GLOBALS["tools_backups_grid"] = new ctools_backups_grid;
				if ($GLOBALS["tools_backups_grid"]->DetailAdd) {
					if ($this->CopyRecord)
						$GLOBALS["tools_backups_grid"]->CurrentMode = "copy";
					else
						$GLOBALS["tools_backups_grid"]->CurrentMode = "add";
					$GLOBALS["tools_backups_grid"]->CurrentAction = "gridadd";

					// Save current master table to detail table
					$GLOBALS["tools_backups_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["tools_backups_grid"]->setStartRecordNumber(1);
					$GLOBALS["tools_backups_grid"]->domain_id->FldIsDetailKey = TRUE;
					$GLOBALS["tools_backups_grid"]->domain_id->CurrentValue = $this->id_domains->CurrentValue;
					$GLOBALS["tools_backups_grid"]->domain_id->setSessionValue($GLOBALS["tools_backups_grid"]->domain_id->CurrentValue);
				}
			}
			if ($sDetailTblVar == "tools") {
				if (!isset($GLOBALS["tools_grid"]))
					$GLOBALS["tools_grid"] = new ctools_grid;
				if ($GLOBALS["tools_grid"]->DetailAdd) {
					if ($this->CopyRecord)
						$GLOBALS["tools_grid"]->CurrentMode = "copy";
					else
						$GLOBALS["tools_grid"]->CurrentMode = "add";
					$GLOBALS["tools_grid"]->CurrentAction = "gridadd";

					// Save current master table to detail table
					$GLOBALS["tools_grid"]->setCurrentMasterTable($this->TableVar);
					$GLOBALS["tools_grid"]->setStartRecordNumber(1);
					$GLOBALS["tools_grid"]->parent_domain->FldIsDetailKey = TRUE;
					$GLOBALS["tools_grid"]->parent_domain->CurrentValue = $this->id_domains->CurrentValue;
					$GLOBALS["tools_grid"]->parent_domain->setSessionValue($GLOBALS["tools_grid"]->parent_domain->CurrentValue);
				}
			}
		}
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
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($domains_add)) $domains_add = new cdomains_add();

// Page init
$domains_add->Page_Init();

// Page main
$domains_add->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var domains_add = new ew_Page("domains_add");
domains_add.PageID = "add"; // Page ID
var EW_PAGE_ID = domains_add.PageID; // For backward compatibility

// Form object
var fdomainsadd = new ew_Form("fdomainsadd");

// Validate form
fdomainsadd.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	this.PostAutoSuggest();	
	if (fobj.a_confirm && fobj.a_confirm.value == "F")
		return true;
	var elm, aelm;
	var rowcnt = 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // rowcnt == 0 => Inline-Add
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = "";
		elm = fobj.elements["x" + infix + "_id_proyecto"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($domains->id_proyecto->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_id_proyecto"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($domains->id_proyecto->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_hosted_in"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($domains->hosted_in->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_map"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($domains->map->FldCaption()) ?>");

		// Set up row object
		ew_ElementsToRow(fobj, infix);

		// Fire Form_CustomValidate event
		if (!this.Form_CustomValidate(fobj))
			return false;
	}

	// Process detail page
	if (fobj.detailpage && fobj.detailpage.value && ewForms[fobj.detailpage.value])
		return ewForms[fobj.detailpage.value].Validate(fobj);
	return true;
}

// Form_CustomValidate event
fdomainsadd.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fdomainsadd.ValidateRequired = true;
<?php } else { ?>
fdomainsadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fdomainsadd.Lists["x_map"] = {"LinkField":"x_idmapings","Ajax":null,"AutoFill":false,"DisplayFields":["x_username","x_kind","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<h4 id="ewPageCaption" class="page-header ewTitle ewTableTitle"> <?php echo $Language->Phrase("Add") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $domains->TableCaption() ?></h4>
<a href="<?php echo $domains->getReturnUrl() ?>" id="a_GoBack" class="ewLink label"><i class="icon-arrow-left icon-white"></i> <?php echo $Language->Phrase("GoBack") ?></a>
<?php $domains_add->ShowPageHeader(); ?>
<?php
$domains_add->ShowMessage();
?>
<form name="fdomainsadd" id="fdomainsadd" class="ewForm" action="<?php echo ew_CurrentPage() ?>" method="post" enctype="multipart/form-data" onsubmit="return ewForms[this.id].Submit();">
<input type="hidden" name="t" value="domains" />
<input type="hidden" name="a_add" id="a_add" value="A" />
<table id="tbl_domainsadd" class="ewTable ewTableSeparate table table-striped ">
<?php if ($domains->dominio->Visible) { // dominio ?>
	<tr id="r_dominio"<?php echo $domains->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_domains_dominio">
		<b><?php echo $domains->dominio->FldCaption() ?></b>
		</span></td>
		<td<?php echo $domains->dominio->CellAttributes() ?>><span id="el_domains_dominio">
<input type="text" name="x_dominio" id="x_dominio" size="30" maxlength="145" value="<?php echo $domains->dominio->EditValue ?>"<?php echo $domains->dominio->EditAttributes() ?> />
</span><?php echo $domains->dominio->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($domains->id_proyecto->Visible) { // id_proyecto ?>
	<tr id="r_id_proyecto"<?php echo $domains->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_domains_id_proyecto">
		<b><?php echo $domains->id_proyecto->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></b>
		</span></td>
		<td<?php echo $domains->id_proyecto->CellAttributes() ?>><span id="el_domains_id_proyecto">
<?php if ($domains->id_proyecto->getSessionValue() <> "") { ?>
<span<?php echo $domains->id_proyecto->ViewAttributes() ?>>
<?php echo $domains->id_proyecto->ViewValue ?></span>
<input type="hidden" id="x_id_proyecto" name="x_id_proyecto" value="<?php echo ew_HtmlEncode($domains->id_proyecto->CurrentValue) ?>">
<?php } else { ?>
<input type="text" name="x_id_proyecto" id="x_id_proyecto" size="30" value="<?php echo $domains->id_proyecto->EditValue ?>"<?php echo $domains->id_proyecto->EditAttributes() ?> />
<?php } ?>
</span><?php echo $domains->id_proyecto->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($domains->hosted_in->Visible) { // hosted_in ?>
	<tr id="r_hosted_in"<?php echo $domains->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_domains_hosted_in">
		<b><?php echo $domains->hosted_in->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></b>
		</span></td>
		<td<?php echo $domains->hosted_in->CellAttributes() ?>><span id="el_domains_hosted_in">
<select id="x_hosted_in" name="x_hosted_in"<?php echo $domains->hosted_in->EditAttributes() ?>>
<?php
if (is_array($domains->hosted_in->EditValue)) {
	$arwrk = $domains->hosted_in->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($domains->hosted_in->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
?>
</select>
</span><?php echo $domains->hosted_in->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($domains->map->Visible) { // map ?>
	<tr id="r_map"<?php echo $domains->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_domains_map">
		<b><?php echo $domains->map->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></b>
		</span></td>
		<td<?php echo $domains->map->CellAttributes() ?>><span id="el_domains_map">
<select id="x_map" name="x_map"<?php echo $domains->map->EditAttributes() ?>>
<?php
if (is_array($domains->map->EditValue)) {
	$arwrk = $domains->map->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($domains->map->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
<?php if ($arwrk[$rowcntwrk][2] <> "") { ?>
<?php echo ew_ValueSeparator(1,$domains->map) ?><?php echo $arwrk[$rowcntwrk][2] ?>
<?php } ?>
</option>
<?php
	}
}
?>
</select>
<script type="text/javascript">
fdomainsadd.Lists["x_map"].Options = <?php echo (is_array($domains->map->EditValue)) ? ew_ArrayToJson($domains->map->EditValue, 1) : "[]" ?>;
</script>
</span><?php echo $domains->map->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($domains->bid->Visible) { // bid ?>
	<tr id="r_bid"<?php echo $domains->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_domains_bid">
		<b><?php echo $domains->bid->FldCaption() ?></b>
		</span></td>
		<td<?php echo $domains->bid->CellAttributes() ?>><span id="el_domains_bid">
<input type="text" name="x_bid" id="x_bid" size="30" maxlength="45" value="<?php echo $domains->bid->EditValue ?>"<?php echo $domains->bid->EditAttributes() ?> />
</span><?php echo $domains->bid->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($domains->_language->Visible) { // language ?>
	<tr id="r__language"<?php echo $domains->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_domains__language">
		<b><?php echo $domains->_language->FldCaption() ?></b>
		</span></td>
		<td<?php echo $domains->_language->CellAttributes() ?>><span id="el_domains__language">
<input type="text" name="x__language" id="x__language" size="30" maxlength="45" value="<?php echo $domains->_language->EditValue ?>"<?php echo $domains->_language->EditAttributes() ?> />
</span><?php echo $domains->_language->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</div>
<?php if ($domains->getCurrentDetailTable() == "entries" && $entries->DetailAdd) { ?>
<br />
<?php include_once "entriesgrid.php" ?>
<br />
<?php } ?>
<?php if ($domains->getCurrentDetailTable() == "tools_translation" && $tools_translation->DetailAdd) { ?>
<br />
<?php include_once "tools_translationgrid.php" ?>
<br />
<?php } ?>
<?php if ($domains->getCurrentDetailTable() == "tools_backups" && $tools_backups->DetailAdd) { ?>
<br />
<?php include_once "tools_backupsgrid.php" ?>
<br />
<?php } ?>
<?php if ($domains->getCurrentDetailTable() == "tools" && $tools->DetailAdd) { ?>
<br />
<?php include_once "toolsgrid.php" ?>
<br />
<?php } ?>
<input type="submit"  class="btn btn-large btn-success"  name="btnAction" id="btnAction" value="<?php echo ew_BtnCaption($Language->Phrase("AddBtn")) ?>" />
</form>
<script type="text/javascript">
fdomainsadd.Init();
</script>
<?php
$domains_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$domains_add->Page_Terminate();
?>
