<?php
	include 'src/include/header.php';
	$request = updateInTable($pdo, 'user', ['isConnected'], ['0'], ['user_id'], [$_SESSION['user_login']]);
	echo("<script>location.href = 'index.php';</script>");
	session_destroy();
?>