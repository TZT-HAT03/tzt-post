<?php if (isLoggedIn()) { ?>
<ul id="profiel-dropdown" class="dropdown-content">
	<li><a href="pakket-panel">Pakket Panel</a></li>
	<li><a href="uitloggen">Uitloggen</a></li>
</ul>
<ul id="profiel-dropdown-mobile" class="dropdown-content">
	<li><a href="pakket-panel">Pakket Panel</a></li>
	<li><a href="uitloggen">Uitloggen</a></li>
</ul>
<?php } ?>
<header>
	<div class="<?= isset($nav_fixed)?"navbar-fixed fixed":"" ?>">
		<nav id="main_nav">
			<div class="nav-wrapper">
				<div class="container">
					<a href="home" class="brand-logo">
						<div class="logo-wrapper valign-wrapper">
							<img src="img/logo/tzt_logo.png">
						</div>
					</a>
					<a href="#!" data-activates="mobile-menu" class="button-collapse"><i class="material-icons">menu</i></a>
					<ul class="right hide-on-med-and-down" id="desktop-menu">
						<li class="waves-effect waves-dark <?php if ($page_name == "home") { echo "active"; } ?>">
							<a href="home">Home</a>
						</li>
						<li class="waves-effect waves-dark <?php if ($page_name == "verzenden") { echo "active"; } ?>">
							<a href="verzenden">Verzenden</a>
						</li>
						<li class="waves-effect waves-dark <?php if ($page_name == "track&trace") { echo "active"; } ?>">
							<a href="track-trace">Track & Trace</a>
						</li>
						<li class="waves-effect waves-dark <?php if ($page_name == "treinkoeriers") { echo "active"; } ?>">
							<a href="treinkoeriers">Treinkoeriers</a>
						</li>
						<li class="waves-effect waves-dark <?php if ($page_name == "contact") { echo "active"; } ?>">
							<a href="contact">Contact</a>
						</li>
						<?php
						if (isLoggedIn()) { ?>
						<li class="<?php if ($page_name == "profiel") { echo "active"; } ?>">
      						<a class="waves-effect waves-dark nav-dropdown" data-activates="profiel-dropdown">Account<i class="material-icons right">arrow_drop_down</i></a>
						</li>
						<?php
						} else { ?>
						<li>
							<a href="login" class="waves-effect waves-light btn tertiary">Inloggen</a>
						</li>
						<?php
						} ?>

					</ul>
					<ul class="side-nav" id="mobile-menu">
						<li class="<?php if ($page_name == "home") { echo "active"; } ?>">
							<a href="home">Home</a>
						</li>
						<li class="<?php if ($page_name == "verzenden") { echo "active"; } ?>">
							<a href="verzenden">Verzenden</a>
						</li>
						<li class="<?php if ($page_name == "track&trace") { echo "active"; } ?>">
							<a href="track-trace">Track & trace</a>
						</li>
						<li class="<?php if ($page_name == "treinkoeriers") { echo "active"; } ?>">
							<a href="treinkoeriers">Treinkoeriers</a>
						</li>
						<li class="<?php if ($page_name == "contact") { echo "active"; } ?>">
							<a href="contact">Contact</a>
						</li>
						<?php
						if (isLoggedIn()) { ?>
						<li class="<?php if ($page_name == "profiel") { echo "active"; } ?>">
      						<a class="nav-dropdown" data-activates="profiel-dropdown-mobile">Uw account<i class="material-icons right">arrow_drop_down</i></a>
						</li>
						<?php
						} else { ?>
						<li>
							<a href="login">Inloggen</a>
						</li>
						<?php
						} ?>

					</ul>
				</div>
			</div>
		</nav>
	</div>
</header>
