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
			<form class="check-send" action="betalen" method="POST">
				<!-- VERZENDEN -->
				<div class="card hoverable">
					<div class="card-content">
						<!-- VERZENDER -->
						<span class="card-title">Persoonlijke gegevens</span>
						<p>U bent een:</p>
						<p>
							<input class="with-gap change-view" href="#pg_particulier" data-disable="#pg_bedrijf" name="verzender[type]" type="radio" value="particulier" id="v_particuler" />
							<label for="v_particuler">Particuler</label>
						</p>
						<p>
							<input class="with-gap change-view" href="#pg_bedrijf" data-disable="#pg_particulier" name="verzender[type]" type="radio" value="bedrijf" id="v_bedrijf" />
							<label for="v_bedrijf">Bedrijf</label>
						</p>
						<div class="row card-row" id="pg_particulier" style="display: none;">
							<div class="input-field col s12 m6 l4">
								<input type="text" id="vp_naam" name="verzender[p_naam]"></input>
								<label for="vp_naam">Voornaam</label>
							</div>
							<div class="input-field col s12 m6 l4">
								<input type="text" id="v_achternaam" name="verzender[achternaam]"></input>
								<label for="v_achternaam">Achternaam</label>
							</div>
							<div class="input-field col s12 m6 l4">
								<input type="email" id="vp_email" name="verzender[p_email]"></input>
								<label for="vp_email">Emailadres</label>
							</div>
						</div>
						<div class="row card-row" id="pg_bedrijf" style="display: none;">
							<div class="input-field col s12 m6 l4">
								<input type="text" id="vb_naam" name="verzender[b_naam]"></input>
								<label for="vb_naam">Bedrijfsnaam</label>
							</div>
							<div class="input-field col s12 m6 l4">
								<input type="email" id="vb_email" name="verzender[b_email]"></input>
								<label for="vb_email">Emailadres	</label>
							</div>
							<div class="input-field col s12 m6 l4">
								<input type="text" id="v_telefoon" name="verzender[telefoon]"></input>
								<label for="v_telefoon">Telefoon</label>
							</div>
							<div class="input-field col s12 m6 l4">
								<input type="text" id="v_kvk" name="verzender[kvk]"></input>
								<label for="v_kvk">KvK nummer</label>
							</div>
							<div class="input-field col s12 m6 l4">
								<input type="text" id="v_iban" name="verzender[iban]"></input>
								<label for="v_iban">iban</label>
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
								<input type="text" id="v_straat" name="begin[straat]"></input>
								<label for="v_straat">Straatnaam</label>
							</div>
							<div class="input-field col s12 m6">
								<input type="text" id="v_huisnummer" name="begin[huisnummer]"></input>
								<label for="v_huisnummer">Huisnummer</label>
							</div>
							<div class="input-field col s12 m6">
								<input type="text" id="v_postcode" name="begin[postcode]"></input>
								<label for="v_postcode">Postcode</label>
							</div>
							<div class="input-field col s12 m6">
								<input type="text" id="v_plaats" name="begin[plaats]"></input>
								<label for="v_plaats">Plaats</label>
							</div>
						</div>
					</div>
				</div>
				<div class="card hoverable">
					<div class="card-content">
						<!-- ONTVANGER -->
						<span class="card-title">Informatie ontvanger</span>
						<p>Ontvanger is een:</p>
						<p>
							<input class="with-gap change-view" href="#io_particulier" data-disable="#io_bedrijf" name="ontvanger[type]" type="radio" value="particulier" id="o_particuler" />
							<label for="o_particuler">Particuler</label>
						</p>
						<p>
							<input class="with-gap change-view" href="#io_bedrijf" data-disable="#io_particulier" name="ontvanger[type]" type="radio" value="bedrijf" id="o_bedrijf" />
							<label for="o_bedrijf">Bedrijf</label>
						</p>
						<div class="row card-row" id="io_particulier" style="display: none;">
							<div class="input-field col s12 m6">
								<input type="text" id="op_naam" name="ontvanger[p_naam]"></input>
								<label for="op_naam">Voornaam</label>
							</div>
							<div class="input-field col s12 m6">
								<input type="text" id="o_achternaam" name="ontvanger[achternaam]"></input>
								<label for="o_achternaam">Achternaam</label>
							</div>
						</div>
						<div class="row card-row" id="io_bedrijf" style="display: none;">
							<div class="input-field col s12">
								<input type="text" id="ob_naam" name="ontvanger[b_naam]"></input>
								<label for="ob_naam">Bedrijfsnaam</label>
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
								<input type="text" id="o_straat" name="einde[straat]"></input>
								<label for="o_straat">Straatnaam</label>
							</div>
							<div class="input-field col s12 m6">
								<input type="text" id="o_huisnummer" name="einde[huisnummer]"></input>
								<label for="o_huisnummer">Huisnummer</label>
							</div>
							<div class="input-field col s12 m6">
								<input type="text" id="o_postcode" name="einde[postcode]"></input>
								<label for="o_postcode">Postcode</label>
							</div>
							<div class="input-field col s12 m6">
								<input type="text" id="o_plaats" name="einde[plaats]"></input>
								<label for="o_plaats">Plaats</label>
							</div>
						</div>
					</div>
				</div>
				<div class="card hoverable">
					<div class="card-content">
						<!-- PAKKETINFORMATIE -->
						<span class="card-title">Pakketinformatie</span>
						<div class="row card-row">
							<div class="input-field col s3">
								<input type="number" id="p_gewicht" name="pakket[gewicht]"></input>
								<label for="p_gewicht">Gewicht(kg)</label>
							</div>
							<div class="input-field col s3">
								<input type="number" id="p_lengte" name="pakket[lengte]"></input>
								<label for="p_lengte">Lengte(cm)</label>
							</div>
							<div class="input-field col s3">
								<input type="number" id="p_breedte" name="pakket[breedte]"></input>
								<label for="p_breedte">Breedte(cm)</label>
							</div>
							<div class="input-field col s3">
								<input type="number" id="p_hoogte" name="pakket[hoogte]"></input>
								<label for="p_hoogte">Hoogte(cm)</label>
							</div>
						</div>
					</div>
				</div>
			</form>
			<button onclick="checkSend(); $('#response-modal').openModal();" href="#modal1" class="btn-large primary"><i class="material-icons left">euro_symbol</i>Bereken prijs</button>
		</div>
	</div>



<!-- Pagewrapper -->
</main>

<div id="response-modal" class="modal">
	<div class="modal-content">
		<h3>Moment geduld</h3>
		<p>Wij zijn de prijs voor u aan het berekenen...</p>
	</div>
</div>

<!-- FOOTER -->
<?php include "includes/footer.php" ?>


</body>
</html>