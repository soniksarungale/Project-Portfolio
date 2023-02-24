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
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.26.0/slimselect.min.css">
  <link rel="stylesheet" href="../../files/css/master.css">
  <link rel="stylesheet" href="../../files/css/newproject.css">
</head>
<body>
  <?php
  require_once 'files/includes/header.php';
  ?>
  <div class="sml-container">
    <section id="newproject">
      <h2>Create a new project</h2>
      <form action="create" method="post">
        <?php
        if (isset($this->msg["error"])) {
          foreach ($this->msg["error"] as $error) {
            echo '<div class="error error-warning show">';
            echo $error;
            echo '</div>';
          }
        }
        ?>
        <div class="input-box">
          <label for="projectname">Project Name</label>
          <input type="text" id="projectname" name="projectname" placeholder="Your project name..." required>
        </div>
        <div class="input-box">
          <label for="projectlink">Project Link</label>
          <input type="text" id="projectlink" name="link" value="projectportfolio.cf/<?php echo $this->user->username; ?>/" disabled required>
        </div>
        <div class="input-box">
          <label for="projecttype">Project Type</label>
          <select required name="type" id="projecttype">
            <?php
            foreach ($projecttypes as $projecttype) {
              echo '<option value="'.$projecttype->project_type_id.'">'.$projecttype->type.'</option>';
            }
            ?>
          </select>
        </div>
        <div class="input-box radio-box">
          <label>Visibility</label>
          <div class="input-group">
            <input type="radio" required name="visibility" value="1" id="public" checked><label for="public"><i class="fas fa-lock-open"></i> Public</label>
          </div>
          <div class="input-group">
            <input type="radio" required name="visibility" value="0" id="private"><label for="private"><i class="fas fa-lock"></i> Private</label>
          </div>
        </div>
        <div class="input-box">
          <label for="description">Description (optional)</label>
          <textarea type="text" id="description" name="description" placeholder="About project..."></textarea>
        </div>
        <div class="input-box align-right">
          <button type="submit" name="button" class="btn btn-primary btn-caps">Create</button>
        </div>
      </form>
    </section>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.26.0/slimselect.min.js"></script>
<script type="text/javascript">
  const link="projectportfolio.cf/<?php echo $this->user->username; ?>/";
    $('#projectname').on('input', function(){
//abc-sakj
      let projname=$.trim($("#projectname").val()).replace(/[^a-zA-Z- ]/g, "").replace(/\s/g, "-").toLowerCase();
      $('#projectlink').val(link+projname);
    });
    new SlimSelect({
  select: '#projectlanguages'
})

  </script>
</body>
</html>
