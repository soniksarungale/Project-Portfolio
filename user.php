<?php
if (!isset($this)) {
	header('Location: index.php');
}

$social_media = new SocialMedia($this->db);
$result=$social_media->find($this->user->user_id);
$socialnum = $result->rowCount();
if($socialnum > 0) {
  $socialmedia = $result->fetch(PDO::FETCH_OBJ);
}

$project = new Project($this->db);
$projectresult=$project->findByUserPublic($this->user->user_id);
$projectnum = $projectresult->rowCount();

$userSkill = new UserSkill($this->db);
$userskillresult = $userSkill->findByUser($this->user->user_id);
$userSkillnum = $userskillresult->rowCount();
$userskillsdata=array();
if($userSkillnum > 0) {
  while($userskill = $userskillresult->fetch(PDO::FETCH_OBJ)){
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
	<link rel="stylesheet" href="files/css/master.css">
	<link rel="stylesheet" href="files/css/user.css">
	<link rel="stylesheet" href="files/css/loader.css">
</head>
<body>
	<?php
	require_once 'files/includes/header.php';
	?>
	<div class="profile-container">
		<div class="layout1" id="userview">
			<div class="layout-holder">
				<section id="profile">
					<div class="user-display">
						<div class="user-img">
              <img src="<?php echo $this->user->username; ?>/p/avatar" alt="">
						</div>
					</div>
					<div class="user-details">
							<div class="user-info user-name-info">
								<h2 class="info"><?php echo $this->user->full_name; ?></h2>
							</div>
							<div class="user-info user-link-info profile-link">
								<a class="info" href="http://localhost/projectportfolio/<?php echo $this->user->username; ?>" target="_blank">http://localhost/projectportfolio/<?php echo $this->user->username; ?></a>
							</div>
							<div class="user-info bio-info">
								<div class="info"><?php echo $this->user->bio; ?></div>
							</div>
							<div class="user-info profile-link">
								<div class="icon"><i class="far fa-envelope"></i></div>
								<a class="info" href="mailto:<?php echo $this->user->email; ?>" target="_blank"><?php echo $this->user->email; ?></a>
							</div>
							<?php if ($this->user->company) { ?>
							<div class="user-info">
								<div class="icon"><i class="far fa-building"></i></div>
								<div class="info"><?php echo $this->user->company; ?></div>
							</div>
							<?php } ?>
							<?php	if ($this->user->location) { ?>
							<div class="user-info">
								<div class="icon"><i class="fas fa-map-marker-alt"></i></div>
								<div class="info"><?php echo $this->user->location; ?></div>
							</div>
							<?php } ?>
							<?php	if ($this->user->website) { ?>
							<div class="user-info profile-link">
								<div class="icon"><i class="fas fa-link"></i></div>
								<a class="info" href="<?php echo $this->user->website; ?>" target="_blank"><?php echo $this->user->website; ?></a>
							</div>
							<?php } ?>
							<?php	if ($socialmedia->twitter) { ?>
              <div class="user-info profile-link">
								<div class="icon"><i class="fab fa-twitter"></i></div>
								<a class="info" href="https://twitter.com/<?php echo $socialmedia->twitter; ?>" target="_blank">@<?php echo $socialmedia->twitter; ?></a>
							</div>
							<?php } ?>
							<?php	if ($socialmedia->github) { ?>
              <div class="user-info profile-link">
								<div class="icon"><i class="fab fa-github"></i></div>
								<a class="info" href="https://github.com/<?php echo $socialmedia->github; ?>" target="_blank">@<?php echo $socialmedia->github; ?></a>
							</div>
							<?php } ?>
							<?php	if ($socialmedia->linkedin) { ?>
              <div class="user-info profile-link">
								<div class="icon"><i class="fab fa-linkedin-in"></i></div>
								<a class="info" href="https://linkedin.com/in/<?php echo $socialmedia->linkedin; ?>" target="_blank">@<?php echo $socialmedia->linkedin; ?></a>
							</div>
							<?php } ?>
							<?php	if ($socialmedia->codepen) { ?>
              <div class="user-info profile-link">
								<div class="icon"><i class="fab fa-codepen"></i></div>
								<a class="info" href="https://codepen.io/<?php echo $socialmedia->codepen; ?>" target="_blank">@<?php echo $socialmedia->codepen; ?></a>
							</div>
							<?php } ?>
					</div>
				</section>
        <div id="application-main">
          <section id="project" class="section-box">
            <h3 class="section-heading">Projects</h3>
            <div class="content project-content">
							<?php
							if ($projectnum>0) {
								$projectVisitor=new ProjectVisitor($this->db);
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
												</div>
												<div class="project-bottom">
													<div class="project-type-box">
														<div class="project-type project-type-<?php echo $projectdata->project_type_id; ?>">
															<?php echo $projectdata->project_type; ?>
														</div>
													</div>
													<div class="project-status">
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
										<?php echo $this->user->full_name?> don't have any projects yet.
									</div>
								</div>
							<?php } ?>

            </div>
          </section>
					<?php if(!empty($userskillsdata)){ ?>
						<section id="skills" class="section-box">
							<h3 class="section-heading">Skills</h3>
							<div class="content skills-content">
								<div class="display-skills">
									<?php	foreach ($userskillsdata as $skillsheading => $skillvalue) { ?>
										<div class="skills-box">
											<div class="skills-heading">
												<h3><?php echo $skillsheading; ?></h3>
											</div>
											<ol class="skill-category-list">
												<?php	foreach ($skillvalue as $key => $value) { ?>
												<li class="skill-list"><?php echo $value; ?></li>
												<?php }	?>
											</ol>
										</div>
									<?php }	?>
								</div>

							</div>
						</section>
          <?php } ?>
        </div>
			</div>
		</div>
	</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

</body>
</html>
