<?php include_once "usersinfo.php" ?>
<?php

// Create page object
if (!isset($tools_backups_grid)) $tools_backups_grid = new ctools_backups_grid();

// Page init
$tools_backups_grid->Page_Init();

// Page main
$tools_backups_grid->Page_Main();
?>
<?php if ($tools_backups->Export == "") { ?>
<script type="text/javascript">

// Page object
var tools_backups_grid = new ew_Page("tools_backups_grid");
tools_backups_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = tools_backups_grid.PageID; // For backward compatibility

// Form object
var ftools_backupsgrid = new ew_Form("ftools_backupsgrid");

// Validate form
ftools_backupsgrid.Validate = function(fobj) {
	if (!this.ValidateRequired)
		return true; // Ignore validation
	fobj = fobj || this.Form;
	this.PostAutoSuggest();	
	if (fobj.a_confirm && fobj.a_confirm.value == "F")
		return true;
	var elm, aelm;
	var rowcnt = (fobj.key_count) ? Number(fobj.key_count.value) : 1;
	var startcnt = (rowcnt == 0) ? 0 : 1; // rowcnt == 0 => Inline-Add
	var addcnt = 0;
	for (var i = startcnt; i <= rowcnt; i++) {
		var infix = (fobj.key_count) ? String(i) : "";
		var checkrow = (fobj.a_list && fobj.a_list.value == "gridinsert") ? !this.EmptyRow(infix) : true;
		if (checkrow) {
			addcnt++;
		elm = fobj.elements["x" + infix + "_data"];
		if (elm && !ew_CheckFileType(elm.value))
			return ew_OnError(this, elm, ewLanguage.Phrase("WrongFileType"));
		elm = fobj.elements["x" + infix + "_date"];
		if (elm && !ew_CheckDate(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($tools_backups->date->FldErrMsg()) ?>");

		// Set up row object
		ew_ElementsToRow(fobj, infix);

		// Fire Form_CustomValidate event
		if (!this.Form_CustomValidate(fobj))
			return false;
		} // End Grid Add checking
	}
	return true;
}

// Check empty row
ftools_backupsgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "domain_id", false)) return false;
	if (ew_ValueChanged(fobj, infix, "data", false)) return false;
	if (ew_ValueChanged(fobj, infix, "date", false)) return false;
	return true;
}

// Form_CustomValidate event
ftools_backupsgrid.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftools_backupsgrid.ValidateRequired = true;
<?php } else { ?>
ftools_backupsgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ftools_backupsgrid.Lists["x_domain_id"] = {"LinkField":"x_id_domains","Ajax":true,"AutoFill":false,"DisplayFields":["x_dominio","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<?php } ?>
<?php
if ($tools_backups->CurrentAction == "gridadd") {
	if ($tools_backups->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$tools_backups_grid->TotalRecs = $tools_backups->SelectRecordCount();
			$tools_backups_grid->Recordset = $tools_backups_grid->LoadRecordset($tools_backups_grid->StartRec-1, $tools_backups_grid->DisplayRecs);
		} else {
			if ($tools_backups_grid->Recordset = $tools_backups_grid->LoadRecordset())
				$tools_backups_grid->TotalRecs = $tools_backups_grid->Recordset->RecordCount();
		}
		$tools_backups_grid->StartRec = 1;
		$tools_backups_grid->DisplayRecs = $tools_backups_grid->TotalRecs;
	} else {
		$tools_backups->CurrentFilter = "0=1";
		$tools_backups_grid->StartRec = 1;
		$tools_backups_grid->DisplayRecs = $tools_backups->GridAddRowCount;
	}
	$tools_backups_grid->TotalRecs = $tools_backups_grid->DisplayRecs;
	$tools_backups_grid->StopRec = $tools_backups_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$tools_backups_grid->TotalRecs = $tools_backups->SelectRecordCount();
	} else {
		if ($tools_backups_grid->Recordset = $tools_backups_grid->LoadRecordset())
			$tools_backups_grid->TotalRecs = $tools_backups_grid->Recordset->RecordCount();
	}
	$tools_backups_grid->StartRec = 1;
	$tools_backups_grid->DisplayRecs = $tools_backups_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$tools_backups_grid->Recordset = $tools_backups_grid->LoadRecordset($tools_backups_grid->StartRec-1, $tools_backups_grid->DisplayRecs);
}
?>
<p style="white-spaceJAMES: nowrapJAMES;"><h4 id="ewPageCaption" class="page-header ewTitle ewTableTitle"> <?php if ($tools_backups->CurrentMode == "add" || $tools_backups->CurrentMode == "copy") { ?><?php echo $Language->Phrase("Add") ?><?php } elseif ($tools_backups->CurrentMode == "edit") { ?><?php echo $Language->Phrase("Edit") ?><?php } ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $tools_backups->TableCaption() ?></h4></p>
</p>
<?php $tools_backups_grid->ShowPageHeader(); ?>
<?php
$tools_backups_grid->ShowMessage();
?>
<div id="ftools_backupsgrid" class="ewForm">
<div id="gmp_tools_backups" class="ewGridMiddlePanel row-fluid">
<ul class="thumbnails">
<table id="tbl_tools_backupsgrid" class="ewTable ewTableSeparate table table-striped table-bordered ">
<?php echo $tools_backups->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$tools_backups_grid->RenderListOptions();

// Render list options (header, left)
$tools_backups_grid->ListOptions->Render("header", "left");
?>
<?php if ($tools_backups->idtools_backups->Visible) { // idtools_backups ?>
	<?php if ($tools_backups->SortUrl($tools_backups->idtools_backups) == "") { ?>
		<th><span id="elh_tools_backups_idtools_backups" class="tools_backups_idtools_backups">
		<div class="ewTableHeaderBtn"><?php echo $tools_backups->idtools_backups->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div><span id="elh_tools_backups_idtools_backups" class="tools_backups_idtools_backups">
			<div class="ewTableHeaderBtn">			
			<?php echo $tools_backups->idtools_backups->FldCaption() ?>
			<?php if ($tools_backups->idtools_backups->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($tools_backups->idtools_backups->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($tools_backups->domain_id->Visible) { // domain_id ?>
	<?php if ($tools_backups->SortUrl($tools_backups->domain_id) == "") { ?>
		<th><span id="elh_tools_backups_domain_id" class="tools_backups_domain_id">
		<div class="ewTableHeaderBtn"><?php echo $tools_backups->domain_id->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div><span id="elh_tools_backups_domain_id" class="tools_backups_domain_id">
			<div class="ewTableHeaderBtn">			
			<?php echo $tools_backups->domain_id->FldCaption() ?>
			<?php if ($tools_backups->domain_id->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($tools_backups->domain_id->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($tools_backups->data->Visible) { // data ?>
	<?php if ($tools_backups->SortUrl($tools_backups->data) == "") { ?>
		<th><span id="elh_tools_backups_data" class="tools_backups_data">
		<div class="ewTableHeaderBtn"><?php echo $tools_backups->data->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div><span id="elh_tools_backups_data" class="tools_backups_data">
			<div class="ewTableHeaderBtn">			
			<?php echo $tools_backups->data->FldCaption() ?>
			<?php if ($tools_backups->data->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($tools_backups->data->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($tools_backups->date->Visible) { // date ?>
	<?php if ($tools_backups->SortUrl($tools_backups->date) == "") { ?>
		<th><span id="elh_tools_backups_date" class="tools_backups_date">
		<div class="ewTableHeaderBtn"><?php echo $tools_backups->date->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div><span id="elh_tools_backups_date" class="tools_backups_date">
			<div class="ewTableHeaderBtn">			
			<?php echo $tools_backups->date->FldCaption() ?>
			<?php if ($tools_backups->date->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($tools_backups->date->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$tools_backups_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$tools_backups_grid->StartRec = 1;
$tools_backups_grid->StopRec = $tools_backups_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue("key_count") && ($tools_backups->CurrentAction == "gridadd" || $tools_backups->CurrentAction == "gridedit" || $tools_backups->CurrentAction == "F")) {
		$tools_backups_grid->KeyCount = $objForm->GetValue("key_count");
		$tools_backups_grid->StopRec = $tools_backups_grid->KeyCount;
	}
}
$tools_backups_grid->RecCnt = $tools_backups_grid->StartRec - 1;
if ($tools_backups_grid->Recordset && !$tools_backups_grid->Recordset->EOF) {
	$tools_backups_grid->Recordset->MoveFirst();
	if (!$bSelectLimit && $tools_backups_grid->StartRec > 1)
		$tools_backups_grid->Recordset->Move($tools_backups_grid->StartRec - 1);
} elseif (!$tools_backups->AllowAddDeleteRow && $tools_backups_grid->StopRec == 0) {
	$tools_backups_grid->StopRec = $tools_backups->GridAddRowCount;
}

// Initialize aggregate
$tools_backups->RowType = EW_ROWTYPE_AGGREGATEINIT;
$tools_backups->ResetAttrs();
$tools_backups_grid->RenderRow();
if ($tools_backups->CurrentAction == "gridadd")
	$tools_backups_grid->RowIndex = 0;
if ($tools_backups->CurrentAction == "gridedit")
	$tools_backups_grid->RowIndex = 0;
while ($tools_backups_grid->RecCnt < $tools_backups_grid->StopRec) {
	$tools_backups_grid->RecCnt++;
	if (intval($tools_backups_grid->RecCnt) >= intval($tools_backups_grid->StartRec)) {
		$tools_backups_grid->RowCnt++;
		if ($tools_backups->CurrentAction == "gridadd" || $tools_backups->CurrentAction == "gridedit" || $tools_backups->CurrentAction == "F") {
			$tools_backups_grid->RowIndex++;
			$objForm->Index = $tools_backups_grid->RowIndex;
			if ($objForm->HasValue("k_action"))
				$tools_backups_grid->RowAction = strval($objForm->GetValue("k_action"));
			elseif ($tools_backups->CurrentAction == "gridadd")
				$tools_backups_grid->RowAction = "insert";
			else
				$tools_backups_grid->RowAction = "";
		}

		// Set up key count
		$tools_backups_grid->KeyCount = $tools_backups_grid->RowIndex;

		// Init row class and style
		$tools_backups->ResetAttrs();
		$tools_backups->CssClass = "";
		if ($tools_backups->CurrentAction == "gridadd") {
			if ($tools_backups->CurrentMode == "copy") {
				$tools_backups_grid->LoadRowValues($tools_backups_grid->Recordset); // Load row values
				$tools_backups_grid->SetRecordKey($tools_backups_grid->RowOldKey, $tools_backups_grid->Recordset); // Set old record key
			} else {
				$tools_backups_grid->LoadDefaultValues(); // Load default values
				$tools_backups_grid->RowOldKey = ""; // Clear old key value
			}
		} elseif ($tools_backups->CurrentAction == "gridedit") {
			$tools_backups_grid->LoadRowValues($tools_backups_grid->Recordset); // Load row values
		}
		$tools_backups->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($tools_backups->CurrentAction == "gridadd") // Grid add
			$tools_backups->RowType = EW_ROWTYPE_ADD; // Render add
		if ($tools_backups->CurrentAction == "gridadd" && $tools_backups->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$tools_backups_grid->RestoreCurrentRowFormValues($tools_backups_grid->RowIndex); // Restore form values
		if ($tools_backups->CurrentAction == "gridedit") { // Grid edit
			if ($tools_backups->EventCancelled) {
				$tools_backups_grid->RestoreCurrentRowFormValues($tools_backups_grid->RowIndex); // Restore form values
			}
			if ($tools_backups_grid->RowAction == "insert")
				$tools_backups->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$tools_backups->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($tools_backups->CurrentAction == "gridedit" && ($tools_backups->RowType == EW_ROWTYPE_EDIT || $tools_backups->RowType == EW_ROWTYPE_ADD) && $tools_backups->EventCancelled) // Update failed
			$tools_backups_grid->RestoreCurrentRowFormValues($tools_backups_grid->RowIndex); // Restore form values
		if ($tools_backups->RowType == EW_ROWTYPE_EDIT) // Edit row
			$tools_backups_grid->EditRowCnt++;
		if ($tools_backups->CurrentAction == "F") // Confirm row
			$tools_backups_grid->RestoreCurrentRowFormValues($tools_backups_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$tools_backups->RowAttrs = array_merge($tools_backups->RowAttrs, array('data-rowindex'=>$tools_backups_grid->RowCnt, 'id'=>'r' . $tools_backups_grid->RowCnt . '_tools_backups', 'data-rowtype'=>$tools_backups->RowType));

		// Render row
		$tools_backups_grid->RenderRow();

		// Render list options
		$tools_backups_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($tools_backups_grid->RowAction <> "delete" && $tools_backups_grid->RowAction <> "insertdelete" && !($tools_backups_grid->RowAction == "insert" && $tools_backups->CurrentAction == "F" && $tools_backups_grid->EmptyRow())) {
?>
	<tr<?php echo $tools_backups->RowAttributes() ?>>
<?php

// Render list options (body, left)
$tools_backups_grid->ListOptions->Render("body", "left", $tools_backups_grid->RowCnt);
?>
	<?php if ($tools_backups->idtools_backups->Visible) { // idtools_backups ?>
		<td<?php echo $tools_backups->idtools_backups->CellAttributes() ?>><span id="el<?php echo $tools_backups_grid->RowCnt ?>_tools_backups_idtools_backups" class="tools_backups_idtools_backups">
<?php if ($tools_backups->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" name="o<?php echo $tools_backups_grid->RowIndex ?>_idtools_backups" id="o<?php echo $tools_backups_grid->RowIndex ?>_idtools_backups" value="<?php echo ew_HtmlEncode($tools_backups->idtools_backups->OldValue) ?>" />
<?php } ?>
<?php if ($tools_backups->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span<?php echo $tools_backups->idtools_backups->ViewAttributes() ?>>
<?php echo $tools_backups->idtools_backups->EditValue ?></span>
<input type="hidden" name="x<?php echo $tools_backups_grid->RowIndex ?>_idtools_backups" id="x<?php echo $tools_backups_grid->RowIndex ?>_idtools_backups" value="<?php echo ew_HtmlEncode($tools_backups->idtools_backups->CurrentValue) ?>" />
<?php } ?>
<?php if ($tools_backups->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $tools_backups->idtools_backups->ViewAttributes() ?>>
<?php echo $tools_backups->idtools_backups->ListViewValue() ?></span>
<input type="hidden" name="x<?php echo $tools_backups_grid->RowIndex ?>_idtools_backups" id="x<?php echo $tools_backups_grid->RowIndex ?>_idtools_backups" value="<?php echo ew_HtmlEncode($tools_backups->idtools_backups->FormValue) ?>" />
<input type="hidden" name="o<?php echo $tools_backups_grid->RowIndex ?>_idtools_backups" id="o<?php echo $tools_backups_grid->RowIndex ?>_idtools_backups" value="<?php echo ew_HtmlEncode($tools_backups->idtools_backups->OldValue) ?>" />
<?php } ?>
</span></td>
	<?php } ?>
<a id="<?php echo $tools_backups_grid->PageObjName . "_row_" . $tools_backups_grid->RowCnt ?>"></a>
	<?php if ($tools_backups->domain_id->Visible) { // domain_id ?>
		<td<?php echo $tools_backups->domain_id->CellAttributes() ?>><span id="el<?php echo $tools_backups_grid->RowCnt ?>_tools_backups_domain_id" class="tools_backups_domain_id">
<?php if ($tools_backups->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($tools_backups->domain_id->getSessionValue() <> "") { ?>
<span<?php echo $tools_backups->domain_id->ViewAttributes() ?>>
<?php echo $tools_backups->domain_id->ListViewValue() ?></span>
<input type="hidden" id="x<?php echo $tools_backups_grid->RowIndex ?>_domain_id" name="x<?php echo $tools_backups_grid->RowIndex ?>_domain_id" value="<?php echo ew_HtmlEncode($tools_backups->domain_id->CurrentValue) ?>">
<?php } else { ?>
<?php
	$wrkonchange = trim(" " . @$tools_backups->domain_id->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$tools_backups->domain_id->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $tools_backups_grid->RowIndex ?>_domain_id" style="white-space: nowrap; z-index: <?php echo (9000 - $tools_backups_grid->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $tools_backups_grid->RowIndex ?>_domain_id" id="sv_x<?php echo $tools_backups_grid->RowIndex ?>_domain_id" value="<?php echo $tools_backups->domain_id->EditValue ?>" size="30" maxlength="45"<?php echo $tools_backups->domain_id->EditAttributes() ?> />&nbsp;<span id="em_x<?php echo $tools_backups_grid->RowIndex ?>_domain_id" class="ewMessage" style="display: none"><?php echo str_replace("%f", "phpimages/", $Language->Phrase("UnmatchedValue")) ?></span>
	<div id="sc_x<?php echo $tools_backups_grid->RowIndex ?>_domain_id" style="z-index: <?php echo (9000 - $tools_backups_grid->RowCnt * 10) ?>"></div>
</span>
<input type="hidden" name="x<?php echo $tools_backups_grid->RowIndex ?>_domain_id" id="x<?php echo $tools_backups_grid->RowIndex ?>_domain_id" value="<?php echo $tools_backups->domain_id->CurrentValue ?>"<?php echo $wrkonchange ?> />
<?php
 $sSqlWrk = "SELECT `id_domains`, `dominio` FROM `domains`";
 $sWhereWrk = "`dominio` LIKE '{query_value}%'";
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x<?php echo $tools_backups_grid->RowIndex ?>_domain_id" id="q_x<?php echo $tools_backups_grid->RowIndex ?>_domain_id" value="s=<?php echo TEAencrypt($sSqlWrk) ?>&fn=<?php echo urlencode($tools_backups->domain_id->LookupFn) ?>" />
<script type="text/javascript">
var oas = new ew_AutoSuggest("x<?php echo $tools_backups_grid->RowIndex ?>_domain_id", ftools_backupsgrid, false, EW_AUTO_SUGGEST_MAX_ENTRIES);
oas.formatResult = function(ar) {
	var dv = ar[1];
	for (var i = 2; i <= 4; i++)
		dv += (ar[i]) ? ew_ValueSeparator(i - 1, "x<?php echo $tools_backups_grid->RowIndex ?>_domain_id") + ar[i] : "";
	return dv;
}
oas.ac.typeAhead = false;
ftools_backupsgrid.AutoSuggests["x<?php echo $tools_backups_grid->RowIndex ?>_domain_id"] = oas;
</script>
<?php } ?>
<input type="hidden" name="o<?php echo $tools_backups_grid->RowIndex ?>_domain_id" id="o<?php echo $tools_backups_grid->RowIndex ?>_domain_id" value="<?php echo ew_HtmlEncode($tools_backups->domain_id->OldValue) ?>" />
<?php } ?>
<?php if ($tools_backups->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($tools_backups->domain_id->getSessionValue() <> "") { ?>
<span<?php echo $tools_backups->domain_id->ViewAttributes() ?>>
<?php echo $tools_backups->domain_id->ListViewValue() ?></span>
<input type="hidden" id="x<?php echo $tools_backups_grid->RowIndex ?>_domain_id" name="x<?php echo $tools_backups_grid->RowIndex ?>_domain_id" value="<?php echo ew_HtmlEncode($tools_backups->domain_id->CurrentValue) ?>">
<?php } else { ?>
<?php
	$wrkonchange = trim(" " . @$tools_backups->domain_id->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$tools_backups->domain_id->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $tools_backups_grid->RowIndex ?>_domain_id" style="white-space: nowrap; z-index: <?php echo (9000 - $tools_backups_grid->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $tools_backups_grid->RowIndex ?>_domain_id" id="sv_x<?php echo $tools_backups_grid->RowIndex ?>_domain_id" value="<?php echo $tools_backups->domain_id->EditValue ?>" size="30" maxlength="45"<?php echo $tools_backups->domain_id->EditAttributes() ?> />&nbsp;<span id="em_x<?php echo $tools_backups_grid->RowIndex ?>_domain_id" class="ewMessage" style="display: none"><?php echo str_replace("%f", "phpimages/", $Language->Phrase("UnmatchedValue")) ?></span>
	<div id="sc_x<?php echo $tools_backups_grid->RowIndex ?>_domain_id" style="z-index: <?php echo (9000 - $tools_backups_grid->RowCnt * 10) ?>"></div>
</span>
<input type="hidden" name="x<?php echo $tools_backups_grid->RowIndex ?>_domain_id" id="x<?php echo $tools_backups_grid->RowIndex ?>_domain_id" value="<?php echo $tools_backups->domain_id->CurrentValue ?>"<?php echo $wrkonchange ?> />
<?php
 $sSqlWrk = "SELECT `id_domains`, `dominio` FROM `domains`";
 $sWhereWrk = "`dominio` LIKE '{query_value}%'";
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x<?php echo $tools_backups_grid->RowIndex ?>_domain_id" id="q_x<?php echo $tools_backups_grid->RowIndex ?>_domain_id" value="s=<?php echo TEAencrypt($sSqlWrk) ?>&fn=<?php echo urlencode($tools_backups->domain_id->LookupFn) ?>" />
<script type="text/javascript">
var oas = new ew_AutoSuggest("x<?php echo $tools_backups_grid->RowIndex ?>_domain_id", ftools_backupsgrid, false, EW_AUTO_SUGGEST_MAX_ENTRIES);
oas.formatResult = function(ar) {
	var dv = ar[1];
	for (var i = 2; i <= 4; i++)
		dv += (ar[i]) ? ew_ValueSeparator(i - 1, "x<?php echo $tools_backups_grid->RowIndex ?>_domain_id") + ar[i] : "";
	return dv;
}
oas.ac.typeAhead = false;
ftools_backupsgrid.AutoSuggests["x<?php echo $tools_backups_grid->RowIndex ?>_domain_id"] = oas;
</script>
<?php } ?>
<?php } ?>
<?php if ($tools_backups->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $tools_backups->domain_id->ViewAttributes() ?>>
<?php echo $tools_backups->domain_id->ListViewValue() ?></span>
<input type="hidden" name="x<?php echo $tools_backups_grid->RowIndex ?>_domain_id" id="x<?php echo $tools_backups_grid->RowIndex ?>_domain_id" value="<?php echo ew_HtmlEncode($tools_backups->domain_id->FormValue) ?>" />
<input type="hidden" name="o<?php echo $tools_backups_grid->RowIndex ?>_domain_id" id="o<?php echo $tools_backups_grid->RowIndex ?>_domain_id" value="<?php echo ew_HtmlEncode($tools_backups->domain_id->OldValue) ?>" />
<?php } ?>
</span></td>
	<?php } ?>
	<?php if ($tools_backups->data->Visible) { // data ?>
		<td<?php echo $tools_backups->data->CellAttributes() ?>><span id="el<?php echo $tools_backups_grid->RowCnt ?>_tools_backups_data" class="tools_backups_data">
<?php if ($tools_backups_grid->RowAction == "insert") { // Add record ?>
<?php if ($tools_backups->CurrentAction <> "F") { ?>
<div id="old_x<?php echo $tools_backups_grid->RowIndex ?>_data">
<?php if ($tools_backups->data->LinkAttributes() <> "") { ?>
<?php if (!empty($tools_backups->data->Upload->DbValue)) { ?>
<a<?php echo $tools_backups->data->LinkAttributes() ?>><?php echo $tools_backups->data->EditValue ?></a>
<?php } elseif (!in_array($tools_backups->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } else { ?>
<?php if (!empty($tools_backups->data->Upload->DbValue)) { ?>
<?php echo $tools_backups->data->EditValue ?>
<?php } elseif (!in_array($tools_backups->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } ?>
</div>
<div id="new_x<?php echo $tools_backups_grid->RowIndex ?>_data">
<?php if ($tools_backups->data->ReadOnly) { ?>
<?php if (!empty($tools_backups->data->Upload->DbValue)) { ?>
<input type="hidden" name="a<?php echo $tools_backups_grid->RowIndex ?>_data" id="a<?php echo $tools_backups_grid->RowIndex ?>_data" value="1" />
<?php } ?>
<?php } else { ?>
<?php if (!empty($tools_backups->data->Upload->DbValue)) { ?>
<label><input type="radio" name="a<?php echo $tools_backups_grid->RowIndex ?>_data" id="a<?php echo $tools_backups_grid->RowIndex ?>_data" value="1" checked="checked" /><?php echo $Language->Phrase("Keep") ?></label>&nbsp;
<label><input type="radio" name="a<?php echo $tools_backups_grid->RowIndex ?>_data" id="a<?php echo $tools_backups_grid->RowIndex ?>_data" value="2" /><?php echo $Language->Phrase("Remove") ?></label>&nbsp;
<label><input type="radio" name="a<?php echo $tools_backups_grid->RowIndex ?>_data" id="a<?php echo $tools_backups_grid->RowIndex ?>_data" value="3" /><?php echo $Language->Phrase("Replace") ?><br /></label>
<?php $tools_backups->data->EditAttrs["onchange"] = "this.form.a" . $tools_backups_grid->RowIndex . "_data[2].checked=true;" . @$tools_backups->data->EditAttrs["onchange"]; ?>
<?php } else { ?>
<input type="hidden" name="a<?php echo $tools_backups_grid->RowIndex ?>_data" id="a<?php echo $tools_backups_grid->RowIndex ?>_data" value="3" />
<?php } ?>
<input type="file" name="x<?php echo $tools_backups_grid->RowIndex ?>_data" id="x<?php echo $tools_backups_grid->RowIndex ?>_data" size="30"<?php echo $tools_backups->data->EditAttributes() ?> />
<?php } ?>
</div>
<?php } else { ?>
<input type="hidden" name="a_data" id="a_data" value="<?php echo $tools_backups->data->Upload->Action ?>" />
<?php
if ($tools_backups->data->Upload->Action == "1") {
	if (!ew_Empty($tools_backups->data->Upload->DbValue)) {
?>
<a href="ewbv9.php?tbl=<?php echo $tools_backups->TableVar ?>&fld=x_data&rnd=<?php echo ew_Random() ?>&idx=<?php echo $tools_backups_grid->RowIndex ?>&db=1" target="_blank"><?php echo $tools_backups->data->FldCaption() ?></a>
<?php
	}
} elseif ($tools_backups->data->Upload->Action == "2") {
} else {
	if (!ew_Empty($tools_backups->data->Upload->Value)) {
?>
<a href="ewbv9.php?tbl=<?php echo $tools_backups->TableVar ?>&fld=x_data&rnd=<?php echo ew_Random() ?>&idx=<?php echo $tools_backups_grid->RowIndex ?>" target="_blank"><?php echo $tools_backups->data->FldCaption() ?></a>
<?php
	}
}
?>
<?php } ?>
<input type="hidden" name="o<?php echo $tools_backups_grid->RowIndex ?>_data" id="o<?php echo $tools_backups_grid->RowIndex ?>_data" value="<?php echo ew_HtmlEncode($tools_backups->data->OldValue) ?>" />
<?php } else { // Edit record ?>
<?php if ($tools_backups->CurrentAction <> "F") { ?>
<div id="old_x<?php echo $tools_backups_grid->RowIndex ?>_data">
<?php if ($tools_backups->data->LinkAttributes() <> "") { ?>
<?php if (!empty($tools_backups->data->Upload->DbValue)) { ?>
<a<?php echo $tools_backups->data->LinkAttributes() ?>><?php echo $tools_backups->data->EditValue ?></a>
<?php } elseif (!in_array($tools_backups->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } else { ?>
<?php if (!empty($tools_backups->data->Upload->DbValue)) { ?>
<?php echo $tools_backups->data->EditValue ?>
<?php } elseif (!in_array($tools_backups->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } ?>
</div>
<div id="new_x<?php echo $tools_backups_grid->RowIndex ?>_data">
<?php if ($tools_backups->data->ReadOnly) { ?>
<?php if (!empty($tools_backups->data->Upload->DbValue)) { ?>
<input type="hidden" name="a<?php echo $tools_backups_grid->RowIndex ?>_data" id="a<?php echo $tools_backups_grid->RowIndex ?>_data" value="1" />
<?php } ?>
<?php } else { ?>
<?php if (!empty($tools_backups->data->Upload->DbValue)) { ?>
<label><input type="radio" name="a<?php echo $tools_backups_grid->RowIndex ?>_data" id="a<?php echo $tools_backups_grid->RowIndex ?>_data" value="1" checked="checked" /><?php echo $Language->Phrase("Keep") ?></label>&nbsp;
<label><input type="radio" name="a<?php echo $tools_backups_grid->RowIndex ?>_data" id="a<?php echo $tools_backups_grid->RowIndex ?>_data" value="2" /><?php echo $Language->Phrase("Remove") ?></label>&nbsp;
<label><input type="radio" name="a<?php echo $tools_backups_grid->RowIndex ?>_data" id="a<?php echo $tools_backups_grid->RowIndex ?>_data" value="3" /><?php echo $Language->Phrase("Replace") ?><br /></label>
<?php $tools_backups->data->EditAttrs["onchange"] = "this.form.a" . $tools_backups_grid->RowIndex . "_data[2].checked=true;" . @$tools_backups->data->EditAttrs["onchange"]; ?>
<?php } else { ?>
<input type="hidden" name="a<?php echo $tools_backups_grid->RowIndex ?>_data" id="a<?php echo $tools_backups_grid->RowIndex ?>_data" value="3" />
<?php } ?>
<input type="file" name="x<?php echo $tools_backups_grid->RowIndex ?>_data" id="x<?php echo $tools_backups_grid->RowIndex ?>_data" size="30"<?php echo $tools_backups->data->EditAttributes() ?> />
<?php } ?>
</div>
<?php } else { ?>
<div id="old_x<?php echo $tools_backups_grid->RowIndex ?>_data">
<?php if ($tools_backups->data->LinkAttributes() <> "") { ?>
<?php if (!empty($tools_backups->data->Upload->DbValue)) { ?>
<a<?php echo $tools_backups->data->LinkAttributes() ?>><?php echo $tools_backups->data->ViewValue ?></a>
<?php } elseif (!in_array($tools_backups->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } else { ?>
<?php if (!empty($tools_backups->data->Upload->DbValue)) { ?>
<?php echo $tools_backups->data->ViewValue ?>
<?php } elseif (!in_array($tools_backups->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } ?>
<?php if (!ew_Empty($tools_backups->data->Upload->DbValue) && $tools_backups->data->Upload->Action == "3") echo "[" . $Language->Phrase("Old") . "]"; ?>
</div>
<div id="new_x<?php echo $tools_backups_grid->RowIndex ?>_data">
<input type="hidden" name="a_data" id="a_data" value="<?php echo $tools_backups->data->Upload->Action ?>" />
<?php
if ($tools_backups->data->Upload->Action == "1") {
	echo "[" . $Language->Phrase("Keep") . "]";
} elseif ($tools_backups->data->Upload->Action == "2") {
	echo "[" . $Language->Phrase("Remove") . "]";
} else {
	if (!ew_Empty($tools_backups->data->Upload->Value)) {
?>
<a href="ewbv9.php?tbl=<?php echo $tools_backups->TableVar ?>&fld=x_data&rnd=<?php echo ew_Random() ?>&idx=<?php echo $tools_backups_grid->RowIndex ?>" target="_blank"><?php echo $tools_backups->data->FldCaption() ?></a>
<?php
	}
}
?>
<?php if (!ew_Empty($tools_backups->data->Upload->DbValue) && $tools_backups->data->Upload->Action == "3") echo "[" . $Language->Phrase("New") . "]"; ?>
</div>
<?php } ?>
<?php } ?>
</span></td>
	<?php } ?>
	<?php if ($tools_backups->date->Visible) { // date ?>
		<td<?php echo $tools_backups->date->CellAttributes() ?>><span id="el<?php echo $tools_backups_grid->RowCnt ?>_tools_backups_date" class="tools_backups_date">
<?php if ($tools_backups->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="text" name="x<?php echo $tools_backups_grid->RowIndex ?>_date" id="x<?php echo $tools_backups_grid->RowIndex ?>_date" value="<?php echo $tools_backups->date->EditValue ?>"<?php echo $tools_backups->date->EditAttributes() ?> />
<input type="hidden" name="o<?php echo $tools_backups_grid->RowIndex ?>_date" id="o<?php echo $tools_backups_grid->RowIndex ?>_date" value="<?php echo ew_HtmlEncode($tools_backups->date->OldValue) ?>" />
<?php } ?>
<?php if ($tools_backups->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<input type="text" name="x<?php echo $tools_backups_grid->RowIndex ?>_date" id="x<?php echo $tools_backups_grid->RowIndex ?>_date" value="<?php echo $tools_backups->date->EditValue ?>"<?php echo $tools_backups->date->EditAttributes() ?> />
<?php } ?>
<?php if ($tools_backups->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $tools_backups->date->ViewAttributes() ?>>
<?php echo $tools_backups->date->ListViewValue() ?></span>
<input type="hidden" name="x<?php echo $tools_backups_grid->RowIndex ?>_date" id="x<?php echo $tools_backups_grid->RowIndex ?>_date" value="<?php echo ew_HtmlEncode($tools_backups->date->FormValue) ?>" />
<input type="hidden" name="o<?php echo $tools_backups_grid->RowIndex ?>_date" id="o<?php echo $tools_backups_grid->RowIndex ?>_date" value="<?php echo ew_HtmlEncode($tools_backups->date->OldValue) ?>" />
<?php } ?>
</span></td>
	<?php } ?>
<?php

// Render list options (body, right)
$tools_backups_grid->ListOptions->Render("body", "right", $tools_backups_grid->RowCnt);
?>
	</tr>
<?php if ($tools_backups->RowType == EW_ROWTYPE_ADD || $tools_backups->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
ftools_backupsgrid.UpdateOpts(<?php echo $tools_backups_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($tools_backups->CurrentAction <> "gridadd" || $tools_backups->CurrentMode == "copy")
		if (!$tools_backups_grid->Recordset->EOF) $tools_backups_grid->Recordset->MoveNext();
}
?>
<?php
	if ($tools_backups->CurrentMode == "add" || $tools_backups->CurrentMode == "copy" || $tools_backups->CurrentMode == "edit") {
		$tools_backups_grid->RowIndex = '$rowindex$';
		$tools_backups_grid->LoadDefaultValues();

		// Set row properties
		$tools_backups->ResetAttrs();
		$tools_backups->RowAttrs = array_merge($tools_backups->RowAttrs, array('data-rowindex'=>$tools_backups_grid->RowIndex, 'id'=>'r0_tools_backups', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($tools_backups->RowAttrs["class"], "ewTemplate");
		$tools_backups->RowType = EW_ROWTYPE_ADD;

		// Render row
		$tools_backups_grid->RenderRow();

		// Render list options
		$tools_backups_grid->RenderListOptions();
		$tools_backups_grid->StartRowCnt = 0;
?>
	<tr<?php echo $tools_backups->RowAttributes() ?>>
<?php

// Render list options (body, left)
$tools_backups_grid->ListOptions->Render("body", "left", $tools_backups_grid->RowIndex);
?>
	<?php if ($tools_backups->idtools_backups->Visible) { // idtools_backups ?>
		<td><span2 id="el$rowindex$_tools_backups_idtools_backups" class="tools_backups_idtools_backups">
<?php if ($tools_backups->CurrentAction <> "F") { ?>
<?php } else { ?>
<span<?php echo $tools_backups->idtools_backups->ViewAttributes() ?>>
<?php echo $tools_backups->idtools_backups->ViewValue ?></span>
<input type="hidden" name="x<?php echo $tools_backups_grid->RowIndex ?>_idtools_backups" id="x<?php echo $tools_backups_grid->RowIndex ?>_idtools_backups" value="<?php echo ew_HtmlEncode($tools_backups->idtools_backups->FormValue) ?>" />
<?php } ?>
<input type="hidden" name="o<?php echo $tools_backups_grid->RowIndex ?>_idtools_backups" id="o<?php echo $tools_backups_grid->RowIndex ?>_idtools_backups" value="<?php echo ew_HtmlEncode($tools_backups->idtools_backups->OldValue) ?>" />
</span></td>
	<?php } ?>
	<?php if ($tools_backups->domain_id->Visible) { // domain_id ?>
		<td><span2 id="el$rowindex$_tools_backups_domain_id" class="tools_backups_domain_id">
<?php if ($tools_backups->CurrentAction <> "F") { ?>
<?php if ($tools_backups->domain_id->getSessionValue() <> "") { ?>
<span<?php echo $tools_backups->domain_id->ViewAttributes() ?>>
<?php echo $tools_backups->domain_id->ListViewValue() ?></span>
<input type="hidden" id="x<?php echo $tools_backups_grid->RowIndex ?>_domain_id" name="x<?php echo $tools_backups_grid->RowIndex ?>_domain_id" value="<?php echo ew_HtmlEncode($tools_backups->domain_id->CurrentValue) ?>">
<?php } else { ?>
<?php
	$wrkonchange = trim(" " . @$tools_backups->domain_id->EditAttrs["onchange"]);
	if ($wrkonchange <> "") $wrkonchange = " onchange=\"" . ew_JsEncode2($wrkonchange) . "\"";
	$tools_backups->domain_id->EditAttrs["onchange"] = "";
?>
<span id="as_x<?php echo $tools_backups_grid->RowIndex ?>_domain_id" style="white-space: nowrap; z-index: <?php echo (9000 - $tools_backups_grid->RowCnt * 10) ?>">
	<input type="text" name="sv_x<?php echo $tools_backups_grid->RowIndex ?>_domain_id" id="sv_x<?php echo $tools_backups_grid->RowIndex ?>_domain_id" value="<?php echo $tools_backups->domain_id->EditValue ?>" size="30" maxlength="45"<?php echo $tools_backups->domain_id->EditAttributes() ?> />&nbsp;<span id="em_x<?php echo $tools_backups_grid->RowIndex ?>_domain_id" class="ewMessage" style="display: none"><?php echo str_replace("%f", "phpimages/", $Language->Phrase("UnmatchedValue")) ?></span>
	<div id="sc_x<?php echo $tools_backups_grid->RowIndex ?>_domain_id" style="z-index: <?php echo (9000 - $tools_backups_grid->RowCnt * 10) ?>"></div>
</span>
<input type="hidden" name="x<?php echo $tools_backups_grid->RowIndex ?>_domain_id" id="x<?php echo $tools_backups_grid->RowIndex ?>_domain_id" value="<?php echo $tools_backups->domain_id->CurrentValue ?>"<?php echo $wrkonchange ?> />
<?php
 $sSqlWrk = "SELECT `id_domains`, `dominio` FROM `domains`";
 $sWhereWrk = "`dominio` LIKE '{query_value}%'";
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
 $sSqlWrk .= " LIMIT " . EW_AUTO_SUGGEST_MAX_ENTRIES;
?>
<input type="hidden" name="q_x<?php echo $tools_backups_grid->RowIndex ?>_domain_id" id="q_x<?php echo $tools_backups_grid->RowIndex ?>_domain_id" value="s=<?php echo TEAencrypt($sSqlWrk) ?>&fn=<?php echo urlencode($tools_backups->domain_id->LookupFn) ?>" />
<script type="text/javascript">
var oas = new ew_AutoSuggest("x<?php echo $tools_backups_grid->RowIndex ?>_domain_id", ftools_backupsgrid, false, EW_AUTO_SUGGEST_MAX_ENTRIES);
oas.formatResult = function(ar) {
	var dv = ar[1];
	for (var i = 2; i <= 4; i++)
		dv += (ar[i]) ? ew_ValueSeparator(i - 1, "x<?php echo $tools_backups_grid->RowIndex ?>_domain_id") + ar[i] : "";
	return dv;
}
oas.ac.typeAhead = false;
ftools_backupsgrid.AutoSuggests["x<?php echo $tools_backups_grid->RowIndex ?>_domain_id"] = oas;
</script>
<?php } ?>
<?php } else { ?>
<span<?php echo $tools_backups->domain_id->ViewAttributes() ?>>
<?php echo $tools_backups->domain_id->ViewValue ?></span>
<input type="hidden" name="x<?php echo $tools_backups_grid->RowIndex ?>_domain_id" id="x<?php echo $tools_backups_grid->RowIndex ?>_domain_id" value="<?php echo ew_HtmlEncode($tools_backups->domain_id->FormValue) ?>" />
<?php } ?>
<input type="hidden" name="o<?php echo $tools_backups_grid->RowIndex ?>_domain_id" id="o<?php echo $tools_backups_grid->RowIndex ?>_domain_id" value="<?php echo ew_HtmlEncode($tools_backups->domain_id->OldValue) ?>" />
</span></td>
	<?php } ?>
	<?php if ($tools_backups->data->Visible) { // data ?>
		<td><span2 id="el$rowindex$_tools_backups_data" class="tools_backups_data">
<?php if ($tools_backups->CurrentAction <> "F") { ?>
<div id="old_x<?php echo $tools_backups_grid->RowIndex ?>_data">
<?php if ($tools_backups->data->LinkAttributes() <> "") { ?>
<?php if (!empty($tools_backups->data->Upload->DbValue)) { ?>
<a<?php echo $tools_backups->data->LinkAttributes() ?>><?php echo $tools_backups->data->EditValue ?></a>
<?php } elseif (!in_array($tools_backups->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } else { ?>
<?php if (!empty($tools_backups->data->Upload->DbValue)) { ?>
<?php echo $tools_backups->data->EditValue ?>
<?php } elseif (!in_array($tools_backups->CurrentAction, array("I", "edit", "gridedit"))) { ?>	
&nbsp;
<?php } ?>
<?php } ?>
</div>
<div id="new_x<?php echo $tools_backups_grid->RowIndex ?>_data">
<?php if ($tools_backups->data->ReadOnly) { ?>
<?php if (!empty($tools_backups->data->Upload->DbValue)) { ?>
<input type="hidden" name="a<?php echo $tools_backups_grid->RowIndex ?>_data" id="a<?php echo $tools_backups_grid->RowIndex ?>_data" value="1" />
<?php } ?>
<?php } else { ?>
<?php if (!empty($tools_backups->data->Upload->DbValue)) { ?>
<label><input type="radio" name="a<?php echo $tools_backups_grid->RowIndex ?>_data" id="a<?php echo $tools_backups_grid->RowIndex ?>_data" value="1" checked="checked" /><?php echo $Language->Phrase("Keep") ?></label>&nbsp;
<label><input type="radio" name="a<?php echo $tools_backups_grid->RowIndex ?>_data" id="a<?php echo $tools_backups_grid->RowIndex ?>_data" value="2" /><?php echo $Language->Phrase("Remove") ?></label>&nbsp;
<label><input type="radio" name="a<?php echo $tools_backups_grid->RowIndex ?>_data" id="a<?php echo $tools_backups_grid->RowIndex ?>_data" value="3" /><?php echo $Language->Phrase("Replace") ?><br /></label>
<?php $tools_backups->data->EditAttrs["onchange"] = "this.form.a" . $tools_backups_grid->RowIndex . "_data[2].checked=true;" . @$tools_backups->data->EditAttrs["onchange"]; ?>
<?php } else { ?>
<input type="hidden" name="a<?php echo $tools_backups_grid->RowIndex ?>_data" id="a<?php echo $tools_backups_grid->RowIndex ?>_data" value="3" />
<?php } ?>
<input type="file" name="x<?php echo $tools_backups_grid->RowIndex ?>_data" id="x<?php echo $tools_backups_grid->RowIndex ?>_data" size="30"<?php echo $tools_backups->data->EditAttributes() ?> />
<?php } ?>
</div>
<?php } else { ?>
<input type="hidden" name="a_data" id="a_data" value="<?php echo $tools_backups->data->Upload->Action ?>" />
<?php
if ($tools_backups->data->Upload->Action == "1") {
	if (!ew_Empty($tools_backups->data->Upload->DbValue)) {
?>
<a href="ewbv9.php?tbl=<?php echo $tools_backups->TableVar ?>&fld=x_data&rnd=<?php echo ew_Random() ?>&idx=<?php echo $tools_backups_grid->RowIndex ?>&db=1" target="_blank"><?php echo $tools_backups->data->FldCaption() ?></a>
<?php
	}
} elseif ($tools_backups->data->Upload->Action == "2") {
} else {
	if (!ew_Empty($tools_backups->data->Upload->Value)) {
?>
<a href="ewbv9.php?tbl=<?php echo $tools_backups->TableVar ?>&fld=x_data&rnd=<?php echo ew_Random() ?>&idx=<?php echo $tools_backups_grid->RowIndex ?>" target="_blank"><?php echo $tools_backups->data->FldCaption() ?></a>
<?php
	}
}
?>
<?php } ?>
<input type="hidden" name="o<?php echo $tools_backups_grid->RowIndex ?>_data" id="o<?php echo $tools_backups_grid->RowIndex ?>_data" value="<?php echo ew_HtmlEncode($tools_backups->data->OldValue) ?>" />
</span></td>
	<?php } ?>
	<?php if ($tools_backups->date->Visible) { // date ?>
		<td><span2 id="el$rowindex$_tools_backups_date" class="tools_backups_date">
<?php if ($tools_backups->CurrentAction <> "F") { ?>
<input type="text" name="x<?php echo $tools_backups_grid->RowIndex ?>_date" id="x<?php echo $tools_backups_grid->RowIndex ?>_date" value="<?php echo $tools_backups->date->EditValue ?>"<?php echo $tools_backups->date->EditAttributes() ?> />
<?php } else { ?>
<span<?php echo $tools_backups->date->ViewAttributes() ?>>
<?php echo $tools_backups->date->ViewValue ?></span>
<input type="hidden" name="x<?php echo $tools_backups_grid->RowIndex ?>_date" id="x<?php echo $tools_backups_grid->RowIndex ?>_date" value="<?php echo ew_HtmlEncode($tools_backups->date->FormValue) ?>" />
<?php } ?>
<input type="hidden" name="o<?php echo $tools_backups_grid->RowIndex ?>_date" id="o<?php echo $tools_backups_grid->RowIndex ?>_date" value="<?php echo ew_HtmlEncode($tools_backups->date->OldValue) ?>" />
</span></td>
	<?php } ?>
<?php

// Render list options (body, right)
$tools_backups_grid->ListOptions->Render("body", "right", $tools_backups_grid->RowCnt);
?>
<script type="text/javascript">
ftools_backupsgrid.UpdateOpts(<?php echo $tools_backups_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
<!--</table>-->
<?php if ($tools_backups->CurrentMode == "add" || $tools_backups->CurrentMode == "copy") { ?>
<input class="btn btn-large btn-success" type="submit" />
<input type="hidden" name="a_list" id="a_list" value="gridinsert" />
<input type="hidden" name="key_count" id="key_count" value="<?php echo $tools_backups_grid->KeyCount ?>" />
<?php echo $tools_backups_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($tools_backups->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="key_count" id="key_count" value="<?php echo $tools_backups_grid->KeyCount ?>" />
<?php echo $tools_backups_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($tools_backups->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" id="detailpage" value="ftools_backupsgrid">
<?php

// Close recordset
if ($tools_backups_grid->Recordset)
	$tools_backups_grid->Recordset->Close();
?>
<?php if (($tools_backups->CurrentMode == "add" || $tools_backups->CurrentMode == "copy" || $tools_backups->CurrentMode == "edit") && $tools_backups->CurrentAction != "F") { // add/copy/edit mode ?>
<div class="ewGridLowerPanel">
</div>
<?php } ?>
</div>
<?php if ($tools_backups->Export == "") { ?>
<script type="text/javascript">
ftools_backupsgrid.Init();
</script>
<?php } ?>
<?php
$tools_backups_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$tools_backups_grid->Page_Terminate();
$Page = &$MasterPage;
?>
