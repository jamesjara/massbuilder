<?php
if (session_id() == "") session_start(); // Initialize Session data
ob_start(); // Turn on output buffering
?>
<?php include_once "ewcfg9.php" ?>
<?php include_once "ewmysql9.php" ?>
<?php include_once "phpfn9.php" ?>
<?php include_once "fb_group_entriesinfo.php" ?>
<?php include_once "usersinfo.php" ?>
<?php include_once "userfn9.php" ?>
<?php

//
// Page class
//

$fb_group_entries_delete = NULL; // Initialize page object first

class cfb_group_entries_delete extends cfb_group_entries {

	// Page ID
	var $PageID = 'delete';

	// Project ID
	var $ProjectID = "{3554BCCA-7E88-4E52-9661-DF55D75275C9}";

	// Table name
	var $TableName = 'fb_group_entries';

	// Page object name
	var $PageObjName = 'fb_group_entries_delete';

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

		// Table object (fb_group_entries)
		if (!isset($GLOBALS["fb_group_entries"])) {
			$GLOBALS["fb_group_entries"] = &$this;
			$GLOBALS["Table"] = &$GLOBALS["fb_group_entries"];
		}

		// Table object (users)
		if (!isset($GLOBALS['users'])) $GLOBALS['users'] = new cusers();

		// Page ID
		if (!defined("EW_PAGE_ID"))
			define("EW_PAGE_ID", 'delete', TRUE);

		// Table name (for backward compatibility)
		if (!defined("EW_TABLE_NAME"))
			define("EW_TABLE_NAME", 'fb_group_entries', TRUE);

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
			$this->Page_Terminate("fb_group_entrieslist.php");
		}
		$Security->UserID_Loading();
		if ($Security->IsLoggedIn()) $Security->LoadUserID();
		$Security->UserID_Loaded();
		$this->CurrentAction = (@$_GET["a"] <> "") ? $_GET["a"] : @$_POST["a_list"];
		$this->idfb_posts->Visible = !$this->IsAdd() && !$this->IsCopy() && !$this->IsGridAdd();

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
			$this->Page_Terminate($this->james_url( "fb_group_entrieslist.php" )); // Prevent SQL injection, return to list

		// Set up filter (SQL WHHERE clause) and get return SQL
		// SQL constructor in fb_group_entries class, fb_group_entriesinfo.php

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
		$this->idfb_posts->setDbValue($rs->fields('idfb_posts'));
		$this->domain_id->setDbValue($rs->fields('domain_id'));
		$this->record_time->setDbValue($rs->fields('record_time'));
		$this->data->setDbValue($rs->fields('data'));
		$this->fid->setDbValue($rs->fields('fid'));
		$this->md5->setDbValue($rs->fields('md5'));
		$this->body->setDbValue($rs->fields('body'));
		$this->titulo->setDbValue($rs->fields('titulo'));
		$this->created_time->setDbValue($rs->fields('created_time'));
		$this->actions->setDbValue($rs->fields('actions'));
		$this->id->setDbValue($rs->fields('id'));
		$this->is_published->setDbValue($rs->fields('is_published'));
		$this->message->setDbValue($rs->fields('message'));
		$this->privacy->setDbValue($rs->fields('privacy'));
		$this->promotion_status->setDbValue($rs->fields('promotion_status'));
		$this->timeline_visibility->setDbValue($rs->fields('timeline_visibility'));
		$this->to->setDbValue($rs->fields('to'));
		$this->type->setDbValue($rs->fields('type'));
		$this->updated_time->setDbValue($rs->fields('updated_time'));
		$this->from->setDbValue($rs->fields('from'));
		$this->comments->setDbValue($rs->fields('comments'));
		$this->id_grupo->setDbValue($rs->fields('id_grupo'));
		$this->icon->setDbValue($rs->fields('icon'));
		$this->link->setDbValue($rs->fields('link'));
		$this->name->setDbValue($rs->fields('name'));
		$this->object_id->setDbValue($rs->fields('object_id'));
		$this->picture->setDbValue($rs->fields('picture'));
		$this->properties->setDbValue($rs->fields('properties'));
		$this->message_tags->setDbValue($rs->fields('message_tags'));
		$this->caption->setDbValue($rs->fields('caption'));
		$this->description->setDbValue($rs->fields('description'));
		$this->tool_id->setDbValue($rs->fields('tool_id'));
		$this->application->setDbValue($rs->fields('application'));
	}

	// Render row values based on field settings
	function RenderRow() {
		global $conn, $Security, $Language;
		global $gsLanguage;

		// Initialize URLs
		// Call Row_Rendering event

		$this->Row_Rendering();

		// Common render codes for all row types
		// idfb_posts
		// domain_id
		// record_time
		// data
		// fid
		// md5
		// body
		// titulo
		// created_time
		// actions
		// id
		// is_published
		// message
		// privacy
		// promotion_status
		// timeline_visibility
		// to
		// type
		// updated_time
		// from
		// comments
		// id_grupo
		// icon
		// link
		// name
		// object_id
		// picture
		// properties
		// message_tags
		// caption
		// description
		// tool_id
		// application

		if ($this->RowType == EW_ROWTYPE_VIEW) { // View row

			// idfb_posts
			$this->idfb_posts->ViewValue = $this->idfb_posts->CurrentValue;
			$this->idfb_posts->ViewCustomAttributes = "";

			// domain_id
			$this->domain_id->ViewValue = $this->domain_id->CurrentValue;
			$this->domain_id->ViewCustomAttributes = "";

			// record_time
			$this->record_time->ViewValue = $this->record_time->CurrentValue;
			$this->record_time->ViewValue = ew_FormatDateTime($this->record_time->ViewValue, 5);
			$this->record_time->ViewCustomAttributes = "";

			// data
			$this->data->ViewValue = $this->data->CurrentValue;
			$this->data->ViewCustomAttributes = "";

			// fid
			$this->fid->ViewValue = $this->fid->CurrentValue;
			$this->fid->ViewCustomAttributes = "";

			// md5
			$this->md5->ViewValue = $this->md5->CurrentValue;
			$this->md5->ViewCustomAttributes = "";

			// body
			$this->body->ViewValue = $this->body->CurrentValue;
			$this->body->ViewCustomAttributes = "";

			// titulo
			$this->titulo->ViewValue = $this->titulo->CurrentValue;
			$this->titulo->ViewCustomAttributes = "";

			// created_time
			$this->created_time->ViewValue = $this->created_time->CurrentValue;
			$this->created_time->ViewCustomAttributes = "";

			// actions
			$this->actions->ViewValue = $this->actions->CurrentValue;
			$this->actions->ViewCustomAttributes = "";

			// id
			$this->id->ViewValue = $this->id->CurrentValue;
			$this->id->ViewCustomAttributes = "";

			// is_published
			$this->is_published->ViewValue = $this->is_published->CurrentValue;
			$this->is_published->ViewCustomAttributes = "";

			// message
			$this->message->ViewValue = $this->message->CurrentValue;
			$this->message->ViewCustomAttributes = "";

			// privacy
			$this->privacy->ViewValue = $this->privacy->CurrentValue;
			$this->privacy->ViewCustomAttributes = "";

			// promotion_status
			$this->promotion_status->ViewValue = $this->promotion_status->CurrentValue;
			$this->promotion_status->ViewCustomAttributes = "";

			// timeline_visibility
			$this->timeline_visibility->ViewValue = $this->timeline_visibility->CurrentValue;
			$this->timeline_visibility->ViewCustomAttributes = "";

			// to
			$this->to->ViewValue = $this->to->CurrentValue;
			$this->to->ViewCustomAttributes = "";

			// type
			$this->type->ViewValue = $this->type->CurrentValue;
			$this->type->ViewCustomAttributes = "";

			// updated_time
			$this->updated_time->ViewValue = $this->updated_time->CurrentValue;
			$this->updated_time->ViewCustomAttributes = "";

			// from
			$this->from->ViewValue = $this->from->CurrentValue;
			$this->from->ViewCustomAttributes = "";

			// comments
			$this->comments->ViewValue = $this->comments->CurrentValue;
			$this->comments->ViewCustomAttributes = "";

			// id_grupo
			$this->id_grupo->ViewValue = $this->id_grupo->CurrentValue;
			$this->id_grupo->ViewCustomAttributes = "";

			// icon
			$this->icon->ViewValue = $this->icon->CurrentValue;
			$this->icon->ViewCustomAttributes = "";

			// link
			$this->link->ViewValue = $this->link->CurrentValue;
			$this->link->ViewCustomAttributes = "";

			// name
			$this->name->ViewValue = $this->name->CurrentValue;
			$this->name->ViewCustomAttributes = "";

			// object_id
			$this->object_id->ViewValue = $this->object_id->CurrentValue;
			$this->object_id->ViewCustomAttributes = "";

			// picture
			$this->picture->ViewValue = $this->picture->CurrentValue;
			$this->picture->ViewCustomAttributes = "";

			// properties
			$this->properties->ViewValue = $this->properties->CurrentValue;
			$this->properties->ViewCustomAttributes = "";

			// message_tags
			$this->message_tags->ViewValue = $this->message_tags->CurrentValue;
			$this->message_tags->ViewCustomAttributes = "";

			// caption
			$this->caption->ViewValue = $this->caption->CurrentValue;
			$this->caption->ViewCustomAttributes = "";

			// description
			$this->description->ViewValue = $this->description->CurrentValue;
			$this->description->ViewCustomAttributes = "";

			// tool_id
			$this->tool_id->ViewValue = $this->tool_id->CurrentValue;
			$this->tool_id->ViewCustomAttributes = "";

			// application
			$this->application->ViewValue = $this->application->CurrentValue;
			$this->application->ViewCustomAttributes = "";

			// idfb_posts
			$this->idfb_posts->LinkCustomAttributes = "";
			$this->idfb_posts->HrefValue = "";
			$this->idfb_posts->TooltipValue = "";

			// domain_id
			$this->domain_id->LinkCustomAttributes = "";
			$this->domain_id->HrefValue = "";
			$this->domain_id->TooltipValue = "";

			// record_time
			$this->record_time->LinkCustomAttributes = "";
			$this->record_time->HrefValue = "";
			$this->record_time->TooltipValue = "";

			// data
			$this->data->LinkCustomAttributes = "";
			$this->data->HrefValue = "";
			$this->data->TooltipValue = "";

			// fid
			$this->fid->LinkCustomAttributes = "";
			$this->fid->HrefValue = "";
			$this->fid->TooltipValue = "";

			// md5
			$this->md5->LinkCustomAttributes = "";
			$this->md5->HrefValue = "";
			$this->md5->TooltipValue = "";

			// body
			$this->body->LinkCustomAttributes = "";
			$this->body->HrefValue = "";
			$this->body->TooltipValue = "";

			// titulo
			$this->titulo->LinkCustomAttributes = "";
			$this->titulo->HrefValue = "";
			$this->titulo->TooltipValue = "";

			// created_time
			$this->created_time->LinkCustomAttributes = "";
			$this->created_time->HrefValue = "";
			$this->created_time->TooltipValue = "";

			// actions
			$this->actions->LinkCustomAttributes = "";
			$this->actions->HrefValue = "";
			$this->actions->TooltipValue = "";

			// id
			$this->id->LinkCustomAttributes = "";
			$this->id->HrefValue = "";
			$this->id->TooltipValue = "";

			// is_published
			$this->is_published->LinkCustomAttributes = "";
			$this->is_published->HrefValue = "";
			$this->is_published->TooltipValue = "";

			// message
			$this->message->LinkCustomAttributes = "";
			$this->message->HrefValue = "";
			$this->message->TooltipValue = "";

			// privacy
			$this->privacy->LinkCustomAttributes = "";
			$this->privacy->HrefValue = "";
			$this->privacy->TooltipValue = "";

			// promotion_status
			$this->promotion_status->LinkCustomAttributes = "";
			$this->promotion_status->HrefValue = "";
			$this->promotion_status->TooltipValue = "";

			// timeline_visibility
			$this->timeline_visibility->LinkCustomAttributes = "";
			$this->timeline_visibility->HrefValue = "";
			$this->timeline_visibility->TooltipValue = "";

			// to
			$this->to->LinkCustomAttributes = "";
			$this->to->HrefValue = "";
			$this->to->TooltipValue = "";

			// type
			$this->type->LinkCustomAttributes = "";
			$this->type->HrefValue = "";
			$this->type->TooltipValue = "";

			// updated_time
			$this->updated_time->LinkCustomAttributes = "";
			$this->updated_time->HrefValue = "";
			$this->updated_time->TooltipValue = "";

			// from
			$this->from->LinkCustomAttributes = "";
			$this->from->HrefValue = "";
			$this->from->TooltipValue = "";

			// comments
			$this->comments->LinkCustomAttributes = "";
			$this->comments->HrefValue = "";
			$this->comments->TooltipValue = "";

			// id_grupo
			$this->id_grupo->LinkCustomAttributes = "";
			$this->id_grupo->HrefValue = "";
			$this->id_grupo->TooltipValue = "";

			// icon
			$this->icon->LinkCustomAttributes = "";
			$this->icon->HrefValue = "";
			$this->icon->TooltipValue = "";

			// link
			$this->link->LinkCustomAttributes = "";
			$this->link->HrefValue = "";
			$this->link->TooltipValue = "";

			// name
			$this->name->LinkCustomAttributes = "";
			$this->name->HrefValue = "";
			$this->name->TooltipValue = "";

			// object_id
			$this->object_id->LinkCustomAttributes = "";
			$this->object_id->HrefValue = "";
			$this->object_id->TooltipValue = "";

			// picture
			$this->picture->LinkCustomAttributes = "";
			$this->picture->HrefValue = "";
			$this->picture->TooltipValue = "";

			// properties
			$this->properties->LinkCustomAttributes = "";
			$this->properties->HrefValue = "";
			$this->properties->TooltipValue = "";

			// message_tags
			$this->message_tags->LinkCustomAttributes = "";
			$this->message_tags->HrefValue = "";
			$this->message_tags->TooltipValue = "";

			// caption
			$this->caption->LinkCustomAttributes = "";
			$this->caption->HrefValue = "";
			$this->caption->TooltipValue = "";

			// description
			$this->description->LinkCustomAttributes = "";
			$this->description->HrefValue = "";
			$this->description->TooltipValue = "";

			// tool_id
			$this->tool_id->LinkCustomAttributes = "";
			$this->tool_id->HrefValue = "";
			$this->tool_id->TooltipValue = "";

			// application
			$this->application->LinkCustomAttributes = "";
			$this->application->HrefValue = "";
			$this->application->TooltipValue = "";
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
				$sThisKey .= $row['idfb_posts'];
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
if (!isset($fb_group_entries_delete)) $fb_group_entries_delete = new cfb_group_entries_delete();

// Page init
$fb_group_entries_delete->Page_Init();

// Page main
$fb_group_entries_delete->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var fb_group_entries_delete = new ew_Page("fb_group_entries_delete");
fb_group_entries_delete.PageID = "delete"; // Page ID
var EW_PAGE_ID = fb_group_entries_delete.PageID; // For backward compatibility

// Form object
var ffb_group_entriesdelete = new ew_Form("ffb_group_entriesdelete");

// Form_CustomValidate event
ffb_group_entriesdelete.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ffb_group_entriesdelete.ValidateRequired = true;
<?php } else { ?>
ffb_group_entriesdelete.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<?php

// Load records for display
if ($fb_group_entries_delete->Recordset = $fb_group_entries_delete->LoadRecordset())
	$fb_group_entries_deleteTotalRecs = $fb_group_entries_delete->Recordset->RecordCount(); // Get record count
if ($fb_group_entries_deleteTotalRecs <= 0) { // No record found, exit
	if ($fb_group_entries_delete->Recordset)
		$fb_group_entries_delete->Recordset->Close();
	$fb_group_entries_delete->Page_Terminate($this->james_url( "fb_group_entrieslist.php" )); // Return to list
}
?>
<h4 id="ewPageCaption" class="page-header ewTitle ewTableTitle"> <?php echo $Language->Phrase("Delete") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $fb_group_entries->TableCaption() ?></h4>
<a href="<?php echo $fb_group_entries->getReturnUrl() ?>" id="a_GoBack" class="ewLink label"><i class="icon-arrow-left icon-white"></i><?php echo $Language->Phrase("GoBack") ?></a>
<?php $fb_group_entries_delete->ShowPageHeader(); ?>
<?php
$fb_group_entries_delete->ShowMessage();
?>
<form name="ffb_group_entriesdelete" id="ffb_group_entriesdelete" class="ewForm" action="<?php echo ew_CurrentPage() ?>" method="post">
<input type="hidden" name="t" value="fb_group_entries" />
<input type="hidden" name="a_delete" id="a_delete" value="D" />
<?php foreach ($fb_group_entries_delete->RecKeys as $key) { ?>
<?php $keyvalue = is_array($key) ? implode($EW_COMPOSITE_KEY_SEPARATOR, $key) : $key; ?>
<input type="hidden" name="key_m[]" value="<?php echo ew_HtmlEncode($keyvalue) ?>" />
<?php } ?>
<br />
<table id="tbl_fb_group_entriesdelete" class="ewTable ewTableSeparate table table-striped table-bordered ">
<?php echo $fb_group_entries->TableCustomInnerHtml ?>
	<thead>
	<tr class="ewTableHeader">
		<th><span id="elh_fb_group_entries_idfb_posts" class="fb_group_entries_idfb_posts">
		<?php echo $fb_group_entries->idfb_posts->FldCaption() ?></span></th>
		<th><span id="elh_fb_group_entries_domain_id" class="fb_group_entries_domain_id">
		<?php echo $fb_group_entries->domain_id->FldCaption() ?></span></th>
		<th><span id="elh_fb_group_entries_record_time" class="fb_group_entries_record_time">
		<?php echo $fb_group_entries->record_time->FldCaption() ?></span></th>
		<th><span id="elh_fb_group_entries_data" class="fb_group_entries_data">
		<?php echo $fb_group_entries->data->FldCaption() ?></span></th>
		<th><span id="elh_fb_group_entries_fid" class="fb_group_entries_fid">
		<?php echo $fb_group_entries->fid->FldCaption() ?></span></th>
		<th><span id="elh_fb_group_entries_md5" class="fb_group_entries_md5">
		<?php echo $fb_group_entries->md5->FldCaption() ?></span></th>
		<th><span id="elh_fb_group_entries_body" class="fb_group_entries_body">
		<?php echo $fb_group_entries->body->FldCaption() ?></span></th>
		<th><span id="elh_fb_group_entries_titulo" class="fb_group_entries_titulo">
		<?php echo $fb_group_entries->titulo->FldCaption() ?></span></th>
		<th><span id="elh_fb_group_entries_created_time" class="fb_group_entries_created_time">
		<?php echo $fb_group_entries->created_time->FldCaption() ?></span></th>
		<th><span id="elh_fb_group_entries_actions" class="fb_group_entries_actions">
		<?php echo $fb_group_entries->actions->FldCaption() ?></span></th>
		<th><span id="elh_fb_group_entries_id" class="fb_group_entries_id">
		<?php echo $fb_group_entries->id->FldCaption() ?></span></th>
		<th><span id="elh_fb_group_entries_is_published" class="fb_group_entries_is_published">
		<?php echo $fb_group_entries->is_published->FldCaption() ?></span></th>
		<th><span id="elh_fb_group_entries_message" class="fb_group_entries_message">
		<?php echo $fb_group_entries->message->FldCaption() ?></span></th>
		<th><span id="elh_fb_group_entries_privacy" class="fb_group_entries_privacy">
		<?php echo $fb_group_entries->privacy->FldCaption() ?></span></th>
		<th><span id="elh_fb_group_entries_promotion_status" class="fb_group_entries_promotion_status">
		<?php echo $fb_group_entries->promotion_status->FldCaption() ?></span></th>
		<th><span id="elh_fb_group_entries_timeline_visibility" class="fb_group_entries_timeline_visibility">
		<?php echo $fb_group_entries->timeline_visibility->FldCaption() ?></span></th>
		<th><span id="elh_fb_group_entries_to" class="fb_group_entries_to">
		<?php echo $fb_group_entries->to->FldCaption() ?></span></th>
		<th><span id="elh_fb_group_entries_type" class="fb_group_entries_type">
		<?php echo $fb_group_entries->type->FldCaption() ?></span></th>
		<th><span id="elh_fb_group_entries_updated_time" class="fb_group_entries_updated_time">
		<?php echo $fb_group_entries->updated_time->FldCaption() ?></span></th>
		<th><span id="elh_fb_group_entries_from" class="fb_group_entries_from">
		<?php echo $fb_group_entries->from->FldCaption() ?></span></th>
		<th><span id="elh_fb_group_entries_comments" class="fb_group_entries_comments">
		<?php echo $fb_group_entries->comments->FldCaption() ?></span></th>
		<th><span id="elh_fb_group_entries_id_grupo" class="fb_group_entries_id_grupo">
		<?php echo $fb_group_entries->id_grupo->FldCaption() ?></span></th>
		<th><span id="elh_fb_group_entries_icon" class="fb_group_entries_icon">
		<?php echo $fb_group_entries->icon->FldCaption() ?></span></th>
		<th><span id="elh_fb_group_entries_link" class="fb_group_entries_link">
		<?php echo $fb_group_entries->link->FldCaption() ?></span></th>
		<th><span id="elh_fb_group_entries_name" class="fb_group_entries_name">
		<?php echo $fb_group_entries->name->FldCaption() ?></span></th>
		<th><span id="elh_fb_group_entries_object_id" class="fb_group_entries_object_id">
		<?php echo $fb_group_entries->object_id->FldCaption() ?></span></th>
		<th><span id="elh_fb_group_entries_picture" class="fb_group_entries_picture">
		<?php echo $fb_group_entries->picture->FldCaption() ?></span></th>
		<th><span id="elh_fb_group_entries_properties" class="fb_group_entries_properties">
		<?php echo $fb_group_entries->properties->FldCaption() ?></span></th>
		<th><span id="elh_fb_group_entries_message_tags" class="fb_group_entries_message_tags">
		<?php echo $fb_group_entries->message_tags->FldCaption() ?></span></th>
		<th><span id="elh_fb_group_entries_caption" class="fb_group_entries_caption">
		<?php echo $fb_group_entries->caption->FldCaption() ?></span></th>
		<th><span id="elh_fb_group_entries_description" class="fb_group_entries_description">
		<?php echo $fb_group_entries->description->FldCaption() ?></span></th>
		<th><span id="elh_fb_group_entries_tool_id" class="fb_group_entries_tool_id">
		<?php echo $fb_group_entries->tool_id->FldCaption() ?></span></th>
		<th><span id="elh_fb_group_entries_application" class="fb_group_entries_application">
		<?php echo $fb_group_entries->application->FldCaption() ?></span></th>
	</tr>
	</thead>
	<tbody>
<?php
$fb_group_entries_delete->RecCnt = 0;
$i = 0;
while (!$fb_group_entries_delete->Recordset->EOF) {
	$fb_group_entries_delete->RecCnt++;
	$fb_group_entries_delete->RowCnt++;

	// Set row properties
	$fb_group_entries->ResetAttrs();
	$fb_group_entries->RowType = EW_ROWTYPE_VIEW; // View

	// Get the field contents
	$fb_group_entries_delete->LoadRowValues($fb_group_entries_delete->Recordset);

	// Render row
	$fb_group_entries_delete->RenderRow();
?>
	<tr<?php echo $fb_group_entries->RowAttributes() ?>>
		<td<?php echo $fb_group_entries->idfb_posts->CellAttributes() ?>><span id="el<?php echo $fb_group_entries_delete->RowCnt ?>_fb_group_entries_idfb_posts" class="fb_group_entries_idfb_posts">
<span<?php echo $fb_group_entries->idfb_posts->ViewAttributes() ?>>
<?php echo $fb_group_entries->idfb_posts->ListViewValue() ?></span>
</span></td>
		<td<?php echo $fb_group_entries->domain_id->CellAttributes() ?>><span id="el<?php echo $fb_group_entries_delete->RowCnt ?>_fb_group_entries_domain_id" class="fb_group_entries_domain_id">
<span<?php echo $fb_group_entries->domain_id->ViewAttributes() ?>>
<?php echo $fb_group_entries->domain_id->ListViewValue() ?></span>
</span></td>
		<td<?php echo $fb_group_entries->record_time->CellAttributes() ?>><span id="el<?php echo $fb_group_entries_delete->RowCnt ?>_fb_group_entries_record_time" class="fb_group_entries_record_time">
<span<?php echo $fb_group_entries->record_time->ViewAttributes() ?>>
<?php echo $fb_group_entries->record_time->ListViewValue() ?></span>
</span></td>
		<td<?php echo $fb_group_entries->data->CellAttributes() ?>><span id="el<?php echo $fb_group_entries_delete->RowCnt ?>_fb_group_entries_data" class="fb_group_entries_data">
<span<?php echo $fb_group_entries->data->ViewAttributes() ?>>
<?php echo $fb_group_entries->data->ListViewValue() ?></span>
</span></td>
		<td<?php echo $fb_group_entries->fid->CellAttributes() ?>><span id="el<?php echo $fb_group_entries_delete->RowCnt ?>_fb_group_entries_fid" class="fb_group_entries_fid">
<span<?php echo $fb_group_entries->fid->ViewAttributes() ?>>
<?php echo $fb_group_entries->fid->ListViewValue() ?></span>
</span></td>
		<td<?php echo $fb_group_entries->md5->CellAttributes() ?>><span id="el<?php echo $fb_group_entries_delete->RowCnt ?>_fb_group_entries_md5" class="fb_group_entries_md5">
<span<?php echo $fb_group_entries->md5->ViewAttributes() ?>>
<?php echo $fb_group_entries->md5->ListViewValue() ?></span>
</span></td>
		<td<?php echo $fb_group_entries->body->CellAttributes() ?>><span id="el<?php echo $fb_group_entries_delete->RowCnt ?>_fb_group_entries_body" class="fb_group_entries_body">
<span<?php echo $fb_group_entries->body->ViewAttributes() ?>>
<?php echo $fb_group_entries->body->ListViewValue() ?></span>
</span></td>
		<td<?php echo $fb_group_entries->titulo->CellAttributes() ?>><span id="el<?php echo $fb_group_entries_delete->RowCnt ?>_fb_group_entries_titulo" class="fb_group_entries_titulo">
<span<?php echo $fb_group_entries->titulo->ViewAttributes() ?>>
<?php echo $fb_group_entries->titulo->ListViewValue() ?></span>
</span></td>
		<td<?php echo $fb_group_entries->created_time->CellAttributes() ?>><span id="el<?php echo $fb_group_entries_delete->RowCnt ?>_fb_group_entries_created_time" class="fb_group_entries_created_time">
<span<?php echo $fb_group_entries->created_time->ViewAttributes() ?>>
<?php echo $fb_group_entries->created_time->ListViewValue() ?></span>
</span></td>
		<td<?php echo $fb_group_entries->actions->CellAttributes() ?>><span id="el<?php echo $fb_group_entries_delete->RowCnt ?>_fb_group_entries_actions" class="fb_group_entries_actions">
<span<?php echo $fb_group_entries->actions->ViewAttributes() ?>>
<?php echo $fb_group_entries->actions->ListViewValue() ?></span>
</span></td>
		<td<?php echo $fb_group_entries->id->CellAttributes() ?>><span id="el<?php echo $fb_group_entries_delete->RowCnt ?>_fb_group_entries_id" class="fb_group_entries_id">
<span<?php echo $fb_group_entries->id->ViewAttributes() ?>>
<?php echo $fb_group_entries->id->ListViewValue() ?></span>
</span></td>
		<td<?php echo $fb_group_entries->is_published->CellAttributes() ?>><span id="el<?php echo $fb_group_entries_delete->RowCnt ?>_fb_group_entries_is_published" class="fb_group_entries_is_published">
<span<?php echo $fb_group_entries->is_published->ViewAttributes() ?>>
<?php echo $fb_group_entries->is_published->ListViewValue() ?></span>
</span></td>
		<td<?php echo $fb_group_entries->message->CellAttributes() ?>><span id="el<?php echo $fb_group_entries_delete->RowCnt ?>_fb_group_entries_message" class="fb_group_entries_message">
<span<?php echo $fb_group_entries->message->ViewAttributes() ?>>
<?php echo $fb_group_entries->message->ListViewValue() ?></span>
</span></td>
		<td<?php echo $fb_group_entries->privacy->CellAttributes() ?>><span id="el<?php echo $fb_group_entries_delete->RowCnt ?>_fb_group_entries_privacy" class="fb_group_entries_privacy">
<span<?php echo $fb_group_entries->privacy->ViewAttributes() ?>>
<?php echo $fb_group_entries->privacy->ListViewValue() ?></span>
</span></td>
		<td<?php echo $fb_group_entries->promotion_status->CellAttributes() ?>><span id="el<?php echo $fb_group_entries_delete->RowCnt ?>_fb_group_entries_promotion_status" class="fb_group_entries_promotion_status">
<span<?php echo $fb_group_entries->promotion_status->ViewAttributes() ?>>
<?php echo $fb_group_entries->promotion_status->ListViewValue() ?></span>
</span></td>
		<td<?php echo $fb_group_entries->timeline_visibility->CellAttributes() ?>><span id="el<?php echo $fb_group_entries_delete->RowCnt ?>_fb_group_entries_timeline_visibility" class="fb_group_entries_timeline_visibility">
<span<?php echo $fb_group_entries->timeline_visibility->ViewAttributes() ?>>
<?php echo $fb_group_entries->timeline_visibility->ListViewValue() ?></span>
</span></td>
		<td<?php echo $fb_group_entries->to->CellAttributes() ?>><span id="el<?php echo $fb_group_entries_delete->RowCnt ?>_fb_group_entries_to" class="fb_group_entries_to">
<span<?php echo $fb_group_entries->to->ViewAttributes() ?>>
<?php echo $fb_group_entries->to->ListViewValue() ?></span>
</span></td>
		<td<?php echo $fb_group_entries->type->CellAttributes() ?>><span id="el<?php echo $fb_group_entries_delete->RowCnt ?>_fb_group_entries_type" class="fb_group_entries_type">
<span<?php echo $fb_group_entries->type->ViewAttributes() ?>>
<?php echo $fb_group_entries->type->ListViewValue() ?></span>
</span></td>
		<td<?php echo $fb_group_entries->updated_time->CellAttributes() ?>><span id="el<?php echo $fb_group_entries_delete->RowCnt ?>_fb_group_entries_updated_time" class="fb_group_entries_updated_time">
<span<?php echo $fb_group_entries->updated_time->ViewAttributes() ?>>
<?php echo $fb_group_entries->updated_time->ListViewValue() ?></span>
</span></td>
		<td<?php echo $fb_group_entries->from->CellAttributes() ?>><span id="el<?php echo $fb_group_entries_delete->RowCnt ?>_fb_group_entries_from" class="fb_group_entries_from">
<span<?php echo $fb_group_entries->from->ViewAttributes() ?>>
<?php echo $fb_group_entries->from->ListViewValue() ?></span>
</span></td>
		<td<?php echo $fb_group_entries->comments->CellAttributes() ?>><span id="el<?php echo $fb_group_entries_delete->RowCnt ?>_fb_group_entries_comments" class="fb_group_entries_comments">
<span<?php echo $fb_group_entries->comments->ViewAttributes() ?>>
<?php echo $fb_group_entries->comments->ListViewValue() ?></span>
</span></td>
		<td<?php echo $fb_group_entries->id_grupo->CellAttributes() ?>><span id="el<?php echo $fb_group_entries_delete->RowCnt ?>_fb_group_entries_id_grupo" class="fb_group_entries_id_grupo">
<span<?php echo $fb_group_entries->id_grupo->ViewAttributes() ?>>
<?php echo $fb_group_entries->id_grupo->ListViewValue() ?></span>
</span></td>
		<td<?php echo $fb_group_entries->icon->CellAttributes() ?>><span id="el<?php echo $fb_group_entries_delete->RowCnt ?>_fb_group_entries_icon" class="fb_group_entries_icon">
<span<?php echo $fb_group_entries->icon->ViewAttributes() ?>>
<?php echo $fb_group_entries->icon->ListViewValue() ?></span>
</span></td>
		<td<?php echo $fb_group_entries->link->CellAttributes() ?>><span id="el<?php echo $fb_group_entries_delete->RowCnt ?>_fb_group_entries_link" class="fb_group_entries_link">
<span<?php echo $fb_group_entries->link->ViewAttributes() ?>>
<?php echo $fb_group_entries->link->ListViewValue() ?></span>
</span></td>
		<td<?php echo $fb_group_entries->name->CellAttributes() ?>><span id="el<?php echo $fb_group_entries_delete->RowCnt ?>_fb_group_entries_name" class="fb_group_entries_name">
<span<?php echo $fb_group_entries->name->ViewAttributes() ?>>
<?php echo $fb_group_entries->name->ListViewValue() ?></span>
</span></td>
		<td<?php echo $fb_group_entries->object_id->CellAttributes() ?>><span id="el<?php echo $fb_group_entries_delete->RowCnt ?>_fb_group_entries_object_id" class="fb_group_entries_object_id">
<span<?php echo $fb_group_entries->object_id->ViewAttributes() ?>>
<?php echo $fb_group_entries->object_id->ListViewValue() ?></span>
</span></td>
		<td<?php echo $fb_group_entries->picture->CellAttributes() ?>><span id="el<?php echo $fb_group_entries_delete->RowCnt ?>_fb_group_entries_picture" class="fb_group_entries_picture">
<span<?php echo $fb_group_entries->picture->ViewAttributes() ?>>
<?php echo $fb_group_entries->picture->ListViewValue() ?></span>
</span></td>
		<td<?php echo $fb_group_entries->properties->CellAttributes() ?>><span id="el<?php echo $fb_group_entries_delete->RowCnt ?>_fb_group_entries_properties" class="fb_group_entries_properties">
<span<?php echo $fb_group_entries->properties->ViewAttributes() ?>>
<?php echo $fb_group_entries->properties->ListViewValue() ?></span>
</span></td>
		<td<?php echo $fb_group_entries->message_tags->CellAttributes() ?>><span id="el<?php echo $fb_group_entries_delete->RowCnt ?>_fb_group_entries_message_tags" class="fb_group_entries_message_tags">
<span<?php echo $fb_group_entries->message_tags->ViewAttributes() ?>>
<?php echo $fb_group_entries->message_tags->ListViewValue() ?></span>
</span></td>
		<td<?php echo $fb_group_entries->caption->CellAttributes() ?>><span id="el<?php echo $fb_group_entries_delete->RowCnt ?>_fb_group_entries_caption" class="fb_group_entries_caption">
<span<?php echo $fb_group_entries->caption->ViewAttributes() ?>>
<?php echo $fb_group_entries->caption->ListViewValue() ?></span>
</span></td>
		<td<?php echo $fb_group_entries->description->CellAttributes() ?>><span id="el<?php echo $fb_group_entries_delete->RowCnt ?>_fb_group_entries_description" class="fb_group_entries_description">
<span<?php echo $fb_group_entries->description->ViewAttributes() ?>>
<?php echo $fb_group_entries->description->ListViewValue() ?></span>
</span></td>
		<td<?php echo $fb_group_entries->tool_id->CellAttributes() ?>><span id="el<?php echo $fb_group_entries_delete->RowCnt ?>_fb_group_entries_tool_id" class="fb_group_entries_tool_id">
<span<?php echo $fb_group_entries->tool_id->ViewAttributes() ?>>
<?php echo $fb_group_entries->tool_id->ListViewValue() ?></span>
</span></td>
		<td<?php echo $fb_group_entries->application->CellAttributes() ?>><span id="el<?php echo $fb_group_entries_delete->RowCnt ?>_fb_group_entries_application" class="fb_group_entries_application">
<span<?php echo $fb_group_entries->application->ViewAttributes() ?>>
<?php echo $fb_group_entries->application->ListViewValue() ?></span>
</span></td>
	</tr>
<?php
	$fb_group_entries_delete->Recordset->MoveNext();
}
$fb_group_entries_delete->Recordset->Close();
?>
</tbody>
</table>
<input class="ewLink btn btn-danger" type="submit" name="Action" value="<?php echo ew_BtnCaption($Language->Phrase("DeleteBtn")) ?>" />
</form>
<script type="text/javascript">
ffb_group_entriesdelete.Init();
</script>
<?php
$fb_group_entries_delete->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$fb_group_entries_delete->Page_Terminate();
?>
