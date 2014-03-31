<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg9.php" ?>
<?php include_once "ewmysql9.php" ?>
<?php include_once "phpfn9.php" ?>
<?php include_once "tools_translationinfo.php" ?>
<?php include_once "domainsinfo.php" ?>
<?php include_once "userfn9.php" ?>
<?php

//
// Page class
//

$tools_translation_delete = NULL; // Initialize page object first

class ctools_translation_delete extends ctools_translation {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{3554BCCA-7E88-4E52-9661-DF55D75275C9}";

	// Table name
	var $TableName = 'tools_translation';

	// Page object name
	var $PageObjName = 'tools_translation_delete';

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

		// Table object (tools_translation)
		if (!isset($GLOBALS["tools_translation"])) {
			$GLOBALS["tools_translation"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["tools_translation"];
		}

		// Table object (domains)
		if (!isset($GLOBALS['domains'])) $GLOBALS['domains'] = new cdomains();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'tools_translation', TRUE);

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
		if (!$Security->IsLoggedIn()) $Security->AutoLogin();
		if (!$Security->IsLoggedIn()) {
			$Security->SaveLastUrl();
			$this->Page_Terminate("login.php");
		}
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"];
		$this->idtools_translation->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
	var $TotalRecs = 0;
	var $RecCnt;
	var $RecKeys = array();
	var $Recordset;
	var $RowCnt = 0;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Load key parameters
		$this->RecKeys = $this->GetRecordKeys(); // Load record keys
		$sFilter = $this->GetKeyFilter();
		if ($sFilter == "")
			$this->Page_Terminate("tools_translationlist.php"); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in tools_translation class, tools_translationinfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} else {
			$this->CurrentAction = "D"; // Delete record directly
		}
		switch ($this->CurrentAction) {
			case "D": // Delete
				$this->SendEmail = TRUE; // Send email on delete success
				if ($this->DeleteRows()) { // delete rows
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("DeleteSuccess")); // Set up success message
					$this->Page_Terminate($this->getReturnUrl()); // Return to caller
				}
		}
	}

// No functions
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
		$this->idtools_translation->setDbValue($rs->fields('idtools_translation'));
		$this->domain_id->setDbValue($rs->fields('domain_id'));
		$this->to_domain->setDbValue($rs->fields('to_domain'));
		$this->media->setDbValue($rs->fields('media'));
		$this->date->setDbValue($rs->fields('date'));
		$this->lenguaje->setDbValue($rs->fields('lenguaje'));
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// idtools_translation
		// domain_id
		// to_domain
		// media
		// date
		// lenguaje

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// idtools_translation
			$this->idtools_translation->ViewValue = $this->idtools_translation->CurrentValue;
			$this->idtools_translation->ViewCustomAttributes = "";

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

			// to_domain
			if (strval($this->to_domain->CurrentValue) <> "") {
				$sFilterWrk = "`id_domains`" . ew_SearchString("=", $this->to_domain->CurrentValue, EW_DATATYPE_NUMBER);
			$sSqlWrk = "SELECT `id_domains`, `dominio` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `domains`";
			$sWhereWrk = "";
			if ($sFilterWrk <> "") {
				ew_AddFilter($sWhereWrk, $sFilterWrk);
			}
			if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
				$rswrk = $conn->Execute($sSqlWrk);
				if ($rswrk && !$rswrk->EOF) { // Lookup values found
					$this->to_domain->ViewValue = $rswrk->fields('DispFld');
					$rswrk->Close();
				} else {
					$this->to_domain->ViewValue = $this->to_domain->CurrentValue;
				}
			} else {
				$this->to_domain->ViewValue = NULL;
			}
			$this->to_domain->ViewCustomAttributes = "";

			// media
			$this->media->ViewValue = $this->media->CurrentValue;
			$this->media->ViewCustomAttributes = "";

			// date
			$this->date->ViewValue = $this->date->CurrentValue;
			$this->date->ViewValue = ew_FormatDateTime($this->date->ViewValue, 5);
			$this->date->ViewCustomAttributes = "";

			// lenguaje
			$this->lenguaje->ViewValue = $this->lenguaje->CurrentValue;
			$this->lenguaje->ViewCustomAttributes = "";

			// idtools_translation
			$this->idtools_translation->LinkCustomAttributes = "";
			$this->idtools_translation->HrefValue = "";
			$this->idtools_translation->TooltipValue = "";

			// domain_id
			$this->domain_id->LinkCustomAttributes = "";
			$this->domain_id->HrefValue = "";
			$this->domain_id->TooltipValue = "";

			// to_domain
			$this->to_domain->LinkCustomAttributes = "";
			$this->to_domain->HrefValue = "";
			$this->to_domain->TooltipValue = "";

			// media
			$this->media->LinkCustomAttributes = "";
			if (!ew_Empty($this->media->CurrentValue)) {
				$this->media->HrefValue = ((!empty($this->media->ViewValue)) ? $this->media->ViewValue : $this->media->CurrentValue); // Add prefix/suffix
				$this->media->LinkAttrs["target"] = "_blank"; // Add target
				if ($this->Export <> "") $this->media->HrefValue = ew_ConvertFullUrl($this->media->HrefValue);
			} else {
				$this->media->HrefValue = "";
			}
			$this->media->TooltipValue = "";

			// date
			$this->date->LinkCustomAttributes = "";
			$this->date->HrefValue = "";
			$this->date->TooltipValue = "";

			// lenguaje
			$this->lenguaje->LinkCustomAttributes = "";
			$this->lenguaje->HrefValue = "";
			$this->lenguaje->TooltipValue = "";
		}

		// Call Row Rendered event
		if ($this->RowType <> EW_ROWTYPE_AGGREGATEINIT)
			$this->Row_Rendered();
	}

	//
	// Delete records based on current filter
	//
	function DeleteRows() {
		global $conn, $Language, $Security;
		$DeleteRows = TRUE;
		$sSql = $this->SQL();
		$conn->raiseErrorFn = 'ew_ErrorFn';
		$rs = $conn->Execute($sSql);
		$conn->raiseErrorFn = '';
		if ($rs === FALSE) {
			return FALSE;
		} elseif ($rs->EOF) {
			$this->setFailureMessage($Language->Phrase("NoRecord")); // No record found
			$rs->Close();
			return FALSE;
		} else {
			$this->LoadRowValues($rs); // Load row values
		}
		$conn->BeginTrans();

		// Clone old rows
		$rsold = ($rs) ? $rs->GetRows() : array();
		if ($rs)
			$rs->Close();

		// Call row deleting event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$DeleteRows = $this->Row_Deleting($row);
				if (!$DeleteRows) break;
			}
		}
		if ($DeleteRows) {
			$sKey = "";
			foreach ($rsold as $row) {
				$sThisKey = "";
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['idtools_translation'];
				$conn->raiseErrorFn = 'ew_ErrorFn';
				$DeleteRows = $this->Delete($row); // Delete
				$conn->raiseErrorFn = '';
				if ($DeleteRows === FALSE)
					break;
				if ($sKey <> "") $sKey .= ", ";
				$sKey .= $sThisKey;
			}
		} else {

			// Set up error message
			if ($this->getSuccessMessage() <> "" || $this->getFailureMessage() <> "") {

				// Use the message, do nothing
			} elseif ($this->CancelMessage <> "") {
				$this->setFailureMessage($this->CancelMessage);
				$this->CancelMessage = "";
			} else {
				$this->setFailureMessage($Language->Phrase("DeleteCancelled"));
			}
		}
		if ($DeleteRows) {
			$conn->CommitTrans(); // Commit the changes
		} else {
			$conn->RollbackTrans(); // Rollback changes
		}

		// Call Row Deleted event
		if ($DeleteRows) {
			foreach ($rsold as $row) {
				$this->Row_Deleted($row);
			}
		}
		return $DeleteRows;
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
}
?>
<?php ew_Header(FALSE) ?>
<?php

// Create page object
if (!isset($tools_translation_delete)) $tools_translation_delete = new ctools_translation_delete();

// Page init
$tools_translation_delete->Page_Init();

// Page main
$tools_translation_delete->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var tools_translation_delete = new ew_Page("tools_translation_delete");
tools_translation_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = tools_translation_delete.PageID; // For backward compatibility

// Form object
var ftools_translationdelete = new ew_Form("ftools_translationdelete");

// Form_CustomValidate event
ftools_translationdelete.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftools_translationdelete.ValidateRequired = true;
<?php } else { ?>
ftools_translationdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ftools_translationdelete.Lists["x_domain_id"] = {"LinkField":"x_id_domains","Ajax":null,"AutoFill":false,"DisplayFields":["x_dominio","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
ftools_translationdelete.Lists["x_to_domain"] = {"LinkField":"x_id_domains","Ajax":true,"AutoFill":false,"DisplayFields":["x_dominio","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($tools_translation_delete->Recordset = $tools_translation_delete->LoadRecordset())
	$tools_translation_deleteTotalRecs = $tools_translation_delete->Recordset->RecordCount(); // Get record count
if ($tools_translation_deleteTotalRecs <= 0) { // No record found, exit
	if ($tools_translation_delete->Recordset)
		$tools_translation_delete->Recordset->Close();
	$tools_translation_delete->Page_Terminate("tools_translationlist.php"); // Return to list
}
?>
<p><span id="ewPageCaption" class="ewTitle ewTableTitle"><?php echo $Language->Phrase("Delete") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $tools_translation->TableCaption() ?></span></p>
<p class="phpmaker"><a href="<?php echo $tools_translation->getReturnUrl() ?>" id="a_GoBack" class="ewLink"><?php echo $Language->Phrase("GoBack") ?></a></p>
<?php $tools_translation_delete->ShowPageHeader(); ?>
<?php
$tools_translation_delete->ShowMessage();
?>
<form name="ftools_translationdelete" id="ftools_translationdelete" class="ewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<br>
<input type="hidden" name="t" value="tools_translation">
<input type="hidden" name="a_delete" id="a_delete" value="D">
<?php foreach ($tools_translation_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>">
<?php } ?>
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_tools_translationdelete" class="ewTable ewTableSeparate">
<?php echo $tools_translation->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
		<td><span id="elh_tools_translation_idtools_translation" class="tools_translation_idtools_translation"><table class="ewTableHeaderBtn"><tr><td><?php echo $tools_translation->idtools_translation->FldCaption() ?></td></tr></table></span></td>
		<td><span id="elh_tools_translation_domain_id" class="tools_translation_domain_id"><table class="ewTableHeaderBtn"><tr><td><?php echo $tools_translation->domain_id->FldCaption() ?></td></tr></table></span></td>
		<td><span id="elh_tools_translation_to_domain" class="tools_translation_to_domain"><table class="ewTableHeaderBtn"><tr><td><?php echo $tools_translation->to_domain->FldCaption() ?></td></tr></table></span></td>
		<td><span id="elh_tools_translation_media" class="tools_translation_media"><table class="ewTableHeaderBtn"><tr><td><?php echo $tools_translation->media->FldCaption() ?></td></tr></table></span></td>
		<td><span id="elh_tools_translation_date" class="tools_translation_date"><table class="ewTableHeaderBtn"><tr><td><?php echo $tools_translation->date->FldCaption() ?></td></tr></table></span></td>
		<td><span id="elh_tools_translation_lenguaje" class="tools_translation_lenguaje"><table class="ewTableHeaderBtn"><tr><td><?php echo $tools_translation->lenguaje->FldCaption() ?></td></tr></table></span></td>
	</tr>
	</thead>
	<tbody>
<?php
$tools_translation_delete->RecCnt = 0;
$i = 0;
while (!$tools_translation_delete->Recordset->EOF) {
	$tools_translation_delete->RecCnt++;
	$tools_translation_delete->RowCnt++;

	// Set row properties
	$tools_translation->ResetAttrs();
	$tools_translation->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$tools_translation_delete->LoadRowValues($tools_translation_delete->Recordset);

	// Render row
	$tools_translation_delete->RenderRow();
?>
	<tr<?php echo $tools_translation->RowAttributes() ?>>
		<td<?php echo $tools_translation->idtools_translation->CellAttributes() ?>><span id="el<?php echo $tools_translation_delete->RowCnt ?>_tools_translation_idtools_translation" class="tools_translation_idtools_translation">
<span<?php echo $tools_translation->idtools_translation->ViewAttributes() ?>>
<?php echo $tools_translation->idtools_translation->ListViewValue() ?></span>
</span></td>
		<td<?php echo $tools_translation->domain_id->CellAttributes() ?>><span id="el<?php echo $tools_translation_delete->RowCnt ?>_tools_translation_domain_id" class="tools_translation_domain_id">
<span<?php echo $tools_translation->domain_id->ViewAttributes() ?>>
<?php echo $tools_translation->domain_id->ListViewValue() ?></span>
</span></td>
		<td<?php echo $tools_translation->to_domain->CellAttributes() ?>><span id="el<?php echo $tools_translation_delete->RowCnt ?>_tools_translation_to_domain" class="tools_translation_to_domain">
<span<?php echo $tools_translation->to_domain->ViewAttributes() ?>>
<?php echo $tools_translation->to_domain->ListViewValue() ?></span>
</span></td>
		<td<?php echo $tools_translation->media->CellAttributes() ?>><span id="el<?php echo $tools_translation_delete->RowCnt ?>_tools_translation_media" class="tools_translation_media">
<span<?php echo $tools_translation->media->ViewAttributes() ?>>
<?php if (!ew_EmptyStr($tools_translation->media->ListViewValue()) && $tools_translation->media->LinkAttributes() <> "") { ?>
<a<?php echo $tools_translation->media->LinkAttributes() ?>><?php echo $tools_translation->media->ListViewValue() ?></a>
<?php } else { ?>
<?php echo $tools_translation->media->ListViewValue() ?>
<?php } ?>
</span>
</span></td>
		<td<?php echo $tools_translation->date->CellAttributes() ?>><span id="el<?php echo $tools_translation_delete->RowCnt ?>_tools_translation_date" class="tools_translation_date">
<span<?php echo $tools_translation->date->ViewAttributes() ?>>
<?php echo $tools_translation->date->ListViewValue() ?></span>
</span></td>
		<td<?php echo $tools_translation->lenguaje->CellAttributes() ?>><span id="el<?php echo $tools_translation_delete->RowCnt ?>_tools_translation_lenguaje" class="tools_translation_lenguaje">
<span<?php echo $tools_translation->lenguaje->ViewAttributes() ?>>
<?php echo $tools_translation->lenguaje->ListViewValue() ?></span>
</span></td>
	</tr>
<?php
	$tools_translation_delete->Recordset->MoveNext();
}
$tools_translation_delete->Recordset->Close();
?>
</tbody>
</table>
</div>
</td></tr></table>
<br>
<input type="submit" name="Action" value="<?php echo ew_BtnCaption($Language->Phrase("DeleteBtn")) ?>">
</form>
<script type="text/javascript">
ftools_translationdelete.Init();
</script>
<?php
$tools_translation_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$tools_translation_delete->Page_Terminate();
?>
