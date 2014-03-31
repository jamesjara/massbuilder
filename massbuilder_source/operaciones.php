<?php
set_include_path(
	'sdk/apis/librerias/' . PATH_SEPARATOR . get_include_path()
);

include('sdk/comoon.php');
include('sdk/apis/librerias/logger.php');


switch ( strtolower( $_GET['tipo'] )  ){

/*
* PARA BLOGGER - START
*/
	//Por dominio entero
	case "blogger_backup":
		include ('sdk/blogger_backup.php');		
	break;
	//para dominio entero
	case "blogger_translate":
		include ('sdk/blogger_translate.php');	
	break;
	//para dominio entero
	case "blogger_refresh_entries":
		include ('sdk/blogger_get_entries.php');			
	break;
/*
* PARA BLOGGER - END
*/
		
/*
* PARA FACEBOOK - START
*/
	case "facebook_get_entries":
		include ('sdk/facebook_get_entries.php');			
	break;
/*
* PARA FACEBOOK - END
*/
	
	
/*
* PARA ENTRADAS - START
*/
	case "opt_tidy":
		$data =  $_POST['data'];
		include ('sdk/opt_tidy.php');
	break;
/*
* PARA ENTRADAS - END
*/

	// por entradas
	case "ppt":
		$data =  $_GET['id'	];
	break;
	case "pdf":
		$data =  $_GET['id'	];
	break;
	case "mp4":
		$data =  $_GET['id'	];
	break;
	case "imagen":
		$data =  $_GET['id'	];
	break;
	default:
	break;
}

echo '<div id="actionbox" class="well"><a href="#" onclick="$(\'#dialog\').hide()">close</a>'.$data.'</div>';
?>
