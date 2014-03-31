<?php

// Global variable for table object
$entries = NULL;

//
// Table class for entries
//
class centries extends cTable {
	var $identries;
	var $domain_id;
	var $hash_content;
	var $fuente;
	var $published;
	var $updated;
	var $categorias;
	var $titulo;
	var $contenido;
	var $id;
	var $islive;
	var $thumbnail;
	var $reqdate;
	var $author;
	var $trans_en;
	var $trans_es;
	var $trans_fr;
	var $trans_it;
	var $fid;
	var $fmd5;
	var $tool_id;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'entries';
		$this->TableName = 'entries';
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

		// identries
		$this->identries = new cField('entries', 'entries', 'x_identries', 'identries', '`identries`', '`identries`', 3, -1, FALSE, '`identries`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->identries->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['identries'] = &$this->identries;

		// domain_id
		$this->domain_id = new cField('entries', 'entries', 'x_domain_id', 'domain_id', '`domain_id`', '`domain_id`', 3, -1, FALSE, '`domain_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->domain_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['domain_id'] = &$this->domain_id;

		// hash_content
		$this->hash_content = new cField('entries', 'entries', 'x_hash_content', 'hash_content', '`hash_content`', '`hash_content`', 200, -1, FALSE, '`hash_content`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['hash_content'] = &$this->hash_content;

		// fuente
		$this->fuente = new cField('entries', 'entries', 'x_fuente', 'fuente', '`fuente`', '`fuente`', 200, -1, FALSE, '`fuente`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['fuente'] = &$this->fuente;

		// published
		$this->published = new cField('entries', 'entries', 'x_published', 'published', '`published`', 'DATE_FORMAT(`published`, \'%Y/%m/%d %H:%i:%s\')', 135, 5, FALSE, '`published`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->published->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateYMD"));
		$this->fields['published'] = &$this->published;

		// updated
		$this->updated = new cField('entries', 'entries', 'x_updated', 'updated', '`updated`', 'DATE_FORMAT(`updated`, \'%Y/%m/%d %H:%i:%s\')', 135, 5, FALSE, '`updated`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->updated->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateYMD"));
		$this->fields['updated'] = &$this->updated;

		// categorias
		$this->categorias = new cField('entries', 'entries', 'x_categorias', 'categorias', '`categorias`', '`categorias`', 201, -1, FALSE, '`categorias`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['categorias'] = &$this->categorias;

		// titulo
		$this->titulo = new cField('entries', 'entries', 'x_titulo', 'titulo', '`titulo`', '`titulo`', 200, -1, FALSE, '`titulo`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['titulo'] = &$this->titulo;

		// contenido
		$this->contenido = new cField('entries', 'entries', 'x_contenido', 'contenido', '`contenido`', '`contenido`', 201, -1, FALSE, '`contenido`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['contenido'] = &$this->contenido;

		// id
		$this->id = new cField('entries', 'entries', 'x_id', 'id', '`id`', '`id`', 20, -1, FALSE, '`id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id'] = &$this->id;

		// islive
		$this->islive = new cField('entries', 'entries', 'x_islive', 'islive', '`islive`', '`islive`', 200, -1, FALSE, '`islive`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['islive'] = &$this->islive;

		// thumbnail
		$this->thumbnail = new cField('entries', 'entries', 'x_thumbnail', 'thumbnail', '`thumbnail`', '`thumbnail`', 200, -1, FALSE, '`thumbnail`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['thumbnail'] = &$this->thumbnail;

		// reqdate
		$this->reqdate = new cField('entries', 'entries', 'x_reqdate', 'reqdate', '`reqdate`', 'DATE_FORMAT(`reqdate`, \'%Y/%m/%d %H:%i:%s\')', 135, 5, FALSE, '`reqdate`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->reqdate->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateYMD"));
		$this->fields['reqdate'] = &$this->reqdate;

		// author
		$this->author = new cField('entries', 'entries', 'x_author', 'author', '`author`', '`author`', 200, -1, FALSE, '`author`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['author'] = &$this->author;

		// trans_en
		$this->trans_en = new cField('entries', 'entries', 'x_trans_en', 'trans_en', '`trans_en`', '`trans_en`', 200, -1, FALSE, '`trans_en`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['trans_en'] = &$this->trans_en;

		// trans_es
		$this->trans_es = new cField('entries', 'entries', 'x_trans_es', 'trans_es', '`trans_es`', '`trans_es`', 200, -1, FALSE, '`trans_es`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['trans_es'] = &$this->trans_es;

		// trans_fr
		$this->trans_fr = new cField('entries', 'entries', 'x_trans_fr', 'trans_fr', '`trans_fr`', '`trans_fr`', 200, -1, FALSE, '`trans_fr`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['trans_fr'] = &$this->trans_fr;

		// trans_it
		$this->trans_it = new cField('entries', 'entries', 'x_trans_it', 'trans_it', '`trans_it`', '`trans_it`', 200, -1, FALSE, '`trans_it`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['trans_it'] = &$this->trans_it;

		// fid
		$this->fid = new cField('entries', 'entries', 'x_fid', 'fid', '`fid`', '`fid`', 200, -1, FALSE, '`fid`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['fid'] = &$this->fid;

		// fmd5
		$this->fmd5 = new cField('entries', 'entries', 'x_fmd5', 'fmd5', '`fmd5`', '`fmd5`', 200, -1, FALSE, '`fmd5`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['fmd5'] = &$this->fmd5;

		// tool_id
		$this->tool_id = new cField('entries', 'entries', 'x_tool_id', 'tool_id', '`tool_id`', '`tool_id`', 200, -1, FALSE, '`tool_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['tool_id'] = &$this->tool_id;
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

	// Current master table name
	function getCurrentMasterTable() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_MASTER_TABLE];
	}

	function setCurrentMasterTable($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_MASTER_TABLE] = $v;
	}

	// Session master WHERE clause
	function GetMasterFilter() {

		// Master filter
		$sMasterFilter = "";
		if ($this->getCurrentMasterTable() == "domains") {
			if ($this->domain_id->getSessionValue() <> "")
				$sMasterFilter .= "`id_domains`=" . ew_QuotedValue($this->domain_id->getSessionValue(), EW_DATATYPE_NUMBER);
			else
				return "";
		}
		return $sMasterFilter;
	}

	// Session detail WHERE clause
	function GetDetailFilter() {

		// Detail filter
		$sDetailFilter = "";
		if ($this->getCurrentMasterTable() == "domains") {
			if ($this->domain_id->getSessionValue() <> "")
				$sDetailFilter .= "`domain_id`=" . ew_QuotedValue($this->domain_id->getSessionValue(), EW_DATATYPE_NUMBER);
			else
				return "";
		}
		return $sDetailFilter;
	}

	// Master filter
	function SqlMasterFilter_domains() {
		return "`id_domains`=@id_domains@";
	}

	// Detail filter
	function SqlDetailFilter_domains() {
		return "`domain_id`=@domain_id@";
	}

	// Table level SQL
	function SqlFrom() { // From
		return "`entries`";
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
	var $UpdateTable = "`entries`";

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
			$sql .= ew_QuotedName('identries') . '=' . ew_QuotedValue($rs['identries'], $this->identries->FldDataType) . ' AND ';
			$sql .= ew_QuotedName('id') . '=' . ew_QuotedValue($rs['id'], $this->id->FldDataType) . ' AND ';
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
		return "`identries` = @identries@ AND `id` = @id@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->identries->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@identries@", ew_AdjustSql($this->identries->CurrentValue), $sKeyFilter); // Replace key value
		if (!is_numeric($this->id->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@id@", ew_AdjustSql($this->id->CurrentValue), $sKeyFilter); // Replace key value
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
			return $this->james_url( "entrieslist.php" );
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
		return $this->james_url( "entrieslist.php" );
	}

	// View URL
	function GetViewUrl() {
		return $this->KeyUrl($this->james_url("entriesview.php"), $this->UrlParm());
	}

	// Add URL
	function GetAddUrl() {
		return $this->james_url("entriesadd.php");
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl($this->james_url("entriesedit.php"), $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl($this->james_url(ew_CurrentPage()), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl($this->james_url("entriesadd.php"), $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl( $this->james_url( ew_CurrentPage() ), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl($this->james_url("entriesdelete.php"), $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->identries->CurrentValue)) {
			$sUrl .= "identries=" . urlencode($this->identries->CurrentValue);
		} else {
			return "javascript:alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		if (!is_null($this->id->CurrentValue)) {
			$sUrl .= "&id=" . urlencode($this->id->CurrentValue);
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
			for ($i = 0; $i < $cnt; $i++)
				$arKeys[$i] = explode($EW_COMPOSITE_KEY_SEPARATOR, $arKeys[$i]);
		} elseif (isset($_GET["key_m"])) {
			$arKeys = ew_StripSlashes($_GET["key_m"]);
			$cnt = count($arKeys);
			for ($i = 0; $i < $cnt; $i++)
				$arKeys[$i] = explode($EW_COMPOSITE_KEY_SEPARATOR, $arKeys[$i]);
		} elseif (isset($_GET)) {
			$arKey[] = @$_GET["identries"]; // identries
			$arKey[] = @$_GET["id"]; // id
			$arKeys[] = $arKey;

			//return $arKeys; // do not return yet, so the values will also be checked by the following code
		}

		// check keys
		$ar = array();
		foreach ($arKeys as $key) {
			if (!is_array($key) || count($key) <> 2)
				continue; // just skip so other keys will still work
			if (!is_numeric($key[0])) // identries
				continue;
			if (!is_numeric($key[1])) // id
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
			$this->identries->CurrentValue = $key[0];
			$this->id->CurrentValue = $key[1];
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

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
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
				if ($this->identries->Exportable) $Doc->ExportCaption($this->identries);
				if ($this->domain_id->Exportable) $Doc->ExportCaption($this->domain_id);
				if ($this->hash_content->Exportable) $Doc->ExportCaption($this->hash_content);
				if ($this->fuente->Exportable) $Doc->ExportCaption($this->fuente);
				if ($this->published->Exportable) $Doc->ExportCaption($this->published);
				if ($this->updated->Exportable) $Doc->ExportCaption($this->updated);
				if ($this->categorias->Exportable) $Doc->ExportCaption($this->categorias);
				if ($this->titulo->Exportable) $Doc->ExportCaption($this->titulo);
				if ($this->contenido->Exportable) $Doc->ExportCaption($this->contenido);
				if ($this->id->Exportable) $Doc->ExportCaption($this->id);
				if ($this->islive->Exportable) $Doc->ExportCaption($this->islive);
				if ($this->thumbnail->Exportable) $Doc->ExportCaption($this->thumbnail);
				if ($this->reqdate->Exportable) $Doc->ExportCaption($this->reqdate);
				if ($this->author->Exportable) $Doc->ExportCaption($this->author);
				if ($this->trans_en->Exportable) $Doc->ExportCaption($this->trans_en);
				if ($this->trans_es->Exportable) $Doc->ExportCaption($this->trans_es);
				if ($this->trans_fr->Exportable) $Doc->ExportCaption($this->trans_fr);
				if ($this->trans_it->Exportable) $Doc->ExportCaption($this->trans_it);
				if ($this->fid->Exportable) $Doc->ExportCaption($this->fid);
				if ($this->fmd5->Exportable) $Doc->ExportCaption($this->fmd5);
				if ($this->tool_id->Exportable) $Doc->ExportCaption($this->tool_id);
			} else {
				if ($this->identries->Exportable) $Doc->ExportCaption($this->identries);
				if ($this->domain_id->Exportable) $Doc->ExportCaption($this->domain_id);
				if ($this->hash_content->Exportable) $Doc->ExportCaption($this->hash_content);
				if ($this->fuente->Exportable) $Doc->ExportCaption($this->fuente);
				if ($this->published->Exportable) $Doc->ExportCaption($this->published);
				if ($this->updated->Exportable) $Doc->ExportCaption($this->updated);
				if ($this->categorias->Exportable) $Doc->ExportCaption($this->categorias);
				if ($this->titulo->Exportable) $Doc->ExportCaption($this->titulo);
				if ($this->id->Exportable) $Doc->ExportCaption($this->id);
				if ($this->islive->Exportable) $Doc->ExportCaption($this->islive);
				if ($this->thumbnail->Exportable) $Doc->ExportCaption($this->thumbnail);
				if ($this->reqdate->Exportable) $Doc->ExportCaption($this->reqdate);
				if ($this->author->Exportable) $Doc->ExportCaption($this->author);
				if ($this->trans_en->Exportable) $Doc->ExportCaption($this->trans_en);
				if ($this->trans_es->Exportable) $Doc->ExportCaption($this->trans_es);
				if ($this->trans_fr->Exportable) $Doc->ExportCaption($this->trans_fr);
				if ($this->trans_it->Exportable) $Doc->ExportCaption($this->trans_it);
				if ($this->fid->Exportable) $Doc->ExportCaption($this->fid);
				if ($this->fmd5->Exportable) $Doc->ExportCaption($this->fmd5);
				if ($this->tool_id->Exportable) $Doc->ExportCaption($this->tool_id);
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
					if ($this->identries->Exportable) $Doc->ExportField($this->identries);
					if ($this->domain_id->Exportable) $Doc->ExportField($this->domain_id);
					if ($this->hash_content->Exportable) $Doc->ExportField($this->hash_content);
					if ($this->fuente->Exportable) $Doc->ExportField($this->fuente);
					if ($this->published->Exportable) $Doc->ExportField($this->published);
					if ($this->updated->Exportable) $Doc->ExportField($this->updated);
					if ($this->categorias->Exportable) $Doc->ExportField($this->categorias);
					if ($this->titulo->Exportable) $Doc->ExportField($this->titulo);
					if ($this->contenido->Exportable) $Doc->ExportField($this->contenido);
					if ($this->id->Exportable) $Doc->ExportField($this->id);
					if ($this->islive->Exportable) $Doc->ExportField($this->islive);
					if ($this->thumbnail->Exportable) $Doc->ExportField($this->thumbnail);
					if ($this->reqdate->Exportable) $Doc->ExportField($this->reqdate);
					if ($this->author->Exportable) $Doc->ExportField($this->author);
					if ($this->trans_en->Exportable) $Doc->ExportField($this->trans_en);
					if ($this->trans_es->Exportable) $Doc->ExportField($this->trans_es);
					if ($this->trans_fr->Exportable) $Doc->ExportField($this->trans_fr);
					if ($this->trans_it->Exportable) $Doc->ExportField($this->trans_it);
					if ($this->fid->Exportable) $Doc->ExportField($this->fid);
					if ($this->fmd5->Exportable) $Doc->ExportField($this->fmd5);
					if ($this->tool_id->Exportable) $Doc->ExportField($this->tool_id);
				} else {
					if ($this->identries->Exportable) $Doc->ExportField($this->identries);
					if ($this->domain_id->Exportable) $Doc->ExportField($this->domain_id);
					if ($this->hash_content->Exportable) $Doc->ExportField($this->hash_content);
					if ($this->fuente->Exportable) $Doc->ExportField($this->fuente);
					if ($this->published->Exportable) $Doc->ExportField($this->published);
					if ($this->updated->Exportable) $Doc->ExportField($this->updated);
					if ($this->categorias->Exportable) $Doc->ExportField($this->categorias);
					if ($this->titulo->Exportable) $Doc->ExportField($this->titulo);
					if ($this->id->Exportable) $Doc->ExportField($this->id);
					if ($this->islive->Exportable) $Doc->ExportField($this->islive);
					if ($this->thumbnail->Exportable) $Doc->ExportField($this->thumbnail);
					if ($this->reqdate->Exportable) $Doc->ExportField($this->reqdate);
					if ($this->author->Exportable) $Doc->ExportField($this->author);
					if ($this->trans_en->Exportable) $Doc->ExportField($this->trans_en);
					if ($this->trans_es->Exportable) $Doc->ExportField($this->trans_es);
					if ($this->trans_fr->Exportable) $Doc->ExportField($this->trans_fr);
					if ($this->trans_it->Exportable) $Doc->ExportField($this->trans_it);
					if ($this->fid->Exportable) $Doc->ExportField($this->fid);
					if ($this->fmd5->Exportable) $Doc->ExportField($this->fmd5);
					if ($this->tool_id->Exportable) $Doc->ExportField($this->tool_id);
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
