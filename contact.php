<?php session_start(); ?>

<!DOCTYPE html>
<html>

<?php
include "includes/functions.php";
include "includes/database.php";
include "includes/head.php";

// pageinfo
$page_name = "contact";
?>

<body>

<!-- HEADER -->
<?php include "includes/header.php" ?>

<main class="pagewrapper">
<?php
if (!isset($_SESSION["contact-geslaagd"])) { ?>

	<div class="container">
		<div class="section">
			<h2 class="header">Contact</h2>
			<p>Je kunt contact met TZT opnemen door een e-mail te sturen naar <a href="mailto:info@tzt.nl">info@tzt.nl</a>, of
			door onderstaand contactformulier in te vullen.</p>

			<div class="card-panel">
				<form action="contact_verwerk.php" method="POST">
					<div class="row">
						<div class="input-field col s6">
							<input id="naam" type="text" name="naam">
							<label for="naam">Naam</label>
						</div>
						<div class="input-field col s6">
							<input id="email" type="email" name="email">
							<label for="email">E-mail</label>
						</div>
						<div class="input-field col s12">
							<textarea id="bericht" class="materialize-textarea" name="bericht"></textarea>
							<label for="bericht">Bericht</label>
						</div>
					</div>
					<input type="submit" class="btn primary" value="Verstuur">
				</form>
			</div>

		</div>
	</div>

<?php
} else
if ($_SESSION["contact-geslaagd"] == true) { ?>

	<div class="container">
		<div class="center-card" id="login-card">
			<div class="card">
				<div class="card-content">
					<span class="card-title">Geslaagd</span>
					<p>We hebben uw bericht ontvangen, bedankt!</p>
				</div>
				<div class="card-action">
					<a href="/home">Terug</a>
				</div>
			</div>
		</div>
	</div>

<?php

unset($_SESSION["contact-geslaagd"]);

} else
if ($_SESSION["contact-geslaagd"] == false) { ?>

	<div class="container">
		<div class="center-card" id="login-card">
			<div class="card">
				<div class="card-content">
					<span class="card-title">Mislukt</span>
					<p>Er is iets verkeerd gegaan. Probeer het opnieuw!</p>
				</div>
				<div class="card-action">
					<a href="/contact">Terug</a>
				</div>
			</div>
		</div>
	</div>

<?php

unset($_SESSION["contact-geslaagd"]);

}?>

</main>

<!-- FOOTER -->
<?php include "includes/footer.php" ?>


</body>
</html>