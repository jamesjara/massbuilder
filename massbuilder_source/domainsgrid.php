<?php include_once "usersinfo.php" ?>
<?php

// Create page object
if (!isset($domains_grid)) $domains_grid = new cdomains_grid();

// Page init
$domains_grid->Page_Init();

// Page main
$domains_grid->Page_Main();
?>
<?php if ($domains->Export == "") { ?>
<script type="text/javascript">

// Page object
var domains_grid = new ew_Page("domains_grid");
domains_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = domains_grid.PageID; // For backward compatibility

// Form object
var fdomainsgrid = new ew_Form("fdomainsgrid");

// Validate form
fdomainsgrid.Validate = function(fobj) {
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
		elm = fobj.elements["x" + infix + "_hosted_in"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($domains->hosted_in->FldCaption()) ?>");

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
fdomainsgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "dominio", false)) return false;
	if (ew_ValueChanged(fobj, infix, "hosted_in", false)) return false;
	return true;
}

// Form_CustomValidate event
fdomainsgrid.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fdomainsgrid.ValidateRequired = true;
<?php } else { ?>
fdomainsgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<?php } ?>
<?php
if ($domains->CurrentAction == "gridadd") {
	if ($domains->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$domains_grid->TotalRecs = $domains->SelectRecordCount();
			$domains_grid->Recordset = $domains_grid->LoadRecordset($domains_grid->StartRec-1, $domains_grid->DisplayRecs);
		} else {
			if ($domains_grid->Recordset = $domains_grid->LoadRecordset())
				$domains_grid->TotalRecs = $domains_grid->Recordset->RecordCount();
		}
		$domains_grid->StartRec = 1;
		$domains_grid->DisplayRecs = $domains_grid->TotalRecs;
	} else {
		$domains->CurrentFilter = "0=1";
		$domains_grid->StartRec = 1;
		$domains_grid->DisplayRecs = $domains->GridAddRowCount;
	}
	$domains_grid->TotalRecs = $domains_grid->DisplayRecs;
	$domains_grid->StopRec = $domains_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$domains_grid->TotalRecs = $domains->SelectRecordCount();
	} else {
		if ($domains_grid->Recordset = $domains_grid->LoadRecordset())
			$domains_grid->TotalRecs = $domains_grid->Recordset->RecordCount();
	}
	$domains_grid->StartRec = 1;
	$domains_grid->DisplayRecs = $domains_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$domains_grid->Recordset = $domains_grid->LoadRecordset($domains_grid->StartRec-1, $domains_grid->DisplayRecs);
}
?>
<p style="white-spaceJAMES: nowrapJAMES;"><h4 id="ewPageCaption" class="page-header ewTitle ewTableTitle"> <?php if ($domains->CurrentMode == "add" || $domains->CurrentMode == "copy") { ?><?php echo $Language->Phrase("Add") ?><?php } elseif ($domains->CurrentMode == "edit") { ?><?php echo $Language->Phrase("Edit") ?><?php } ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $domains->TableCaption() ?></h4></p>
</p>
<?php $domains_grid->ShowPageHeader(); ?>
<?php
$domains_grid->ShowMessage();
?>
<div id="fdomainsgrid" class="ewForm">
<?php if (($domains->CurrentMode == "add" || $domains->CurrentMode == "copy" || $domains->CurrentMode == "edit") && $domains->CurrentAction != "F") { // add/copy/edit mode ?>
<div class="ewGridUpperPanel">
<?php if ($domains->AllowAddDeleteRow) { ?>
<?php if ($Security->CanAdd()) { ?>
<span class="phpmaker">
<a href="javascript:void(0);" onclick="ew_AddGridRow(this);"><?php echo $Language->Phrase("AddBlankRow") ?></a>
</span>
<?php } ?>
<?php } ?>
</div>
<?php } ?>
<div id="gmp_domains" class="ewGridMiddlePanel row-fluid">
<ul class="thumbnails">
<table id="tbl_domainsgrid" class="ewTable ewTableSeparate table table-striped table-bordered ">
<?php echo $domains->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$domains_grid->RenderListOptions();

// Render list options (header, left)
$domains_grid->ListOptions->Render("header", "left");
?>
<?php if ($domains->dominio->Visible) { // dominio ?>
	<?php if ($domains->SortUrl($domains->dominio) == "") { ?>
		<th><span id="elh_domains_dominio" class="domains_dominio">
		<div class="ewTableHeaderBtn"><?php echo $domains->dominio->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div><span id="elh_domains_dominio" class="domains_dominio">
			<div class="ewTableHeaderBtn">			
			<?php echo $domains->dominio->FldCaption() ?>
			<?php if ($domains->dominio->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($domains->dominio->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($domains->id_domains->Visible) { // id_domains ?>
	<?php if ($domains->SortUrl($domains->id_domains) == "") { ?>
		<th><span id="elh_domains_id_domains" class="domains_id_domains">
		<div class="ewTableHeaderBtn"><?php echo $domains->id_domains->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div><span id="elh_domains_id_domains" class="domains_id_domains">
			<div class="ewTableHeaderBtn">			
			<?php echo $domains->id_domains->FldCaption() ?>
			<?php if ($domains->id_domains->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($domains->id_domains->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($domains->hosted_in->Visible) { // hosted_in ?>
	<?php if ($domains->SortUrl($domains->hosted_in) == "") { ?>
		<th><span id="elh_domains_hosted_in" class="domains_hosted_in">
		<div class="ewTableHeaderBtn"><?php echo $domains->hosted_in->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div><span id="elh_domains_hosted_in" class="domains_hosted_in">
			<div class="ewTableHeaderBtn">			
			<?php echo $domains->hosted_in->FldCaption() ?>
			<?php if ($domains->hosted_in->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($domains->hosted_in->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$domains_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$domains_grid->StartRec = 1;
$domains_grid->StopRec = $domains_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue("key_count") && ($domains->CurrentAction == "gridadd" || $domains->CurrentAction == "gridedit" || $domains->CurrentAction == "F")) {
		$domains_grid->KeyCount = $objForm->GetValue("key_count");
		$domains_grid->StopRec = $domains_grid->KeyCount;
	}
}
$domains_grid->RecCnt = $domains_grid->StartRec - 1;
if ($domains_grid->Recordset && !$domains_grid->Recordset->EOF) {
	$domains_grid->Recordset->MoveFirst();
	if (!$bSelectLimit && $domains_grid->StartRec > 1)
		$domains_grid->Recordset->Move($domains_grid->StartRec - 1);
} elseif (!$domains->AllowAddDeleteRow && $domains_grid->StopRec == 0) {
	$domains_grid->StopRec = $domains->GridAddRowCount;
}

// Initialize aggregate
$domains->RowType = EW_ROWTYPE_AGGREGATEINIT;
$domains->ResetAttrs();
$domains_grid->RenderRow();
if ($domains->CurrentAction == "gridadd")
	$domains_grid->RowIndex = 0;
if ($domains->CurrentAction == "gridedit")
	$domains_grid->RowIndex = 0;
while ($domains_grid->RecCnt < $domains_grid->StopRec) {
	$domains_grid->RecCnt++;
	if (intval($domains_grid->RecCnt) >= intval($domains_grid->StartRec)) {
		$domains_grid->RowCnt++;
		if ($domains->CurrentAction == "gridadd" || $domains->CurrentAction == "gridedit" || $domains->CurrentAction == "F") {
			$domains_grid->RowIndex++;
			$objForm->Index = $domains_grid->RowIndex;
			if ($objForm->HasValue("k_action"))
				$domains_grid->RowAction = strval($objForm->GetValue("k_action"));
			elseif ($domains->CurrentAction == "gridadd")
				$domains_grid->RowAction = "insert";
			else
				$domains_grid->RowAction = "";
		}

		// Set up key count
		$domains_grid->KeyCount = $domains_grid->RowIndex;

		// Init row class and style
		$domains->ResetAttrs();
		$domains->CssClass = "";
		if ($domains->CurrentAction == "gridadd") {
			if ($domains->CurrentMode == "copy") {
				$domains_grid->LoadRowValues($domains_grid->Recordset); // Load row values
				$domains_grid->SetRecordKey($domains_grid->RowOldKey, $domains_grid->Recordset); // Set old record key
			} else {
				$domains_grid->LoadDefaultValues(); // Load default values
				$domains_grid->RowOldKey = ""; // Clear old key value
			}
		} elseif ($domains->CurrentAction == "gridedit") {
			$domains_grid->LoadRowValues($domains_grid->Recordset); // Load row values
		}
		$domains->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($domains->CurrentAction == "gridadd") // Grid add
			$domains->RowType = EW_ROWTYPE_ADD; // Render add
		if ($domains->CurrentAction == "gridadd" && $domains->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$domains_grid->RestoreCurrentRowFormValues($domains_grid->RowIndex); // Restore form values
		if ($domains->CurrentAction == "gridedit") { // Grid edit
			if ($domains->EventCancelled) {
				$domains_grid->RestoreCurrentRowFormValues($domains_grid->RowIndex); // Restore form values
			}
			if ($domains_grid->RowAction == "insert")
				$domains->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$domains->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($domains->CurrentAction == "gridedit" && ($domains->RowType == EW_ROWTYPE_EDIT || $domains->RowType == EW_ROWTYPE_ADD) && $domains->EventCancelled) // Update failed
			$domains_grid->RestoreCurrentRowFormValues($domains_grid->RowIndex); // Restore form values
		if ($domains->RowType == EW_ROWTYPE_EDIT) // Edit row
			$domains_grid->EditRowCnt++;
		if ($domains->CurrentAction == "F") // Confirm row
			$domains_grid->RestoreCurrentRowFormValues($domains_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$domains->RowAttrs = array_merge($domains->RowAttrs, array('data-rowindex'=>$domains_grid->RowCnt, 'id'=>'r' . $domains_grid->RowCnt . '_domains', 'data-rowtype'=>$domains->RowType));

		// Render row
		$domains_grid->RenderRow();

		// Render list options
		$domains_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($domains_grid->RowAction <> "delete" && $domains_grid->RowAction <> "insertdelete" && !($domains_grid->RowAction == "insert" && $domains->CurrentAction == "F" && $domains_grid->EmptyRow())) {
?>
	<tr<?php echo $domains->RowAttributes() ?>>
<?php

// Render list options (body, left)
$domains_grid->ListOptions->Render("body", "left", $domains_grid->RowCnt);
?>
	<?php if ($domains->dominio->Visible) { // dominio ?>
		<td<?php echo $domains->dominio->CellAttributes() ?>><span id="el<?php echo $domains_grid->RowCnt ?>_domains_dominio" class="domains_dominio">
<?php if ($domains->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="text" name="x<?php echo $domains_grid->RowIndex ?>_dominio" id="x<?php echo $domains_grid->RowIndex ?>_dominio" size="30" maxlength="145" value="<?php echo $domains->dominio->EditValue ?>"<?php echo $domains->dominio->EditAttributes() ?> />
<input type="hidden" name="o<?php echo $domains_grid->RowIndex ?>_dominio" id="o<?php echo $domains_grid->RowIndex ?>_dominio" value="<?php echo ew_HtmlEncode($domains->dominio->OldValue) ?>" />
<?php } ?>
<?php if ($domains->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<input type="text" name="x<?php echo $domains_grid->RowIndex ?>_dominio" id="x<?php echo $domains_grid->RowIndex ?>_dominio" size="30" maxlength="145" value="<?php echo $domains->dominio->EditValue ?>"<?php echo $domains->dominio->EditAttributes() ?> />
<?php } ?>
<?php if ($domains->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $domains->dominio->ViewAttributes() ?>>
<?php echo $domains->dominio->ListViewValue() ?></span>
<input type="hidden" name="x<?php echo $domains_grid->RowIndex ?>_dominio" id="x<?php echo $domains_grid->RowIndex ?>_dominio" value="<?php echo ew_HtmlEncode($domains->dominio->FormValue) ?>" />
<input type="hidden" name="o<?php echo $domains_grid->RowIndex ?>_dominio" id="o<?php echo $domains_grid->RowIndex ?>_dominio" value="<?php echo ew_HtmlEncode($domains->dominio->OldValue) ?>" />
<?php } ?>
</span></td>
	<?php } ?>
<a id="<?php echo $domains_grid->PageObjName . "_row_" . $domains_grid->RowCnt ?>"></a>
	<?php if ($domains->id_domains->Visible) { // id_domains ?>
		<td<?php echo $domains->id_domains->CellAttributes() ?>><span id="el<?php echo $domains_grid->RowCnt ?>_domains_id_domains" class="domains_id_domains">
<?php if ($domains->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" name="o<?php echo $domains_grid->RowIndex ?>_id_domains" id="o<?php echo $domains_grid->RowIndex ?>_id_domains" value="<?php echo ew_HtmlEncode($domains->id_domains->OldValue) ?>" />
<?php } ?>
<?php if ($domains->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<input type="hidden" name="x<?php echo $domains_grid->RowIndex ?>_id_domains" id="x<?php echo $domains_grid->RowIndex ?>_id_domains" value="<?php echo ew_HtmlEncode($domains->id_domains->CurrentValue) ?>" />
<?php } ?>
<?php if ($domains->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<div id="orig_domains_id_domains" class="ewDisplayNone">
<span<?php echo $domains->id_domains->ViewAttributes() ?>>
<?php echo $domains->id_domains->ListViewValue() ?></span>
</div>
<?php
if( BASENAME($_SERVER['PHP_SELF']) == 'domainslist.php' ){   // solo si es domainslist.php
   //$data  = '<a class="operacion btn btn-success" name="BackUp"    id="'.CurrentTable()->id_domains->CurrentValue.'" href="#">BackUp</a> ';  
   //$data .= '<a class="operacion btn btn-primary" name="Traducion" id="'.CurrentTable()->id_domains->CurrentValue.'" href="#">Traducion</a>';   
   //echo $data;
} else    {
   //$data  = '<a class="operacion btn btn-success" name="BackUp"    id="'.$_GET['id_domains'].'" href="#">BackUp</a> ';  
   //$data .= '<a class="operacion btn btn-primary" name="Traducion" id="'.$_GET['id_domains'].'" href="#">Traducion</a>';    
   //echo $data;  
}     
                                               
if( BASENAME($_SERVER['PHP_SELF']) == 'tools_backupslist.php' ){   // solo si es tools_backupslist.php   
   $data  = '<a class="operacion btn btn-success" name="blogger_backup"    id="'.$_GET['id_domains'].'" href="#">BackUp</a> ';   
   echo $data;                                                                    
}                                           
if( BASENAME($_SERVER['PHP_SELF']) == 'tools_translationlist.php' ){   // solo si es tools_translationlist.php   
   $data  = '<a class="operacion btn btn-success" name="blogger_translate" id="'.$_GET['id_domains'].'" href="#">Traducion</a> ';   
   echo $data;                                                                    
}                                          
if( BASENAME($_SERVER['PHP_SELF']) == 'entrieslist.php' ){   // solo si es entrieslist.php                            
   $data   = '<a class="operacion btn btn-success" name="push_entries" id="'.$_GET['id_domains'].'" href="#">Push</a> ';   
   $data  .= '<a class="operacion btn btn-success" name="blogger_refresh_entries" id="'.$_GET['id_domains'].'" href="#">Refresh</a> ';    
   echo $data;                                                                    
}  
         
?>                  
<?php if (debug01) echo CurrentTable()->id_domains->CurrentValue; ?>
<input type="hidden" name="x<?php echo $domains_grid->RowIndex ?>_id_domains" id="x<?php echo $domains_grid->RowIndex ?>_id_domains" value="<?php echo ew_HtmlEncode($domains->id_domains->FormValue) ?>" />
<input type="hidden" name="o<?php echo $domains_grid->RowIndex ?>_id_domains" id="o<?php echo $domains_grid->RowIndex ?>_id_domains" value="<?php echo ew_HtmlEncode($domains->id_domains->OldValue) ?>" />
<?php } ?>
</span></td>
	<?php } ?>
	<?php if ($domains->hosted_in->Visible) { // hosted_in ?>
		<td<?php echo $domains->hosted_in->CellAttributes() ?>><span id="el<?php echo $domains_grid->RowCnt ?>_domains_hosted_in" class="domains_hosted_in">
<?php if ($domains->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<select id="x<?php echo $domains_grid->RowIndex ?>_hosted_in" name="x<?php echo $domains_grid->RowIndex ?>_hosted_in"<?php echo $domains->hosted_in->EditAttributes() ?>>
<?php
if (is_array($domains->hosted_in->EditValue)) {
	$arwrk = $domains->hosted_in->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($domains->hosted_in->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $domains->hosted_in->OldValue = "";
?>
</select>
<input type="hidden" name="o<?php echo $domains_grid->RowIndex ?>_hosted_in" id="o<?php echo $domains_grid->RowIndex ?>_hosted_in" value="<?php echo ew_HtmlEncode($domains->hosted_in->OldValue) ?>" />
<?php } ?>
<?php if ($domains->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<select id="x<?php echo $domains_grid->RowIndex ?>_hosted_in" name="x<?php echo $domains_grid->RowIndex ?>_hosted_in"<?php echo $domains->hosted_in->EditAttributes() ?>>
<?php
if (is_array($domains->hosted_in->EditValue)) {
	$arwrk = $domains->hosted_in->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($domains->hosted_in->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $domains->hosted_in->OldValue = "";
?>
</select>
<?php } ?>
<?php if ($domains->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $domains->hosted_in->ViewAttributes() ?>>
<?php echo $domains->hosted_in->ListViewValue() ?></span>
<input type="hidden" name="x<?php echo $domains_grid->RowIndex ?>_hosted_in" id="x<?php echo $domains_grid->RowIndex ?>_hosted_in" value="<?php echo ew_HtmlEncode($domains->hosted_in->FormValue) ?>" />
<input type="hidden" name="o<?php echo $domains_grid->RowIndex ?>_hosted_in" id="o<?php echo $domains_grid->RowIndex ?>_hosted_in" value="<?php echo ew_HtmlEncode($domains->hosted_in->OldValue) ?>" />
<?php } ?>
</span></td>
	<?php } ?>
<?php

// Render list options (body, right)
$domains_grid->ListOptions->Render("body", "right", $domains_grid->RowCnt);
?>
	</tr>
<?php if ($domains->RowType == EW_ROWTYPE_ADD || $domains->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fdomainsgrid.UpdateOpts(<?php echo $domains_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($domains->CurrentAction <> "gridadd" || $domains->CurrentMode == "copy")
		if (!$domains_grid->Recordset->EOF) $domains_grid->Recordset->MoveNext();
}
?>
<?php
	if ($domains->CurrentMode == "add" || $domains->CurrentMode == "copy" || $domains->CurrentMode == "edit") {
		$domains_grid->RowIndex = '$rowindex$';
		$domains_grid->LoadDefaultValues();

		// Set row properties
		$domains->ResetAttrs();
		$domains->RowAttrs = array_merge($domains->RowAttrs, array('data-rowindex'=>$domains_grid->RowIndex, 'id'=>'r0_domains', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($domains->RowAttrs["class"], "ewTemplate");
		$domains->RowType = EW_ROWTYPE_ADD;

		// Render row
		$domains_grid->RenderRow();

		// Render list options
		$domains_grid->RenderListOptions();
		$domains_grid->StartRowCnt = 0;
?>
	<tr<?php echo $domains->RowAttributes() ?>>
<?php

// Render list options (body, left)
$domains_grid->ListOptions->Render("body", "left", $domains_grid->RowIndex);
?>
	<?php if ($domains->dominio->Visible) { // dominio ?>
		<td><span2 id="el$rowindex$_domains_dominio" class="domains_dominio">
<?php if ($domains->CurrentAction <> "F") { ?>
<input type="text" name="x<?php echo $domains_grid->RowIndex ?>_dominio" id="x<?php echo $domains_grid->RowIndex ?>_dominio" size="30" maxlength="145" value="<?php echo $domains->dominio->EditValue ?>"<?php echo $domains->dominio->EditAttributes() ?> />
<?php } else { ?>
<span<?php echo $domains->dominio->ViewAttributes() ?>>
<?php echo $domains->dominio->ViewValue ?></span>
<input type="hidden" name="x<?php echo $domains_grid->RowIndex ?>_dominio" id="x<?php echo $domains_grid->RowIndex ?>_dominio" value="<?php echo ew_HtmlEncode($domains->dominio->FormValue) ?>" />
<?php } ?>
<input type="hidden" name="o<?php echo $domains_grid->RowIndex ?>_dominio" id="o<?php echo $domains_grid->RowIndex ?>_dominio" value="<?php echo ew_HtmlEncode($domains->dominio->OldValue) ?>" />
</span></td>
	<?php } ?>
	<?php if ($domains->id_domains->Visible) { // id_domains ?>
		<td><span2 id="el$rowindex$_domains_id_domains" class="domains_id_domains">
<?php if ($domains->CurrentAction <> "F") { ?>
<?php } else { ?>
<input type="hidden" name="x<?php echo $domains_grid->RowIndex ?>_id_domains" id="x<?php echo $domains_grid->RowIndex ?>_id_domains" value="<?php echo ew_HtmlEncode($domains->id_domains->FormValue) ?>" />
<?php } ?>
<input type="hidden" name="o<?php echo $domains_grid->RowIndex ?>_id_domains" id="o<?php echo $domains_grid->RowIndex ?>_id_domains" value="<?php echo ew_HtmlEncode($domains->id_domains->OldValue) ?>" />
</span></td>
	<?php } ?>
	<?php if ($domains->hosted_in->Visible) { // hosted_in ?>
		<td><span2 id="el$rowindex$_domains_hosted_in" class="domains_hosted_in">
<?php if ($domains->CurrentAction <> "F") { ?>
<select id="x<?php echo $domains_grid->RowIndex ?>_hosted_in" name="x<?php echo $domains_grid->RowIndex ?>_hosted_in"<?php echo $domains->hosted_in->EditAttributes() ?>>
<?php
if (is_array($domains->hosted_in->EditValue)) {
	$arwrk = $domains->hosted_in->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($domains->hosted_in->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $domains->hosted_in->OldValue = "";
?>
</select>
<?php } else { ?>
<span<?php echo $domains->hosted_in->ViewAttributes() ?>>
<?php echo $domains->hosted_in->ViewValue ?></span>
<input type="hidden" name="x<?php echo $domains_grid->RowIndex ?>_hosted_in" id="x<?php echo $domains_grid->RowIndex ?>_hosted_in" value="<?php echo ew_HtmlEncode($domains->hosted_in->FormValue) ?>" />
<?php } ?>
<input type="hidden" name="o<?php echo $domains_grid->RowIndex ?>_hosted_in" id="o<?php echo $domains_grid->RowIndex ?>_hosted_in" value="<?php echo ew_HtmlEncode($domains->hosted_in->OldValue) ?>" />
</span></td>
	<?php } ?>
<?php

// Render list options (body, right)
$domains_grid->ListOptions->Render("body", "right", $domains_grid->RowCnt);
?>
<script type="text/javascript">
fdomainsgrid.UpdateOpts(<?php echo $domains_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
<!--</table>-->
<?php if ($domains->CurrentMode == "add" || $domains->CurrentMode == "copy") { ?>
<input class="btn btn-large btn-success" type="submit" />
<input type="hidden" name="a_list" id="a_list" value="gridinsert" />
<input type="hidden" name="key_count" id="key_count" value="<?php echo $domains_grid->KeyCount ?>" />
<?php echo $domains_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($domains->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="key_count" id="key_count" value="<?php echo $domains_grid->KeyCount ?>" />
<?php echo $domains_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($domains->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" id="detailpage" value="fdomainsgrid">
<?php

// Close recordset
if ($domains_grid->Recordset)
	$domains_grid->Recordset->Close();
?>
</div>
<?php if ($domains->Export == "") { ?>
<script type="text/javascript">
fdomainsgrid.Init();
</script>
<?php } ?>
<?php
$domains_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$domains_grid->Page_Terminate();
$Page = &$MasterPage;
?>
