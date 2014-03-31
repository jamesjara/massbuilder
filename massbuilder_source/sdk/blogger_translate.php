<?php
	include_once 'simplepie-simplepie-e9472a1/autoloader.php';
	include_once 'simplepie-simplepie-e9472a1/idn/idna_convert.class.php';
	include_once 'translator_api.php';

	//pre requsitos
	$var1 		=  (int)$_GET['id'	];
	$service 	=  'blogger';
	
	//obtener el dominio 
	$result =  multiscalar(  'domains', 'map,bid', " id_domains = '".$var1."';");
	$bid	=	$result['bid'];
	$map	=	$result['map'];
	
	//Obtener mapeo del dominio con los credenciales
	$result =  multiscalar(  'mapings', 'username,password', " idmapings = '".$map."';");
	$username 	=  $result['username'];
	$password 	=  $result['password'];
	
	logg('procesando sitio '.$bid);
	
	include('blogger_api.php');
		
	//Todos los sitios pendientes 
	$result =  multiscalar2(  'tools_translation', ' * ', " domain_id = '".$var1."' and Status is null ;");
	foreach( $result as $domain){
		logg('procesando sitio de traduccion : '.$bid);
		$domain_id	=	$domain['domain_id'];
		$tolanguage	=	$domain['lenguaje'];
		$to_domain	=	$domain['to_domain'];

		//Obtener el bid del blog destino
		$result =  multiscalar(  'domains', 'map,bid,language', " id_domains = '".$to_domain."';");
		$bid			=	$result['bid'];
		$fromlanguage	=	$result['language'];
		
		//obtener  todos los feed , traducir y push. - solo los que no tiene ya traducido la entrada
		$result =  multiscalar2(  'entries', ' * ', " domain_id = '".$domain_id."' and (trans_".$tolanguage." is null or trans_".$tolanguage." = '' );");
		$new_data = array();
		foreach( $result as $data){
			
			$id_entry	= $data['identries'];
			$titulo 	= translate($data['titulo'] 	, $fromlanguage ,  $tolanguage ).' '; 
			$contenido 	= translate($data['contenido'] 	, $fromlanguage ,  $tolanguage ).' '; 
			$categories = translate($data['categorias'] , $fromlanguage ,  $tolanguage ).' '; 
						
			logg('procesando y traduciendo entrada del sitio : '.$id_entry);
			
			//Push data traducida
			$nsid = createPublishedPost($bid, $titulo, $contenido ,$categories) ;
			if ( $nsid != null){
				//When is pushed add id to trasnlated field.
				sql(  ' update entries set  trans_'.$tolanguage.' = "'.$nsid.'" where  identries = "'.$id_entry.'" '  );
				//sql(  ' update tools_translation set  trans_'.$tolanguage.' = "'.$nsid.'" where  identries = "'.$id_entry.'" '  );
			}
			die();
		}
	}


