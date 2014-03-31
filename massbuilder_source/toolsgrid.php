<?php include_once "usersinfo.php" ?>
<?php

// Create page object
if (!isset($tools_grid)) $tools_grid = new ctools_grid();

// Page init
$tools_grid->Page_Init();

// Page main
$tools_grid->Page_Main();
?>
<?php if ($tools->Export == "") { ?>
<script type="text/javascript">

// Page object
var tools_grid = new ew_Page("tools_grid");
tools_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = tools_grid.PageID; // For backward compatibility

// Form object
var ftoolsgrid = new ew_Form("ftoolsgrid");

// Validate form
ftoolsgrid.Validate = function(fobj) {
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
		elm = fobj.elements["x" + infix + "_idtools"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($tools->idtools->FldErrMsg()) ?>");

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
ftoolsgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "target_domain", false)) return false;
	if (ew_ValueChanged(fobj, infix, "type", false)) return false;
	if (ew_ValueChanged(fobj, infix, "status[]", false)) return false;
	if (ew_ValueChanged(fobj, infix, "Descripcion", false)) return false;
	if (ew_ValueChanged(fobj, infix, "tags", false)) return false;
	return true;
}

// Form_CustomValidate event
ftoolsgrid.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
ftoolsgrid.ValidateRequired = true;
<?php } else { ?>
ftoolsgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
ftoolsgrid.Lists["x_target_domain"] = {"LinkField":"x_id_domains","Ajax":true,"AutoFill":false,"DisplayFields":["x_dominio","","",""],"ParentFields":[],"FilterFields":[],"Options":[]};

// Form object for search
</script>
<?php } ?>
<?php
if ($tools->CurrentAction == "gridadd") {
	if ($tools->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$tools_grid->TotalRecs = $tools->SelectRecordCount();
			$tools_grid->Recordset = $tools_grid->LoadRecordset($tools_grid->StartRec-1, $tools_grid->DisplayRecs);
		} else {
			if ($tools_grid->Recordset = $tools_grid->LoadRecordset())
				$tools_grid->TotalRecs = $tools_grid->Recordset->RecordCount();
		}
		$tools_grid->StartRec = 1;
		$tools_grid->DisplayRecs = $tools_grid->TotalRecs;
	} else {
		$tools->CurrentFilter = "0=1";
		$tools_grid->StartRec = 1;
		$tools_grid->DisplayRecs = $tools->GridAddRowCount;
	}
	$tools_grid->TotalRecs = $tools_grid->DisplayRecs;
	$tools_grid->StopRec = $tools_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$tools_grid->TotalRecs = $tools->SelectRecordCount();
	} else {
		if ($tools_grid->Recordset = $tools_grid->LoadRecordset())
			$tools_grid->TotalRecs = $tools_grid->Recordset->RecordCount();
	}
	$tools_grid->StartRec = 1;
	$tools_grid->DisplayRecs = $tools_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$tools_grid->Recordset = $tools_grid->LoadRecordset($tools_grid->StartRec-1, $tools_grid->DisplayRecs);
}
?>
<p style="white-spaceJAMES: nowrapJAMES;"><h4 id="ewPageCaption" class="page-header ewTitle ewTableTitle"> <?php if ($tools->CurrentMode == "add" || $tools->CurrentMode == "copy") { ?><?php echo $Language->Phrase("Add") ?><?php } elseif ($tools->CurrentMode == "edit") { ?><?php echo $Language->Phrase("Edit") ?><?php } ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $tools->TableCaption() ?></h4></p>
</p>
<?php $tools_grid->ShowPageHeader(); ?>
<?php
$tools_grid->ShowMessage();
?>
<div id="ftoolsgrid" class="ewForm">
<div id="gmp_tools" class="ewGridMiddlePanel row-fluid">
<ul class="thumbnails">
<table id="tbl_toolsgrid" class="ewTable ewTableSeparate table table-striped table-bordered ">
<?php echo $tools->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$tools_grid->RenderListOptions();

// Render list options (header, left)
$tools_grid->ListOptions->Render("header", "left");
?>
<?php if ($tools->idtools->Visible) { // idtools ?>
	<?php if ($tools->SortUrl($tools->idtools) == "") { ?>
		<th><span id="elh_tools_idtools" class="tools_idtools">
		<div class="ewTableHeaderBtn"><?php echo $tools->idtools->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div><span id="elh_tools_idtools" class="tools_idtools">
			<div class="ewTableHeaderBtn">			
			<?php echo $tools->idtools->FldCaption() ?>
			<?php if ($tools->idtools->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($tools->idtools->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($tools->target_domain->Visible) { // target_domain ?>
	<?php if ($tools->SortUrl($tools->target_domain) == "") { ?>
		<th><span id="elh_tools_target_domain" class="tools_target_domain">
		<div class="ewTableHeaderBtn"><?php echo $tools->target_domain->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div><span id="elh_tools_target_domain" class="tools_target_domain">
			<div class="ewTableHeaderBtn">			
			<?php echo $tools->target_domain->FldCaption() ?>
			<?php if ($tools->target_domain->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($tools->target_domain->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($tools->type->Visible) { // type ?>
	<?php if ($tools->SortUrl($tools->type) == "") { ?>
		<th><span id="elh_tools_type" class="tools_type">
		<div class="ewTableHeaderBtn"><?php echo $tools->type->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div><span id="elh_tools_type" class="tools_type">
			<div class="ewTableHeaderBtn">			
			<?php echo $tools->type->FldCaption() ?>
			<?php if ($tools->type->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($tools->type->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($tools->status->Visible) { // status ?>
	<?php if ($tools->SortUrl($tools->status) == "") { ?>
		<th><span id="elh_tools_status" class="tools_status">
		<div class="ewTableHeaderBtn"><?php echo $tools->status->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div><span id="elh_tools_status" class="tools_status">
			<div class="ewTableHeaderBtn">			
			<?php echo $tools->status->FldCaption() ?>
			<?php if ($tools->status->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($tools->status->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($tools->Descripcion->Visible) { // Descripcion ?>
	<?php if ($tools->SortUrl($tools->Descripcion) == "") { ?>
		<th><span id="elh_tools_Descripcion" class="tools_Descripcion">
		<div class="ewTableHeaderBtn"><?php echo $tools->Descripcion->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div><span id="elh_tools_Descripcion" class="tools_Descripcion">
			<div class="ewTableHeaderBtn">			
			<?php echo $tools->Descripcion->FldCaption() ?>
			<?php if ($tools->Descripcion->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($tools->Descripcion->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($tools->tags->Visible) { // tags ?>
	<?php if ($tools->SortUrl($tools->tags) == "") { ?>
		<th><span id="elh_tools_tags" class="tools_tags">
		<div class="ewTableHeaderBtn"><?php echo $tools->tags->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div><span id="elh_tools_tags" class="tools_tags">
			<div class="ewTableHeaderBtn">			
			<?php echo $tools->tags->FldCaption() ?>
			<?php if ($tools->tags->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($tools->tags->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$tools_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$tools_grid->StartRec = 1;
$tools_grid->StopRec = $tools_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue("key_count") && ($tools->CurrentAction == "gridadd" || $tools->CurrentAction == "gridedit" || $tools->CurrentAction == "F")) {
		$tools_grid->KeyCount = $objForm->GetValue("key_count");
		$tools_grid->StopRec = $tools_grid->KeyCount;
	}
}
$tools_grid->RecCnt = $tools_grid->StartRec - 1;
if ($tools_grid->Recordset && !$tools_grid->Recordset->EOF) {
	$tools_grid->Recordset->MoveFirst();
	if (!$bSelectLimit && $tools_grid->StartRec > 1)
		$tools_grid->Recordset->Move($tools_grid->StartRec - 1);
} elseif (!$tools->AllowAddDeleteRow && $tools_grid->StopRec == 0) {
	$tools_grid->StopRec = $tools->GridAddRowCount;
}

// Initialize aggregate
$tools->RowType = EW_ROWTYPE_AGGREGATEINIT;
$tools->ResetAttrs();
$tools_grid->RenderRow();
if ($tools->CurrentAction == "gridadd")
	$tools_grid->RowIndex = 0;
if ($tools->CurrentAction == "gridedit")
	$tools_grid->RowIndex = 0;
while ($tools_grid->RecCnt < $tools_grid->StopRec) {
	$tools_grid->RecCnt++;
	if (intval($tools_grid->RecCnt) >= intval($tools_grid->StartRec)) {
		$tools_grid->RowCnt++;
		if ($tools->CurrentAction == "gridadd" || $tools->CurrentAction == "gridedit" || $tools->CurrentAction == "F") {
			$tools_grid->RowIndex++;
			$objForm->Index = $tools_grid->RowIndex;
			if ($objForm->HasValue("k_action"))
				$tools_grid->RowAction = strval($objForm->GetValue("k_action"));
			elseif ($tools->CurrentAction == "gridadd")
				$tools_grid->RowAction = "insert";
			else
				$tools_grid->RowAction = "";
		}

		// Set up key count
		$tools_grid->KeyCount = $tools_grid->RowIndex;

		// Init row class and style
		$tools->ResetAttrs();
		$tools->CssClass = "";
		if ($tools->CurrentAction == "gridadd") {
			if ($tools->CurrentMode == "copy") {
				$tools_grid->LoadRowValues($tools_grid->Recordset); // Load row values
				$tools_grid->SetRecordKey($tools_grid->RowOldKey, $tools_grid->Recordset); // Set old record key
			} else {
				$tools_grid->LoadDefaultValues(); // Load default values
				$tools_grid->RowOldKey = ""; // Clear old key value
			}
		} elseif ($tools->CurrentAction == "gridedit") {
			$tools_grid->LoadRowValues($tools_grid->Recordset); // Load row values
		}
		$tools->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($tools->CurrentAction == "gridadd") // Grid add
			$tools->RowType = EW_ROWTYPE_ADD; // Render add
		if ($tools->CurrentAction == "gridadd" && $tools->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$tools_grid->RestoreCurrentRowFormValues($tools_grid->RowIndex); // Restore form values
		if ($tools->CurrentAction == "gridedit") { // Grid edit
			if ($tools->EventCancelled) {
				$tools_grid->RestoreCurrentRowFormValues($tools_grid->RowIndex); // Restore form values
			}
			if ($tools_grid->RowAction == "insert")
				$tools->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$tools->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($tools->CurrentAction == "gridedit" && ($tools->RowType == EW_ROWTYPE_EDIT || $tools->RowType == EW_ROWTYPE_ADD) && $tools->EventCancelled) // Update failed
			$tools_grid->RestoreCurrentRowFormValues($tools_grid->RowIndex); // Restore form values
		if ($tools->RowType == EW_ROWTYPE_EDIT) // Edit row
			$tools_grid->EditRowCnt++;
		if ($tools->CurrentAction == "F") // Confirm row
			$tools_grid->RestoreCurrentRowFormValues($tools_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$tools->RowAttrs = array_merge($tools->RowAttrs, array('data-rowindex'=>$tools_grid->RowCnt, 'id'=>'r' . $tools_grid->RowCnt . '_tools', 'data-rowtype'=>$tools->RowType));

		// Render row
		$tools_grid->RenderRow();

		// Render list options
		$tools_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($tools_grid->RowAction <> "delete" && $tools_grid->RowAction <> "insertdelete" && !($tools_grid->RowAction == "insert" && $tools->CurrentAction == "F" && $tools_grid->EmptyRow())) {
?>
	<tr<?php echo $tools->RowAttributes() ?>>
<?php

// Render list options (body, left)
$tools_grid->ListOptions->Render("body", "left", $tools_grid->RowCnt);
?>
	<?php if ($tools->idtools->Visible) { // idtools ?>
		<td<?php echo $tools->idtools->CellAttributes() ?>><span id="el<?php echo $tools_grid->RowCnt ?>_tools_idtools" class="tools_idtools">
<?php if ($tools->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" name="o<?php echo $tools_grid->RowIndex ?>_idtools" id="o<?php echo $tools_grid->RowIndex ?>_idtools" value="<?php echo ew_HtmlEncode($tools->idtools->OldValue) ?>" />
<?php } ?>
<?php if ($tools->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<div id="orig_tools_idtools" class="ewDisplayNone">
<span<?php echo $tools->idtools->ViewAttributes() ?>>
<?php echo $tools->idtools->EditValue ?></span>
</div>
<?php    
                     
if( BASENAME($_SERVER['PHP_SELF']) == 'toolslist.php' ){   // solo si es entrieslist.php
   $data  = '<a class="operacion btn btn-success" name="facebook_get_entries" id="'.CurrentTable()->idtools->CurrentValue.'" href="#">Process</a> ';  
   echo $data;
} else    {
   //$data  = '<a class="operacion btn btn-success" name="BackUp"    id="'.$_GET['id_domains'].'" href="#">BackUp</a> ';  
   //$data .= '<a class="operacion btn btn-primary" name="Traducion" id="'.$_GET['id_domains'].'" href="#">Traducion</a>';    
   //echo $data;          
}      
?> 
                                                                        
<?php if (debug01) echo CurrentTable()->idtools->CurrentValue;   ?>
<input type="hidden" name="x<?php echo $tools_grid->RowIndex ?>_idtools" id="x<?php echo $tools_grid->RowIndex ?>_idtools" value="<?php echo ew_HtmlEncode($tools->idtools->CurrentValue) ?>" />
<?php } ?>
<?php if ($tools->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<div id="orig_tools_idtools" class="ewDisplayNone">
<span<?php echo $tools->idtools->ViewAttributes() ?>>
<?php echo $tools->idtools->ListViewValue() ?></span>
</div>
<?php    
                     
if( BASENAME($_SERVER['PHP_SELF']) == 'toolslist.php' ){   // solo si es entrieslist.php
   $data  = '<a class="operacion btn btn-success" name="facebook_get_entries" id="'.CurrentTable()->idtools->CurrentValue.'" href="#">Process</a> ';  
   echo $data;
} else    {
   //$data  = '<a class="operacion btn btn-success" name="BackUp"    id="'.$_GET['id_domains'].'" href="#">BackUp</a> ';  
   //$data .= '<a class="operacion btn btn-primary" name="Traducion" id="'.$_GET['id_domains'].'" href="#">Traducion</a>';    
   //echo $data;          
}      
?> 
                                                                        
<?php if (debug01) echo CurrentTable()->idtools->CurrentValue;   ?>
<input type="hidden" name="x<?php echo $tools_grid->RowIndex ?>_idtools" id="x<?php echo $tools_grid->RowIndex ?>_idtools" value="<?php echo ew_HtmlEncode($tools->idtools->FormValue) ?>" />
<input type="hidden" name="o<?php echo $tools_grid->RowIndex ?>_idtools" id="o<?php echo $tools_grid->RowIndex ?>_idtools" value="<?php echo ew_HtmlEncode($tools->idtools->OldValue) ?>" />
<?php } ?>
</span></td>
	<?php } ?>
<a id="<?php echo $tools_grid->PageObjName . "_row_" . $tools_grid->RowCnt ?>"></a>
	<?php if ($tools->target_domain->Visible) { // target_domain ?>
		<td<?php echo $tools->target_domain->CellAttributes() ?>><span id="el<?php echo $tools_grid->RowCnt ?>_tools_target_domain" class="tools_target_domain">
<?php if ($tools->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<select id="x<?php echo $tools_grid->RowIndex ?>_target_domain" name="x<?php echo $tools_grid->RowIndex ?>_target_domain"<?php echo $tools->target_domain->EditAttributes() ?>>
<?php
if (is_array($tools->target_domain->EditValue)) {
	$arwrk = $tools->target_domain->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($tools->target_domain->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $tools->target_domain->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `id_domains`, `dominio` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `domains`";
 $sWhereWrk = "";
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $tools_grid->RowIndex ?>_target_domain" id="s_x<?php echo $tools_grid->RowIndex ?>_target_domain" value="s=<?php echo TEAencrypt($sSqlWrk) ?>&fn=<?php echo urlencode($tools->target_domain->LookupFn) ?>&f0=<?php echo TEAencrypt("`id_domains` = {filter_value}"); ?>&t0=3" />
<input type="hidden" name="o<?php echo $tools_grid->RowIndex ?>_target_domain" id="o<?php echo $tools_grid->RowIndex ?>_target_domain" value="<?php echo ew_HtmlEncode($tools->target_domain->OldValue) ?>" />
<?php } ?>
<?php if ($tools->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<select id="x<?php echo $tools_grid->RowIndex ?>_target_domain" name="x<?php echo $tools_grid->RowIndex ?>_target_domain"<?php echo $tools->target_domain->EditAttributes() ?>>
<?php
if (is_array($tools->target_domain->EditValue)) {
	$arwrk = $tools->target_domain->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($tools->target_domain->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $tools->target_domain->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `id_domains`, `dominio` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `domains`";
 $sWhereWrk = "";
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $tools_grid->RowIndex ?>_target_domain" id="s_x<?php echo $tools_grid->RowIndex ?>_target_domain" value="s=<?php echo TEAencrypt($sSqlWrk) ?>&fn=<?php echo urlencode($tools->target_domain->LookupFn) ?>&f0=<?php echo TEAencrypt("`id_domains` = {filter_value}"); ?>&t0=3" />
<?php } ?>
<?php if ($tools->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $tools->target_domain->ViewAttributes() ?>>
<?php echo $tools->target_domain->ListViewValue() ?></span>
<input type="hidden" name="x<?php echo $tools_grid->RowIndex ?>_target_domain" id="x<?php echo $tools_grid->RowIndex ?>_target_domain" value="<?php echo ew_HtmlEncode($tools->target_domain->FormValue) ?>" />
<input type="hidden" name="o<?php echo $tools_grid->RowIndex ?>_target_domain" id="o<?php echo $tools_grid->RowIndex ?>_target_domain" value="<?php echo ew_HtmlEncode($tools->target_domain->OldValue) ?>" />
<?php } ?>
</span></td>
	<?php } ?>
	<?php if ($tools->type->Visible) { // type ?>
		<td<?php echo $tools->type->CellAttributes() ?>><span id="el<?php echo $tools_grid->RowCnt ?>_tools_type" class="tools_type">
<?php if ($tools->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<select id="x<?php echo $tools_grid->RowIndex ?>_type" name="x<?php echo $tools_grid->RowIndex ?>_type"<?php echo $tools->type->EditAttributes() ?>>
<?php
if (is_array($tools->type->EditValue)) {
	$arwrk = $tools->type->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($tools->type->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $tools->type->OldValue = "";
?>
</select>
<input type="hidden" name="o<?php echo $tools_grid->RowIndex ?>_type" id="o<?php echo $tools_grid->RowIndex ?>_type" value="<?php echo ew_HtmlEncode($tools->type->OldValue) ?>" />
<?php } ?>
<?php if ($tools->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<select id="x<?php echo $tools_grid->RowIndex ?>_type" name="x<?php echo $tools_grid->RowIndex ?>_type"<?php echo $tools->type->EditAttributes() ?>>
<?php
if (is_array($tools->type->EditValue)) {
	$arwrk = $tools->type->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($tools->type->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $tools->type->OldValue = "";
?>
</select>
<?php } ?>
<?php if ($tools->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $tools->type->ViewAttributes() ?>>
<?php echo $tools->type->ListViewValue() ?></span>
<input type="hidden" name="x<?php echo $tools_grid->RowIndex ?>_type" id="x<?php echo $tools_grid->RowIndex ?>_type" value="<?php echo ew_HtmlEncode($tools->type->FormValue) ?>" />
<input type="hidden" name="o<?php echo $tools_grid->RowIndex ?>_type" id="o<?php echo $tools_grid->RowIndex ?>_type" value="<?php echo ew_HtmlEncode($tools->type->OldValue) ?>" />
<?php } ?>
</span></td>
	<?php } ?>
	<?php if ($tools->status->Visible) { // status ?>
		<td<?php echo $tools->status->CellAttributes() ?>><span id="el<?php echo $tools_grid->RowCnt ?>_tools_status" class="tools_status">
<?php if ($tools->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<div id="tp_x<?php echo $tools_grid->RowIndex ?>_status" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME; ?>"><input type="checkbox" name="x<?php echo $tools_grid->RowIndex ?>_status[]" id="x<?php echo $tools_grid->RowIndex ?>_status[]" value="{value}"<?php echo $tools->status->EditAttributes() ?> /></div>
<div id="dsl_x<?php echo $tools_grid->RowIndex ?>_status" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $tools->status->EditValue;
if (is_array($arwrk)) {
	$armultiwrk= explode(",", strval($tools->status->CurrentValue));
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = "";
		$cnt = count($armultiwrk);
		for ($ari = 0; $ari < $cnt; $ari++) {
			if (strval($arwrk[$rowcntwrk][0]) == trim(strval($armultiwrk[$ari]))) {
				$selwrk = " checked=\"checked\"";
				if ($selwrk <> "") $emptywrk = FALSE;
				break;
			}
		}

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label><input type="checkbox" name="x<?php echo $tools_grid->RowIndex ?>_status[]" id="x<?php echo $tools_grid->RowIndex ?>_status[]" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $tools->status->EditAttributes() ?> /><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $tools->status->OldValue = "";
?>
</div>
<input type="hidden" name="o<?php echo $tools_grid->RowIndex ?>_status[]" id="o<?php echo $tools_grid->RowIndex ?>_status[]" value="<?php echo ew_HtmlEncode($tools->status->OldValue) ?>" />
<?php } ?>
<?php if ($tools->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<div id="tp_x<?php echo $tools_grid->RowIndex ?>_status" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME; ?>"><input type="checkbox" name="x<?php echo $tools_grid->RowIndex ?>_status[]" id="x<?php echo $tools_grid->RowIndex ?>_status[]" value="{value}"<?php echo $tools->status->EditAttributes() ?> /></div>
<div id="dsl_x<?php echo $tools_grid->RowIndex ?>_status" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $tools->status->EditValue;
if (is_array($arwrk)) {
	$armultiwrk= explode(",", strval($tools->status->CurrentValue));
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = "";
		$cnt = count($armultiwrk);
		for ($ari = 0; $ari < $cnt; $ari++) {
			if (strval($arwrk[$rowcntwrk][0]) == trim(strval($armultiwrk[$ari]))) {
				$selwrk = " checked=\"checked\"";
				if ($selwrk <> "") $emptywrk = FALSE;
				break;
			}
		}

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label><input type="checkbox" name="x<?php echo $tools_grid->RowIndex ?>_status[]" id="x<?php echo $tools_grid->RowIndex ?>_status[]" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $tools->status->EditAttributes() ?> /><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $tools->status->OldValue = "";
?>
</div>
<?php } ?>
<?php if ($tools->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $tools->status->ViewAttributes() ?>>
<?php echo $tools->status->ListViewValue() ?></span>
<input type="hidden" name="x<?php echo $tools_grid->RowIndex ?>_status" id="x<?php echo $tools_grid->RowIndex ?>_status" value="<?php echo ew_HtmlEncode($tools->status->FormValue) ?>" />
<input type="hidden" name="o<?php echo $tools_grid->RowIndex ?>_status[]" id="o<?php echo $tools_grid->RowIndex ?>_status[]" value="<?php echo ew_HtmlEncode($tools->status->OldValue) ?>" />
<?php } ?>
</span></td>
	<?php } ?>
	<?php if ($tools->Descripcion->Visible) { // Descripcion ?>
		<td<?php echo $tools->Descripcion->CellAttributes() ?>><span id="el<?php echo $tools_grid->RowCnt ?>_tools_Descripcion" class="tools_Descripcion">
<?php if ($tools->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="text" name="x<?php echo $tools_grid->RowIndex ?>_Descripcion" id="x<?php echo $tools_grid->RowIndex ?>_Descripcion" size="30" maxlength="245" value="<?php echo $tools->Descripcion->EditValue ?>"<?php echo $tools->Descripcion->EditAttributes() ?> />
<input type="hidden" name="o<?php echo $tools_grid->RowIndex ?>_Descripcion" id="o<?php echo $tools_grid->RowIndex ?>_Descripcion" value="<?php echo ew_HtmlEncode($tools->Descripcion->OldValue) ?>" />
<?php } ?>
<?php if ($tools->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<input type="text" name="x<?php echo $tools_grid->RowIndex ?>_Descripcion" id="x<?php echo $tools_grid->RowIndex ?>_Descripcion" size="30" maxlength="245" value="<?php echo $tools->Descripcion->EditValue ?>"<?php echo $tools->Descripcion->EditAttributes() ?> />
<?php } ?>
<?php if ($tools->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $tools->Descripcion->ViewAttributes() ?>>
<?php echo $tools->Descripcion->ListViewValue() ?></span>
<input type="hidden" name="x<?php echo $tools_grid->RowIndex ?>_Descripcion" id="x<?php echo $tools_grid->RowIndex ?>_Descripcion" value="<?php echo ew_HtmlEncode($tools->Descripcion->FormValue) ?>" />
<input type="hidden" name="o<?php echo $tools_grid->RowIndex ?>_Descripcion" id="o<?php echo $tools_grid->RowIndex ?>_Descripcion" value="<?php echo ew_HtmlEncode($tools->Descripcion->OldValue) ?>" />
<?php } ?>
</span></td>
	<?php } ?>
	<?php if ($tools->tags->Visible) { // tags ?>
		<td<?php echo $tools->tags->CellAttributes() ?>><span id="el<?php echo $tools_grid->RowCnt ?>_tools_tags" class="tools_tags">
<?php if ($tools->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="text" name="x<?php echo $tools_grid->RowIndex ?>_tags" id="x<?php echo $tools_grid->RowIndex ?>_tags" size="30" maxlength="245" value="<?php echo $tools->tags->EditValue ?>"<?php echo $tools->tags->EditAttributes() ?> />
<input type="hidden" name="o<?php echo $tools_grid->RowIndex ?>_tags" id="o<?php echo $tools_grid->RowIndex ?>_tags" value="<?php echo ew_HtmlEncode($tools->tags->OldValue) ?>" />
<?php } ?>
<?php if ($tools->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<input type="text" name="x<?php echo $tools_grid->RowIndex ?>_tags" id="x<?php echo $tools_grid->RowIndex ?>_tags" size="30" maxlength="245" value="<?php echo $tools->tags->EditValue ?>"<?php echo $tools->tags->EditAttributes() ?> />
<?php } ?>
<?php if ($tools->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $tools->tags->ViewAttributes() ?>>
<?php echo $tools->tags->ListViewValue() ?></span>
<input type="hidden" name="x<?php echo $tools_grid->RowIndex ?>_tags" id="x<?php echo $tools_grid->RowIndex ?>_tags" value="<?php echo ew_HtmlEncode($tools->tags->FormValue) ?>" />
<input type="hidden" name="o<?php echo $tools_grid->RowIndex ?>_tags" id="o<?php echo $tools_grid->RowIndex ?>_tags" value="<?php echo ew_HtmlEncode($tools->tags->OldValue) ?>" />
<?php } ?>
</span></td>
	<?php } ?>
<?php

// Render list options (body, right)
$tools_grid->ListOptions->Render("body", "right", $tools_grid->RowCnt);
?>
	</tr>
<?php if ($tools->RowType == EW_ROWTYPE_ADD || $tools->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
ftoolsgrid.UpdateOpts(<?php echo $tools_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($tools->CurrentAction <> "gridadd" || $tools->CurrentMode == "copy")
		if (!$tools_grid->Recordset->EOF) $tools_grid->Recordset->MoveNext();
}
?>
<?php
	if ($tools->CurrentMode == "add" || $tools->CurrentMode == "copy" || $tools->CurrentMode == "edit") {
		$tools_grid->RowIndex = '$rowindex$';
		$tools_grid->LoadDefaultValues();

		// Set row properties
		$tools->ResetAttrs();
		$tools->RowAttrs = array_merge($tools->RowAttrs, array('data-rowindex'=>$tools_grid->RowIndex, 'id'=>'r0_tools', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($tools->RowAttrs["class"], "ewTemplate");
		$tools->RowType = EW_ROWTYPE_ADD;

		// Render row
		$tools_grid->RenderRow();

		// Render list options
		$tools_grid->RenderListOptions();
		$tools_grid->StartRowCnt = 0;
?>
	<tr<?php echo $tools->RowAttributes() ?>>
<?php

// Render list options (body, left)
$tools_grid->ListOptions->Render("body", "left", $tools_grid->RowIndex);
?>
	<?php if ($tools->idtools->Visible) { // idtools ?>
		<td><span2 id="el$rowindex$_tools_idtools" class="tools_idtools">
<?php if ($tools->CurrentAction <> "F") { ?>
<?php } else { ?>
<div id="orig_tools_idtools" class="ewDisplayNone">
<span<?php echo $tools->idtools->ViewAttributes() ?>>
<?php echo $tools->idtools->ViewValue ?></span>
</div>
<?php    
                     
if( BASENAME($_SERVER['PHP_SELF']) == 'toolslist.php' ){   // solo si es entrieslist.php
   $data  = '<a class="operacion btn btn-success" name="facebook_get_entries" id="'.CurrentTable()->idtools->CurrentValue.'" href="#">Process</a> ';  
   echo $data;
} else    {
   //$data  = '<a class="operacion btn btn-success" name="BackUp"    id="'.$_GET['id_domains'].'" href="#">BackUp</a> ';  
   //$data .= '<a class="operacion btn btn-primary" name="Traducion" id="'.$_GET['id_domains'].'" href="#">Traducion</a>';    
   //echo $data;          
}      
?> 
                                                                        
<?php if (debug01) echo CurrentTable()->idtools->CurrentValue;   ?>
<input type="hidden" name="x<?php echo $tools_grid->RowIndex ?>_idtools" id="x<?php echo $tools_grid->RowIndex ?>_idtools" value="<?php echo ew_HtmlEncode($tools->idtools->FormValue) ?>" />
<?php } ?>
<input type="hidden" name="o<?php echo $tools_grid->RowIndex ?>_idtools" id="o<?php echo $tools_grid->RowIndex ?>_idtools" value="<?php echo ew_HtmlEncode($tools->idtools->OldValue) ?>" />
</span></td>
	<?php } ?>
	<?php if ($tools->target_domain->Visible) { // target_domain ?>
		<td><span2 id="el$rowindex$_tools_target_domain" class="tools_target_domain">
<?php if ($tools->CurrentAction <> "F") { ?>
<select id="x<?php echo $tools_grid->RowIndex ?>_target_domain" name="x<?php echo $tools_grid->RowIndex ?>_target_domain"<?php echo $tools->target_domain->EditAttributes() ?>>
<?php
if (is_array($tools->target_domain->EditValue)) {
	$arwrk = $tools->target_domain->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($tools->target_domain->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $tools->target_domain->OldValue = "";
?>
</select>
<?php
 $sSqlWrk = "SELECT `id_domains`, `dominio` AS `DispFld`, '' AS `Disp2Fld`, '' AS `Disp3Fld`, '' AS `Disp4Fld` FROM `domains`";
 $sWhereWrk = "";
 if ($sWhereWrk <> "") $sSqlWrk .= " WHERE " . $sWhereWrk;
?>
<input type="hidden" name="s_x<?php echo $tools_grid->RowIndex ?>_target_domain" id="s_x<?php echo $tools_grid->RowIndex ?>_target_domain" value="s=<?php echo TEAencrypt($sSqlWrk) ?>&fn=<?php echo urlencode($tools->target_domain->LookupFn) ?>&f0=<?php echo TEAencrypt("`id_domains` = {filter_value}"); ?>&t0=3" />
<?php } else { ?>
<span<?php echo $tools->target_domain->ViewAttributes() ?>>
<?php echo $tools->target_domain->ViewValue ?></span>
<input type="hidden" name="x<?php echo $tools_grid->RowIndex ?>_target_domain" id="x<?php echo $tools_grid->RowIndex ?>_target_domain" value="<?php echo ew_HtmlEncode($tools->target_domain->FormValue) ?>" />
<?php } ?>
<input type="hidden" name="o<?php echo $tools_grid->RowIndex ?>_target_domain" id="o<?php echo $tools_grid->RowIndex ?>_target_domain" value="<?php echo ew_HtmlEncode($tools->target_domain->OldValue) ?>" />
</span></td>
	<?php } ?>
	<?php if ($tools->type->Visible) { // type ?>
		<td><span2 id="el$rowindex$_tools_type" class="tools_type">
<?php if ($tools->CurrentAction <> "F") { ?>
<select id="x<?php echo $tools_grid->RowIndex ?>_type" name="x<?php echo $tools_grid->RowIndex ?>_type"<?php echo $tools->type->EditAttributes() ?>>
<?php
if (is_array($tools->type->EditValue)) {
	$arwrk = $tools->type->EditValue;
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = (strval($tools->type->CurrentValue) == strval($arwrk[$rowcntwrk][0])) ? " selected=\"selected\"" : "";
		if ($selwrk <> "") $emptywrk = FALSE;
?>
<option value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?>>
<?php echo $arwrk[$rowcntwrk][1] ?>
</option>
<?php
	}
}
if (@$emptywrk) $tools->type->OldValue = "";
?>
</select>
<?php } else { ?>
<span<?php echo $tools->type->ViewAttributes() ?>>
<?php echo $tools->type->ViewValue ?></span>
<input type="hidden" name="x<?php echo $tools_grid->RowIndex ?>_type" id="x<?php echo $tools_grid->RowIndex ?>_type" value="<?php echo ew_HtmlEncode($tools->type->FormValue) ?>" />
<?php } ?>
<input type="hidden" name="o<?php echo $tools_grid->RowIndex ?>_type" id="o<?php echo $tools_grid->RowIndex ?>_type" value="<?php echo ew_HtmlEncode($tools->type->OldValue) ?>" />
</span></td>
	<?php } ?>
	<?php if ($tools->status->Visible) { // status ?>
		<td><span2 id="el$rowindex$_tools_status" class="tools_status">
<?php if ($tools->CurrentAction <> "F") { ?>
<div id="tp_x<?php echo $tools_grid->RowIndex ?>_status" class="<?php echo EW_ITEM_TEMPLATE_CLASSNAME; ?>"><input type="checkbox" name="x<?php echo $tools_grid->RowIndex ?>_status[]" id="x<?php echo $tools_grid->RowIndex ?>_status[]" value="{value}"<?php echo $tools->status->EditAttributes() ?> /></div>
<div id="dsl_x<?php echo $tools_grid->RowIndex ?>_status" data-repeatcolumn="5" class="ewItemList">
<?php
$arwrk = $tools->status->EditValue;
if (is_array($arwrk)) {
	$armultiwrk= explode(",", strval($tools->status->CurrentValue));
	$rowswrk = count($arwrk);
	$emptywrk = TRUE;
	for ($rowcntwrk = 0; $rowcntwrk < $rowswrk; $rowcntwrk++) {
		$selwrk = "";
		$cnt = count($armultiwrk);
		for ($ari = 0; $ari < $cnt; $ari++) {
			if (strval($arwrk[$rowcntwrk][0]) == trim(strval($armultiwrk[$ari]))) {
				$selwrk = " checked=\"checked\"";
				if ($selwrk <> "") $emptywrk = FALSE;
				break;
			}
		}

		// Note: No spacing within the LABEL tag
?>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 1) ?>
<label><input type="checkbox" name="x<?php echo $tools_grid->RowIndex ?>_status[]" id="x<?php echo $tools_grid->RowIndex ?>_status[]" value="<?php echo ew_HtmlEncode($arwrk[$rowcntwrk][0]) ?>"<?php echo $selwrk ?><?php echo $tools->status->EditAttributes() ?> /><?php echo $arwrk[$rowcntwrk][1] ?></label>
<?php echo ew_RepeatColumnTable($rowswrk, $rowcntwrk, 5, 2) ?>
<?php
	}
}
if (@$emptywrk) $tools->status->OldValue = "";
?>
</div>
<?php } else { ?>
<span<?php echo $tools->status->ViewAttributes() ?>>
<?php echo $tools->status->ViewValue ?></span>
<input type="hidden" name="x<?php echo $tools_grid->RowIndex ?>_status" id="x<?php echo $tools_grid->RowIndex ?>_status" value="<?php echo ew_HtmlEncode($tools->status->FormValue) ?>" />
<?php } ?>
<input type="hidden" name="o<?php echo $tools_grid->RowIndex ?>_status[]" id="o<?php echo $tools_grid->RowIndex ?>_status[]" value="<?php echo ew_HtmlEncode($tools->status->OldValue) ?>" />
</span></td>
	<?php } ?>
	<?php if ($tools->Descripcion->Visible) { // Descripcion ?>
		<td><span2 id="el$rowindex$_tools_Descripcion" class="tools_Descripcion">
<?php if ($tools->CurrentAction <> "F") { ?>
<input type="text" name="x<?php echo $tools_grid->RowIndex ?>_Descripcion" id="x<?php echo $tools_grid->RowIndex ?>_Descripcion" size="30" maxlength="245" value="<?php echo $tools->Descripcion->EditValue ?>"<?php echo $tools->Descripcion->EditAttributes() ?> />
<?php } else { ?>
<span<?php echo $tools->Descripcion->ViewAttributes() ?>>
<?php echo $tools->Descripcion->ViewValue ?></span>
<input type="hidden" name="x<?php echo $tools_grid->RowIndex ?>_Descripcion" id="x<?php echo $tools_grid->RowIndex ?>_Descripcion" value="<?php echo ew_HtmlEncode($tools->Descripcion->FormValue) ?>" />
<?php } ?>
<input type="hidden" name="o<?php echo $tools_grid->RowIndex ?>_Descripcion" id="o<?php echo $tools_grid->RowIndex ?>_Descripcion" value="<?php echo ew_HtmlEncode($tools->Descripcion->OldValue) ?>" />
</span></td>
	<?php } ?>
	<?php if ($tools->tags->Visible) { // tags ?>
		<td><span2 id="el$rowindex$_tools_tags" class="tools_tags">
<?php if ($tools->CurrentAction <> "F") { ?>
<input type="text" name="x<?php echo $tools_grid->RowIndex ?>_tags" id="x<?php echo $tools_grid->RowIndex ?>_tags" size="30" maxlength="245" value="<?php echo $tools->tags->EditValue ?>"<?php echo $tools->tags->EditAttributes() ?> />
<?php } else { ?>
<span<?php echo $tools->tags->ViewAttributes() ?>>
<?php echo $tools->tags->ViewValue ?></span>
<input type="hidden" name="x<?php echo $tools_grid->RowIndex ?>_tags" id="x<?php echo $tools_grid->RowIndex ?>_tags" value="<?php echo ew_HtmlEncode($tools->tags->FormValue) ?>" />
<?php } ?>
<input type="hidden" name="o<?php echo $tools_grid->RowIndex ?>_tags" id="o<?php echo $tools_grid->RowIndex ?>_tags" value="<?php echo ew_HtmlEncode($tools->tags->OldValue) ?>" />
</span></td>
	<?php } ?>
<?php

// Render list options (body, right)
$tools_grid->ListOptions->Render("body", "right", $tools_grid->RowCnt);
?>
<script type="text/javascript">
ftoolsgrid.UpdateOpts(<?php echo $tools_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
<!--</table>-->
<?php if ($tools->CurrentMode == "add" || $tools->CurrentMode == "copy") { ?>
<input class="btn btn-large btn-success" type="submit" />
<input type="hidden" name="a_list" id="a_list" value="gridinsert" />
<input type="hidden" name="key_count" id="key_count" value="<?php echo $tools_grid->KeyCount ?>" />
<?php echo $tools_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($tools->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="key_count" id="key_count" value="<?php echo $tools_grid->KeyCount ?>" />
<?php echo $tools_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($tools->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" id="detailpage" value="ftoolsgrid">
<?php

// Close recordset
if ($tools_grid->Recordset)
	$tools_grid->Recordset->Close();
?>
<?php if (($tools->CurrentMode == "add" || $tools->CurrentMode == "copy" || $tools->CurrentMode == "edit") && $tools->CurrentAction != "F") { // add/copy/edit mode ?>
<div class="ewGridLowerPanel">
<?php if ($tools->AllowAddDeleteRow) { ?>
<?php if ($Security->CanAdd()) { ?>
<span class="phpmaker">
<a href="javascript:void(0);" onclick="ew_AddGridRow(this);"><?php echo $Language->Phrase("AddBlankRow") ?></a>
</span>
<?php } ?>
<?php } ?>
</div>
<?php } ?>
</div>
<?php if ($tools->Export == "") { ?>
<script type="text/javascript">
ftoolsgrid.Init();
</script>
<?php } ?>
<?php
$tools_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$tools_grid->Page_Terminate();
$Page = &$MasterPage;
?>
