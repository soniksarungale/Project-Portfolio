<?php
session_start();
require_once 'files/models/Database.php';
require_once 'files/models/User.php';
require_once 'files/models/Verification.php';

$database = new Database();
$db = $database->connect();
$user = new User($db);
$msg="";
$verified=0;
if(isset($_SESSION["verified"])){
	if ($_SESSION["verified"]==1) {
		$verified=1;
	}else{
		$userresult = $user->findByUsername($_SESSION["username"]);
		$usernum = $userresult->rowCount();
		if($usernum > 0) {
				$userrow = $userresult->fetch(PDO::FETCH_OBJ);
				if ($userrow->verified=="1") {
					$verified=1;
					$_SESSION["verified"]=1;
				}
		}
	}
}
if (isset($_GET["code"]) && $verified==0){
	$code=trim($_GET["code"]);
	$verification = new Verification($db);

	$result = $verification->findLatestByCode($code);
	if($result->rowCount() > 0) {
		$row = $result->fetch(PDO::FETCH_ASSOC);
		$expiretime = strtotime($row["expire_on"]);
		$curtime = time();
		if ($curtime<$expiretime) {
			if ($user->verified($row["user_id"])) {
				$userdata = $user->find($row["user_id"]);
				$data = $userdata->fetch(PDO::FETCH_ASSOC);
				$_SESSION["logged"]=1;
				$_SESSION["full_name"]=$data["full_name"];
				$_SESSION["email"]=$data["email"];
				$_SESSION["username"]=$data["username"];
				$_SESSION["user_id"]=$data["user_id"];
				$_SESSION["verified"]=1;
				$_SESSION["private"]=$data["private"];
				$_SESSION["disabled"]=$data["disabled"];
				$_SESSION["active"]=$data["active"];
				$_SESSION["level"]=$data["account_level"];
				$msg="Verified";
				$verified=1;
			}
		}else{
			$msg="This link is expired";
		}
	}else{
		$msg="Invalid Link";
	}

}
if (isset($_SESSION["logged"]) && $msg=="" && $verified==0) {
	$msg="Verification link has been send to your email address ".$_SESSION["email"];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Verify</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSriVlkMXe40PTKnXrLnZ9+fkDaog==" crossorigin="anonymous" />
	<link rel="stylesheet" href="files/css/master.css">
</head>
<body>
	<?php
	require_once 'files/includes/header.php';
	?>
	<section id="verify">
		<div class="container container-mgr grey-bg">
			<div class="center-content">
				<?php
				if ($verified==1) {
					echo "Your account has been verified<br>Go to <a href='".$_SESSION["username"]."'>Your Account</a>";
				}
				echo $msg;
				?>
			</div>
		</div>
	</section>
</body>
</html>
