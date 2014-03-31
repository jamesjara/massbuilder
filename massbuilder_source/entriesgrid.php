<?php include_once "usersinfo.php" ?>
<?php

// Create page object
if (!isset($entries_grid)) $entries_grid = new centries_grid();

// Page init
$entries_grid->Page_Init();

// Page main
$entries_grid->Page_Main();
?>
<?php if ($entries->Export == "") { ?>
<script type="text/javascript">

// Page object
var entries_grid = new ew_Page("entries_grid");
entries_grid.PageID = "grid"; // Page ID
var EW_PAGE_ID = entries_grid.PageID; // For backward compatibility

// Form object
var fentriesgrid = new ew_Form("fentriesgrid");

// Validate form
fentriesgrid.Validate = function(fobj) {
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
		elm = fobj.elements["x" + infix + "_id"];
		if (elm && !ew_HasValue(elm))
			return ew_OnError(this, elm, ewLanguage.Phrase("EnterRequiredField") + " - <?php echo ew_JsEncode2($entries->id->FldCaption()) ?>");
		elm = fobj.elements["x" + infix + "_id"];
		if (elm && !ew_CheckInteger(elm.value))
			return ew_OnError(this, elm, "<?php echo ew_JsEncode2($entries->id->FldErrMsg()) ?>");

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
fentriesgrid.EmptyRow = function(infix) {
	var fobj = this.Form;
	if (ew_ValueChanged(fobj, infix, "titulo", false)) return false;
	if (ew_ValueChanged(fobj, infix, "id", false)) return false;
	if (ew_ValueChanged(fobj, infix, "islive", false)) return false;
	if (ew_ValueChanged(fobj, infix, "tool_id", false)) return false;
	return true;
}

// Form_CustomValidate event
fentriesgrid.Form_CustomValidate =  
 function(fobj) { // DO NOT CHANGE THIS LINE!

 	// Your custom validation code here, return false if invalid. 
 	return true;
 }

// Use JavaScript validation or not
<?php if (EW_CLIENT_VALIDATE) { ?>
fentriesgrid.ValidateRequired = true;
<?php } else { ?>
fentriesgrid.ValidateRequired = false; 
<?php } ?>

// Dynamic selection lists
// Form object for search

</script>
<?php } ?>
<?php
if ($entries->CurrentAction == "gridadd") {
	if ($entries->CurrentMode == "copy") {
		$bSelectLimit = EW_SELECT_LIMIT;
		if ($bSelectLimit) {
			$entries_grid->TotalRecs = $entries->SelectRecordCount();
			$entries_grid->Recordset = $entries_grid->LoadRecordset($entries_grid->StartRec-1, $entries_grid->DisplayRecs);
		} else {
			if ($entries_grid->Recordset = $entries_grid->LoadRecordset())
				$entries_grid->TotalRecs = $entries_grid->Recordset->RecordCount();
		}
		$entries_grid->StartRec = 1;
		$entries_grid->DisplayRecs = $entries_grid->TotalRecs;
	} else {
		$entries->CurrentFilter = "0=1";
		$entries_grid->StartRec = 1;
		$entries_grid->DisplayRecs = $entries->GridAddRowCount;
	}
	$entries_grid->TotalRecs = $entries_grid->DisplayRecs;
	$entries_grid->StopRec = $entries_grid->DisplayRecs;
} else {
	$bSelectLimit = EW_SELECT_LIMIT;
	if ($bSelectLimit) {
		$entries_grid->TotalRecs = $entries->SelectRecordCount();
	} else {
		if ($entries_grid->Recordset = $entries_grid->LoadRecordset())
			$entries_grid->TotalRecs = $entries_grid->Recordset->RecordCount();
	}
	$entries_grid->StartRec = 1;
	$entries_grid->DisplayRecs = $entries_grid->TotalRecs; // Display all records
	if ($bSelectLimit)
		$entries_grid->Recordset = $entries_grid->LoadRecordset($entries_grid->StartRec-1, $entries_grid->DisplayRecs);
}
?>
<p style="white-spaceJAMES: nowrapJAMES;"><h4 id="ewPageCaption" class="page-header ewTitle ewTableTitle"> <?php if ($entries->CurrentMode == "add" || $entries->CurrentMode == "copy") { ?><?php echo $Language->Phrase("Add") ?><?php } elseif ($entries->CurrentMode == "edit") { ?><?php echo $Language->Phrase("Edit") ?><?php } ?>&nbsp;<?php echo $Language->Phrase("TblTypeTABLE") ?><?php echo $entries->TableCaption() ?></h4></p>
</p>
<?php $entries_grid->ShowPageHeader(); ?>
<?php
$entries_grid->ShowMessage();
?>
<div id="fentriesgrid" class="ewForm">
<?php if (($entries->CurrentMode == "add" || $entries->CurrentMode == "copy" || $entries->CurrentMode == "edit") && $entries->CurrentAction != "F") { // add/copy/edit mode ?>
<div class="ewGridUpperPanel">
<?php if ($entries->AllowAddDeleteRow) { ?>
<?php if ($Security->CanAdd()) { ?>
<span class="phpmaker">
<a href="javascript:void(0);" onclick="ew_AddGridRow(this);"><?php echo $Language->Phrase("AddBlankRow") ?></a>
</span>
<?php } ?>
<?php } ?>
</div>
<?php } ?>
<div id="gmp_entries" class="ewGridMiddlePanel row-fluid">
<ul class="thumbnails">
<table id="tbl_entriesgrid" class="ewTable ewTableSeparate table table-striped table-bordered ">
<?php echo $entries->TableCustomInnerHtml ?>
<thead><!-- Table header -->
	<tr class="ewTableHeader">
<?php

// Render list options
$entries_grid->RenderListOptions();

// Render list options (header, left)
$entries_grid->ListOptions->Render("header", "left");
?>
<?php if ($entries->identries->Visible) { // identries ?>
	<?php if ($entries->SortUrl($entries->identries) == "") { ?>
		<th><span id="elh_entries_identries" class="entries_identries">
		<div class="ewTableHeaderBtn"><?php echo $entries->identries->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div><span id="elh_entries_identries" class="entries_identries">
			<div class="ewTableHeaderBtn">			
			<?php echo $entries->identries->FldCaption() ?>
			<?php if ($entries->identries->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($entries->identries->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($entries->titulo->Visible) { // titulo ?>
	<?php if ($entries->SortUrl($entries->titulo) == "") { ?>
		<th><span id="elh_entries_titulo" class="entries_titulo">
		<div class="ewTableHeaderBtn"><?php echo $entries->titulo->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div><span id="elh_entries_titulo" class="entries_titulo">
			<div class="ewTableHeaderBtn">			
			<?php echo $entries->titulo->FldCaption() ?>
			<?php if ($entries->titulo->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($entries->titulo->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($entries->id->Visible) { // id ?>
	<?php if ($entries->SortUrl($entries->id) == "") { ?>
		<th><span id="elh_entries_id" class="entries_id">
		<div class="ewTableHeaderBtn"><?php echo $entries->id->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div><span id="elh_entries_id" class="entries_id">
			<div class="ewTableHeaderBtn">			
			<?php echo $entries->id->FldCaption() ?>
			<?php if ($entries->id->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($entries->id->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($entries->islive->Visible) { // islive ?>
	<?php if ($entries->SortUrl($entries->islive) == "") { ?>
		<th><span id="elh_entries_islive" class="entries_islive">
		<div class="ewTableHeaderBtn"><?php echo $entries->islive->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div><span id="elh_entries_islive" class="entries_islive">
			<div class="ewTableHeaderBtn">			
			<?php echo $entries->islive->FldCaption() ?>
			<?php if ($entries->islive->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($entries->islive->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php if ($entries->tool_id->Visible) { // tool_id ?>
	<?php if ($entries->SortUrl($entries->tool_id) == "") { ?>
		<th><span id="elh_entries_tool_id" class="entries_tool_id">
		<div class="ewTableHeaderBtn"><?php echo $entries->tool_id->FldCaption() ?></div>		
		</span></th>
	<?php } else { ?>
		<th><div><span id="elh_entries_tool_id" class="entries_tool_id">
			<div class="ewTableHeaderBtn">			
			<?php echo $entries->tool_id->FldCaption() ?>
			<?php if ($entries->tool_id->getSort() == "ASC") { ?><img src="phpimages/sortup.gif" width="10" height="9" alt="" style="border: 0;" /><?php } elseif ($entries->tool_id->getSort() == "DESC") { ?><img src="phpimages/sortdown.gif" width="10" height="9" alt="" style="border: 0;" /><?php } ?>
			</div>
		</span></div></th>		
	<?php } ?>
<?php } ?>		
<?php

// Render list options (header, right)
$entries_grid->ListOptions->Render("header", "right");
?>
	</tr>
</thead>
<tbody>
<?php
$entries_grid->StartRec = 1;
$entries_grid->StopRec = $entries_grid->TotalRecs; // Show all records

// Restore number of post back records
if ($objForm) {
	$objForm->Index = -1;
	if ($objForm->HasValue("key_count") && ($entries->CurrentAction == "gridadd" || $entries->CurrentAction == "gridedit" || $entries->CurrentAction == "F")) {
		$entries_grid->KeyCount = $objForm->GetValue("key_count");
		$entries_grid->StopRec = $entries_grid->KeyCount;
	}
}
$entries_grid->RecCnt = $entries_grid->StartRec - 1;
if ($entries_grid->Recordset && !$entries_grid->Recordset->EOF) {
	$entries_grid->Recordset->MoveFirst();
	if (!$bSelectLimit && $entries_grid->StartRec > 1)
		$entries_grid->Recordset->Move($entries_grid->StartRec - 1);
} elseif (!$entries->AllowAddDeleteRow && $entries_grid->StopRec == 0) {
	$entries_grid->StopRec = $entries->GridAddRowCount;
}

// Initialize aggregate
$entries->RowType = EW_ROWTYPE_AGGREGATEINIT;
$entries->ResetAttrs();
$entries_grid->RenderRow();
if ($entries->CurrentAction == "gridadd")
	$entries_grid->RowIndex = 0;
if ($entries->CurrentAction == "gridedit")
	$entries_grid->RowIndex = 0;
while ($entries_grid->RecCnt < $entries_grid->StopRec) {
	$entries_grid->RecCnt++;
	if (intval($entries_grid->RecCnt) >= intval($entries_grid->StartRec)) {
		$entries_grid->RowCnt++;
		if ($entries->CurrentAction == "gridadd" || $entries->CurrentAction == "gridedit" || $entries->CurrentAction == "F") {
			$entries_grid->RowIndex++;
			$objForm->Index = $entries_grid->RowIndex;
			if ($objForm->HasValue("k_action"))
				$entries_grid->RowAction = strval($objForm->GetValue("k_action"));
			elseif ($entries->CurrentAction == "gridadd")
				$entries_grid->RowAction = "insert";
			else
				$entries_grid->RowAction = "";
		}

		// Set up key count
		$entries_grid->KeyCount = $entries_grid->RowIndex;

		// Init row class and style
		$entries->ResetAttrs();
		$entries->CssClass = "";
		if ($entries->CurrentAction == "gridadd") {
			if ($entries->CurrentMode == "copy") {
				$entries_grid->LoadRowValues($entries_grid->Recordset); // Load row values
				$entries_grid->SetRecordKey($entries_grid->RowOldKey, $entries_grid->Recordset); // Set old record key
			} else {
				$entries_grid->LoadDefaultValues(); // Load default values
				$entries_grid->RowOldKey = ""; // Clear old key value
			}
		} elseif ($entries->CurrentAction == "gridedit") {
			$entries_grid->LoadRowValues($entries_grid->Recordset); // Load row values
		}
		$entries->RowType = EW_ROWTYPE_VIEW; // Render view
		if ($entries->CurrentAction == "gridadd") // Grid add
			$entries->RowType = EW_ROWTYPE_ADD; // Render add
		if ($entries->CurrentAction == "gridadd" && $entries->EventCancelled && !$objForm->HasValue("k_blankrow")) // Insert failed
			$entries_grid->RestoreCurrentRowFormValues($entries_grid->RowIndex); // Restore form values
		if ($entries->CurrentAction == "gridedit") { // Grid edit
			if ($entries->EventCancelled) {
				$entries_grid->RestoreCurrentRowFormValues($entries_grid->RowIndex); // Restore form values
			}
			if ($entries_grid->RowAction == "insert")
				$entries->RowType = EW_ROWTYPE_ADD; // Render add
			else
				$entries->RowType = EW_ROWTYPE_EDIT; // Render edit
		}
		if ($entries->CurrentAction == "gridedit" && ($entries->RowType == EW_ROWTYPE_EDIT || $entries->RowType == EW_ROWTYPE_ADD) && $entries->EventCancelled) // Update failed
			$entries_grid->RestoreCurrentRowFormValues($entries_grid->RowIndex); // Restore form values
		if ($entries->RowType == EW_ROWTYPE_EDIT) // Edit row
			$entries_grid->EditRowCnt++;
		if ($entries->CurrentAction == "F") // Confirm row
			$entries_grid->RestoreCurrentRowFormValues($entries_grid->RowIndex); // Restore form values

		// Set up row id / data-rowindex
		$entries->RowAttrs = array_merge($entries->RowAttrs, array('data-rowindex'=>$entries_grid->RowCnt, 'id'=>'r' . $entries_grid->RowCnt . '_entries', 'data-rowtype'=>$entries->RowType));

		// Render row
		$entries_grid->RenderRow();

		// Render list options
		$entries_grid->RenderListOptions();

		// Skip delete row / empty row for confirm page
		if ($entries_grid->RowAction <> "delete" && $entries_grid->RowAction <> "insertdelete" && !($entries_grid->RowAction == "insert" && $entries->CurrentAction == "F" && $entries_grid->EmptyRow())) {
?>
	<tr<?php echo $entries->RowAttributes() ?>>
<?php

// Render list options (body, left)
$entries_grid->ListOptions->Render("body", "left", $entries_grid->RowCnt);
?>
	<?php if ($entries->identries->Visible) { // identries ?>
		<td<?php echo $entries->identries->CellAttributes() ?>><span id="el<?php echo $entries_grid->RowCnt ?>_entries_identries" class="entries_identries">
<?php if ($entries->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="hidden" name="o<?php echo $entries_grid->RowIndex ?>_identries" id="o<?php echo $entries_grid->RowIndex ?>_identries" value="<?php echo ew_HtmlEncode($entries->identries->OldValue) ?>" />
<?php } ?>
<?php if ($entries->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<div id="orig_entries_identries" class="ewDisplayNone">
<span<?php echo $entries->identries->ViewAttributes() ?>>
<?php echo $entries->identries->EditValue ?></span>
</div>
<?php    
       
if( BASENAME($_SERVER['PHP_SELF']) == 'entrieslist.php' ){   // solo si es entrieslist.php
   $data  = '<a class="operacion btn btn-success" name="ppt" id="'.CurrentTable()->identries->CurrentValue.'" href="#">ppt</a> ';  
   $data .= '<a class="operacion btn btn-primary" name="pdf" id="'.CurrentTable()->identries->CurrentValue.'" href="#">pdf</a> ';  
   $data .= '<a class="operacion btn btn-primary" name="mp4" id="'.CurrentTable()->identries->CurrentValue.'" href="#">mp4</a> ';    
   $data .= '<a class="operacion btn btn-primary" name="imagen" id="'.CurrentTable()->identries->CurrentValue.'" href="#">imagen</a> ';    
   echo $data;                                                                                                          
} else    {
   //$data  = '<a class="operacion btn btn-success" name="BackUp"    id="'.$_GET['id_domains'].'" href="#">BackUp</a> ';  
   //$data .= '<a class="operacion btn btn-primary" name="Traducion" id="'.$_GET['id_domains'].'" href="#">Traducion</a>';    
   //echo $data;          
} 
?>                  
<?php if (debug01) echo CurrentTable()->identries->CurrentValue;   ?>   
<script>   
/*  NO SE VA A USAR
Todo : mostrar solo si esta con showmaster
var ewpagerform  =  $("#ewpagerformTOOLS");
if ($("#ewpagerformTOOLS").length == 0) {        
        tools = '<a class="operacion btn btn-success" name="push_entries" id="<?php CurrentTable()->id_domains->CurrentValue ?>" href="#">Actualizar Blog</a>';
        tools += ' <a class="operacion btn btn-success" name="get_entries" id="<?php CurrentTable()->id_domains->CurrentValue ?>" href="#">Obtener Nuevas</a>';
        <?php          
        if( isset($_GET['showmaster']) ){
            echo " ewpagerformTOOLS = $('<div id=\"ewpagerformTOOLS\">'+tools+'</div>').appendTo('#ewpagerform');"; 
            }
        ?>        
}         
*/                  
</script>
<input type="hidden" name="x<?php echo $entries_grid->RowIndex ?>_identries" id="x<?php echo $entries_grid->RowIndex ?>_identries" value="<?php echo ew_HtmlEncode($entries->identries->CurrentValue) ?>" />
<?php } ?>
<?php if ($entries->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<div id="orig_entries_identries" class="ewDisplayNone">
<span<?php echo $entries->identries->ViewAttributes() ?>>
<?php echo $entries->identries->ListViewValue() ?></span>
</div>
<?php    
       
if( BASENAME($_SERVER['PHP_SELF']) == 'entrieslist.php' ){   // solo si es entrieslist.php
   $data  = '<a class="operacion btn btn-success" name="ppt" id="'.CurrentTable()->identries->CurrentValue.'" href="#">ppt</a> ';  
   $data .= '<a class="operacion btn btn-primary" name="pdf" id="'.CurrentTable()->identries->CurrentValue.'" href="#">pdf</a> ';  
   $data .= '<a class="operacion btn btn-primary" name="mp4" id="'.CurrentTable()->identries->CurrentValue.'" href="#">mp4</a> ';    
   $data .= '<a class="operacion btn btn-primary" name="imagen" id="'.CurrentTable()->identries->CurrentValue.'" href="#">imagen</a> ';    
   echo $data;                                                                                                          
} else    {
   //$data  = '<a class="operacion btn btn-success" name="BackUp"    id="'.$_GET['id_domains'].'" href="#">BackUp</a> ';  
   //$data .= '<a class="operacion btn btn-primary" name="Traducion" id="'.$_GET['id_domains'].'" href="#">Traducion</a>';    
   //echo $data;          
} 
?>                  
<?php if (debug01) echo CurrentTable()->identries->CurrentValue;   ?>   
<script>   
/*  NO SE VA A USAR
Todo : mostrar solo si esta con showmaster
var ewpagerform  =  $("#ewpagerformTOOLS");
if ($("#ewpagerformTOOLS").length == 0) {        
        tools = '<a class="operacion btn btn-success" name="push_entries" id="<?php CurrentTable()->id_domains->CurrentValue ?>" href="#">Actualizar Blog</a>';
        tools += ' <a class="operacion btn btn-success" name="get_entries" id="<?php CurrentTable()->id_domains->CurrentValue ?>" href="#">Obtener Nuevas</a>';
        <?php          
        if( isset($_GET['showmaster']) ){
            echo " ewpagerformTOOLS = $('<div id=\"ewpagerformTOOLS\">'+tools+'</div>').appendTo('#ewpagerform');"; 
            }
        ?>        
}         
*/                  
</script>
<input type="hidden" name="x<?php echo $entries_grid->RowIndex ?>_identries" id="x<?php echo $entries_grid->RowIndex ?>_identries" value="<?php echo ew_HtmlEncode($entries->identries->FormValue) ?>" />
<input type="hidden" name="o<?php echo $entries_grid->RowIndex ?>_identries" id="o<?php echo $entries_grid->RowIndex ?>_identries" value="<?php echo ew_HtmlEncode($entries->identries->OldValue) ?>" />
<?php } ?>
</span></td>
	<?php } ?>
<a id="<?php echo $entries_grid->PageObjName . "_row_" . $entries_grid->RowCnt ?>"></a>
	<?php if ($entries->titulo->Visible) { // titulo ?>
		<td<?php echo $entries->titulo->CellAttributes() ?>><span id="el<?php echo $entries_grid->RowCnt ?>_entries_titulo" class="entries_titulo">
<?php if ($entries->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="text" name="x<?php echo $entries_grid->RowIndex ?>_titulo" id="x<?php echo $entries_grid->RowIndex ?>_titulo" size="30" maxlength="245" value="<?php echo $entries->titulo->EditValue ?>"<?php echo $entries->titulo->EditAttributes() ?> />
<input type="hidden" name="o<?php echo $entries_grid->RowIndex ?>_titulo" id="o<?php echo $entries_grid->RowIndex ?>_titulo" value="<?php echo ew_HtmlEncode($entries->titulo->OldValue) ?>" />
<?php } ?>
<?php if ($entries->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<input type="text" name="x<?php echo $entries_grid->RowIndex ?>_titulo" id="x<?php echo $entries_grid->RowIndex ?>_titulo" size="30" maxlength="245" value="<?php echo $entries->titulo->EditValue ?>"<?php echo $entries->titulo->EditAttributes() ?> />
<?php } ?>
<?php if ($entries->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $entries->titulo->ViewAttributes() ?>>
<?php echo $entries->titulo->ListViewValue() ?></span>
<input type="hidden" name="x<?php echo $entries_grid->RowIndex ?>_titulo" id="x<?php echo $entries_grid->RowIndex ?>_titulo" value="<?php echo ew_HtmlEncode($entries->titulo->FormValue) ?>" />
<input type="hidden" name="o<?php echo $entries_grid->RowIndex ?>_titulo" id="o<?php echo $entries_grid->RowIndex ?>_titulo" value="<?php echo ew_HtmlEncode($entries->titulo->OldValue) ?>" />
<?php } ?>
</span></td>
	<?php } ?>
	<?php if ($entries->id->Visible) { // id ?>
		<td<?php echo $entries->id->CellAttributes() ?>><span id="el<?php echo $entries_grid->RowCnt ?>_entries_id" class="entries_id">
<?php if ($entries->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="text" name="x<?php echo $entries_grid->RowIndex ?>_id" id="x<?php echo $entries_grid->RowIndex ?>_id" size="30" value="<?php echo $entries->id->EditValue ?>"<?php echo $entries->id->EditAttributes() ?> />
<input type="hidden" name="o<?php echo $entries_grid->RowIndex ?>_id" id="o<?php echo $entries_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($entries->id->OldValue) ?>" />
<?php } ?>
<?php if ($entries->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<span<?php echo $entries->id->ViewAttributes() ?>>
<?php echo $entries->id->EditValue ?></span>
<input type="hidden" name="x<?php echo $entries_grid->RowIndex ?>_id" id="x<?php echo $entries_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($entries->id->CurrentValue) ?>" />
<?php } ?>
<?php if ($entries->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $entries->id->ViewAttributes() ?>>
<?php echo $entries->id->ListViewValue() ?></span>
<input type="hidden" name="x<?php echo $entries_grid->RowIndex ?>_id" id="x<?php echo $entries_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($entries->id->FormValue) ?>" />
<input type="hidden" name="o<?php echo $entries_grid->RowIndex ?>_id" id="o<?php echo $entries_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($entries->id->OldValue) ?>" />
<?php } ?>
</span></td>
	<?php } ?>
	<?php if ($entries->islive->Visible) { // islive ?>
		<td<?php echo $entries->islive->CellAttributes() ?>><span id="el<?php echo $entries_grid->RowCnt ?>_entries_islive" class="entries_islive">
<?php if ($entries->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="text" name="x<?php echo $entries_grid->RowIndex ?>_islive" id="x<?php echo $entries_grid->RowIndex ?>_islive" size="30" maxlength="245" value="<?php echo $entries->islive->EditValue ?>"<?php echo $entries->islive->EditAttributes() ?> />
<input type="hidden" name="o<?php echo $entries_grid->RowIndex ?>_islive" id="o<?php echo $entries_grid->RowIndex ?>_islive" value="<?php echo ew_HtmlEncode($entries->islive->OldValue) ?>" />
<?php } ?>
<?php if ($entries->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<input type="text" name="x<?php echo $entries_grid->RowIndex ?>_islive" id="x<?php echo $entries_grid->RowIndex ?>_islive" size="30" maxlength="245" value="<?php echo $entries->islive->EditValue ?>"<?php echo $entries->islive->EditAttributes() ?> />
<?php } ?>
<?php if ($entries->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $entries->islive->ViewAttributes() ?>>
<?php echo $entries->islive->ListViewValue() ?></span>
<input type="hidden" name="x<?php echo $entries_grid->RowIndex ?>_islive" id="x<?php echo $entries_grid->RowIndex ?>_islive" value="<?php echo ew_HtmlEncode($entries->islive->FormValue) ?>" />
<input type="hidden" name="o<?php echo $entries_grid->RowIndex ?>_islive" id="o<?php echo $entries_grid->RowIndex ?>_islive" value="<?php echo ew_HtmlEncode($entries->islive->OldValue) ?>" />
<?php } ?>
</span></td>
	<?php } ?>
	<?php if ($entries->tool_id->Visible) { // tool_id ?>
		<td<?php echo $entries->tool_id->CellAttributes() ?>><span id="el<?php echo $entries_grid->RowCnt ?>_entries_tool_id" class="entries_tool_id">
<?php if ($entries->RowType == EW_ROWTYPE_ADD) { // Add record ?>
<input type="text" name="x<?php echo $entries_grid->RowIndex ?>_tool_id" id="x<?php echo $entries_grid->RowIndex ?>_tool_id" size="30" maxlength="235" value="<?php echo $entries->tool_id->EditValue ?>"<?php echo $entries->tool_id->EditAttributes() ?> />
<input type="hidden" name="o<?php echo $entries_grid->RowIndex ?>_tool_id" id="o<?php echo $entries_grid->RowIndex ?>_tool_id" value="<?php echo ew_HtmlEncode($entries->tool_id->OldValue) ?>" />
<?php } ?>
<?php if ($entries->RowType == EW_ROWTYPE_EDIT) { // Edit record ?>
<input type="text" name="x<?php echo $entries_grid->RowIndex ?>_tool_id" id="x<?php echo $entries_grid->RowIndex ?>_tool_id" size="30" maxlength="235" value="<?php echo $entries->tool_id->EditValue ?>"<?php echo $entries->tool_id->EditAttributes() ?> />
<?php } ?>
<?php if ($entries->RowType == EW_ROWTYPE_VIEW) { // View record ?>
<span<?php echo $entries->tool_id->ViewAttributes() ?>>
<?php echo $entries->tool_id->ListViewValue() ?></span>
<input type="hidden" name="x<?php echo $entries_grid->RowIndex ?>_tool_id" id="x<?php echo $entries_grid->RowIndex ?>_tool_id" value="<?php echo ew_HtmlEncode($entries->tool_id->FormValue) ?>" />
<input type="hidden" name="o<?php echo $entries_grid->RowIndex ?>_tool_id" id="o<?php echo $entries_grid->RowIndex ?>_tool_id" value="<?php echo ew_HtmlEncode($entries->tool_id->OldValue) ?>" />
<?php } ?>
</span></td>
	<?php } ?>
<?php

// Render list options (body, right)
$entries_grid->ListOptions->Render("body", "right", $entries_grid->RowCnt);
?>
	</tr>
<?php if ($entries->RowType == EW_ROWTYPE_ADD || $entries->RowType == EW_ROWTYPE_EDIT) { ?>
<script type="text/javascript">
fentriesgrid.UpdateOpts(<?php echo $entries_grid->RowIndex ?>);
</script>
<?php } ?>
<?php
	}
	} // End delete row checking
	if ($entries->CurrentAction <> "gridadd" || $entries->CurrentMode == "copy")
		if (!$entries_grid->Recordset->EOF) $entries_grid->Recordset->MoveNext();
}
?>
<?php
	if ($entries->CurrentMode == "add" || $entries->CurrentMode == "copy" || $entries->CurrentMode == "edit") {
		$entries_grid->RowIndex = '$rowindex$';
		$entries_grid->LoadDefaultValues();

		// Set row properties
		$entries->ResetAttrs();
		$entries->RowAttrs = array_merge($entries->RowAttrs, array('data-rowindex'=>$entries_grid->RowIndex, 'id'=>'r0_entries', 'data-rowtype'=>EW_ROWTYPE_ADD));
		ew_AppendClass($entries->RowAttrs["class"], "ewTemplate");
		$entries->RowType = EW_ROWTYPE_ADD;

		// Render row
		$entries_grid->RenderRow();

		// Render list options
		$entries_grid->RenderListOptions();
		$entries_grid->StartRowCnt = 0;
?>
	<tr<?php echo $entries->RowAttributes() ?>>
<?php

// Render list options (body, left)
$entries_grid->ListOptions->Render("body", "left", $entries_grid->RowIndex);
?>
	<?php if ($entries->identries->Visible) { // identries ?>
		<td><span2 id="el$rowindex$_entries_identries" class="entries_identries">
<?php if ($entries->CurrentAction <> "F") { ?>
<?php } else { ?>
<div id="orig_entries_identries" class="ewDisplayNone">
<span<?php echo $entries->identries->ViewAttributes() ?>>
<?php echo $entries->identries->ViewValue ?></span>
</div>
<?php    
       
if( BASENAME($_SERVER['PHP_SELF']) == 'entrieslist.php' ){   // solo si es entrieslist.php
   $data  = '<a class="operacion btn btn-success" name="ppt" id="'.CurrentTable()->identries->CurrentValue.'" href="#">ppt</a> ';  
   $data .= '<a class="operacion btn btn-primary" name="pdf" id="'.CurrentTable()->identries->CurrentValue.'" href="#">pdf</a> ';  
   $data .= '<a class="operacion btn btn-primary" name="mp4" id="'.CurrentTable()->identries->CurrentValue.'" href="#">mp4</a> ';    
   $data .= '<a class="operacion btn btn-primary" name="imagen" id="'.CurrentTable()->identries->CurrentValue.'" href="#">imagen</a> ';    
   echo $data;                                                                                                          
} else    {
   //$data  = '<a class="operacion btn btn-success" name="BackUp"    id="'.$_GET['id_domains'].'" href="#">BackUp</a> ';  
   //$data .= '<a class="operacion btn btn-primary" name="Traducion" id="'.$_GET['id_domains'].'" href="#">Traducion</a>';    
   //echo $data;          
} 
?>                  
<?php if (debug01) echo CurrentTable()->identries->CurrentValue;   ?>   
<script>   
/*  NO SE VA A USAR
Todo : mostrar solo si esta con showmaster
var ewpagerform  =  $("#ewpagerformTOOLS");
if ($("#ewpagerformTOOLS").length == 0) {        
        tools = '<a class="operacion btn btn-success" name="push_entries" id="<?php CurrentTable()->id_domains->CurrentValue ?>" href="#">Actualizar Blog</a>';
        tools += ' <a class="operacion btn btn-success" name="get_entries" id="<?php CurrentTable()->id_domains->CurrentValue ?>" href="#">Obtener Nuevas</a>';
        <?php          
        if( isset($_GET['showmaster']) ){
            echo " ewpagerformTOOLS = $('<div id=\"ewpagerformTOOLS\">'+tools+'</div>').appendTo('#ewpagerform');"; 
            }
        ?>        
}         
*/                  
</script>
<input type="hidden" name="x<?php echo $entries_grid->RowIndex ?>_identries" id="x<?php echo $entries_grid->RowIndex ?>_identries" value="<?php echo ew_HtmlEncode($entries->identries->FormValue) ?>" />
<?php } ?>
<input type="hidden" name="o<?php echo $entries_grid->RowIndex ?>_identries" id="o<?php echo $entries_grid->RowIndex ?>_identries" value="<?php echo ew_HtmlEncode($entries->identries->OldValue) ?>" />
</span></td>
	<?php } ?>
	<?php if ($entries->titulo->Visible) { // titulo ?>
		<td><span2 id="el$rowindex$_entries_titulo" class="entries_titulo">
<?php if ($entries->CurrentAction <> "F") { ?>
<input type="text" name="x<?php echo $entries_grid->RowIndex ?>_titulo" id="x<?php echo $entries_grid->RowIndex ?>_titulo" size="30" maxlength="245" value="<?php echo $entries->titulo->EditValue ?>"<?php echo $entries->titulo->EditAttributes() ?> />
<?php } else { ?>
<span<?php echo $entries->titulo->ViewAttributes() ?>>
<?php echo $entries->titulo->ViewValue ?></span>
<input type="hidden" name="x<?php echo $entries_grid->RowIndex ?>_titulo" id="x<?php echo $entries_grid->RowIndex ?>_titulo" value="<?php echo ew_HtmlEncode($entries->titulo->FormValue) ?>" />
<?php } ?>
<input type="hidden" name="o<?php echo $entries_grid->RowIndex ?>_titulo" id="o<?php echo $entries_grid->RowIndex ?>_titulo" value="<?php echo ew_HtmlEncode($entries->titulo->OldValue) ?>" />
</span></td>
	<?php } ?>
	<?php if ($entries->id->Visible) { // id ?>
		<td><span2 id="el$rowindex$_entries_id" class="entries_id">
<?php if ($entries->CurrentAction <> "F") { ?>
<input type="text" name="x<?php echo $entries_grid->RowIndex ?>_id" id="x<?php echo $entries_grid->RowIndex ?>_id" size="30" value="<?php echo $entries->id->EditValue ?>"<?php echo $entries->id->EditAttributes() ?> />
<?php } else { ?>
<span<?php echo $entries->id->ViewAttributes() ?>>
<?php echo $entries->id->ViewValue ?></span>
<input type="hidden" name="x<?php echo $entries_grid->RowIndex ?>_id" id="x<?php echo $entries_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($entries->id->FormValue) ?>" />
<?php } ?>
<input type="hidden" name="o<?php echo $entries_grid->RowIndex ?>_id" id="o<?php echo $entries_grid->RowIndex ?>_id" value="<?php echo ew_HtmlEncode($entries->id->OldValue) ?>" />
</span></td>
	<?php } ?>
	<?php if ($entries->islive->Visible) { // islive ?>
		<td><span2 id="el$rowindex$_entries_islive" class="entries_islive">
<?php if ($entries->CurrentAction <> "F") { ?>
<input type="text" name="x<?php echo $entries_grid->RowIndex ?>_islive" id="x<?php echo $entries_grid->RowIndex ?>_islive" size="30" maxlength="245" value="<?php echo $entries->islive->EditValue ?>"<?php echo $entries->islive->EditAttributes() ?> />
<?php } else { ?>
<span<?php echo $entries->islive->ViewAttributes() ?>>
<?php echo $entries->islive->ViewValue ?></span>
<input type="hidden" name="x<?php echo $entries_grid->RowIndex ?>_islive" id="x<?php echo $entries_grid->RowIndex ?>_islive" value="<?php echo ew_HtmlEncode($entries->islive->FormValue) ?>" />
<?php } ?>
<input type="hidden" name="o<?php echo $entries_grid->RowIndex ?>_islive" id="o<?php echo $entries_grid->RowIndex ?>_islive" value="<?php echo ew_HtmlEncode($entries->islive->OldValue) ?>" />
</span></td>
	<?php } ?>
	<?php if ($entries->tool_id->Visible) { // tool_id ?>
		<td><span2 id="el$rowindex$_entries_tool_id" class="entries_tool_id">
<?php if ($entries->CurrentAction <> "F") { ?>
<input type="text" name="x<?php echo $entries_grid->RowIndex ?>_tool_id" id="x<?php echo $entries_grid->RowIndex ?>_tool_id" size="30" maxlength="235" value="<?php echo $entries->tool_id->EditValue ?>"<?php echo $entries->tool_id->EditAttributes() ?> />
<?php } else { ?>
<span<?php echo $entries->tool_id->ViewAttributes() ?>>
<?php echo $entries->tool_id->ViewValue ?></span>
<input type="hidden" name="x<?php echo $entries_grid->RowIndex ?>_tool_id" id="x<?php echo $entries_grid->RowIndex ?>_tool_id" value="<?php echo ew_HtmlEncode($entries->tool_id->FormValue) ?>" />
<?php } ?>
<input type="hidden" name="o<?php echo $entries_grid->RowIndex ?>_tool_id" id="o<?php echo $entries_grid->RowIndex ?>_tool_id" value="<?php echo ew_HtmlEncode($entries->tool_id->OldValue) ?>" />
</span></td>
	<?php } ?>
<?php

// Render list options (body, right)
$entries_grid->ListOptions->Render("body", "right", $entries_grid->RowCnt);
?>
<script type="text/javascript">
fentriesgrid.UpdateOpts(<?php echo $entries_grid->RowIndex ?>);
</script>
	</tr>
<?php
}
?>
</tbody>
<!--</table>-->
<?php if ($entries->CurrentMode == "add" || $entries->CurrentMode == "copy") { ?>
<input class="btn btn-large btn-success" type="submit" />
<input type="hidden" name="a_list" id="a_list" value="gridinsert" />
<input type="hidden" name="key_count" id="key_count" value="<?php echo $entries_grid->KeyCount ?>" />
<?php echo $entries_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($entries->CurrentMode == "edit") { ?>
<input type="hidden" name="a_list" id="a_list" value="gridupdate">
<input type="hidden" name="key_count" id="key_count" value="<?php echo $entries_grid->KeyCount ?>" />
<?php echo $entries_grid->MultiSelectKey ?>
<?php } ?>
<?php if ($entries->CurrentMode == "") { ?>
<input type="hidden" name="a_list" id="a_list" value="">
<?php } ?>
<input type="hidden" name="detailpage" id="detailpage" value="fentriesgrid">
<?php

// Close recordset
if ($entries_grid->Recordset)
	$entries_grid->Recordset->Close();
?>
</div>
<?php if ($entries->Export == "") { ?>
<script type="text/javascript">
fentriesgrid.Init();
</script>
<?php } ?>
<?php
$entries_grid->ShowPageFooter();
if (EW_DEBUG_ENABLED)
	echo ew_DebugMsg();
?>
<?php
$entries_grid->Page_Terminate();
$Page = &$MasterPage;
?>
