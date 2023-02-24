<div class="layout2">
  <section id="profile">
  <div class="user-img">
    <img src="files/uploads/profiles/user.jpg" alt="">
  </div>
  <div class="user-info">
    <div class="info-holder">
      <h2 class="user-fullname"><?php echo $this->user->full_name; ?></h2>
      <div class="user-link"><a href="http://projectportfolio.cf/<?php echo $this->user->username; ?>" class="link">projectportfolio.cf/<?php echo $this->user->username; ?></a></div>
    </div>
  </div>
  <div class="edit-profile"><a href="profile.php" class="btn btn-primary">Edit Profile</a></div>
</section>
</div>
