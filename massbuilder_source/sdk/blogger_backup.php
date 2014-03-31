<?php
	//pre requsitos
	$var1 		=  (int)$_GET['id'	];
	$service 	=  'blogger';
	
	//obtener el dominio 
	$result =  multiscalar(  'domains', 'map,bid', " id_domains = '".$var1."';");
	$bid	=	$result['bid'];
	$map	=	$result['map'];
	
	//Obtener mapeo del dominio con los credenciales
	$result =  multiscalar(  'mapings', 'username,password', " idmapings = '".$var1."';");
	$username 	=  $result['username'];
	$password 	=  $result['password'];
	
	logg('procesando sitio '.$bid);

	//Descargar feed
	include('blogger_api.php');
	$blog_export = getBlogExport( $bid );
	$_fields['domain_id']	=	$var1;
	$_fields['data']		=	$blog_export;
	$_fields['date']		=	date("Y-m-d H:m:s", time());
	
	if (insert_values( 'tools_backups',  null , $_fields  )){
		$data = 'success';
	} else $data = 'error subiendo el backup';
	
	