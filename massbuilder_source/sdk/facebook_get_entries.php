<?php
	$LOG_ID = uniqid();
	ms_log( '1. facebook_get_entries' , $LOG_ID , false  );
	
	//pre requsitos
	$var1 		=  (int)$_GET['id'];
	
	//obtener la Url 
	$result =  multiscalar(  'tools', ' * ', " idtools = '".$var1."';");
	$idtools=	$result['idtools'];
	$type	=	$result['type'];
	$status	=	$result['status'];
	$log	=	$result['log'];
	$time	=	$result['time'];
	$target_domain	=	$result['target_domain'];
	$url	=	$result['url'];
	$keywords	=	$result['tags'];
	$parent_domain	=	$result['parent_domain'];
	
	//Checkear si se puede correr.
	if ( $status === "1"){
		ms_log( '1.b facebook_get_entries - error , este JOB ya ha sido procesado ' , $LOG_ID  , true  , LLOG_INFO  );
		ms_log( '1.c facebook_get_entries - todo procesado ' , $LOG_ID , false  );
		sql( 'UPDATE tools SET status= 1, log = "'. $LOG_ID.'" WHERE  idtools = "'.$idtools.'" ;');
		//exit;
	}
	if ( $status === "2"){
		ms_log( '1.b facebook_get_entries - error , este JOB sigue en progreso, o ocurrio un error, en  '.
				' el script debera ', $LOG_ID  , true  , LLOG_INFO  );
		//En este caso se debe contar todos los feeds con el mismo job id en la tabla de fb entries group
		//y comparar contra los posts en entries, si no son el mismo entonces hay entries faltantes.
		//exit;
	}
	sql( 'UPDATE tools SET status= 2, log = "'. $LOG_ID.'" WHERE  idtools = "'.$idtools.'" ;');
	
	//obtener el dominio 
	$result =  multiscalar(  'domains', 'map,bid', " id_domains = '".$parent_domain."';");
	$bid	=	$result['bid'];
	$map	=	$result['map'];
	ms_log( '2. facebook_get_entries - Obteniendo infromacion del domino '.$parent_domain.' , mapa'.$map , $LOG_ID , false  );
	
	//Obtener mapeo del dominio con los credenciales
	ms_log( '3. facebook_get_entries - Obteniendo credenciales blogger del domino '.$parent_domain , $LOG_ID , false  );
	$result =  multiscalar(  'mapings', 'username,password', " idmapings = '".$map."';");
	$username 	=  $result['username'];
	$password 	=  $result['password'];
	$service 	=  'blogger';
	
	include('blogger_api.php');
	include('facebook_api.php');
	
	ms_log( '4.a facebook_get_entries - Obteniendo entradas de facebook, id '.$url , $LOG_ID , false  );
	$data_raw = get_entries( $url );
	//var_dump( $data_raw );die();
	ms_log( '4.b facebook_get_entries - '.(count($data_raw)-1).'Entradas obtenidas de facebook, id '.$url , $LOG_ID , false  );
	
	$omitidas 	= 0;
	$nuevas		= 0;
	if ( !is_array( $data_raw )) die( $data_raw ); else {
		//Get all data post - https://developers.facebook.com/docs/reference/api/group/
		for($x =0; $x<=count($data_raw)-1;$x++ ) {
		
			$data_entry	= $data_raw[$x]['data'];
			//var_dump(  $data_entry  );  die();		
			$data_entry['domain_id'] 	 	= $target_domain ;
			$data_entry['md5'] 	 			= $data_raw[$x]['md5'];   
			$data_entry['body'] 	 		= $data_raw[$x]['body'];  
			$data_entry['fid'] 	 			= $data_raw[$x]['fid'];  
			$data_entry['tool_id'] 	 		= $idtools;                
			$data_entry['record_time']		= date("Y-m-d H:i:s");
			$super_id				 		= $data_entry['fid'];
			
			//Get arrays first and convert to  serialized objects
			foreach( $data_entry as $key => $value){
				if (is_array( $value ) ){
						$data_entry[ $key ] = serialize($value) ;
				}
			}	
			if ( check_post_exists_bykey(  'fb_group_entries', 'fid' , $super_id ) ) {
				$omitidas++;
				$temp_log .= " OMITIENDO entrada ".$super_id .' ya existe.';
				unset(  $data_raw[$x]   ); //eliminar si ya exisste
				continue;
				//echo 'extiste'; 
				//If exist , validate if we need to update 
			} else{
				$nuevas++;
				//echo ' no existe';
				$temp_log .= " Agregando entrada ".$super_id .'.';
				insert_values( 'fb_group_entries',  $data_entry );
				$data_entry_A .= " new value added. ";
			}
			/*//crear automaticamente 
			foreach($data_entry as $column => $value) { 
				//Use this code only if you want to inteligence inserts.
				//Locate column in table
				if ( check_if_column_exists( 'fb_group_entries', $column ) ) {
					//If exist , insert value into.
					insert_value(  'fb_group_entries', $column , $value , $super_id  );
				} else
				//Else create new column.
					if (create_new_column( 'fb_group_entries',  $column ) ) 
					insert_value( 'fb_group_entries', $column , $value , $super_id  );	
			}*/
		}
	}
	//die();
	ms_log( '5.a facebook_get_entries - '.$omitidas.' feeds omitidos, '.$nuevas.' feed nuevos' , $LOG_ID , true  , LLOG_INFO );
	
	ms_log( '5.b facebook_get_entries - Insertando entradas de fbG en la db: '.$temp_log , $LOG_ID , false  );
			
	IF($nuevas>0){
		ms_log( '6. facebook_get_entries - Insertando entradas de fb gruop a blogger id: '.$parent_domain , $LOG_ID , false  );
		/*
		1. crear post 
		2. guardar id del post vacio en entries, junto el idfbpost.
		*/
		$e_nuevas		= 0;
		foreach($data_raw as $entry ){				
			//Solamente si no existe en la db
			if (  check_entries_exists(  'fid' , $entry['fid']) == false  ) {
			//Si el id no existe en entries, entonces debe crear el post en blogger y retornar el id para se ingresado
				$Result_log .=  'Creando entrada de fb group '.$entry['fid'].' a  entrada del sitio : '.$bid;	
				//($gBlogId, $myBlogTitle, $myBlogText , $gCategory , isdraft
				$nsid = createPublishedPost($bid, $entry['titulo'], $entry['body'] ,$keywords.',fb_'.$entry['type'] , $entry['isdraft'] ) ;
				if ( $nsid != null){
					//When is pushed add id to trasnlated field.
					$e_nuevas++;
					sql(  ' insert into entries  (fid  , fmd5 , id , tool_id , domain_id, hash_content  ) values ("'.$entry['fid'].'" , "'.$entry['md5'].'" , "'.$nsid.'" , "'.$idtools.'" , "'.$parent_domain.'" , "'.rand().'")  ; ' );
				}
			} ELSE 
			$Result_log .= 'OMITIENDO entrada de fb group '.$entry['fid'].' a  entrada del sitio : '.$bid;
			//die();	
		}
		ms_log( '6. facebook_get_entries - '.$e_nuevas.'# entradas nuevas, Insertando entradas de fbG en la blogger: '.$Result_log , $LOG_ID , false  );
	} else 
	ms_log( '5.c facebook_get_entries - no hay nuevos feeds de fb para agregar en blogger: ' , $LOG_ID ,  true  , LLOG_INFO  );
	
	
	ms_log( '7. facebook_get_entries - todo procesado ' , $LOG_ID , false  );
	sql( 'UPDATE tools SET status= 1, log = "'. $LOG_ID.'" WHERE  idtools = "'.$idtools.'" ;');