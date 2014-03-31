<?php

// Global variable for table object
$view1 = NULL;

//
// Table class for view1
//
class cview1 extends cTable {
	var $dominio;
	var $id_domains;
	var $bt1;
	var $bt2;
	var $bt3;

	//
	// Table class constructor
	//
	function __construct() {
		global $Language;

		// Language object
		if (!isset($Language)) $Language = new cLanguage();
		$this->TableVar = 'view1';
		$this->TableName = 'view1';
		$this->TableType = 'VIEW';
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
		$this->dominio = new cField('view1', 'view1', 'x_dominio', 'dominio', '`dominio`', '`dominio`', 200, -1, FALSE, '`dominio`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->fields['dominio'] = &$this->dominio;

		// id_domains
		$this->id_domains = new cField('view1', 'view1', 'x_id_domains', 'id_domains', '`id_domains`', '`id_domains`', 3, -1, FALSE, '`id_domains`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->id_domains->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['id_domains'] = &$this->id_domains;

		// bt1
		$this->bt1 = new cField('view1', 'view1', 'x_bt1', 'bt1', '`bt1`', '`bt1`', 3, -1, FALSE, '`bt1`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->bt1->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['bt1'] = &$this->bt1;

		// bt2
		$this->bt2 = new cField('view1', 'view1', 'x_bt2', 'bt2', '`bt2`', '`bt2`', 3, -1, FALSE, '`bt2`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->bt2->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['bt2'] = &$this->bt2;

		// bt3
		$this->bt3 = new cField('view1', 'view1', 'x_bt3', 'bt3', '`bt3`', '`bt3`', 3, -1, FALSE, '`bt3`', FALSE, FALSE, FALSE, 'FORMATTED TEXT');
		$this->bt3->FldDefaultErrMsg = $Language->Phrase("IncorrectInteger");
		$this->fields['bt3'] = &$this->bt3;
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
		return "`view1`";
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
	var $UpdateTable = "`view1`";

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
			$sql .= ew_QuotedName('bt1') . '=' . ew_QuotedValue($rs['bt1'], $this->bt1->FldDataType) . ' AND ';
			$sql .= ew_QuotedName('bt2') . '=' . ew_QuotedValue($rs['bt2'], $this->bt2->FldDataType) . ' AND ';
			$sql .= ew_QuotedName('bt3') . '=' . ew_QuotedValue($rs['bt3'], $this->bt3->FldDataType) . ' AND ';
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
		return "`id_domains` = @id_domains@ AND `bt1` = @bt1@ AND `bt2` = @bt2@ AND `bt3` = @bt3@";
	}

	// Key filter
	function KeyFilter() {
		$sKeyFilter = $this->SqlKeyFilter();
		if (!is_numeric($this->id_domains->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@id_domains@", ew_AdjustSql($this->id_domains->CurrentValue), $sKeyFilter); // Replace key value
		if (!is_numeric($this->bt1->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@bt1@", ew_AdjustSql($this->bt1->CurrentValue), $sKeyFilter); // Replace key value
		if (!is_numeric($this->bt2->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@bt2@", ew_AdjustSql($this->bt2->CurrentValue), $sKeyFilter); // Replace key value
		if (!is_numeric($this->bt3->CurrentValue))
			$sKeyFilter = "0=1"; // Invalid key
		$sKeyFilter = str_replace("@bt3@", ew_AdjustSql($this->bt3->CurrentValue), $sKeyFilter); // Replace key value
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
			return "view1list.php";
		}
	}

	function setReturnUrl($v) {
		$_SESSION[EW_PROJECT_NAME . "_" . $this->TableVar . "_" . EW_TABLE_RETURN_URL] = $v;
	}

	// List URL
	function GetListUrl() {
		return "view1list.php";
	}

	// View URL
	function GetViewUrl() {
		return $this->KeyUrl("view1view.php", $this->UrlParm());
	}

	// Add URL
	function GetAddUrl() {
		return "view1add.php";
	}

	// Edit URL
	function GetEditUrl($parm = "") {
		return $this->KeyUrl("view1edit.php", $this->UrlParm($parm));
	}

	// Inline edit URL
	function GetInlineEditUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=edit"));
	}

	// Copy URL
	function GetCopyUrl($parm = "") {
		return $this->KeyUrl("view1add.php", $this->UrlParm($parm));
	}

	// Inline copy URL
	function GetInlineCopyUrl() {
		return $this->KeyUrl(ew_CurrentPage(), $this->UrlParm("a=copy"));
	}

	// Delete URL
	function GetDeleteUrl() {
		return $this->KeyUrl("view1delete.php", $this->UrlParm());
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
		if (!is_null($this->bt1->CurrentValue)) {
			$sUrl .= "&bt1=" . urlencode($this->bt1->CurrentValue);
		} else {
			return "javascript:alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		if (!is_null($this->bt2->CurrentValue)) {
			$sUrl .= "&bt2=" . urlencode($this->bt2->CurrentValue);
		} else {
			return "javascript:alert(ewLanguage.Phrase('InvalidRecord'));";
		}
		if (!is_null($this->bt3->CurrentValue)) {
			$sUrl .= "&bt3=" . urlencode($this->bt3->CurrentValue);
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
			$arKey[] = @$_GET["id_domains"]; // id_domains
			$arKey[] = @$_GET["bt1"]; // bt1
			$arKey[] = @$_GET["bt2"]; // bt2
			$arKey[] = @$_GET["bt3"]; // bt3
			$arKeys[] = $arKey;

			//return $arKeys; // do not return yet, so the values will also be checked by the following code
		}

		// check keys
		$ar = array();
		foreach ($arKeys as $key) {
			if (!is_array($key) || count($key) <> 4)
				continue; // just skip so other keys will still work
			if (!is_numeric($key[0])) // id_domains
				continue;
			if (!is_numeric($key[1])) // bt1
				continue;
			if (!is_numeric($key[2])) // bt2
				continue;
			if (!is_numeric($key[3])) // bt3
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
			$this->id_domains->CurrentValue = $key[0];
			$this->bt1->CurrentValue = $key[1];
			$this->bt2->CurrentValue = $key[2];
			$this->bt3->CurrentValue = $key[3];
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
		$this->bt1->setDbValue($rs->fields('bt1'));
		$this->bt2->setDbValue($rs->fields('bt2'));
		$this->bt3->setDbValue($rs->fields('bt3'));
	}

	// Render list row values
	function RenderListRow() {
		global $conn, $Security;

		// Call Row Rendering event
		$this->Row_Rendering();

   // Common render codes
		// dominio
		// id_domains
		// bt1
		// bt2
		// bt3
		// dominio

		$this->dominio->ViewValue = $this->dominio->CurrentValue;
		$this->dominio->ViewCustomAttributes = "";

		// id_domains
		$this->id_domains->ViewValue = $this->id_domains->CurrentValue;
		$this->id_domains->ViewCustomAttributes = "";

		// bt1
		$this->bt1->ViewValue = $this->bt1->CurrentValue;
		$this->bt1->ViewCustomAttributes = "";

		// bt2
		$this->bt2->ViewValue = $this->bt2->CurrentValue;
		$this->bt2->ViewCustomAttributes = "";

		// bt3
		$this->bt3->ViewValue = $this->bt3->CurrentValue;
		$this->bt3->ViewCustomAttributes = "";

		// dominio
		$this->dominio->LinkCustomAttributes = "";
		$this->dominio->HrefValue = "";
		$this->dominio->TooltipValue = "";

		// id_domains
		$this->id_domains->LinkCustomAttributes = "";
		$this->id_domains->HrefValue = "";
		$this->id_domains->TooltipValue = "";

		// bt1
		$this->bt1->LinkCustomAttributes = test;
		if (!ew_Empty($this->id_domains->CurrentValue)) {
			$this->bt1->HrefValue = ((!empty($this->id_domains->ViewValue)) ? $this->id_domains->ViewValue : $this->id_domains->CurrentValue); // Add prefix/suffix
			$this->bt1->LinkAttrs["target"] = "_blank"; // Add target
			if ($this->Export <> "") $this->bt1->HrefValue = ew_ConvertFullUrl($this->bt1->HrefValue);
		} else {
			$this->bt1->HrefValue = "";
		}
		$this->bt1->TooltipValue = "";

		// bt2
		$this->bt2->LinkCustomAttributes = "";
		if (!ew_Empty($this->bt2->CurrentValue)) {
			$this->bt2->HrefValue = ((!empty($this->bt2->ViewValue)) ? $this->bt2->ViewValue : $this->bt2->CurrentValue); // Add prefix/suffix
			$this->bt2->LinkAttrs["target"] = "_blank"; // Add target
			if ($this->Export <> "") $this->bt2->HrefValue = ew_ConvertFullUrl($this->bt2->HrefValue);
		} else {
			$this->bt2->HrefValue = "";
		}
		$this->bt2->TooltipValue = "";

		// bt3
		$this->bt3->LinkCustomAttributes = "";
		if (!ew_Empty($this->bt3->CurrentValue)) {
			$this->bt3->HrefValue = ((!empty($this->bt3->ViewValue)) ? $this->bt3->ViewValue : $this->bt3->CurrentValue); // Add prefix/suffix
			$this->bt3->LinkAttrs["target"] = "_blank"; // Add target
			if ($this->Export <> "") $this->bt3->HrefValue = ew_ConvertFullUrl($this->bt3->HrefValue);
		} else {
			$this->bt3->HrefValue = "";
		}
		$this->bt3->TooltipValue = "";

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
				if ($this->bt1->Exportable) $Doc->ExportCaption($this->bt1);
				if ($this->bt2->Exportable) $Doc->ExportCaption($this->bt2);
				if ($this->bt3->Exportable) $Doc->ExportCaption($this->bt3);
			} else {
				if ($this->dominio->Exportable) $Doc->ExportCaption($this->dominio);
				if ($this->id_domains->Exportable) $Doc->ExportCaption($this->id_domains);
				if ($this->bt1->Exportable) $Doc->ExportCaption($this->bt1);
				if ($this->bt2->Exportable) $Doc->ExportCaption($this->bt2);
				if ($this->bt3->Exportable) $Doc->ExportCaption($this->bt3);
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
					if ($this->bt1->Exportable) $Doc->ExportField($this->bt1);
					if ($this->bt2->Exportable) $Doc->ExportField($this->bt2);
					if ($this->bt3->Exportable) $Doc->ExportField($this->bt3);
				} else {
					if ($this->dominio->Exportable) $Doc->ExportField($this->dominio);
					if ($this->id_domains->Exportable) $Doc->ExportField($this->id_domains);
					if ($this->bt1->Exportable) $Doc->ExportField($this->bt1);
					if ($this->bt2->Exportable) $Doc->ExportField($this->bt2);
					if ($this->bt3->Exportable) $Doc->ExportField($this->bt3);
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
