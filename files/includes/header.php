<?php
$root="http://localhost/projectportfolio/";
?>
<header>
	<div class="header-container">
		<div class="logo"><a href="<?php echo $root; ?>"><h1 class="logo-color">Project<span>Portfolio</span></h1></a></div>

		<nav class="menu">
			<ul>
				<li class="dropdown"><a href="" class="dropbtn menu-link"><i class="fas fa-bars"></i></a>
					<div class="dropdown-content">
						<?php if (isset($_SESSION["logged"])) { ?>
							<a href="<?php echo $root.$_SESSION["username"]; ?>">Profile</a>
							<a href="<?php echo $root; ?>">Explore</a>
							<a href="<?php echo $root.$_SESSION["username"].'/project/new'; ?>">New Project</a>
							<a href="<?php echo $root.'cu/logout/user'; ?>">Logout</a>
						<?php }else{ ?>
							<a href="<?php echo $root; ?>">Login</a>
							<a href="<?php echo $root; ?>">Signup</a>
						<?php } ?>
				  </div>
				</li>
			</ul>
		</nav>
	</div>
</header>
