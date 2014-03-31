<?php

// dominio
// id_domains
// hosted_in

?>
<?php if ($domains->Visible) { ?>
<small>
<table id="tbl_domainsmaster" class="ewTable ewTableSeparate table table-striped table-bordered ">
	<tbody>
<?php if ($domains->dominio->Visible) { // dominio ?>
		<tr id="r_dominio">
			<td class="ewTableHeader"><b><?php echo $domains->dominio->FldCaption() ?></b></td>
			<td<?php echo $domains->dominio->CellAttributes() ?>><span id="el_domains_dominio">
<span<?php echo $domains->dominio->ViewAttributes() ?>>
<?php echo $domains->dominio->ListViewValue() ?></span>
</span></td>
		</tr>
<?php } ?>
<?php if ($domains->id_domains->Visible) { // id_domains ?>
		<tr id="r_id_domains">
			<td class="ewTableHeader"><b><?php echo $domains->id_domains->FldCaption() ?></b></td>
			<td<?php echo $domains->id_domains->CellAttributes() ?>><span id="el_domains_id_domains">
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
</span></td>
		</tr>
<?php } ?>
<?php if ($domains->hosted_in->Visible) { // hosted_in ?>
		<tr id="r_hosted_in">
			<td class="ewTableHeader"><b><?php echo $domains->hosted_in->FldCaption() ?></b></td>
			<td<?php echo $domains->hosted_in->CellAttributes() ?>><span id="el_domains_hosted_in">
<span<?php echo $domains->hosted_in->ViewAttributes() ?>>
<?php echo $domains->hosted_in->ListViewValue() ?></span>
</span></td>
		</tr>
<?php } ?>
	</tbody>
</table></small>
<?php } ?>
