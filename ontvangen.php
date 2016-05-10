<?php session_start(); ?>

<!DOCTYPE html>
<html>

<?php
include "includes/functions.php";
include "includes/database.php";
include "includes/head.php";

// pageinfo
$page_name = "ontvangen";
?>

<body>

<!-- HEADER -->
<?php include "includes/header.php" ?>

<main class="pagewrapper">

	<div class="section">
		<div class="container">
			<div class="center-card">
				<div class="card" id="login-card">
					<div class="card-content">
						<span class="card-title">Track & trace</span>
						<p>Deze gegevens heeft u per mail ontvangen wanneer er een pakketje naar uw adres is gestuurd. De trackingcode begint met twee hoofdletters.</p>
						<br>
						<div class="valign-wrapper">
							<form class="valign" action="">
								<div class="row">
									<div class="input-field col s12">
										<input type="text" id="trackingcode" name="trackingcode" placeholder="AB1234"></input>
										<label for="trackingcode">Trackingcode</label>
									</div>
									<div class="input-field col s12">
										<input type="text" id="postcode" name="postcode"></input>
										<label for="postcode">Postcode</label>
									</div>
									<div class="input-field col s12">
										<button type="submit" name="submit" class="btn primary waves-effect waves-light" value="Inloggen"><i class="material-icons left">update</i>Track & trace!</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>



<!-- Pagewrapper -->
</main>

<!-- FOOTER -->
<?php include "includes/footer.php" ?>


</body>
</html>