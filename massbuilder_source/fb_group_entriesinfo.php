<?php

// Global variable for table object
$fb_group_entries = NULL;

//
// Table class for fb_group_entries
//
class cfb_group_entries extends cTable {
	var $idfb_posts;
	var $domain_id;
	var $record_time;
	var $data;
	var $fid;
	var $md5;
	var $body;
	var $titulo;
	var $created_time;
	var $actions;
	var $id;
	var $is_published;
	var $message;
	var $privacy;
	var $promotion_status;
	var $timeline_visibility;
	var $to;
	var $type;
	var $updated_time;
	var $from;
	var $comments;
	var $id_grupo;
	var $icon;
	var $link;
	var $name;
	var $object_id;
	var $picture;
	var $properties;
	var $message_tags;
	var $caption;
	var $description;
	var $tool_id;
	var $application;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'fb_group_entries';
		$this->TableName = 'fb_group_entries';
		$this->TableType = 'TABLE';
		$this->ExportAll = TRUE;
		$this->ExportPageBreakCount = 0; // Page break per every n record (PDF only)
		$this->ExportPageOrientation = "portrait"; // Page orientation (PDF only)
		$this->ExportPageSize = "a4"; // Page size (PDF only)
		$this->DetailAdd = FALSE; // Allow detail add
		$this->DetailEdit = FALSE; // Allow detail edit
		$this->GridAddRowCount = 5;
		$this->AllowAddDeleteRow = ew_AllowAddDeleteRow(); // Allow add/delete row
		$this->UserIDAllowSecurity = 0; // User ID Allow
		$this->BasicSearch = new cBasicSearch($this->TableVar);

		// idfb_posts
		$this->idfb_posts = new cField('fb_group_entries', 'fb_group_entries', 'x_idfb_posts', 'idfb_posts', '`idfb_posts`', '`idfb_posts`', 3, -1, FALSE, '`idfb_posts`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->idfb_posts->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['idfb_posts'] = &$this->idfb_posts;

		// domain_id
		$this->domain_id = new cField('fb_group_entries', 'fb_group_entries', 'x_domain_id', 'domain_id', '`domain_id`', '`domain_id`', 3, -1, FALSE, '`domain_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->domain_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['domain_id'] = &$this->domain_id;

		// record_time
		$this->record_time = new cField('fb_group_entries', 'fb_group_entries', 'x_record_time', 'record_time', '`record_time`', 'DATE_FORMAT(`record_time`, \'%Y/%m/%d %H:%i:%s\')', 135, 5, FALSE, '`record_time`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->record_time->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateYMD"));
		$this->fields['record_time'] = &$this->record_time;

		// data
		$this->data = new cField('fb_group_entries', 'fb_group_entries', 'x_data', 'data', '`data`', '`data`', 200, -1, FALSE, '`data`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['data'] = &$this->data;

		// fid
		$this->fid = new cField('fb_group_entries', 'fb_group_entries', 'x_fid', 'fid', '`fid`', '`fid`', 200, -1, FALSE, '`fid`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['fid'] = &$this->fid;

		// md5
		$this->md5 = new cField('fb_group_entries', 'fb_group_entries', 'x_md5', 'md5', '`md5`', '`md5`', 200, -1, FALSE, '`md5`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['md5'] = &$this->md5;

		// body
		$this->body = new cField('fb_group_entries', 'fb_group_entries', 'x_body', 'body', '`body`', '`body`', 200, -1, FALSE, '`body`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['body'] = &$this->body;

		// titulo
		$this->titulo = new cField('fb_group_entries', 'fb_group_entries', 'x_titulo', 'titulo', '`titulo`', '`titulo`', 200, -1, FALSE, '`titulo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['titulo'] = &$this->titulo;

		// created_time
		$this->created_time = new cField('fb_group_entries', 'fb_group_entries', 'x_created_time', 'created_time', '`created_time`', '`created_time`', 200, -1, FALSE, '`created_time`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['created_time'] = &$this->created_time;

		// actions
		$this->actions = new cField('fb_group_entries', 'fb_group_entries', 'x_actions', 'actions', '`actions`', '`actions`', 200, -1, FALSE, '`actions`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['actions'] = &$this->actions;

		// id
		$this->id = new cField('fb_group_entries', 'fb_group_entries', 'x_id', 'id', '`id`', '`id`', 200, -1, FALSE, '`id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['id'] = &$this->id;

		// is_published
		$this->is_published = new cField('fb_group_entries', 'fb_group_entries', 'x_is_published', 'is_published', '`is_published`', '`is_published`', 200, -1, FALSE, '`is_published`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['is_published'] = &$this->is_published;

		// message
		$this->message = new cField('fb_group_entries', 'fb_group_entries', 'x_message', 'message', '`message`', '`message`', 200, -1, FALSE, '`message`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['message'] = &$this->message;

		// privacy
		$this->privacy = new cField('fb_group_entries', 'fb_group_entries', 'x_privacy', 'privacy', '`privacy`', '`privacy`', 200, -1, FALSE, '`privacy`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['privacy'] = &$this->privacy;

		// promotion_status
		$this->promotion_status = new cField('fb_group_entries', 'fb_group_entries', 'x_promotion_status', 'promotion_status', '`promotion_status`', '`promotion_status`', 200, -1, FALSE, '`promotion_status`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['promotion_status'] = &$this->promotion_status;

		// timeline_visibility
		$this->timeline_visibility = new cField('fb_group_entries', 'fb_group_entries', 'x_timeline_visibility', 'timeline_visibility', '`timeline_visibility`', '`timeline_visibility`', 200, -1, FALSE, '`timeline_visibility`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['timeline_visibility'] = &$this->timeline_visibility;

		// to
		$this->to = new cField('fb_group_entries', 'fb_group_entries', 'x_to', 'to', '`to`', '`to`', 200, -1, FALSE, '`to`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['to'] = &$this->to;

		// type
		$this->type = new cField('fb_group_entries', 'fb_group_entries', 'x_type', 'type', '`type`', '`type`', 200, -1, FALSE, '`type`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['type'] = &$this->type;

		// updated_time
		$this->updated_time = new cField('fb_group_entries', 'fb_group_entries', 'x_updated_time', 'updated_time', '`updated_time`', '`updated_time`', 200, -1, FALSE, '`updated_time`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['updated_time'] = &$this->updated_time;

		// from
		$this->from = new cField('fb_group_entries', 'fb_group_entries', 'x_from', 'from', '`from`', '`from`', 200, -1, FALSE, '`from`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['from'] = &$this->from;

		// comments
		$this->comments = new cField('fb_group_entries', 'fb_group_entries', 'x_comments', 'comments', '`comments`', '`comments`', 200, -1, FALSE, '`comments`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['comments'] = &$this->comments;

		// id_grupo
		$this->id_grupo = new cField('fb_group_entries', 'fb_group_entries', 'x_id_grupo', 'id_grupo', '`id_grupo`', '`id_grupo`', 200, -1, FALSE, '`id_grupo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['id_grupo'] = &$this->id_grupo;

		// icon
		$this->icon = new cField('fb_group_entries', 'fb_group_entries', 'x_icon', 'icon', '`icon`', '`icon`', 200, -1, FALSE, '`icon`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['icon'] = &$this->icon;

		// link
		$this->link = new cField('fb_group_entries', 'fb_group_entries', 'x_link', 'link', '`link`', '`link`', 200, -1, FALSE, '`link`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['link'] = &$this->link;

		// name
		$this->name = new cField('fb_group_entries', 'fb_group_entries', 'x_name', 'name', '`name`', '`name`', 200, -1, FALSE, '`name`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['name'] = &$this->name;

		// object_id
		$this->object_id = new cField('fb_group_entries', 'fb_group_entries', 'x_object_id', 'object_id', '`object_id`', '`object_id`', 200, -1, FALSE, '`object_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['object_id'] = &$this->object_id;

		// picture
		$this->picture = new cField('fb_group_entries', 'fb_group_entries', 'x_picture', 'picture', '`picture`', '`picture`', 200, -1, FALSE, '`picture`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['picture'] = &$this->picture;

		// properties
		$this->properties = new cField('fb_group_entries', 'fb_group_entries', 'x_properties', 'properties', '`properties`', '`properties`', 200, -1, FALSE, '`properties`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['properties'] = &$this->properties;

		// message_tags
		$this->message_tags = new cField('fb_group_entries', 'fb_group_entries', 'x_message_tags', 'message_tags', '`message_tags`', '`message_tags`', 200, -1, FALSE, '`message_tags`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['message_tags'] = &$this->message_tags;

		// caption
		$this->caption = new cField('fb_group_entries', 'fb_group_entries', 'x_caption', 'caption', '`caption`', '`caption`', 200, -1, FALSE, '`caption`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['caption'] = &$this->caption;

		// description
		$this->description = new cField('fb_group_entries', 'fb_group_entries', 'x_description', 'description', '`description`', '`description`', 200, -1, FALSE, '`description`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['description'] = &$this->description;

		// tool_id
		$this->tool_id = new cField('fb_group_entries', 'fb_group_entries', 'x_tool_id', 'tool_id', '`tool_id`', '`tool_id`', 3, -1, FALSE, '`tool_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->tool_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['tool_id'] = &$this->tool_id;

		// application
		$this->application = new cField('fb_group_entries', 'fb_group_entries', 'x_application', 'application', '`application`', '`application`', 200, -1, FALSE, '`application`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['application'] = &$this->application;
	}

	// Single column sort
	function UpdateSort(&$ofld) {
		if ($this->CurrentOrder == $ofld->FldName) {
			$sSortField = $ofld->FldExpression;
			$sLastSort = $ofld->getSort();
			if ($this->CurrentOrderType == "ASC" || $this->CurrentOrderType == "DESC") {
				$sThisSort = $this->CurrentOrderType;
			} else {
				$sThisSort = ($sLastSort == "ASC") ? "DESC" : "ASC";
			}
			$ofld->setSort($sThisSort);
			$this->setSessionOrderBy($sSortField . " " . $sThisSort); // Save to Session
		} else {
			$ofld->setSort("");
		}
	}

	// Table level SQL
	function SqlFrom() { // From
		return "`fb_group_entries`";
	}

	function SqlSelect() { // Select
		return "SELECT * FROM " . $this->SqlFrom();
	}

	function SqlWhere() { // Where
		$sWhere = "";
		$this->TableFilter = "";
		ew_AddFilter($sWhere, $this->TableFilter);
		return $sWhere;
	}

	function SqlGroupBy() { // Group By
		return "";
	}

	function SqlHaving() { // Having
		return "";
	}

	function SqlOrderBy() { // Order By
		return "";
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

	// Get SQL
	function GetSQL($where, $orderby) {
		return ew_BuildSelectSql($this->SqlSelect(), $this->SqlWhere(),
			$this->SqlGroupBy(), $this->SqlHaving(), $this->SqlOrderBy(),
			$where, $orderby);
	}

	// Table SQL
	function SQL() {
		$sFilter = $this->CurrentFilter;
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->SqlSelect(), $this->SqlWhere(),
			$this->SqlGroupBy(), $this->SqlHaving(), $this->SqlOrderBy(),
			$sFilter, $sSort);
	}

	// Table SQL with List page filter
	function SelectSQL() {
		$sFilter = $this->getSessionWhere();
		ew_AddFilter($sFilter, $this->CurrentFilter);
		$sFilter = $this->ApplyUserIDFilters($sFilter);
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql($this->SqlSelect(), $this->SqlWhere(), $this->SqlGroupBy(),
			$this->SqlHaving(), $this->SqlOrderBy(), $sFilter, $sSort);
	}

	// Get ORDER BY clause
	function GetOrderBy() {
		$sSort = $this->getSessionOrderBy();
		return ew_BuildSelectSql("", "", "", "", $this->SqlOrderBy(), "", $sSort);
	}

	// Try to get record count
	function TryGetRecordCount($sSql) {
		global $conn;
		$cnt = -1;
		if ($this->TableType == 'TABLE' || $this->TableType == 'VIEW') {
			$sSql = "SELECT COUNT(*) FROM" . substr($sSql, 13);
			$sOrderBy = $this->GetOrderBy();
			if (substr($sSql, strlen($sOrderBy) * -1) == $sOrderBy)
				$sSql = substr($sSql, 0, strlen($sSql) - strlen($sOrderBy)); // Remove ORDER BY clause
		} else {
			$sSql = "SELECT COUNT(*) FROM (" . $sSql . ") EW_COUNT_TABLE";
		}
		if ($rs = $conn->Execute($sSql)) {
			if (!$rs->EOF && $rs->FieldCount() > 0) {
				$cnt = $rs->fields[0];
				$rs->Close();
			}
		}
		return intval($cnt);
	}

	// Get record count based on filter (for detail record count in master table pages)
	function LoadRecordCount($sFilter) {
		$origFilter = $this->CurrentFilter;
		$this->CurrentFilter = $sFilter;
		$this->Recordset_Selecting($this->CurrentFilter);

		//$sSql = $this->SQL();
		$sSql = $this->GetSQL($this->CurrentFilter, "");
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $this->LoadRs($this->CurrentFilter)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Get record count (for current List page)
	function SelectRecordCount() {
		global $conn;
		$origFilter = $this->CurrentFilter;
		$this->Recordset_Selecting($this->CurrentFilter);
		$sSql = $this->SelectSQL();
		$cnt = $this->TryGetRecordCount($sSql);
		if ($cnt == -1) {
			if ($rs = $conn->Execute($sSql)) {
				$cnt = $rs->RecordCount();
				$rs->Close();
			}
		}
		$this->CurrentFilter = $origFilter;
		return intval($cnt);
	}

	// Update Table
	var $UpdateTable = "`fb_group_entries`";

	// INSERT statement
	function InsertSQL(&$rs) {
		global $conn;
		$names = "";
		$values = "";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]))
				continue;
			$names .= $this->fields[$name]->FldExpression . ",";
			$values .= ew_QuotedValue($value, $this->fields[$name]->FldDataType) . ",";
		}
		while (substr($names, -1) == ",")
			$names = substr($names, 0, -1);
		while (substr($values, -1) == ",")
			$values = substr($values, 0, -1);
		return "INSERT INTO " . $this->UpdateTable . " ($names) VALUES ($values)";
	}

	// Insert
	function Insert(&$rs) {
		global $conn;
		return $conn->Execute($this->InsertSQL($rs));
	}

	// UPDATE statement
	function UpdateSQL(&$rs, $where = "") {
		$sql = "UPDATE " . $this->UpdateTable . " SET ";
		foreach ($rs as $name => $value) {
			if (!isset($this->fields[$name]))
				continue;
			$sql .= $this->fields[$name]->FldExpression . "=";
			$sql .= ew_QuotedValue($value, $this->fields[$name]->FldDataType) . ",";
		}
		while (substr($sql, -1) == ",")
			$sql = substr($sql, 0, -1);
		$filter = $this->CurrentFilter;
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " WHERE " . $filter;
		return $sql;
	}

	// Update
	function Update(&$rs, $where = "") {
		global $conn;
		return $conn->Execute($this->UpdateSQL($rs, $where));
	}

	// DELETE statement
	function DeleteSQL(&$rs, $where = "") {
		$sql = "DELETE FROM " . $this->UpdateTable . " WHERE ";
		if ($rs) {
			$sql .= ew_QuotedName('idfb_posts') . '=' . ew_QuotedValue($rs['idfb_posts'], $this->idfb_posts->FldDataType) . ' AND ';
		}
		if (substr($sql, -5) == " AND ") $sql = substr($sql, 0, -5);
		$filter = $this->CurrentFilter;
		ew_AddFilter($filter, $where);
		if ($filter <> "")	$sql .= " AND " . $filter;
		return $sql;
	}

	// Delete
	function Delete(&$rs, $where = "") {
		global $conn;
		return $conn->Execute($this->DeleteSQL($rs, $where));
	}

	// Key filter WHERE clause
	function SqlKeyFilter() {
		return "`idfb_posts` = @idfb_posts@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->idfb_posts->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@idfb_posts@", ew_AdjustSql($this->idfb_posts->CurrentValue), $sKeyFilter); // Replace key value
		return $sKeyFilter;
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
			return $this->james_url( "fb_group_entrieslist.php" );
		}
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

	// List URL
	function GetListUrl() {
		return $this->james_url( "fb_group_entrieslist.php" );
	}

	// View URL
	function GetViewUrl() {
		return $this->KeyUrl($this->james_url("fb_group_entriesview.php"), $this->UrlParm());
	}

	// Add URL
	function GetAddUrl() {
		return $this->james_url("fb_group_entriesadd.php");
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl($this->james_url("fb_group_entriesedit.php"), $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl($this->james_url(ew_CurrentPage()), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl($this->james_url("fb_group_entriesadd.php"), $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl( $this->james_url( ew_CurrentPage() ), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl($this->james_url("fb_group_entriesdelete.php"), $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->idfb_posts->CurrentValue)) {
			$sUrl .= "idfb_posts=" . urlencode($this->idfb_posts->CurrentValue);
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
			$arKeys[] = @$_GET["idfb_posts"]; // idfb_posts

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
			$this->idfb_posts->CurrentValue = $key;
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

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
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

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
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

		// Call Row Rendered event
		$this->Row_Rendered();
	}

	// Aggregate list row values
	function AggregateListRowValues() {
	}

	// Aggregate list row (for rendering)
	function AggregateListRow() {
	}

	// Export data in HTML/CSV/Word/Excel/Email/PDF format
	function ExportDocument(&$Doc, &$Recordset, $StartRec, $StopRec, $ExportPageType = "") {
		if (!$Recordset || !$Doc)
			return;

		// Write header
		$Doc->ExportTableHeader();
		if ($Doc->Horizontal) { // Horizontal format, write header
			$Doc->BeginExportRow();
			if ($ExportPageType == "view") {
				if ($this->idfb_posts->Exportable) $Doc->ExportCaption($this->idfb_posts);
				if ($this->domain_id->Exportable) $Doc->ExportCaption($this->domain_id);
				if ($this->record_time->Exportable) $Doc->ExportCaption($this->record_time);
				if ($this->data->Exportable) $Doc->ExportCaption($this->data);
				if ($this->fid->Exportable) $Doc->ExportCaption($this->fid);
				if ($this->md5->Exportable) $Doc->ExportCaption($this->md5);
				if ($this->body->Exportable) $Doc->ExportCaption($this->body);
				if ($this->titulo->Exportable) $Doc->ExportCaption($this->titulo);
				if ($this->created_time->Exportable) $Doc->ExportCaption($this->created_time);
				if ($this->actions->Exportable) $Doc->ExportCaption($this->actions);
				if ($this->id->Exportable) $Doc->ExportCaption($this->id);
				if ($this->is_published->Exportable) $Doc->ExportCaption($this->is_published);
				if ($this->message->Exportable) $Doc->ExportCaption($this->message);
				if ($this->privacy->Exportable) $Doc->ExportCaption($this->privacy);
				if ($this->promotion_status->Exportable) $Doc->ExportCaption($this->promotion_status);
				if ($this->timeline_visibility->Exportable) $Doc->ExportCaption($this->timeline_visibility);
				if ($this->to->Exportable) $Doc->ExportCaption($this->to);
				if ($this->type->Exportable) $Doc->ExportCaption($this->type);
				if ($this->updated_time->Exportable) $Doc->ExportCaption($this->updated_time);
				if ($this->from->Exportable) $Doc->ExportCaption($this->from);
				if ($this->comments->Exportable) $Doc->ExportCaption($this->comments);
				if ($this->id_grupo->Exportable) $Doc->ExportCaption($this->id_grupo);
				if ($this->icon->Exportable) $Doc->ExportCaption($this->icon);
				if ($this->link->Exportable) $Doc->ExportCaption($this->link);
				if ($this->name->Exportable) $Doc->ExportCaption($this->name);
				if ($this->object_id->Exportable) $Doc->ExportCaption($this->object_id);
				if ($this->picture->Exportable) $Doc->ExportCaption($this->picture);
				if ($this->properties->Exportable) $Doc->ExportCaption($this->properties);
				if ($this->message_tags->Exportable) $Doc->ExportCaption($this->message_tags);
				if ($this->caption->Exportable) $Doc->ExportCaption($this->caption);
				if ($this->description->Exportable) $Doc->ExportCaption($this->description);
				if ($this->tool_id->Exportable) $Doc->ExportCaption($this->tool_id);
				if ($this->application->Exportable) $Doc->ExportCaption($this->application);
			} else {
				if ($this->idfb_posts->Exportable) $Doc->ExportCaption($this->idfb_posts);
				if ($this->domain_id->Exportable) $Doc->ExportCaption($this->domain_id);
				if ($this->record_time->Exportable) $Doc->ExportCaption($this->record_time);
				if ($this->data->Exportable) $Doc->ExportCaption($this->data);
				if ($this->fid->Exportable) $Doc->ExportCaption($this->fid);
				if ($this->md5->Exportable) $Doc->ExportCaption($this->md5);
				if ($this->body->Exportable) $Doc->ExportCaption($this->body);
				if ($this->titulo->Exportable) $Doc->ExportCaption($this->titulo);
				if ($this->created_time->Exportable) $Doc->ExportCaption($this->created_time);
				if ($this->actions->Exportable) $Doc->ExportCaption($this->actions);
				if ($this->id->Exportable) $Doc->ExportCaption($this->id);
				if ($this->is_published->Exportable) $Doc->ExportCaption($this->is_published);
				if ($this->message->Exportable) $Doc->ExportCaption($this->message);
				if ($this->privacy->Exportable) $Doc->ExportCaption($this->privacy);
				if ($this->promotion_status->Exportable) $Doc->ExportCaption($this->promotion_status);
				if ($this->timeline_visibility->Exportable) $Doc->ExportCaption($this->timeline_visibility);
				if ($this->to->Exportable) $Doc->ExportCaption($this->to);
				if ($this->type->Exportable) $Doc->ExportCaption($this->type);
				if ($this->updated_time->Exportable) $Doc->ExportCaption($this->updated_time);
				if ($this->from->Exportable) $Doc->ExportCaption($this->from);
				if ($this->comments->Exportable) $Doc->ExportCaption($this->comments);
				if ($this->id_grupo->Exportable) $Doc->ExportCaption($this->id_grupo);
				if ($this->icon->Exportable) $Doc->ExportCaption($this->icon);
				if ($this->link->Exportable) $Doc->ExportCaption($this->link);
				if ($this->name->Exportable) $Doc->ExportCaption($this->name);
				if ($this->object_id->Exportable) $Doc->ExportCaption($this->object_id);
				if ($this->picture->Exportable) $Doc->ExportCaption($this->picture);
				if ($this->properties->Exportable) $Doc->ExportCaption($this->properties);
				if ($this->message_tags->Exportable) $Doc->ExportCaption($this->message_tags);
				if ($this->caption->Exportable) $Doc->ExportCaption($this->caption);
				if ($this->description->Exportable) $Doc->ExportCaption($this->description);
				if ($this->tool_id->Exportable) $Doc->ExportCaption($this->tool_id);
				if ($this->application->Exportable) $Doc->ExportCaption($this->application);
			}
			$Doc->EndExportRow();
		}

		// Move to first record
		$RecCnt = $StartRec - 1;
		if (!$Recordset->EOF) {
			$Recordset->MoveFirst();
			if ($StartRec > 1)
				$Recordset->Move($StartRec - 1);
		}
		while (!$Recordset->EOF && $RecCnt < $StopRec) {
			$RecCnt++;
			if (intval($RecCnt) >= intval($StartRec)) {
				$RowCnt = intval($RecCnt) - intval($StartRec) + 1;

				// Page break
				if ($this->ExportPageBreakCount > 0) {
					if ($RowCnt > 1 && ($RowCnt - 1) % $this->ExportPageBreakCount == 0)
						$Doc->ExportPageBreak();
				}
				$this->LoadListRowValues($Recordset);

				// Render row
				$this->RowType = EW_ROWTYPE_VIEW; // Render view
				$this->ResetAttrs();
				$this->RenderListRow();
				$Doc->BeginExportRow($RowCnt); // Allow CSS styles if enabled
				if ($ExportPageType == "view") {
					if ($this->idfb_posts->Exportable) $Doc->ExportField($this->idfb_posts);
					if ($this->domain_id->Exportable) $Doc->ExportField($this->domain_id);
					if ($this->record_time->Exportable) $Doc->ExportField($this->record_time);
					if ($this->data->Exportable) $Doc->ExportField($this->data);
					if ($this->fid->Exportable) $Doc->ExportField($this->fid);
					if ($this->md5->Exportable) $Doc->ExportField($this->md5);
					if ($this->body->Exportable) $Doc->ExportField($this->body);
					if ($this->titulo->Exportable) $Doc->ExportField($this->titulo);
					if ($this->created_time->Exportable) $Doc->ExportField($this->created_time);
					if ($this->actions->Exportable) $Doc->ExportField($this->actions);
					if ($this->id->Exportable) $Doc->ExportField($this->id);
					if ($this->is_published->Exportable) $Doc->ExportField($this->is_published);
					if ($this->message->Exportable) $Doc->ExportField($this->message);
					if ($this->privacy->Exportable) $Doc->ExportField($this->privacy);
					if ($this->promotion_status->Exportable) $Doc->ExportField($this->promotion_status);
					if ($this->timeline_visibility->Exportable) $Doc->ExportField($this->timeline_visibility);
					if ($this->to->Exportable) $Doc->ExportField($this->to);
					if ($this->type->Exportable) $Doc->ExportField($this->type);
					if ($this->updated_time->Exportable) $Doc->ExportField($this->updated_time);
					if ($this->from->Exportable) $Doc->ExportField($this->from);
					if ($this->comments->Exportable) $Doc->ExportField($this->comments);
					if ($this->id_grupo->Exportable) $Doc->ExportField($this->id_grupo);
					if ($this->icon->Exportable) $Doc->ExportField($this->icon);
					if ($this->link->Exportable) $Doc->ExportField($this->link);
					if ($this->name->Exportable) $Doc->ExportField($this->name);
					if ($this->object_id->Exportable) $Doc->ExportField($this->object_id);
					if ($this->picture->Exportable) $Doc->ExportField($this->picture);
					if ($this->properties->Exportable) $Doc->ExportField($this->properties);
					if ($this->message_tags->Exportable) $Doc->ExportField($this->message_tags);
					if ($this->caption->Exportable) $Doc->ExportField($this->caption);
					if ($this->description->Exportable) $Doc->ExportField($this->description);
					if ($this->tool_id->Exportable) $Doc->ExportField($this->tool_id);
					if ($this->application->Exportable) $Doc->ExportField($this->application);
				} else {
					if ($this->idfb_posts->Exportable) $Doc->ExportField($this->idfb_posts);
					if ($this->domain_id->Exportable) $Doc->ExportField($this->domain_id);
					if ($this->record_time->Exportable) $Doc->ExportField($this->record_time);
					if ($this->data->Exportable) $Doc->ExportField($this->data);
					if ($this->fid->Exportable) $Doc->ExportField($this->fid);
					if ($this->md5->Exportable) $Doc->ExportField($this->md5);
					if ($this->body->Exportable) $Doc->ExportField($this->body);
					if ($this->titulo->Exportable) $Doc->ExportField($this->titulo);
					if ($this->created_time->Exportable) $Doc->ExportField($this->created_time);
					if ($this->actions->Exportable) $Doc->ExportField($this->actions);
					if ($this->id->Exportable) $Doc->ExportField($this->id);
					if ($this->is_published->Exportable) $Doc->ExportField($this->is_published);
					if ($this->message->Exportable) $Doc->ExportField($this->message);
					if ($this->privacy->Exportable) $Doc->ExportField($this->privacy);
					if ($this->promotion_status->Exportable) $Doc->ExportField($this->promotion_status);
					if ($this->timeline_visibility->Exportable) $Doc->ExportField($this->timeline_visibility);
					if ($this->to->Exportable) $Doc->ExportField($this->to);
					if ($this->type->Exportable) $Doc->ExportField($this->type);
					if ($this->updated_time->Exportable) $Doc->ExportField($this->updated_time);
					if ($this->from->Exportable) $Doc->ExportField($this->from);
					if ($this->comments->Exportable) $Doc->ExportField($this->comments);
					if ($this->id_grupo->Exportable) $Doc->ExportField($this->id_grupo);
					if ($this->icon->Exportable) $Doc->ExportField($this->icon);
					if ($this->link->Exportable) $Doc->ExportField($this->link);
					if ($this->name->Exportable) $Doc->ExportField($this->name);
					if ($this->object_id->Exportable) $Doc->ExportField($this->object_id);
					if ($this->picture->Exportable) $Doc->ExportField($this->picture);
					if ($this->properties->Exportable) $Doc->ExportField($this->properties);
					if ($this->message_tags->Exportable) $Doc->ExportField($this->message_tags);
					if ($this->caption->Exportable) $Doc->ExportField($this->caption);
					if ($this->description->Exportable) $Doc->ExportField($this->description);
					if ($this->tool_id->Exportable) $Doc->ExportField($this->tool_id);
					if ($this->application->Exportable) $Doc->ExportField($this->application);
				}
				$Doc->EndExportRow();
			}
			$Recordset->MoveNext();
		}
		$Doc->ExportTableFooter();
	}

	// Table level events
	// Recordset Selecting event
	function Recordset_Selecting(&$filter) {

		// Enter your code here	
	}

	// Recordset Selected event
	function Recordset_Selected(&$rs) {

		//echo "Recordset Selected";
	}

	// Recordset Search Validated event
	function Recordset_SearchValidated() {

		// Example:
		//$this->MyField1->AdvancedSearch->SearchValue = "your search criteria"; // Search value

	}

	// Recordset Searching event
	function Recordset_Searching(&$filter) {

		// Enter your code here	
	}

	// Row_Selecting event
	function Row_Selecting(&$filter) {

		// Enter your code here	
	}

	// Row Selected event
	function Row_Selected(&$rs) {

		//echo "Row Selected";
	}

	// Row Inserting event
	function Row_Inserting($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Inserted event
	function Row_Inserted($rsold, &$rsnew) {

		//echo "Row Inserted"
	}

	// Row Updating event
	function Row_Updating($rsold, &$rsnew) {

		// Enter your code here
		// To cancel, set return value to FALSE

		return TRUE;
	}

	// Row Updated event
	function Row_Updated($rsold, &$rsnew) {

		//echo "Row Updated";
	}

	// Row Update Conflict event
	function Row_UpdateConflict($rsold, &$rsnew) {

		// Enter your code here
		// To ignore conflict, set return value to FALSE

		return TRUE;
	}

	// Row Deleting event
	function Row_Deleting(&$rs) {

		// Enter your code here
		// To cancel, set return value to False

		return TRUE;
	}

	// Row Deleted event
	function Row_Deleted(&$rs) {

		//echo "Row Deleted";
	}

	// Email Sending event
	function Email_Sending(&$Email, &$Args) {

		//var_dump($Email); var_dump($Args); exit();
		return TRUE;
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
