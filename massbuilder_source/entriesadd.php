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

$entries_add = NULL; // Initialize page object first

class centries_add extends centries {

	// Page ID
	var $PageID = 'add';

	// Project ID
	var $ProjectID = "{3554BCCA-7E88-4E52-9661-DF55D75275C9}";

	// Table name
	var $TableName = 'entries';

	// Page object name
	var $PageObjName = 'entries_add';

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
			define("EW_PAGE_ID", 'add', TRUE);

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
		if (!$Security->CanAdd()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate("entrieslist.php");
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
			if (@$_GET["identries"] != "") {
				$this->identries->setQueryStringValue($_GET["identries"]);
				$this->setKey("identries", $this->identries->CurrentValue); // Set up key
			} else {
				$this->setKey("identries", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if (@$_GET["id"] != "") {
				$this->id->setQueryStringValue($_GET["id"]);
				$this->setKey("id", $this->id->CurrentValue); // Set up key
			} else {
				$this->setKey("id", ""); // Clear key
				$this->CopyRecord = FALSE;
			}
			if ($this->CopyRecord) {
				$this->CurrentAction = "C"; // Copy record
			} else {
				$this->CurrentAction = "I"; // Display blank record
				$this->LoadDefaultValues(); // Load default values
			}
		}

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
					$this->Page_Terminate($this->james_url( "entrieslist.php" )); // No matching record, return to list
				}
				break;
			case "A": // Add new record
				$this->SendEmail = TRUE; // Send email on add success
				if ($this->AddRow($this->OldRecordset)) { // Add successful
					if ($this->getSuccessMessage() == "")
						$this->setSuccessMessage($Language->Phrase("AddSuccess")); // Set up success message
					$sReturnUrl = $this->getReturnUrl();
					if (ew_GetPageName($sReturnUrl) == "entriesview.php")
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
		$this->domain_id->CurrentValue = NULL;
		$this->domain_id->OldValue = $this->domain_id->CurrentValue;
		$this->hash_content->CurrentValue = NULL;
		$this->hash_content->OldValue = $this->hash_content->CurrentValue;
		$this->fuente->CurrentValue = NULL;
		$this->fuente->OldValue = $this->fuente->CurrentValue;
		$this->published->CurrentValue = NULL;
		$this->published->OldValue = $this->published->CurrentValue;
		$this->updated->CurrentValue = NULL;
		$this->updated->OldValue = $this->updated->CurrentValue;
		$this->categorias->CurrentValue = NULL;
		$this->categorias->OldValue = $this->categorias->CurrentValue;
		$this->titulo->CurrentValue = NULL;
		$this->titulo->OldValue = $this->titulo->CurrentValue;
		$this->contenido->CurrentValue = NULL;
		$this->contenido->OldValue = $this->contenido->CurrentValue;
		$this->id->CurrentValue = NULL;
		$this->id->OldValue = $this->id->CurrentValue;
		$this->islive->CurrentValue = NULL;
		$this->islive->OldValue = $this->islive->CurrentValue;
		$this->thumbnail->CurrentValue = NULL;
		$this->thumbnail->OldValue = $this->thumbnail->CurrentValue;
		$this->reqdate->CurrentValue = NULL;
		$this->reqdate->OldValue = $this->reqdate->CurrentValue;
		$this->author->CurrentValue = NULL;
		$this->author->OldValue = $this->author->CurrentValue;
		$this->trans_en->CurrentValue = NULL;
		$this->trans_en->OldValue = $this->trans_en->CurrentValue;
		$this->trans_es->CurrentValue = NULL;
		$this->trans_es->OldValue = $this->trans_es->CurrentValue;
		$this->trans_fr->CurrentValue = NULL;
		$this->trans_fr->OldValue = $this->trans_fr->CurrentValue;
		$this->trans_it->CurrentValue = NULL;
		$this->trans_it->OldValue = $this->trans_it->CurrentValue;
		$this->fid->CurrentValue = NULL;
		$this->fid->OldValue = $this->fid->CurrentValue;
		$this->fmd5->CurrentValue = NULL;
		$this->fmd5->OldValue = $this->fmd5->CurrentValue;
		$this->tool_id->CurrentValue = NULL;
		$this->tool_id->OldValue = $this->tool_id->CurrentValue;
	}

	// Load form values
	function LoadFormValues() {

		// Load from form
		global $objForm;
		if (!$this->domain_id->FldIsDetailKey) {
			$this->domain_id->setFormValue($objForm->GetValue("x_domain_id"));
		}
		if (!$this->hash_content->FldIsDetailKey) {
			$this->hash_content->setFormValue($objForm->GetValue("x_hash_content"));
		}
		if (!$this->fuente->FldIsDetailKey) {
			$this->fuente->setFormValue($objForm->GetValue("x_fuente"));
		}
		if (!$this->published->FldIsDetailKey) {
			$this->published->setFormValue($objForm->GetValue("x_published"));
			$this->published->CurrentValue = ew_UnFormatDateTime($this->published->CurrentValue, 5);
		}
		if (!$this->updated->FldIsDetailKey) {
			$this->updated->setFormValue($objForm->GetValue("x_updated"));
			$this->updated->CurrentValue = ew_UnFormatDateTime($this->updated->CurrentValue, 5);
		}
		if (!$this->categorias->FldIsDetailKey) {
			$this->categorias->setFormValue($objForm->GetValue("x_categorias"));
		}
		if (!$this->titulo->FldIsDetailKey) {
			$this->titulo->setFormValue($objForm->GetValue("x_titulo"));
		}
		if (!$this->contenido->FldIsDetailKey) {
			$this->contenido->setFormValue($objForm->GetValue("x_contenido"));
		}
		if (!$this->id->FldIsDetailKey) {
			$this->id->setFormValue($objForm->GetValue("x_id"));
		}
		if (!$this->islive->FldIsDetailKey) {
			$this->islive->setFormValue($objForm->GetValue("x_islive"));
		}
		if (!$this->thumbnail->FldIsDetailKey) {
			$this->thumbnail->setFormValue($objForm->GetValue("x_thumbnail"));
		}
		if (!$this->reqdate->FldIsDetailKey) {
			$this->reqdate->setFormValue($objForm->GetValue("x_reqdate"));
			$this->reqdate->CurrentValue = ew_UnFormatDateTime($this->reqdate->CurrentValue, 5);
		}
		if (!$this->author->FldIsDetailKey) {
			$this->author->setFormValue($objForm->GetValue("x_author"));
		}
		if (!$this->trans_en->FldIsDetailKey) {
			$this->trans_en->setFormValue($objForm->GetValue("x_trans_en"));
		}
		if (!$this->trans_es->FldIsDetailKey) {
			$this->trans_es->setFormValue($objForm->GetValue("x_trans_es"));
		}
		if (!$this->trans_fr->FldIsDetailKey) {
			$this->trans_fr->setFormValue($objForm->GetValue("x_trans_fr"));
		}
		if (!$this->trans_it->FldIsDetailKey) {
			$this->trans_it->setFormValue($objForm->GetValue("x_trans_it"));
		}
		if (!$this->fid->FldIsDetailKey) {
			$this->fid->setFormValue($objForm->GetValue("x_fid"));
		}
		if (!$this->fmd5->FldIsDetailKey) {
			$this->fmd5->setFormValue($objForm->GetValue("x_fmd5"));
		}
		if (!$this->tool_id->FldIsDetailKey) {
			$this->tool_id->setFormValue($objForm->GetValue("x_tool_id"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadOldRecord();
		$this->domain_id->CurrentValue = $this->domain_id->FormValue;
		$this->hash_content->CurrentValue = $this->hash_content->FormValue;
		$this->fuente->CurrentValue = $this->fuente->FormValue;
		$this->published->CurrentValue = $this->published->FormValue;
		$this->published->CurrentValue = ew_UnFormatDateTime($this->published->CurrentValue, 5);
		$this->updated->CurrentValue = $this->updated->FormValue;
		$this->updated->CurrentValue = ew_UnFormatDateTime($this->updated->CurrentValue, 5);
		$this->categorias->CurrentValue = $this->categorias->FormValue;
		$this->titulo->CurrentValue = $this->titulo->FormValue;
		$this->contenido->CurrentValue = $this->contenido->FormValue;
		$this->id->CurrentValue = $this->id->FormValue;
		$this->islive->CurrentValue = $this->islive->FormValue;
		$this->thumbnail->CurrentValue = $this->thumbnail->FormValue;
		$this->reqdate->CurrentValue = $this->reqdate->FormValue;
		$this->reqdate->CurrentValue = ew_UnFormatDateTime($this->reqdate->CurrentValue, 5);
		$this->author->CurrentValue = $this->author->FormValue;
		$this->trans_en->CurrentValue = $this->trans_en->FormValue;
		$this->trans_es->CurrentValue = $this->trans_es->FormValue;
		$this->trans_fr->CurrentValue = $this->trans_fr->FormValue;
		$this->trans_it->CurrentValue = $this->trans_it->FormValue;
		$this->fid->CurrentValue = $this->fid->FormValue;
		$this->fmd5->CurrentValue = $this->fmd5->FormValue;
		$this->tool_id->CurrentValue = $this->tool_id->FormValue;
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

	// Load old record
	function LoadOldRecord() {

		// Load key values from Session
		$bValidKey = TRUE;
		if (strval($this->getKey("identries")) <> "")
			$this->identries->CurrentValue = $this->getKey("identries"); // identries
		else
			$bValidKey = FALSE;
		if (strval($this->getKey("id")) <> "")
			$this->id->CurrentValue = $this->getKey("id"); // id
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

			// contenido
			$this->contenido->ViewValue = $this->contenido->CurrentValue;
			$this->contenido->ViewCustomAttributes = "";

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

			// domain_id
			$this->domain_id->LinkCustomAttributes = "";
			$this->domain_id->HrefValue = "";
			$this->domain_id->TooltipValue = "";

			// hash_content
			$this->hash_content->LinkCustomAttributes = "";
			$this->hash_content->HrefValue = "";
			$this->hash_content->TooltipValue = "";

			// fuente
			$this->fuente->LinkCustomAttributes = "";
			$this->fuente->HrefValue = "";
			$this->fuente->TooltipValue = "";

			// published
			$this->published->LinkCustomAttributes = "";
			$this->published->HrefValue = "";
			$this->published->TooltipValue = "";

			// updated
			$this->updated->LinkCustomAttributes = "";
			$this->updated->HrefValue = "";
			$this->updated->TooltipValue = "";

			// categorias
			$this->categorias->LinkCustomAttributes = "";
			$this->categorias->HrefValue = "";
			$this->categorias->TooltipValue = "";

			// titulo
			$this->titulo->LinkCustomAttributes = "";
			$this->titulo->HrefValue = "";
			$this->titulo->TooltipValue = "";

			// contenido
			$this->contenido->LinkCustomAttributes = "";
			$this->contenido->HrefValue = "";
			$this->contenido->TooltipValue = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// islive
			$this->islive->LinkCustomAttributes = "";
			$this->islive->HrefValue = "";
			$this->islive->TooltipValue = "";

			// thumbnail
			$this->thumbnail->LinkCustomAttributes = "";
			$this->thumbnail->HrefValue = "";
			$this->thumbnail->TooltipValue = "";

			// reqdate
			$this->reqdate->LinkCustomAttributes = "";
			$this->reqdate->HrefValue = "";
			$this->reqdate->TooltipValue = "";

			// author
			$this->author->LinkCustomAttributes = "";
			$this->author->HrefValue = "";
			$this->author->TooltipValue = "";

			// trans_en
			$this->trans_en->LinkCustomAttributes = "";
			$this->trans_en->HrefValue = "";
			$this->trans_en->TooltipValue = "";

			// trans_es
			$this->trans_es->LinkCustomAttributes = "";
			$this->trans_es->HrefValue = "";
			$this->trans_es->TooltipValue = "";

			// trans_fr
			$this->trans_fr->LinkCustomAttributes = "";
			$this->trans_fr->HrefValue = "";
			$this->trans_fr->TooltipValue = "";

			// trans_it
			$this->trans_it->LinkCustomAttributes = "";
			$this->trans_it->HrefValue = "";
			$this->trans_it->TooltipValue = "";

			// fid
			$this->fid->LinkCustomAttributes = "";
			$this->fid->HrefValue = "";
			$this->fid->TooltipValue = "";

			// fmd5
			$this->fmd5->LinkCustomAttributes = "";
			$this->fmd5->HrefValue = "";
			$this->fmd5->TooltipValue = "";

			// tool_id
			$this->tool_id->LinkCustomAttributes = "";
			$this->tool_id->HrefValue = "";
			$this->tool_id->TooltipValue = "";
		} elseif ($this->RowType == EW_ROWTYPE_ADD) { // Add row

			// domain_id
			$this->domain_id->EditCustomAttributes = "";
			if ($this->domain_id->getSessionValue() <> "") {
				$this->domain_id->CurrentValue = $this->domain_id->getSessionValue();
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
			} else {
			if (trim(strval($this->domain_id->CurrentValue)) == "") {
				$sFilterWrk = "0=1";
			} else {
				$sFilterWrk = "`id_domains`" . ew_SearchString("=", $this->domain_id->CurrentValue, EW_DATATYPE_NUMBER);
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
			$this->domain_id->EditValue = $arwrk;
			}

			// hash_content
			$this->hash_content->EditCustomAttributes = "";
			$this->hash_content->EditValue = ew_HtmlEncode($this->hash_content->CurrentValue);

			// fuente
			$this->fuente->EditCustomAttributes = "";
			$this->fuente->EditValue = ew_HtmlEncode($this->fuente->CurrentValue);

			// published
			$this->published->EditCustomAttributes = "";
			$this->published->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->published->CurrentValue, 5));

			// updated
			$this->updated->EditCustomAttributes = "";
			$this->updated->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->updated->CurrentValue, 5));

			// categorias
			$this->categorias->EditCustomAttributes = "";
			$this->categorias->EditValue = ew_HtmlEncode($this->categorias->CurrentValue);

			// titulo
			$this->titulo->EditCustomAttributes = "";
			$this->titulo->EditValue = ew_HtmlEncode($this->titulo->CurrentValue);

			// contenido
			$this->contenido->EditCustomAttributes = "";
			$this->contenido->EditValue = ew_HtmlEncode($this->contenido->CurrentValue);

			// id
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = ew_HtmlEncode($this->id->CurrentValue);

			// islive
			$this->islive->EditCustomAttributes = "";
			$this->islive->EditValue = ew_HtmlEncode($this->islive->CurrentValue);

			// thumbnail
			$this->thumbnail->EditCustomAttributes = "";
			$this->thumbnail->EditValue = ew_HtmlEncode($this->thumbnail->CurrentValue);

			// reqdate
			$this->reqdate->EditCustomAttributes = "";
			$this->reqdate->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->reqdate->CurrentValue, 5));

			// author
			$this->author->EditCustomAttributes = "";
			$this->author->EditValue = ew_HtmlEncode($this->author->CurrentValue);

			// trans_en
			$this->trans_en->EditCustomAttributes = "";
			$this->trans_en->EditValue = ew_HtmlEncode($this->trans_en->CurrentValue);

			// trans_es
			$this->trans_es->EditCustomAttributes = "";
			$this->trans_es->EditValue = ew_HtmlEncode($this->trans_es->CurrentValue);

			// trans_fr
			$this->trans_fr->EditCustomAttributes = "";
			$this->trans_fr->EditValue = ew_HtmlEncode($this->trans_fr->CurrentValue);

			// trans_it
			$this->trans_it->EditCustomAttributes = "";
			$this->trans_it->EditValue = ew_HtmlEncode($this->trans_it->CurrentValue);

			// fid
			$this->fid->EditCustomAttributes = "";
			$this->fid->EditValue = ew_HtmlEncode($this->fid->CurrentValue);

			// fmd5
			$this->fmd5->EditCustomAttributes = "";
			$this->fmd5->EditValue = ew_HtmlEncode($this->fmd5->CurrentValue);

			// tool_id
			$this->tool_id->EditCustomAttributes = "";
			$this->tool_id->EditValue = ew_HtmlEncode($this->tool_id->CurrentValue);

			// Edit refer script
			// domain_id

			$this->domain_id->HrefValue = "";

			// hash_content
			$this->hash_content->HrefValue = "";

			// fuente
			$this->fuente->HrefValue = "";

			// published
			$this->published->HrefValue = "";

			// updated
			$this->updated->HrefValue = "";

			// categorias
			$this->categorias->HrefValue = "";

			// titulo
			$this->titulo->HrefValue = "";

			// contenido
			$this->contenido->HrefValue = "";

			// id
			$this->id->HrefValue = "";

			// islive
			$this->islive->HrefValue = "";

			// thumbnail
			$this->thumbnail->HrefValue = "";

			// reqdate
			$this->reqdate->HrefValue = "";

			// author
			$this->author->HrefValue = "";

			// trans_en
			$this->trans_en->HrefValue = "";

			// trans_es
			$this->trans_es->HrefValue = "";

			// trans_fr
			$this->trans_fr->HrefValue = "";

			// trans_it
			$this->trans_it->HrefValue = "";

			// fid
			$this->fid->HrefValue = "";

			// fmd5
			$this->fmd5->HrefValue = "";

			// tool_id
			$this->tool_id->HrefValue = "";
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
		if (!ew_CheckDate($this->published->FormValue)) {
			ew_AddMessage($gsFormError, $this->published->FldErrMsg());
		}
		if (!ew_CheckDate($this->updated->FormValue)) {
			ew_AddMessage($gsFormError, $this->updated->FldErrMsg());
		}
		if (!is_null($this->id->FormValue) && $this->id->FormValue == "") {
			ew_AddMessage($gsFormError, $Language->Phrase("EnterRequiredField") . " - " . $this->id->FldCaption());
		}
		if (!ew_CheckInteger($this->id->FormValue)) {
			ew_AddMessage($gsFormError, $this->id->FldErrMsg());
		}
		if (!ew_CheckDate($this->reqdate->FormValue)) {
			ew_AddMessage($gsFormError, $this->reqdate->FldErrMsg());
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

		// domain_id
		$this->domain_id->SetDbValueDef($rsnew, $this->domain_id->CurrentValue, NULL, FALSE);

		// hash_content
		$this->hash_content->SetDbValueDef($rsnew, $this->hash_content->CurrentValue, NULL, FALSE);

		// fuente
		$this->fuente->SetDbValueDef($rsnew, $this->fuente->CurrentValue, NULL, FALSE);

		// published
		$this->published->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->published->CurrentValue, 5), NULL, FALSE);

		// updated
		$this->updated->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->updated->CurrentValue, 5), NULL, FALSE);

		// categorias
		$this->categorias->SetDbValueDef($rsnew, $this->categorias->CurrentValue, NULL, FALSE);

		// titulo
		$this->titulo->SetDbValueDef($rsnew, $this->titulo->CurrentValue, NULL, FALSE);

		// contenido
		$this->contenido->SetDbValueDef($rsnew, $this->contenido->CurrentValue, NULL, FALSE);

		// id
		$this->id->SetDbValueDef($rsnew, $this->id->CurrentValue, 0, FALSE);

		// islive
		$this->islive->SetDbValueDef($rsnew, $this->islive->CurrentValue, NULL, FALSE);

		// thumbnail
		$this->thumbnail->SetDbValueDef($rsnew, $this->thumbnail->CurrentValue, NULL, FALSE);

		// reqdate
		$this->reqdate->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->reqdate->CurrentValue, 5), NULL, FALSE);

		// author
		$this->author->SetDbValueDef($rsnew, $this->author->CurrentValue, NULL, FALSE);

		// trans_en
		$this->trans_en->SetDbValueDef($rsnew, $this->trans_en->CurrentValue, NULL, FALSE);

		// trans_es
		$this->trans_es->SetDbValueDef($rsnew, $this->trans_es->CurrentValue, NULL, FALSE);

		// trans_fr
		$this->trans_fr->SetDbValueDef($rsnew, $this->trans_fr->CurrentValue, NULL, FALSE);

		// trans_it
		$this->trans_it->SetDbValueDef($rsnew, $this->trans_it->CurrentValue, NULL, FALSE);

		// fid
		$this->fid->SetDbValueDef($rsnew, $this->fid->CurrentValue, NULL, FALSE);

		// fmd5
		$this->fmd5->SetDbValueDef($rsnew, $this->fmd5->CurrentValue, NULL, FALSE);

		// tool_id
		$this->tool_id->SetDbValueDef($rsnew, $this->tool_id->CurrentValue, NULL, FALSE);

		// Call Row Inserting event
		$rs = ($rsold == NULL) ? NULL : $rsold->fields;
		$bInsertRow = $this->Row_Inserting($rs, $rsnew);

		// Check if key value entered
		if ($bInsertRow && $this->ValidateKey && $this->id->CurrentValue == "" && $this->id->getSessionValue() == "") {
			$this->setFailureMessage($Language->Phrase("InvalidKeyValue"));
			$bInsertRow = FALSE;
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
			$this->identries->setDbValue($conn->Insert_ID());
			$rsnew['identries'] = $this->identries->DbValue;
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
			if ($sMasterTblVar == "domains") {
				$bValidMaster = TRUE;
				if (@$_GET["id_domains"] <> "") {
					$GLOBALS["domains"]->id_domains->setQueryStringValue($_GET["id_domains"]);
					$this->domain_id->setQueryStringValue($GLOBALS["domains"]->id_domains->QueryStringValue);
					$this->domain_id->setSessionValue($this->domain_id->QueryStringValue);
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
				if ($this->domain_id->QueryStringValue == "") $this->domain_id->setSessionValue("");
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
if (!isset($entries_add)) $entries_add = new centries_add();

// Page init
$entries_add->Page_Init();

// Page main
$entries_add->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var entries_add = new ew_Page("entries_add");
entries_add.PageID = "add"; // Page ID
var EW_PAGE_ID = entries_add.PageID; // For backward compatibility

// Form object
var fentriesadd = new ew_Form("fentriesadd");

// Validate form
fentriesadd.Validate = function(fobj) {
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
		elm = fobj.elements["x" + infix + "_published"];
		if (elm && !ew_CheckDate(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($entries->published->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_updated"];
		if (elm && !ew_CheckDate(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($entries->updated->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_id"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($entries->id->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_id"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($entries->id->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_reqdate"];
		if (elm && !ew_CheckDate(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($entries->reqdate->FldErrMsg()) ?>");

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
fentriesadd.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fentriesadd.ValidateRequired = true;
<?php } else { ?>
fentriesadd.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fentriesadd.Lists["x_domain_id"] = {"LinkField":"x_id_domains","Ajax":true,"AutoFill":false,"DisplayFields":["x_dominio","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<h4 id="ewPageCaption" class="page-header ewTitle ewTableTitle"> <?php echo $Language->Phrase("Add") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $entries->TableCaption() ?></h4>
<a href="<?php echo $entries->getReturnUrl() ?>" id="a_GoBack" class="ewLink label"><i class="icon-arrow-left icon-white"></i> <?php echo $Language->Phrase("GoBack") ?></a>
<?php $entries_add->ShowPageHeader(); ?>
<?php
$entries_add->ShowMessage();
?>
<form name="fentriesadd" id="fentriesadd" class="ewForm" action="<?php echo ew_CurrentPage() ?>" method="post" onsubmit="return ewForms[this.id].Submit();">
<input type="hidden" name="t" value="entries" />
<input type="hidden" name="a_add" id="a_add" value="A" />
<table id="tbl_entriesadd" class="ewTable ewTableSeparate table table-striped ">
<?php if ($entries->domain_id->Visible) { // domain_id ?>
	<tr id="r_domain_id"<?php echo $entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_entries_domain_id">
		<b><?php echo $entries->domain_id->FldCaption() ?></b>
		</span></td>
		<td<?php echo $entries->domain_id->CellAttributes() ?>><span id="el_entries_domain_id">
<?php if ($entries->domain_id->getSessionValue() <> "") { ?>
<span<?php echo $entries->domain_id->ViewAttributes() ?>>
<?php echo $entries->domain_id->ViewValue ?></span>
<input type="hidden" id="x_domain_id" name="x_domain_id" value="<?php echo ew_HtmlEncode($entries->domain_id->CurrentValue) ?>">
<?php } else { ?>
<select id="x_domain_id" name="x_domain_id"<?php echo $entries->domain_id->EditAttributes() ?>>
<?php
if (is_array($entries->domain_id->EditValue)) {
	$arwrk = $entries->domain_id->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($entries->domain_id->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
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
<input type="hidden" name="s_x_domain_id" id="s_x_domain_id" value="s=<?php echo TEAencrypt($sSqlWrk) ?>&fn=<?php echo urlencode($entries->domain_id->LookupFn) ?>&f0=<?php echo TEAencrypt("`id_domains` = {filter_value}"); ?>&t0=3" />
<?php } ?>
</span><?php echo $entries->domain_id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($entries->hash_content->Visible) { // hash_content ?>
	<tr id="r_hash_content"<?php echo $entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_entries_hash_content">
		<b><?php echo $entries->hash_content->FldCaption() ?></b>
		</span></td>
		<td<?php echo $entries->hash_content->CellAttributes() ?>><span id="el_entries_hash_content">
<input type="text" name="x_hash_content" id="x_hash_content" size="30" maxlength="145" value="<?php echo $entries->hash_content->EditValue ?>"<?php echo $entries->hash_content->EditAttributes() ?> />
</span><?php echo $entries->hash_content->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($entries->fuente->Visible) { // fuente ?>
	<tr id="r_fuente"<?php echo $entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_entries_fuente">
		<b><?php echo $entries->fuente->FldCaption() ?></b>
		</span></td>
		<td<?php echo $entries->fuente->CellAttributes() ?>><span id="el_entries_fuente">
<input type="text" name="x_fuente" id="x_fuente" size="30" maxlength="245" value="<?php echo $entries->fuente->EditValue ?>"<?php echo $entries->fuente->EditAttributes() ?> />
</span><?php echo $entries->fuente->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($entries->published->Visible) { // published ?>
	<tr id="r_published"<?php echo $entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_entries_published">
		<b><?php echo $entries->published->FldCaption() ?></b>
		</span></td>
		<td<?php echo $entries->published->CellAttributes() ?>><span id="el_entries_published">
<input type="text" name="x_published" id="x_published" value="<?php echo $entries->published->EditValue ?>"<?php echo $entries->published->EditAttributes() ?> />
<?php if (!$entries->published->ReadOnly && !$entries->published->Disabled && @$entries->published->EditAttrs["readonly"] == "" && @$entries->published->EditAttrs["disabled"] == "") { ?>
&nbsp;<img src="phpimages/calendar.png" id="fentriesadd$x_published$" name="fentriesadd$x_published$" alt="<?php echo $Language->Phrase("PickDate") ?>" title="<?php echo $Language->Phrase("PickDate") ?>" class="ewCalendar" />
<script type="text/javascript">
ew_CreateCalendar("fentriesadd", "x_published", "%Y/%m/%d");
</script>
<?php } ?>
</span><?php echo $entries->published->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($entries->updated->Visible) { // updated ?>
	<tr id="r_updated"<?php echo $entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_entries_updated">
		<b><?php echo $entries->updated->FldCaption() ?></b>
		</span></td>
		<td<?php echo $entries->updated->CellAttributes() ?>><span id="el_entries_updated">
<input type="text" name="x_updated" id="x_updated" value="<?php echo $entries->updated->EditValue ?>"<?php echo $entries->updated->EditAttributes() ?> />
<?php if (!$entries->updated->ReadOnly && !$entries->updated->Disabled && @$entries->updated->EditAttrs["readonly"] == "" && @$entries->updated->EditAttrs["disabled"] == "") { ?>
&nbsp;<img src="phpimages/calendar.png" id="fentriesadd$x_updated$" name="fentriesadd$x_updated$" alt="<?php echo $Language->Phrase("PickDate") ?>" title="<?php echo $Language->Phrase("PickDate") ?>" class="ewCalendar" />
<script type="text/javascript">
ew_CreateCalendar("fentriesadd", "x_updated", "%Y/%m/%d");
</script>
<?php } ?>
</span><?php echo $entries->updated->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($entries->categorias->Visible) { // categorias ?>
	<tr id="r_categorias"<?php echo $entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_entries_categorias">
		<b><?php echo $entries->categorias->FldCaption() ?></b>
		</span></td>
		<td<?php echo $entries->categorias->CellAttributes() ?>><span id="el_entries_categorias">
<textarea name="x_categorias" id="x_categorias" cols="35" rows="4"<?php echo $entries->categorias->EditAttributes() ?>><?php echo $entries->categorias->EditValue ?></textarea>
</span><?php echo $entries->categorias->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($entries->titulo->Visible) { // titulo ?>
	<tr id="r_titulo"<?php echo $entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_entries_titulo">
		<b><?php echo $entries->titulo->FldCaption() ?></b>
		</span></td>
		<td<?php echo $entries->titulo->CellAttributes() ?>><span id="el_entries_titulo">
<input type="text" name="x_titulo" id="x_titulo" size="30" maxlength="245" value="<?php echo $entries->titulo->EditValue ?>"<?php echo $entries->titulo->EditAttributes() ?> />
</span><?php echo $entries->titulo->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($entries->contenido->Visible) { // contenido ?>
	<tr id="r_contenido"<?php echo $entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_entries_contenido">
		<b><?php echo $entries->contenido->FldCaption() ?></b>
		</span></td>
		<td<?php echo $entries->contenido->CellAttributes() ?>><span id="el_entries_contenido">
<textarea name="x_contenido" id="x_contenido" cols="35" rows="4"<?php echo $entries->contenido->EditAttributes() ?>><?php echo $entries->contenido->EditValue ?></textarea>
<script type="text/javascript">
ew_CreateEditor("fentriesadd", "x_contenido", 35, 4, <?php echo ($entries->contenido->ReadOnly || FALSE) ? "true" : "false" ?>);
</script>
</span><?php echo $entries->contenido->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($entries->id->Visible) { // id ?>
	<tr id="r_id"<?php echo $entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_entries_id">
		<b><?php echo $entries->id->FldCaption() ?><?php echo $Language->Phrase("FieldRequiredIndicator") ?></b>
		</span></td>
		<td<?php echo $entries->id->CellAttributes() ?>><span id="el_entries_id">
<input type="text" name="x_id" id="x_id" size="30" value="<?php echo $entries->id->EditValue ?>"<?php echo $entries->id->EditAttributes() ?> />
</span><?php echo $entries->id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($entries->islive->Visible) { // islive ?>
	<tr id="r_islive"<?php echo $entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_entries_islive">
		<b><?php echo $entries->islive->FldCaption() ?></b>
		</span></td>
		<td<?php echo $entries->islive->CellAttributes() ?>><span id="el_entries_islive">
<input type="text" name="x_islive" id="x_islive" size="30" maxlength="245" value="<?php echo $entries->islive->EditValue ?>"<?php echo $entries->islive->EditAttributes() ?> />
</span><?php echo $entries->islive->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($entries->thumbnail->Visible) { // thumbnail ?>
	<tr id="r_thumbnail"<?php echo $entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_entries_thumbnail">
		<b><?php echo $entries->thumbnail->FldCaption() ?></b>
		</span></td>
		<td<?php echo $entries->thumbnail->CellAttributes() ?>><span id="el_entries_thumbnail">
<input type="text" name="x_thumbnail" id="x_thumbnail" size="30" maxlength="245" value="<?php echo $entries->thumbnail->EditValue ?>"<?php echo $entries->thumbnail->EditAttributes() ?> />
</span><?php echo $entries->thumbnail->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($entries->reqdate->Visible) { // reqdate ?>
	<tr id="r_reqdate"<?php echo $entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_entries_reqdate">
		<b><?php echo $entries->reqdate->FldCaption() ?></b>
		</span></td>
		<td<?php echo $entries->reqdate->CellAttributes() ?>><span id="el_entries_reqdate">
<input type="text" name="x_reqdate" id="x_reqdate" value="<?php echo $entries->reqdate->EditValue ?>"<?php echo $entries->reqdate->EditAttributes() ?> />
</span><?php echo $entries->reqdate->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($entries->author->Visible) { // author ?>
	<tr id="r_author"<?php echo $entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_entries_author">
		<b><?php echo $entries->author->FldCaption() ?></b>
		</span></td>
		<td<?php echo $entries->author->CellAttributes() ?>><span id="el_entries_author">
<input type="text" name="x_author" id="x_author" size="30" maxlength="245" value="<?php echo $entries->author->EditValue ?>"<?php echo $entries->author->EditAttributes() ?> />
</span><?php echo $entries->author->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($entries->trans_en->Visible) { // trans_en ?>
	<tr id="r_trans_en"<?php echo $entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_entries_trans_en">
		<b><?php echo $entries->trans_en->FldCaption() ?></b>
		</span></td>
		<td<?php echo $entries->trans_en->CellAttributes() ?>><span id="el_entries_trans_en">
<input type="text" name="x_trans_en" id="x_trans_en" size="30" maxlength="145" value="<?php echo $entries->trans_en->EditValue ?>"<?php echo $entries->trans_en->EditAttributes() ?> />
</span><?php echo $entries->trans_en->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($entries->trans_es->Visible) { // trans_es ?>
	<tr id="r_trans_es"<?php echo $entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_entries_trans_es">
		<b><?php echo $entries->trans_es->FldCaption() ?></b>
		</span></td>
		<td<?php echo $entries->trans_es->CellAttributes() ?>><span id="el_entries_trans_es">
<input type="text" name="x_trans_es" id="x_trans_es" size="30" maxlength="145" value="<?php echo $entries->trans_es->EditValue ?>"<?php echo $entries->trans_es->EditAttributes() ?> />
</span><?php echo $entries->trans_es->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($entries->trans_fr->Visible) { // trans_fr ?>
	<tr id="r_trans_fr"<?php echo $entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_entries_trans_fr">
		<b><?php echo $entries->trans_fr->FldCaption() ?></b>
		</span></td>
		<td<?php echo $entries->trans_fr->CellAttributes() ?>><span id="el_entries_trans_fr">
<input type="text" name="x_trans_fr" id="x_trans_fr" size="30" maxlength="145" value="<?php echo $entries->trans_fr->EditValue ?>"<?php echo $entries->trans_fr->EditAttributes() ?> />
</span><?php echo $entries->trans_fr->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($entries->trans_it->Visible) { // trans_it ?>
	<tr id="r_trans_it"<?php echo $entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_entries_trans_it">
		<b><?php echo $entries->trans_it->FldCaption() ?></b>
		</span></td>
		<td<?php echo $entries->trans_it->CellAttributes() ?>><span id="el_entries_trans_it">
<input type="text" name="x_trans_it" id="x_trans_it" size="30" maxlength="145" value="<?php echo $entries->trans_it->EditValue ?>"<?php echo $entries->trans_it->EditAttributes() ?> />
</span><?php echo $entries->trans_it->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($entries->fid->Visible) { // fid ?>
	<tr id="r_fid"<?php echo $entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_entries_fid">
		<b><?php echo $entries->fid->FldCaption() ?></b>
		</span></td>
		<td<?php echo $entries->fid->CellAttributes() ?>><span id="el_entries_fid">
<input type="text" name="x_fid" id="x_fid" size="30" maxlength="245" value="<?php echo $entries->fid->EditValue ?>"<?php echo $entries->fid->EditAttributes() ?> />
</span><?php echo $entries->fid->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($entries->fmd5->Visible) { // fmd5 ?>
	<tr id="r_fmd5"<?php echo $entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_entries_fmd5">
		<b><?php echo $entries->fmd5->FldCaption() ?></b>
		</span></td>
		<td<?php echo $entries->fmd5->CellAttributes() ?>><span id="el_entries_fmd5">
<input type="text" name="x_fmd5" id="x_fmd5" size="30" maxlength="245" value="<?php echo $entries->fmd5->EditValue ?>"<?php echo $entries->fmd5->EditAttributes() ?> />
</span><?php echo $entries->fmd5->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($entries->tool_id->Visible) { // tool_id ?>
	<tr id="r_tool_id"<?php echo $entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_entries_tool_id">
		<b><?php echo $entries->tool_id->FldCaption() ?></b>
		</span></td>
		<td<?php echo $entries->tool_id->CellAttributes() ?>><span id="el_entries_tool_id">
<input type="text" name="x_tool_id" id="x_tool_id" size="30" maxlength="235" value="<?php echo $entries->tool_id->EditValue ?>"<?php echo $entries->tool_id->EditAttributes() ?> />
</span><?php echo $entries->tool_id->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</div>
<input type="submit"  class="btn btn-large btn-success"  name="btnAction" id="btnAction" value="<?php echo ew_BtnCaption($Language->Phrase("AddBtn")) ?>" />
</form>
<script type="text/javascript">
fentriesadd.Init();
</script>
<?php
$entries_add->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$entries_add->Page_Terminate();
?>
