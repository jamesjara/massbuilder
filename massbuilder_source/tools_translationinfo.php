<?php

// Global variable for table object
$tools_translation = NULL;

//
// Table class for tools_translation
//
class ctools_translation extends cTable {
	var $idtools_translation;
	var $domain_id;
	var $to_domain;
	var $date;
	var $lenguaje;
	var $log;
	var $Status;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'tools_translation';
		$this->TableName = 'tools_translation';
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

		// idtools_translation
		$this->idtools_translation = new cField('tools_translation', 'tools_translation', 'x_idtools_translation', 'idtools_translation', '`idtools_translation`', '`idtools_translation`', 3, -1, FALSE, '`idtools_translation`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->idtools_translation->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['idtools_translation'] = &$this->idtools_translation;

		// domain_id
		$this->domain_id = new cField('tools_translation', 'tools_translation', 'x_domain_id', 'domain_id', '`domain_id`', '`domain_id`', 3, -1, FALSE, '`domain_id`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->domain_id->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['domain_id'] = &$this->domain_id;

		// to_domain
		$this->to_domain = new cField('tools_translation', 'tools_translation', 'x_to_domain', 'to_domain', '`to_domain`', '`to_domain`', 200, -1, FALSE, '`to_domain`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['to_domain'] = &$this->to_domain;

		// date
		$this->date = new cField('tools_translation', 'tools_translation', 'x_date', 'date', '`date`', 'DATE_FORMAT(`date`, \'%Y/%m/%d %H:%i:%s\')', 135, 5, FALSE, '`date`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->date->FldDefaultErrMsg = str_replace("%s", "/", $Language->Phrase("IncorrectDateYMD"));
		$this->fields['date'] = &$this->date;

		// lenguaje
		$this->lenguaje = new cField('tools_translation', 'tools_translation', 'x_lenguaje', 'lenguaje', '`lenguaje`', '`lenguaje`', 200, -1, FALSE, '`lenguaje`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['lenguaje'] = &$this->lenguaje;

		// log
		$this->log = new cField('tools_translation', 'tools_translation', 'x_log', 'log', '`log`', '`log`', 201, -1, FALSE, '`log`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['log'] = &$this->log;

		// Status
		$this->Status = new cField('tools_translation', 'tools_translation', 'x_Status', 'Status', '`Status`', '`Status`', 3, -1, FALSE, '`Status`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->Status->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['Status'] = &$this->Status;
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
		return "`tools_translation`";
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
	var $UpdateTable = "`tools_translation`";

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
			$sql .= ew_QuotedName('idtools_translation') . '=' . ew_QuotedValue($rs['idtools_translation'], $this->idtools_translation->FldDataType) . ' AND ';
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
		return "`idtools_translation` = @idtools_translation@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->idtools_translation->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@idtools_translation@", ew_AdjustSql($this->idtools_translation->CurrentValue), $sKeyFilter); // Replace key value
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
			return $this->james_url( "tools_translationlist.php" );
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
		return $this->james_url( "tools_translationlist.php" );
	}

	// View URL
	function GetViewUrl() {
		return $this->KeyUrl($this->james_url("tools_translationview.php"), $this->UrlParm());
	}

	// Add URL
	function GetAddUrl() {
		return $this->james_url("tools_translationadd.php");
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl($this->james_url("tools_translationedit.php"), $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl($this->james_url(ew_CurrentPage()), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl($this->james_url("tools_translationadd.php"), $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl( $this->james_url( ew_CurrentPage() ), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl($this->james_url("tools_translationdelete.php"), $this->UrlParm());
	}

	// Add key value to URL
	function KeyUrl($url, $parm = "") {
		$sUrl = $url . "?";
		if ($parm <> "") $sUrl .= $parm . "&";
		if (!is_null($this->idtools_translation->CurrentValue)) {
			$sUrl .= "idtools_translation=" . urlencode($this->idtools_translation->CurrentValue);
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
			$arKeys[] = @$_GET["idtools_translation"]; // idtools_translation

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
			$this->idtools_translation->CurrentValue = $key;
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
		$this->idtools_translation->setDbValue($rs->fields('idtools_translation'));
		$this->domain_id->setDbValue($rs->fields('domain_id'));
		$this->to_domain->setDbValue($rs->fields('to_domain'));
		$this->date->setDbValue($rs->fields('date'));
		$this->lenguaje->setDbValue($rs->fields('lenguaje'));
		$this->log->setDbValue($rs->fields('log'));
		$this->Status->setDbValue($rs->fields('Status'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// idtools_translation
		// domain_id
		// to_domain
		// date
		// lenguaje
		// log
		// Status
		// idtools_translation

		$this->idtools_translation->ViewValue = $this->idtools_translation->CurrentValue;
		$this->idtools_translation->ViewCustomAttributes = "";

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

		// to_domain
		if (strval($this->to_domain->CurrentValue) <> "") {
			$sFilterWrk = "`id_domains`" . ew_SearchString("=", $this->to_domain->CurrentValue, EW_DATATYPE_NUMBER);
		$sSqlWrk = "SELECT `id_domains`, `dominio` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `domains`";
		$sWhereWrk = "";
		if ($sFilterWrk <> "") {
			ew_AddFilter($sWhereWrk, $sFilterWrk);
		}
		if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
			$rswrk = $conn->Execute($sSqlWrk);
			if ($rswrk && !$rswrk->EOF) { // Lookup values found
				$this->to_domain->ViewValue = $rswrk->fields('DispFld');
				$rswrk->Close();
			} else {
				$this->to_domain->ViewValue = $this->to_domain->CurrentValue;
			}
		} else {
			$this->to_domain->ViewValue = NULL;
		}
		$this->to_domain->ViewCustomAttributes = "";

		// date
		$this->date->ViewValue = $this->date->CurrentValue;
		$this->date->ViewValue = ew_FormatDateTime($this->date->ViewValue, 5);
		$this->date->ViewCustomAttributes = "";

		// lenguaje
		if (strval($this->lenguaje->CurrentValue) <> "") {
			switch ($this->lenguaje->CurrentValue) {
				case $this->lenguaje->FldTagValue(1):
					$this->lenguaje->ViewValue = $this->lenguaje->FldTagCaption(1) <> "" ? $this->lenguaje->FldTagCaption(1) : $this->lenguaje->CurrentValue;
					break;
				case $this->lenguaje->FldTagValue(2):
					$this->lenguaje->ViewValue = $this->lenguaje->FldTagCaption(2) <> "" ? $this->lenguaje->FldTagCaption(2) : $this->lenguaje->CurrentValue;
					break;
				case $this->lenguaje->FldTagValue(3):
					$this->lenguaje->ViewValue = $this->lenguaje->FldTagCaption(3) <> "" ? $this->lenguaje->FldTagCaption(3) : $this->lenguaje->CurrentValue;
					break;
				default:
					$this->lenguaje->ViewValue = $this->lenguaje->CurrentValue;
			}
		} else {
			$this->lenguaje->ViewValue = NULL;
		}
		$this->lenguaje->ViewCustomAttributes = "";

		// log
		$this->log->ViewValue = $this->log->CurrentValue;
		$this->log->ViewCustomAttributes = "";

		// Status
		$this->Status->ViewValue = $this->Status->CurrentValue;
		$this->Status->ViewCustomAttributes = "";

		// idtools_translation
		$this->idtools_translation->LinkCustomAttributes = "";
		$this->idtools_translation->HrefValue = "";
		$this->idtools_translation->TooltipValue = "";

		// domain_id
		$this->domain_id->LinkCustomAttributes = "";
		$this->domain_id->HrefValue = "";
		$this->domain_id->TooltipValue = "";

		// to_domain
		$this->to_domain->LinkCustomAttributes = "";
		$this->to_domain->HrefValue = "";
		$this->to_domain->TooltipValue = "";

		// date
		$this->date->LinkCustomAttributes = "";
		$this->date->HrefValue = "";
		$this->date->TooltipValue = "";

		// lenguaje
		$this->lenguaje->LinkCustomAttributes = "";
		$this->lenguaje->HrefValue = "";
		$this->lenguaje->TooltipValue = "";

		// log
		$this->log->LinkCustomAttributes = "";
		$this->log->HrefValue = "";
		$this->log->TooltipValue = "";

		// Status
		$this->Status->LinkCustomAttributes = "";
		$this->Status->HrefValue = "";
		$this->Status->TooltipValue = "";

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
				if ($this->idtools_translation->Exportable) $Doc->ExportCaption($this->idtools_translation);
				if ($this->domain_id->Exportable) $Doc->ExportCaption($this->domain_id);
				if ($this->to_domain->Exportable) $Doc->ExportCaption($this->to_domain);
				if ($this->date->Exportable) $Doc->ExportCaption($this->date);
				if ($this->lenguaje->Exportable) $Doc->ExportCaption($this->lenguaje);
				if ($this->log->Exportable) $Doc->ExportCaption($this->log);
				if ($this->Status->Exportable) $Doc->ExportCaption($this->Status);
			} else {
				if ($this->idtools_translation->Exportable) $Doc->ExportCaption($this->idtools_translation);
				if ($this->to_domain->Exportable) $Doc->ExportCaption($this->to_domain);
				if ($this->date->Exportable) $Doc->ExportCaption($this->date);
				if ($this->lenguaje->Exportable) $Doc->ExportCaption($this->lenguaje);
				if ($this->log->Exportable) $Doc->ExportCaption($this->log);
				if ($this->Status->Exportable) $Doc->ExportCaption($this->Status);
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
					if ($this->idtools_translation->Exportable) $Doc->ExportField($this->idtools_translation);
					if ($this->domain_id->Exportable) $Doc->ExportField($this->domain_id);
					if ($this->to_domain->Exportable) $Doc->ExportField($this->to_domain);
					if ($this->date->Exportable) $Doc->ExportField($this->date);
					if ($this->lenguaje->Exportable) $Doc->ExportField($this->lenguaje);
					if ($this->log->Exportable) $Doc->ExportField($this->log);
					if ($this->Status->Exportable) $Doc->ExportField($this->Status);
				} else {
					if ($this->idtools_translation->Exportable) $Doc->ExportField($this->idtools_translation);
					if ($this->to_domain->Exportable) $Doc->ExportField($this->to_domain);
					if ($this->date->Exportable) $Doc->ExportField($this->date);
					if ($this->lenguaje->Exportable) $Doc->ExportField($this->lenguaje);
					if ($this->log->Exportable) $Doc->ExportField($this->log);
					if ($this->Status->Exportable) $Doc->ExportField($this->Status);
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
