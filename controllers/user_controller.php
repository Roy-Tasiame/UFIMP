<?php
//connect to the user account class
include_once("../classes/user_class.php");



function registerUser_ctr($user_name, $email, $password, $user_role){
	$adduser=new customer_class();
	$result = $adduser->registerUser($user_name, $email, $password, $user_role);
}


function loginUser_ctr($email, $password) {
	$loginClass = new customer_class();
	$result = $loginClass->loginUser($email, $password);

    // Call the login_user method in the general_class
    return $result;
}




?>