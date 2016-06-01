<?php session_start(); ?>

<!DOCTYPE html>
<html>

<?php
include "includes/functions.php";
include "includes/database.php";
include "includes/head.php";

// pageinfo
$page_name = "track&trace";
?>

<body>

<!-- HEADER -->
<?php include "includes/header.php" ?>

<main class="pagewrapper">

	<div class="section" id="inputs">
		<div class="container">
			<div class="center-card">
				<div class="card" id="login-card">
					<div class="card-content">
						<span class="card-title">Track & trace</span>
						<p>Deze gegevens heeft u per mail ontvangen wanneer er een pakketje naar uw adres is gestuurd. De trackingcode begint met twee hoofdletters.</p>
						<p id="error-field" class="red-text"></p>
						<br>
						<div class="valign-wrapper">
							<div class="row">
								<form class="valign">
									<div class="input-field col s12">
										<input type="text" id="trackingnr" name="trackingnr" placeholder="AB1234"></input>
										<label for="trackingnr">Trackingcode</label>
									</div>
									<div class="input-field col s12">
										<input type="text" id="postcode" name="postcode"></input>
										<label for="postcode">Postcode</label>
									</div>
								</form>
								<div class="input-field col s12">
									<button onclick="trackTrace();" class="btn primary waves-effect waves-light"><i class="material-icons left">update</i>Track & trace!</button>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>


	<div class="section" id="result" style="display: none;">
		<div class="container">
			<h4>Uw pakketje</h4>
		</div>
		<div class="container" id="table-container">

		</div>
	</div>


<!-- Pagewrapper -->
</main>

<!-- FOOTER -->
<?php include "includes/footer.php" ?>


</body>
</html>