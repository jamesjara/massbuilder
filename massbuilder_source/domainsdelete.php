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
<?php include_once "userfn9.php" ?>
<?php

//
// Page class
//

$domains_delete = NULL; // Initialize page object first

class cdomains_delete extends cdomains {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{3554BCCA-7E88-4E52-9661-DF55D75275C9}";

	// Table name
	var $TableName = 'domains';

	// Page object name
	var $PageObjName = 'domains_delete';

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
			define("EW_PAGE_ID", 'delete', TRUE);

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
		if (!$Security->CanDelete()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate("domainslist.php");
		}
		$Security->UserID_Loading();
		if ($Security->IsLoggedIn()) $Security->LoadUserID();
		$Security->UserID_Loaded();
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
			$this->Page_Terminate($this->james_url( "domainslist.php" )); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in domains class, domainsinfo.php

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
		$this->dominio->setDbValue($rs->fields('dominio'));
		$this->id_domains->setDbValue($rs->fields('id_domains'));
		$this->id_proyecto->setDbValue($rs->fields('id_proyecto'));
		$this->hosted_in->setDbValue($rs->fields('hosted_in'));
		$this->map->setDbValue($rs->fields('map'));
		$this->bid->setDbValue($rs->fields('bid'));
		$this->_language->setDbValue($rs->fields('language'));
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
				$sThisKey .= $row['id_domains'];
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
if (!isset($domains_delete)) $domains_delete = new cdomains_delete();

// Page init
$domains_delete->Page_Init();

// Page main
$domains_delete->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var domains_delete = new ew_Page("domains_delete");
domains_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = domains_delete.PageID; // For backward compatibility

// Form object
var fdomainsdelete = new ew_Form("fdomainsdelete");

// Form_CustomValidate event
fdomainsdelete.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fdomainsdelete.ValidateRequired = true;
<?php } else { ?>
fdomainsdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($domains_delete->Recordset = $domains_delete->LoadRecordset())
	$domains_deleteTotalRecs = $domains_delete->Recordset->RecordCount(); // Get record count
if ($domains_deleteTotalRecs <= 0) { // No record found, exit
	if ($domains_delete->Recordset)
		$domains_delete->Recordset->Close();
	$domains_delete->Page_Terminate($this->james_url( "domainslist.php" )); // Return to list
}
?>
<h4 id="ewPageCaption" class="page-header ewTitle ewTableTitle"> <?php echo $Language->Phrase("Delete") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $domains->TableCaption() ?></h4>
<a href="<?php echo $domains->getReturnUrl() ?>" id="a_GoBack" class="ewLink label"><i class="icon-arrow-left icon-white"></i><?php echo $Language->Phrase("GoBack") ?></a>
<?php $domains_delete->ShowPageHeader(); ?>
<?php
$domains_delete->ShowMessage();
?>
<form name="fdomainsdelete" id="fdomainsdelete" class="ewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="domains" />
<input type="hidden" name="a_delete" id="a_delete" value="D" />
<?php foreach ($domains_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>" />
<?php } ?>
<br />
<table id="tbl_domainsdelete" class="ewTable ewTableSeparate table table-striped table-bordered ">
<?php echo $domains->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
		<th><span id="elh_domains_dominio" class="domains_dominio">
		<?php echo $domains->dominio->FldCaption() ?></span></th>
		<th><span id="elh_domains_id_domains" class="domains_id_domains">
		<?php echo $domains->id_domains->FldCaption() ?></span></th>
		<th><span id="elh_domains_hosted_in" class="domains_hosted_in">
		<?php echo $domains->hosted_in->FldCaption() ?></span></th>
	</tr>
	</thead>
	<tbody>
<?php
$domains_delete->RecCnt = 0;
$i = 0;
while (!$domains_delete->Recordset->EOF) {
	$domains_delete->RecCnt++;
	$domains_delete->RowCnt++;

	// Set row properties
	$domains->ResetAttrs();
	$domains->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$domains_delete->LoadRowValues($domains_delete->Recordset);

	// Render row
	$domains_delete->RenderRow();
?>
	<tr<?php echo $domains->RowAttributes() ?>>
		<td<?php echo $domains->dominio->CellAttributes() ?>><span id="el<?php echo $domains_delete->RowCnt ?>_domains_dominio" class="domains_dominio">
<span<?php echo $domains->dominio->ViewAttributes() ?>>
<?php echo $domains->dominio->ListViewValue() ?></span>
</span></td>
		<td<?php echo $domains->id_domains->CellAttributes() ?>><span id="el<?php echo $domains_delete->RowCnt ?>_domains_id_domains" class="domains_id_domains">
<div id="orig<?php echo $domains_delete->RowCnt ?>_domains_id_domains" class="ewDisplayNone">
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
		<td<?php echo $domains->hosted_in->CellAttributes() ?>><span id="el<?php echo $domains_delete->RowCnt ?>_domains_hosted_in" class="domains_hosted_in">
<span<?php echo $domains->hosted_in->ViewAttributes() ?>>
<?php echo $domains->hosted_in->ListViewValue() ?></span>
</span></td>
	</tr>
<?php
	$domains_delete->Recordset->MoveNext();
}
$domains_delete->Recordset->Close();
?>
</tbody>
</table>
<input class="ewLink btn btn-danger" type="submit" name="Action" value="<?php echo ew_BtnCaption($Language->Phrase("DeleteBtn")) ?>" />
</form>
<script type="text/javascript">
fdomainsdelete.Init();
</script>
<?php
$domains_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$domains_delete->Page_Terminate();
?>
