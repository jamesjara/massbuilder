<?php
ini_set( 'default_charset', 'UTF-8' );
require 'facebook-php-sdk-master/src/facebook.php';

// Create our Application instance (replace this with your appId and secret).
$facebook = new Facebook(array(
  'appId'  => '296166450514839',
  'secret' => 'b1120eec7624941d38b64db17f1c3004',
));



function get_entries( $grupo_id ){
	global $facebook;
	
	// Get User ID
	$user = $facebook->getUser();
	if ($user) {
	  try {
		// Proceed knowing you have a logged in user who's authenticated.
		$user_profile = $facebook->api('/me');
	  } catch (FacebookApiException $e) {
		//var_dump($e);
		$user = null;
	  }
	  try {
		// Proceed knowing you have a logged in user who's authenticated.
		$access_token = $facebook->getAccessToken();
		$groupWall = $facebook->api(
									'/'.$grupo_id.'/feed?fields=caption,description,created_time,application,actions,coordinates,expanded_height,feed_targeting,expanded_width,height,icon,id,is_hidden,is_published,link,message,message_tags,name,object_id,parent_id,picture,place,privacy,promotion_status,properties,scheduled_publish_time,shares,source,status_type,story,story_tags,subscribed,targeting,timeline_visibility,to,type,updated_time,via,width,with_tags,from,comments.limit(500).fields(can_remove,created_time,from,id,like_count,message,message_tags,user_likes,likes,comments)&with=<value>&limit=10' 
									, array('access_token'=>$access_token)
									);
	  } catch (FacebookApiException $e) {
		var_dump($e);
		$groupWall = null;
	  }
	}
	
	// Login or logout url will be needed depending on current user state.
	if ($user) {
	  $logoutUrl = $facebook->getLogoutUrl();
	} else {
	  $loginUrl = $facebook->getLoginUrl(array(
				'scope' => 'user_groups,friends_groups,user_website,user_work_history,user_relationships'
	  ));// $loginUrl = $facebook->getLoginUrl(); 
	}

	if (!$user)  //return '<a href="'.$logoutUrl.'">logoutUrl</a>'; else
	 return '<a href="'.$loginUrl.'">loginUrl</a>'; 
	 
		 

	//print_r($groupWall); 
	//print_r($user_profile); 
	
	$result = array();
	$c = 0;
	$data_A = null;
	$is_draft = false;
	//Get all data post - https://developers.facebook.com/docs/reference/api/group/
	$c2 = 0;
	foreach ($groupWall['data'] as $data) {
		//If  ($c2==2)continue;
		$data['id_grupo'] = $grupo_id ;
				
		$comentarios = array();
		if( isset($data['comments']) ){
			foreach( $data['comments']["data"] as $value){
					$comentarios[] =  '<div class="mbox"><span class="comentador">'.$value["from"]["name"].'</span> , <span class="message">'.$value["message"].'</span></div><br>';				
			}
		}
			
		$js_data = removeEmptyLines('<script>
				var data_post = {
				  "id": "'.$data['id'].'",
				  "created_time":  "'.$data['created_time'].'",
				  "is_published":  "'.$data['is_published'].'",
				  "promotion_status":  "'.$data['promotion_status'].'",
				  "timeline_visibility":  "'.$data['timeline_visibility'].'",
				  "type":  "'.$data['type'].'",
				  "updated_time":  "'.$data['updated_time'].'",
				  "id_grupo": "'.$data['id_grupo'].'",
				  "md5hash": "'.$md5hash.'"
				};</script>
		');
		
		if ( $data['type'] == "link") {
			//tipo LINK
			$id			= $data['id'];
			$name		= $data['name'];
			$caption	= $data['caption'];
			$description= $data['description'];
			$link		= $data['link'];
			$imagen		= $data['picture'];
			$autor		= $data['from']['name'];	
			$message	= $data['message']; 
			$titulo		= @substr($name,0,60);//wordwrap($description, 20, " ");
			$alt		= @substr($name,0,30);//wordwrap($description, 20, " ");
			$body = "<div class='mlink'>Mas: ".$link."</div> <br>
					<img src='".$imagen."' alt='".$alt."' >
						<div class='mdata'><b>".$name." , ".$message." </b> <br> ".$description." , ".$autor.".<br></div>
					<span id='superid' style='display:none;' >".$id ."</span>
					<h5>Comentarios:</h5><br>".implode(' ', $comentarios).' '.$js_data;	
			if ( str_word_count($description) > 13 ) $is_draft = true;
		} else if ( $data['type'] == "photo") {
			//tipo photo
			$id			= $data['id'];
			$message	= $data['message']; 
			$link		= $data['link']; 
			$autor		= $data['from']['name'];
			$imagen		= $data['picture'];	
			$alt		= @substr($message,0,30);//wordwrap($description, 20, " ");
			$titulo		= @substr($message,0,60);//wordwrap($message, 20, " ");
			$body = " 
					<img src='".$imagen."' alt='".$alt."' >
					<div class='mdata'><b>". wordwrap($message, 20, " ")." </b> <br> ".$message.' - '.$link."<br> ".$autor.".<br></div>
					<span id='superid' style='display:none;' >".$id ."</span>
					<h5>Comentarios:</h5><br>".implode(' ', $comentarios).' '.$js_data;		
			if ( str_word_count($message) > 13 ) $is_draft = true;
		} else if ( $data['type'] == "status") {
			//tipo status
			$id			= $data['id'];
			$message	= $data['message']; 
			$autor		= $data['from']['name'];	
			$titulo		= @substr($message,0,60);//wordwrap($message, 20, " ");	
			$body = " 
					<div class='mdata'><b>". wordwrap($message, 10, " ")." </b> <br> ".$message."<br>  ".$autor.".<br></div>
					<span id='superid' style='display:none;' >".$id ."</span>
					<h5>Comentarios:</h5><br>".implode(' ', $comentarios).' '.$js_data;		
			if ( str_word_count($message) > 13 ) $is_draft = true;
		} else if ( $data['type'] == "video") {
			//tipo video
			$id			= $data['id'];
			$name		= $data['name'];
			$caption	= $data['caption'];
			$description= $data['description'];
			$link		= $data['link'];
			$imagen		= $data['picture'];
			$autor		= $data['from']['name'];	
			$message	= $data['message']; 
			$video		= $data['source']; 
			$titulo		= @substr($name,0,60);
			$body = " 
					<div class='mdata'><b>".$name."  <br>  <video src='".$video."'  poster='".$imagen."' controls>
						No soporta HTML5Video ver aqui: ".$video."
				</video>    ".$message." </b> <br> ".$description." , ".$autor.".<br></div>
					<span id='superid' style='display:none;' >".$id ."</span>
					<h5>Comentarios:</h5><br>".implode(' ', $comentarios).' '.$js_data;	
			if ( str_word_count($description) > 13 ) $is_draft = true;
		} else {
		var_dump( $data['type'] );
		echo '************';
		die();
		}	

	

		$result[$c]['isdraft'] 	= $is_draft;
		$result[$c]['data'] 	= $data;
		$result[$c]['fid'] 		= $data['id'];
		$result[$c]['md5'] 		= md5($body);
		$result[$c]['body'] 	= $body;
		$result[$c]['titulo'] 	= $titulo;
		$result[$c]['type'] 	= $data['type'];
			
		//var_dump( $result[$c]['fid']  );die();
		$c++;
		$c2++;
	}
	return  $result;
	
}



