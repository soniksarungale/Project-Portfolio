<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>New Project | Project portfolio</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSriVlkMXe40PTKnXrLnZ9+fkDaog==" crossorigin="anonymous" />
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.26.0/slimselect.min.css">
<link rel="stylesheet" href="../files/css/dropzone.css" />
  <link rel="stylesheet" href="../files/css/master.css">
  <link rel="stylesheet" href="../files/css/newproject.css">
</head>
<body>
  <?php
  require_once 'files/includes/header.php';
  ?>
  <div class="sml-container">
    <section id="newproject">
      <div class="op-heading">
        <div class="back">
          <a href="../<?php echo $this->user->username; ?>"><i class="fas fa-chevron-left"></i></a>
        </div>
      <h2>Upload zip/rar file</h2>
    </div>
      <p>Make sure your zip/rar has index.html in root directory</p>
      <?php
      if (isset($this->msg["error"])) {
        foreach ($this->msg["error"] as $error) {
          echo '<div class="error error-warning show">';
          echo $error;
          echo '</div>';
        }
      }
      ?>
<form action="<?php echo $this->project->url; ?>/upload/frontend" class="dropzone" id="filedrop">
  <div class="fallback">
    <input name="file" type="file" />
  </div>
</form>
      </form>
    </section>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="../files/js/dropzone.js"></script>
<script type="text/javascript">
Dropzone.options.filedrop = {
maxFiles: 1,
maxFilesize: 10,
acceptedFiles:'.zip,.rar',
init: function () {
    var totalFiles = 0,
        completeFiles = 0;
    this.on("addedfile", function (file) {
        totalFiles += 1;
    });
    this.on("removed file", function (file) {
        totalFiles -= 1;
        console.log('hi');
    });
    this.on("complete", function (file) {
        completeFiles += 1;
        if (completeFiles === totalFiles) {
        }
    });
    this.on("success", function(file, response) {
      location = location;
    });
}
};
</script>
</body>
</html>
