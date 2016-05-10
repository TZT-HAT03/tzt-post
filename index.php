<?php session_start(); ?>

<!DOCTYPE html>
<html>

<?php
include "includes/functions.php";
include "includes/database.php";
include "includes/head.php";

// pageinfo
$page_name = "home";
?>

<body>

<!-- HEADER -->
<?php
$nav_fixed = true;
include "includes/header.php"; ?>

<main class="pagewrapper">

	<div class="slider home">
		<ul class="slides home">
			<li>
				<img src="img/big_image_home_1.jpg">
				<div class="caption center-align" style="margin-top: 5vh;">
					<h3>TZT post verzendt uw pakketje groen!</h3>
					<h5 class="light grey-text text-lighten-3">TZT post streeft ernaar om uw pakketje
					zo groen mogelijk te versturen.</h5>
					<a href="verzenden" class="btn-large primary waves-effect waves-light">Verzend een pakketje</a>
				</div>
			</li>
			<li>
				<img src="img/big_image_home_2.jpg">
				<div class="caption right-align" style="margin-top: 30vh;">
					<h3>TZT post zoekt treinkoeriers!</h3>
					<h5 class="light grey-text text-lighten-3">Wil jij voor een vergoeding op je
					dagelijkse reis een pakketje meenemen?</h5>
					<a href="treinkoeriers" class="btn-large primary waves-effect waves-light">Meld je aan!</a>
				</div>
			</li>
			<li>
				<img src="img/big_image_home_3.jpg">
				<div class="caption left-align">
					<h3>Pakketje traceren?</h3>
					<h5 class="light grey-text text-lighten-3">Wil jij het pakketje traceren dat met
					TZT wordt verstuurd?</h5>
					<a href="ontvangen" class="btn-large primary waves-effect waves-light">Track- en trace</a>
				</div>
			</li>
		</ul>
	</div>

	<div class="section">
		<div class="container">
			<h3 class="header">Hoe werkt het?</h3>
			<div class="row">
				<div class="col s12 m4">
					<div class="center promo">
						<i class="material-icons">send</i>
						<p class="promo-caption">U heeft een pakketje</p>
						<p class="light center">Deze meldt u aan voor verzending, en een koerier komt deze thuis bij u ophalen</p>
					</div>
				</div>
				<div class="col s12 m4">
					<div class="center promo">
						<i class="material-icons">local_shipping</i>
						<p class="promo-caption">Het pakketje wordt verzonden</p>
						<p class="light center">Het pakketje wordt verzonden met een lokale koerier en een treinkoerier die uw pakketje mee
						neemt de trein in.</p>
					</div>
				</div>
				<div class="col s12 m4">
					<div class="center promo">
						<i class="material-icons">drafts</i>
						<p class="promo-caption">Aflevering</p>
						<p class="light center">Het pakketje wordt <strong>binnen 6 uur</strong> bij het bezorgadres afgeleverd!</p>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="section">
		<div class="container">
			<br>
			<h3 class="header">Wat wilt u doen?</h3>
			<div class="row">
				<div class="col s12 m6">
					<div class="card hoverable">
						<div class="card-content">
							<span class="card-title">Verzenden</span>
							<p>Wilt u zo snel mogelijk een pakketje van A naar B verzenden? Wij bezorgen uw pakketje binnen 6 uur!</p>
						</div>
						<div class="card-action">
							<a href="verzenden">Verzend een pakketje!</a>
						</div>
					</div>
				</div>
				<div class="col s12 m6">
					<div class="card hoverable">
						<div class="card-content">
							<span class="card-title">Ontvangen</span>
							<p>Wordt er een pakketje met TZT naar uw adres gestuurd? Controleer hier de status van het pakketje!</p>
						</div>
						<div class="card-action">
							<a href="ontvangen">Bekijk hier de status</a>
						</div>
					</div>
				</div>
				<div class="col s12 m6 push-m3">
					<div class="card hoverable">
						<div class="card-content">
							<span class="card-title">Treinkoeriers</span>
							<p>Wilt u een treinkoerier worden, of bent u er een? Klik op onderstaande links om te navigeren op de
							website.</p>
						</div>
						<div class="card-action">
							<a href="login">Inloggen</a>
							<a href="treinkoeriers">Treinkoerier worden</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="section">

	</div>



<!-- Pagewrapper -->
</main>

<!-- FOOTER -->
<?php include "includes/footer.php" ?>


</body>
</html>