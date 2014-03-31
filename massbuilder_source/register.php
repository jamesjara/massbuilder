<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg9.php" ?>
<?php include_once "ewmysql9.php" ?>
<?php include_once "phpfn9.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "userfn9.php" ?>
<?php

//
// Page class
//

$register = NULL; // Initialize page object first

class cregister extends cusers {

	// Page ID
	var $PageID = 'register';

	// Project ID
	var $ProjectID = "{3554BCCA-7E88-4E52-9661-DF55D75275C9}";

	// Page object name
	var $PageObjName = 'register';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
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
		return TRUE;
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

		// Table object (users)
		if (!isset($GLOBALS["users"])) {
			$GLOBALS["users"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["users"];
		}
		if (!isset($GLOBALS["users"])) $GLOBALS["users"] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'register', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();
	}

	// 
	//  Page_Init
	//
	function Page_Init() {
		global $gsExport, $gsExportFile, $UserProfile, $Language, $Security, $objForm;

		// Security
		$Security = new cAdvancedSecurity();

		// Create form object
		$objForm = new cFormObj();

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

	//
	// Page main
	//
	function Page_Main() {
		global $conn, $Security, $Language, $gsFormError, $objForm;
		$bUserExists = FALSE;
		if (@$_POST["a_register"] <> "") {

			// Get action
			$this->CurrentAction = $_POST["a_register"];
			$this->LoadFormValues(); // Get form values

			// Validate form
			if (!$this->ValidateForm()) {
				$this->CurrentAction = "I"; // Form error, reset action
				$this->setFailureMessage($gsFormError);
			}
		} else {
			$this->CurrentAction = "I"; // Display blank record
			$this->LoadDefaultValues(); // Load default values
		}
		switch ($this->CurrentAction) {
			case "I": // Blank record, no action required
				break;
			case "A": // Add

				// Check for duplicate User ID
				$sFilter = str_replace("%u", ew_AdjustSql($this->user->CurrentValue), EW_USER_NAME_FILTER);

				// Set up filter (SQL WHERE clause) and get return SQL
				// SQL constructor in users class, usersinfo.php

				$this->CurrentFilter = $sFilter;
				$sUserSql = $this->SQL();
				if ($rs = $conn->Execute($sUserSql)) {
					if (!$rs->EOF) {
						$bUserExists = TRUE;
						$this->RestoreFormValues(); // Restore form values
						$this->setFailureMessage($Language->Phrase("UserExists")); // Set user exist message
					}
					$rs->Close();
				}
				if (!$bUserExists) {
					$this->SendEmail = TRUE; // Send email on add success
					if ($this->AddRow()) { // Add record
						if ($this->getSuccessMessage() == "")
							$this->setSuccessMessage($Language->Phrase("RegisterSuccess")); // Register success
						$this->Page_Terminate("login.php"); // Return
					} else {
						$this->RestoreFormValues(); // Restore form values
					}
				}
		}

		// Render row
		if ($this->CurrentAction == "F") { // Confirm page
			$this->RowType = EW_ROWTYPE_VIEW; // Render view
		} else {
			$this->RowType = EW_ROWTYPE_ADD; // Render add
		}
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
		$this->user->CurrentValue = NULL;
		$this->user->OldValue = $this->user->CurrentValue;
		$this->pass->CurrentValue = NULL;
		$this->pass->OldValue = $this->pass->CurrentValue;
		$this->_email->CurrentValue = NULL;
		$this->_email->OldValue = $this->_email->CurrentValue;
		$this->activate->CurrentValue = NULL;
		$this->activate->OldValue = $this->activate->CurrentValue;
		$this->promo->CurrentValue = NULL;
		$this->promo->OldValue = $this->promo->CurrentValue;
		$this->level->CurrentValue = NULL;
		$this->level->OldValue = $this->level->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->user->FldIsDetailKey) {
			$this->user->setFormValue($objForm->GetValue("x_user"));
		}
		if (!$this->pass->FldIsDetailKey) {
			$this->pass->setFormValue($objForm->GetValue("x_pass"));
		}
		$this->pass->ConfirmValue = $objForm->GetValue("c_pass");
		if (!$this->_email->FldIsDetailKey) {
			$this->_email->setFormValue($objForm->GetValue("x__email"));
		}
		if (!$this->activate->FldIsDetailKey) {
			$this->activate->setFormValue($objForm->GetValue("x_activate"));
		}
		if (!$this->promo->FldIsDetailKey) {
			$this->promo->setFormValue($objForm->GetValue("x_promo"));
		}
		if (!$this->level->FldIsDetailKey) {
			$this->level->setFormValue($objForm->GetValue("x_level"));
		}
		if (!$this->idusers->FldIsDetailKey)
			$this->idusers->setFormValue($objForm->GetValue("x_idusers"));
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->idusers->CurrentValue = $this->idusers->FormValue;
		$this->user->CurrentValue = $this->user->FormValue;
		$this->pass->CurrentValue = $this->pass->FormValue;
		$this->_email->CurrentValue = $this->_email->FormValue;
		$this->activate->CurrentValue = $this->activate->FormValue;
		$this->promo->CurrentValue = $this->promo->FormValue;
		$this->level->CurrentValue = $this->level->FormValue;
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
		$this->idusers->setDbValue($rs->fields('idusers'));
		$this->user->setDbValue($rs->fields('user'));
		$this->pass->setDbValue($rs->fields('pass'));
		$this->_email->setDbValue($rs->fields('email'));
		$this->activate->setDbValue($rs->fields('activate'));
		$this->promo->setDbValue($rs->fields('promo'));
		$this->level->setDbValue($rs->fields('level'));
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// idusers
		// user
		// pass
		// email
		// activate
		// promo
		// level

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// idusers
			$this->idusers->ViewValue = $this->idusers->CurrentValue;
			$this->idusers->ViewCustomAttributes = "";

			// user
			$this->user->ViewValue = $this->user->CurrentValue;
			$this->user->ViewCustomAttributes = "";

			// pass
			$this->pass->ViewValue = $this->pass->CurrentValue;
			$this->pass->ViewCustomAttributes = "";

			// email
			$this->_email->ViewValue = $this->_email->CurrentValue;
			$this->_email->ViewCustomAttributes = "";

			// activate
			$this->activate->ViewValue = $this->activate->CurrentValue;
			$this->activate->ViewCustomAttributes = "";

			// promo
			$this->promo->ViewValue = $this->promo->CurrentValue;
			$this->promo->ViewCustomAttributes = "";

			// level
			if ($Security->CanAdmin()) { // System admin
			if (strval($this->level->CurrentValue) <> "") {
				$sFilterWrk = "`userlevelid`" . ew_SearchString("=", $this->level->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `userlevelid`, `userlevelname` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `userlevels`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->level->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->level->ViewValue = $this->level->CurrentValue;
				}
			} else {
				$this->level->ViewValue = NULL;
			}
			} else {
				$this->level->ViewValue = "********";
			}
			$this->level->ViewCustomAttributes = "";

			// user
			$this->user->LinkCustomAttributes = "";
			$this->user->HrefValue = "";
			$this->user->TooltipValue = "";

			// pass
			$this->pass->LinkCustomAttributes = "";
			$this->pass->HrefValue = "";
			$this->pass->TooltipValue = "";

			// email
			$this->_email->LinkCustomAttributes = "";
			$this->_email->HrefValue = "";
			$this->_email->TooltipValue = "";

			// activate
			$this->activate->LinkCustomAttributes = "";
			$this->activate->HrefValue = "";
			$this->activate->TooltipValue = "";

			// promo
			$this->promo->LinkCustomAttributes = "";
			$this->promo->HrefValue = "";
			$this->promo->TooltipValue = "";

			// level
			$this->level->LinkCustomAttributes = "";
			$this->level->HrefValue = "";
			$this->level->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// user
			$this->user->EditCustomAttributes = "";
			$this->user->EditValue = ew_HtmlEncode($this->user->CurrentValue);

			// pass
			$this->pass->EditCustomAttributes = "";
			$this->pass->EditValue = ew_HtmlEncode($this->pass->CurrentValue);

			// email
			$this->_email->EditCustomAttributes = "";
			$this->_email->EditValue = ew_HtmlEncode($this->_email->CurrentValue);

			// activate
			$this->activate->EditCustomAttributes = "";
			$this->activate->EditValue = ew_HtmlEncode($this->activate->CurrentValue);

			// promo
			$this->promo->EditCustomAttributes = "";
			$this->promo->EditValue = ew_HtmlEncode($this->promo->CurrentValue);

			// level
			$this->level->EditCustomAttributes = "";
			if (!$Security->CanAdmin()) { // System admin
				$this->level->EditValue = "********";
			} else {
			$sFilterWrk = "";
			$sSqlWrk = "SELECT `userlevelid`, `userlevelname` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld`, '' AS `SelectFilterFld`, '' AS `SelectFilterFld2`, '' AS `SelectFilterFld3`, '' AS `SelectFilterFld4` FROM `userlevels`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			$arwrk = ($rswrk) ? $rswrk->GetRows() : array();
			if ($rswrk) $rswrk->Close();
			array_unshift($arwrk, array("", $Language->Phrase("PleaseSelect"), "", "", "", "", "", "", ""));
			$this->level->EditValue = $arwrk;
			}

			// Edit refer script
			// user

			$this->user->HrefValue = "";

			// pass
			$this->pass->HrefValue = "";

			// email
			$this->_email->HrefValue = "";

			// activate
			$this->activate->HrefValue = "";

			// promo
			$this->promo->HrefValue = "";

			// level
			$this->level->HrefValue = "";
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
		if (!is_null($this->user->FormValue) && $this->user->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterUserName"));
		}
		if (!is_null($this->pass->FormValue) && $this->pass->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterPassword"));
		}
		if ($this->pass->ConfirmValue <> $this->pass->FormValue) {
			ew_AddMessage($gsFormError, $Language->Phrase("MismatchPassword"));
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

		// user
		$this->user->SetDbValueDef($rsnew, $this->user->CurrentValue, NULL, FALSE);

		// pass
		$this->pass->SetDbValueDef($rsnew, $this->pass->CurrentValue, NULL, FALSE);

		// email
		$this->_email->SetDbValueDef($rsnew, $this->_email->CurrentValue, NULL, FALSE);

		// activate
		$this->activate->SetDbValueDef($rsnew, $this->activate->CurrentValue, NULL, FALSE);

		// promo
		$this->promo->SetDbValueDef($rsnew, $this->promo->CurrentValue, NULL, FALSE);

		// level
		$rsnew['level'] = 0; // Set default User Level

		// idusers
		// Call Row Inserting event

		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && $this->idusers->CurrentValue == "" && $this->idusers->getSessionValue() == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
		}

		// Check for duplicate key
		if ($bInsertRow && $this->ValidateKey) {
			$sFilter = $this->KeyFilter();
			$rsChk = $this->LoadRs($sFilter);
			if ($rsChk && !$rsChk->EOF) {
				$sKeyErrMsg = str_replace("%f", $sFilter, $Language->Phrase("DupKey"));
				$this->setFailureMessage($sKeyErrMsg);
				$rsChk->Close();
				$bInsertRow = FALSE;
			}
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
		}
		if ($AddRow) {

			// Call Row Inserted event
			$rs = ($rsold == NULL) ? NULL : $rsold->fields;
			$this->Row_Inserted($rs, $rsnew);

			// Call User Registered event
			$this->User_Registered($rsnew);
		}
		return $AddRow;
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
	// $type = ''|'success'|'failure'
	function Message_Showing(&$msg, $type) {

		// Example:
		//if ($type == 'success') $msg = "your success message";

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

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
	}

	// Form Custom Validate event
	function Form_CustomValidate(&$CustomError) {

		// Return error message in CustomError
		return TRUE;
	}

	// User Registered event
	function User_Registered(&$rs) {

	  //echo "User_Registered";
	}

	// User Activated event
	function User_Activated(&$rs) {

	  //echo "User_Activated";
	}
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($register)) $register = new cregister();

// Page init
$register->Page_Init();

// Page main
$register->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var register = new ew_Page("register");
register.PageID = "register"; // Page ID
var EW_PAGE_ID = register.PageID; // For backward compatibility

// Form object
var fregister = new ew_Form("fregister");

// Validate form
fregister.Validate = function(fobj) {
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
		elm = fobj.elements["x" + infix + "_user"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterUserName"));
		elm = fobj.elements["x" + infix + "_pass"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterPassword"));
		if (fobj.c_pass.value != fobj.x_pass.value)
			return ew_OnError(this, fobj.c_pass, ewLanguage.Phrase("MismatchPassword"));

		// Set up row object
		ew_ElementsToRow(fobj, infix);

		// Fire Form_CustomValidate event
		if (!this.Form_CustomValidate(fobj))
			return false;
	}
	return true;
}

// Form_CustomValidate event
fregister.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fregister.ValidateRequired = true;
<?php } else { ?>
fregister.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fregister.Lists["x_level"] = {"LinkField":"x_userlevelid","Ajax":null,"AutoFill":false,"DisplayFields":["x_userlevelname","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<p><span id="ewPageCaption" class="ewTitle ewRegisterTitle"><?php echo $Language->Phrase("RegisterPage") ?></span></p>
<p class="phpmaker"><a href="login.php"><?php echo $Language->Phrase("BackToLogin") ?></a></p>
<?php $register->ShowPageHeader(); ?>
<?php
$register->ShowMessage();
?>
<form name="fregister" id="fregister" class="ewForm" action="<?php echo ew_CurrentPage() ?>" method="post" onsubmit="return ewForms[this.id].Submit();">
<br />
<input type="hidden" name="t" value="users" />
<input type="hidden" name="a_register" id="a_register" value="A" />
<?php if ($users->CurrentAction == "F") { // Confirm page ?>
<input type="hidden" name="a_confirm" id="a_confirm" value="F" />
<?php } ?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_register" class="ewTable">
<?php if ($users->user->Visible) { // user ?>
	<tr id="r_user"<?php echo $users->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_users_user"><table class="ewTableHeaderBtn"><tr><td><?php echo $users->user->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></td></tr></table></span></td>
		<td<?php echo $users->user->CellAttributes() ?>><span id="el_users_user">
<?php if ($users->CurrentAction <> "F") { ?>
<input type="text" name="x_user" id="x_user" size="30" maxlength="45" value="<?php echo $users->user->EditValue ?>"<?php echo $users->user->EditAttributes() ?> />
<?php } else { ?>
<span<?php echo $users->user->ViewAttributes() ?>>
<?php echo $users->user->ViewValue ?></span>
<input type="hidden" name="x_user" id="x_user" value="<?php echo ew_HtmlEncode($users->user->FormValue) ?>" />
<?php } ?>
</span><?php echo $users->user->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($users->pass->Visible) { // pass ?>
	<tr id="r_pass"<?php echo $users->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_users_pass"><table class="ewTableHeaderBtn"><tr><td><?php echo $users->pass->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></td></tr></table></span></td>
		<td<?php echo $users->pass->CellAttributes() ?>><span id="el_users_pass">
<?php if ($users->CurrentAction <> "F") { ?>
<input type="text" name="x_pass" id="x_pass" size="30" maxlength="45" value="<?php echo $users->pass->EditValue ?>"<?php echo $users->pass->EditAttributes() ?> />
<?php } else { ?>
<span<?php echo $users->pass->ViewAttributes() ?>>
<?php echo $users->pass->ViewValue ?></span>
<input type="hidden" name="x_pass" id="x_pass" value="<?php echo ew_HtmlEncode($users->pass->FormValue) ?>" />
<?php } ?>
</span><?php echo $users->pass->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($users->pass->Visible) { // pass ?>
	<tr id="rc_pass"<?php echo $users->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="e2h_users_pass"><?php echo $Language->Phrase("Confirm") ?>&nbsp;<?php echo $users->pass->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></span></td>
		<td<?php echo $users->pass->CellAttributes() ?>><span id="e2_users_pass">
<?php if ($users->CurrentAction <> "F") { ?>
<input type="text" name="c_pass" id="c_pass" size="30" maxlength="45" value="<?php echo $users->pass->EditValue ?>"<?php echo $users->pass->EditAttributes() ?> />
<?php } else { ?>
<span<?php echo $users->pass->ViewAttributes() ?>>
<?php echo $users->pass->ViewValue ?></span>
<input type="hidden" name="c_pass" id="c_pass" value="<?php echo ew_HtmlEncode($users->pass->FormValue) ?>" />
<?php } ?>
</span></td>
	</tr>
<?php } ?>
<?php if ($users->_email->Visible) { // email ?>
	<tr id="r__email"<?php echo $users->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_users__email"><table class="ewTableHeaderBtn"><tr><td><?php echo $users->_email->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $users->_email->CellAttributes() ?>><span id="el_users__email">
<?php if ($users->CurrentAction <> "F") { ?>
<input type="text" name="x__email" id="x__email" size="30" maxlength="145" value="<?php echo $users->_email->EditValue ?>"<?php echo $users->_email->EditAttributes() ?> />
<?php } else { ?>
<span<?php echo $users->_email->ViewAttributes() ?>>
<?php echo $users->_email->ViewValue ?></span>
<input type="hidden" name="x__email" id="x__email" value="<?php echo ew_HtmlEncode($users->_email->FormValue) ?>" />
<?php } ?>
</span><?php echo $users->_email->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($users->activate->Visible) { // activate ?>
	<tr id="r_activate"<?php echo $users->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_users_activate"><table class="ewTableHeaderBtn"><tr><td><?php echo $users->activate->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $users->activate->CellAttributes() ?>><span id="el_users_activate">
<?php if ($users->CurrentAction <> "F") { ?>
<input type="text" name="x_activate" id="x_activate" size="30" maxlength="45" value="<?php echo $users->activate->EditValue ?>"<?php echo $users->activate->EditAttributes() ?> />
<?php } else { ?>
<span<?php echo $users->activate->ViewAttributes() ?>>
<?php echo $users->activate->ViewValue ?></span>
<input type="hidden" name="x_activate" id="x_activate" value="<?php echo ew_HtmlEncode($users->activate->FormValue) ?>" />
<?php } ?>
</span><?php echo $users->activate->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($users->promo->Visible) { // promo ?>
	<tr id="r_promo"<?php echo $users->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_users_promo"><table class="ewTableHeaderBtn"><tr><td><?php echo $users->promo->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $users->promo->CellAttributes() ?>><span id="el_users_promo">
<?php if ($users->CurrentAction <> "F") { ?>
<textarea name="x_promo" id="x_promo" cols="35" rows="4"<?php echo $users->promo->EditAttributes() ?>><?php echo $users->promo->EditValue ?></textarea>
<?php } else { ?>
<span<?php echo $users->promo->ViewAttributes() ?>>
<?php echo $users->promo->ViewValue ?></span>
<input type="hidden" name="x_promo" id="x_promo" value="<?php echo ew_HtmlEncode($users->promo->FormValue) ?>" />
<?php } ?>
</span><?php echo $users->promo->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</div>
</td></tr></table>
<br />
<?php if ($users->CurrentAction <> "F") { // Confirm page ?>
<input type="submit" name="btnAction" id="btnAction" value="<?php echo ew_BtnCaption($Language->Phrase("RegisterBtn")) ?>" onclick="this.form.a_register.value='F';" />
<?php } else { ?>
<input type="submit" name="btnCancel" id="btnCancel" value="<?php echo ew_BtnCaption($Language->Phrase("CancelBtn")) ?>" onclick="this.form.a_register.value='X';" />
<input type="submit" name="btnAction" id="btnAction" value="<?php echo ew_BtnCaption($Language->Phrase("ConfirmBtn")) ?>" />
<?php } ?>
</form>
<script type="text/javascript">
fregister.Init();
</script>
<?php
$register->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$register->Page_Terminate();
?>
