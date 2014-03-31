<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg9.php" ?>
<?php include_once "ewmysql9.php" ?>
<?php include_once "phpfn9.php" ?>
<?php

// Global variable for table object
$Test = NULL;

//
// Table class for Test
//
class cTest extends cTableBase {
	var $id_domains;
	var $dominio;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'Test';
		$this->TableName = 'Test';
		$this->TableType = 'REPORT';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->UserIDAllowSecurity = 0; // User ID Allow

		// id_domains
		$this->id_domains = new cField('Test', 'Test', 'x_id_domains', 'id_domains', '`id_domains`', '`id_domains`', 3, -1, FALSE, '`id_domains`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id_domains->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_domains'] = &$this->id_domains;

		// dominio
		$this->dominio = new cField('Test', 'Test', 'x_dominio', 'dominio', '`dominio`', '`dominio`', 200, -1, FALSE, '`dominio`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['dominio'] = &$this->dominio;
	}

	// Report group level SQL
	function SqlGroupSelect() { // Select
		return "SELECT DISTINCT `dominio` FROM `domains`";
	}

	function SqlGroupWhere() { // Where
		return "";
	}

	function SqlGroupGroupBy() { // Group By
		return "";
	}

	function SqlGroupHaving() { // Having
		return "";
	}

	function SqlGroupOrderBy() { // Order By
		return "`dominio` ASC";
	}

	// Report detail level SQL
	function SqlDetailSelect() { // Select
		return "SELECT * FROM `domains`";
	}

	function SqlDetailWhere() { // Where
		return "";
	}

	function SqlDetailGroupBy() { // Group By
		return "";
	}

	function SqlDetailHaving() { // Having
		return "";
	}

	function SqlDetailOrderBy() { // Order By
		return "`dominio` ASC";
	}

	// Check if Anonymous User is allowed
	function AllowAnonymousUser() {
		switch (@$this->PageID) {
			case "add":
			case "register":
			case "addopt":
				return FALSE;
			case "edit":
			case "update":
			case "changepwd":
			case "forgotpwd":
				return FALSE;
			case "delete":
				return FALSE;
			case "view":
				return FALSE;
			case "search":
				return FALSE;
			default:
				return FALSE;
		}
	}

	// Apply User ID filters
	function ApplyUserIDFilters($sFilter) {
		return $sFilter;
	}

	// Check if User ID security allows view all
	function UserIDAllow($id = "") {
		return TRUE;
	}

	// Report group SQL
	function GroupSQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = "";
		return ew_BuildSelectSql($this->SqlGroupSelect(), $this->SqlGroupWhere(),
			 $this->SqlGroupGroupBy(), $this->SqlGroupHaving(),
			 $this->SqlGroupOrderBy(), $sFilter, $sSort);
	}

	// Report detail SQL
	function DetailSQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = "";
		return ew_BuildSelectSql($this->SqlDetailSelect(), $this->SqlDetailWhere(),
			$this->SqlDetailGroupBy(), $this->SqlDetailHaving(),
			$this->SqlDetailOrderBy(), $sFilter, $sSort);
	}

	// Return page URL
	function getReturnUrl() {
		$name = EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL;

		// Get referer URL automatically
		if (ew_ServerVar("HTTP_REFERER") <> "" && ew_ReferPage() <> ew_CurrentPage() && ew_ReferPage() <> "login.php") // Referer not same page or login page
			$_SESSION[$name] = ew_ServerVar("HTTP_REFERER"); // Save to Session
		if (@$_SESSION[$name] <> "") {
			return $_SESSION[$name];
		} else {
			return "testreport.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "testreport.php";
	}

	// View URL
	function GetViewUrl() {
		return $this->KeyUrl("", $this->UrlParm());
	}

	// Add URL
	function GetAddUrl() {
		return "";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("", $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->id_domains->CurrentValue)) {
			$sUrl .= "id_domains=" . urlencode($this->id_domains->CurrentValue);
		} else {
			return "javascript:alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		return $sUrl;
	}

	// Sort URL
	function SortUrl(&$fld) {
		if ($this->CurrentAction <> "" || $this->Export <> "" ||
			in_array($fld->FldType, array(128, 204, 205))) { // Unsortable data type
				return "";
		} elseif ($fld->Sortable) {
			$sUrlParm = $this->UrlParm("order=" . urlencode($fld->FldName) . "&ordertype=" . $fld->ReverseSort());
			return ew_CurrentPage() . "?" . $sUrlParm;
		} else {
			return "";
		}
	}

	// Get record keys from $_POST/$_GET/$_SESSION
	function GetRecordKeys() {
		global $EW_COMPOSITE_KEY_SEPARATOR;
		$arKeys = array();
		$arKey = array();
		if (isset($_POST["key_m"])) {
			$arKeys = ew_StripSlashes($_POST["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = ew_StripSlashes($_GET["key_m"]);
			$cnt = count($arKeys);
		} elseif (isset($_GET)) {
			$arKeys[] = @$_GET["id_domains"]; // id_domains

			//return $arKeys; // do not return yet, so the values will also be checked by the following code
		}

		// check keys
		$ar = array();
		foreach ($arKeys as $key) {
			if (!is_numeric($key))
				continue;
			$ar[] = $key;
		}
		return $ar;
	}

	// Get key filter
	function GetKeyFilter() {
		$arKeys = $this->GetRecordKeys();
		$sKeyFilter = "";
		foreach ($arKeys as $key) {
			if ($sKeyFilter <> "") $sKeyFilter .= " OR ";
			$this->id_domains->CurrentValue = $key;
			$sKeyFilter .= "(" . $this->KeyFilter() . ")";
		}
		return $sKeyFilter;
	}

	// Load rows based on filter
	function &LoadRs($sFilter) {
		global $conn;

		// Set up filter (SQL WHERE clause) and get return SQL
		//$this->CurrentFilter = $sFilter;
		//$sSql = $this->SQL();

		$sSql = $this->GetSQL($sFilter, "");
		$rs = $conn->Execute($sSql);
		return $rs;
	}

	// Row Rendering event
	function Row_Rendering() {

		// Enter your code here	
	}

	// Row Rendered event
	function Row_Rendered() {

		// To view properties of field class, use:
		//var_dump($this-><FieldName>); 

	}

	// User ID Filtering event
	function UserID_Filtering(&$filter) {

		// Enter your code here
	}
}
?>
<?php include_once "userfn9.php" ?>
<?php

//
// Page class
//

$Test_report = NULL; // Initialize page object first

class cTest_report extends cTest {

	// Page ID
	var $PageID = 'report';

	// Project ID
	var $ProjectID = "{3554BCCA-7E88-4E52-9661-DF55D75275C9}";

	// Table name
	var $TableName = 'Test';

	// Page object name
	var $PageObjName = 'Test_report';

	// Page name
	function PageName() {
		return ew_CurrentPage();
	}

	// Page URL
	function PageUrl() {
		$PageUrl = ew_CurrentPage() . "?";
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

		// Table object (Test)
		if (!isset($GLOBALS["Test"])) {
			$GLOBALS["Test"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["Test"];
		}

		// Initialize URLs
		$this->ExportPrintUrl = $this->PageUrl() . "export=print";
		$this->ExportExcelUrl = $this->PageUrl() . "export=excel";
		$this->ExportWordUrl = $this->PageUrl() . "export=word";

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'report', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'Test', TRUE);

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

		// Get export parameters
		if (@$_GET["export"] <> "") {
			$this->Export = $_GET["export"];
		}
		$gsExport = $this->Export; // Get export parameter, used in header
		$gsExportFile = $this->TableVar; // Get export file, used in header

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
		global $EW_EXPORT_REPORT;

		// Page Unload event
		$this->Page_Unload();

		// Export
		if ($this->Export <> "" && array_key_exists($this->Export, $EW_EXPORT_REPORT)) {
			$sContent = ob_get_contents();
			$fn = $EW_EXPORT_REPORT[$this->Export];
			$this->$fn($sContent);
			if ($this->Export == "email") { // Email
				ob_end_clean();
				$conn->Close(); // Close connection
				header("Location: " . ew_CurrentPage());
				exit();
			}
		}

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
	var $RecCnt = 0;
	var $ReportSql = "";
	var $ReportFilter = "";
	var $DefaultFilter = "";
	var $DbMasterFilter = "";
	var $DbDetailFilter = "";
	var $MasterRecordExists;
	var $Command;
	var $DtlRecordCount;
	var $ReportGroups;
	var $ReportCounts;
	var $LevelBreak;
	var $ReportTotals;
	var $ReportMaxs;
	var $ReportMins;
	var $Recordset;
	var $DetailRecordset;

	//
	// Page main
	//
	function Page_Main() {
		global $Language;
		$this->ReportGroups = &ew_InitArray(2, NULL);
		$this->ReportCounts = &ew_InitArray(2, 0);
		$this->LevelBreak = &ew_InitArray(2, FALSE);
		$this->ReportTotals = &ew_Init2DArray(2, 2, 0);
		$this->ReportMaxs = &ew_Init2DArray(2, 2, 0);
		$this->ReportMins = &ew_Init2DArray(2, 2, 0);
	}

	// Check level break
	function ChkLvlBreak() {
		$this->LevelBreak[1] = FALSE;
		if ($this->RecCnt == 0) { // Start Or End of Recordset
			$this->LevelBreak[1] = TRUE;
		} else {
			if (!ew_CompareValue($this->dominio->CurrentValue, $this->ReportGroups[0])) {
				$this->LevelBreak[1] = TRUE;
			}
		}
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// id_domains
		// dominio

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// id_domains
			$this->id_domains->ViewValue = $this->id_domains->CurrentValue;
			$this->id_domains->ViewCustomAttributes = "";

			// dominio
			$this->dominio->ViewValue = $this->dominio->CurrentValue;
			$this->dominio->ViewCustomAttributes = "";

			// id_domains
			$this->id_domains->LinkCustomAttributes = "";
			$this->id_domains->HrefValue = "";
			$this->id_domains->TooltipValue = "";

			// dominio
			$this->dominio->LinkCustomAttributes = "";
			$this->dominio->HrefValue = "";
			$this->dominio->TooltipValue = "";
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
if (!isset($Test_report)) $Test_report = new cTest_report();

// Page init
$Test_report->Page_Init();

// Page main
$Test_report->Page_Main();
?>
<?php include_once "header.php" ?>
<?php if ($Test->Export == "") { ?>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php } ?>
<?php if ($Test->Export == "") { ?>
<?php } ?>
<p><span id="ewPageCaption" class="ewTitle ewReportTitle"><?php echo $Language->Phrase("TblTypeReport") ?><?php echo $Test->TableCaption() ?>
&nbsp;&nbsp;</span><?php $Test_report->ExportOptions->Render("body"); ?>
</p>
<?php $Test_report->ShowPageHeader(); ?>
<form method="post">
<table class="ewReportTable">
<?php
$Test_report->DefaultFilter = "";
$Test_report->ReportFilter = $Test_report->DefaultFilter;
if ($Test_report->DbDetailFilter <> "") {
	if ($Test_report->ReportFilter <> "") $Test_report->ReportFilter .= " AND ";
	$Test_report->ReportFilter .= "(" . $Test_report->DbDetailFilter . ")";
}

// Set up filter and load Group level sql
$Test->CurrentFilter = $Test_report->ReportFilter;
$Test_report->ReportSql = $Test->GroupSQL();

// Load recordset
$Test_report->Recordset = $conn->Execute($Test_report->ReportSql);

// Get First Row
if (!$Test_report->Recordset->EOF) {
	$Test->dominio->setDbValue($Test_report->Recordset->fields('dominio'));
	$Test_report->ReportGroups[0] = $Test->dominio->DbValue;
}
$Test_report->RecCnt = 0;
$Test_report->ReportCounts[0] = 0;
$Test_report->ChkLvlBreak();
while (!$Test_report->Recordset->EOF) {

	// Render for view
	$Test->RowType = EW_ROWTYPE_VIEW;
	$Test->ResetAttrs();
	$Test_report->RenderRow();

	// Show group headers
	if ($Test_report->LevelBreak[1]) { // Reset counter and aggregation
?>
	<tr><td class="ewGroupField"><span class="phpmaker"><?php echo $Test->dominio->FldCaption() ?></span></td>
	<td colspan=1 class="ewGroupName"><span class="phpmaker">
<span<?php echo $Test->dominio->ViewAttributes() ?>>
<?php echo $Test->dominio->ViewValue ?></span>
</span></td></tr>
<?php
	}

	// Get detail records
	$Test_report->ReportFilter = $Test_report->DefaultFilter;
	if ($Test_report->ReportFilter <> "") $Test_report->ReportFilter .= " AND ";
	if (is_null($Test->dominio->CurrentValue)) {
		$Test_report->ReportFilter .= "(`dominio` IS NULL)";
	} else {
		$Test_report->ReportFilter .= "(`dominio` = '" . ew_AdjustSql($Test->dominio->CurrentValue) . "')";
	}
	if ($Test_report->DbDetailFilter <> "") {
		if ($Test_report->ReportFilter <> "")
			$Test_report->ReportFilter .= " AND ";
		$Test_report->ReportFilter .= "(" . $Test_report->DbDetailFilter . ")";
	}

	// Set up detail SQL
	$Test->CurrentFilter = $Test_report->ReportFilter;
	$Test_report->ReportSql = $Test->DetailSQL();

	// Load detail records
	$Test_report->DetailRecordset = $conn->Execute($Test_report->ReportSql);
	$Test_report->DtlRecordCount = $Test_report->DetailRecordset->RecordCount();

	// Initialize aggregates
	if (!$Test_report->DetailRecordset->EOF) {
		$Test_report->RecCnt++;
		$Test->id_domains->setDbValue($Test_report->DetailRecordset->fields('id_domains'));
	}
	if ($Test_report->RecCnt == 1) {
		$Test_report->ReportCounts[0] = 0;
		$Test_report->ReportTotals[0][0] = 0;
	}
	for ($i = 1; $i <= 1; $i++) {
		if ($Test_report->LevelBreak[$i]) { // Reset counter and aggregation
			$Test_report->ReportCounts[$i] = 0;
			$Test_report->ReportTotals[$i][0] = 0;
		}
	}
	$Test_report->ReportCounts[0] += $Test_report->DtlRecordCount;
	$Test_report->ReportCounts[1] += $Test_report->DtlRecordCount;
?>
	<tr>
		<td></td>
		<td class="ewGroupHeader"><span class="phpmaker"><?php echo $Test->id_domains->FldCaption() ?></span></td>
	</tr>
<?php
	while (!$Test_report->DetailRecordset->EOF) {
		$Test->id_domains->setDbValue($Test_report->DetailRecordset->fields('id_domains'));
		$Test_report->ReportTotals[0][0] += $Test->id_domains->CurrentValue;
		$Test_report->ReportTotals[1][0] += $Test->id_domains->CurrentValue;

		// Render for view
		$Test->RowType = EW_ROWTYPE_VIEW;
		$Test->ResetAttrs();
		$Test_report->RenderRow();
?>
	<tr>
		<td></td>
		<td<?php echo $Test->id_domains->CellAttributes() ?>><span class="phpmaker">
<span<?php echo $Test->id_domains->ViewAttributes() ?>>
<?php echo $Test->id_domains->ViewValue ?></span>
</span></td>
	</tr>
<?php
		$Test_report->DetailRecordset->MoveNext();
	}
	$Test_report->DetailRecordset->Close();

	// Save old group data
	$Test_report->ReportGroups[0] = $Test->dominio->CurrentValue;

	// Get next record
	$Test_report->Recordset->MoveNext();
	if ($Test_report->Recordset->EOF) {
		$Test_report->RecCnt = 0; // EOF, force all level breaks
	} else {
		$Test->dominio->setDbValue($Test_report->Recordset->fields('dominio'));
	}
	$Test_report->ChkLvlBreak();

	// Show footers
	if ($Test_report->LevelBreak[1]) {
		$Test->dominio->CurrentValue = $Test_report->ReportGroups[0];

		// Render row for view
		$Test->RowType = EW_ROWTYPE_VIEW;
		$Test->ResetAttrs();
		$Test_report->RenderRow();
		$Test->dominio->CurrentValue = $Test->dominio->DbValue;
?>
	<tr><td colspan=2 class="ewGroupSummary"><span class="phpmaker"><?php echo $Language->Phrase("RptSumHead") ?>&nbsp;<?php echo $Test->dominio->FldCaption() ?>:&nbsp;<?php echo $Test->dominio->ViewValue ?> (<?php echo ew_FormatNumber($Test_report->ReportCounts[1],0) ?> <?php echo $Language->Phrase("RptDtlRec") ?>)</span></td></tr>
<?php
	$Test->id_domains->CurrentValue = $Test_report->ReportTotals[1][0];

	// Render row for view
	$Test->RowType = EW_ROWTYPE_VIEW;
	$Test->ResetAttrs();
	$Test_report->RenderRow();
?>
	<tr>
		<td class="ewGroupAggregate"><span class="phpmaker"><?php echo $Language->Phrase("RptSum") ?></span></td>
		<td<?php echo $Test->id_domains->CellAttributes() ?>><span class="phpmaker">
<span<?php echo $Test->id_domains->ViewAttributes() ?>>
<?php echo $Test->id_domains->ViewValue ?></span>
</span></td>
	</tr>
<?php
	if ($Test_report->ReportCounts[1] > 0) {
		$Test->id_domains->CurrentValue = $Test_report->ReportTotals[1][0] / $Test_report->ReportCounts[1];
	} else {
		$Test->id_domains->CurrentValue = 0;
	}

	// Render row for view
	$Test->RowType = EW_ROWTYPE_VIEW;
	$Test->ResetAttrs();
	$Test_report->RenderRow();
?>
	<tr>
		<td class="ewGroupAggregate"><span class="phpmaker"><?php echo $Language->Phrase("RptAvg") ?></span></td>
		<td<?php echo $Test->id_domains->CellAttributes() ?>><span class="phpmaker">
<span<?php echo $Test->id_domains->ViewAttributes() ?>>
<?php echo $Test->id_domains->ViewValue ?></span>
</span></td>
	</tr>
	<tr><td colspan=2><span class="phpmaker">&nbsp;<br /></span></td></tr>
<?php
}
}

// Close recordset
$Test_report->Recordset->Close();
?>
	<tr><td colspan=2><span class="phpmaker">&nbsp;<br /></span></td></tr>
	<tr><td colspan=2 class="ewGrandSummary"><span class="phpmaker"><?php echo $Language->Phrase("RptGrandTotal") ?>&nbsp;(<?php echo ew_FormatNumber($Test_report->ReportCounts[0], 0) ?>&nbsp;<?php echo $Language->Phrase("RptDtlRec") ?>)</span></td></tr>
<?php
	$Test->id_domains->CurrentValue = $Test_report->ReportTotals[0][0];

	// Render row for view
	$Test->RowType = EW_ROWTYPE_VIEW;
	$Test->ResetAttrs();
	$Test_report->RenderRow();
?>
	<tr>
		<td class="ewGroupAggregate"><span class="phpmaker"><?php echo $Language->Phrase("RptSum") ?></span></td>
		<td<?php echo $Test->id_domains->CellAttributes() ?>><span class="phpmaker">
<span<?php echo $Test->id_domains->ViewAttributes() ?>>
<?php echo $Test->id_domains->ViewValue ?></span>
</span></td>
	</tr>
<?php
	if ($Test_report->ReportCounts[1] > 0) {
		$Test->id_domains->CurrentValue = $Test_report->ReportTotals[0][0] / $Test_report->ReportCounts[0];
	} else {
		$Test->id_domains->CurrentValue = 0;
	}

	// Render row for view
	$Test->RowType = EW_ROWTYPE_VIEW;
	$Test->ResetAttrs();
	$Test_report->RenderRow();
?>
	<tr>
		<td class="ewGroupAggregate"><span class="phpmaker"><?php echo $Language->Phrase("RptAvg") ?></span></td>
		<td<?php echo $Test->id_domains->CellAttributes() ?>><span class="phpmaker">
<span<?php echo $Test->id_domains->ViewAttributes() ?>>
<?php echo $Test->id_domains->ViewValue ?></span>
</span></td>
	</tr>
	<tr><td colspan=2><span class="phpmaker">&nbsp;<br /></span></td></tr>
</table>
</form>
<?php
$Test_report->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php if ($Test->Export == "") { ?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php } ?>
<?php include_once "footer.php" ?>
<?php
$Test_report->Page_Terminate();
?>
