<?php
session_start();
header('Content-Type: application/json');
require_once '../models/Database.php';
require_once '../models/User.php';
require_once '../models/Verification.php';
require_once '../models/SocialMedia.php';
require_once '../includes/mail.php';

$database = new Database();
$db = $database->connect();

function getfullname($string){
	$string = strstr($string, '@', true);
	$string = str_replace(' ', '-', $string);
	return preg_replace('/[^A-Za-z0-9\-]/', ' ', $string);
}
function getusername($string){
	$string = strstr($string, '@', true);
	$string = str_replace(' ', '-', $string);
	return preg_replace('/[^A-Za-z0-9\-]/', '', $string);
}
$data = Null;
if(isset($_POST["email"]) && isset($_POST["pass"])  && isset($_POST["rpass"]) && isset($_POST["usession"])){
    if(crypt($_SESSION['token'], $_POST['usession']) == $_POST['usession']){
		$verification = new Verification($db);
		$social_media = new SocialMedia($db);

		$user = new User($db);
		$user->full_name=getfullname($_POST["email"]);
		$user->email=$_POST["email"];
		$user->username=getusername($_POST["email"]);
		$user->password=$_POST["pass"];

		if(!$user->emailExist($user->email)){
			while (!$user->uniqueUsername($user->username)){
				$user->username=$user->username+rand(0,1000);
			}
			if ($user->create()) {
				$verification->user_id=$user->user_id;
				$verification->code=md5(uniqid($user->user_id, true));
				$verification->expire_on=date("Y-m-d H:i:s",time()+3600);
				$social_media->user_id=$user->user_id;
//				if($verification->create() && sendmail($user->email,$verification->code) && $social_media->create()){
				if($verification->create() && $social_media->create()){
					$_SESSION["logged"]=1;
					$_SESSION["full_name"]=$user->full_name;
					$_SESSION["email"]=$user->email;
					$_SESSION["username"]=$user->username;
					$_SESSION["user_id"]=$user->user_id;
					$_SESSION["location"]="";
					$_SESSION["website"]="";
					$_SESSION["company"]="";
					$_SESSION["bio"]="";
					$_SESSION["verified"]=1;
					$_SESSION["private"]=0;
					$_SESSION["disabled"]=0;
					$_SESSION["active"]=1;

					$data = array('status' => 'sucess', 'type' => 'user account', 'html' => 'User account created sucessfully');
				}else{
					$data = array('status' => 'error', 'type' => 'verification', 'html' => 'Error while sending verification mail');
				}
			}else{
				$data = array('status' => 'error', 'type' => 'user account', 'html' => 'Error while creating user account');
			}
		}else{
			$data = array('status' => 'error', 'type' => 'email', 'html' => 'Email address already exist');
		}

	}else{
		$data = array('status' => 'error', 'type' => 'session', 'html' => 'Refress your page and try again');
	}
}
echo json_encode($data);
?>
