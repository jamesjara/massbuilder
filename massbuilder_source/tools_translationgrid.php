<?php include_once "usersinfo.php" ?>
<?php

// Create page object
if (!isset($tools_translation_grid)) $tools_translation_grid = new ctools_translation_grid();

// Page init
$tools_translation_grid->Page_Init();

// Page main
$tools_translation_grid->Page_Main();
?>
<?php if ($tools_translation->Export == "") { ?>
<script type="text/javascript">

// Page object
var tools_translation_grid = new ew_Page("tools_translation_grid");
tools_translation_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = tools_translation_grid.PageID; // For backward compatibility

// Form object
var ftools_translationgrid = new ew_Form("ftools_translationgrid");

// Validate form
ftools_translationgrid.Validate = function(fobj) {
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
		elm = fobj.elements["x" + infix + "_Status"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($tools_translation->Status->FldErrMsg()) ?>");

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
ftools_translationgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "domain_id", false)) return false;
	if (ew_ValueChanged(fobj, infix, "to_domain", false)) return false;
	if (ew_ValueChanged(fobj, infix, "lenguaje", false)) return false;
	if (ew_ValueChanged(fobj, infix, "log", false)) return false;
	if (ew_ValueChanged(fobj, infix, "Status", false)) return false;
	return true;
}

// Form_CustomValidate event
ftools_translationgrid.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftools_translationgrid.ValidateRequired = true;
<?php } else { ?>
ftools_translationgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ftools_translationgrid.Lists["x_domain_id"] = {"LinkField":"x_id_domains","Ajax":null,"AutoFill":false,"DisplayFields":["x_dominio","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};
ftools_translationgrid.Lists["x_to_domain"] = {"LinkField":"x_id_domains","Ajax":true,"AutoFill":false,"DisplayFields":["x_dominio","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<?php } ?>
<?php
if ($tools_translation->CurrentAction == "gridadd") {
	if ($tools_translation->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$tools_translation_grid->TotalRecs = $tools_translation->SelectRecordCount();
			$tools_translation_grid->Recordset = $tools_translation_grid->LoadRecordset($tools_translation_grid->StartRec-1, $tools_translation_grid->DisplayRecs);
		} else {
			if ($tools_translation_grid->Recordset = $tools_translation_grid->LoadRecordset())
				$tools_translation_grid->TotalRecs = $tools_translation_grid->Recordset->RecordCount();
		}
		$tools_translation_grid->StartRec = 1;
		$tools_translation_grid->DisplayRecs = $tools_translation_grid->TotalRecs;
	} else {
		$tools_translation->CurrentFilter = "0=1";
		$tools_translation_grid->StartRec = 1;
		$tools_translation_grid->DisplayRecs = $tools_translation->GridAddRowCount;
	}
	$tools_translation_grid->TotalRecs = $tools_translation_grid->DisplayRecs;
	$tools_translation_grid->StopRec = $tools_translation_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$tools_translation_grid->TotalRecs = $tools_translation->SelectRecordCount();
	} else {
		if ($tools_translation_grid->Recordset = $tools_translation_grid->LoadRecordset())
			$tools_translation_grid->TotalRecs = $tools_translation_grid->Recordset->RecordCount();
	}
	$tools_translation_grid->StartRec = 1;
	$tools_translation_grid->DisplayRecs = $tools_translation_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$tools_translation_grid->Recordset = $tools_translation_grid->LoadRecordset($tools_translation_grid->StartRec-1, $tools_translation_grid->DisplayRecs);
}
?>
<p style="white-spaceJAMES: nowrapJAMES;"><h4 id="ewPageCaption" class="page-header ewTitle ewTableTitle"> <?php if ($tools_translation->CurrentMode == "add" || $tools_translation->CurrentMode == "copy") { ?><?php echo $Language->Phrase("Add") ?><?php } elseif ($tools_translation->CurrentMode == "edit") { ?><?php echo $Language->Phrase("Edit") ?><?php } ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $tools_translation->TableCaption() ?></h4></p>
</p>
<?php $tools_translation_grid->ShowPageHeader(); ?>
<?php
$tools_translation_grid->ShowMessage();
?>
<div id="ftools_translationgrid" class="ewForm">
<div id="gmp_tools_translation" class="ewGridMiddlePanel row-fluid">
<ul class="thumbnails">
<table id="tbl_tools_translationgrid" class="ewTable ewTableSeparate table table-striped table-bordered ">
<?php echo $tools_translation->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$tools_translation_grid->RenderListOptions();

// Render list options (header, left)
$tools_translation_grid->ListOptions->Render("header", "left");
?>
<?php if ($tools_translation->idtools_translation->Visible) { // idtools_translation ?>
	<?php if ($tools_translation->SortUrl($tools_translation->idtools_translation) == "") { ?>
		<th><span id="elh_tools_translation_idtools_translation" class="tools_translation_idtools_translation">
		<div class="ewTableHeaderBtn"><?php echo $tools_translation->idtools_translation->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div><span id="elh_tools_translation_idtools_translation" class="tools_translation_idtools_translation">
			<div class="ewTableHeaderBtn">			
			<?php echo $tools_translation->idtools_translation->FldCaption() ?>
			<?php if ($tools_translation->idtools_translation->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($tools_translation->idtools_translation->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($tools_translation->domain_id->Visible) { // domain_id ?>
	<?php if ($tools_translation->SortUrl($tools_translation->domain_id) == "") { ?>
		<th><span id="elh_tools_translation_domain_id" class="tools_translation_domain_id">
		<div class="ewTableHeaderBtn"><?php echo $tools_translation->domain_id->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div><span id="elh_tools_translation_domain_id" class="tools_translation_domain_id">
			<div class="ewTableHeaderBtn">			
			<?php echo $tools_translation->domain_id->FldCaption() ?>
			<?php if ($tools_translation->domain_id->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($tools_translation->domain_id->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($tools_translation->to_domain->Visible) { // to_domain ?>
	<?php if ($tools_translation->SortUrl($tools_translation->to_domain) == "") { ?>
		<th><span id="elh_tools_translation_to_domain" class="tools_translation_to_domain">
		<div class="ewTableHeaderBtn"><?php echo $tools_translation->to_domain->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div><span id="elh_tools_translation_to_domain" class="tools_translation_to_domain">
			<div class="ewTableHeaderBtn">			
			<?php echo $tools_translation->to_domain->FldCaption() ?>
			<?php if ($tools_translation->to_domain->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($tools_translation->to_domain->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($tools_translation->date->Visible) { // date ?>
	<?php if ($tools_translation->SortUrl($tools_translation->date) == "") { ?>
		<th><span id="elh_tools_translation_date" class="tools_translation_date">
		<div class="ewTableHeaderBtn"><?php echo $tools_translation->date->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div><span id="elh_tools_translation_date" class="tools_translation_date">
			<div class="ewTableHeaderBtn">			
			<?php echo $tools_translation->date->FldCaption() ?>
			<?php if ($tools_translation->date->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($tools_translation->date->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($tools_translation->lenguaje->Visible) { // lenguaje ?>
	<?php if ($tools_translation->SortUrl($tools_translation->lenguaje) == "") { ?>
		<th><span id="elh_tools_translation_lenguaje" class="tools_translation_lenguaje">
		<div class="ewTableHeaderBtn"><?php echo $tools_translation->lenguaje->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div><span id="elh_tools_translation_lenguaje" class="tools_translation_lenguaje">
			<div class="ewTableHeaderBtn">			
			<?php echo $tools_translation->lenguaje->FldCaption() ?>
			<?php if ($tools_translation->lenguaje->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($tools_translation->lenguaje->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($tools_translation->log->Visible) { // log ?>
	<?php if ($tools_translation->SortUrl($tools_translation->log) == "") { ?>
		<th><span id="elh_tools_translation_log" class="tools_translation_log">
		<div class="ewTableHeaderBtn"><?php echo $tools_translation->log->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div><span id="elh_tools_translation_log" class="tools_translation_log">
			<div class="ewTableHeaderBtn">			
			<?php echo $tools_translation->log->FldCaption() ?>
			<?php if ($tools_translation->log->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($tools_translation->log->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($tools_translation->Status->Visible) { // Status ?>
	<?php if ($tools_translation->SortUrl($tools_translation->Status) == "") { ?>
		<th><span id="elh_tools_translation_Status" class="tools_translation_Status">
		<div class="ewTableHeaderBtn"><?php echo $tools_translation->Status->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div><span id="elh_tools_translation_Status" class="tools_translation_Status">
			<div class="ewTableHeaderBtn">			
			<?php echo $tools_translation->Status->FldCaption() ?>
			<?php if ($tools_translation->Status->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($tools_translation->Status->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$tools_translation_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$tools_translation_grid->StartRec = 1;
$tools_translation_grid->StopRec = $tools_translation_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue("key_count") && ($tools_translation->CurrentAction == "gridadd" || $tools_translation->CurrentAction == "gridedit" || $tools_translation->CurrentAction == "F")) {
		$tools_translation_grid->KeyCount = $objForm->GetValue("key_count");
		$tools_translation_grid->StopRec = $tools_translation_grid->KeyCount;
	}
}
$tools_translation_grid->RecCnt = $tools_translation_grid->StartRec - 1;
if ($tools_translation_grid->Recordset && !$tools_translation_grid->Recordset->EOF) {
	$tools_translation_grid->Recordset->MoveFirst();
	if (!$bSelectLimit && $tools_translation_grid->StartRec > 1)
		$tools_translation_grid->Recordset->Move($tools_translation_grid->StartRec - 1);
} elseif (!$tools_translation->AllowAddDeleteRow && $tools_translation_grid->StopRec == 0) {
	$tools_translation_grid->StopRec = $tools_translation->GridAddRowCount;
}

// Initialize aggregate
$tools_translation->RowType = EW_ROWTYPE_AGGREGATEINIT;
$tools_translation->ResetAttrs();
$tools_translation_grid->RenderRow();
if ($tools_translation->CurrentAction == "gridadd")
	$tools_translation_grid->RowIndex = 0;
if ($tools_translation->CurrentAction == "gridedit")
	$tools_translation_grid->RowIndex = 0;
while ($tools_translation_grid->RecCnt < $tools_translation_grid->StopRec) {
	$tools_translation_grid->RecCnt++;
	if (intval($tools_translation_grid->RecCnt) >= intval($tools_translation_grid->StartRec)) {
		$tools_translation_grid->RowCnt++;
		if ($tools_translation->CurrentAction == "gridadd" || $tools_translation->CurrentAction == "gridedit" || $tools_translation->CurrentAction == "F") {
			$tools_translation_grid->RowIndex++;
			$objForm->Index = $tools_translation_grid->RowIndex;
			if ($objForm->HasValue("k_action"))
				$tools_translation_grid->RowAction = strval($objForm->GetValue("k_action"));
			elseif ($tools_translation->CurrentAction == "gridadd")
				$tools_translation_grid->RowAction = "insert";
			else
				$tools_translation_grid->RowAction = "";
		}

		// Set up key count
		$tools_translation_grid->KeyCount = $tools_translation_grid->RowIndex;

		// Init row class and style
		$tools_translation->ResetAttrs();
		$tools_translation->CssClass = "";
		if ($tools_translation->CurrentAction == "gridadd") {
			if ($tools_translation->CurrentMode == "copy") {
				$tools_translation_grid->LoadRowValues($tools_translation_grid->Recordset); // Load row values
				$tools_translation_grid->SetRecordKey($tools_translation_grid->RowOldKey, $tools_translation_grid->Recordset); // Set old record key
			} else {
				$tools_translation_grid->LoadDefaultValues(); // Load default values
				$tools_translation_grid->RowOldKey = ""; // Clear old key value
			}
		} elseif ($tools_translation->CurrentAction == "gridedit") {
			$tools_translation_grid->LoadRowValues($tools_translation_grid->Recordset); // Load row values
		}
		$tools_translation->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($tools_translation->CurrentAction == "gridadd") // Grid add
			$tools_translation->RowType = EW_ROWTYPE_ADD; // Render add
		if ($tools_translation->CurrentAction == "gridadd" && $tools_translation->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$tools_translation_grid->RestoreCurrentRowFormValues($tools_translation_grid->RowIndex); // Restore form values
		if ($tools_translation->CurrentAction == "gridedit") { // Grid edit
			if ($tools_translation->EventCancelled) {
				$tools_translation_grid->RestoreCurrentRowFormValues($tools_translation_grid->RowIndex); // Restore form values
			}
			if ($tools_translation_grid->RowAction == "insert")
				$tools_translation->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$tools_translation->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($tools_translation->CurrentAction == "gridedit" && ($tools_translation->RowType == EW_ROWTYPE_EDIT || $tools_translation->RowType == EW_ROWTYPE_ADD) && $tools_translation->EventCancelled) // Update failed
			$tools_translation_grid->RestoreCurrentRowFormValues($tools_translation_grid->RowIndex); // Restore form values
		if ($tools_translation->RowType == EW_ROWTYPE_EDIT) // Edit row
			$tools_translation_grid->EditRowCnt++;
		if ($tools_translation->CurrentAction == "F") // Confirm row
			$tools_translation_grid->RestoreCurrentRowFormValues($tools_translation_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$tools_translation->RowAttrs = array_merge($tools_translation->RowAttrs, array('data-rowindex'=>$tools_translation_grid->RowCnt, 'id'=>'r' . $tools_translation_grid->RowCnt . '_tools_translation', 'data-rowtype'=>$tools_translation->RowType));

		// Render row
		$tools_translation_grid->RenderRow();

		// Render list options
		$tools_translation_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($tools_translation_grid->RowAction <> "delete" && $tools_translation_grid->RowAction <> "insertdelete" && !($tools_translation_grid->RowAction == "insert" && $tools_translation->CurrentAction == "F" && $tools_translation_grid->EmptyRow())) {
?>
	<tr<?php echo $tools_translation->RowAttributes() ?>>
<?php

// Render list options (body, left)
$tools_translation_grid->ListOptions->Render("body", "left", $tools_translation_grid->RowCnt);
?>
	<?php if ($tools_translation->idtools_translation->Visible) { // idtools_translation ?>
		<td<?php echo $tools_translation->idtools_translation->CellAttributes() ?>><span id="el<?php echo $tools_translation_grid->RowCnt ?>_tools_translation_idtools_translation" class="tools_translation_idtools_translation">
<?php if ($tools_translation->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" name="o<?php echo $tools_translation_grid->RowIndex ?>_idtools_translation" id="o<?php echo $tools_translation_grid->RowIndex ?>_idtools_translation" value="<?php echo ew_HtmlEncode($tools_translation->idtools_translation->OldValue) ?>" />
<?php } ?>
<?php if ($tools_translation->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span<?php echo $tools_translation->idtools_translation->ViewAttributes() ?>>
<?php echo $tools_translation->idtools_translation->EditValue ?></span>
<input type="hidden" name="x<?php echo $tools_translation_grid->RowIndex ?>_idtools_translation" id="x<?php echo $tools_translation_grid->RowIndex ?>_idtools_translation" value="<?php echo ew_HtmlEncode($tools_translation->idtools_translation->CurrentValue) ?>" />
<?php } ?>
<?php if ($tools_translation->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $tools_translation->idtools_translation->ViewAttributes() ?>>
<?php echo $tools_translation->idtools_translation->ListViewValue() ?></span>
<input type="hidden" name="x<?php echo $tools_translation_grid->RowIndex ?>_idtools_translation" id="x<?php echo $tools_translation_grid->RowIndex ?>_idtools_translation" value="<?php echo ew_HtmlEncode($tools_translation->idtools_translation->FormValue) ?>" />
<input type="hidden" name="o<?php echo $tools_translation_grid->RowIndex ?>_idtools_translation" id="o<?php echo $tools_translation_grid->RowIndex ?>_idtools_translation" value="<?php echo ew_HtmlEncode($tools_translation->idtools_translation->OldValue) ?>" />
<?php } ?>
</span></td>
	<?php } ?>
<a id="<?php echo $tools_translation_grid->PageObjName . "_row_" . $tools_translation_grid->RowCnt ?>"></a>
	<?php if ($tools_translation->domain_id->Visible) { // domain_id ?>
		<td<?php echo $tools_translation->domain_id->CellAttributes() ?>><span id="el<?php echo $tools_translation_grid->RowCnt ?>_tools_translation_domain_id" class="tools_translation_domain_id">
<?php if ($tools_translation->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<?php if ($tools_translation->domain_id->getSessionValue() <> "") { ?>
<span<?php echo $tools_translation->domain_id->ViewAttributes() ?>>
<?php echo $tools_translation->domain_id->ListViewValue() ?></span>
<input type="hidden" id="x<?php echo $tools_translation_grid->RowIndex ?>_domain_id" name="x<?php echo $tools_translation_grid->RowIndex ?>_domain_id" value="<?php echo ew_HtmlEncode($tools_translation->domain_id->CurrentValue) ?>">
<?php } else { ?>
<select id="x<?php echo $tools_translation_grid->RowIndex ?>_domain_id" name="x<?php echo $tools_translation_grid->RowIndex ?>_domain_id"<?php echo $tools_translation->domain_id->EditAttributes() ?>>
<?php
if (is_array($tools_translation->domain_id->EditValue)) {
	$arwrk = $tools_translation->domain_id->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($tools_translation->domain_id->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $tools_translation->domain_id->OldValue = "";
?>
</select>
<script type="text/javascript">
ftools_translationgrid.Lists["x_domain_id"].Options = <?php echo (is_array($tools_translation->domain_id->EditValue)) ? ew_ArrayToJson($tools_translation->domain_id->EditValue, 1) : "[]" ?>;
</script>
<?php } ?>
<input type="hidden" name="o<?php echo $tools_translation_grid->RowIndex ?>_domain_id" id="o<?php echo $tools_translation_grid->RowIndex ?>_domain_id" value="<?php echo ew_HtmlEncode($tools_translation->domain_id->OldValue) ?>" />
<?php } ?>
<?php if ($tools_translation->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php if ($tools_translation->domain_id->getSessionValue() <> "") { ?>
<span<?php echo $tools_translation->domain_id->ViewAttributes() ?>>
<?php echo $tools_translation->domain_id->ListViewValue() ?></span>
<input type="hidden" id="x<?php echo $tools_translation_grid->RowIndex ?>_domain_id" name="x<?php echo $tools_translation_grid->RowIndex ?>_domain_id" value="<?php echo ew_HtmlEncode($tools_translation->domain_id->CurrentValue) ?>">
<?php } else { ?>
<select id="x<?php echo $tools_translation_grid->RowIndex ?>_domain_id" name="x<?php echo $tools_translation_grid->RowIndex ?>_domain_id"<?php echo $tools_translation->domain_id->EditAttributes() ?>>
<?php
if (is_array($tools_translation->domain_id->EditValue)) {
	$arwrk = $tools_translation->domain_id->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($tools_translation->domain_id->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $tools_translation->domain_id->OldValue = "";
?>
</select>
<script type="text/javascript">
ftools_translationgrid.Lists["x_domain_id"].Options = <?php echo (is_array($tools_translation->domain_id->EditValue)) ? ew_ArrayToJson($tools_translation->domain_id->EditValue, 1) : "[]" ?>;
</script>
<?php } ?>
<?php } ?>
<?php if ($tools_translation->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $tools_translation->domain_id->ViewAttributes() ?>>
<?php echo $tools_translation->domain_id->ListViewValue() ?></span>
<input type="hidden" name="x<?php echo $tools_translation_grid->RowIndex ?>_domain_id" id="x<?php echo $tools_translation_grid->RowIndex ?>_domain_id" value="<?php echo ew_HtmlEncode($tools_translation->domain_id->FormValue) ?>" />
<input type="hidden" name="o<?php echo $tools_translation_grid->RowIndex ?>_domain_id" id="o<?php echo $tools_translation_grid->RowIndex ?>_domain_id" value="<?php echo ew_HtmlEncode($tools_translation->domain_id->OldValue) ?>" />
<?php } ?>
</span></td>
	<?php } ?>
	<?php if ($tools_translation->to_domain->Visible) { // to_domain ?>
		<td<?php echo $tools_translation->to_domain->CellAttributes() ?>><span id="el<?php echo $tools_translation_grid->RowCnt ?>_tools_translation_to_domain" class="tools_translation_to_domain">
<?php if ($tools_translation->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<select id="x<?php echo $tools_translation_grid->RowIndex ?>_to_domain" name="x<?php echo $tools_translation_grid->RowIndex ?>_to_domain"<?php echo $tools_translation->to_domain->EditAttributes() ?>>
<?php
if (is_array($tools_translation->to_domain->EditValue)) {
	$arwrk = $tools_translation->to_domain->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($tools_translation->to_domain->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $tools_translation->to_domain->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `id_domains`, `dominio` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `domains`";
 $sWhereWrk = "";
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $tools_translation_grid->RowIndex ?>_to_domain" id="s_x<?php echo $tools_translation_grid->RowIndex ?>_to_domain" value="s=<?php echo TEAencrypt($sSqlWrk) ?>&fn=<?php echo urlencode($tools_translation->to_domain->LookupFn) ?>&f0=<?php echo TEAencrypt("`id_domains` = {filter_value}"); ?>&t0=3" />
<input type="hidden" name="o<?php echo $tools_translation_grid->RowIndex ?>_to_domain" id="o<?php echo $tools_translation_grid->RowIndex ?>_to_domain" value="<?php echo ew_HtmlEncode($tools_translation->to_domain->OldValue) ?>" />
<?php } ?>
<?php if ($tools_translation->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<select id="x<?php echo $tools_translation_grid->RowIndex ?>_to_domain" name="x<?php echo $tools_translation_grid->RowIndex ?>_to_domain"<?php echo $tools_translation->to_domain->EditAttributes() ?>>
<?php
if (is_array($tools_translation->to_domain->EditValue)) {
	$arwrk = $tools_translation->to_domain->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($tools_translation->to_domain->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $tools_translation->to_domain->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `id_domains`, `dominio` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `domains`";
 $sWhereWrk = "";
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $tools_translation_grid->RowIndex ?>_to_domain" id="s_x<?php echo $tools_translation_grid->RowIndex ?>_to_domain" value="s=<?php echo TEAencrypt($sSqlWrk) ?>&fn=<?php echo urlencode($tools_translation->to_domain->LookupFn) ?>&f0=<?php echo TEAencrypt("`id_domains` = {filter_value}"); ?>&t0=3" />
<?php } ?>
<?php if ($tools_translation->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $tools_translation->to_domain->ViewAttributes() ?>>
<?php echo $tools_translation->to_domain->ListViewValue() ?></span>
<input type="hidden" name="x<?php echo $tools_translation_grid->RowIndex ?>_to_domain" id="x<?php echo $tools_translation_grid->RowIndex ?>_to_domain" value="<?php echo ew_HtmlEncode($tools_translation->to_domain->FormValue) ?>" />
<input type="hidden" name="o<?php echo $tools_translation_grid->RowIndex ?>_to_domain" id="o<?php echo $tools_translation_grid->RowIndex ?>_to_domain" value="<?php echo ew_HtmlEncode($tools_translation->to_domain->OldValue) ?>" />
<?php } ?>
</span></td>
	<?php } ?>
	<?php if ($tools_translation->date->Visible) { // date ?>
		<td<?php echo $tools_translation->date->CellAttributes() ?>><span id="el<?php echo $tools_translation_grid->RowCnt ?>_tools_translation_date" class="tools_translation_date">
<?php if ($tools_translation->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" name="o<?php echo $tools_translation_grid->RowIndex ?>_date" id="o<?php echo $tools_translation_grid->RowIndex ?>_date" value="<?php echo ew_HtmlEncode($tools_translation->date->OldValue) ?>" />
<?php } ?>
<?php if ($tools_translation->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<?php } ?>
<?php if ($tools_translation->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $tools_translation->date->ViewAttributes() ?>>
<?php echo $tools_translation->date->ListViewValue() ?></span>
<input type="hidden" name="x<?php echo $tools_translation_grid->RowIndex ?>_date" id="x<?php echo $tools_translation_grid->RowIndex ?>_date" value="<?php echo ew_HtmlEncode($tools_translation->date->FormValue) ?>" />
<input type="hidden" name="o<?php echo $tools_translation_grid->RowIndex ?>_date" id="o<?php echo $tools_translation_grid->RowIndex ?>_date" value="<?php echo ew_HtmlEncode($tools_translation->date->OldValue) ?>" />
<?php } ?>
</span></td>
	<?php } ?>
	<?php if ($tools_translation->lenguaje->Visible) { // lenguaje ?>
		<td<?php echo $tools_translation->lenguaje->CellAttributes() ?>><span id="el<?php echo $tools_translation_grid->RowCnt ?>_tools_translation_lenguaje" class="tools_translation_lenguaje">
<?php if ($tools_translation->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<select id="x<?php echo $tools_translation_grid->RowIndex ?>_lenguaje" name="x<?php echo $tools_translation_grid->RowIndex ?>_lenguaje"<?php echo $tools_translation->lenguaje->EditAttributes() ?>>
<?php
if (is_array($tools_translation->lenguaje->EditValue)) {
	$arwrk = $tools_translation->lenguaje->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($tools_translation->lenguaje->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $tools_translation->lenguaje->OldValue = "";
?>
</select>
<input type="hidden" name="o<?php echo $tools_translation_grid->RowIndex ?>_lenguaje" id="o<?php echo $tools_translation_grid->RowIndex ?>_lenguaje" value="<?php echo ew_HtmlEncode($tools_translation->lenguaje->OldValue) ?>" />
<?php } ?>
<?php if ($tools_translation->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<select id="x<?php echo $tools_translation_grid->RowIndex ?>_lenguaje" name="x<?php echo $tools_translation_grid->RowIndex ?>_lenguaje"<?php echo $tools_translation->lenguaje->EditAttributes() ?>>
<?php
if (is_array($tools_translation->lenguaje->EditValue)) {
	$arwrk = $tools_translation->lenguaje->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($tools_translation->lenguaje->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $tools_translation->lenguaje->OldValue = "";
?>
</select>
<?php } ?>
<?php if ($tools_translation->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $tools_translation->lenguaje->ViewAttributes() ?>>
<?php echo $tools_translation->lenguaje->ListViewValue() ?></span>
<input type="hidden" name="x<?php echo $tools_translation_grid->RowIndex ?>_lenguaje" id="x<?php echo $tools_translation_grid->RowIndex ?>_lenguaje" value="<?php echo ew_HtmlEncode($tools_translation->lenguaje->FormValue) ?>" />
<input type="hidden" name="o<?php echo $tools_translation_grid->RowIndex ?>_lenguaje" id="o<?php echo $tools_translation_grid->RowIndex ?>_lenguaje" value="<?php echo ew_HtmlEncode($tools_translation->lenguaje->OldValue) ?>" />
<?php } ?>
</span></td>
	<?php } ?>
	<?php if ($tools_translation->log->Visible) { // log ?>
		<td<?php echo $tools_translation->log->CellAttributes() ?>><span id="el<?php echo $tools_translation_grid->RowCnt ?>_tools_translation_log" class="tools_translation_log">
<?php if ($tools_translation->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="text" name="x<?php echo $tools_translation_grid->RowIndex ?>_log" id="x<?php echo $tools_translation_grid->RowIndex ?>_log" size="30" maxlength="255" value="<?php echo $tools_translation->log->EditValue ?>"<?php echo $tools_translation->log->EditAttributes() ?> />
<input type="hidden" name="o<?php echo $tools_translation_grid->RowIndex ?>_log" id="o<?php echo $tools_translation_grid->RowIndex ?>_log" value="<?php echo ew_HtmlEncode($tools_translation->log->OldValue) ?>" />
<?php } ?>
<?php if ($tools_translation->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<input type="text" name="x<?php echo $tools_translation_grid->RowIndex ?>_log" id="x<?php echo $tools_translation_grid->RowIndex ?>_log" size="30" maxlength="255" value="<?php echo $tools_translation->log->EditValue ?>"<?php echo $tools_translation->log->EditAttributes() ?> />
<?php } ?>
<?php if ($tools_translation->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $tools_translation->log->ViewAttributes() ?>>
<?php echo $tools_translation->log->ListViewValue() ?></span>
<input type="hidden" name="x<?php echo $tools_translation_grid->RowIndex ?>_log" id="x<?php echo $tools_translation_grid->RowIndex ?>_log" value="<?php echo ew_HtmlEncode($tools_translation->log->FormValue) ?>" />
<input type="hidden" name="o<?php echo $tools_translation_grid->RowIndex ?>_log" id="o<?php echo $tools_translation_grid->RowIndex ?>_log" value="<?php echo ew_HtmlEncode($tools_translation->log->OldValue) ?>" />
<?php } ?>
</span></td>
	<?php } ?>
	<?php if ($tools_translation->Status->Visible) { // Status ?>
		<td<?php echo $tools_translation->Status->CellAttributes() ?>><span id="el<?php echo $tools_translation_grid->RowCnt ?>_tools_translation_Status" class="tools_translation_Status">
<?php if ($tools_translation->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="text" name="x<?php echo $tools_translation_grid->RowIndex ?>_Status" id="x<?php echo $tools_translation_grid->RowIndex ?>_Status" size="30" value="<?php echo $tools_translation->Status->EditValue ?>"<?php echo $tools_translation->Status->EditAttributes() ?> />
<input type="hidden" name="o<?php echo $tools_translation_grid->RowIndex ?>_Status" id="o<?php echo $tools_translation_grid->RowIndex ?>_Status" value="<?php echo ew_HtmlEncode($tools_translation->Status->OldValue) ?>" />
<?php } ?>
<?php if ($tools_translation->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<input type="text" name="x<?php echo $tools_translation_grid->RowIndex ?>_Status" id="x<?php echo $tools_translation_grid->RowIndex ?>_Status" size="30" value="<?php echo $tools_translation->Status->EditValue ?>"<?php echo $tools_translation->Status->EditAttributes() ?> />
<?php } ?>
<?php if ($tools_translation->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $tools_translation->Status->ViewAttributes() ?>>
<?php echo $tools_translation->Status->ListViewValue() ?></span>
<input type="hidden" name="x<?php echo $tools_translation_grid->RowIndex ?>_Status" id="x<?php echo $tools_translation_grid->RowIndex ?>_Status" value="<?php echo ew_HtmlEncode($tools_translation->Status->FormValue) ?>" />
<input type="hidden" name="o<?php echo $tools_translation_grid->RowIndex ?>_Status" id="o<?php echo $tools_translation_grid->RowIndex ?>_Status" value="<?php echo ew_HtmlEncode($tools_translation->Status->OldValue) ?>" />
<?php } ?>
</span></td>
	<?php } ?>
<?php

// Render list options (body, right)
$tools_translation_grid->ListOptions->Render("body", "right", $tools_translation_grid->RowCnt);
?>
	</tr>
<?php if ($tools_translation->RowType == EW_ROWTYPE_ADD || $tools_translation->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
ftools_translationgrid.UpdateOpts(<?php echo $tools_translation_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($tools_translation->CurrentAction <> "gridadd" || $tools_translation->CurrentMode == "copy")
		if (!$tools_translation_grid->Recordset->EOF) $tools_translation_grid->Recordset->MoveNext();
}
?>
<?php
	if ($tools_translation->CurrentMode == "add" || $tools_translation->CurrentMode == "copy" || $tools_translation->CurrentMode == "edit") {
		$tools_translation_grid->RowIndex = '$rowindex$';
		$tools_translation_grid->LoadDefaultValues();

		// Set row properties
		$tools_translation->ResetAttrs();
		$tools_translation->RowAttrs = array_merge($tools_translation->RowAttrs, array('data-rowindex'=>$tools_translation_grid->RowIndex, 'id'=>'r0_tools_translation', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($tools_translation->RowAttrs["class"], "ewTemplate");
		$tools_translation->RowType = EW_ROWTYPE_ADD;

		// Render row
		$tools_translation_grid->RenderRow();

		// Render list options
		$tools_translation_grid->RenderListOptions();
		$tools_translation_grid->StartRowCnt = 0;
?>
	<tr<?php echo $tools_translation->RowAttributes() ?>>
<?php

// Render list options (body, left)
$tools_translation_grid->ListOptions->Render("body", "left", $tools_translation_grid->RowIndex);
?>
	<?php if ($tools_translation->idtools_translation->Visible) { // idtools_translation ?>
		<td><span2 id="el$rowindex$_tools_translation_idtools_translation" class="tools_translation_idtools_translation">
<?php if ($tools_translation->CurrentAction <> "F") { ?>
<?php } else { ?>
<span<?php echo $tools_translation->idtools_translation->ViewAttributes() ?>>
<?php echo $tools_translation->idtools_translation->ViewValue ?></span>
<input type="hidden" name="x<?php echo $tools_translation_grid->RowIndex ?>_idtools_translation" id="x<?php echo $tools_translation_grid->RowIndex ?>_idtools_translation" value="<?php echo ew_HtmlEncode($tools_translation->idtools_translation->FormValue) ?>" />
<?php } ?>
<input type="hidden" name="o<?php echo $tools_translation_grid->RowIndex ?>_idtools_translation" id="o<?php echo $tools_translation_grid->RowIndex ?>_idtools_translation" value="<?php echo ew_HtmlEncode($tools_translation->idtools_translation->OldValue) ?>" />
</span></td>
	<?php } ?>
	<?php if ($tools_translation->domain_id->Visible) { // domain_id ?>
		<td><span2 id="el$rowindex$_tools_translation_domain_id" class="tools_translation_domain_id">
<?php if ($tools_translation->CurrentAction <> "F") { ?>
<?php if ($tools_translation->domain_id->getSessionValue() <> "") { ?>
<span<?php echo $tools_translation->domain_id->ViewAttributes() ?>>
<?php echo $tools_translation->domain_id->ListViewValue() ?></span>
<input type="hidden" id="x<?php echo $tools_translation_grid->RowIndex ?>_domain_id" name="x<?php echo $tools_translation_grid->RowIndex ?>_domain_id" value="<?php echo ew_HtmlEncode($tools_translation->domain_id->CurrentValue) ?>">
<?php } else { ?>
<select id="x<?php echo $tools_translation_grid->RowIndex ?>_domain_id" name="x<?php echo $tools_translation_grid->RowIndex ?>_domain_id"<?php echo $tools_translation->domain_id->EditAttributes() ?>>
<?php
if (is_array($tools_translation->domain_id->EditValue)) {
	$arwrk = $tools_translation->domain_id->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($tools_translation->domain_id->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $tools_translation->domain_id->OldValue = "";
?>
</select>
<script type="text/javascript">
ftools_translationgrid.Lists["x_domain_id"].Options = <?php echo (is_array($tools_translation->domain_id->EditValue)) ? ew_ArrayToJson($tools_translation->domain_id->EditValue, 1) : "[]" ?>;
</script>
<?php } ?>
<?php } else { ?>
<span<?php echo $tools_translation->domain_id->ViewAttributes() ?>>
<?php echo $tools_translation->domain_id->ViewValue ?></span>
<input type="hidden" name="x<?php echo $tools_translation_grid->RowIndex ?>_domain_id" id="x<?php echo $tools_translation_grid->RowIndex ?>_domain_id" value="<?php echo ew_HtmlEncode($tools_translation->domain_id->FormValue) ?>" />
<?php } ?>
<input type="hidden" name="o<?php echo $tools_translation_grid->RowIndex ?>_domain_id" id="o<?php echo $tools_translation_grid->RowIndex ?>_domain_id" value="<?php echo ew_HtmlEncode($tools_translation->domain_id->OldValue) ?>" />
</span></td>
	<?php } ?>
	<?php if ($tools_translation->to_domain->Visible) { // to_domain ?>
		<td><span2 id="el$rowindex$_tools_translation_to_domain" class="tools_translation_to_domain">
<?php if ($tools_translation->CurrentAction <> "F") { ?>
<select id="x<?php echo $tools_translation_grid->RowIndex ?>_to_domain" name="x<?php echo $tools_translation_grid->RowIndex ?>_to_domain"<?php echo $tools_translation->to_domain->EditAttributes() ?>>
<?php
if (is_array($tools_translation->to_domain->EditValue)) {
	$arwrk = $tools_translation->to_domain->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($tools_translation->to_domain->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $tools_translation->to_domain->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `id_domains`, `dominio` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `domains`";
 $sWhereWrk = "";
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $tools_translation_grid->RowIndex ?>_to_domain" id="s_x<?php echo $tools_translation_grid->RowIndex ?>_to_domain" value="s=<?php echo TEAencrypt($sSqlWrk) ?>&fn=<?php echo urlencode($tools_translation->to_domain->LookupFn) ?>&f0=<?php echo TEAencrypt("`id_domains` = {filter_value}"); ?>&t0=3" />
<?php } else { ?>
<span<?php echo $tools_translation->to_domain->ViewAttributes() ?>>
<?php echo $tools_translation->to_domain->ViewValue ?></span>
<input type="hidden" name="x<?php echo $tools_translation_grid->RowIndex ?>_to_domain" id="x<?php echo $tools_translation_grid->RowIndex ?>_to_domain" value="<?php echo ew_HtmlEncode($tools_translation->to_domain->FormValue) ?>" />
<?php } ?>
<input type="hidden" name="o<?php echo $tools_translation_grid->RowIndex ?>_to_domain" id="o<?php echo $tools_translation_grid->RowIndex ?>_to_domain" value="<?php echo ew_HtmlEncode($tools_translation->to_domain->OldValue) ?>" />
</span></td>
	<?php } ?>
	<?php if ($tools_translation->date->Visible) { // date ?>
		<td><span2 id="el$rowindex$_tools_translation_date" class="tools_translation_date">
<?php if ($tools_translation->CurrentAction <> "F") { ?>
<?php } else { ?>
<span<?php echo $tools_translation->date->ViewAttributes() ?>>
<?php echo $tools_translation->date->ViewValue ?></span>
<input type="hidden" name="x<?php echo $tools_translation_grid->RowIndex ?>_date" id="x<?php echo $tools_translation_grid->RowIndex ?>_date" value="<?php echo ew_HtmlEncode($tools_translation->date->FormValue) ?>" />
<?php } ?>
<input type="hidden" name="o<?php echo $tools_translation_grid->RowIndex ?>_date" id="o<?php echo $tools_translation_grid->RowIndex ?>_date" value="<?php echo ew_HtmlEncode($tools_translation->date->OldValue) ?>" />
</span></td>
	<?php } ?>
	<?php if ($tools_translation->lenguaje->Visible) { // lenguaje ?>
		<td><span2 id="el$rowindex$_tools_translation_lenguaje" class="tools_translation_lenguaje">
<?php if ($tools_translation->CurrentAction <> "F") { ?>
<select id="x<?php echo $tools_translation_grid->RowIndex ?>_lenguaje" name="x<?php echo $tools_translation_grid->RowIndex ?>_lenguaje"<?php echo $tools_translation->lenguaje->EditAttributes() ?>>
<?php
if (is_array($tools_translation->lenguaje->EditValue)) {
	$arwrk = $tools_translation->lenguaje->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($tools_translation->lenguaje->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $tools_translation->lenguaje->OldValue = "";
?>
</select>
<?php } else { ?>
<span<?php echo $tools_translation->lenguaje->ViewAttributes() ?>>
<?php echo $tools_translation->lenguaje->ViewValue ?></span>
<input type="hidden" name="x<?php echo $tools_translation_grid->RowIndex ?>_lenguaje" id="x<?php echo $tools_translation_grid->RowIndex ?>_lenguaje" value="<?php echo ew_HtmlEncode($tools_translation->lenguaje->FormValue) ?>" />
<?php } ?>
<input type="hidden" name="o<?php echo $tools_translation_grid->RowIndex ?>_lenguaje" id="o<?php echo $tools_translation_grid->RowIndex ?>_lenguaje" value="<?php echo ew_HtmlEncode($tools_translation->lenguaje->OldValue) ?>" />
</span></td>
	<?php } ?>
	<?php if ($tools_translation->log->Visible) { // log ?>
		<td><span2 id="el$rowindex$_tools_translation_log" class="tools_translation_log">
<?php if ($tools_translation->CurrentAction <> "F") { ?>
<input type="text" name="x<?php echo $tools_translation_grid->RowIndex ?>_log" id="x<?php echo $tools_translation_grid->RowIndex ?>_log" size="30" maxlength="255" value="<?php echo $tools_translation->log->EditValue ?>"<?php echo $tools_translation->log->EditAttributes() ?> />
<?php } else { ?>
<span<?php echo $tools_translation->log->ViewAttributes() ?>>
<?php echo $tools_translation->log->ViewValue ?></span>
<input type="hidden" name="x<?php echo $tools_translation_grid->RowIndex ?>_log" id="x<?php echo $tools_translation_grid->RowIndex ?>_log" value="<?php echo ew_HtmlEncode($tools_translation->log->FormValue) ?>" />
<?php } ?>
<input type="hidden" name="o<?php echo $tools_translation_grid->RowIndex ?>_log" id="o<?php echo $tools_translation_grid->RowIndex ?>_log" value="<?php echo ew_HtmlEncode($tools_translation->log->OldValue) ?>" />
</span></td>
	<?php } ?>
	<?php if ($tools_translation->Status->Visible) { // Status ?>
		<td><span2 id="el$rowindex$_tools_translation_Status" class="tools_translation_Status">
<?php if ($tools_translation->CurrentAction <> "F") { ?>
<input type="text" name="x<?php echo $tools_translation_grid->RowIndex ?>_Status" id="x<?php echo $tools_translation_grid->RowIndex ?>_Status" size="30" value="<?php echo $tools_translation->Status->EditValue ?>"<?php echo $tools_translation->Status->EditAttributes() ?> />
<?php } else { ?>
<span<?php echo $tools_translation->Status->ViewAttributes() ?>>
<?php echo $tools_translation->Status->ViewValue ?></span>
<input type="hidden" name="x<?php echo $tools_translation_grid->RowIndex ?>_Status" id="x<?php echo $tools_translation_grid->RowIndex ?>_Status" value="<?php echo ew_HtmlEncode($tools_translation->Status->FormValue) ?>" />
<?php } ?>
<input type="hidden" name="o<?php echo $tools_translation_grid->RowIndex ?>_Status" id="o<?php echo $tools_translation_grid->RowIndex ?>_Status" value="<?php echo ew_HtmlEncode($tools_translation->Status->OldValue) ?>" />
</span></td>
	<?php } ?>
<?php

// Render list options (body, right)
$tools_translation_grid->ListOptions->Render("body", "right", $tools_translation_grid->RowCnt);
?>
<script type="text/javascript">
ftools_translationgrid.UpdateOpts(<?php echo $tools_translation_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
<!--</table>-->
<?php if ($tools_translation->CurrentMode == "add" || $tools_translation->CurrentMode == "copy") { ?>
<input class="btn btn-large btn-success" type="submit" />
<input type="hidden" name="a_list" id="a_list" value="gridinsert" />
<input type="hidden" name="key_count" id="key_count" value="<?php echo $tools_translation_grid->KeyCount ?>" />
<?php echo $tools_translation_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($tools_translation->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="key_count" id="key_count" value="<?php echo $tools_translation_grid->KeyCount ?>" />
<?php echo $tools_translation_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($tools_translation->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" id="detailpage" value="ftools_translationgrid">
<?php

// Close recordset
if ($tools_translation_grid->Recordset)
	$tools_translation_grid->Recordset->Close();
?>
<?php if (($tools_translation->CurrentMode == "add" || $tools_translation->CurrentMode == "copy" || $tools_translation->CurrentMode == "edit") && $tools_translation->CurrentAction != "F") { // add/copy/edit mode ?>
<div class="ewGridLowerPanel">
<?php if ($tools_translation->AllowAddDeleteRow) { ?>
<?php if ($Security->CanAdd()) { ?>
<span class="phpmaker">
<a href="javascript:void(0);" onclick="ew_AddGridRow(this);"><?php echo $Language->Phrase("AddBlankRow") ?></a>
</span>
<?php } ?>
<?php } ?>
</div>
<?php } ?>
</div>
<?php if ($tools_translation->Export == "") { ?>
<script type="text/javascript">
ftools_translationgrid.Init();
</script>
<?php } ?>
<?php
$tools_translation_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$tools_translation_grid->Page_Terminate();
$Page = &$MasterPage;
?>
