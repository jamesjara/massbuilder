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

$tools_translation_view = NULL; // Initialize page object first

class ctools_translation_view extends ctools_translation {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{3554BCCA-7E88-4E52-9661-DF55D75275C9}";

	// Table name
	var $TableName = 'tools_translation';

	// Page object name
	var $PageObjName = 'tools_translation_view';

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
		$KeyUrl = "";
		if (@$_GET["idtools_translation"] <> "") {
			$this->RecKey["idtools_translation"] = $_GET["idtools_translation"];
			$KeyUrl .= "&idtools_translation=" . urlencode($this->RecKey["idtools_translation"]);
		}
		$this->ExportPrintUrl = $this->PageUrl() . "export=print" . $KeyUrl;
		$this->ExportHtmlUrl = $this->PageUrl() . "export=html" . $KeyUrl;
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel" . $KeyUrl;
		$this->ExportWordUrl = $this->PageUrl() . "export=word" . $KeyUrl;
		$this->ExportXmlUrl = $this->PageUrl() . "export=xml" . $KeyUrl;
		$this->ExportCsvUrl = $this->PageUrl() . "export=csv" . $KeyUrl;
		$this->ExportPdfUrl = $this->PageUrl() . "export=pdf" . $KeyUrl;

		// Table object (domains)
		if (!isset($GLOBALS['domains'])) $GLOBALS['domains'] = new cdomains();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'tools_translation', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "span";
		$this->ExportOptions->TagClassName = "ewExportOption";
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
	var $ExportOptions; // Export options
	var $DisplayRecs = 1;
	var $StartRec;
	var $StopRec;
	var $TotalRecs = 0;
	var $RecRange = 10;
	var $Pager;
	var $RecCnt;
	var $RecKey = array();
	var $Recordset;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;

		// Load current record
		$bLoadCurrentRecord = FALSE;
		$sReturnUrl = "";
		$bMatchRecord = FALSE;
		if ($this->IsPageRequest()) { // Validate request
			if (@$_GET["idtools_translation"] <> "") {
				$this->idtools_translation->setQueryStringValue($_GET["idtools_translation"]);
				$this->RecKey["idtools_translation"] = $this->idtools_translation->QueryStringValue;
			} else {
				$bLoadCurrentRecord = TRUE;
			}

			// Get action
			$this->CurrentAction = "I"; // Display form
			switch ($this->CurrentAction) {
				case "I": // Get a record to display
					$this->StartRec = 1; // Initialize start position
					if ($this->Recordset = $this->LoadRecordset()) // Load records
						$this->TotalRecs = $this->Recordset->RecordCount(); // Get record count
					if ($this->TotalRecs <= 0) { // No record found
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$this->Page_Terminate("tools_translationlist.php"); // Return to list page
					} elseif ($bLoadCurrentRecord) { // Load current record position
						$this->SetUpStartRec(); // Set up start record position

						// Point to current record
						if (intval($this->StartRec) <= intval($this->TotalRecs)) {
							$bMatchRecord = TRUE;
							$this->Recordset->Move($this->StartRec-1);
						}
					} else { // Match key values
						while (!$this->Recordset->EOF) {
							if (strval($this->idtools_translation->CurrentValue) == strval($this->Recordset->fields('idtools_translation'))) {
								$this->setStartRecordNumber($this->StartRec); // Save record position
								$bMatchRecord = TRUE;
								break;
							} else {
								$this->StartRec++;
								$this->Recordset->MoveNext();
							}
						}
					}
					if (!$bMatchRecord) {
						if ($this->getSuccessMessage() == "" && $this->getFailureMessage() == "")
							$this->setFailureMessage($Language->Phrase("NoRecord")); // Set no record message
						$sReturnUrl = "tools_translationlist.php"; // No matching record, return to list
					} else {
						$this->LoadRowValues($this->Recordset); // Load row values
					}
			}
		} else {
			$sReturnUrl = "tools_translationlist.php"; // Not page request, return to list
		}
		if ($sReturnUrl <> "")
			$this->Page_Terminate($sReturnUrl);

		// Render row
		$this->RowType = EW_ROWTYPE_VIEW;
		$this->ResetAttrs();
		$this->RenderRow();
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
		$this->AddUrl = $this->GetAddUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();
		$this->ListUrl = $this->GetListUrl();

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
if (!isset($tools_translation_view)) $tools_translation_view = new ctools_translation_view();

// Page init
$tools_translation_view->Page_Init();

// Page main
$tools_translation_view->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var tools_translation_view = new ew_Page("tools_translation_view");
tools_translation_view.PageID = "view"; // Page ID
var EW_PAGE_ID = tools_translation_view.PageID; // For backward compatibility

// Form object
var ftools_translationview = new ew_Form("ftools_translationview");

// Form_CustomValidate event
ftools_translationview.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftools_translationview.ValidateRequired = true;
<?php } else { ?>
ftools_translationview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ftools_translationview.Lists["x_domain_id"] = {"LinkField":"x_id_domains","Ajax":null,"AutoFill":false,"DisplayFields":["x_dominio","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
ftools_translationview.Lists["x_to_domain"] = {"LinkField":"x_id_domains","Ajax":true,"AutoFill":false,"DisplayFields":["x_dominio","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<p><span id="ewPageCaption" class="ewTitle ewTableTitle"><?php echo $Language->Phrase("View") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $tools_translation->TableCaption() ?>&nbsp;&nbsp;</span><?php $tools_translation_view->ExportOptions->Render("body"); ?>
</p>
<p class="phpmaker">
<a href="<?php echo $tools_translation_view->ListUrl ?>" id="a_BackToList" class="ewLink"><?php echo $Language->Phrase("BackToList") ?></a>&nbsp;
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($tools_translation_view->AddUrl <> "") { ?>
<a href="<?php echo $tools_translation_view->AddUrl ?>" id="a_AddLink" class="ewLink"><?php echo $Language->Phrase("ViewPageAddLink") ?></a>&nbsp;
<?php } ?>
<?php } ?>
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($tools_translation_view->EditUrl <> "") { ?>
<a href="<?php echo $tools_translation_view->EditUrl ?>" id="a_EditLink" class="ewLink"><?php echo $Language->Phrase("ViewPageEditLink") ?></a>&nbsp;
<?php } ?>
<?php } ?>
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($tools_translation_view->CopyUrl <> "") { ?>
<a href="<?php echo $tools_translation_view->CopyUrl ?>" id="a_CopyLink" class="ewLink"><?php echo $Language->Phrase("ViewPageCopyLink") ?></a>&nbsp;
<?php } ?>
<?php } ?>
<?php if ($Security->IsLoggedIn()) { ?>
<?php if ($tools_translation_view->DeleteUrl <> "") { ?>
<a onclick="return ew_Confirm(ewLanguage.Phrase('DeleteConfirmMsg'));" href="<?php echo $tools_translation_view->DeleteUrl ?>" id="a_DeleteLink" class="ewLink"><?php echo $Language->Phrase("ViewPageDeleteLink") ?></a>&nbsp;
<?php } ?>
<?php } ?>
</p>
<?php $tools_translation_view->ShowPageHeader(); ?>
<?php
$tools_translation_view->ShowMessage();
?>
<form name="ewpagerform" id="ewpagerform" class="ewForm" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager"><tr><td>
<span class="phpmaker">
<?php if (!isset($tools_translation_view->Pager)) $tools_translation_view->Pager = new cNumericPager($tools_translation_view->StartRec, $tools_translation_view->DisplayRecs, $tools_translation_view->TotalRecs, $tools_translation_view->RecRange) ?>
<?php if ($tools_translation_view->Pager->RecordCount > 0) { ?>
	<?php if ($tools_translation_view->Pager->FirstButton->Enabled) { ?>
	<a href="<?php echo $tools_translation_view->PageUrl() ?>start=<?php echo $tools_translation_view->Pager->FirstButton->Start ?>"><b><?php echo $Language->Phrase("PagerFirst") ?></b></a>&nbsp;
	<?php } ?>
	<?php if ($tools_translation_view->Pager->PrevButton->Enabled) { ?>
	<a href="<?php echo $tools_translation_view->PageUrl() ?>start=<?php echo $tools_translation_view->Pager->PrevButton->Start ?>"><b><?php echo $Language->Phrase("PagerPrevious") ?></b></a>&nbsp;
	<?php } ?>
	<?php foreach ($tools_translation_view->Pager->Items as $PagerItem) { ?>
		<?php if ($PagerItem->Enabled) { ?><a href="<?php echo $tools_translation_view->PageUrl() ?>start=<?php echo $PagerItem->Start ?>"><?php } ?><b><?php echo $PagerItem->Text ?></b><?php if ($PagerItem->Enabled) { ?></a><?php } ?>&nbsp;
	<?php } ?>
	<?php if ($tools_translation_view->Pager->NextButton->Enabled) { ?>
	<a href="<?php echo $tools_translation_view->PageUrl() ?>start=<?php echo $tools_translation_view->Pager->NextButton->Start ?>"><b><?php echo $Language->Phrase("PagerNext") ?></b></a>&nbsp;
	<?php } ?>
	<?php if ($tools_translation_view->Pager->LastButton->Enabled) { ?>
	<a href="<?php echo $tools_translation_view->PageUrl() ?>start=<?php echo $tools_translation_view->Pager->LastButton->Start ?>"><b><?php echo $Language->Phrase("PagerLast") ?></b></a>&nbsp;
	<?php } ?>
<?php } else { ?>	
	<?php if ($tools_translation_view->SearchWhere == "0=101") { ?>
	<?php echo $Language->Phrase("EnterSearchCriteria") ?>
	<?php } else { ?>
	<?php echo $Language->Phrase("NoRecord") ?>
	<?php } ?>
<?php } ?>
</span>
	</td>
</tr></table>
</form>
<br>
<form name="ftools_translationview" id="ftools_translationview" class="ewForm" action="" method="post">
<input type="hidden" name="t" value="tools_translation">
<table cellspacing="0" class="ewGrid"><tr><td class="ewGridContent">
<div class="ewGridMiddlePanel">
<table id="tbl_tools_translationview" class="ewTable">
<?php if ($tools_translation->idtools_translation->Visible) { // idtools_translation ?>
	<tr id="r_idtools_translation"<?php echo $tools_translation->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tools_translation_idtools_translation"><table class="ewTableHeaderBtn"><tr><td><?php echo $tools_translation->idtools_translation->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tools_translation->idtools_translation->CellAttributes() ?>><span id="el_tools_translation_idtools_translation">
<span<?php echo $tools_translation->idtools_translation->ViewAttributes() ?>>
<?php echo $tools_translation->idtools_translation->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($tools_translation->domain_id->Visible) { // domain_id ?>
	<tr id="r_domain_id"<?php echo $tools_translation->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tools_translation_domain_id"><table class="ewTableHeaderBtn"><tr><td><?php echo $tools_translation->domain_id->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tools_translation->domain_id->CellAttributes() ?>><span id="el_tools_translation_domain_id">
<span<?php echo $tools_translation->domain_id->ViewAttributes() ?>>
<?php echo $tools_translation->domain_id->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($tools_translation->to_domain->Visible) { // to_domain ?>
	<tr id="r_to_domain"<?php echo $tools_translation->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tools_translation_to_domain"><table class="ewTableHeaderBtn"><tr><td><?php echo $tools_translation->to_domain->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tools_translation->to_domain->CellAttributes() ?>><span id="el_tools_translation_to_domain">
<span<?php echo $tools_translation->to_domain->ViewAttributes() ?>>
<?php echo $tools_translation->to_domain->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($tools_translation->media->Visible) { // media ?>
	<tr id="r_media"<?php echo $tools_translation->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tools_translation_media"><table class="ewTableHeaderBtn"><tr><td><?php echo $tools_translation->media->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tools_translation->media->CellAttributes() ?>><span id="el_tools_translation_media">
<span<?php echo $tools_translation->media->ViewAttributes() ?>>
<?php if (!ew_EmptyStr($tools_translation->media->ViewValue) && $tools_translation->media->LinkAttributes() <> "") { ?>
<a<?php echo $tools_translation->media->LinkAttributes() ?>><?php echo $tools_translation->media->ViewValue ?></a>
<?php } else { ?>
<?php echo $tools_translation->media->ViewValue ?>
<?php } ?>
</span>
</span></td>
	</tr>
<?php } ?>
<?php if ($tools_translation->date->Visible) { // date ?>
	<tr id="r_date"<?php echo $tools_translation->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tools_translation_date"><table class="ewTableHeaderBtn"><tr><td><?php echo $tools_translation->date->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tools_translation->date->CellAttributes() ?>><span id="el_tools_translation_date">
<span<?php echo $tools_translation->date->ViewAttributes() ?>>
<?php echo $tools_translation->date->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($tools_translation->lenguaje->Visible) { // lenguaje ?>
	<tr id="r_lenguaje"<?php echo $tools_translation->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_tools_translation_lenguaje"><table class="ewTableHeaderBtn"><tr><td><?php echo $tools_translation->lenguaje->FldCaption() ?></td></tr></table></span></td>
		<td<?php echo $tools_translation->lenguaje->CellAttributes() ?>><span id="el_tools_translation_lenguaje">
<span<?php echo $tools_translation->lenguaje->ViewAttributes() ?>>
<?php echo $tools_translation->lenguaje->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
</table>
</div>
</td></tr></table>
</form>
<br>
<form name="ewpagerform" id="ewpagerform" class="ewForm" action="<?php echo ew_CurrentPage() ?>">
<table class="ewPager"><tr><td>
<span class="phpmaker">
<?php if (!isset($tools_translation_view->Pager)) $tools_translation_view->Pager = new cNumericPager($tools_translation_view->StartRec, $tools_translation_view->DisplayRecs, $tools_translation_view->TotalRecs, $tools_translation_view->RecRange) ?>
<?php if ($tools_translation_view->Pager->RecordCount > 0) { ?>
	<?php if ($tools_translation_view->Pager->FirstButton->Enabled) { ?>
	<a href="<?php echo $tools_translation_view->PageUrl() ?>start=<?php echo $tools_translation_view->Pager->FirstButton->Start ?>"><b><?php echo $Language->Phrase("PagerFirst") ?></b></a>&nbsp;
	<?php } ?>
	<?php if ($tools_translation_view->Pager->PrevButton->Enabled) { ?>
	<a href="<?php echo $tools_translation_view->PageUrl() ?>start=<?php echo $tools_translation_view->Pager->PrevButton->Start ?>"><b><?php echo $Language->Phrase("PagerPrevious") ?></b></a>&nbsp;
	<?php } ?>
	<?php foreach ($tools_translation_view->Pager->Items as $PagerItem) { ?>
		<?php if ($PagerItem->Enabled) { ?><a href="<?php echo $tools_translation_view->PageUrl() ?>start=<?php echo $PagerItem->Start ?>"><?php } ?><b><?php echo $PagerItem->Text ?></b><?php if ($PagerItem->Enabled) { ?></a><?php } ?>&nbsp;
	<?php } ?>
	<?php if ($tools_translation_view->Pager->NextButton->Enabled) { ?>
	<a href="<?php echo $tools_translation_view->PageUrl() ?>start=<?php echo $tools_translation_view->Pager->NextButton->Start ?>"><b><?php echo $Language->Phrase("PagerNext") ?></b></a>&nbsp;
	<?php } ?>
	<?php if ($tools_translation_view->Pager->LastButton->Enabled) { ?>
	<a href="<?php echo $tools_translation_view->PageUrl() ?>start=<?php echo $tools_translation_view->Pager->LastButton->Start ?>"><b><?php echo $Language->Phrase("PagerLast") ?></b></a>&nbsp;
	<?php } ?>
<?php } else { ?>	
	<?php if ($tools_translation_view->SearchWhere == "0=101") { ?>
	<?php echo $Language->Phrase("EnterSearchCriteria") ?>
	<?php } else { ?>
	<?php echo $Language->Phrase("NoRecord") ?>
	<?php } ?>
<?php } ?>
</span>
	</td>
</tr></table>
</form>
<br>
<script type="text/javascript">
ftools_translationview.Init();
</script>
<?php
$tools_translation_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$tools_translation_view->Page_Terminate();
?>
