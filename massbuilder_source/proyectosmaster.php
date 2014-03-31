<?php

// NOMBRE
?>
<?php if ($proyectos->Visible) { ?>
<small>
<table id="tbl_proyectosmaster" class="ewTable ewTableSeparate table table-striped table-bordered ">
	<tbody>
<?php if ($proyectos->NOMBRE->Visible) { // NOMBRE ?>
		<tr id="r_NOMBRE">
			<td class="ewTableHeader"><b><?php echo $proyectos->NOMBRE->FldCaption() ?></b></td>
			<td<?php echo $proyectos->NOMBRE->CellAttributes() ?>><span id="el_proyectos_NOMBRE">
<span<?php echo $proyectos->NOMBRE->ViewAttributes() ?>>
<?php echo $proyectos->NOMBRE->ListViewValue() ?></span>
</span></td>
		</tr>
<?php } ?>
	</tbody>
</table></small>
<?php } ?>
