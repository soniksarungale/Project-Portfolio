<?php
$projectVideo = new ProjectVideo($this->db);
$result=$projectVideo->find($this->project->project_id);
$num = $result->rowCount();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title><?php echo $this->project->title;?> | Project Portfolio</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSriVlkMXe40PTKnXrLnZ9+fkDaog==" crossorigin="anonymous" />
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.26.0/slimselect.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.css" />
  <link rel="stylesheet" href="../files/css/master.css">

</head>
<body>
  <?php
  require_once 'files/includes/header.php';
  ?>
  <div class="container">
    <section id="video">
      <div class="video-flex">
        <div class="video-holder">
          <div class="grid">
            <?php
            if ($num>0) {
              while($projectvideo = $result->fetch(PDO::FETCH_OBJ)){
              ?>
              <div class="grid-item">
                <div class="embed-container">
                  <iframe src="https://www.youtube.com/embed/<?php echo $projectvideo->video_id; ?>" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                </div>
              </div>
            <?php } } ?>
          </div>
        </div>
      </div>
    </section>
  </div>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://unpkg.com/masonry-layout@4/dist/masonry.pkgd.min.js"></script>
<script src="https://npmcdn.com/imagesloaded@4.1/imagesloaded.pkgd.min.js"></script>
<script src="https://cdn.jsdelivr.net/gh/fancyapps/fancybox@3.5.7/dist/jquery.fancybox.min.js"></script>
<script type="text/javascript">

var $grid = $('.grid').masonry({
itemSelector: '.grid-item',
horizontalOrder: true
});

$grid.imagesLoaded().progress( function() {
$grid.masonry('layout');
});


    </script>
</body>
</html>
