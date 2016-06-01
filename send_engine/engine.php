<?php

require_once "functions.php";
require_once "../includes/database.php";

// Dit bestand wordt aangeroepen door een httprequest van de server of de backoffice
if (isset($_POST["functie"])) {

	try {
		// Kijken of de arrays die bij elke functie nodig zijn bestaan en/of leeg zijn.
		if (!checkReguliereArrays()) {
			throw new Exception("ERROR: Onvolledige data, begin, pakket of eind array niet volledig", 1);
			exit();
		} else
		// Check of arrays voor route en totaal bestaan en niet leeg zijn
		if (!checkSpecificArrays()) {
			// Error codes worden gegenereerd door bovenstaande functie
		} else {
			// Alle arrays worden goed doorgegeven, voer functies uit :D
				switch ($_POST["functie"]) {
					case 'kosten':
						echo berekenKosten($_POST["begin"], $_POST["eind"], $_POST["pakket"]);
						break;
					case 'route':
						print_r(berekenRoute($_POST["begin"], $_POST["eind"], $_POST["pakket"], "json"));
						break;
					case 'totaal':
						insertInDatabase(berekenRoute($_POST["begin"], $_POST["eind"], $_POST["pakket"], "return"), $_POST["verzender"], $_POST["ontvanger"], $pdo);
						break;
					default:
						throw new Exception("Geen functie herkend. De mogelijke functies zijn 'kosten', 'route', 'totaal'. Controleer je functie en probeer het opnieuw", 1);
						break;
				}
		}

	} catch (Exception $e) {
		echo $e->getMessage();
	}

} else {
	echo "Lege _POST array. Kan niets uitvoeren.";
	header('location: /home');
}



function checkReguliereArrays() {
	if ($_POST["begin"]["straat"] == "" ||
		$_POST["begin"]["huisnummer"] == "" ||
		$_POST["begin"]["postcode"] == "" ||
		$_POST["begin"]["plaats"] == "" ||
		$_POST["eind"]["straat"] == "" ||
		$_POST["eind"]["huisnummer"] == "" ||
		$_POST["eind"]["postcode"] == "" ||
		$_POST["eind"]["plaats"] == "" ||
		$_POST["pakket"]["gewicht"] == "" ||
		$_POST["pakket"]["lengte"] == "" ||
		$_POST["pakket"]["breedte"] == "" ||
		$_POST["pakket"]["hoogte"] == "") {
		return false;
	} else {
		return true;
	}
}


function checkSpecificArrays() {
	if ($_POST["functie"] == "totaal" &&
		(isset($_POST["verzender"]) &
		(isset($_POST["ontvanger"])))) {

		// Check verzender array op lege velden
		switch ($_POST["verzender"]["type"]) {
			case 'particulier':

				if ($_POST["verzender"]["p_naam"] == "" ||
					$_POST["verzender"]["p_email"] == "" ||
					$_POST["verzender"]["achternaam"] == "") {
					return false;
				}

				break;
			case 'bedrijf':

				if ($_POST["verzender"]["b_naam"] == "" ||
					$_POST["verzender"]["b_email"] == "" ||
					$_POST["verzender"]["telefoon"] == "" ||
					$_POST["verzender"]["kvk"] == "" ||
					$_POST["verzender"]["iban"] == "") {
					return false;
				}

				break;

			default:
				throw new Exception("ERROR: geen geldig verzender type doorgegeven ('particulier' of 'bedrijf', lowcaps)", 1);
				exit();
		}

		// Verzender array is correct, ontvanger array:
		switch ($_POST["ontvanger"]["type"]) {
			case 'particulier':

				if ($_POST["ontvanger"]["p_naam"] == "" ||
					$_POST["ontvanger"]["achternaam"] == "") {
					return false;
				}

				break;
			case 'bedrijf':

				if ($_POST["ontvanger"]["b_naam"] == "") {
					return false;
				}

				break;

			default:
				throw new Exception("ERROR: geen geldig ontvanger type doorgegeven ('particulier' of 'bedrijf', lowcaps)", 1);
				exit();
		}

		return true;
	} else {
		return true;
	}
}


?>