<?php
use \Gumlet\ImageResize;

$klein->respond($file.'/[:username]', function ($request, $response, $service) {
    $database = new Database();
    $db = $database->connect();
    $service->escape = function ($str) {
        return htmlentities($str);
    };
    if (isset($_SESSION["username"])) {
        if ($_SESSION["username"]==$request->username) {
            $user=json_decode(json_encode($_SESSION));
            $service->render('files/includes/auth.php');
            $service->render('files/models/SocialMedia.php');
            $service->render('files/models/Project.php');
            $service->render('files/models/ProjectDesign.php');
            $service->render('files/models/ProjectVideo.php');
            $service->render('files/models/ProjectVisitor.php');
            $service->render('files/models/Skill.php');
            $service->render('files/models/UserSkill.php');
            $service->render('dashboard.php', array('user' => $user,'db'=>$db,'msg'=>$service->flashes()));
            return;
        }
    }

    $user = new User($db);
    $result = $user->findByUsername($request->username);
    $num = $result->rowCount();
    if($num > 0) {
        $row = $result->fetch(PDO::FETCH_OBJ);
        $service->render('files/models/SocialMedia.php');
        $service->render('files/models/Project.php');
        $service->render('files/models/ProjectDesign.php');
        $service->render('files/models/ProjectVideo.php');
        $service->render('files/models/ProjectVisitor.php');
        $service->render('files/models/UserSkill.php');
        $service->render('user.php', array('user' => $row,'db'=>$db));
    }else{
        $service->render('files/views/pages/404.php');
    }
    
});
//profile image
$klein->respond($file.'/[:username]/p/avatar', function ($request, $response, $service) {
    header('Content-type: image/jpeg');
    $database = new Database();
    $db = $database->connect();
    $user = new User($db);
    $result = $user->findByUsername($request->username);
    $num = $result->rowCount();
    if($num > 0) {
        $row = $result->fetch(PDO::FETCH_OBJ);
        if ($row->profile_picture!="") {
          $service->render('files/uploads/profiles/'.$row->profile_picture);
          return;
        }
        $service->render('files/uploads/profiles/user.jpg');
        return;
    }
    $service->render('files/uploads/profiles/user.jpg');
    return;
});

//update user info
$klein->respond('POST',$file.'/[:username]/update/profile', function ($request, $response, $service) use ($klein)  {
    $klein->onError(function ($klein, $err_msg) {
      $data = array('status' => 'error', 'type' => 'user update', 'html' => $err_msg);
      $klein->response()->json($data);
    });

    $service->render('files/includes/auth.php');
    if ($_SESSION["username"]==$request->username) {
        $service->render('files/models/SocialMedia.php');

        $service->validateParam('full_name', 'Please enter a valid full name')->isLen(1, 64)->isChars('a-zA-Z ')->notNull();
        $service->validateParam('username', 'Please enter a valid username')->isLen(1, 64)->isAlnum()->notNull();
        $service->validateParam('bio', 'Please enter a valid bio')->isLen(0, 1000);
        $service->validateParam('company', 'Please enter a valid company')->isLen(0, 60);
        $service->validateParam('location', 'Please enter a valid location')->isLen(0, 60);
        $service->validateParam('website', 'Please enter a valid website')->isLen(0, 100);
        $service->validateParam('twitter', 'Please enter a valid twitter username')->isLen(0, 30);
        $service->validateParam('github', 'Please enter a valid github username')->isLen(0, 30);
        $service->validateParam('linkedin', 'Please enter a valid linkedin username')->isLen(0, 30);
        $service->validateParam('codepen', 'Please enter a valid codepen username')->isLen(0, 30);

        $database = new Database();
        $db = $database->connect();

        $user = new User($db);
        $socialMedia = new SocialMedia($db);
        $user->full_name=$request->paramsPost()->full_name;
        $user->username=$request->paramsPost()->username;
        $user->bio=$request->paramsPost()->bio;
        $user->company=$request->paramsPost()->company;
        $user->location=$request->paramsPost()->location;
        $user->website=$request->paramsPost()->website;
        $socialMedia->twitter=$request->paramsPost()->twitter;
        $socialMedia->github=$request->paramsPost()->github;
        $socialMedia->linkedin=$request->paramsPost()->linkedin;
        $socialMedia->codepen=$request->paramsPost()->codepen;
        if ($user->update($_SESSION["user_id"]) && $socialMedia->update($_SESSION["user_id"])) {
          $_SESSION["full_name"]=$user->full_name;
          $_SESSION["location"]=$user->location;
          $_SESSION["website"]=$user->website;
          $_SESSION["company"]=$user->company;
          $_SESSION["bio"]=$user->bio;
          if ($user->username==$_SESSION["username"]) {
            $data = array('status' => 'sucess', 'type' => 'user update', 'html' => 'Account successfully updated');
            $response->json($data);
            return;
          }
          if (!$user->uniqueUsername($user->username)) {
            $data = array('status' => 'error', 'type' => 'username', 'html' => 'Username already taken');
            $response->json($data);
            return;
          }
          if ($user->updateUsername($_SESSION["user_id"])) {
            $_SESSION["username"]= $user->username;
            $data = array('status' => 'sucess', 'type' => 'username', 'html' => $user->username);
            $response->json($data);
            return;
          }
          $data = array('status' => 'error', 'type' => 'username', 'html' => 'Error while updating username');
          $response->json($data);
          return;
        }
        return;
    }


});


$klein->respond('POST',$file.'/[:username]/update/profile/img', function ($request, $response, $service)  {
  $service->render('files/includes/auth.php');
  if ($_SESSION["username"]==$request->username) {
    if (isset($request->files()->userimg) && file_exists($request->files()->userimg["tmp_name"]) && is_uploaded_file($request->files()->userimg['tmp_name'])) {
      $database = new Database();
      $db = $database->connect();

      $user=new User($db);

      $image = new ImageResize($request->files()->userimg["tmp_name"]);
      $image->resize(400, 400);
      $imageFileType = strtolower(pathinfo($request->files()->userimg["name"],PATHINFO_EXTENSION));
      $newImgName=$request->id($hash = true).".".$imageFileType;

      $image->save('files/uploads/profiles/'.$newImgName);
      $user->profile_picture=$newImgName;
      if ($user->updateProfilePicture($_SESSION["user_id"])) {
        $response->redirect('../../../'.$request->username, $code = 302);
      }
    }
  }
});

$klein->respond('POST',$file.'/[:username]/skills/save', function ($request, $response, $service)  {
  $service->render('files/includes/auth.php');
  if ($_SESSION["username"]==$request->username) {
    if (isset($request->paramsPost()->skills)) {
      $database = new Database();
      $db = $database->connect();
      $service->render('files/models/UserSkill.php');
      $userSkill = new UserSkill($db);
      $result = $userSkill->findByUser($_SESSION["user_id"]);
      $num = $result->rowCount();
      $userSkill->user_id=$_SESSION["user_id"];
      if($num > 0) {
        $error=0;
        $row = $result->fetchAll(PDO::FETCH_ASSOC);
        $oldskills=array();
        foreach ($row as $value) {
          array_push($oldskills,$value["skill_id"]);
          if (!in_array($value["skill_id"],$request->paramsPost()->skills)) {
            echo "Old : ".$value["skill_id"];
            if(!$userSkill->delete($value["user_skill_id"])){
              $error=1;
            }
          }
        }
        foreach ($request->paramsPost()->skills as $key => $skill_id) {
          if (!in_array($skill_id,$oldskills)) {
            echo "New : ".$skill_id;
            $userSkill->skill_id=$skill_id;
            if(!$userSkill->create()){
              $error=1;
            }
          }
        }
        if ($error==0) {
          $service->flash("Skills successfully saved",'sucess');
          $service->back();
          return;
        }
        $service->flash("Error please try again later",'error');
        $service->back();
        return;
      }else{
        $error=0;
        foreach ($request->paramsPost()->skills as $key => $skill_id) {
          $userSkill->skill_id=$skill_id;
          if(!$userSkill->create()){
            $error=1;
          }
        }
        if ($error==0) {
          $service->flash("Skills successfully saved",'sucess');
          $service->back();
          return;
        }
        $service->flash("Error please try again later",'error');
        $service->back();
        return;
      }

    }
  }
});

?>
