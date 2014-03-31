<?php

	//include('apis/librerias/htmLawed/htmLawed.php');

	$data =  $_POST['data'];
	
	$data2 =  '<div dir="ltr" style="text-align: left;" trbidi="on">
	<div class="mlink">
		<br />
		<div class="mdata">
			<b>Hecho Ingenieril<br />
			<br />
			Hay dos tipos de Ing. de Sistemas:<br />
			<br />
			1. Aquellos que pueden hacer extrapolaci&oacute;n de datos incompletos. </b><br />
			Hecho Ingenieril<br />
			<br />
			Hay dos tipos de Ing. de Sistemas:<br />
			<br />
			1. Aquellos que pueden hacer extrapolaci&oacute;n de datos incompletos.<br />
			autor: Randall Barnett.</div>
		<br />
		<span id="superid" style="display: none;">214267468618834_531720546873523</span><br />
		<h5>
			Comentarios:</h5>
		<br />
		<div class="mbox">
			<span class="comentador">Comentador: Juan Mat&iacute;as</span> , <span class="message">... y los que se quedan esperando, a ver qui&eacute;n les da los datos ;) !!</span></div>
<script><br />    var data_post = { <br />      "id": "214267468618834_531720546873523", <br />      "created_time":  "2013-05-13T12:38:29+0000", <br />      "is_published":  "1", <br />      "promotion_status":  "ineligible", <br />      "timeline_visibility":  "no timeline unit for this post", <br />      "type":  "status", <br />      "updated_time":  "2013-05-13T13:45:23+0000", <br />      "id_grupo": "214267468618834", <br />      "md5hash": "" <br />    };</script>	</div>
</div>';

	  $options = array(
	  "show-body-only" => true,
	  "clean" => true,
	  "vertical-space" => true
	  
	  );
    $tidy = tidy_parse_string( $data , $options , 'UTF8'  );
    tidy_clean_repair($tidy);
    echo $tidy;
	
	//$spec = 'p, span, b, i, em, strong, sub, sup, strike, table, caption, tbody, tr, td = style(nomatch=%"("?<!background-color"|"color"|"font-weight"|"text-decoration")"\s*:%i);';

	//$config['deny_attribute'] = 'class, id';

	//$out = htmLawed( $data  , $config, $spec);

	//echo 'cc' ;

	die();
?>