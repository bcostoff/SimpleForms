<?php

require_once('dbConnection_class.php');
require_once('mysql.class.php');
require_once('simple_form_class.php');

//---------------------------- EXAMPLE FUNCTION --------------------------//

function formNameProcess(){
	$values_array = array();
	$columns_array = array();
	foreach($_POST as $key=>$value){
		$my_value = $value;
	 	$my_column = $key;
	 	array_push($values_array,$my_value);
	 	array_push($columns_array,$my_column);
	}
	$form = new simple_form('table_name',$values_array,$columns_array);
	$newId = $form->insert();
}

?>
