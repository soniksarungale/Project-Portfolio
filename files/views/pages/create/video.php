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
  <title>New video | Project portfolio</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSriVlkMXe40PTKnXrLnZ9+fkDaog==" crossorigin="anonymous" />
  <link rel="stylesheet" href="../files/css/master.css">
  <link rel="stylesheet" href="../files/css/newproject.css">

</head>
<body>
  <?php
  require_once 'files/includes/header.php';
  ?>
  <div class="sml-container">
    <section id="newproject" class="newvideo">
      <div class="op-heading">
        <div class="back">
          <a href="../<?php echo $this->user->username; ?>"><i class="fas fa-chevron-left"></i></a>
        </div>
        <h2>Upload your youtube video link</h2>
      </div>
      <?php
      if (isset($this->msg["error"])) {
        foreach ($this->msg["error"] as $error) {
          echo '<div class="error error-warning show">';
          echo $error;
          echo '</div><br>';
        }
      }
      if (isset($this->msg["sucess"])) {
        foreach ($this->msg["sucess"] as $sucess) {
          echo '<div class="sucess sucess-alert show">';
          echo $sucess;
          echo '</div>';
        }
      }
      ?>
      <div class="newproject">
        <div class="uploadproject-btn">
          <a href="#" id="newvideo-btn" class="btn btn-primary btn-sml">Upload Link</a>
        </div>
      </div>
    </section>
  </div>
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
              <div class="align-right card-remove">
                <a href="<?php echo $this->project->url.'/delete/'.$projectvideo->project_video_id;?>" class="btn btn-danger">Remove</a>
              </div>
            </div>
          <?php } } ?>
        </div>
      </div>
    </div>
  </section>
</div>

<!-- The Modal -->
<div id="modal" class="modal">

  <!-- Modal content -->
  <div class="modal-content">
    <div class="modal-header">
      <span class="close">&times;</span>
      <h2>Upload youtube video link</h2>
    </div>
    <form action="<?php echo $this->project->url; ?>/upload/video" method="post">
    <div class="modal-body">
      <div class="video-input">
        <input type="url" id="video" name="video" placeholder="Paste your youtube video link here" required>
      </div>
    </div>
    <div class="modal-footer align-right">
      <input type="submit" value="Upload" class="btn btn-primary btn-sml">
    </div>
    </form>
  </div>

</div>


<script type="text/javascript">
var modal = document.getElementById("modal");
var btn = document.getElementById("newvideo-btn");
var span = document.getElementsByClassName("close")[0];
btn.onclick = function() {
  modal.style.display = "block";
}
span.onclick = function() {
  modal.style.display = "none";
}
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}
</script>

</body>
</html>
