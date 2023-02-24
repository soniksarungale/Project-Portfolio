<?php
$hashed='';
$_SESSION['token'] = microtime();

if (defined("CRYPT_BLOWFISH") && CRYPT_BLOWFISH) {
    $salt = '$2y$11$' . substr(md5(uniqid(mt_rand(), true)), 0, 22);
    $hashed = crypt($_SESSION['token'], $salt);
}

$social_media = new SocialMedia($this->db);
$result=$social_media->find($this->user->user_id);
$num = $result->rowCount();
if($num > 0) {
  $socialmedia = $result->fetch(PDO::FETCH_OBJ);
}else{
  $social_media->user_id=$this->user->user_id;
  $social_media->create();
  header('Location: '.$this->user->username);
  exit();
}

$project = new Project($this->db);
$projectresult=$project->findByUser($this->user->user_id);
$projectnum = $projectresult->rowCount();

$skill = new Skill($this->db);
$skillresult=$skill->all();
$skillsdata=array();
while ($skills = $skillresult->fetch(PDO::FETCH_OBJ)) {
  $skillsdata[$skills->skill_type][$skills->skill_id]=$skills->name;
}

$userSkill = new UserSkill($this->db);
$userskillresult = $userSkill->findByUser($_SESSION["user_id"]);
$userSkillnum = $userskillresult->rowCount();
$skillsarray=array();
$userskillsdata=array();
if($userSkillnum > 0) {
  while($userskill = $userskillresult->fetch(PDO::FETCH_OBJ)){
    array_push($skillsarray,$userskill->skill_id);
    $userskillsdata[$userskill->type][$userskill->skill_id]=$userskill->name;
  }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?php echo $this->user->full_name; ?> | Project portfolio</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSriVlkMXe40PTKnXrLnZ9+fkDaog==" crossorigin="anonymous" />
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.26.0/slimselect.min.css">
	<link rel="stylesheet" href="files/css/master.css">
	<link rel="stylesheet" href="files/css/user.css">
	<link rel="stylesheet" href="files/css/loader.css">
</head>
<body>
	<?php
	require_once 'files/includes/header.php';
	?>
	<div class="profile-container">
		<div class="layout1">
			<div class="layout-holder">
				<section id="profile">
					<div class="user-display">
						<div class="user-img">
              <form method="post" action="<?php echo $this->user->username; ?>/update/profile/img" enctype="multipart/form-data">
                <label for="user-newimg" class="upload-label"><img src="<?php echo $this->user->username; ?>/p/avatar" alt=""></label>
  					    <input class="input-file hide" name="userimg" id="user-newimg" type="file"/>
                <input type="submit" class="btn btn-primary btn-block btn-caps hide upload-newimg" value="Upload">
              </form>
						</div>
					</div>
					<div class="user-details">
						<form>
              <div class="error error-warning"></div>
              <div class="sucess sucess-alert"></div>
							<div class="user-info info-input user-name-info">
								<input type="text" name="fullname" id="full_name" value="<?php echo $this->user->full_name; ?>">
							</div>
							<div class="user-info info-input user-link-info">
								<div class="domain">projectportfolio.cf/</div><input type="text" name="username" id="username" value="<?php echo $this->user->username; ?>">
							</div>
							<div class="user-info info-input bio-info">
								<textarea name="bio" id="bio" placeholder="Some interesting things about yourself..."><?php echo $this->user->bio; ?></textarea>
							</div>
							<div class="user-info info-input">
								<div class="icon"><i class="far fa-envelope"></i></div>
								<input type="email" name="email" id="email" disabled value="<?php echo $this->user->email; ?>">
							</div>
							<div class="user-info info-input">
								<div class="icon"><i class="far fa-building"></i></div>
								<input type="text" id="company" name="company" placeholder="Company" value="<?php echo $this->user->company; ?>">
							</div>
							<div class="user-info info-input">
								<div class="icon"><i class="fas fa-map-marker-alt"></i></div>
								<input type="text" id="location" name="location" placeholder="Location" value="<?php echo $this->user->location; ?>">
							</div>
							<div class="user-info info-input">
								<div class="icon"><i class="fas fa-link"></i></div>
								<input type="text" id="website" name="website" placeholder="Website" value="<?php echo $this->user->website; ?>">
							</div>
              <div class="user-info info-input">
								<div class="icon"><i class="fab fa-twitter"></i></div>
								<input type="text" id="twitter" name="twitter" placeholder="Twitter username" value="<?php echo $socialmedia->twitter; ?>">
							</div>
              <div class="user-info info-input">
								<div class="icon"><i class="fab fa-github"></i></div>
								<input type="text" id="github" name="github" placeholder="Github username" value="<?php echo $socialmedia->github; ?>">
							</div>
              <div class="user-info info-input">
								<div class="icon"><i class="fab fa-linkedin-in"></i></div>
								<input type="text" id="linkedin" name="linkedin" placeholder="Linkedin username" value="<?php echo $socialmedia->linkedin; ?>">
							</div>
              <div class="user-info info-input">
								<div class="icon"><i class="fab fa-codepen"></i></div>
								<input type="text" id="codepen" name="codepen" placeholder="Codepen username" value="<?php echo $socialmedia->codepen; ?>">
							</div>

							<div class="user-info align-right">
								<button type="button" class="btn btn-primary btn-sml btn-caps" onclick="updateProfile()">Update</button>
							</div>
						</form>
					</div>
				</section>
        <div id="application-main">
          <section id="project" class="section-box">
            <h3 class="section-heading">Projects</h3>
            <div class="content project-content">

              <?php
              if ($projectnum>0) {
                $projectVisitor=new ProjectVisitor($this->db);
                echo '<div class="newproject-btn">
                  <a href="'.$this->user->username.'/project/new" class="btn btn-primary btn-sml">New Project</a>
                </div>';
                echo '<div class="project-holder">';
                while($projectdata = $projectresult->fetch(PDO::FETCH_OBJ)){
                  ?>
                  <div class="project-box">
                    <div class="project">
                      <div class="project-thumbnail">
                        <?php if($projectdata->uploaded=="0"){?>
                          <img src="files/img/thumbnails/coming-soon.png" alt="<?php echo $projectdata->title; ?> coming soon" class="thumbnail-img">
                        <?php }elseif ($projectdata->project_type_id=="1") { ?>
                          <iframe src="files/projects/<?php echo $projectdata->folder_name; ?>/index.html" class="project-iframe" scrolling="no" tabindex="-1" title="100 divs - Dragon" loading="lazy" frameborder="0"></iframe>
                        <?php } elseif ($projectdata->project_type_id=="2") {
                          $projectDesign = new ProjectDesign($this->db);
                          $projectDesignresult=$projectDesign->findThumbnail($projectdata->project_id);
                          $designnum = $projectDesignresult->rowCount();
                          if ($designnum>0) {
                            $projectdesign = $projectDesignresult->fetch(PDO::FETCH_OBJ);
                          ?>
                            <img src="files/projects/<?php echo $projectdata->folder_name.'/'.$projectdesign->name;?>" class="thumbnail-img<?php if($projectdesign->orientation!="landscape"){echo " center-thumbnail"; }?>" alt="<?php echo $projectdata->title; ?> thumbnail">
                          <?php }
                         } elseif ($projectdata->project_type_id=="3") {
                        $projectVideo = new ProjectVideo($this->db);
                        $projectVideoresult=$projectVideo->findThumbnail($projectdata->project_id);
                        $videonum = $projectVideoresult->rowCount();
                        if ($videonum>0) {
                          $projectvideo = $projectVideoresult->fetch(PDO::FETCH_OBJ);
                        ?>
                        <img src="https://img.youtube.com/vi/<?php echo $projectvideo->video_id; ?>/hqdefault.jpg" alt="<?php echo $projectdata->title; ?> coming soon" class="thumbnail-img">
                        <?php }
                        } ?>
                        <a href="<?php echo $this->user->username.'/'.$projectdata->url; ?>" class="thumbnail-link"><span class="hide">View <?php echo $projectdata->title; ?></span></a>
                      </div>
                      <div class="project-details">
                        <div class="project-top">
                          <div class="project-name">
                            <a href="<?php echo $this->user->username.'/'.$projectdata->url; ?>"><?php echo $projectdata->title; ?></a>
                          </div>

                          <div class="bookmark-icon">
                            <a href="<?php echo $this->user->username.'/'.$projectdata->url; ?>/edit"><i class="far fa-edit"></i></a>
                          </div>
                        </div>
                        <div class="project-bottom">
                          <div class="project-type-box">
                            <div class="project-type project-type-<?php echo $projectdata->project_type_id; ?>">
                              <?php echo $projectdata->project_type; ?>
                            </div>
                          </div>
                          <div class="project-status">
                            <?php if($projectdata->public=="0"){ ?>
                            <div class="private status">
                              <div class="icon">
                                <i class="fas fa-lock"></i>
                              </div>
                            </div>
                            <?php } ?>
                            <div class="views status">
                              <div class="icon">
                                <i class="far fa-eye"></i>
                              </div>
                              <div class="number">
                                <?php
                                $viewresult=$projectVisitor->views($projectdata->project_id);
                                $viewrownum = $viewresult->rowCount();
                                if($viewrownum > 0) {
                                  $projectview = $viewresult->fetch(PDO::FETCH_OBJ);
                                  echo $projectview->views;
                                }else{
                                  echo "0";
                                }
                                ?>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                <?php
                }
                echo '</div>';
              }else{?>

                <div class="newproject">
                  <div class="newproject-text">
                    You don't have any projects yet.
                  </div>
                  <div class="uploadproject-btn">
                    <a href="<?php echo $this->user->username; ?>/project/new" class="btn btn-primary">Upload Project</a>
                  </div>
                </div>
              <?php } ?>
            </div>
          </section>
          <section id="skills" class="section-box">
            <h3 class="section-heading">Skills</h3>
            <div class="content skills-content">
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
                  echo '</div><br>';
                }
              }
              ?>
              <form class="skills" action="<?php echo $this->user->username?>/skills/save" method="post">
                <select id="skills-box" multiple name="skills[]">
                  <?php foreach ($skillsdata as $skilltype => $skillvalue): ?>
                    <optgroup label="<?php echo $skilltype; ?>">
                      <?php foreach ($skillvalue as $skillid => $skillname): ?>
                        <option value="<?php echo $skillid; ?>"<?php
                        if (in_array($skillid,$skillsarray)) {
                          echo " selected";
                        }
                        ?>><?php echo $skillname; ?></option>
                      <?php endforeach; ?>
                    </optgroup>
                  <?php endforeach; ?>
                </select>
                <div class="align-right skill-btn">
                  <input type="submit" value="Save" class="btn btn-primary btn-sml">
                </div>
                <?php
                //var_dump($userskillsdata);
                ?>
              </form>
            </div>
          </section>
        </div>
			</div>
		</div>
	</div>
	<div class="loader">
												<div class="preloader"><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div></div>
										</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/slim-select/1.26.0/slimselect.min.js"></script>
<script type="text/javascript">
new SlimSelect({
select: '#skills-box'
});
	function updateProfile() {
    const full_name= $.trim($("#full_name").val());
    const username= $.trim($("#username").val());
    const bio = $.trim($("#bio").val());
    const company = $.trim($("#company").val());
    const location = $.trim($("#location").val());
    const website = $.trim($("#website").val());
    const twitter = $.trim($("#twitter").val());
    const github = $.trim($("#github").val());
    const linkedin = $.trim($("#linkedin").val());
    const codepen = $.trim($("#codepen").val());
    if (full_name == "") {
      $(".user-details .error").css("display","block");
      $(".user-details .error").html("Full name is required");
      $("#full_name").css("border-color","red");
      return;
    }
    $(".user-details .error").css("display","none");
    $(".user-details .error").html("");
    $("#full_name").css("border-color","#c6c6c6");

    if (username == "") {
      $(".user-details .error").css("display","block");
      $(".user-details .error").html("Username is required");
      $("#username").css("border-color","red");
      return;
    }
    $(".user-details .error").css("display","none");
    $(".user-details .error").html("");
    $("#username").css("border-color","#c6c6c6");

    $(".loader").fadeIn();
    var session = "<?php echo $hashed;?>";
    $.post("<?php echo $this->user->username; ?>/update/profile" , {
        full_name:full_name,
        username:username,
        bio:bio,
        company:company,
        location:location,
        website:website,
        twitter:twitter,
        github:github,
        linkedin:linkedin,
        codepen:codepen,
        usession: session
    }).done(
    function(result){
      var json = result;
      if (json.status=="sucess") {
        $(".user-details .error").css("display","none");
        $(".user-details .error").html("");
        $(".loader").fadeOut();
        if (json.type=="username") {
          window.location.href =json.html;
        }else{
          $(".user-details .sucess").css("display","block");
          $(".user-details .sucess").html(json.html);
        }
      }else{
        $(".user-details .sucess").css("display","none");
        $(".user-details .error").css("display","block");
        $(".user-details .error").html(json.html);
        $(".loader").fadeOut();
      }
    });
  }
  function readURL(input) {
    if (input.files && input.files[0]) {
    const reader = new FileReader();

    reader.onload = function(e) {
      $('.upload-newimg').fadeIn();
      $('.user-display .user-img img').attr('src', e.target.result);
    }

    reader.readAsDataURL(input.files[0]);
    }
    }

    $("#user-newimg").change(function() {
    readURL(this);
    });

function openCity(evt, cityName) {
  var i, tabcontent, tablinks;
  tabcontent = document.getElementsByClassName("tabcontent");
  for (i = 0; i < tabcontent.length; i++) {
    tabcontent[i].style.display = "none";
  }
  tablinks = document.getElementsByClassName("tablinks");
  for (i = 0; i < tablinks.length; i++) {
    tablinks[i].className = tablinks[i].className.replace(" active", "");
  }
  document.getElementById(cityName).style.display = "block";
  evt.currentTarget.className += " active";
}

// Get the element with id="defaultOpen" and click on it
document.getElementById("defaultOpen").click();
</script>
</body>
</html>
