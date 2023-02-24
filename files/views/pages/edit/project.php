<?php
$project_type = new ProjectType($this->db);
$result=$project_type->all();
$projecttypes = $result->fetchAll(PDO::FETCH_OBJ);

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>New Project | Project portfolio</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSriVlkMXe40PTKnXrLnZ9+fkDaog==" crossorigin="anonymous" />
  <link rel="stylesheet" href="../../files/css/master.css">
  <link rel="stylesheet" href="../../files/css/newproject.css">
</head>
<body>
  <?php
  require_once 'files/includes/header.php';
  ?>
  <div class="sml-container">
    <section id="newproject">
      <div class="op-heading">
        <div class="back">
          <a href="../../<?php echo $this->user->username; ?>"><i class="fas fa-chevron-left"></i></a>
        </div>
        <h2>Update <?php echo $this->project->title;?></h2>
        <div class="delete">
          <a href="#" id="delete-btn"><i class="far fa-trash-alt"></i></a>
        </div>
      </div>
      <form action="update" method="post">
        <?php
        if (isset($this->msg["error"])) {
          foreach ($this->msg["error"] as $error) {
            echo '<div class="error error-warning show">';
            echo $error;
            echo '</div>';
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
        <div class="input-box">
          <label for="projectname">Project Name</label>
          <input type="text" id="projectname" name="projectname" placeholder="Your project name..." required value="<?php echo $this->project->title;?>">
        </div>
        <div class="input-box">
          <label for="projectlink">Project Link</label>
          <input type="text" id="projectlink" name="link" value="projectportfolio.cf/<?php echo $this->user->username.'/'.$this->project->url; ?>" disabled required>
        </div>
        <div class="input-box radio-box">
          <label>Visibility</label>
          <div class="input-group">
            <input type="radio" required name="visibility" value="1" id="public"<?php
            if ($this->project->public=="1") {
              echo " checked";
            }
            ?>><label for="public"><i class="fas fa-lock-open"></i> Public</label>
          </div>
          <div class="input-group">
            <input type="radio" required name="visibility" value="0" id="private"<?php
            if ($this->project->public=="0") {
              echo " checked";
            }
            ?>><label for="private"><i class="fas fa-lock"></i> Private</label>
          </div>
        </div>
        <div class="input-box">
          <label for="description">Description (optional)</label>
          <textarea type="text" id="description" name="description" placeholder="About project..."><?php echo $this->project->description; ?></textarea>
        </div>
        <div class="input-box align-right">
          <button type="submit" name="button" class="btn btn-primary btn-caps">Update</button>
        </div>
      </form>
    </section>
  </div>

  <div id="delete-modal" class="modal">
    <div class="modal-content">
      <div class="modal-header">
        <span class="close">&times;</span>
        <h2>Are you absolutely sure?</h2>
      </div>
      <div class="modal-body">
        <p>This action cannot be undone. This will permanently delete the <?php echo $this->user->username.'/'.$this->project->url;?> project and remove all files. </p>
      </div>
      <div class="modal-footer align-right">
        <a href="delete" class="btn btn-danger btn-sml">Delete</a>
      </div>
    </div>

  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script type="text/javascript">
  const link="projectportfolio.cf/<?php echo $this->user->username; ?>/";
    $('#projectname').on('input', function(){
//abc-sakj
      let projname=$.trim($("#projectname").val()).replace(/[^a-zA-Z0-9- ]/g, "").replace(/\s/g, "-").toLowerCase();
      $('#projectlink').val(link+projname);
    });

  var modal = document.getElementById("delete-modal");
  var btn = document.getElementById("delete-btn");
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
