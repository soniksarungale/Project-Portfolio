<?php
session_start();
//header('Content-Type: application/json');
require_once '../models/Database.php';
require_once '../models/User.php';

$database = new Database();
$db = $database->connect();

$data = Null;
if(isset($_POST["email"]) && isset($_POST["pass"]) && isset($_POST["usession"])){
    if(crypt($_SESSION['token'], $_POST['usession']) == $_POST['usession']){
    	$email=$_POST["email"];
    	$pass=$_POST["pass"];
		$user = new User($db);
	    $result = $user->login($email,$pass);
	    $num = $result->rowCount();
	    if($num > 0) {
	    	$row = $result->fetch(PDO::FETCH_ASSOC);
	    	if ($row["disabled"]!=1) {
				$_SESSION["logged"]=1;
				$_SESSION["full_name"]=$row["full_name"];
				$_SESSION["email"]=$row["email"];
				$_SESSION["username"]=$row["username"];
				$_SESSION["user_id"]=$row["user_id"];
        $_SESSION["location"]=$row["location"];
        $_SESSION["website"]=$row["website"];
        $_SESSION["company"]=$row["company"];
        $_SESSION["bio"]=$row["bio"];
				$_SESSION["verified"]=$row["verified"];
				$_SESSION["private"]=$row["private"];
				$_SESSION["disabled"]=$row["disabled"];
				$_SESSION["active"]=$row["active"];
				$_SESSION["level"]=$row["account_level"];
				$data = array('status' => 'sucess', 'type' => 'login', 'html' => $row["username"]);
	    	}else{
				$data = array('status' => 'error', 'type' => 'login', 'html' => 'Your account is been disabled. Please contact admin');
	    	}
	    }else{
			$data = array('status' => 'error', 'type' => 'login', 'html' => 'Incorrect email or password');
	    }
	}else{
		$data = array('status' => 'error', 'type' => 'session', 'html' => 'Refress your page and try again');
	}
}
echo json_encode($data);
?>
