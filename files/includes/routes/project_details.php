<?php
use \Gumlet\ImageResize;

$klein->respond('POST',$file.'/[:username]/[:project]/upload/frontend', function ($request, $response, $service) use ($klein) {
  $service->render('files/includes/auth.php');
  $fill_extensions =array("html","css","js","svg","scss","json");
  $img_extensions =array("jpg","png","jpeg");
  $klein->onError(function ($klein, $err_msg) {
    $klein->service()->flash($err_msg,'error');
    $data = array('status' => 'error', 'type' => 'upload', 'html' => $err_msg);
    $klein->response()->json($data);
  });
  if ($_SESSION["username"]==$request->username) {
    $database = new Database();
    $db = $database->connect();

    $service->render('files/models/Project.php');
    $project = new Project($db);
    $result = $project->findByUrl($request->username,$request->project);
    if($result->rowCount() > 0) {
        $row = $result->fetch(PDO::FETCH_OBJ);
        if ($row->project_type_id=="1") {
          //Zip uploads
          $archive = \wapmorgan\UnifiedArchive\UnifiedArchive::open($request->files()->file["tmp_name"]);
          $extracted_size = $archive->getArchiveType();
          $files_lists = $archive->getFileNames();
          foreach ($files_lists as $files_list) {
            $imageFileType = strtolower(pathinfo($files_list,PATHINFO_EXTENSION));
            if (!in_array($imageFileType,$fill_extensions) && !in_array($imageFileType,$img_extensions)) {
              throw new Exception("Invalid file ".$imageFileType);
            }
          }
          if (!$archive->isFileExists('index.html')) {
            throw new Exception("index.html doesn't exist in root folder");
          }
          if($archive->extractFiles("files/projects/".$row->folder_name)){
            $url = "https://codetine.com/";
//            $url = "http://localhost/projectportfolio/files/projects/".$row->folder_name."/index.html";
// AIzaSyCtQS3GwtWzlOBEK9KpPQJ_wANKhwi7MuY
/*
            $screenCapture = new Capture($url);
            $screenCapture->setWidth(1366);
            $screenCapture->setHeight(768);
            $screenCapture->setClipWidth(1366);
            $screenCapture->setClipHeight(768);
            $screenCapture->setImageType('jpg');
            $screenCapture->save('files/img/thumbnails/abc3');
*/
            $service->render('files/models/ProjectArchive.php');
            $projectArchive = new ProjectArchive($db);
            $projectArchive->project_id=$row->project_id;
            $projectArchive->file_name=$request->files()->file["name"];
            $projectArchive->archive_type=$archive->getArchiveType();
            $projectArchive->archive_size=$archive->getArchiveSize();
            $projectArchive->files_count=$archive->countFiles();
            if ($projectArchive->create() && $project->uploaded($row->project_id)) {
              $data = array('status' => 'sucess', 'type' => 'upload', 'html' => 'Project successfully uploaded');
              $response->json($data);
              return;
            }
          }
        }
    }
  }
});
$klein->respond('POST',$file.'/[:username]/[:project]/upload/design', function ($request, $response, $service) use ($klein) {
  $service->render('files/includes/auth.php');
  $klein->onError(function ($klein, $err_msg) {
    $klein->service()->flash($err_msg,'error');
    $data = array('status' => 'error', 'type' => 'upload', 'html' => $err_msg);
    $klein->response()->json($data);
  });

  if ($_SESSION["username"]==$request->username) {
    $database = new Database();
    $db = $database->connect();

    $service->render('files/models/Project.php');
    $service->render('files/models/ProjectDesign.php');
    $project = new Project($db);
    $projectDesign = new ProjectDesign($db);
    $result = $project->findByUrl($request->username,$request->project);
    if($result->rowCount() > 0) {
      $row = $result->fetch(PDO::FETCH_OBJ);
      if ($row->project_type_id=="2") {
        if (isset($request->files()->file)) {
          $projectDesign->project_id=$row->project_id;
          $images=array();
          $i=1;
          foreach ($request->files()->file["tmp_name"] as $key => $tmp) {
            $image = new ImageResize($tmp);
            list($width, $height) = getimagesize($tmp);
            if ($width == $height) {
              $image->resize(400,400);
              $projectDesign->orientation="square";
            }elseif($width>$height){
              $image->resizeToHeight(400);
              $projectDesign->orientation="landscape";
            } else {
              $image->resizeToWidth(400);
              $projectDesign->orientation="portrait";
            }

            $imageFileType = strtolower(pathinfo($request->files()->file["name"][$key],PATHINFO_EXTENSION));
            $newImgName=$request->id($hash = true).$i.".".$imageFileType;
            $i++;
            $image->save("files/projects/".$row->folder_name."/".$newImgName);
            $projectDesign->name=$newImgName;
            $projectDesign->file_name=$request->files()->file['name'][$key];

            if ($projectDesign->create()) {
              array_push($images,$projectDesign->name);
            }
          }
          if ($row->uploaded=="0") {
            if ($project->uploaded($row->project_id)) {
              $row->uploaded=1;
            }
          }
          $data = array('status' => 'sucess', 'type' => 'design upload', 'html' => $images);
          $response->json($data);
          return;
        }
      }
    }
  }
});

$klein->respond('POST',$file.'/[:username]/[:project]/upload/video', function ($request, $response, $service) use ($klein) {
  $service->render('files/includes/auth.php');
  $klein->onError(function ($klein, $err_msg) {
    $klein->service()->flash($err_msg,'error');
    $klein->service()->back();
  });
  $service->addValidator('link', function ($str) {
    $rx = '~
  ^(?:https?://)?                           # protocol
   (?:www[.])?                              # sub-domain
   (?:youtube[.]com/watch[?]v=|youtu[.]be/) # Mandatory domain name (w/ query string in .com)
   ([^&]{11})                               # Video id of 11 characters as capture group 1
    ~x';
    return preg_match($rx, $str);
  });
  if ($_SESSION["username"]==$request->username) {
    $database = new Database();
    $db = $database->connect();

    $service->render('files/models/Project.php');
    $service->render('files/models/ProjectVideo.php');
    $project = new Project($db);
    $projectVideo = new ProjectVideo($db);
    $result = $project->findByUrl($request->username,$request->project);
    if($result->rowCount() > 0) {
      $row = $result->fetch(PDO::FETCH_OBJ);
      if ($row->project_type_id=="3") {
        $service->validateParam('video', 'Please enter a valid youtube url')->isLen(1, 100)->isLink();
        $link = $request->paramsPost()->video;
        $video_id = explode("?v=", $link); // For videos like http://www.youtube.com/watch?v=...
        if (empty($video_id[1]))
            $video_id = explode("/v/", $link); // For videos like http://www.youtube.com/watch/v/..

        $video_id = explode("&", $video_id[1]); // Deleting any other params
        $video_id = $video_id[0];

        $projectVideo->project_id=$row->project_id;
        $projectVideo->link=$link;
        $projectVideo->video_id=$video_id;

        if ($projectVideo->create()) {
          if ($row->uploaded=="0") {
            if ($project->uploaded($row->project_id)) {
              $row->uploaded=1;
            }
          }
          $klein->service()->flash('Link added successfully ','sucess');
          $klein->service()->back();
          return;
        }
      }
    }
  }
});

$klein->respond($file.'/[:username]/[:project]/delete/[i:id]', function ($request, $response, $service) use ($klein) {
  $service->render('files/includes/auth.php');
  $service->render('files/models/Project.php');
  $database = new Database();
  $db = $database->connect();
  $project= new Project($db);
  if ($_SESSION["username"]==$request->username) {
    $result = $project->findByUrl($request->username,$request->project);
    if($result->rowCount() > 0) {
      $row = $result->fetch(PDO::FETCH_OBJ);
      if ($row->project_type_id=="2") {
        $service->render('files/models/ProjectDesign.php');
        $projectDesign = new ProjectDesign($db);
        if($projectDesign->delete($request->id)){
          $Designresult=$projectDesign->find($row->project_id);
          if($Designresult->rowCount()==0){
            $project->notUploaded($row->project_id);
          }
          $klein->service()->flash('Design removed successfully ','sucess');
          $klein->service()->back();
        }
      }
      if ($row->project_type_id=="3") {
        $service->render('files/models/ProjectVideo.php');
        $projectVideo = new ProjectVideo($db);
        if($projectVideo->delete($request->id)){
          $videoresult=$projectVideo->find($row->project_id);
          if($videoresult->rowCount()==0){
            $project->notUploaded($row->project_id);
          }
          $klein->service()->flash('Video removed successfully ','sucess');
          $klein->service()->back();
        }
      }
    }
  }
});

?>
