<?php session_start(); ?>

<!DOCTYPE html>
<html>

<?php
include "includes/functions.php";
include "includes/database.php";
include "includes/head.php";

// pageinfo
$page_name = "treinkoeriers";
?>

<body>

<!-- HEADER -->
<?php
$nav_fixed =  true;
include "includes/header.php" ?>

<main class="pagewrapper">

	<?php
	if (!isset($_SESSION["register-confirm"])) { ?>

	<div class="parallax-container depth-page z-depth-2">
		<div class="parallax"><img src="img/big_image_treinkoerier.jpg"></div>
		<div class="container white-text center" style="margin-top: 25vh;">
			<h2>Treinkoerier worden?</h2>
			<p>Wij staan te popelen om je te ontmoeten!</p>
			<a onclick="scrollTo('#aanmelden')" class="btn-large primary waves-effect waves-light">Meld je aan!</a>
		</div>
	</div>

	<div class="section">
		<div class="container">
			<h4>Hoe werkt het?</h4>
			<div class="row">
				<div class="col s12 m4">
					<div class="center promo">
						<i class="material-icons">lock_open</i>
						<p class="promo-caption">Het halen van het pakketje</p>
						<p class="light center">Voo uw dagelijkse reis begint haalt u
						op het station een pakketje op in een van onze kluisjes. Dit
						pakketje zal aan u verbonden zijn door ons.</p>
					</div>
				</div>
				<div class="col s12 m4">
					<div class="center promo">
						<i class="material-icons">train</i>
						<p class="promo-caption">U vervolgd uw reis</p>
						<p class="light center">Tijdens uw treinreis neemt u het pakketje
						mee die wij aan u hebben gebonden. U hoeft tijdens de reis alleen
						maar op het pakketje te letten.</p>
					</div>
				</div>
				<div class="col s12 m4">
					<div class="center promo">
						<i class="material-icons">euro_symbol</i>
						<p class="promo-caption">Ontvang geld</p>
						<p class="light center">Na de reis stopt u het pakketje in het
						kluisje dat wij voor u hebben aangewezen, en het geld wordt op
						uw rekening ovregemaakt.</p>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="section" id="aanmelden">
		<div class="container">
			<h4 class="header">Meld u nu aan!</h4>
			<!-- ACCOUNT -->
			<form action="registreren_verwerk.php" method="POST">
				<?php if (isset($_SESSION["emailErrorMessage"])) { ?>
					<p class="red-text"><?= $_SESSION["emailErrorMessage"] ?></p>
				<?php } ?>
				<?php if (isset($_SESSION["passwordErrorMessage"])) { ?>
					<p class="red-text"><?= $_SESSION["passwordErrorMessage"] ?></p>
				<?php } ?>
				<?php if (isset($_SESSION["passwordMatchErrorMessage"])) { ?>
					<p class="red-text"><?= $_SESSION["passwordMatchErrorMessage"] ?></p>
				<?php } ?>
				<div class="card hoverable">
					<div class="card-content">
						<span class="card-title">Account</span>
						<div class="row card-row">
							<div class="input-field col s12 m6">
								<input type="email" id="email-adres" name="email" class="stop-validation
								<?php if (isset($_SESSION["emailErrorMessage"])) 	echo 'invalid' ?>"
								<?php if (isset($_SESSION["email"])) 				echo 'value="' . $_SESSION["email"] . '" ' ?> >
								<label for="email-adres">Emailadres</label>
							</div>
							<div class="col offset-m6"></div>
							<!-- WACHTWOORD -->
							<div class="input-field col s12 m6">
								<input type="password" id="wachtwoord" name="password1" class="stop-validation
								<?php if (isset($_SESSION["passwordErrorMessage"]) || isset($_SESSION["passwordMatchErrorMessage"])) echo 'invalid' ?>" >
								<label for="wachtwoord">Wachtwoord</label>
							</div>
							<!-- WACHTWOORD HERHALEN -->
							<div class="input-field col s12 m6">
								<input type="password" id="wachtwoord2" name="password2" class="stop-validation
								<?php if (isset($_SESSION["passwordErrorMessage"]) || isset($_SESSION["passwordMatchErrorMessage"])) echo 'invalid' ?>" >
								<label for="wachtwoord2">Wachtwoord (herhalen)</label>
							</div>
						</div>
					</div>
				</div>
				<!-- CONTACTGEGEVENS -->
				<div class="card hoverable">
					<div class="card-content">
						<span class="card-title">Contactgegevens</span>
						<div class="row card-row">
							<div class="input-field col s12 m6">
								<select name="geslacht">
									<option value="M">Man</option>
									<option value="V">Vrouw</option>
								</select>
								<label>Geslacht</label>
							</div>
							<div class="input-field col s12 m6">
								<input type="date" id="geb_datum" name="geb_datum" class="datepicker"></input>
								<label for="geb_datum">Geboortedatum</label>
							</div>
							<div class="input-field col s12 m6">
								<input type="text" id="voornaam" name="voornaam"></input>
								<label for="voornaam">Voornaam</label>
							</div>
							<div class="input-field col s12 m6">
								<input type="text" id="achternaam" name="achternaam"></input>
								<label for="achternaam">Achternaam</label>
							</div>
							<div class="input-field col s12 m6">
								<input type="text" name="straat" id="straat">
								<label for="straat" class="active">Straat</label>
							</div>
							<div class="input-field col s12 m6">
								<input type="text" name="huisnummer" id="huisnummer">
								<label for="huisnummer" class="active">Huisnummer</label>
							</div>
							<div class="input-field col s12 m6">
								<input type="text" name="postcode" id="postcode">
								<label for="postcode" class="active">Postcode</label>
							</div>
							<div class="input-field col s12 m6">
								<input type="text" name="plaats" id="plaats">
								<label for="plaats" class="active">Plaats</label>
							</div>
							<div class="input-field col s12 m6">
								<input type="text" name="telefoon" id="telefoon">
								<label for="telefoon" class="active">Telefoonnummer (06)</label>
							</div>
						</div>
					</div>
				</div>
				<!-- IDENTIFICATIE -->
				<div class="card hoverable">
					<div class="card-content">
						<span class="card-title">Identificatie</span>
						<p>Voor deze stap heeft u uw id-kaart nodig. Het BSN-nummer staat achterop de kaart onder "persoonsnummer".
						Het documentnummer staat voorop onder ""documentnummer".</p>
						<div class="row card-row">
							<div class="input-field col s12 m6">
								<input type="text" name="bsn" id="bsn">
								<label for="bsn" class="active">Burger Service Nummer</label>
							</div>
							<div class="input-field col s12 m6">
								<input type="text" name="doc_nummer" id="doc_nummer">
								<label for="doc_nummer" class="active">Documentnummer</label>
							</div>
						</div>
					</div>
				</div>
				<button type="submit" class="btn-large primary" name="submit"><i class="material-icons left">send</i>Verzenden</button>
			</form>
		</div>
	</div>

	<?php
	} else
	if ($_SESSION["register-confirm"] == true) {?>

	<div class="container">
		<div class="center-card" id="login-card">
			<div class="card">
				<div class="card-content">
					<span class="card-title">Geslaagd</span>
					<p>We hebben uw inschrijving ontvangen. U ontvangt z.s.m een email naar aanleiding van uw inschrijving, met daarin of deze is geaccepteerd of niet.</p>
					<br>
					<p>Hartelijk dank voor uw interesse!</p>
				</div>
				<div class="card-action">
					<a href="/home">Terug</a>
				</div>
			</div>
		</div>
	</div>

	<?php
	unset($_SESSION["register-confirm"]);

	} else
	if ($_SESSION["register-confirm"] == false) {?>

	<div class="container">
		<div class="center-card" id="login-card">
			<div class="card">
				<div class="card-content">
					<span class="card-title">Er is iets fout gegaan...</span>
					<p>Er is iets fout gegaan bij het inlezen van uw inschrijving, probeer het a.u.b. opnieuw</p>
				</div>
				<div class="card-action">
					<a href="/treinkoeriers">Probeer opnieuw</a>
				</div>
			</div>
		</div>
	</div>

	<?php
	unset($_SESSION["register-confirm"]);

	}
	?>





<!-- Pagewrapper -->
</main>

<!-- FOOTER -->
<?php
unset($_SESSION["emailErrorMessage"]);
unset($_SESSION["passwordErrorMessage"]);
unset($_SESSION["passwordMatchErrorMessage"]);
unset($_SESSION["email"]);

include "includes/footer.php" ?>


</body>
</html>