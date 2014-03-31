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

$fb_group_entries_edit = NULL; // Initialize page object first

class cfb_group_entries_edit extends cfb_group_entries {

	// Page ID
	var $PageID = 'edit';

	// Project ID
	var $ProjectID = "{3554BCCA-7E88-4E52-9661-DF55D75275C9}";

	// Table name
	var $TableName = 'fb_group_entries';

	// Page object name
	var $PageObjName = 'fb_group_entries_edit';

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
			define("EW_PAGE_ID", 'edit', TRUE);

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
		if (!$Security->CanEdit()) {
			$Security->SaveLastUrl();
			$this->setFailureMessage($Language->Phrase("NoPermission")); // Set no permission
			$this->Page_Terminate("fb_group_entrieslist.php");
		}
		$Security->UserID_Loading();
		if ($Security->IsLoggedIn()) $Security->LoadUserID();
		$Security->UserID_Loaded();

		// Create form object
		$objForm = new cFormObj();
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
	var $DbMasterFilter;
	var $DbDetailFilter;

	// 
	// Page main
	//
	function Page_Main() {
		global $objForm, $Language, $gsFormError;

		// Load key from QueryString
		if (@$_GET["idfb_posts"] <> "")
			$this->idfb_posts->setQueryStringValue($_GET["idfb_posts"]);

		// Process form if post back
		if (@$_POST["a_edit"] <> "") {
			$this->CurrentAction = $_POST["a_edit"]; // Get action code
			$this->LoadFormValues(); // Get form values
		} else {
			$this->CurrentAction = "I"; // Default action is display
		}

		// Check if valid key
		if ($this->idfb_posts->CurrentValue == "")
			$this->Page_Terminate($this->james_url( "fb_group_entrieslist.php" )); // Invalid key, return to list

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
					$this->Page_Terminate($this->james_url( "fb_group_entrieslist.php" )); // No matching record, return to list
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
		if (!$this->idfb_posts->FldIsDetailKey)
			$this->idfb_posts->setFormValue($objForm->GetValue("x_idfb_posts"));
		if (!$this->domain_id->FldIsDetailKey) {
			$this->domain_id->setFormValue($objForm->GetValue("x_domain_id"));
		}
		if (!$this->record_time->FldIsDetailKey) {
			$this->record_time->setFormValue($objForm->GetValue("x_record_time"));
			$this->record_time->CurrentValue = ew_UnFormatDateTime($this->record_time->CurrentValue, 5);
		}
		if (!$this->data->FldIsDetailKey) {
			$this->data->setFormValue($objForm->GetValue("x_data"));
		}
		if (!$this->fid->FldIsDetailKey) {
			$this->fid->setFormValue($objForm->GetValue("x_fid"));
		}
		if (!$this->md5->FldIsDetailKey) {
			$this->md5->setFormValue($objForm->GetValue("x_md5"));
		}
		if (!$this->body->FldIsDetailKey) {
			$this->body->setFormValue($objForm->GetValue("x_body"));
		}
		if (!$this->titulo->FldIsDetailKey) {
			$this->titulo->setFormValue($objForm->GetValue("x_titulo"));
		}
		if (!$this->created_time->FldIsDetailKey) {
			$this->created_time->setFormValue($objForm->GetValue("x_created_time"));
		}
		if (!$this->actions->FldIsDetailKey) {
			$this->actions->setFormValue($objForm->GetValue("x_actions"));
		}
		if (!$this->id->FldIsDetailKey) {
			$this->id->setFormValue($objForm->GetValue("x_id"));
		}
		if (!$this->is_published->FldIsDetailKey) {
			$this->is_published->setFormValue($objForm->GetValue("x_is_published"));
		}
		if (!$this->message->FldIsDetailKey) {
			$this->message->setFormValue($objForm->GetValue("x_message"));
		}
		if (!$this->privacy->FldIsDetailKey) {
			$this->privacy->setFormValue($objForm->GetValue("x_privacy"));
		}
		if (!$this->promotion_status->FldIsDetailKey) {
			$this->promotion_status->setFormValue($objForm->GetValue("x_promotion_status"));
		}
		if (!$this->timeline_visibility->FldIsDetailKey) {
			$this->timeline_visibility->setFormValue($objForm->GetValue("x_timeline_visibility"));
		}
		if (!$this->to->FldIsDetailKey) {
			$this->to->setFormValue($objForm->GetValue("x_to"));
		}
		if (!$this->type->FldIsDetailKey) {
			$this->type->setFormValue($objForm->GetValue("x_type"));
		}
		if (!$this->updated_time->FldIsDetailKey) {
			$this->updated_time->setFormValue($objForm->GetValue("x_updated_time"));
		}
		if (!$this->from->FldIsDetailKey) {
			$this->from->setFormValue($objForm->GetValue("x_from"));
		}
		if (!$this->comments->FldIsDetailKey) {
			$this->comments->setFormValue($objForm->GetValue("x_comments"));
		}
		if (!$this->id_grupo->FldIsDetailKey) {
			$this->id_grupo->setFormValue($objForm->GetValue("x_id_grupo"));
		}
		if (!$this->icon->FldIsDetailKey) {
			$this->icon->setFormValue($objForm->GetValue("x_icon"));
		}
		if (!$this->link->FldIsDetailKey) {
			$this->link->setFormValue($objForm->GetValue("x_link"));
		}
		if (!$this->name->FldIsDetailKey) {
			$this->name->setFormValue($objForm->GetValue("x_name"));
		}
		if (!$this->object_id->FldIsDetailKey) {
			$this->object_id->setFormValue($objForm->GetValue("x_object_id"));
		}
		if (!$this->picture->FldIsDetailKey) {
			$this->picture->setFormValue($objForm->GetValue("x_picture"));
		}
		if (!$this->properties->FldIsDetailKey) {
			$this->properties->setFormValue($objForm->GetValue("x_properties"));
		}
		if (!$this->message_tags->FldIsDetailKey) {
			$this->message_tags->setFormValue($objForm->GetValue("x_message_tags"));
		}
		if (!$this->caption->FldIsDetailKey) {
			$this->caption->setFormValue($objForm->GetValue("x_caption"));
		}
		if (!$this->description->FldIsDetailKey) {
			$this->description->setFormValue($objForm->GetValue("x_description"));
		}
		if (!$this->tool_id->FldIsDetailKey) {
			$this->tool_id->setFormValue($objForm->GetValue("x_tool_id"));
		}
		if (!$this->application->FldIsDetailKey) {
			$this->application->setFormValue($objForm->GetValue("x_application"));
		}
	}

	// Restore form values
	function RestoreFormValues() {
		global $objForm;
		$this->LoadRow();
		$this->idfb_posts->CurrentValue = $this->idfb_posts->FormValue;
		$this->domain_id->CurrentValue = $this->domain_id->FormValue;
		$this->record_time->CurrentValue = $this->record_time->FormValue;
		$this->record_time->CurrentValue = ew_UnFormatDateTime($this->record_time->CurrentValue, 5);
		$this->data->CurrentValue = $this->data->FormValue;
		$this->fid->CurrentValue = $this->fid->FormValue;
		$this->md5->CurrentValue = $this->md5->FormValue;
		$this->body->CurrentValue = $this->body->FormValue;
		$this->titulo->CurrentValue = $this->titulo->FormValue;
		$this->created_time->CurrentValue = $this->created_time->FormValue;
		$this->actions->CurrentValue = $this->actions->FormValue;
		$this->id->CurrentValue = $this->id->FormValue;
		$this->is_published->CurrentValue = $this->is_published->FormValue;
		$this->message->CurrentValue = $this->message->FormValue;
		$this->privacy->CurrentValue = $this->privacy->FormValue;
		$this->promotion_status->CurrentValue = $this->promotion_status->FormValue;
		$this->timeline_visibility->CurrentValue = $this->timeline_visibility->FormValue;
		$this->to->CurrentValue = $this->to->FormValue;
		$this->type->CurrentValue = $this->type->FormValue;
		$this->updated_time->CurrentValue = $this->updated_time->FormValue;
		$this->from->CurrentValue = $this->from->FormValue;
		$this->comments->CurrentValue = $this->comments->FormValue;
		$this->id_grupo->CurrentValue = $this->id_grupo->FormValue;
		$this->icon->CurrentValue = $this->icon->FormValue;
		$this->link->CurrentValue = $this->link->FormValue;
		$this->name->CurrentValue = $this->name->FormValue;
		$this->object_id->CurrentValue = $this->object_id->FormValue;
		$this->picture->CurrentValue = $this->picture->FormValue;
		$this->properties->CurrentValue = $this->properties->FormValue;
		$this->message_tags->CurrentValue = $this->message_tags->FormValue;
		$this->caption->CurrentValue = $this->caption->FormValue;
		$this->description->CurrentValue = $this->description->FormValue;
		$this->tool_id->CurrentValue = $this->tool_id->FormValue;
		$this->application->CurrentValue = $this->application->FormValue;
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
		} elseif ($this->RowType == EW_ROWTYPE_EDIT) { // Edit row

			// idfb_posts
			$this->idfb_posts->EditCustomAttributes = "";
			$this->idfb_posts->EditValue = $this->idfb_posts->CurrentValue;
			$this->idfb_posts->ViewCustomAttributes = "";

			// domain_id
			$this->domain_id->EditCustomAttributes = "";
			$this->domain_id->EditValue = ew_HtmlEncode($this->domain_id->CurrentValue);

			// record_time
			$this->record_time->EditCustomAttributes = "";
			$this->record_time->EditValue = ew_HtmlEncode(ew_FormatDateTime($this->record_time->CurrentValue, 5));

			// data
			$this->data->EditCustomAttributes = "";
			$this->data->EditValue = ew_HtmlEncode($this->data->CurrentValue);

			// fid
			$this->fid->EditCustomAttributes = "";
			$this->fid->EditValue = ew_HtmlEncode($this->fid->CurrentValue);

			// md5
			$this->md5->EditCustomAttributes = "";
			$this->md5->EditValue = ew_HtmlEncode($this->md5->CurrentValue);

			// body
			$this->body->EditCustomAttributes = "";
			$this->body->EditValue = ew_HtmlEncode($this->body->CurrentValue);

			// titulo
			$this->titulo->EditCustomAttributes = "";
			$this->titulo->EditValue = ew_HtmlEncode($this->titulo->CurrentValue);

			// created_time
			$this->created_time->EditCustomAttributes = "";
			$this->created_time->EditValue = ew_HtmlEncode($this->created_time->CurrentValue);

			// actions
			$this->actions->EditCustomAttributes = "";
			$this->actions->EditValue = ew_HtmlEncode($this->actions->CurrentValue);

			// id
			$this->id->EditCustomAttributes = "";
			$this->id->EditValue = ew_HtmlEncode($this->id->CurrentValue);

			// is_published
			$this->is_published->EditCustomAttributes = "";
			$this->is_published->EditValue = ew_HtmlEncode($this->is_published->CurrentValue);

			// message
			$this->message->EditCustomAttributes = "";
			$this->message->EditValue = ew_HtmlEncode($this->message->CurrentValue);

			// privacy
			$this->privacy->EditCustomAttributes = "";
			$this->privacy->EditValue = ew_HtmlEncode($this->privacy->CurrentValue);

			// promotion_status
			$this->promotion_status->EditCustomAttributes = "";
			$this->promotion_status->EditValue = ew_HtmlEncode($this->promotion_status->CurrentValue);

			// timeline_visibility
			$this->timeline_visibility->EditCustomAttributes = "";
			$this->timeline_visibility->EditValue = ew_HtmlEncode($this->timeline_visibility->CurrentValue);

			// to
			$this->to->EditCustomAttributes = "";
			$this->to->EditValue = ew_HtmlEncode($this->to->CurrentValue);

			// type
			$this->type->EditCustomAttributes = "";
			$this->type->EditValue = ew_HtmlEncode($this->type->CurrentValue);

			// updated_time
			$this->updated_time->EditCustomAttributes = "";
			$this->updated_time->EditValue = ew_HtmlEncode($this->updated_time->CurrentValue);

			// from
			$this->from->EditCustomAttributes = "";
			$this->from->EditValue = ew_HtmlEncode($this->from->CurrentValue);

			// comments
			$this->comments->EditCustomAttributes = "";
			$this->comments->EditValue = ew_HtmlEncode($this->comments->CurrentValue);

			// id_grupo
			$this->id_grupo->EditCustomAttributes = "";
			$this->id_grupo->EditValue = ew_HtmlEncode($this->id_grupo->CurrentValue);

			// icon
			$this->icon->EditCustomAttributes = "";
			$this->icon->EditValue = ew_HtmlEncode($this->icon->CurrentValue);

			// link
			$this->link->EditCustomAttributes = "";
			$this->link->EditValue = ew_HtmlEncode($this->link->CurrentValue);

			// name
			$this->name->EditCustomAttributes = "";
			$this->name->EditValue = ew_HtmlEncode($this->name->CurrentValue);

			// object_id
			$this->object_id->EditCustomAttributes = "";
			$this->object_id->EditValue = ew_HtmlEncode($this->object_id->CurrentValue);

			// picture
			$this->picture->EditCustomAttributes = "";
			$this->picture->EditValue = ew_HtmlEncode($this->picture->CurrentValue);

			// properties
			$this->properties->EditCustomAttributes = "";
			$this->properties->EditValue = ew_HtmlEncode($this->properties->CurrentValue);

			// message_tags
			$this->message_tags->EditCustomAttributes = "";
			$this->message_tags->EditValue = ew_HtmlEncode($this->message_tags->CurrentValue);

			// caption
			$this->caption->EditCustomAttributes = "";
			$this->caption->EditValue = ew_HtmlEncode($this->caption->CurrentValue);

			// description
			$this->description->EditCustomAttributes = "";
			$this->description->EditValue = ew_HtmlEncode($this->description->CurrentValue);

			// tool_id
			$this->tool_id->EditCustomAttributes = "";
			$this->tool_id->EditValue = ew_HtmlEncode($this->tool_id->CurrentValue);

			// application
			$this->application->EditCustomAttributes = "";
			$this->application->EditValue = ew_HtmlEncode($this->application->CurrentValue);

			// Edit refer script
			// idfb_posts

			$this->idfb_posts->HrefValue = "";

			// domain_id
			$this->domain_id->HrefValue = "";

			// record_time
			$this->record_time->HrefValue = "";

			// data
			$this->data->HrefValue = "";

			// fid
			$this->fid->HrefValue = "";

			// md5
			$this->md5->HrefValue = "";

			// body
			$this->body->HrefValue = "";

			// titulo
			$this->titulo->HrefValue = "";

			// created_time
			$this->created_time->HrefValue = "";

			// actions
			$this->actions->HrefValue = "";

			// id
			$this->id->HrefValue = "";

			// is_published
			$this->is_published->HrefValue = "";

			// message
			$this->message->HrefValue = "";

			// privacy
			$this->privacy->HrefValue = "";

			// promotion_status
			$this->promotion_status->HrefValue = "";

			// timeline_visibility
			$this->timeline_visibility->HrefValue = "";

			// to
			$this->to->HrefValue = "";

			// type
			$this->type->HrefValue = "";

			// updated_time
			$this->updated_time->HrefValue = "";

			// from
			$this->from->HrefValue = "";

			// comments
			$this->comments->HrefValue = "";

			// id_grupo
			$this->id_grupo->HrefValue = "";

			// icon
			$this->icon->HrefValue = "";

			// link
			$this->link->HrefValue = "";

			// name
			$this->name->HrefValue = "";

			// object_id
			$this->object_id->HrefValue = "";

			// picture
			$this->picture->HrefValue = "";

			// properties
			$this->properties->HrefValue = "";

			// message_tags
			$this->message_tags->HrefValue = "";

			// caption
			$this->caption->HrefValue = "";

			// description
			$this->description->HrefValue = "";

			// tool_id
			$this->tool_id->HrefValue = "";

			// application
			$this->application->HrefValue = "";
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
		if (!ew_CheckInteger($this->domain_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->domain_id->FldErrMsg());
		}
		if (!ew_CheckDate($this->record_time->FormValue)) {
			ew_AddMessage($gsFormError, $this->record_time->FldErrMsg());
		}
		if (!ew_CheckInteger($this->tool_id->FormValue)) {
			ew_AddMessage($gsFormError, $this->tool_id->FldErrMsg());
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

			// domain_id
			$this->domain_id->SetDbValueDef($rsnew, $this->domain_id->CurrentValue, NULL, $this->domain_id->ReadOnly);

			// record_time
			$this->record_time->SetDbValueDef($rsnew, ew_UnFormatDateTime($this->record_time->CurrentValue, 5), NULL, $this->record_time->ReadOnly);

			// data
			$this->data->SetDbValueDef($rsnew, $this->data->CurrentValue, NULL, $this->data->ReadOnly);

			// fid
			$this->fid->SetDbValueDef($rsnew, $this->fid->CurrentValue, NULL, $this->fid->ReadOnly);

			// md5
			$this->md5->SetDbValueDef($rsnew, $this->md5->CurrentValue, NULL, $this->md5->ReadOnly);

			// body
			$this->body->SetDbValueDef($rsnew, $this->body->CurrentValue, NULL, $this->body->ReadOnly);

			// titulo
			$this->titulo->SetDbValueDef($rsnew, $this->titulo->CurrentValue, NULL, $this->titulo->ReadOnly);

			// created_time
			$this->created_time->SetDbValueDef($rsnew, $this->created_time->CurrentValue, NULL, $this->created_time->ReadOnly);

			// actions
			$this->actions->SetDbValueDef($rsnew, $this->actions->CurrentValue, NULL, $this->actions->ReadOnly);

			// id
			$this->id->SetDbValueDef($rsnew, $this->id->CurrentValue, NULL, $this->id->ReadOnly);

			// is_published
			$this->is_published->SetDbValueDef($rsnew, $this->is_published->CurrentValue, NULL, $this->is_published->ReadOnly);

			// message
			$this->message->SetDbValueDef($rsnew, $this->message->CurrentValue, NULL, $this->message->ReadOnly);

			// privacy
			$this->privacy->SetDbValueDef($rsnew, $this->privacy->CurrentValue, NULL, $this->privacy->ReadOnly);

			// promotion_status
			$this->promotion_status->SetDbValueDef($rsnew, $this->promotion_status->CurrentValue, NULL, $this->promotion_status->ReadOnly);

			// timeline_visibility
			$this->timeline_visibility->SetDbValueDef($rsnew, $this->timeline_visibility->CurrentValue, NULL, $this->timeline_visibility->ReadOnly);

			// to
			$this->to->SetDbValueDef($rsnew, $this->to->CurrentValue, NULL, $this->to->ReadOnly);

			// type
			$this->type->SetDbValueDef($rsnew, $this->type->CurrentValue, NULL, $this->type->ReadOnly);

			// updated_time
			$this->updated_time->SetDbValueDef($rsnew, $this->updated_time->CurrentValue, NULL, $this->updated_time->ReadOnly);

			// from
			$this->from->SetDbValueDef($rsnew, $this->from->CurrentValue, NULL, $this->from->ReadOnly);

			// comments
			$this->comments->SetDbValueDef($rsnew, $this->comments->CurrentValue, NULL, $this->comments->ReadOnly);

			// id_grupo
			$this->id_grupo->SetDbValueDef($rsnew, $this->id_grupo->CurrentValue, NULL, $this->id_grupo->ReadOnly);

			// icon
			$this->icon->SetDbValueDef($rsnew, $this->icon->CurrentValue, NULL, $this->icon->ReadOnly);

			// link
			$this->link->SetDbValueDef($rsnew, $this->link->CurrentValue, NULL, $this->link->ReadOnly);

			// name
			$this->name->SetDbValueDef($rsnew, $this->name->CurrentValue, NULL, $this->name->ReadOnly);

			// object_id
			$this->object_id->SetDbValueDef($rsnew, $this->object_id->CurrentValue, NULL, $this->object_id->ReadOnly);

			// picture
			$this->picture->SetDbValueDef($rsnew, $this->picture->CurrentValue, NULL, $this->picture->ReadOnly);

			// properties
			$this->properties->SetDbValueDef($rsnew, $this->properties->CurrentValue, NULL, $this->properties->ReadOnly);

			// message_tags
			$this->message_tags->SetDbValueDef($rsnew, $this->message_tags->CurrentValue, NULL, $this->message_tags->ReadOnly);

			// caption
			$this->caption->SetDbValueDef($rsnew, $this->caption->CurrentValue, NULL, $this->caption->ReadOnly);

			// description
			$this->description->SetDbValueDef($rsnew, $this->description->CurrentValue, NULL, $this->description->ReadOnly);

			// tool_id
			$this->tool_id->SetDbValueDef($rsnew, $this->tool_id->CurrentValue, NULL, $this->tool_id->ReadOnly);

			// application
			$this->application->SetDbValueDef($rsnew, $this->application->CurrentValue, NULL, $this->application->ReadOnly);

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
if (!isset($fb_group_entries_edit)) $fb_group_entries_edit = new cfb_group_entries_edit();

// Page init
$fb_group_entries_edit->Page_Init();

// Page main
$fb_group_entries_edit->Page_Main();
?>
<?php include_once "header.php" ?>
<script type="text/javascript">

// Page object
var fb_group_entries_edit = new ew_Page("fb_group_entries_edit");
fb_group_entries_edit.PageID = "edit"; // Page ID
var EW_PAGE_ID = fb_group_entries_edit.PageID; // For backward compatibility

// Form object
var ffb_group_entriesedit = new ew_Form("ffb_group_entriesedit");

// Validate form
ffb_group_entriesedit.Validate = function(fobj) {
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
		elm = fobj.elements["x" + infix + "_domain_id"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($fb_group_entries->domain_id->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_record_time"];
		if (elm && !ew_CheckDate(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($fb_group_entries->record_time->FldErrMsg()) ?>");
		elm = fobj.elements["x" + infix + "_tool_id"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($fb_group_entries->tool_id->FldErrMsg()) ?>");

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
ffb_group_entriesedit.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ffb_group_entriesedit.ValidateRequired = true;
<?php } else { ?>
ffb_group_entriesedit.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<script type="text/javascript">

// Write your client script here, no need to add script tags.
</script>
<h4 id="ewPageCaption" class="page-header ewTitle ewTableTitle"> <?php echo $Language->Phrase("Edit") ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $fb_group_entries->TableCaption() ?></h4>
<a href="<?php echo $fb_group_entries->getReturnUrl() ?>" id="a_GoBack" class="ewLink label"><i class="icon-arrow-left icon-white"></i> <?php echo $Language->Phrase("GoBack") ?></a>
<?php $fb_group_entries_edit->ShowPageHeader(); ?>
<?php
$fb_group_entries_edit->ShowMessage();
?>
<form name="ffb_group_entriesedit" id="ffb_group_entriesedit" class="ewForm" action="<?php echo ew_CurrentPage() ?>" method="post" onsubmit="return ewForms[this.id].Submit();">
<input type="hidden" name="t" value="fb_group_entries" />
<input type="hidden" name="a_edit" id="a_edit" value="U" />
<table id="tbl_fb_group_entriesedit" class="ewTable ewTableSeparate table table-striped ">
<?php if ($fb_group_entries->idfb_posts->Visible) { // idfb_posts ?>
	<tr id="r_idfb_posts"<?php echo $fb_group_entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_group_entries_idfb_posts">
		<b><?php echo $fb_group_entries->idfb_posts->FldCaption() ?></b>
		</span></td>
		<td<?php echo $fb_group_entries->idfb_posts->CellAttributes() ?>><span id="el_fb_group_entries_idfb_posts">
<span<?php echo $fb_group_entries->idfb_posts->ViewAttributes() ?>>
<?php echo $fb_group_entries->idfb_posts->EditValue ?></span>
<input type="hidden" name="x_idfb_posts" id="x_idfb_posts" value="<?php echo ew_HtmlEncode($fb_group_entries->idfb_posts->CurrentValue) ?>" />
</span><?php echo $fb_group_entries->idfb_posts->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($fb_group_entries->domain_id->Visible) { // domain_id ?>
	<tr id="r_domain_id"<?php echo $fb_group_entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_group_entries_domain_id">
		<b><?php echo $fb_group_entries->domain_id->FldCaption() ?></b>
		</span></td>
		<td<?php echo $fb_group_entries->domain_id->CellAttributes() ?>><span id="el_fb_group_entries_domain_id">
<input type="text" name="x_domain_id" id="x_domain_id" size="30" value="<?php echo $fb_group_entries->domain_id->EditValue ?>"<?php echo $fb_group_entries->domain_id->EditAttributes() ?> />
</span><?php echo $fb_group_entries->domain_id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($fb_group_entries->record_time->Visible) { // record_time ?>
	<tr id="r_record_time"<?php echo $fb_group_entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_group_entries_record_time">
		<b><?php echo $fb_group_entries->record_time->FldCaption() ?></b>
		</span></td>
		<td<?php echo $fb_group_entries->record_time->CellAttributes() ?>><span id="el_fb_group_entries_record_time">
<input type="text" name="x_record_time" id="x_record_time" value="<?php echo $fb_group_entries->record_time->EditValue ?>"<?php echo $fb_group_entries->record_time->EditAttributes() ?> />
</span><?php echo $fb_group_entries->record_time->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($fb_group_entries->data->Visible) { // data ?>
	<tr id="r_data"<?php echo $fb_group_entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_group_entries_data">
		<b><?php echo $fb_group_entries->data->FldCaption() ?></b>
		</span></td>
		<td<?php echo $fb_group_entries->data->CellAttributes() ?>><span id="el_fb_group_entries_data">
<input type="text" name="x_data" id="x_data" size="30" maxlength="245" value="<?php echo $fb_group_entries->data->EditValue ?>"<?php echo $fb_group_entries->data->EditAttributes() ?> />
</span><?php echo $fb_group_entries->data->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($fb_group_entries->fid->Visible) { // fid ?>
	<tr id="r_fid"<?php echo $fb_group_entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_group_entries_fid">
		<b><?php echo $fb_group_entries->fid->FldCaption() ?></b>
		</span></td>
		<td<?php echo $fb_group_entries->fid->CellAttributes() ?>><span id="el_fb_group_entries_fid">
<input type="text" name="x_fid" id="x_fid" size="30" maxlength="245" value="<?php echo $fb_group_entries->fid->EditValue ?>"<?php echo $fb_group_entries->fid->EditAttributes() ?> />
</span><?php echo $fb_group_entries->fid->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($fb_group_entries->md5->Visible) { // md5 ?>
	<tr id="r_md5"<?php echo $fb_group_entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_group_entries_md5">
		<b><?php echo $fb_group_entries->md5->FldCaption() ?></b>
		</span></td>
		<td<?php echo $fb_group_entries->md5->CellAttributes() ?>><span id="el_fb_group_entries_md5">
<input type="text" name="x_md5" id="x_md5" size="30" maxlength="245" value="<?php echo $fb_group_entries->md5->EditValue ?>"<?php echo $fb_group_entries->md5->EditAttributes() ?> />
</span><?php echo $fb_group_entries->md5->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($fb_group_entries->body->Visible) { // body ?>
	<tr id="r_body"<?php echo $fb_group_entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_group_entries_body">
		<b><?php echo $fb_group_entries->body->FldCaption() ?></b>
		</span></td>
		<td<?php echo $fb_group_entries->body->CellAttributes() ?>><span id="el_fb_group_entries_body">
<input type="text" name="x_body" id="x_body" size="30" maxlength="245" value="<?php echo $fb_group_entries->body->EditValue ?>"<?php echo $fb_group_entries->body->EditAttributes() ?> />
</span><?php echo $fb_group_entries->body->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($fb_group_entries->titulo->Visible) { // titulo ?>
	<tr id="r_titulo"<?php echo $fb_group_entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_group_entries_titulo">
		<b><?php echo $fb_group_entries->titulo->FldCaption() ?></b>
		</span></td>
		<td<?php echo $fb_group_entries->titulo->CellAttributes() ?>><span id="el_fb_group_entries_titulo">
<input type="text" name="x_titulo" id="x_titulo" size="30" maxlength="245" value="<?php echo $fb_group_entries->titulo->EditValue ?>"<?php echo $fb_group_entries->titulo->EditAttributes() ?> />
</span><?php echo $fb_group_entries->titulo->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($fb_group_entries->created_time->Visible) { // created_time ?>
	<tr id="r_created_time"<?php echo $fb_group_entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_group_entries_created_time">
		<b><?php echo $fb_group_entries->created_time->FldCaption() ?></b>
		</span></td>
		<td<?php echo $fb_group_entries->created_time->CellAttributes() ?>><span id="el_fb_group_entries_created_time">
<input type="text" name="x_created_time" id="x_created_time" size="30" maxlength="245" value="<?php echo $fb_group_entries->created_time->EditValue ?>"<?php echo $fb_group_entries->created_time->EditAttributes() ?> />
</span><?php echo $fb_group_entries->created_time->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($fb_group_entries->actions->Visible) { // actions ?>
	<tr id="r_actions"<?php echo $fb_group_entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_group_entries_actions">
		<b><?php echo $fb_group_entries->actions->FldCaption() ?></b>
		</span></td>
		<td<?php echo $fb_group_entries->actions->CellAttributes() ?>><span id="el_fb_group_entries_actions">
<input type="text" name="x_actions" id="x_actions" size="30" maxlength="245" value="<?php echo $fb_group_entries->actions->EditValue ?>"<?php echo $fb_group_entries->actions->EditAttributes() ?> />
</span><?php echo $fb_group_entries->actions->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($fb_group_entries->id->Visible) { // id ?>
	<tr id="r_id"<?php echo $fb_group_entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_group_entries_id">
		<b><?php echo $fb_group_entries->id->FldCaption() ?></b>
		</span></td>
		<td<?php echo $fb_group_entries->id->CellAttributes() ?>><span id="el_fb_group_entries_id">
<input type="text" name="x_id" id="x_id" size="30" maxlength="245" value="<?php echo $fb_group_entries->id->EditValue ?>"<?php echo $fb_group_entries->id->EditAttributes() ?> />
</span><?php echo $fb_group_entries->id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($fb_group_entries->is_published->Visible) { // is_published ?>
	<tr id="r_is_published"<?php echo $fb_group_entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_group_entries_is_published">
		<b><?php echo $fb_group_entries->is_published->FldCaption() ?></b>
		</span></td>
		<td<?php echo $fb_group_entries->is_published->CellAttributes() ?>><span id="el_fb_group_entries_is_published">
<input type="text" name="x_is_published" id="x_is_published" size="30" maxlength="245" value="<?php echo $fb_group_entries->is_published->EditValue ?>"<?php echo $fb_group_entries->is_published->EditAttributes() ?> />
</span><?php echo $fb_group_entries->is_published->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($fb_group_entries->message->Visible) { // message ?>
	<tr id="r_message"<?php echo $fb_group_entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_group_entries_message">
		<b><?php echo $fb_group_entries->message->FldCaption() ?></b>
		</span></td>
		<td<?php echo $fb_group_entries->message->CellAttributes() ?>><span id="el_fb_group_entries_message">
<input type="text" name="x_message" id="x_message" size="30" maxlength="245" value="<?php echo $fb_group_entries->message->EditValue ?>"<?php echo $fb_group_entries->message->EditAttributes() ?> />
</span><?php echo $fb_group_entries->message->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($fb_group_entries->privacy->Visible) { // privacy ?>
	<tr id="r_privacy"<?php echo $fb_group_entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_group_entries_privacy">
		<b><?php echo $fb_group_entries->privacy->FldCaption() ?></b>
		</span></td>
		<td<?php echo $fb_group_entries->privacy->CellAttributes() ?>><span id="el_fb_group_entries_privacy">
<input type="text" name="x_privacy" id="x_privacy" size="30" maxlength="245" value="<?php echo $fb_group_entries->privacy->EditValue ?>"<?php echo $fb_group_entries->privacy->EditAttributes() ?> />
</span><?php echo $fb_group_entries->privacy->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($fb_group_entries->promotion_status->Visible) { // promotion_status ?>
	<tr id="r_promotion_status"<?php echo $fb_group_entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_group_entries_promotion_status">
		<b><?php echo $fb_group_entries->promotion_status->FldCaption() ?></b>
		</span></td>
		<td<?php echo $fb_group_entries->promotion_status->CellAttributes() ?>><span id="el_fb_group_entries_promotion_status">
<input type="text" name="x_promotion_status" id="x_promotion_status" size="30" maxlength="245" value="<?php echo $fb_group_entries->promotion_status->EditValue ?>"<?php echo $fb_group_entries->promotion_status->EditAttributes() ?> />
</span><?php echo $fb_group_entries->promotion_status->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($fb_group_entries->timeline_visibility->Visible) { // timeline_visibility ?>
	<tr id="r_timeline_visibility"<?php echo $fb_group_entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_group_entries_timeline_visibility">
		<b><?php echo $fb_group_entries->timeline_visibility->FldCaption() ?></b>
		</span></td>
		<td<?php echo $fb_group_entries->timeline_visibility->CellAttributes() ?>><span id="el_fb_group_entries_timeline_visibility">
<input type="text" name="x_timeline_visibility" id="x_timeline_visibility" size="30" maxlength="245" value="<?php echo $fb_group_entries->timeline_visibility->EditValue ?>"<?php echo $fb_group_entries->timeline_visibility->EditAttributes() ?> />
</span><?php echo $fb_group_entries->timeline_visibility->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($fb_group_entries->to->Visible) { // to ?>
	<tr id="r_to"<?php echo $fb_group_entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_group_entries_to">
		<b><?php echo $fb_group_entries->to->FldCaption() ?></b>
		</span></td>
		<td<?php echo $fb_group_entries->to->CellAttributes() ?>><span id="el_fb_group_entries_to">
<input type="text" name="x_to" id="x_to" size="30" maxlength="245" value="<?php echo $fb_group_entries->to->EditValue ?>"<?php echo $fb_group_entries->to->EditAttributes() ?> />
</span><?php echo $fb_group_entries->to->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($fb_group_entries->type->Visible) { // type ?>
	<tr id="r_type"<?php echo $fb_group_entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_group_entries_type">
		<b><?php echo $fb_group_entries->type->FldCaption() ?></b>
		</span></td>
		<td<?php echo $fb_group_entries->type->CellAttributes() ?>><span id="el_fb_group_entries_type">
<input type="text" name="x_type" id="x_type" size="30" maxlength="245" value="<?php echo $fb_group_entries->type->EditValue ?>"<?php echo $fb_group_entries->type->EditAttributes() ?> />
</span><?php echo $fb_group_entries->type->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($fb_group_entries->updated_time->Visible) { // updated_time ?>
	<tr id="r_updated_time"<?php echo $fb_group_entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_group_entries_updated_time">
		<b><?php echo $fb_group_entries->updated_time->FldCaption() ?></b>
		</span></td>
		<td<?php echo $fb_group_entries->updated_time->CellAttributes() ?>><span id="el_fb_group_entries_updated_time">
<input type="text" name="x_updated_time" id="x_updated_time" size="30" maxlength="245" value="<?php echo $fb_group_entries->updated_time->EditValue ?>"<?php echo $fb_group_entries->updated_time->EditAttributes() ?> />
</span><?php echo $fb_group_entries->updated_time->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($fb_group_entries->from->Visible) { // from ?>
	<tr id="r_from"<?php echo $fb_group_entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_group_entries_from">
		<b><?php echo $fb_group_entries->from->FldCaption() ?></b>
		</span></td>
		<td<?php echo $fb_group_entries->from->CellAttributes() ?>><span id="el_fb_group_entries_from">
<input type="text" name="x_from" id="x_from" size="30" maxlength="245" value="<?php echo $fb_group_entries->from->EditValue ?>"<?php echo $fb_group_entries->from->EditAttributes() ?> />
</span><?php echo $fb_group_entries->from->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($fb_group_entries->comments->Visible) { // comments ?>
	<tr id="r_comments"<?php echo $fb_group_entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_group_entries_comments">
		<b><?php echo $fb_group_entries->comments->FldCaption() ?></b>
		</span></td>
		<td<?php echo $fb_group_entries->comments->CellAttributes() ?>><span id="el_fb_group_entries_comments">
<input type="text" name="x_comments" id="x_comments" size="30" maxlength="245" value="<?php echo $fb_group_entries->comments->EditValue ?>"<?php echo $fb_group_entries->comments->EditAttributes() ?> />
</span><?php echo $fb_group_entries->comments->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($fb_group_entries->id_grupo->Visible) { // id_grupo ?>
	<tr id="r_id_grupo"<?php echo $fb_group_entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_group_entries_id_grupo">
		<b><?php echo $fb_group_entries->id_grupo->FldCaption() ?></b>
		</span></td>
		<td<?php echo $fb_group_entries->id_grupo->CellAttributes() ?>><span id="el_fb_group_entries_id_grupo">
<input type="text" name="x_id_grupo" id="x_id_grupo" size="30" maxlength="245" value="<?php echo $fb_group_entries->id_grupo->EditValue ?>"<?php echo $fb_group_entries->id_grupo->EditAttributes() ?> />
</span><?php echo $fb_group_entries->id_grupo->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($fb_group_entries->icon->Visible) { // icon ?>
	<tr id="r_icon"<?php echo $fb_group_entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_group_entries_icon">
		<b><?php echo $fb_group_entries->icon->FldCaption() ?></b>
		</span></td>
		<td<?php echo $fb_group_entries->icon->CellAttributes() ?>><span id="el_fb_group_entries_icon">
<input type="text" name="x_icon" id="x_icon" size="30" maxlength="245" value="<?php echo $fb_group_entries->icon->EditValue ?>"<?php echo $fb_group_entries->icon->EditAttributes() ?> />
</span><?php echo $fb_group_entries->icon->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($fb_group_entries->link->Visible) { // link ?>
	<tr id="r_link"<?php echo $fb_group_entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_group_entries_link">
		<b><?php echo $fb_group_entries->link->FldCaption() ?></b>
		</span></td>
		<td<?php echo $fb_group_entries->link->CellAttributes() ?>><span id="el_fb_group_entries_link">
<input type="text" name="x_link" id="x_link" size="30" maxlength="245" value="<?php echo $fb_group_entries->link->EditValue ?>"<?php echo $fb_group_entries->link->EditAttributes() ?> />
</span><?php echo $fb_group_entries->link->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($fb_group_entries->name->Visible) { // name ?>
	<tr id="r_name"<?php echo $fb_group_entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_group_entries_name">
		<b><?php echo $fb_group_entries->name->FldCaption() ?></b>
		</span></td>
		<td<?php echo $fb_group_entries->name->CellAttributes() ?>><span id="el_fb_group_entries_name">
<input type="text" name="x_name" id="x_name" size="30" maxlength="245" value="<?php echo $fb_group_entries->name->EditValue ?>"<?php echo $fb_group_entries->name->EditAttributes() ?> />
</span><?php echo $fb_group_entries->name->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($fb_group_entries->object_id->Visible) { // object_id ?>
	<tr id="r_object_id"<?php echo $fb_group_entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_group_entries_object_id">
		<b><?php echo $fb_group_entries->object_id->FldCaption() ?></b>
		</span></td>
		<td<?php echo $fb_group_entries->object_id->CellAttributes() ?>><span id="el_fb_group_entries_object_id">
<input type="text" name="x_object_id" id="x_object_id" size="30" maxlength="245" value="<?php echo $fb_group_entries->object_id->EditValue ?>"<?php echo $fb_group_entries->object_id->EditAttributes() ?> />
</span><?php echo $fb_group_entries->object_id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($fb_group_entries->picture->Visible) { // picture ?>
	<tr id="r_picture"<?php echo $fb_group_entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_group_entries_picture">
		<b><?php echo $fb_group_entries->picture->FldCaption() ?></b>
		</span></td>
		<td<?php echo $fb_group_entries->picture->CellAttributes() ?>><span id="el_fb_group_entries_picture">
<input type="text" name="x_picture" id="x_picture" size="30" maxlength="245" value="<?php echo $fb_group_entries->picture->EditValue ?>"<?php echo $fb_group_entries->picture->EditAttributes() ?> />
</span><?php echo $fb_group_entries->picture->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($fb_group_entries->properties->Visible) { // properties ?>
	<tr id="r_properties"<?php echo $fb_group_entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_group_entries_properties">
		<b><?php echo $fb_group_entries->properties->FldCaption() ?></b>
		</span></td>
		<td<?php echo $fb_group_entries->properties->CellAttributes() ?>><span id="el_fb_group_entries_properties">
<input type="text" name="x_properties" id="x_properties" size="30" maxlength="245" value="<?php echo $fb_group_entries->properties->EditValue ?>"<?php echo $fb_group_entries->properties->EditAttributes() ?> />
</span><?php echo $fb_group_entries->properties->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($fb_group_entries->message_tags->Visible) { // message_tags ?>
	<tr id="r_message_tags"<?php echo $fb_group_entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_group_entries_message_tags">
		<b><?php echo $fb_group_entries->message_tags->FldCaption() ?></b>
		</span></td>
		<td<?php echo $fb_group_entries->message_tags->CellAttributes() ?>><span id="el_fb_group_entries_message_tags">
<input type="text" name="x_message_tags" id="x_message_tags" size="30" maxlength="245" value="<?php echo $fb_group_entries->message_tags->EditValue ?>"<?php echo $fb_group_entries->message_tags->EditAttributes() ?> />
</span><?php echo $fb_group_entries->message_tags->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($fb_group_entries->caption->Visible) { // caption ?>
	<tr id="r_caption"<?php echo $fb_group_entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_group_entries_caption">
		<b><?php echo $fb_group_entries->caption->FldCaption() ?></b>
		</span></td>
		<td<?php echo $fb_group_entries->caption->CellAttributes() ?>><span id="el_fb_group_entries_caption">
<input type="text" name="x_caption" id="x_caption" size="30" maxlength="245" value="<?php echo $fb_group_entries->caption->EditValue ?>"<?php echo $fb_group_entries->caption->EditAttributes() ?> />
</span><?php echo $fb_group_entries->caption->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($fb_group_entries->description->Visible) { // description ?>
	<tr id="r_description"<?php echo $fb_group_entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_group_entries_description">
		<b><?php echo $fb_group_entries->description->FldCaption() ?></b>
		</span></td>
		<td<?php echo $fb_group_entries->description->CellAttributes() ?>><span id="el_fb_group_entries_description">
<input type="text" name="x_description" id="x_description" size="30" maxlength="245" value="<?php echo $fb_group_entries->description->EditValue ?>"<?php echo $fb_group_entries->description->EditAttributes() ?> />
</span><?php echo $fb_group_entries->description->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($fb_group_entries->tool_id->Visible) { // tool_id ?>
	<tr id="r_tool_id"<?php echo $fb_group_entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_group_entries_tool_id">
		<b><?php echo $fb_group_entries->tool_id->FldCaption() ?></b>
		</span></td>
		<td<?php echo $fb_group_entries->tool_id->CellAttributes() ?>><span id="el_fb_group_entries_tool_id">
<input type="text" name="x_tool_id" id="x_tool_id" size="30" value="<?php echo $fb_group_entries->tool_id->EditValue ?>"<?php echo $fb_group_entries->tool_id->EditAttributes() ?> />
</span><?php echo $fb_group_entries->tool_id->CustomMsg ?></td>
	</tr>
<?php } ?>
<?php if ($fb_group_entries->application->Visible) { // application ?>
	<tr id="r_application"<?php echo $fb_group_entries->RowAttributes() ?>>
		<td class="ewTableHeader"><span id="elh_fb_group_entries_application">
		<b><?php echo $fb_group_entries->application->FldCaption() ?></b>
		</span></td>
		<td<?php echo $fb_group_entries->application->CellAttributes() ?>><span id="el_fb_group_entries_application">
<input type="text" name="x_application" id="x_application" size="30" maxlength="245" value="<?php echo $fb_group_entries->application->EditValue ?>"<?php echo $fb_group_entries->application->EditAttributes() ?> />
</span><?php echo $fb_group_entries->application->CustomMsg ?></td>
	</tr>
<?php } ?>
</table>
</div>
<input type="submit" class="btn btn-large btn-success" name="btnAction" id="btnAction" value="<?php echo ew_BtnCaption($Language->Phrase("EditBtn")) ?>" />
</form>
<script type="text/javascript">
ffb_group_entriesedit.Init();
</script>
<?php
$fb_group_entries_edit->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<script type="text/javascript">

// Write your table-specific startup script here
// document.write("page loaded");

</script>
<?php include_once "footer.php" ?>
<?php
$fb_group_entries_edit->Page_Terminate();
?>
