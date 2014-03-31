<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg9.php" ?>
<?php include_once "ewmysql9.php" ?>
<?php include_once "phpfn9.php" ?>
<?php include_once "entriesinfo.php" ?>
<?php include_once "domainsinfo.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "userfn9.php" ?>
<?php

//
// Page class
//

$entries_delete = NULL; // Initialize page object first

class centries_delete extends centries {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{3554BCCA-7E88-4E52-9661-DF55D75275C9}";

	// Table name
	var $TableName = 'entries';

	// Page object name
	var $PageObjName = 'entries_delete';

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

		// Table object (entries)
		if (!isset($GLOBALS["entries"])) {
			$GLOBALS["entries"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["entries"];
		}

		// Table object (domains)
		if (!isset($GLOBALS['domains'])) $GLOBALS['domains'] = new cdomains();

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'entries', TRUE);

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
		if (!$Security->CanDelete()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate("entrieslist.php");
		}
		$Security->UserID_Loading();
		if ($Security->IsLoggedIn()) $Security->LoadUserID();
		$Security->UserID_Loaded();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"];
		$this->identries->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
			$this->Page_Terminate($this->james_url( "entrieslist.php" )); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in entries class, entriesinfo.php

		$this->CurrentFilter = $sFilter;

		// Get action
		if (@$_POST["a_delete"] <> "") {
			$this->CurrentAction = $_POST["a_delete"];
		} else {
			$this->CurrentAction = "I"; // Display record
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
		$this->identries->setDbValue($rs->fields('identries'));
		$this->domain_id->setDbValue($rs->fields('domain_id'));
		$this->hash_content->setDbValue($rs->fields('hash_content'));
		$this->fuente->setDbValue($rs->fields('fuente'));
		$this->published->setDbValue($rs->fields('published'));
		$this->updated->setDbValue($rs->fields('updated'));
		$this->categorias->setDbValue($rs->fields('categorias'));
		$this->titulo->setDbValue($rs->fields('titulo'));
		$this->contenido->setDbValue($rs->fields('contenido'));
		$this->id->setDbValue($rs->fields('id'));
		$this->islive->setDbValue($rs->fields('islive'));
		$this->thumbnail->setDbValue($rs->fields('thumbnail'));
		$this->reqdate->setDbValue($rs->fields('reqdate'));
		$this->author->setDbValue($rs->fields('author'));
		$this->trans_en->setDbValue($rs->fields('trans_en'));
		$this->trans_es->setDbValue($rs->fields('trans_es'));
		$this->trans_fr->setDbValue($rs->fields('trans_fr'));
		$this->trans_it->setDbValue($rs->fields('trans_it'));
		$this->fid->setDbValue($rs->fields('fid'));
		$this->fmd5->setDbValue($rs->fields('fmd5'));
		$this->tool_id->setDbValue($rs->fields('tool_id'));
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// identries
		// domain_id
		// hash_content
		// fuente
		// published
		// updated
		// categorias
		// titulo
		// contenido
		// id
		// islive
		// thumbnail
		// reqdate
		// author
		// trans_en
		// trans_es
		// trans_fr
		// trans_it
		// fid
		// fmd5
		// tool_id

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// identries
			$this->identries->ViewValue = $this->identries->CurrentValue;
			$this->identries->ViewCustomAttributes = "";

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

			// hash_content
			$this->hash_content->ViewValue = $this->hash_content->CurrentValue;
			$this->hash_content->ViewCustomAttributes = "";

			// fuente
			$this->fuente->ViewValue = $this->fuente->CurrentValue;
			$this->fuente->ViewCustomAttributes = "";

			// published
			$this->published->ViewValue = $this->published->CurrentValue;
			$this->published->ViewValue = ew_FormatDateTime($this->published->ViewValue, 5);
			$this->published->ViewCustomAttributes = "";

			// updated
			$this->updated->ViewValue = $this->updated->CurrentValue;
			$this->updated->ViewValue = ew_FormatDateTime($this->updated->ViewValue, 5);
			$this->updated->ViewCustomAttributes = "";

			// categorias
			$this->categorias->ViewValue = $this->categorias->CurrentValue;
			$this->categorias->ViewCustomAttributes = "";

			// titulo
			$this->titulo->ViewValue = $this->titulo->CurrentValue;
			$this->titulo->ViewCustomAttributes = "";

			// id
			$this->id->ViewValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// islive
			$this->islive->ViewValue = $this->islive->CurrentValue;
			$this->islive->ViewCustomAttributes = "";

			// thumbnail
			$this->thumbnail->ViewValue = $this->thumbnail->CurrentValue;
			$this->thumbnail->ViewCustomAttributes = "";

			// reqdate
			$this->reqdate->ViewValue = $this->reqdate->CurrentValue;
			$this->reqdate->ViewValue = ew_FormatDateTime($this->reqdate->ViewValue, 5);
			$this->reqdate->ViewCustomAttributes = "";

			// author
			$this->author->ViewValue = $this->author->CurrentValue;
			$this->author->ViewCustomAttributes = "";

			// trans_en
			$this->trans_en->ViewValue = $this->trans_en->CurrentValue;
			$this->trans_en->ViewCustomAttributes = "";

			// trans_es
			$this->trans_es->ViewValue = $this->trans_es->CurrentValue;
			$this->trans_es->ViewCustomAttributes = "";

			// trans_fr
			$this->trans_fr->ViewValue = $this->trans_fr->CurrentValue;
			$this->trans_fr->ViewCustomAttributes = "";

			// trans_it
			$this->trans_it->ViewValue = $this->trans_it->CurrentValue;
			$this->trans_it->ViewCustomAttributes = "";

			// fid
			$this->fid->ViewValue = $this->fid->CurrentValue;
			$this->fid->ViewCustomAttributes = "";

			// fmd5
			$this->fmd5->ViewValue = $this->fmd5->CurrentValue;
			$this->fmd5->ViewCustomAttributes = "";

			// tool_id
			$this->tool_id->ViewValue = $this->tool_id->CurrentValue;
			$this->tool_id->ViewCustomAttributes = "";

			// identries
			$this->identries->LinkCustomAttributes = "";
			$this->identries->HrefValue = "";
			$this->identries->TooltipValue = "";

			// titulo
			$this->titulo->LinkCustomAttributes = "";
			$this->titulo->HrefValue = "";
			$this->titulo->TooltipValue = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// islive
			$this->islive->LinkCustomAttributes = "";
			$this->islive->HrefValue = "";
			$this->islive->TooltipValue = "";

			// tool_id
			$this->tool_id->LinkCustomAttributes = "";
			$this->tool_id->HrefValue = "";
			$this->tool_id->TooltipValue = "";
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
		if (!$Security->CanDelete()) {
			$this->setFailureMessage($Language->Phrase("NoDeletePermission")); // No delete permission
			return FALSE;
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
				$sThisKey .= $row['identries'];
				if ($sThisKey <> "") $sThisKey .= $GLOBALS["EW_COMPOSITE_KEY_SEPARATOR"];
				$sThisKey .= $row['id'];
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
if (!isset($entries_delete)) $entries_delete = new centries_delete();

// Page init
$entries_delete->Page_Init();

// Page main
$entries_delete->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var entries_delete = new ew_Page("entries_delete");
entries_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = entries_delete.PageID; // For backward compatibility

// Form object
var fentriesdelete = new ew_Form("fentriesdelete");

// Form_CustomValidate event
fentriesdelete.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fentriesdelete.ValidateRequired = true;
<?php } else { ?>
fentriesdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($entries_delete->Recordset = $entries_delete->LoadRecordset())
	$entries_deleteTotalRecs = $entries_delete->Recordset->RecordCount(); // Get record count
if ($entries_deleteTotalRecs <= 0) { // No record found, exit
	if ($entries_delete->Recordset)
		$entries_delete->Recordset->Close();
	$entries_delete->Page_Terminate($this->james_url( "entrieslist.php" )); // Return to list
}
?>
<h4 id="ewPageCaption" class="page-header ewTitle ewTableTitle"> <?php echo $Language->Phrase("Delete") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $entries->TableCaption() ?></h4>
<a href="<?php echo $entries->getReturnUrl() ?>" id="a_GoBack" class="ewLink label"><i class="icon-arrow-left icon-white"></i><?php echo $Language->Phrase("GoBack") ?></a>
<?php $entries_delete->ShowPageHeader(); ?>
<?php
$entries_delete->ShowMessage();
?>
<form name="fentriesdelete" id="fentriesdelete" class="ewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="entries" />
<input type="hidden" name="a_delete" id="a_delete" value="D" />
<?php foreach ($entries_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>" />
<?php } ?>
<br />
<table id="tbl_entriesdelete" class="ewTable ewTableSeparate table table-striped table-bordered ">
<?php echo $entries->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
		<th><span id="elh_entries_identries" class="entries_identries">
		<?php echo $entries->identries->FldCaption() ?></span></th>
		<th><span id="elh_entries_titulo" class="entries_titulo">
		<?php echo $entries->titulo->FldCaption() ?></span></th>
		<th><span id="elh_entries_id" class="entries_id">
		<?php echo $entries->id->FldCaption() ?></span></th>
		<th><span id="elh_entries_islive" class="entries_islive">
		<?php echo $entries->islive->FldCaption() ?></span></th>
		<th><span id="elh_entries_tool_id" class="entries_tool_id">
		<?php echo $entries->tool_id->FldCaption() ?></span></th>
	</tr>
	</thead>
	<tbody>
<?php
$entries_delete->RecCnt = 0;
$i = 0;
while (!$entries_delete->Recordset->EOF) {
	$entries_delete->RecCnt++;
	$entries_delete->RowCnt++;

	// Set row properties
	$entries->ResetAttrs();
	$entries->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$entries_delete->LoadRowValues($entries_delete->Recordset);

	// Render row
	$entries_delete->RenderRow();
?>
	<tr<?php echo $entries->RowAttributes() ?>>
		<td<?php echo $entries->identries->CellAttributes() ?>><span id="el<?php echo $entries_delete->RowCnt ?>_entries_identries" class="entries_identries">
<div id="orig<?php echo $entries_delete->RowCnt ?>_entries_identries" class="ewDisplayNone">
<span<?php echo $entries->identries->ViewAttributes() ?>>
<?php echo $entries->identries->ListViewValue() ?></span>
</div>
<?php    
       
if( BASENAME($_SERVER['PHP_SELF']) == 'entrieslist.php' ){   // solo si es entrieslist.php
   $data  = '<a class="operacion btn btn-success" name="ppt" id="'.CurrentTable()->identries->CurrentValue.'" href="#">ppt</a> ';  
   $data .= '<a class="operacion btn btn-primary" name="pdf" id="'.CurrentTable()->identries->CurrentValue.'" href="#">pdf</a> ';  
   $data .= '<a class="operacion btn btn-primary" name="mp4" id="'.CurrentTable()->identries->CurrentValue.'" href="#">mp4</a> ';    
   $data .= '<a class="operacion btn btn-primary" name="imagen" id="'.CurrentTable()->identries->CurrentValue.'" href="#">imagen</a> ';    
   echo $data;                                                                                                          
} else    {
   //$data  = '<a class="operacion btn btn-success" name="BackUp"    id="'.$_GET['id_domains'].'" href="#">BackUp</a> ';  
   //$data .= '<a class="operacion btn btn-primary" name="Traducion" id="'.$_GET['id_domains'].'" href="#">Traducion</a>';    
   //echo $data;          
} 
?>                  
<?php if (debug01) echo CurrentTable()->identries->CurrentValue;   ?>   
<script>   
/*  NO SE VA A USAR
Todo : mostrar solo si esta con showmaster
var ewpagerform  =  $("#ewpagerformTOOLS");
if ($("#ewpagerformTOOLS").length == 0) {        
        tools = '<a class="operacion btn btn-success" name="push_entries" id="<?php CurrentTable()->id_domains->CurrentValue ?>" href="#">Actualizar Blog</a>';
        tools += ' <a class="operacion btn btn-success" name="get_entries" id="<?php CurrentTable()->id_domains->CurrentValue ?>" href="#">Obtener Nuevas</a>';
        <?php          
        if( isset($_GET['showmaster']) ){
            echo " ewpagerformTOOLS = $('<div id=\"ewpagerformTOOLS\">'+tools+'</div>').appendTo('#ewpagerform');"; 
            }
        ?>        
}         
*/                  
</script>
</span></td>
		<td<?php echo $entries->titulo->CellAttributes() ?>><span id="el<?php echo $entries_delete->RowCnt ?>_entries_titulo" class="entries_titulo">
<span<?php echo $entries->titulo->ViewAttributes() ?>>
<?php echo $entries->titulo->ListViewValue() ?></span>
</span></td>
		<td<?php echo $entries->id->CellAttributes() ?>><span id="el<?php echo $entries_delete->RowCnt ?>_entries_id" class="entries_id">
<span<?php echo $entries->id->ViewAttributes() ?>>
<?php echo $entries->id->ListViewValue() ?></span>
</span></td>
		<td<?php echo $entries->islive->CellAttributes() ?>><span id="el<?php echo $entries_delete->RowCnt ?>_entries_islive" class="entries_islive">
<span<?php echo $entries->islive->ViewAttributes() ?>>
<?php echo $entries->islive->ListViewValue() ?></span>
</span></td>
		<td<?php echo $entries->tool_id->CellAttributes() ?>><span id="el<?php echo $entries_delete->RowCnt ?>_entries_tool_id" class="entries_tool_id">
<span<?php echo $entries->tool_id->ViewAttributes() ?>>
<?php echo $entries->tool_id->ListViewValue() ?></span>
</span></td>
	</tr>
<?php
	$entries_delete->Recordset->MoveNext();
}
$entries_delete->Recordset->Close();
?>
</tbody>
</table>
<input class="ewLink btn btn-danger" type="submit" name="Action" value="<?php echo ew_BtnCaption($Language->Phrase("DeleteBtn")) ?>" />
</form>
<script type="text/javascript">
fentriesdelete.Init();
</script>
<?php
$entries_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$entries_delete->Page_Terminate();
?>
