<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg9.php" ?>
<?php include_once "ewmysql9.php" ?>
<?php include_once "phpfn9.php" ?>
<?php include_once "logsinfo.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "userfn9.php" ?>
<?php

//
// Page class
//

$logs_edit = NULL; // Initialize page object first

class clogs_edit extends clogs {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{3554BCCA-7E88-4E52-9661-DF55D75275C9}";

	// Table name
	var $TableName = 'logs';

	// Page object name
	var $PageObjName = 'logs_edit';

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

		// Table object (logs)
		if (!isset($GLOBALS["logs"])) {
			$GLOBALS["logs"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["logs"];
		}

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'edit', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'logs', TRUE);

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
			$this->Page_Terminate("logslist.php");
		}
		$Security->UserID_Loading();
		if ($Security->IsLoggedIn()) $Security->LoadUserID();
		$Security->UserID_Loaded();

		// Create form object
		$objForm = new cFormObj();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"];
		$this->idlogs->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
		if (@$_GET["idlogs"] <> "")
			$this->idlogs->setQueryStringValue($_GET["idlogs"]);

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->idlogs->CurrentValue == "")
			$this->Page_Terminate($this->james_url( "logslist.php" )); // Invalid key, return to list

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
					$this->Page_Terminate($this->james_url( "logslist.php" )); // No matching record, return to list
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
		if (!$this->idlogs->FldIsDetailKey)
			$this->idlogs->setFormValue($objForm->GetValue("x_idlogs"));
		if (!$this->log->FldIsDetailKey) {
			$this->log->setFormValue($objForm->GetValue("x_log"));
		}
		if (!$this->log_id->FldIsDetailKey) {
			$this->log_id->setFormValue($objForm->GetValue("x_log_id"));
		}
		if (!$this->msj->FldIsDetailKey) {
			$this->msj->setFormValue($objForm->GetValue("x_msj"));
		}
		if (!$this->log_severity->FldIsDetailKey) {
			$this->log_severity->setFormValue($objForm->GetValue("x_log_severity"));
		}
		if (!$this->timestamp->FldIsDetailKey) {
			$this->timestamp->setFormValue($objForm->GetValue("x_timestamp"));
			$this->timestamp->CurrentValue = ew_UnFormatDateTime($this->timestamp->CurrentValue, 5);
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->idlogs->CurrentValue = $this->idlogs->FormValue;
		$this->log->CurrentValue = $this->log->FormValue;
		$this->log_id->CurrentValue = $this->log_id->FormValue;
		$this->msj->CurrentValue = $this->msj->FormValue;
		$this->log_severity->CurrentValue = $this->log_severity->FormValue;
		$this->timestamp->CurrentValue = $this->timestamp->FormValue;
		$this->timestamp->CurrentValue = ew_UnFormatDateTime($this->timestamp->CurrentValue, 5);
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
		$this->idlogs->setDbValue($rs->fields('idlogs'));
		$this->log->setDbValue($rs->fields('log'));
		$this->log_id->setDbValue($rs->fields('log_id'));
		$this->msj->setDbValue($rs->fields('msj'));
		$this->log_severity->setDbValue($rs->fields('log_severity'));
		$this->timestamp->setDbValue($rs->fields('timestamp'));
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// idlogs
		// log
		// log_id
		// msj
		// log_severity
		// timestamp

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// idlogs
			$this->idlogs->ViewValue = $this->idlogs->CurrentValue;
			$this->idlogs->ViewCustomAttributes = "";

			// log
			$this->log->ViewValue = $this->log->CurrentValue;
			$this->log->ViewCustomAttributes = "";

			// log_id
			$this->log_id->ViewValue = $this->log_id->CurrentValue;
			$this->log_id->ViewCustomAttributes = "";

			// msj
			$this->msj->ViewValue = $this->msj->CurrentValue;
			$this->msj->ViewCustomAttributes = "";

			// log_severity
			$this->log_severity->ViewValue = $this->log_severity->CurrentValue;
			$this->log_severity->ViewCustomAttributes = "";

			// timestamp
			$this->timestamp->ViewValue = $this->timestamp->CurrentValue;
			$this->timestamp->ViewValue = ew_FormatDateTime($this->timestamp->ViewValue, 5);
			$this->timestamp->ViewCustomAttributes = "";

			// idlogs
			$this->idlogs->LinkCustomAttributes = "";
			$this->idlogs->HrefValue = "";
			$this->idlogs->TooltipValue = "";

			// log
			$this->log->LinkCustomAttributes = "";
			$this->log->HrefValue = "";
			$this->log->TooltipValue = "";

			// log_id
			$this->log_id->LinkCustomAttributes = "";
			$this->log_id->HrefValue = "";
			$this->log_id->TooltipValue = "";

			// msj
			$this->msj->LinkCustomAttributes = "";
			$this->msj->HrefValue = "";
			$this->msj->TooltipValue = "";

			// log_severity
			$this->log_severity->LinkCustomAttributes = "";
			$this->log_severity->HrefValue = "";
			$this->log_severity->TooltipValue = "";

			// timestamp
			$this->timestamp->LinkCustomAttributes = "";
			$this->timestamp->HrefValue = "";
			$this->timestamp->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// idlogs
			$this->idlogs->EditCustomAttributes = "";
			$this->idlogs->EditValue = $this->idlogs->CurrentValue;
			$this->idlogs->ViewCustomAttributes = "";

			// log
			$this->log->EditCustomAttributes = "";
			$this->log->EditValue = ew_HtmlEncode($this->log->CurrentValue);

			// log_id
			$this->log_id->EditCustomAttributes = "";
			$this->log_id->EditValue = ew_HtmlEncode($this->log_id->CurrentValue);

			// msj
			$this->msj->EditCustomAttributes = "";
			$this->msj->EditValue = ew_HtmlEncode($this->msj->CurrentValue);

			// log_severity
			$this->log_severity->EditCustomAttributes = "";
			$this->log_severity->EditValue = ew_HtmlEncode($this->log_severity->CurrentValue);

			// timestamp
			$this->timestamp->EditCustomAttributes = "";
			$this->timestamp->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->timestamp->CurrentValue, 5));

			// Edit refer script
			// idlogs

			$this->idlogs->HrefValue = "";

			// log
			$this->log->HrefValue = "";

			// log_id
			$this->log_id->HrefValue = "";

			// msj
			$this->msj->HrefValue = "";

			// log_severity
			$this->log_severity->HrefValue = "";

			// timestamp
			$this->timestamp->HrefValue = "";
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
		if (!ew_CheckDate($this->timestamp->FormValue)) {
			ew_AddMessage($gsFormError, $this->timestamp->FldErrMsg());
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

			// log
			$this->log->SetDbValueDef($rsnew, $this->log->CurrentValue, NULL, $this->log->ReadOnly);

			// log_id
			$this->log_id->SetDbValueDef($rsnew, $this->log_id->CurrentValue, NULL, $this->log_id->ReadOnly);

			// msj
			$this->msj->SetDbValueDef($rsnew, $this->msj->CurrentValue, NULL, $this->msj->ReadOnly);

			// log_severity
			$this->log_severity->SetDbValueDef($rsnew, $this->log_severity->CurrentValue, NULL, $this->log_severity->ReadOnly);

			// timestamp
			$this->timestamp->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->timestamp->CurrentValue, 5), NULL, $this->timestamp->ReadOnly);

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
if (!isset($logs_edit)) $logs_edit = new clogs_edit();

// Page init
$logs_edit->Page_Init();

// Page main
$logs_edit->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var logs_edit = new ew_Page("logs_edit");
logs_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = logs_edit.PageID; // For backward compatibility

// Form object
var flogsedit = new ew_Form("flogsedit");

// Validate form
flogsedit.Validate = function(fobj) {
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
		elm = fobj.elements["x" + infix + "_timestamp"];
		if (elm && !ew_CheckDate(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($logs->timestamp->FldErrMsg()) ?>");

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
flogsedit.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
flogsedit.ValidateRequired = true;
<?php } else { ?>
flogsedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<h4 id="ewPageCaption" class="page-header ewTitle ewTableTitle"> <?php echo $Language->Phrase("Edit") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $logs->TableCaption() ?></h4>
<a href="<?php echo $logs->getReturnUrl() ?>" id="a_GoBack" class="ewLink label"><i class="icon-arrow-left icon-white"></i> <?php echo $Language->Phrase("GoBack") ?></a>
<?php $logs_edit->ShowPageHeader(); ?>
<?php
$logs_edit->ShowMessage();
?>
<form name="flogsedit" id="flogsedit" class="ewForm" action="<?php echo ew_CurrentPage() ?>" method="post" onsubmit="return ewForms[this.id].Submit();">
<input type="hidden" name="t" value="logs" />
<input type="hidden" name="a_edit" id="a_edit" value="U" />
<table id="tbl_logsedit" class="ewTable ewTableSeparate table table-striped ">
<?php if ($logs->idlogs->Visible) { // idlogs ?>
	<tr id="r_idlogs"<?php echo $logs->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_logs_idlogs">
		<b><?php echo $logs->idlogs->FldCaption() ?></b>
		</span></td>
		<td<?php echo $logs->idlogs->CellAttributes() ?>><span id="el_logs_idlogs">
<span<?php echo $logs->idlogs->ViewAttributes() ?>>
<?php echo $logs->idlogs->EditValue ?></span>
<input type="hidden" name="x_idlogs" id="x_idlogs" value="<?php echo ew_HtmlEncode($logs->idlogs->CurrentValue) ?>" />
</span><?php echo $logs->idlogs->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($logs->log->Visible) { // log ?>
	<tr id="r_log"<?php echo $logs->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_logs_log">
		<b><?php echo $logs->log->FldCaption() ?></b>
		</span></td>
		<td<?php echo $logs->log->CellAttributes() ?>><span id="el_logs_log">
<textarea name="x_log" id="x_log" cols="35" rows="4"<?php echo $logs->log->EditAttributes() ?>><?php echo $logs->log->EditValue ?></textarea>
</span><?php echo $logs->log->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($logs->log_id->Visible) { // log_id ?>
	<tr id="r_log_id"<?php echo $logs->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_logs_log_id">
		<b><?php echo $logs->log_id->FldCaption() ?></b>
		</span></td>
		<td<?php echo $logs->log_id->CellAttributes() ?>><span id="el_logs_log_id">
<input type="text" name="x_log_id" id="x_log_id" size="30" maxlength="245" value="<?php echo $logs->log_id->EditValue ?>"<?php echo $logs->log_id->EditAttributes() ?> />
</span><?php echo $logs->log_id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($logs->msj->Visible) { // msj ?>
	<tr id="r_msj"<?php echo $logs->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_logs_msj">
		<b><?php echo $logs->msj->FldCaption() ?></b>
		</span></td>
		<td<?php echo $logs->msj->CellAttributes() ?>><span id="el_logs_msj">
<input type="text" name="x_msj" id="x_msj" size="30" maxlength="245" value="<?php echo $logs->msj->EditValue ?>"<?php echo $logs->msj->EditAttributes() ?> />
</span><?php echo $logs->msj->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($logs->log_severity->Visible) { // log_severity ?>
	<tr id="r_log_severity"<?php echo $logs->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_logs_log_severity">
		<b><?php echo $logs->log_severity->FldCaption() ?></b>
		</span></td>
		<td<?php echo $logs->log_severity->CellAttributes() ?>><span id="el_logs_log_severity">
<input type="text" name="x_log_severity" id="x_log_severity" size="30" maxlength="245" value="<?php echo $logs->log_severity->EditValue ?>"<?php echo $logs->log_severity->EditAttributes() ?> />
</span><?php echo $logs->log_severity->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($logs->timestamp->Visible) { // timestamp ?>
	<tr id="r_timestamp"<?php echo $logs->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_logs_timestamp">
		<b><?php echo $logs->timestamp->FldCaption() ?></b>
		</span></td>
		<td<?php echo $logs->timestamp->CellAttributes() ?>><span id="el_logs_timestamp">
<input type="text" name="x_timestamp" id="x_timestamp" value="<?php echo $logs->timestamp->EditValue ?>"<?php echo $logs->timestamp->EditAttributes() ?> />
</span><?php echo $logs->timestamp->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</div>
<input type="submit" class="btn btn-large btn-success" name="btnAction" id="btnAction" value="<?php echo ew_BtnCaption($Language->Phrase("EditBtn")) ?>" />
</form>
<script type="text/javascript">
flogsedit.Init();
</script>
<?php
$logs_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$logs_edit->Page_Terminate();
?>
