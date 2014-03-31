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

$entries_view = NULL; // Initialize page object first

class centries_view extends centries {

	// Page ID
	var $PageID = 'view';

	// Project ID
	var $ProjectID = "{3554BCCA-7E88-4E52-9661-DF55D75275C9}";

	// Table name
	var $TableName = 'entries';

	// Page object name
	var $PageObjName = 'entries_view';

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
		$KeyUrl = "";
		if (@$_GET["identries"] <> "") {
			$this->RecKey["identries"] = $_GET["identries"];
			$KeyUrl .= "&identries=" . urlencode($this->RecKey["identries"]);
		}
		if (@$_GET["id"] <> "") {
			$this->RecKey["id"] = $_GET["id"];
			$KeyUrl .= "&id=" . urlencode($this->RecKey["id"]);
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

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'view', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'entries', TRUE);

		// Start timer
		if (!isset($GLOBALS["gTimer"])) $GLOBALS["gTimer"] = new cTimer();

		// Open connection
		if (!isset($conn)) $conn = ew_Connect();

		// Export options
		$this->ExportOptions = new cListOptions();
		$this->ExportOptions->Tag = "li";//jamesjara
		$this->ExportOptions->TagClassName = "ewExportOptionIgnore";
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
		if (!$Security->CanView()) {
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
			if (@$_GET["identries"] <> "") {
				$this->identries->setQueryStringValue($_GET["identries"]);
				$this->RecKey["identries"] = $this->identries->QueryStringValue;
			} else {
				$bLoadCurrentRecord = TRUE;
			}
			if (@$_GET["id"] <> "") {
				$this->id->setQueryStringValue($_GET["id"]);
				$this->RecKey["id"] = $this->id->QueryStringValue;
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
						$this->Page_Terminate($this->james_url( "entrieslist.php" )); // Return to list page
					} elseif ($bLoadCurrentRecord) { // Load current record position
						$this->SetUpStartRec(); // Set up start record position

						// Point to current record
						if (intval($this->StartRec) <= intval($this->TotalRecs)) {
							$bMatchRecord = TRUE;
							$this->Recordset->Move($this->StartRec-1);
						}
					} else { // Match key values
						while (!$this->Recordset->EOF) {
							if (strval($this->identries->CurrentValue) == strval($this->Recordset->fields('identries')) && strval($this->id->CurrentValue) == strval($this->Recordset->fields('id'))) {
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
						$sReturnUrl = $this->james_url( "entrieslist.php" ); // No matching record, return to list
					} else {
						$this->LoadRowValues($this->Recordset); // Load row values
					}
			}
		} else {
			$sReturnUrl = $this->james_url( "entrieslist.php" ); // Not page request, return to list
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
		$this->AddUrl = $this->GetAddUrl();
		$this->EditUrl = $this->GetEditUrl();
		$this->CopyUrl = $this->GetCopyUrl();
		$this->DeleteUrl = $this->GetDeleteUrl();
		$this->ListUrl = $this->GetListUrl();

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

			// identries
			$this->identries->LinkCustomAttributes = "";
			$this->identries->HrefValue = "";
			$this->identries->TooltipValue = "";

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
if (!isset($entries_view)) $entries_view = new centries_view();

// Page init
$entries_view->Page_Init();

// Page main
$entries_view->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var entries_view = new ew_Page("entries_view");
entries_view.PageID = "view"; // Page ID
var EW_PAGE_ID = entries_view.PageID; // For backward compatibility

// Form object
var fentriesview = new ew_Form("fentriesview");

// Form_CustomValidate event
fentriesview.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fentriesview.ValidateRequired = true;
<?php } else { ?>
fentriesview.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
fentriesview.Lists["x_domain_id"] = {"LinkField":"x_id_domains","Ajax":true,"AutoFill":false,"DisplayFields":["x_dominio","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<h4 id="ewPageCaption" class="page-header ewTitle ewTableTitle"> <?php echo $Language->Phrase("View") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $entries->TableCaption() ?>&nbsp;&nbsp;</h4>
<a href="<?php echo $entries_view->ListUrl ?>" id="a_BackToList" class="ewLink label"><i class="icon-arrow-left icon-white"></i> <?php echo $Language->Phrase("BackToList") ?></a>
<?php //jamesjara
if(!ISSET($_GET['export']))echo '<div class="btn-group"><button class="btn btn-info dropdown-toggle" data-toggle="dropdown"><i class="icon-share icon-white"></i> Exportar<span class="caret"></span></button><ul class="dropdown-menu">';
$entries_view->ExportOptions->Render("body"); 
if(!ISSET($_GET['export']))echo '</ul></div> ';
?>
<?php if ($Security->CanAdd()) { ?>
<?php if ($entries_view->AddUrl <> "") { ?>
<a href="<?php echo $entries_view->AddUrl ?>" id="a_AddLink" class="ewLink ewGridLink btn btn-success"><?php echo $Language->Phrase("ViewPageAddLink") ?></a>
<?php } ?>
<?php } ?>
<?php if ($Security->CanEdit()) { ?>
<?php if ($entries_view->EditUrl <> "") { ?>
<a href="<?php echo $entries_view->EditUrl ?>" id="a_EditLink" class="ewLink btn btn-primary"><?php echo $Language->Phrase("ViewPageEditLink") ?></a>
<?php } ?>
<?php } ?>
<?php if ($Security->CanAdd()) { ?>
<?php if ($entries_view->CopyUrl <> "") { ?>
<a href="<?php echo $entries_view->CopyUrl ?>" id="a_CopyLink" class="ewLink"><?php echo $Language->Phrase("ViewPageCopyLink") ?></a>
<?php } ?>
<?php } ?>
<?php if ($Security->CanDelete()) { ?>
<?php if ($entries_view->DeleteUrl <> "") { ?>
<a href="<?php echo $entries_view->DeleteUrl ?>" id="a_DeleteLink" class="ewLink  btn btn-danger"><?php echo $Language->Phrase("ViewPageDeleteLink") ?></a>
<?php } ?>
<?php } ?>
<?php $entries_view->ShowPageHeader(); ?>
<?php
$entries_view->ShowMessage();
?>
<div>
<form name="ewpagerform" id="ewpagerform" class="ewForm" action="<?php echo ew_CurrentPage() ?>">
<div id="paginador" class="pagination pull-right">
<?php if (!isset($entries_view->Pager)) $entries_view->Pager = new cNumericPager($entries_view->StartRec, $entries_view->DisplayRecs, $entries_view->TotalRecs, $entries_view->RecRange) ?>
<?php if ($entries_view->Pager->RecordCount > 0) { ?>
	<ul><?php if ($entries_view->Pager->FirstButton->Enabled) { ?>
	<li><a href="<?php echo $entries_view->PageUrl() ?>start=<?php echo $entries_view->Pager->FirstButton->Start ?>"><b><?php echo $Language->Phrase("PagerFirst") ?></b></a></li>
	<?php } ?>
	<?php if ($entries_view->Pager->PrevButton->Enabled) { ?>
	<li><a href="<?php echo $entries_view->PageUrl() ?>start=<?php echo $entries_view->Pager->PrevButton->Start ?>"><b><?php echo $Language->Phrase("PagerPrevious") ?></b></a></li>
	<?php } ?>
	<?php foreach ($entries_view->Pager->Items as $PagerItem) { //jamesjara ?>
		<?php $classs=""; if (!$PagerItem->Enabled) $classs = 'class="active"';

		//jamesjara if ($PagerItem->Enabled) { ?>
			<li <?php echo $classs; ?>><a href="<?php echo $entries_view->PageUrl() ?>start=<?php echo $PagerItem->Start ?>">
		<?php //jamesjara } ?>
			<b><?php echo $PagerItem->Text ?></b>
		<?php //jamesjara if ($PagerItem->Enabled) { ?> 
			</a></li><?php //jamesjara } 
		?>
	<?php } ?>
	<?php if ($entries_view->Pager->NextButton->Enabled) { ?>
	<li><a href="<?php echo $entries_view->PageUrl() ?>start=<?php echo $entries_view->Pager->NextButton->Start ?>"><b><?php echo $Language->Phrase("PagerNext") ?></b></a></li>
	<?php } ?>
	<?php if ($entries_view->Pager->LastButton->Enabled) { ?>
	<li><a href="<?php echo $entries_view->PageUrl() ?>start=<?php echo $entries_view->Pager->LastButton->Start ?>"><b><?php echo $Language->Phrase("PagerLast") ?></b></a></li>
	<?php } ?>
	</ul>
<?php } else { ?>	
	<?php if ($Security->CanList()) { ?>
	<?php if ($entries_view->SearchWhere == "0=101") { ?>
	<?php echo $Language->Phrase("EnterSearchCriteria") ?>
	<?php } else { ?>
	<?php echo $Language->Phrase("NoRecord") ?>
	<?php } ?>
	<?php } else { ?>
	<?php echo $Language->Phrase("NoPermission") ?>
	<?php } ?>
<?php } ?>
</div>
</form>
<div>
<form name="fentriesview" id="fentriesview" class="ewForm" action="" method="post">
<input type="hidden" name="t" value="entries" />
<table id="tbl_entriesview" class="ewTable ewTableSeparate table table-striped ">
<?php if ($entries->identries->Visible) { // identries ?>
	<tr id="r_identries"<?php echo $entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_entries_identries"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $entries->identries->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $entries->identries->CellAttributes() ?>><span id="el_entries_identries">
<div id="orig_entries_identries" class="ewDisplayNone">
<span<?php echo $entries->identries->ViewAttributes() ?>>
<?php echo $entries->identries->ViewValue ?></span>
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
	</tr>
<?php } ?>
<?php if ($entries->domain_id->Visible) { // domain_id ?>
	<tr id="r_domain_id"<?php echo $entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_entries_domain_id"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $entries->domain_id->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $entries->domain_id->CellAttributes() ?>><span id="el_entries_domain_id">
<span<?php echo $entries->domain_id->ViewAttributes() ?>>
<?php echo $entries->domain_id->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($entries->hash_content->Visible) { // hash_content ?>
	<tr id="r_hash_content"<?php echo $entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_entries_hash_content"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $entries->hash_content->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $entries->hash_content->CellAttributes() ?>><span id="el_entries_hash_content">
<span<?php echo $entries->hash_content->ViewAttributes() ?>>
<?php echo $entries->hash_content->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($entries->fuente->Visible) { // fuente ?>
	<tr id="r_fuente"<?php echo $entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_entries_fuente"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $entries->fuente->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $entries->fuente->CellAttributes() ?>><span id="el_entries_fuente">
<span<?php echo $entries->fuente->ViewAttributes() ?>>
<?php echo $entries->fuente->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($entries->published->Visible) { // published ?>
	<tr id="r_published"<?php echo $entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_entries_published"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $entries->published->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $entries->published->CellAttributes() ?>><span id="el_entries_published">
<span<?php echo $entries->published->ViewAttributes() ?>>
<?php echo $entries->published->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($entries->updated->Visible) { // updated ?>
	<tr id="r_updated"<?php echo $entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_entries_updated"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $entries->updated->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $entries->updated->CellAttributes() ?>><span id="el_entries_updated">
<span<?php echo $entries->updated->ViewAttributes() ?>>
<?php echo $entries->updated->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($entries->categorias->Visible) { // categorias ?>
	<tr id="r_categorias"<?php echo $entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_entries_categorias"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $entries->categorias->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $entries->categorias->CellAttributes() ?>><span id="el_entries_categorias">
<span<?php echo $entries->categorias->ViewAttributes() ?>>
<?php echo $entries->categorias->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($entries->titulo->Visible) { // titulo ?>
	<tr id="r_titulo"<?php echo $entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_entries_titulo"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $entries->titulo->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $entries->titulo->CellAttributes() ?>><span id="el_entries_titulo">
<span<?php echo $entries->titulo->ViewAttributes() ?>>
<?php echo $entries->titulo->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($entries->contenido->Visible) { // contenido ?>
	<tr id="r_contenido"<?php echo $entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_entries_contenido"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $entries->contenido->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $entries->contenido->CellAttributes() ?>><span id="el_entries_contenido">
<span<?php echo $entries->contenido->ViewAttributes() ?>>
<?php echo $entries->contenido->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($entries->id->Visible) { // id ?>
	<tr id="r_id"<?php echo $entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_entries_id"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $entries->id->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $entries->id->CellAttributes() ?>><span id="el_entries_id">
<span<?php echo $entries->id->ViewAttributes() ?>>
<?php echo $entries->id->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($entries->islive->Visible) { // islive ?>
	<tr id="r_islive"<?php echo $entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_entries_islive"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $entries->islive->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $entries->islive->CellAttributes() ?>><span id="el_entries_islive">
<span<?php echo $entries->islive->ViewAttributes() ?>>
<?php echo $entries->islive->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($entries->thumbnail->Visible) { // thumbnail ?>
	<tr id="r_thumbnail"<?php echo $entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_entries_thumbnail"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $entries->thumbnail->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $entries->thumbnail->CellAttributes() ?>><span id="el_entries_thumbnail">
<span<?php echo $entries->thumbnail->ViewAttributes() ?>>
<?php echo $entries->thumbnail->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($entries->reqdate->Visible) { // reqdate ?>
	<tr id="r_reqdate"<?php echo $entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_entries_reqdate"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $entries->reqdate->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $entries->reqdate->CellAttributes() ?>><span id="el_entries_reqdate">
<span<?php echo $entries->reqdate->ViewAttributes() ?>>
<?php echo $entries->reqdate->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($entries->author->Visible) { // author ?>
	<tr id="r_author"<?php echo $entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_entries_author"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $entries->author->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $entries->author->CellAttributes() ?>><span id="el_entries_author">
<span<?php echo $entries->author->ViewAttributes() ?>>
<?php echo $entries->author->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($entries->trans_en->Visible) { // trans_en ?>
	<tr id="r_trans_en"<?php echo $entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_entries_trans_en"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $entries->trans_en->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $entries->trans_en->CellAttributes() ?>><span id="el_entries_trans_en">
<span<?php echo $entries->trans_en->ViewAttributes() ?>>
<?php echo $entries->trans_en->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($entries->trans_es->Visible) { // trans_es ?>
	<tr id="r_trans_es"<?php echo $entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_entries_trans_es"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $entries->trans_es->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $entries->trans_es->CellAttributes() ?>><span id="el_entries_trans_es">
<span<?php echo $entries->trans_es->ViewAttributes() ?>>
<?php echo $entries->trans_es->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($entries->trans_fr->Visible) { // trans_fr ?>
	<tr id="r_trans_fr"<?php echo $entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_entries_trans_fr"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $entries->trans_fr->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $entries->trans_fr->CellAttributes() ?>><span id="el_entries_trans_fr">
<span<?php echo $entries->trans_fr->ViewAttributes() ?>>
<?php echo $entries->trans_fr->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($entries->trans_it->Visible) { // trans_it ?>
	<tr id="r_trans_it"<?php echo $entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_entries_trans_it"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $entries->trans_it->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $entries->trans_it->CellAttributes() ?>><span id="el_entries_trans_it">
<span<?php echo $entries->trans_it->ViewAttributes() ?>>
<?php echo $entries->trans_it->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($entries->fid->Visible) { // fid ?>
	<tr id="r_fid"<?php echo $entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_entries_fid"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $entries->fid->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $entries->fid->CellAttributes() ?>><span id="el_entries_fid">
<span<?php echo $entries->fid->ViewAttributes() ?>>
<?php echo $entries->fid->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($entries->fmd5->Visible) { // fmd5 ?>
	<tr id="r_fmd5"<?php echo $entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_entries_fmd5"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $entries->fmd5->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $entries->fmd5->CellAttributes() ?>><span id="el_entries_fmd5">
<span<?php echo $entries->fmd5->ViewAttributes() ?>>
<?php echo $entries->fmd5->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
<?php if ($entries->tool_id->Visible) { // tool_id ?>
	<tr id="r_tool_id"<?php echo $entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_entries_tool_id"><table class="ewTableHeaderBtn"><tr><td><b><?php echo $entries->tool_id->FldCaption() ?></b></td></tr></table></span></td>
		<td<?php echo $entries->tool_id->CellAttributes() ?>><span id="el_entries_tool_id">
<span<?php echo $entries->tool_id->ViewAttributes() ?>>
<?php echo $entries->tool_id->ViewValue ?></span>
</span></td>
	</tr>
<?php } ?>
</table>
</form>
<script type="text/javascript">
fentriesview.Init();
</script>
<?php
$entries_view->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$entries_view->Page_Terminate();
?>
