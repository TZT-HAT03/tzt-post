<?php
session_start();

include "includes/database.php";
include 'includes/functions.php';

if (isLoggedIn()) {
    $gebruiker_id = $_SESSION['persoon_id'];

    $treinkoerier_id = $_SESSION['treinkoerier_id'];

    $stmt = $pdo->prepare("SELECT * FROM tussenstap t JOIN pakket p ON t.pakket_id=p.pakket_id WHERE t.tussenstap_type=2 AND t.koerier_id=:treinkoerier_id;");
    if (!$stmt->execute(array('treinkoerier_id' => $treinkoerier_id))) {
    	echo "Er is iets mis gegaan met het ophalen van jouw aangemelde pakketjes.";
    } else {
    	$aangemeld_rough = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    $last_id = -1;
    $aangemeld = array();
    $tijdelijk = array();
	foreach ($aangemeld_rough as $tussenstap) {
		if ($last_id != $tussenstap["pakket_id"]) {
			if (count($tijdelijk) != 0) {
				array_push($aangemeld, $tijdelijk);
			}
			$tijdelijk[0] = $tussenstap;
		} else {
			$tijdelijk[1] = $tussenstap;
		}
		$last_id = $tussenstap["pakket_id"];
	}
	if (count($tijdelijk) == 2) {
		array_push($aangemeld, $tijdelijk);
	}

    $stmt = $pdo->prepare("SELECT * FROM tussenstap t JOIN pakket p ON t.pakket_id=p.pakket_id WHERE t.tussenstap_type=2 AND t.koerier_id IS NULL;");
    if (!$stmt->execute(array('treinkoerier_id' => $treinkoerier_id))) {
    	echo "Er is iets mis gegaan met het ophalen van openstaande pakketjes.";
    } else {
    	$openstaand_rough = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    $last_id = -1;
    $openstaand = array();
    $tijdelijk = array();
	foreach ($openstaand_rough as $tussenstap) {
		if ($last_id != $tussenstap["pakket_id"]) {
			if (count($tijdelijk) != 0) {
				array_push($openstaand, $tijdelijk);
			}
			$tijdelijk[0] = $tussenstap;
		} else {
			$tijdelijk[1] = $tussenstap;
		}
		$last_id = $tussenstap["pakket_id"];
	}
	if (count($tijdelijk) == 2) {
		array_push($openstaand, $tijdelijk);
	}

} else {
	header('location: /login');
	exit();
}

// pageinfo
$page_name = "pakket-panel";

?>
<!DOCTYPE html>
<html>

<!-- HEADER -->
<?php include "includes/head.php"; ?>

<body>

<!-- HEADER -->
<?php
$nav_fixed = true;
include "includes/header.php" ?>

<main class="pagewrapper">

	<div class="parallax-container depth-page z-depth-2">
		<div class="parallax"><img src="img/big_image_profiel.jpg"></div>
		<div class="container white-text center" style="margin-top: 25vh;">
			<h2>Welkom treinkoerier!</h2>
			<p>Welkom bij het Pakket Panel, bekijk de pakketjes hieronder, en meld je aan!</p>
			<a onclick="scrollTo('#pakket-panel')" class="btn-large primary waves-effect waves-light">Scroll naar Pakket Panel</a>
		</div>
	</div>

	<div class="container section" id="pakket-panel">

		<?php
		if (count($aangemeld) != 0) { ?>
		<h4>Jouw pakketjes</h4>
		<table class="striped responsive-table">
			<thead>
				<tr>
					<th>Van station</th>
					<th>Naar station</th>
					<th>Gewicht</th>
					<th>Lengte</th>
					<th>Breedte</th>
					<th>Hoogte</th>
					<th>Bekijken</th>
				</tr>
			</thead>
			<tbody>

			<?php
			foreach ($aangemeld as $pakketje) {?>

				<tr>
					<td><?= $pakketje[0]["plaats"] ?></td>
					<td><?= $pakketje[1]["plaats"] ?></td>
					<td><?= $pakketje[0]["gewicht"] ?> kg</td>
					<td><?= $pakketje[0]["lengte"] ?> cm</td>
					<td><?= $pakketje[0]["breedte"] ?> cm</td>
					<td><?= $pakketje[0]["hoogte"] ?> cm</td>
					<td class="center"><button class="wave-effect btn primary" id="bekijken-<?= $pakketje[0]["pakket_id"] ?>"><i class="material-icons left">open_in_new</i>Bekijken</button></td>
				</tr>

			<?php }

			?>

			</tbody>
		</table>

		<?php } ?>


		<?php
		if (count($openstaand) == 0) { ?>
		<p>Er zijn geen openstaande pakketjes...</p>
		<?php } else {
		?>
		<h4>Openstaande pakketjes</h4>
		<table class="striped responsive-table">
			<thead>
				<tr>
					<th>Van station</th>
					<th>Naar station</th>
					<th>Gewicht</th>
					<th>Lengte</th>
					<th>Breedte</th>
					<th>Hoogte</th>
					<th>Aanmelden</th>
				</tr>
			</thead>
			<tbody>

			<?php
			foreach ($openstaand as $pakketje) {?>

				<tr>
					<td><?= $pakketje[0]["plaats"] ?></td>
					<td><?= $pakketje[1]["plaats"] ?></td>
					<td><?= $pakketje[0]["gewicht"] ?> kg</td>
					<td><?= $pakketje[0]["lengte"] ?> cm</td>
					<td><?= $pakketje[0]["breedte"] ?> cm</td>
					<td><?= $pakketje[0]["hoogte"] ?> cm</td>
					<td class="center"><button class="wave-effect btn primary" id="aanmelden-<?= $pakketje[0]["pakket_id"] ?>" onclick="aanmeldenPakket(<?= $pakketje[0]["pakket_id"] ?>);"><i class="material-icons left">check</i>Aanmelden</button></td>
				</tr>

			<?php }

			?>

			</tbody>
		</table>

		<?php } ?>

	</div>

</main>

<?php include "includes/footer.php" ?>

</body>
</html>