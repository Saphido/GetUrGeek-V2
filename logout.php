<?php
	include 'src/include/header.php';
	$request = updateInTable($pdo, 'user', ['isConnected'], ['0'], ['user_id'], [$_SESSION['user_login']]);
	header('location: index.php');
	session_destroy();
?>