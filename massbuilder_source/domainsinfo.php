<?php

// Global variable for table object
$domains = NULL;

//
// Table class for domains
//
class cdomains extends cTable {
	var $dominio;
	var $id_domains;
	var $id_proyecto;
	var $hosted_in;
	var $map;
	var $bid;
	var $_language;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'domains';
		$this->TableName = 'domains';
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

		// dominio
		$this->dominio = new cField('domains', 'domains', 'x_dominio', 'dominio', '`dominio`', '`dominio`', 200, -1, FALSE, '`dominio`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['dominio'] = &$this->dominio;

		// id_domains
		$this->id_domains = new cField('domains', 'domains', 'x_id_domains', 'id_domains', '`id_domains`', '`id_domains`', 3, -1, FALSE, '`id_domains`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id_domains->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_domains'] = &$this->id_domains;

		// id_proyecto
		$this->id_proyecto = new cField('domains', 'domains', 'x_id_proyecto', 'id_proyecto', '`id_proyecto`', '`id_proyecto`', 3, -1, FALSE, '`id_proyecto`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id_proyecto->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_proyecto'] = &$this->id_proyecto;

		// hosted_in
		$this->hosted_in = new cField('domains', 'domains', 'x_hosted_in', 'hosted_in', '`hosted_in`', '`hosted_in`', 3, -1, FALSE, '`hosted_in`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->hosted_in->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['hosted_in'] = &$this->hosted_in;

		// map
		$this->map = new cField('domains', 'domains', 'x_map', 'map', '`map`', '`map`', 3, -1, FALSE, '`map`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->map->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['map'] = &$this->map;

		// bid
		$this->bid = new cField('domains', 'domains', 'x_bid', 'bid', '`bid`', '`bid`', 200, -1, FALSE, '`bid`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['bid'] = &$this->bid;

		// language
		$this->_language = new cField('domains', 'domains', 'x__language', 'language', '`language`', '`language`', 200, -1, FALSE, '`language`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['language'] = &$this->_language;
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
		if ($this->getCurrentMasterTable() == "proyectos") {
			if ($this->id_proyecto->getSessionValue() <> "")
				$sMasterFilter .= "`idproyectos`=" . ew_QuotedValue($this->id_proyecto->getSessionValue(), EW_DATATYPE_NUMBER);
			else
				return "";
		}
		return $sMasterFilter;
	}

	// Session detail WHERE clause
	function GetDetailFilter() {

		// Detail filter
		$sDetailFilter = "";
		if ($this->getCurrentMasterTable() == "proyectos") {
			if ($this->id_proyecto->getSessionValue() <> "")
				$sDetailFilter .= "`id_proyecto`=" . ew_QuotedValue($this->id_proyecto->getSessionValue(), EW_DATATYPE_NUMBER);
			else
				return "";
		}
		return $sDetailFilter;
	}

	// Master filter
	function SqlMasterFilter_proyectos() {
		return "`idproyectos`=@idproyectos@";
	}

	// Detail filter
	function SqlDetailFilter_proyectos() {
		return "`id_proyecto`=@id_proyecto@";
	}

	// Current detail table name
	function getCurrentDetailTable() {
		return @$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_DETAIL_TABLE];
	}

	function setCurrentDetailTable($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_DETAIL_TABLE] = $v;
	}

	// Get detail url
	function GetDetailUrl() {

		// Detail url
		$sDetailUrl = "";
		if ($this->getCurrentDetailTable() == "entries") {
			$sDetailUrl =  $this->james_url( $GLOBALS["entries"]->GetListUrl() ). "?showmaster=" . $this->TableVar;
			$sDetailUrl .= "&domain_id=" . $this->id_domains->CurrentValue;
		}
		if ($this->getCurrentDetailTable() == "tools_translation") {
			$sDetailUrl =  $this->james_url( $GLOBALS["tools_translation"]->GetListUrl() ). "?showmaster=" . $this->TableVar;
			$sDetailUrl .= "&domain_id=" . $this->id_domains->CurrentValue;
		}
		if ($this->getCurrentDetailTable() == "tools_backups") {
			$sDetailUrl =  $this->james_url( $GLOBALS["tools_backups"]->GetListUrl() ). "?showmaster=" . $this->TableVar;
			$sDetailUrl .= "&domain_id=" . $this->id_domains->CurrentValue;
		}
		if ($this->getCurrentDetailTable() == "tools") {
			$sDetailUrl =  $this->james_url( $GLOBALS["tools"]->GetListUrl() ). "?showmaster=" . $this->TableVar;
			$sDetailUrl .= "&parent_domain=" . $this->id_domains->CurrentValue;
		}
		return $sDetailUrl;
	}

	// Table level SQL
	function SqlFrom() { // From
		return "`domains`";
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
	var $UpdateTable = "`domains`";

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
			$sql .= ew_QuotedName('id_domains') . '=' . ew_QuotedValue($rs['id_domains'], $this->id_domains->FldDataType) . ' AND ';
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
		return "`id_domains` = @id_domains@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->id_domains->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@id_domains@", ew_AdjustSql($this->id_domains->CurrentValue), $sKeyFilter); // Replace key value
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
			return $this->james_url( "domainslist.php" );
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
		return $this->james_url( "domainslist.php" );
	}

	// View URL
	function GetViewUrl() {
		return $this->KeyUrl($this->james_url("domainsview.php"), $this->UrlParm());
	}

	// Add URL
	function GetAddUrl() {
		return $this->james_url("domainsadd.php");
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl($this->james_url("domainsedit.php"), $this->UrlParm($parm));
		else
			return $this->KeyUrl($this->james_url("domainsedit.php"), $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl($this->james_url(ew_CurrentPage()), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		if ($parm <> "")
			return $this->KeyUrl($this->james_url("domainsadd.php"), $this->UrlParm($parm));
		else
			return $this->KeyUrl($this->james_url("domainsadd.php"), $this->UrlParm(EW_TABLE_SHOW_DETAIL . "="));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl( $this->james_url( ew_CurrentPage() ), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl($this->james_url("domainsdelete.php"), $this->UrlParm());
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

	// Load row values from recordset
	function LoadListRowValues(&$rs) {
		$this->dominio->setDbValue($rs->fields('dominio'));
		$this->id_domains->setDbValue($rs->fields('id_domains'));
		$this->id_proyecto->setDbValue($rs->fields('id_proyecto'));
		$this->hosted_in->setDbValue($rs->fields('hosted_in'));
		$this->map->setDbValue($rs->fields('map'));
		$this->bid->setDbValue($rs->fields('bid'));
		$this->_language->setDbValue($rs->fields('language'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// dominio
		// id_domains
		// id_proyecto
		// hosted_in
		// map
		// bid
		// language
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
				$this->map->ViewValue .= ew_ValueSeparator(1,$this->map) . $rswrk->fields('Disp2Fld');
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

		// id_proyecto
		$this->id_proyecto->LinkCustomAttributes = "";
		$this->id_proyecto->HrefValue = "";
		$this->id_proyecto->TooltipValue = "";

		// hosted_in
		$this->hosted_in->LinkCustomAttributes = "";
		$this->hosted_in->HrefValue = "";
		$this->hosted_in->TooltipValue = "";

		// map
		$this->map->LinkCustomAttributes = "";
		$this->map->HrefValue = "";
		$this->map->TooltipValue = "";

		// bid
		$this->bid->LinkCustomAttributes = "";
		$this->bid->HrefValue = "";
		$this->bid->TooltipValue = "";

		// language
		$this->_language->LinkCustomAttributes = "";
		$this->_language->HrefValue = "";
		$this->_language->TooltipValue = "";

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
				if ($this->dominio->Exportable) $Doc->ExportCaption($this->dominio);
				if ($this->id_domains->Exportable) $Doc->ExportCaption($this->id_domains);
				if ($this->id_proyecto->Exportable) $Doc->ExportCaption($this->id_proyecto);
				if ($this->hosted_in->Exportable) $Doc->ExportCaption($this->hosted_in);
				if ($this->map->Exportable) $Doc->ExportCaption($this->map);
				if ($this->bid->Exportable) $Doc->ExportCaption($this->bid);
				if ($this->_language->Exportable) $Doc->ExportCaption($this->_language);
			} else {
				if ($this->dominio->Exportable) $Doc->ExportCaption($this->dominio);
				if ($this->id_domains->Exportable) $Doc->ExportCaption($this->id_domains);
				if ($this->id_proyecto->Exportable) $Doc->ExportCaption($this->id_proyecto);
				if ($this->hosted_in->Exportable) $Doc->ExportCaption($this->hosted_in);
				if ($this->map->Exportable) $Doc->ExportCaption($this->map);
				if ($this->bid->Exportable) $Doc->ExportCaption($this->bid);
				if ($this->_language->Exportable) $Doc->ExportCaption($this->_language);
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
					if ($this->dominio->Exportable) $Doc->ExportField($this->dominio);
					if ($this->id_domains->Exportable) $Doc->ExportField($this->id_domains);
					if ($this->id_proyecto->Exportable) $Doc->ExportField($this->id_proyecto);
					if ($this->hosted_in->Exportable) $Doc->ExportField($this->hosted_in);
					if ($this->map->Exportable) $Doc->ExportField($this->map);
					if ($this->bid->Exportable) $Doc->ExportField($this->bid);
					if ($this->_language->Exportable) $Doc->ExportField($this->_language);
				} else {
					if ($this->dominio->Exportable) $Doc->ExportField($this->dominio);
					if ($this->id_domains->Exportable) $Doc->ExportField($this->id_domains);
					if ($this->id_proyecto->Exportable) $Doc->ExportField($this->id_proyecto);
					if ($this->hosted_in->Exportable) $Doc->ExportField($this->hosted_in);
					if ($this->map->Exportable) $Doc->ExportField($this->map);
					if ($this->bid->Exportable) $Doc->ExportField($this->bid);
					if ($this->_language->Exportable) $Doc->ExportField($this->_language);
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
