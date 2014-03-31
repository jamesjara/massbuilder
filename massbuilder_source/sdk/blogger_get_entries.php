<?php
	$LOG_ID = uniqid();
	ms_log( '1. blogger_get_entries' , $LOG_ID , false  );
	
	//pre requsitos
	$var1 		=  (int)$_GET['id'	];
	$service 	=  'blogger';
	
	//obtener el dominio 
	ms_log( '2. blogger_get_entries - obteniendo dominio'.$var1 , $LOG_ID , false  );
	$result =  multiscalar(  'domains', 'map,bid', " id_domains = '".$var1."';");
	$bid	=	$result['bid'];
	$map	=	$result['map'];
	
	//Obtener mapeo del dominio con los credenciales
	ms_log( '3. blogger_get_entries - obteniendo credenciales blogger para el dominio '.$var1 , $LOG_ID , false  );
	$result =  multiscalar(  'mapings', 'username,password', " idmapings = '".$map."';");
	$username 	=  $result['username'];
	$password 	=  $result['password'];
	
	//Descargar feed
	include('blogger_api.php');
	$blog_entries = getBlogEntries( $bid );
	
	$omitidas = 0;
	$actualizados = 0;
	$nuevas = 0;
	foreach ( $blog_entries as $entry) {

		$entry_id = $entry['id'] ;
		$entry['domain_id'] 	=	$var1 ;
		$entry['hash_content'] 	=	strlen(serialize($entry)); //get size array
	
		//Insertar on DB.
		if ( check_post_exists(  'entries', $entry_id ) ) {
			$r_log .= ' post existe con id '.$entry_id.',';
			//Si existe pero no tiene el mismo hash debe actualizar, de lo contrario ya existe en la db.
			if ( !check_post_exists_byhash(  'entries', $entry_id, $entry['hash_content'] )  ) {
				$hash_viejo =  scalar(  'entries', 'hash_content', " id = '".$entry_id."';");
				
				update_values_byhash( 'entries', $entry_id , $hash_viejo , $entry  );	
				$count_u++;		
				$r_log .= ' post existe con hash diferente, se actualiza el post '.$entry_id;	
				$actualizados++;			
			} else {
			//ya existe.
				$r_log .= ' post existe id y hash unico, se omite '.$entry_id;
				$omitidas++;
			}
			$count_e++;
		} else{ //es nuevo
		//var_dump();
			insert_values( 'entries',  $entry );	
			$r_log .= 'post no existe, se agrega '.$entry_id;
			$nuevas++;
		}
	}
	ms_log( '4. blogger_get_entries - '.$omitidas.' entradas omitidos, '.$actualizados.' entradas actualizadas, '.$nuevas.' entradas agregadas '.$r_log , $LOG_ID , true  , LLOG_INFO );
	ms_log( '5. blogger_get_entries - todo procesado ' , $LOG_ID , false  );

	
