<?php session_start(); ?>

<!DOCTYPE html>
<html>

<?php
include "includes/functions.php";
include "includes/database.php";
include "includes/head.php";

// pageinfo
$page_name = "verzenden";
?>

<body>

<!-- HEADER -->
<?php
$nav_fixed = true;
include "includes/header.php"; ?>

<main class="pagewrapper">

	<div class="parallax-container depth-page z-depth-2">
		<div class="parallax"><img src="img/big_image_verzenden.jpg"></div>
		<div class="container white-text center" style="margin-top: 25vh;">
			<h2>Een pakketje binnen 6 uur laten leveren?</h2>
			<p>Wij leveren uw pakketje binnen 6 uur op het bestemmingsadres.</p>
			<a onclick="scrollTo('#verzenden')" class="btn-large primary waves-effect waves-light">Verzend een pakketje</a>
		</div>
	</div>

	<div class="section" id="verzenden">
		<div class="container">
			<h4 class="header">Verzend nu een pakketje!</h4>
			<form action="verzenden_verwerk.php" method="POST">
				<!-- VERZENDEN -->
				<div class="card hoverable">
					<div class="card-content">
						<!-- VERZENDER -->
						<span class="card-title">Persoonlijke gegevens</span>
						<div class="row card-row">
							<div class="input-field col s12 m6 l4">
								<input type="text" id="v_naam" name="verzender[naam]"></input>
								<label for="v_naam">Voornaam</label>
							</div>
							<div class="input-field col s12 m6 l4">
								<input type="text" id="v_achternaam" name="verzender[achternaam]"></input>
								<label for="v_achternaam">Achternaam</label>
							</div>
							<div class="input-field col s12 m6 l4">
								<input type="email" id="v_email" name="verzender[email]"></input>
								<label for="v_email">Emailadres	</label>
							</div>
						</div>
					</div>
				</div>
				<div class="card hoverable">
					<div class="card-content">
						<!-- ADRES -->
						<span class="card-title">Uw adres</span>
						<div class="row card-row">
							<div class="input-field col s12 m6">
								<input type="text" id="v_straat" name="traject[0][straat]"></input>
								<label for="v_straat">Straatnaam</label>
							</div>
							<div class="input-field col s12 m6">
								<input type="text" id="v_huisnummer" name="traject[0][huisnummer]"></input>
								<label for="v_huisnummer">Huisnummer</label>
							</div>
							<div class="input-field col s12 m6">
								<input type="text" id="v_postcode" name="traject[0][postcode]"></input>
								<label for="v_postcode">Postcode</label>
							</div>
							<div class="input-field col s12 m6">
								<input type="text" id="v_plaats" name="traject[0][plaats]"></input>
								<label for="v_plaats">Plaats</label>
							</div>
						</div>
					</div>
				</div>
				<div class="card hoverable">
					<div class="card-content">
						<!-- ONTVANGER -->
						<span class="card-title">Informatie ontvanger</span>
						<div class="row card-row">
							<div class="input-field col s12 m6 l4">
								<input type="text" id="o_naam" name="ontvanger[naam]"></input>
								<label for="o_naam">Voornaam</label>
							</div>
							<div class="input-field col s12 m6 l4">
								<input type="text" id="o_achternaam" name="ontvanger[achternaam]"></input>
								<label for="o_achternaam">Achternaam</label>
							</div>
							<div class="input-field col s12 m6 l4">
								<input type="email" id="o_email" name="ontvanger[email]"></input>
								<label for="o_email">Emailadres	</label>
							</div>
						</div>
					</div>
				</div>
				<div class="card hoverable">
					<div class="card-content">
						<!-- ONTVANGSTADRES -->
						<span class="card-title">Ontvangstadres</span>
						<div class="row card-row">
							<div class="input-field col s12 m6">
								<input type="text" id="o_straat" name="traject[1][straat]"></input>
								<label for="o_straat">Straatnaam</label>
							</div>
							<div class="input-field col s12 m6">
								<input type="text" id="o_huisnummer" name="traject[1][huisnummer]"></input>
								<label for="o_huisnummer">Huisnummer</label>
							</div>
							<div class="input-field col s12 m6">
								<input type="text" id="o_postcode" name="traject[1][postcode]"></input>
								<label for="o_postcode">Postcode</label>
							</div>
							<div class="input-field col s12 m6">
								<input type="text" id="o_plaats" name="traject[1][plaats]"></input>
								<label for="o_plaats">Plaats</label>
							</div>
						</div>
					</div>
				</div>
				<button type="submit" class="btn-large primary" name="submit"><i class="material-icons left">euro_symbol</i>Bereken prijs</button>
			</form>
		</div>
	</div>



<!-- Pagewrapper -->
</main>

<!-- FOOTER -->
<?php include "includes/footer.php" ?>


</body>
</html>