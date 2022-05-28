<?php 
include 'src/include/header.php';

//The user's id, which should be present in the GET variable "uid"
$userId = isset($_GET['uid']);
//The token for the request, which should be present in the GET variable "t"
$token = isset($_GET['t']);
//The id for the request, which should be present in the GET variable "id"
$passwordRequestId = isset($_GET['id']);


//Now, we need to query our password_reset_request table and
//make sure that the GET variables we received belong to
//a valid forgot password request.

$req = selectInTable($pdo, 'password_reset_request', ['user_id', 'token', 'id'], ['user_id', 'token', 'id'], ["'" .$_GET['uid'] . "'", "'" .$_GET['t'] . "'", "'" .$_GET['id'] . "'",], 'AND');

//Fetch our result as an associative array.
$requestInfo = $req->fetch();

//If $requestInfo is empty, it means that this
//is not a valid forgot password request. i.e. Somebody could be
//changing GET values and trying to hack our
//forgot password system.
if(empty($requestInfo)){
    echo 'Invalid request!';
    exit;
}

//The request is valid, so give them a session variable
//that gives them access to the reset password form.
$_SESSION['user_id_reset_pass'] = $userId;

//Redirect them to your reset password form.
header('Location: change_password.php');
exit;