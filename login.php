<?php
session_start();
date_default_timezone_set("Europe/Amsterdam");

include "includes/database.php";
include "includes/functions.php";

if (isLoggedIn()) {
	header('location: profiel');
}


// pageinfo
$page_name = "login";

?>

<!DOCTYPE html>
<html>
<!-- HEAD -->
<?php include "includes/head.php"; ?>

<body>

<!-- HEADER -->
<?php include "includes/header.php" ?>

<main class="pagewrapper">

	<div class="container">
		<div class="center-card">
			<div class="card" id="login-card">
				<div class="card-content">
					<span class="card-title">Inloggen</span>

					<!-- Na registreren -->
					<?php if (isset($_SESSION["login-message"])) { ?>
						<p><?= $_SESSION["login-message"] ?></p>
					<?php } ?>

					<?php if (isset($_SESSION["errorMessage"])) { ?>
						<div class="valign-wrapper">
							<i class="material-icons red-text">&#xE000;</i><p class="red-text"><?php echo $_SESSION["errorMessage"] ?></br>
							<?= isset($loginAllowedErrorMessage) ? $loginAllowedErrorMessage:""; ?>
							</p>
						</div>
					<?php } ?>

					<div class="valign-wrapper">
						<form class="valign" action="login_verwerk.php" method="post">
							<div class="row">
								<div class="input-field col s12">
									<input type="text" id="inlognaam" name="email" autofocus>
									<label for="inlognaam">Email</label>
								</div>
								<div class="input-field col s12">
									<input type="password" id="wachtwoord" name="password">
									<label for="wachtwoord">Wachtwoord</label>
								</div>
								<div class="input-field col s12">
									<button type="submit" name="submit" class="btn primary waves-effect waves-light" value="Inloggen">Log in</button>
								</div>
								<div class="input-field col s12">
									<p class="grey-text right">Nog geen account? <a href="treinkoeriers">Word treinkoerier!</a></p>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

</main>

<?php
include "includes/footer.php";
unset($_SESSION["errorMessage"]);
unset($_SESSION["login-message"]);
?>

</body>
</html>