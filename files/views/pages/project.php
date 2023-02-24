<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title><?php echo $this->project->title;?></title>
    <style>
 html, body, iframe { height:100%; width:100%; margin:0; border:0; display:block }
</style>
  </head>
  <body>
    <iframe src="../files/projects/<?php echo $this->project->folder_name; ?>/index.html"></iframe>
  </body>
</html>
