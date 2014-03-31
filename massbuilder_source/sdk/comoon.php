<?php
ini_set('max_execution_time', 999999999999); 
define('HOST', 'localhost');
define('USER', 'root');
define('PASS', 'root');
define('DB', 'massbuilder');

define( LLOG_INFO , 1);
define( LLOG_WARNING , 2);
define( LLOG_CRITICAL , 3);
define( LLOG_DEBUG , 4);


function ms_log(  $msj , $LOG_ID , $print  , $severity = LLOG_DEBUG ){
	$_fields = array();
	$logSecurity = new MassBuilderLogSimple( $msj , $LOG_ID , $severity );
	$_fields['log']		=	$logSecurity->toString();
	$_fields['log_id']	=	$LOG_ID;
	$_fields['msj']		=	$msj;
	$_fields['log_severity']	=	$severity;
	$_fields['timestamp']	=	date("Y-m-d H:i:s");
	insert_values( 'logs',   $_fields  );
	if ($print === true) $logSecurity->printt( $msj );
}

function check_entries_exists( $key,$id ){
	$link = mysql_connect(HOST,USER,PASS);
	if (!$link) {
		die('Could not connect: ' . mysql_error());
	}
	if (!mysql_select_db(DB)) {
		die('Could not select database: ' . mysql_error());
	}
	//echo ('SELECT * FROM entries where '.$key.' = "'.$id.'"');
	$result = mysql_query('SELECT * FROM entries where '.$key.' = "'.$id.'"');
	if (!$result) {
		die('Could not query:1' . mysql_error());
	}
	 if(mysql_fetch_array($result) !== false)
        return true;
    return false;
}

function check_post_exists( $table, $id ){
	$link = mysql_connect(HOST,USER,PASS);
	if (!$link) {
		die('Could not connect: ' . mysql_error());
	}
	if (!mysql_select_db(DB)) {
		die('Could not select database: ' . mysql_error());
	}
	$result = mysql_query('SELECT * FROM `'.$table.'` where id = "'.$id.'"');
	if (!$result) {
		die('Could not query:2' . mysql_error());
	}
	 if(mysql_fetch_array($result) !== false)
        return true;
    return false;
}

function check_post_exists_byhash( $table, $id , $hash){
	$link = mysql_connect(HOST,USER,PASS);
	if (!$link) {
		die('Could not connect: ' . mysql_error());
	}
	if (!mysql_select_db(DB)) {
		die('Could not select database: ' . mysql_error());
	}
	$result = mysql_query('SELECT * FROM `'.$table.'` where id = "'.$id.'" and hash_content = "'.$hash.'"');
	if (!$result) {
		die('Could not query:3' . mysql_error());
	}
	 if(mysql_fetch_array($result) !== false)
        return true;
    return false;
}
function check_post_exists_bykey( $table,  $key , $id ){
	$link = mysql_connect(HOST,USER,PASS);
	if (!$link) {
		die('Could not connect: ' . mysql_error());
	}
	if (!mysql_select_db(DB)) {
		die('Could not select database: ' . mysql_error());
	}
	//echo 'SELECT * FROM `'.$table.'` where "'.$key.'" = "'.$id.'" ';die();
	$result = mysql_query('SELECT * FROM `'.$table.'` where '.$key.' = "'.$id.'" ');
	if (!$result) {
		die('Could not query:4' . mysql_error());
	}
	 if(mysql_fetch_array($result) !== false)
        return true;
    return false;
}

function check_if_column_exists( $table, $column ){
	$link = mysql_connect(HOST,USER,PASS);
	if (!$link) {
		die('Could not connect: ' . mysql_error());
	}
	if (!mysql_select_db(DB)) {
		die('Could not select database: ' . mysql_error());
	}
	if (false === mysql_query('select  '.$column .'  from `'.$table.'` limit 0'))  return false;
    return true;
}
function insert_value( $column , $value , $super_id  ){}

function func($value) {
		return @mysql_real_escape_string( $value );
};

function insert_values( $table,  $_fields  ){
	$link = mysql_connect(HOST,USER,PASS);
	if (!$link) {
		die('Could not connect: ' . mysql_error());
	}
	if (!mysql_select_db(DB)) {
		die('Could not select database: ' . mysql_error());
	}
	mysql_query('SET NAMES "utf8"'); 
	mysql_query('SET GLOBAL max_allowed_packet=1000000000000000;'); 
	mysql_query('SET GLOBAL net_buffer_length=1000000000000000000;'); 
	//$escapedfieldValues = array_map(create_function('$e', 'return mysql_real_escape_string(((get_magic_quotes_gpc()) ? stripslashes($e) : $e));'), array_values($_fields));
    $escapedfieldValues = array_map( 	'func' 
										, array_values($_fields)
									);
    $sql = sprintf('INSERT INTO `'.$table.'` (`%s`) VALUES ( "%s")', implode('`,`',array_keys($_fields)), implode('","',$escapedfieldValues));
	$result = mysql_query( $sql );
	if (!$result) {
		die('Could not query:5' . mysql_error());
	}
	 if(@mysql_fetch_array($result) !== false)
        return true;
    return false;	
}

function update_values_byhash( $table,  $super_id ,$hash, $_fields  ){   //( 'entries', $entry_id , $entry['hash_content'] , $entry  ){
	$link = mysql_connect(HOST,USER,PASS);
	if (!$link) {
		die('Could not connect: ' . mysql_error());
	}
	if (!mysql_select_db(DB)) {
		die('Could not select database: ' . mysql_error());
	}
	mysql_query('SET NAMES "utf8"'); 
	/*$escapedfieldValues = array_map( 	'func' 
										, array_values($_fields)
									);*/
    //$sql = sprintf('INSERT INTO `'.$table.'` (`%s`) VALUES ( "%s")', implode('`,`',array_keys($_fields)), implode('","',$escapedfieldValues));
	

     foreach ($_fields as $key => $value) {
        $value = mysql_real_escape_string($value);
        $value = "'$value'";
        $updates[] = "$key = $value";
      }
      $implodeArray = implode(', ', $updates);
      $sql = sprintf("UPDATE %s SET %s WHERE id='%s' and hash_content='%s' ", $table, $implodeArray, $super_id , $hash);

	$result = mysql_query( $sql );
	if (!$result) {
		die('Could not query:6' . mysql_error());
	}
	 if(@mysql_fetch_array($result) !== false)
        return true;
    return false;	
}	

			 
function create_new_column( $table, $column ){
	$link = mysql_connect(HOST,USER,PASS);
	if (!$link) {
		die('Could not connect: ' . mysql_error());
	}
	if (!mysql_select_db(DB)) {
		die('Could not select database: ' . mysql_error());
	}
	if (false === mysql_query( ' ALTER TABLE `'.$table.'` ADD `'.$column .'` VARCHAR(245)  ' ))  return false;
    return true;
}


function scalar( $table, $key , $where){
	$link = mysql_connect(HOST,USER,PASS);
	if (!$link) {
		die('Could not connect: ' . mysql_error());
	}
	if (!mysql_select_db(DB)) {
		die('Could not select database: ' . mysql_error());
	}
	mysql_query('SET NAMES "utf8"'); 
	$result = mysql_query('SELECT `'.$key.'` FROM `'.$table.'` where  '.$where.' ');
	if (!$result) {
		die('Could not query:7' . mysql_error());
	}
	$result = mysql_fetch_array($result);
    return $result[0];
}



function multiscalar( $table, $key , $where){
	$link = mysql_connect(HOST,USER,PASS);
	if (!$link) {
		die('Could not connect: ' . mysql_error());
	}
	if (!mysql_select_db(DB)) {
		die('Could not select database: ' . mysql_error());
	}
	mysql_query('SET NAMES "utf8"'); 
	$result = mysql_query('SELECT '.$key.' FROM `'.$table.'` where  '.$where.' ');
	if (!$result) {
		die('Could not query:88' . mysql_error());
	}
	$result = mysql_fetch_array($result);
    return $result;
}

function multiscalar2( $table, $key , $where){
	$link = mysql_connect(HOST,USER,PASS);
	if (!$link) {
		die('Could not connect: ' . mysql_error());
	}
	if (!mysql_select_db(DB)) {
		die('Could not select database: ' . mysql_error());
	}
	mysql_query('SET NAMES "utf8"'); 
	$result = mysql_query('SELECT '.$key.' FROM `'.$table.'` where  '.$where.' ');
	if (!$result) {
		die('Could not query:9' . mysql_error());
	}
	//$result = mysql_fetch_array($result);
	while($r[]=mysql_fetch_array($result));
    return $r;
}



function sql( $sql ){
	$link = mysql_connect(HOST,USER,PASS);
	if (!$link) {
		die('Could not connect: ' . mysql_error());
	}
	if (!mysql_select_db(DB)) {
		die('Could not select database: ' . mysql_error());
	}
	mysql_query('SET NAMES "utf8"'); 
	$result = mysql_query( $sql );
	if (!$result) {
		die('Could not query:10' . mysql_error());
	}
	//$result = mysql_fetch_array($result);
    return $result;
}


function removeEmptyLines($string)
{
return preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $string);
}

?>