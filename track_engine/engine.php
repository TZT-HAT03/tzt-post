<?php

include "../includes/database.php";

// Dit bestand wordt aangeroepen door een httprequest van de server
if (isset($_POST["trackingnr"]) & isset($_POST["postcode"])) {
	$trackingnr = $_POST["trackingnr"];
	$postcode = $_POST["postcode"];

	try {

		$stmt = $pdo->prepare("SELECT * FROM tussenstap t JOIN pakket p ON t.pakket_id=p.pakket_id WHERE p.trackingnr=:trackingnr AND p.ontvanger_id IN (SELECT ontvanger_id FROM ontvanger o WHERE o.postcode=:postcode);");
		try {
			$stmt->execute(array('postcode' => $postcode, 'trackingnr' => $trackingnr));

			$tussenstappen = $stmt->fetchAll(PDO::FETCH_ASSOC);
			if (count($tussenstappen) != 0) {
				$json = json_encode($tussenstappen);
				echo $json;
			}
		} catch (Exception $e) {
			throw new Exception("ERROR: Probleem met het ophalen uit de database: ".$e->getMessage(), 1);
		}

	} catch (Exception $e) {
		echo $e->getMessage();
	}

} else {
	echo "Lege _POST array. Kan niets uitvoeren.";
	header('location: /home');
}


?>