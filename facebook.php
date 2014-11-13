<?php
//FacebookSession::setDefaultApplication('YOUR_APP_ID', 'YOUR_APP_SECRET');
$email = $_GET['email'];
if (!empty($email)) {
	require 'connect.php';
    include 'stdlib.php'; 
	$email = trim($email);
	$sql = "SELECT * FROM user WHERE user.email='".$email."'";
	$result = mysql_query($sql);
	if($user = mysql_fetch_array($result)) {
		session_start();
		$_SESSION['login'] = trim($_POST['email']);
		header('Location: index.php?page=home' );
		echo "1";
	} else {
		$_SESSION['login_err'] = 1;
		header('Location: index.php?page=home&login=err');
	}
}

echo $_GET['email'];
?>