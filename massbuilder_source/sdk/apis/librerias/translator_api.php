<?php

$proxies = array(); 
 
// Adding list of proxies to the $proxies array
$proxies[] = '183.89.73.121:3128';
$proxies[] = '180.183.25.158:3128';
$proxies[] = '180.183.187.29:3128';
$proxies[] = '188.126.79.105:3128';
$proxies[] = '221.123.162.123:3128';


$isBlocked = false;
$proxy = null;
function translate( $data , $from , $to ){
	global $proxies;
	
	$asd 	= chunk_split ( $data  , 500  , '____jamesjaraJump63234_____');
	$lines 	= explode('____jamesjaraJump63234_____',$asd);
		
	logg('Procesando  '.count($lines).' para traducir.');
	
	//Todo in case of error the chunk of data will be omited.
	$final = array();
	foreach( $lines as $line ){
		if( !empty($line) ){
				$url="http://mymemory.translated.net/api/get?q=".urlencode ($line)."&langpair=".$from."|".$to."";
				$agent= 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)';

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
				curl_setopt($ch, CURLOPT_VERBOSE, true);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_USERAGENT, $agent);
				curl_setopt($ch, CURLOPT_URL,$url);
				
				if (count($proxies)==0)die('todos los proxies blokeados no se puede obtner mas datos');
				if ( $proxy == null ) { 
					$proxy = $proxies[array_rand($proxies)];  
					unset( $proxies[$proxy] );
				}
				if ( $isBlocked == true ) { 
					$proxy = $proxies[array_rand($proxies)];   
					unset( $proxies[$proxy] );
				}
				if (isset($proxy)) {    // If the $proxy variable is set, then
					//curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
					curl_setopt($ch, CURLOPT_PROXY, $proxy);    // Set CURLOPT_PROXY with proxy in $proxy variable
					$isBlocked = false;
				}
	 
				
				$result = json_decode ( curl_exec($ch) );
				
				if( $result->responseStatus == 200 ){
					$final[] = $result->responseData->translatedText;
					$next = true;
					logg('1 linea mas Procesada.');
				} else {
					$final[] = "**code1337**error : ".$result->responseDetails;
					$isBlocked = true;
				}
		}
	}
	return implode(' ',$final);
}

