<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg9.php" ?>
<?php include_once "ewmysql9.php" ?>
<?php include_once "phpfn9.php" ?>
<?php include_once "audittrailinfo.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "userfn9.php" ?>
<?php

//
// Page class
//

$audittrail_edit = NULL; // Initialize page object first

class caudittrail_edit extends caudittrail {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{3554BCCA-7E88-4E52-9661-DF55D75275C9}";

	// Table name
	var $TableName = 'audittrail';

	// Page object name
	var $PageObjName = 'audittrail_edit';

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

		// Table object (audittrail)
		if (!isset($GLOBALS["audittrail"])) {
			$GLOBALS["audittrail"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["audittrail"];
		}

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'audittrail', TRUE);

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
			$this->Page_Terminate("audittraillist.php");
		}
		$Security->UserID_Loading();
		if ($Security->IsLoggedIn()) $Security->LoadUserID();
		$Security->UserID_Loaded();

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"];
		$this->id->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
		if (@$_GET["id"] <> "")
			$this->id->setQueryStringValue($_GET["id"]);

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->id->CurrentValue == "")
			$this->Page_Terminate($this->james_url( "audittraillist.php" )); // Invalid key, return to list

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
					$this->Page_Terminate($this->james_url( "audittraillist.php" )); // No matching record, return to list
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
		if (!$this->id->FldIsDetailKey)
			$this->id->setFormValue($objForm->GetValue("x_id"));
		if (!$this->datetime->FldIsDetailKey) {
			$this->datetime->setFormValue($objForm->GetValue("x_datetime"));
			$this->datetime->CurrentValue = ew_UnFormatDateTime($this->datetime->CurrentValue, 5);
		}
		if (!$this->script->FldIsDetailKey) {
			$this->script->setFormValue($objForm->GetValue("x_script"));
		}
		if (!$this->user->FldIsDetailKey) {
			$this->user->setFormValue($objForm->GetValue("x_user"));
		}
		if (!$this->action->FldIsDetailKey) {
			$this->action->setFormValue($objForm->GetValue("x_action"));
		}
		if (!$this->_table->FldIsDetailKey) {
			$this->_table->setFormValue($objForm->GetValue("x__table"));
		}
		if (!$this->_field->FldIsDetailKey) {
			$this->_field->setFormValue($objForm->GetValue("x__field"));
		}
		if (!$this->keyvalue->FldIsDetailKey) {
			$this->keyvalue->setFormValue($objForm->GetValue("x_keyvalue"));
		}
		if (!$this->oldvalue->FldIsDetailKey) {
			$this->oldvalue->setFormValue($objForm->GetValue("x_oldvalue"));
		}
		if (!$this->newvalue->FldIsDetailKey) {
			$this->newvalue->setFormValue($objForm->GetValue("x_newvalue"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->id->CurrentValue = $this->id->FormValue;
		$this->datetime->CurrentValue = $this->datetime->FormValue;
		$this->datetime->CurrentValue = ew_UnFormatDateTime($this->datetime->CurrentValue, 5);
		$this->script->CurrentValue = $this->script->FormValue;
		$this->user->CurrentValue = $this->user->FormValue;
		$this->action->CurrentValue = $this->action->FormValue;
		$this->_table->CurrentValue = $this->_table->FormValue;
		$this->_field->CurrentValue = $this->_field->FormValue;
		$this->keyvalue->CurrentValue = $this->keyvalue->FormValue;
		$this->oldvalue->CurrentValue = $this->oldvalue->FormValue;
		$this->newvalue->CurrentValue = $this->newvalue->FormValue;
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
		$this->id->setDbValue($rs->fields('id'));
		$this->datetime->setDbValue($rs->fields('datetime'));
		$this->script->setDbValue($rs->fields('script'));
		$this->user->setDbValue($rs->fields('user'));
		$this->action->setDbValue($rs->fields('action'));
		$this->_table->setDbValue($rs->fields('table'));
		$this->_field->setDbValue($rs->fields('field'));
		$this->keyvalue->setDbValue($rs->fields('keyvalue'));
		$this->oldvalue->setDbValue($rs->fields('oldvalue'));
		$this->newvalue->setDbValue($rs->fields('newvalue'));
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// id
		// datetime
		// script
		// user
		// action
		// table
		// field
		// keyvalue
		// oldvalue
		// newvalue

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id
			$this->id->ViewValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// datetime
			$this->datetime->ViewValue = $this->datetime->CurrentValue;
			$this->datetime->ViewValue = ew_FormatDateTime($this->datetime->ViewValue, 5);
			$this->datetime->ViewCustomAttributes = "";

			// script
			$this->script->ViewValue = $this->script->CurrentValue;
			$this->script->ViewCustomAttributes = "";

			// user
			$this->user->ViewValue = $this->user->CurrentValue;
			$this->user->ViewCustomAttributes = "";

			// action
			$this->action->ViewValue = $this->action->CurrentValue;
			$this->action->ViewCustomAttributes = "";

			// table
			$this->_table->ViewValue = $this->_table->CurrentValue;
			$this->_table->ViewCustomAttributes = "";

			// field
			$this->_field->ViewValue = $this->_field->CurrentValue;
			$this->_field->ViewCustomAttributes = "";

			// keyvalue
			$this->keyvalue->ViewValue = $this->keyvalue->CurrentValue;
			$this->keyvalue->ViewCustomAttributes = "";

			// oldvalue
			$this->oldvalue->ViewValue = $this->oldvalue->CurrentValue;
			$this->oldvalue->ViewCustomAttributes = "";

			// newvalue
			$this->newvalue->ViewValue = $this->newvalue->CurrentValue;
			$this->newvalue->ViewCustomAttributes = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// datetime
			$this->datetime->LinkCustomAttributes = "";
			$this->datetime->HrefValue = "";
			$this->datetime->TooltipValue = "";

			// script
			$this->script->LinkCustomAttributes = "";
			$this->script->HrefValue = "";
			$this->script->TooltipValue = "";

			// user
			$this->user->LinkCustomAttributes = "";
			$this->user->HrefValue = "";
			$this->user->TooltipValue = "";

			// action
			$this->action->LinkCustomAttributes = "";
			$this->action->HrefValue = "";
			$this->action->TooltipValue = "";

			// table
			$this->_table->LinkCustomAttributes = "";
			$this->_table->HrefValue = "";
			$this->_table->TooltipValue = "";

			// field
			$this->_field->LinkCustomAttributes = "";
			$this->_field->HrefValue = "";
			$this->_field->TooltipValue = "";

			// keyvalue
			$this->keyvalue->LinkCustomAttributes = "";
			$this->keyvalue->HrefValue = "";
			$this->keyvalue->TooltipValue = "";

			// oldvalue
			$this->oldvalue->LinkCustomAttributes = "";
			$this->oldvalue->HrefValue = "";
			$this->oldvalue->TooltipValue = "";

			// newvalue
			$this->newvalue->LinkCustomAttributes = "";
			$this->newvalue->HrefValue = "";
			$this->newvalue->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// id
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// datetime
			$this->datetime->EditCustomAttributes = "";
			$this->datetime->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->datetime->CurrentValue, 5));

			// script
			$this->script->EditCustomAttributes = "";
			$this->script->EditValue = ew_HtmlEncode($this->script->CurrentValue);

			// user
			$this->user->EditCustomAttributes = "";
			$this->user->EditValue = ew_HtmlEncode($this->user->CurrentValue);

			// action
			$this->action->EditCustomAttributes = "";
			$this->action->EditValue = ew_HtmlEncode($this->action->CurrentValue);

			// table
			$this->_table->EditCustomAttributes = "";
			$this->_table->EditValue = ew_HtmlEncode($this->_table->CurrentValue);

			// field
			$this->_field->EditCustomAttributes = "";
			$this->_field->EditValue = ew_HtmlEncode($this->_field->CurrentValue);

			// keyvalue
			$this->keyvalue->EditCustomAttributes = "";
			$this->keyvalue->EditValue = ew_HtmlEncode($this->keyvalue->CurrentValue);

			// oldvalue
			$this->oldvalue->EditCustomAttributes = "";
			$this->oldvalue->EditValue = ew_HtmlEncode($this->oldvalue->CurrentValue);

			// newvalue
			$this->newvalue->EditCustomAttributes = "";
			$this->newvalue->EditValue = ew_HtmlEncode($this->newvalue->CurrentValue);

			// Edit refer script
			// id

			$this->id->HrefValue = "";

			// datetime
			$this->datetime->HrefValue = "";

			// script
			$this->script->HrefValue = "";

			// user
			$this->user->HrefValue = "";

			// action
			$this->action->HrefValue = "";

			// table
			$this->_table->HrefValue = "";

			// field
			$this->_field->HrefValue = "";

			// keyvalue
			$this->keyvalue->HrefValue = "";

			// oldvalue
			$this->oldvalue->HrefValue = "";

			// newvalue
			$this->newvalue->HrefValue = "";
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
		if (!is_null($this->datetime->FormValue) && $this->datetime->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->datetime->FldCaption());
		}
		if (!ew_CheckDate($this->datetime->FormValue)) {
			ew_AddMessage($gsFormError, $this->datetime->FldErrMsg());
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

			// datetime
			$this->datetime->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->datetime->CurrentValue, 5), ew_CurrentDate(), $this->datetime->ReadOnly);

			// script
			$this->script->SetDbValueDef($rsnew, $this->script->CurrentValue, NULL, $this->script->ReadOnly);

			// user
			$this->user->SetDbValueDef($rsnew, $this->user->CurrentValue, NULL, $this->user->ReadOnly);

			// action
			$this->action->SetDbValueDef($rsnew, $this->action->CurrentValue, NULL, $this->action->ReadOnly);

			// table
			$this->_table->SetDbValueDef($rsnew, $this->_table->CurrentValue, NULL, $this->_table->ReadOnly);

			// field
			$this->_field->SetDbValueDef($rsnew, $this->_field->CurrentValue, NULL, $this->_field->ReadOnly);

			// keyvalue
			$this->keyvalue->SetDbValueDef($rsnew, $this->keyvalue->CurrentValue, NULL, $this->keyvalue->ReadOnly);

			// oldvalue
			$this->oldvalue->SetDbValueDef($rsnew, $this->oldvalue->CurrentValue, NULL, $this->oldvalue->ReadOnly);

			// newvalue
			$this->newvalue->SetDbValueDef($rsnew, $this->newvalue->CurrentValue, NULL, $this->newvalue->ReadOnly);

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
if (!isset($audittrail_edit)) $audittrail_edit = new caudittrail_edit();

// Page init
$audittrail_edit->Page_Init();

// Page main
$audittrail_edit->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var audittrail_edit = new ew_Page("audittrail_edit");
audittrail_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = audittrail_edit.PageID; // For backward compatibility

// Form object
var faudittrailedit = new ew_Form("faudittrailedit");

// Validate form
faudittrailedit.Validate = function(fobj) {
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
		elm = fobj.elements["x" + infix + "_datetime"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($audittrail->datetime->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_datetime"];
		if (elm && !ew_CheckDate(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($audittrail->datetime->FldErrMsg()) ?>");

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
faudittrailedit.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
faudittrailedit.ValidateRequired = true;
<?php } else { ?>
faudittrailedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<h4 id="ewPageCaption" class="page-header ewTitle ewTableTitle"> <?php echo $Language->Phrase("Edit") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $audittrail->TableCaption() ?></h4>
<a href="<?php echo $audittrail->getReturnUrl() ?>" id="a_GoBack" class="ewLink label"><i class="icon-arrow-left icon-white"></i> <?php echo $Language->Phrase("GoBack") ?></a>
<?php $audittrail_edit->ShowPageHeader(); ?>
<?php
$audittrail_edit->ShowMessage();
?>
<form name="faudittrailedit" id="faudittrailedit" class="ewForm" action="<?php echo ew_CurrentPage() ?>" method="post" onsubmit="return ewForms[this.id].Submit();">
<input type="hidden" name="t" value="audittrail" />
<input type="hidden" name="a_edit" id="a_edit" value="U" />
<table id="tbl_audittrailedit" class="ewTable ewTableSeparate table table-striped ">
<?php if ($audittrail->id->Visible) { // id ?>
	<tr id="r_id"<?php echo $audittrail->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_audittrail_id">
		<b><?php echo $audittrail->id->FldCaption() ?></b>
		</span></td>
		<td<?php echo $audittrail->id->CellAttributes() ?>><span id="el_audittrail_id">
<span<?php echo $audittrail->id->ViewAttributes() ?>>
<?php echo $audittrail->id->EditValue ?></span>
<input type="hidden" name="x_id" id="x_id" value="<?php echo ew_HtmlEncode($audittrail->id->CurrentValue) ?>" />
</span><?php echo $audittrail->id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($audittrail->datetime->Visible) { // datetime ?>
	<tr id="r_datetime"<?php echo $audittrail->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_audittrail_datetime">
		<b><?php echo $audittrail->datetime->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></b>
		</span></td>
		<td<?php echo $audittrail->datetime->CellAttributes() ?>><span id="el_audittrail_datetime">
<input type="text" name="x_datetime" id="x_datetime" value="<?php echo $audittrail->datetime->EditValue ?>"<?php echo $audittrail->datetime->EditAttributes() ?> />
</span><?php echo $audittrail->datetime->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($audittrail->script->Visible) { // script ?>
	<tr id="r_script"<?php echo $audittrail->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_audittrail_script">
		<b><?php echo $audittrail->script->FldCaption() ?></b>
		</span></td>
		<td<?php echo $audittrail->script->CellAttributes() ?>><span id="el_audittrail_script">
<input type="text" name="x_script" id="x_script" size="30" maxlength="255" value="<?php echo $audittrail->script->EditValue ?>"<?php echo $audittrail->script->EditAttributes() ?> />
</span><?php echo $audittrail->script->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($audittrail->user->Visible) { // user ?>
	<tr id="r_user"<?php echo $audittrail->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_audittrail_user">
		<b><?php echo $audittrail->user->FldCaption() ?></b>
		</span></td>
		<td<?php echo $audittrail->user->CellAttributes() ?>><span id="el_audittrail_user">
<input type="text" name="x_user" id="x_user" size="30" maxlength="255" value="<?php echo $audittrail->user->EditValue ?>"<?php echo $audittrail->user->EditAttributes() ?> />
</span><?php echo $audittrail->user->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($audittrail->action->Visible) { // action ?>
	<tr id="r_action"<?php echo $audittrail->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_audittrail_action">
		<b><?php echo $audittrail->action->FldCaption() ?></b>
		</span></td>
		<td<?php echo $audittrail->action->CellAttributes() ?>><span id="el_audittrail_action">
<input type="text" name="x_action" id="x_action" size="30" maxlength="255" value="<?php echo $audittrail->action->EditValue ?>"<?php echo $audittrail->action->EditAttributes() ?> />
</span><?php echo $audittrail->action->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($audittrail->_table->Visible) { // table ?>
	<tr id="r__table"<?php echo $audittrail->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_audittrail__table">
		<b><?php echo $audittrail->_table->FldCaption() ?></b>
		</span></td>
		<td<?php echo $audittrail->_table->CellAttributes() ?>><span id="el_audittrail__table">
<input type="text" name="x__table" id="x__table" size="30" maxlength="255" value="<?php echo $audittrail->_table->EditValue ?>"<?php echo $audittrail->_table->EditAttributes() ?> />
</span><?php echo $audittrail->_table->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($audittrail->_field->Visible) { // field ?>
	<tr id="r__field"<?php echo $audittrail->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_audittrail__field">
		<b><?php echo $audittrail->_field->FldCaption() ?></b>
		</span></td>
		<td<?php echo $audittrail->_field->CellAttributes() ?>><span id="el_audittrail__field">
<input type="text" name="x__field" id="x__field" size="30" maxlength="255" value="<?php echo $audittrail->_field->EditValue ?>"<?php echo $audittrail->_field->EditAttributes() ?> />
</span><?php echo $audittrail->_field->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($audittrail->keyvalue->Visible) { // keyvalue ?>
	<tr id="r_keyvalue"<?php echo $audittrail->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_audittrail_keyvalue">
		<b><?php echo $audittrail->keyvalue->FldCaption() ?></b>
		</span></td>
		<td<?php echo $audittrail->keyvalue->CellAttributes() ?>><span id="el_audittrail_keyvalue">
<textarea name="x_keyvalue" id="x_keyvalue" cols="35" rows="4"<?php echo $audittrail->keyvalue->EditAttributes() ?>><?php echo $audittrail->keyvalue->EditValue ?></textarea>
</span><?php echo $audittrail->keyvalue->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($audittrail->oldvalue->Visible) { // oldvalue ?>
	<tr id="r_oldvalue"<?php echo $audittrail->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_audittrail_oldvalue">
		<b><?php echo $audittrail->oldvalue->FldCaption() ?></b>
		</span></td>
		<td<?php echo $audittrail->oldvalue->CellAttributes() ?>><span id="el_audittrail_oldvalue">
<textarea name="x_oldvalue" id="x_oldvalue" cols="35" rows="4"<?php echo $audittrail->oldvalue->EditAttributes() ?>><?php echo $audittrail->oldvalue->EditValue ?></textarea>
</span><?php echo $audittrail->oldvalue->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($audittrail->newvalue->Visible) { // newvalue ?>
	<tr id="r_newvalue"<?php echo $audittrail->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_audittrail_newvalue">
		<b><?php echo $audittrail->newvalue->FldCaption() ?></b>
		</span></td>
		<td<?php echo $audittrail->newvalue->CellAttributes() ?>><span id="el_audittrail_newvalue">
<textarea name="x_newvalue" id="x_newvalue" cols="35" rows="4"<?php echo $audittrail->newvalue->EditAttributes() ?>><?php echo $audittrail->newvalue->EditValue ?></textarea>
</span><?php echo $audittrail->newvalue->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</div>
<input type="submit" class="btn btn-large btn-success" name="btnAction" id="btnAction" value="<?php echo ew_BtnCaption($Language->Phrase("EditBtn")) ?>" />
</form>
<script type="text/javascript">
faudittrailedit.Init();
</script>
<?php
$audittrail_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$audittrail_edit->Page_Terminate();
?>
