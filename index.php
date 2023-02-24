<?php
session_start();

require_once __DIR__ . '/vendor/autoload.php';
require_once 'files/models/Database.php';
require_once 'files/models/User.php';
require_once 'files/models/Visitor.php';
require_once 'files/includes/functions.php';

use \Gumlet\ImageResize;

$klein = new \Klein\Klein();
$file="/projectportfolio";

$klein->respond('*', function ($request, $response, $service, $app) {
  $app->file="/projectportfolio";
});

$klein->respond($file.'/', function ($request, $response, $service) {
  if (isset($_SESSION["logged"])) {
    $database = new Database();
    $db = $database->connect();
    $user=json_decode(json_encode($_SESSION));
    $service->render('files/models/Project.php');
    $service->render('files/models/ProjectDesign.php');
    $service->render('files/models/ProjectVideo.php');
    $service->render('home.php', array('db'=>$db,'user'=>$user));
    return;
  }
  $service->render('landing.php');
});
$klein->respond($file.'/index.php', function ($request, $response, $service, $app) {
  $response->redirect($app->file.'/', $code = 302);
  return;
});
$klein->respond($file.'', function ($request, $response, $service, $app) {
  $response->redirect($app->file.'/', $code = 302);
});
$klein->respond('*', function ($request, $response, $service, $app) {
  $database = new Database();
  $db = $database->connect();
  $visitor=new Visitor($db);
  $visitor->ip_address=$request->ip();

  if ($request->ip()!="UNKNOWN" && $request->ip()!="::1" && $request->ip()!="127.0.0.1") {
       $json  = file_get_contents("http://ipinfo.io/$request->ip/geo");
       $json  =  json_decode($json ,true);
       $visitor->country="";
       $visitor->state="";
       $visitor->city="";
       if (isset($json['country'])) {
           $visitor->country =  $json['country'];
       }
       if (isset($json['region'])) {
          $visitor->state= $json['region'];
       }
       if (isset($json['city'])) {
           $visitor->city = $json['city'];
       }
  }
  else{
      $visitor->country=NULL;
      $visitor->state=NULL;
      $visitor->city=NULL;
  }
  $visitor->browser= browser();
  $visitor->browser_version=browserVer($visitor->browser);
  $mb=mobile();
  $pc=computer();

  if($mb !="")
  {
  $visitor->os= $mb;
  $visitor->device="mobile";
  }
  elseif($pc !=""){
      $visitor->os= $this->computer();
      $visitor->device= "computer";
  }
  else{
      $visitor->os=null;
      $visitor->device=null;
  }
  $visitor->page=ucfirst(pathinfo($_SERVER['PHP_SELF'], PATHINFO_FILENAME));
  $visitor->url=$request->uri();
  $visitor->reference=$request->headers()->Referer;
  if($visitor->create()){
    $app->visitor_id=$visitor->visitor_id;
  }

});

$klein->respond($file.'/cu/logout/user', function ($request, $response, $service, $app) {

  if (isset($_SESSION["logged"])) {
    session_unset();
    session_destroy();
    $response->redirect($app->file.'/');
}
  return;
});


require_once 'files/includes/routes/user.php';
require_once 'files/includes/routes/project.php';
require_once 'files/includes/routes/project_details.php';

$klein->dispatch();



?>
