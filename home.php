<?php

$project = new Project($this->db);
$projectresult=$project->allPublic();
$projectnum = $projectresult->rowCount();

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Project portfolio</title>
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.14.0/css/all.min.css" integrity="sha512-1PKOgIY59xJ8Co8+NE6FZ+LOAZKjy+KY8iq0G4B3CyeY6wYHN3yt9PW0XpSriVlkMXe40PTKnXrLnZ9+fkDaog==" crossorigin="anonymous" />
	<link rel="stylesheet" href="files/css/master.css">
	<link rel="stylesheet" href="files/css/user.css">
  <link rel="stylesheet" href="files/css/home.css">
</head>
<body>
	<?php
	require_once 'files/includes/header.php';
	?>
	<div class="profile-container">
		<div class="layout1" id="userview">
			<div class="layout-holder">

        <div id="application-main">
          <section id="project" class="section-box">
            <h3 class="section-heading">Projects</h3>
            <div class="content project-content">
							<?php
							if ($projectnum>0) {
								echo '<div class="project-holder">';
								while($projectdata = $projectresult->fetch(PDO::FETCH_OBJ)){
									?>
									<div class="project-box">
										<div class="project home-card">
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
                        <a href="<?php echo $projectdata->username.'/'.$projectdata->url; ?>" class="thumbnail-link"><span class="hide">View <?php echo $projectdata->title; ?></span></a>
                      </div>
											<div class="project-details">
												<div class="project-top">
													<div class="project-name">
														<a href="<?php echo $projectdata->username.'/'.$projectdata->url; ?>"><?php echo $projectdata->title; ?></a>
													</div>
												</div>
                        <div class="project-mid">
                          <div class="project-username">
                            <a href="<?php echo $projectdata->username; ?>"><?php echo $projectdata->username; ?></a>
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
                              echo $projectdata->views;
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
										We don't have any projects yet.
									</div>
								</div>
							<?php } ?>

            </div>
          </section>
        </div>
			</div>
		</div>
	</div>

</body>
</html>
