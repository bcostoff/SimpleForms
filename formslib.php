<?php
include_once "form_functions.php";

//Get Return URL
$returnURL = $_SERVER["HTTP_REFERER"];

if ($_POST) {

	//Determine Form Name
	$form_name = $_POST['form_name'];

	//Create Variable Function
	$form_func = $form_name.'Process';

	//Call Function
	$form_func();

}else{

	header('Location:'.$returnURL);
	exit();

}
?>
