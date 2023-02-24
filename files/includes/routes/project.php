<?php
$klein->respond($file.'/[:username]/[:project]', function ($request, $response, $service, $app) {
    $service->escape = function ($str) {
        return htmlentities($str);
    };
    $database = new Database();
    $db = $database->connect();

    $service->render('files/models/Project.php');
    $project = new Project($db);
    $result = $project->findByUrl($request->username,$request->project);
    if($result->rowCount() > 0) {
        $row = $result->fetch(PDO::FETCH_OBJ);
        if (isset($_SESSION["username"])) {
            if ($_SESSION["username"]==$request->username) {
                $user=json_decode(json_encode($_SESSION));
                if ($row->project_type_id=="1") {
                  $service->render('files/models/ProjectArchive.php');
                  $projectArchive = new ProjectArchive($db);
                  $archiveresult = $projectArchive->find($row->project_id);
                  if($archiveresult->rowCount() > 0) {
                    $service->render('files/views/pages/project.php', array('project' => $row));
                    return;
                  }
                  $service->render('files/views/pages/create/project.php', array('project' => $row,'msg'=>$service->flashes(),'user'=>$user));
                  return;
                }
                elseif ($row->project_type_id=="2") {
                  $service->render('files/models/ProjectDesign.php');
                  $service->render('files/views/pages/create/design.php', array('project' => $row,'db'=>$db,'msg'=>$service->flashes(),'user'=>$user));
                  return;
                }
                elseif ($row->project_type_id=="3") {
                  $service->render('files/models/ProjectVideo.php');
                  $service->render('files/views/pages/create/video.php', array('project' => $row,'db'=>$db,'msg'=>$service->flashes(),'user'=>$user));
                  return;
                }
                echo $row->project_type_id;
            }
        }
        if ($row->public=="0") {
          $service->render('files/views/pages/private.php', array('project' => $row));
          return;
        }
        if ($row->uploaded=="0") {
          $service->render('files/views/pages/comingsoon.php', array('project' => $row));
          return;
        }
        $unique_view=0;
        if (isset($_SESSION["project_viewed"])) {
          if (!in_array($row->project_id, $_SESSION["project_viewed"])) {
            $unique_view=1;
            array_push($_SESSION["project_viewed"],$row->project_id);
          }
        }else{
          $viewed=array();
          array_push($viewed,$row->project_id);
          $_SESSION["project_viewed"]=$viewed;
          $unique_view=1;
        }
        if ($unique_view) {
          $service->render('files/models/ProjectVisitor.php');
          $projectVisitor = new ProjectVisitor($db);
          $projectVisitor->project_id=$row->project_id;
          $projectVisitor->visitor_id=$app->visitor_id;
          $projectVisitor->create();

        }
        if ($row->project_type_id=="1") {
          $service->render('files/views/pages/project.php', array('project' => $row));
        }
        elseif ($row->project_type_id=="2") {
          $service->render('files/models/ProjectDesign.php');
          $service->render('files/views/pages/design.php', array('project' => $row,'db'=>$db));
        }
        elseif ($row->project_type_id=="3") {
          $service->render('files/models/ProjectVideo.php');
          $service->render('files/views/pages/video.php', array('project' => $row,'db'=>$db));
        }


    }else{
        $service->render('files/views/pages/404.php');
    }
});
$klein->respond($file.'/[:username]/project/new', function ($request, $response, $service) {
  $service->render('files/includes/auth.php');
  if ($_SESSION["username"]==$request->username) {
    $database = new Database();
    $db = $database->connect();
    $user=json_decode(json_encode($_SESSION));
    $service->render('files/models/ProjectType.php');
    $service->render('newproject.php', array('user' => $user,'db'=>$db,'msg'=>$service->flashes()));
  }
//    $service->render('landing.php');
});

$klein->respond('POST',$file.'/[:username]/project/create', function ($request, $response, $service) use ($klein) {
  $service->render('files/includes/auth.php');
  $service->render('files/models/Project.php');
  $database = new Database();
  $db = $database->connect();
  $project= new Project($db);
  $project->user_id=$_SESSION["user_id"];
  $service->project=$project;
  $klein->onError(function ($klein, $err_msg) {
    $klein->service()->flash($err_msg,'error');
    $klein->service()->back();
  });
  $service->addValidator('boolean', function ($str) {
      return ((($str == "1") || ($str == "0")) ? true : false);
  });
  $service->addValidator('uniqueUrl', function ($str) use ($klein) {
    $url=getUrl($str);
    if ($klein->service()->project->isProjectExist($url)) {
      return false;
    }
    return true;
  });
  if ($_SESSION["username"]==$request->username) {
    $request->paramsPost()->projectname=trim($request->paramsPost()->projectname);
    $service->validateParam('projectname', 'Please enter a valid project name')->isLen(1, 64)->isChars('a-zA-Z0-9- ')->notNull();
    $service->validateParam('visibility', 'Please select valid visibility')->isBoolean()->notNull();
    $service->validateParam('type', 'Please select a valid project type')->isLen(1)->isInt()->notNull();
    $service->validateParam('description', 'Please enter a valid project description')->isLen(0, 1000);
    $service->validateParam('projectname', 'Project with this name already exist')->isUniqueUrl();

    $project->title=$request->paramsPost()->projectname;
    $project->url=getUrl($request->paramsPost()->projectname);
    $project->project_type_id=$request->paramsPost()->type;
    $project->description=$request->paramsPost()->description;
    $project->public=$request->paramsPost()->visibility;
    $project->folder_name=$request->id($hash = true);

    if ($project->create()) {
      mkdir("files/projects/".$project->folder_name);
      $response->redirect('../'.$project->url, $code = 302);
    }
  }
});
$klein->respond('GET',$file.'/[:username]/[:project]/edit', function ($request, $response, $service) use ($klein) {
  $service->render('files/includes/auth.php');
  if ($_SESSION["username"]==$request->username) {
    $database = new Database();
    $db = $database->connect();
    $service->render('files/models/Project.php');
    $project = new Project($db);
    $result = $project->findByUrl($request->username,$request->project);
    if($result->rowCount() > 0) {
      $row = $result->fetch(PDO::FETCH_OBJ);
      $user=json_decode(json_encode($_SESSION));
      $service->render('files/models/ProjectType.php');
      $service->render('files/views/pages/edit/project.php', array('project'=>$row,'db'=>$db,'msg'=>$service->flashes(),'user'=>$user));
    }
  }
});
$klein->respond('POST',$file.'/[:username]/[:project]/update', function ($request, $response, $service) use ($klein) {
  $service->render('files/includes/auth.php');
  $service->render('files/models/Project.php');
  $database = new Database();
  $db = $database->connect();
  $project= new Project($db);
  $project->user_id=$_SESSION["user_id"];
  $service->project=$project;
  $klein->onError(function ($klein, $err_msg) {
    $klein->service()->flash($err_msg,'error');
    $klein->service()->back();
  });
  $service->addValidator('boolean', function ($str) {
      return ((($str == "1") || ($str == "0")) ? true : false);
  });
  $service->addValidator('uniqueUrl', function ($str) use ($klein) {
    $url=getUrl($str);
    if ($klein->service()->project->isProjectExist($url)) {
      return false;
    }
    return true;
  });
  if ($_SESSION["username"]==$request->username) {
    $result = $project->findByUrl($request->username,$request->project);
    if($result->rowCount() > 0) {
      $row = $result->fetch(PDO::FETCH_OBJ);

      $request->paramsPost()->projectname=trim($request->paramsPost()->projectname);
      $service->validateParam('projectname', 'Please enter a valid project name')->isLen(1, 64)->isChars('a-zA-Z0-9- ')->notNull();
      $service->validateParam('visibility', 'Please select valid visibility')->isBoolean()->notNull();
      $service->validateParam('description', 'Please enter a valid project description')->isLen(0, 1000);
      if ($row->title!=$request->paramsPost()->projectname) {
          $service->validateParam('projectname', 'Project with this name already exist')->isUniqueUrl();
      }

      $project->title=$request->paramsPost()->projectname;
      $project->url=getUrl($request->paramsPost()->projectname);
      $project->description=$request->paramsPost()->description;
      $project->public=$request->paramsPost()->visibility;

      if ($project->update($row->project_id)) {
        $klein->service()->flash('Project successfully updated','sucess');
        $response->redirect('../'.$project->url.'/edit', $code = 302);
      }
    }
  }
});
$klein->respond($file.'/[:username]/[:project]/delete', function ($request, $response, $service) use ($klein) {
  $service->render('files/includes/auth.php');
  $service->render('files/models/Project.php');
  $service->render('files/models/ProjectDeleted.php');
  $database = new Database();
  $db = $database->connect();
  $project= new Project($db);
  if ($_SESSION["username"]==$request->username) {
    $result = $project->findByUrl($request->username,$request->project);
    if($result->rowCount() > 0) {
      $row = $result->fetch(PDO::FETCH_OBJ);
      $projectDeleted= new ProjectDeleted($db);
      $projectDeleted->project_id=$row->project_id;
      $projectDeleted->deleted_folder=$request->id($hash = true);
      if($project->delete($row->project_id) && $projectDeleted->create()){
        if(file_exists("files/projects/".$row->folder_name)){
          if(rename("files/projects/".$row->folder_name,"files/projects/".$projectDeleted->deleted_folder)){
            $response->redirect('../../'.$request->username, $code = 302);
          }
        }
      }
    }
  }
});

?>
