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

$tools_edit = NULL; // Initialize page object first

class ctools_edit extends ctools {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{3554BCCA-7E88-4E52-9661-DF55D75275C9}";

	// Table name
	var $TableName = 'tools';

	// Page object name
	var $PageObjName = 'tools_edit';

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

		// Table object (tools)
		if (!isset($GLOBALS["tools"])) {
			$GLOBALS["tools"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["tools"];
		}

		// Table object (domains)
		if (!isset($GLOBALS['domains'])) $GLOBALS['domains'] = new cdomains();

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'tools', TRUE);

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
		if (!$Security->CanEdit()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate("toolslist.php");
		}
		$Security->UserID_Loading();
		if ($Security->IsLoggedIn()) $Security->LoadUserID();
		$Security->UserID_Loaded();

		// Create form object
		$objForm = new cFormObj();
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
	var $DbMasterFilter;
	var $DbDetailFilter;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Load key from QueryString
		if (@$_GET["idtools"] <> "")
			$this->idtools->setQueryStringValue($_GET["idtools"]);

		// Set up master detail parameters
		$this->SetUpMasterParms();

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->idtools->CurrentValue == "")
			$this->Page_Terminate($this->james_url( "toolslist.php" )); // Invalid key, return to list

		// Validate form if post back
		if (@$_POST["a_edit"] <> "") {
			if (!$this->ValidateForm()) {
				$this->CurrentAction = ""; // Form error, reset action
				$this->setFailureMessage($gsFormError);
				$this->EventCancelled = TRUE; // Event cancelled
				$this->RestoreFormValues();
			}
		}
		switch ($this->CurrentAction) {
			case "I": // Get a record to display
				if (!$this->LoadRow()) { // Load record based on key
					if ($this->getFailureMessage() == "") $this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
					$this->Page_Terminate($this->james_url( "toolslist.php" )); // No matching record, return to list
				}
				break;
			Case "U": // Update
				$this->SendEmail = TRUE; // Send email on update success
				if ($this->EditRow()) { // Update record based on key
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("UpdateSuccess")); // Update success
					$sReturnUrl = $this->getReturnUrl();
					$this->Page_Terminate($sReturnUrl); // Return to caller
				} else {
					$this->EventCancelled = TRUE; // Event cancelled
					$this->RestoreFormValues(); // Restore form values if update failed
				}
		}

		// Render the record
		$this->RowType = EW_ROWTYPE_EDIT; // Render as Edit
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

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->idtools->FldIsDetailKey)
			$this->idtools->setFormValue($objForm->GetValue("x_idtools"));
		if (!$this->target_domain->FldIsDetailKey) {
			$this->target_domain->setFormValue($objForm->GetValue("x_target_domain"));
		}
		if (!$this->type->FldIsDetailKey) {
			$this->type->setFormValue($objForm->GetValue("x_type"));
		}
		if (!$this->url->FldIsDetailKey) {
			$this->url->setFormValue($objForm->GetValue("x_url"));
		}
		if (!$this->time->FldIsDetailKey) {
			$this->time->setFormValue($objForm->GetValue("x_time"));
			$this->time->CurrentValue = ew_UnFormatDateTime($this->time->CurrentValue, 5);
		}
		if (!$this->parent_domain->FldIsDetailKey) {
			$this->parent_domain->setFormValue($objForm->GetValue("x_parent_domain"));
		}
		if (!$this->Descripcion->FldIsDetailKey) {
			$this->Descripcion->setFormValue($objForm->GetValue("x_Descripcion"));
		}
		if (!$this->tags->FldIsDetailKey) {
			$this->tags->setFormValue($objForm->GetValue("x_tags"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->idtools->CurrentValue = $this->idtools->FormValue;
		$this->target_domain->CurrentValue = $this->target_domain->FormValue;
		$this->type->CurrentValue = $this->type->FormValue;
		$this->url->CurrentValue = $this->url->FormValue;
		$this->time->CurrentValue = $this->time->FormValue;
		$this->time->CurrentValue = ew_UnFormatDateTime($this->time->CurrentValue, 5);
		$this->parent_domain->CurrentValue = $this->parent_domain->FormValue;
		$this->Descripcion->CurrentValue = $this->Descripcion->FormValue;
		$this->tags->CurrentValue = $this->tags->FormValue;
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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// idtools
			$this->idtools->EditCustomAttributes = "";
			$this->idtools->EditValue = $this->idtools->CurrentValue;
			$this->idtools->ViewCustomAttributes = "";

			// target_domain
			$this->target_domain->EditCustomAttributes = "";
			if (trim(strval($this->target_domain->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id_domains`" . ew_SearchString("=", $this->target_domain->CurrentValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT `id_domains`, `dominio` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `domains`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->target_domain->EditValue = $arwrk;

			// type
			$this->type->EditCustomAttributes = "";
			$arwrk = array();
			$arwrk[] = array($this->type->FldTagValue(1), $this->type->FldTagCaption(1) <> "" ? $this->type->FldTagCaption(1) : $this->type->FldTagValue(1));
			$arwrk[] = array($this->type->FldTagValue(2), $this->type->FldTagCaption(2) <> "" ? $this->type->FldTagCaption(2) : $this->type->FldTagValue(2));
			$arwrk[] = array($this->type->FldTagValue(3), $this->type->FldTagCaption(3) <> "" ? $this->type->FldTagCaption(3) : $this->type->FldTagValue(3));
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect")));
			$this->type->EditValue = $arwrk;

			// url
			$this->url->EditCustomAttributes = "";
			$this->url->EditValue = ew_HtmlEncode($this->url->CurrentValue);

			// time
			// parent_domain

			$this->parent_domain->EditCustomAttributes = "";
			if ($this->parent_domain->getSessionValue() <> "") {
				$this->parent_domain->CurrentValue = $this->parent_domain->getSessionValue();
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
			} else {
			if (trim(strval($this->parent_domain->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id_domains`" . ew_SearchString("=", $this->parent_domain->CurrentValue, EW_DATATYPE_NUMBER);
			}
			$sSqlWrk = "SELECT `id_domains`, `dominio` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `domains`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->parent_domain->EditValue = $arwrk;
			}

			// Descripcion
			$this->Descripcion->EditCustomAttributes = "";
			$this->Descripcion->EditValue = ew_HtmlEncode($this->Descripcion->CurrentValue);

			// tags
			$this->tags->EditCustomAttributes = "";
			$this->tags->EditValue = ew_HtmlEncode($this->tags->CurrentValue);

			// Edit refer script
			// idtools

			$this->idtools->HrefValue = "";

			// target_domain
			$this->target_domain->HrefValue = "";

			// type
			$this->type->HrefValue = "";

			// url
			$this->url->HrefValue = "";

			// time
			$this->time->HrefValue = "";

			// parent_domain
			$this->parent_domain->HrefValue = "";

			// Descripcion
			$this->Descripcion->HrefValue = "";

			// tags
			$this->tags->HrefValue = "";
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
		if (!ew_CheckInteger($this->idtools->FormValue)) {
			ew_AddMessage($gsFormError, $this->idtools->FldErrMsg());
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

	// Update record based on key values
	function EditRow() {
		global $conn, $Security, $Language;
		$sFilter = $this->KeyFilter();
		$this->CurrentFilter = $sFilter;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = 'ew_ErrorFn';
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE)
			return FALSE;
		if ($rs->EOF) {
			$EditRow = FALSE; // Update Failed
		} else {

			// Save old values
			$rsold = &$rs->fields;
			$rsnew = array();

			// target_domain
			$this->target_domain->SetDbValueDef($rsnew, $this->target_domain->CurrentValue, NULL, $this->target_domain->ReadOnly);

			// type
			$this->type->SetDbValueDef($rsnew, $this->type->CurrentValue, NULL, $this->type->ReadOnly);

			// url
			$this->url->SetDbValueDef($rsnew, $this->url->CurrentValue, NULL, $this->url->ReadOnly);

			// time
			$this->time->SetDbValueDef($rsnew, ew_CurrentDateTime(), NULL);
			$rsnew['time'] = &$this->time->DbValue;

			// parent_domain
			$this->parent_domain->SetDbValueDef($rsnew, $this->parent_domain->CurrentValue, NULL, $this->parent_domain->ReadOnly);

			// Descripcion
			$this->Descripcion->SetDbValueDef($rsnew, $this->Descripcion->CurrentValue, NULL, $this->Descripcion->ReadOnly);

			// tags
			$this->tags->SetDbValueDef($rsnew, $this->tags->CurrentValue, NULL, $this->tags->ReadOnly);

			// Call Row Updating event
			$bUpdateRow = $this->Row_Updating($rsold, $rsnew);
			if ($bUpdateRow) {
				$conn->raiseErrorFn = 'ew_ErrorFn';
				if (count($rsnew) > 0)
					$EditRow = $this->Update($rsnew);
				else
					$EditRow = TRUE; // No field to update
				$conn->raiseErrorFn = '';
			} else {
				if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

					// Use the message, do nothing
				} elseif ($this->CancelMessage <> "") {
					$this->setFailureMessage($this->CancelMessage);
					$this->CancelMessage = "";
				} else {
					$this->setFailureMessage($Language->Phrase("UpdateCancelled"));
				}
				$EditRow = FALSE;
			}
		}

		// Call Row_Updated event
		if ($EditRow)
			$this->Row_Updated($rsold, $rsnew);
		$rs->Close();
		return $EditRow;
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
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($tools_edit)) $tools_edit = new ctools_edit();

// Page init
$tools_edit->Page_Init();

// Page main
$tools_edit->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var tools_edit = new ew_Page("tools_edit");
tools_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = tools_edit.PageID; // For backward compatibility

// Form object
var ftoolsedit = new ew_Form("ftoolsedit");

// Validate form
ftoolsedit.Validate = function(fobj) {
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
		elm = fobj.elements["x" + infix + "_idtools"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($tools->idtools->FldErrMsg()) ?>");

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
ftoolsedit.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftoolsedit.ValidateRequired = true;
<?php } else { ?>
ftoolsedit.ValidateRequired = false; 
<?php } ?>

// Multi-Page properties
ftoolsedit.MultiPage = new ew_MultiPage("ftoolsedit");
ftoolsedit.MultiPage.Elements = [["x_idtools",1],["x_target_domain",1],["x_type",1],["x_url",1],["x_parent_domain",1],["x_Descripcion",1],["x_tags",2]];

// Dynamic selection lists
ftoolsedit.Lists["x_target_domain"] = {"LinkField":"x_id_domains","Ajax":true,"AutoFill":false,"DisplayFields":["x_dominio","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
ftoolsedit.Lists["x_parent_domain"] = {"LinkField":"x_id_domains","Ajax":true,"AutoFill":false,"DisplayFields":["x_dominio","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<h4 id="ewPageCaption" class="page-header ewTitle ewTableTitle"> <?php echo $Language->Phrase("Edit") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $tools->TableCaption() ?></h4>
<a href="<?php echo $tools->getReturnUrl() ?>" id="a_GoBack" class="ewLink label"><i class="icon-arrow-left icon-white"></i> <?php echo $Language->Phrase("GoBack") ?></a>
<?php $tools_edit->ShowPageHeader(); ?>
<?php
$tools_edit->ShowMessage();
?>
<form name="ftoolsedit" id="ftoolsedit" class="ewForm" action="<?php echo ew_CurrentPage() ?>" method="post" onsubmit="return ewForms[this.id].Submit();">
<input type="hidden" name="t" value="tools" />
<input type="hidden" name="a_edit" id="a_edit" value="U" />
<div id="tools_edit" class="y-ui-navset tabbable">
	<ul class="y-ui-nav nav nav-tabs">
		<li class="active"  ><a  data-toggle="tab" href="#tab_tools1"><em><span><?php echo $tools->PageCaption(1) ?></span></em></a></li>
		<li  ><a  data-toggle="tab" href="#tab_tools2"><em><span><?php echo $tools->PageCaption(2) ?></span></em></a></li>
	</ul>
	<div class="y-ui-content tab-content">
		<div  class="tab-pane active" id="tab_tools1">
<table id="tbl_toolsedit1" class="ewTable ewTableSeparate table table-striped ">
<?php if ($tools->idtools->Visible) { // idtools ?>
	<tr id="r_idtools"<?php echo $tools->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tools_idtools">
		<b><?php echo $tools->idtools->FldCaption() ?></b>
		</span></td>
		<td<?php echo $tools->idtools->CellAttributes() ?>><span id="el_tools_idtools">
<div id="orig_tools_idtools" class="ewDisplayNone">
<span<?php echo $tools->idtools->ViewAttributes() ?>>
<?php echo $tools->idtools->EditValue ?></span>
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
<input type="hidden" name="x_idtools" id="x_idtools" value="<?php echo ew_HtmlEncode($tools->idtools->CurrentValue) ?>" />
</span><?php echo $tools->idtools->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tools->target_domain->Visible) { // target_domain ?>
	<tr id="r_target_domain"<?php echo $tools->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tools_target_domain">
		<b><?php echo $tools->target_domain->FldCaption() ?></b>
		</span></td>
		<td<?php echo $tools->target_domain->CellAttributes() ?>><span id="el_tools_target_domain">
<select id="x_target_domain" name="x_target_domain"<?php echo $tools->target_domain->EditAttributes() ?>>
<?php
if (is_array($tools->target_domain->EditValue)) {
	$arwrk = $tools->target_domain->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($tools->target_domain->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php
$sSqlWrk = "SELECT `id_domains`, `dominio` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `domains`";
$sWhereWrk = "";
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x_target_domain" id="s_x_target_domain" value="s=<?php echo TEAencrypt($sSqlWrk) ?>&fn=<?php echo urlencode($tools->target_domain->LookupFn) ?>&f0=<?php echo TEAencrypt("`id_domains` = {filter_value}"); ?>&t0=3" />
</span><?php echo $tools->target_domain->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tools->type->Visible) { // type ?>
	<tr id="r_type"<?php echo $tools->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tools_type">
		<b><?php echo $tools->type->FldCaption() ?></b>
		</span></td>
		<td<?php echo $tools->type->CellAttributes() ?>><span id="el_tools_type">
<select id="x_type" name="x_type"<?php echo $tools->type->EditAttributes() ?>>
<?php
if (is_array($tools->type->EditValue)) {
	$arwrk = $tools->type->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($tools->type->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
</span><?php echo $tools->type->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tools->url->Visible) { // url ?>
	<tr id="r_url"<?php echo $tools->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tools_url">
		<b><?php echo $tools->url->FldCaption() ?></b>
		</span></td>
		<td<?php echo $tools->url->CellAttributes() ?>><span id="el_tools_url">
<input type="text" name="x_url" id="x_url" size="30" maxlength="245" value="<?php echo $tools->url->EditValue ?>"<?php echo $tools->url->EditAttributes() ?> />
</span><?php echo $tools->url->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tools->parent_domain->Visible) { // parent_domain ?>
	<tr id="r_parent_domain"<?php echo $tools->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tools_parent_domain">
		<b><?php echo $tools->parent_domain->FldCaption() ?></b>
		</span></td>
		<td<?php echo $tools->parent_domain->CellAttributes() ?>><span id="el_tools_parent_domain">
<?php if ($tools->parent_domain->getSessionValue() <> "") { ?>
<span<?php echo $tools->parent_domain->ViewAttributes() ?>>
<?php echo $tools->parent_domain->ViewValue ?></span>
<input type="hidden" id="x_parent_domain" name="x_parent_domain" value="<?php echo ew_HtmlEncode($tools->parent_domain->CurrentValue) ?>">
<?php } else { ?>
<select id="x_parent_domain" name="x_parent_domain"<?php echo $tools->parent_domain->EditAttributes() ?>>
<?php
if (is_array($tools->parent_domain->EditValue)) {
	$arwrk = $tools->parent_domain->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($tools->parent_domain->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<?php
$sSqlWrk = "SELECT `id_domains`, `dominio` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `domains`";
$sWhereWrk = "";
if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x_parent_domain" id="s_x_parent_domain" value="s=<?php echo TEAencrypt($sSqlWrk) ?>&fn=<?php echo urlencode($tools->parent_domain->LookupFn) ?>&f0=<?php echo TEAencrypt("`id_domains` = {filter_value}"); ?>&t0=3" />
<?php } ?>
</span><?php echo $tools->parent_domain->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($tools->Descripcion->Visible) { // Descripcion ?>
	<tr id="r_Descripcion"<?php echo $tools->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tools_Descripcion">
		<b><?php echo $tools->Descripcion->FldCaption() ?></b>
		</span></td>
		<td<?php echo $tools->Descripcion->CellAttributes() ?>><span id="el_tools_Descripcion">
<input type="text" name="x_Descripcion" id="x_Descripcion" size="30" maxlength="245" value="<?php echo $tools->Descripcion->EditValue ?>"<?php echo $tools->Descripcion->EditAttributes() ?> />
</span><?php echo $tools->Descripcion->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</div>
		<div  class="tab-pane " id="tab_tools2">
<table id="tbl_toolsedit2" class="ewTable ewTableSeparate table table-striped ">
<?php if ($tools->tags->Visible) { // tags ?>
	<tr id="r_tags"<?php echo $tools->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tools_tags">
		<b><?php echo $tools->tags->FldCaption() ?></b>
		</span></td>
		<td<?php echo $tools->tags->CellAttributes() ?>><span id="el_tools_tags">
<input type="text" name="x_tags" id="x_tags" size="30" maxlength="245" value="<?php echo $tools->tags->EditValue ?>"<?php echo $tools->tags->EditAttributes() ?> />
</span><?php echo $tools->tags->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</div>
</div>
<input type="submit" class="btn btn-large btn-success" name="btnAction" id="btnAction" value="<?php echo ew_BtnCaption($Language->Phrase("EditBtn")) ?>" />
</form>
<script type="text/javascript">
ftoolsedit.Init();
</script>
<?php
$tools_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$tools_edit->Page_Terminate();
?>
