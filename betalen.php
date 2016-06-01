<?php session_start(); ?>

<!DOCTYPE html>
<html>

<?php
include "includes/functions.php";
include "includes/database.php";
include "includes/head.php";

// pageinfo
$page_name = "betalen";
?>

<body>

<!-- HEADER -->
<?php
include "includes/header.php"; ?>


<main class="pagewrapper">

	<?php print_r($_POST) ?>

	<div id="loading-indicator">
		<div class="center-wrapper">
			<div class="preloader-wrapper big active" id="loader">
				<div class="spinner-layer spinner-red">
					<div class="circle-clipper left">
						<div class="circle"></div>
					</div>
					<div class="gap-patch">
						<div class="circle"></div>
					</div>
					<div class="circle-clipper right">
						<div class="circle"></div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="section" id="betalen" style="opacity: 0;">
		<div class="container">
			<h4 class="header">Kies uw betaalmethode</h4>
			<div class="row">
				<div class="col s12 m3 l4 push-m9 push-l8">
					<div class="kosten-panel">
						<h5>Kosten:</h5>
						<h5 class="kosten"></h5>
					</div>
				</div>
				<div class="col s12 m9 l8 pull-m3 pull-l4">
					<form action="versturen-confirm.php">
						<p>
						<input class="with-gap" name="betaalmethode" checked type="radio" id="PayPal" />
							<label for="PayPal">PayPal</label>
						</p>
						<p>
						<input class="with-gap" name="betaalmethode" type="radio" id="iDeal" />
							<label for="iDeal">iDeal</label>
						</p>
						<?php if ($_POST["verzender"]["type"] == "bedrijf") { ?>
						<p>
						<input class="with-gap" name="betaalmethode" type="radio" id="bankafschrijft"  />
							<label for="bankafschrijft">Bankafschrijft</label>
						</p>
						<?php } ?>
						<p>
					</form>
				</div>
			</div>
			<button class="btn primary waves-effect" onclick="clickBetalen($_POST.begin, $_POST.einde, $_POST.verzender, $_POST.ontvanger, $_POST.pakket);">Betalen</button>
		</div>
	</div>



<!-- Pagewrapper -->
</main>


<div id="response-modal" class="modal">
	<div class="modal-content">
		<h3>Moment geduld</h3>
		<p>Wij zijn het pakketje aan het verwerken...</p>
	</div>
</div>


<!-- FOOTER -->
<?php include "includes/footer.php" ?>


	<script type="text/javascript">
		var $_POST = <?php echo json_encode($_POST); ?>;
		$(document).ready(function() {
			berekenKosten($_POST.begin, $_POST.einde, $_POST.pakket, function(response) {
				$('#loading-indicator').delay(1500).fadeOut(400);
				$('.kosten-panel .kosten').html("€"+response);
				$('#betalen').delay(1500).animate({opacity:1}, 400);
			});
		});
	</script>


</body>
</html>