<?php

// NS API waarden
define("NS_user", "elstreekie@gmail.com");
define("NS_pass", "ZyXxcCkE4eEl42RYEk7rmo07mNi4-9ssLKYyqPMDiONwuhIeeYcO0w");

// Google API waarden
define("GMAPS_key", "AIzaSyBjqKB6IcFNxbqS8GwgFHyJcPOKofuiTZM");


function berekenKosten($begin, $eind, $pakket) {
	// Haal stations op
	$stations = simplexml_load_file("http://".NS_user.":".NS_pass."@webservices.ns.nl/ns-api-stations-v2");

	// De kosten beginnen bij 0
	$kosten = 0;

	// Kijken of er geldige data mee is gestuurd, en geef errors wanneer dit niet zo is.
	if (isset($begin) && count($begin) < 1) {
		throw new Exception("Geen geldige begin array, of lege array doorgestuurd. Kan kosten niet berekenen", 1);
		exit();
	}
	if (isset($begin) && count($begin) < 1) {
		throw new Exception("Geen geldige eind array, of lege array doorgestuurd. Kan kosten niet berekenen", 2);
		exit();
	}

	// Haal de stationscodes op als die er zijn, er wordt false doorgegeven wanneer er geen station is op die plaats
	$beginStationCode = plaatsHeeftStation(geefStationDoor($begin), $stations);
	$eindStationCode = plaatsHeeftStation(geefStationDoor($eind), $stations);

	// Als beide plaatsen een station hebben is het true & true
	if (reisType($begin, $eind, $pakket, $stations) == "trein") {
		// Gaat via de trein
		// Bereken lengte van autoreizen
		$tussenstops = getTreinTussenstops($beginStationCode, $eindStationCode, $stations);
		$eersteKoerierKm = berekenAfstand($begin, $tussenstops[0]);
		$tweedeKoerierKm = berekenAfstand($tussenstops[1], $eind);

		// Tel alle kosten bij elkaar op
		$kosten1 = berekenOptimaleKosten($eersteKoerierKm);
		$kosten2 = berekenTreinKoerierPrijs();
		$kosten3 = berekenOptimaleKosten($tweedeKoerierKm);

		// Totale kosten, er komt nog 20% bij als winst voor TZT
		$totaal	= ($kosten1 + $kosten2 + $kosten3) * 1.2;

		// Echo de kosten in het juiste format
		echo formatKosten($totaal);
	} else {
		// Gaat met bodekoerier
		$koerierAfstand = berekenAfstand($begin, $eind);
		// Bereken kosten, er komt nog 20% bij als winst voor TZT
		$kosten = berekenOptimaleKosten($koerierAfstand) * 1.2;
		// Echo de kosten in het juiste format
		echo formatKosten($kosten);
	}
}


function berekenRoute($begin, $eind, $pakket, $output) {
	// Kijken of er geldige data mee is gestuurd, en geef errors wanneer dit niet zo is.
	if (isset($begin) && count($begin) < 1) {
		throw new Exception("ERROR: Geen geldige begin array, of lege array doorgestuurd. Kan route niet berekenen", 1);
		exit();
	}
	if (isset($begin) && count($begin) < 1) {
		throw new Exception("ERROR: Geen geldige eind array, of lege array doorgestuurd. Kan route niet berekenen", 2);
		exit();
	}

	// Stel route array op
	$route = array();
	$route["begin"] = $begin;
	$route["eind"] = $eind;
	// Haal de stations op met de NS API
	$stations = simplexml_load_file("http://".NS_user.":".NS_pass."@webservices.ns.nl/ns-api-stations-v2");

	$route["pakket"] = $pakket;

	// Heeft de beginplaats een station?
	$route["begin"]["heeft-station"] = plaatsHeeftStation(geefStationDoor($begin), $stations);

	// Heeft de eindplaats een station?
	$route["eind"]["heeft-station"] = plaatsHeeftStation(geefStationDoor($eind), $stations);


	// Stuur met de trein als begin- en eindplaats een station heeft, anders met de koeriers
	if (reisType($begin, $eind, $pakket, $stations) == "trein") {
		// Probeer met de trein te sturen
		$route["type"] = "trein";
		// Haal reisadviezen op met de NS API
		$reisadviezen = simplexml_load_file("http://".NS_user.":".NS_pass."@webservices.ns.nl/ns-api-treinplanner?fromStation=".$route["begin"]["heeft-station"]."&toStation=".$route["eind"]["heeft-station"]);
		// Bereken de duur van de treinreis, als deze groter is dan 4.5 uur, moet het pakketje met de koerier.
		if(getTreinMaxTijd($reisadviezen) > 270) {
			// Moet verder met de koerier
			$route["type"] = "koerier";
		} else {
			// Mag verder met de trein
			// Voeg treinduur toe aan reisduur, ga altijd uit van de langste reistijd
			$route["duur"]["totaal"] = getTreinMaxTijd($reisadviezen);
			$route["duur"]["trein"] = getTreinMaxTijd($reisadviezen);

			// Voeg de tussenstops toe aan de reis
			$route["tussenstops"] = getTreinTussenstops($route["begin"]["heeft-station"], $route["eind"]["heeft-station"], $stations);

			// Populate de duur array van de route met verschillende waarden:
			$route["duur"]["totaal"] += berekenTijd($route["begin"], $route["tussenstops"][0]);
			$route["duur"]["begin->station"] = berekenTijd($route["begin"], $route["tussenstops"][0]);

			$route["duur"]["totaal"] += berekenTijd($route["tussenstops"][1], $route["eind"]);
			$route["duur"]["station->eind"] = berekenTijd($route["tussenstops"][1], $route["eind"]);

			$json = json_encode($route, JSON_PRETTY_PRINT);

			switch ($output) {
				case 'json':
					print $json;
					break;
				case 'return':
					return $route;
					break;
			}
		}
	} else {
		// Pakketje gaat met de koerier
		$route["type"] = "koerier";
	}

	// Als reistype is gewijzigd naar, of "koerier" is, moet het pakketje met de koerier worden gestuurd.
	if ($route["type"] == "koerier") {
		$route["tussenstops"] = null;

		$route["duur"]["totaal"] = berekenTijd($route["begin"], $route["eind"]);
		$route["duur"]["trein"] = 0;
		$route["duur"]["begin->station"] = 0;
		$route["duur"]["station->eind"] = 0;

		$json = json_encode($route, JSON_PRETTY_PRINT);

		switch ($output) {
			case 'json':
				print $json;
				break;
			case 'return':
				return $route;
				break;
		}
	}
}


function insertInDatabase($route, $verzender, $ontvanger, $pdo) {
	$pakket = $route["pakket"];
	$pakket["trackingnr"] = makeTrackingnr($pdo);

	// Format ontvanger voor database
	if ($ontvanger["type"] == 'particulier') {
		$o_naam = $ontvanger["p_naam"] . " " . $ontvanger["achternaam"];
	} else
	if ($ontvanger["type"] == 'bedrijf') {
		$o_naam = $ontvanger["b_naam"];
	}

	$straat = $route["eind"]["straat"];
	$plaats = $route["eind"]["plaats"];
	$postcode = $route["eind"]["postcode"];
	$huisnr = $route["eind"]["huisnummer"];

	// INSERT ontvanger in ontvanger table
	$stmt = $pdo->prepare("INSERT INTO ontvanger(naam, straat, plaats, postcode, huisnr) VALUES (:naam, :straat, :plaats, :postcode, :huisnr) ON DUPLICATE KEY UPDATE naam=:naam, straat=:straat, plaats=:plaats, postcode=:postcode, huisnr=:huisnr;");
	if (!$stmt->execute(array('naam' => $o_naam, 'straat' => $straat, 'postcode' => $postcode, 'plaats' => $plaats, 'huisnr' => $huisnr))) {
		throw new Exception("ERROR: Probleem met inserten van ontvanger in database", 1);
	}

	// Haal id op van de zojuist ingevoerde ontvanger
	$stmt = $pdo->prepare("SELECT ontvanger_id FROM ontvanger WHERE naam=:naam AND straat=:straat AND huisnr=:huisnr AND postcode=:postcode AND plaats=:plaats LIMIT 1;");
	if (!$stmt->execute(array('naam' => $o_naam, 'straat' => $straat, 'postcode' => $postcode, 'plaats' => $plaats, 'huisnr' => $huisnr))) {
		throw new Exception("ERROR: Probleem met ophalen van ontvanger_id uit database", 1);
	} else {
		$o_id = $stmt->fetch(PDO::FETCH_COLUMN, 0);
	}

	if ($verzender["type"] == "particulier") {
		// Verzender is een particulier
		$v_email = $verzender["p_email"];
		$v_voornaam = $verzender["p_naam"];
		$v_achternaam = $verzender["achternaam"];

		// INSERT verzender in persoon table als deze nog niet bestaat, en anders UPDATE op basis va email
		$stmt = $pdo->prepare("INSERT INTO persoon(email, voornaam, achternaam) VALUES (:email, :voornaam, :achternaam) ON DUPLICATE KEY UPDATE voornaam=:voornaam, achternaam=:achternaam;");
		if (!$stmt->execute(array('voornaam' => $v_voornaam, 'achternaam' => $v_achternaam, 'email' => $v_email))) {
			throw new Exception("ERROR: Probleem met inserten van persoon in database", 1);
		}

		// Haal persoon_id op die zojuist is ingevoegd
		$stmt = $pdo->prepare("SELECT persoon_id FROM persoon WHERE email=:email AND voornaam=:voornaam AND achternaam=:achternaam LIMIT 1;");
		if (!$stmt->execute(array('voornaam' => $v_voornaam, 'achternaam' => $v_achternaam, 'email' => $v_email))) {
			throw new Exception("ERROR: Probleem met ophalen van persoon_id uit database", 1);
		} else {
			$v_persoon_id = $stmt->fetch(PDO::FETCH_COLUMN, 0);
			$v_id = makeParticulierId($pdo);
		}

		// INSERT persoon in particulier table als deze persoon nog niet bestaat.
		$stmt = $pdo->prepare("INSERT IGNORE INTO particulier(persoon_id, particulier_id) VALUES (:persoon_id, :particulier_id);");
		if (!$stmt->execute(array('persoon_id' => $v_persoon_id, 'particulier_id' => $v_id))) {
			throw new Exception("ERROR: Probleem met inserten van particulier in database", 1);
		}

		// Haal particulier_id op voor als de query ignored werd:
		$stmt = $pdo->prepare("SELECT particulier_id FROM particulier WHERE persoon_id=:persoon_id LIMIT 1;");
		if (!$stmt->execute(array('persoon_id' => $v_persoon_id))) {
			throw new Exception("ERROR: Probleem met ophalen van particulier_id uit database", 1);
		} else {
			$v_id = $stmt->fetch(PDO::FETCH_COLUMN, 0);
		}

	} else {
		// Verzender is een bedrijf
		$v_email = $verzender["b_email"];
		$v_naam = $verzender["b_naam"];
		$v_telefoon = $verzender["telefoon"];
		$v_kvk = $verzender["kvk"];
		$v_iban = $verzender["iban"];

		$v_id = makeBedrijfId($pdo);

		// INSERT persoon in bedrijf table als deze persoon nog niet bestaat.
		$stmt = $pdo->prepare("INSERT IGNORE INTO bedrijf(bedrijf_id, naam, email, telefoon, kvknr, iban) VALUES (:bedrijf_id, :naam, :email, :telefoon, :kvknr, :iban);");
		if (!$stmt->execute(array('bedrijf_id' => $v_id, 'email' => $v_email, 'naam' => $v_naam, 'telefoon' => $v_telefoon, 'kvknr' => $v_kvk, 'iban' => $v_iban))) {
			throw new Exception("ERROR: Probleem met inserten van bedrijf in database", 1);
		}

		// Haal id op van de zojuist ingevoerde ontvanger
		$stmt = $pdo->prepare("SELECT bedrijf_id FROM bedrijf WHERE naam=:naam AND email=:email AND telefoon=:telefoon AND kvknr=:kvknr AND iban=:iban LIMIT 1;");
		if (!$stmt->execute(array('email' => $v_email, 'naam' => $v_naam, 'telefoon' => $v_telefoon, 'kvknr' => $v_kvk, 'iban' => $v_iban))) {
			throw new Exception("ERROR: Probleem met ophalen van bedrijf_id uit database", 1);
		} else {
			$v_id = $stmt->fetch(PDO::FETCH_COLUMN, 0);
		}

	}

	$trackingnr = $pakket["trackingnr"];
	$gewicht = $pakket["gewicht"];
	$lengte = $pakket["lengte"];
	$breedte = $pakket["breedte"];
	$hoogte = $pakket["hoogte"];

	// INSERT in pakket
	$stmt = $pdo->prepare("INSERT INTO pakket(verzender_id, ontvanger_id, trackingnr, gewicht, lengte, breedte, hoogte) VALUES (:verzender_id, :ontvanger_id, :trackingnr, :gewicht, :lengte, :breedte, :hoogte);");
	if (!$stmt->execute(array('verzender_id' => $v_id, 'ontvanger_id' => $o_id, 'trackingnr' => $trackingnr, 'gewicht' => $gewicht, 'lengte' => $lengte, 'breedte' => $breedte, 'hoogte' => $hoogte))) {
		throw new Exception("ERROR: Probleem met inserten van pakket in database", 1);
	}

	// Haal id op van het zojuist ingevoerde pakket
	$stmt = $pdo->prepare("SELECT MAX(pakket_id) FROM pakket WHERE verzender_id=:verzender_id AND ontvanger_id=:ontvanger_id AND gewicht=:gewicht AND lengte=:lengte AND breedte=:breedte AND hoogte=:hoogte LIMIT 1;");
	if (!$stmt->execute(array('verzender_id' => $v_id, 'ontvanger_id' => $o_id, 'gewicht' => $gewicht, 'lengte' => $lengte, 'breedte' => $breedte, 'hoogte' => $hoogte))) {
		throw new Exception("ERROR: Probleem met ophalen van pakket_id uit database", 1);
	} else {
		$pakket_id = $stmt->fetch(PDO::FETCH_COLUMN, 0);
	}

	// INSERT route
	$f_route = array();
	$f_route[0] = $route["begin"];
	$f_route[0]["tussenstaptype"] = 1;

	if ($route["tussenstops"] != null) {
		for ($i=1; $i < count($route["tussenstops"]) + 1; $i++) {
			$f_route[$i] = $route["tussenstops"][$i - 1];
			$f_route[$i]["tussenstaptype"] = 2;
		}
	} else {

	}

	$f_route[3] = $route["eind"];
	$f_route[3]["tussenstaptype"] = 3;

	$i = 1;
	foreach ($f_route as $tussenstap) {
		$tussenstapnr = $i;
		$tussenstap_type = $tussenstap["tussenstaptype"];
		$straat = $tussenstap["straat"];
		$huisnr = $tussenstap["huisnummer"];
		$postcode = $tussenstap["postcode"];
		$plaats = $tussenstap["plaats"];
		$verwacht_ts = "2016-04-05 12:01:02"; //TODO: Verander naar een werkelijk tijdstip
		// INSERT in tussenstap
		$stmt = $pdo->prepare("INSERT INTO tussenstap(pakket_id, tussenstapnr, tussenstap_type, straat, huisnr, postcode, plaats, verwacht_ts) VALUES (:pakket_id, :tussenstapnr, :tussenstap_type, :straat, :huisnr, :postcode, :plaats, :verwacht_ts);");
		if(!$stmt->execute(array('pakket_id' => $pakket_id, 'tussenstapnr' => $tussenstapnr, 'tussenstap_type' => $tussenstap_type, 'straat' => $straat, 'huisnr' => $huisnr, 'postcode' => $postcode, 'plaats' => $plaats, 'verwacht_ts' => $verwacht_ts))) {
			throw new Exception("ERROR: Probleem met inserten van tussenstap ".$i." in de database: ". $e->getMessage(), 1);
		}
		$i++;
	}

	echo $trackingnr;

}


function reisType($beginStationCode, $eindStationCode, $pakket, $stations) {

	if ($beginStationCode != $eindStationCode) {
		// pakket valideren op grootte en gewicht
		if ($pakket["gewicht"] <= 15 & $pakket["lengte"] <= 50 & $pakket["breedte"] <= 50 & $pakket["hoogte"] <= 50) {
			return "trein";
		} else {
			return "bode";
		}
	} else {
		return "bode";
	}

}

//~~ Functies met de NS API ~~//
function plaatsHeeftStation($plaats, $stations) {
	// Default heeft een plaats geen station
	$heeft_station = false;
	// Loop alle stations door
	foreach ($stations->Station as $station) {
		// Alleen stations in NL
		if ($station->Land == "NL") {
			// Bekijk alle namen, Kort, Medium, Lang
			foreach ($station->Namen as $namen) {
				// Vergelijk alle namen (kort, middel, en lang);
				if (strpos($namen->Kort, $plaats) !== false) {
					$heeft_station = true;
				} else
				if (strpos($namen->Middel, $plaats) !== false) {
					$heeft_station = true;
				} else
				if (strpos($namen->Lang, $plaats) !== false) {
					$heeft_station = true;
				}
			}
			// Als $heeft_station == true, geef dan de stationscode door
			if ($heeft_station) {
				return (string)$station->Code;
				break;
			}
		}
	}
	// Geen hits, geef false door, geen station op deze plek
	return false;
}

function getTreinMaxTijd($reisadviezen) {
	// Bereken de maximale tijd van een treinreis
	$tijd = "";
	// Voor elk reisadvies als reismogelijkheid
	foreach ($reisadviezen->ReisMogelijkheid as $reismogelijkheid) {
		// Als de tijd van de reismogelijkheid langer is dan de waarde van $tijd, override deze dan.
		if ($tijd < (string)$reismogelijkheid->GeplandeReisTijd) {
			$tijd = (string)$reismogelijkheid->GeplandeReisTijd;
		}
	}
	// Geef
	return geschrevenTijdNaarMinuten($tijd);
}

function geefStationDoor($stop) {
	// Filter de steden met meerdere stations waarvan een klein station eerder in de lijst staat, zodat de grote stations worden doorgegeven.
	switch ($stop["plaats"]) {
		case 'Amsterdam':
			return 'Amsterdam Centraal';
			break;
		case 'Rotterdam':
			return 'Rotterdam Centraal';
			break;
		case 'Almere':
			return 'Almere Centraal';
			break;

		default:
			return $stop["plaats"];
			break;
	}
}

function geschrevenTijdNaarMinuten($tijd) {
	$parsed = date_parse($tijd);
	$totaal = $parsed['hour'] * 60 + $parsed['minute'];
	return $totaal;
}

function getTreinTussenstops($beginCode, $eindCode, $stations) {
	$tussenstops = array();
	// Loop alle stations door, en doe iets als gelijk is aan beginstation of eindstation
	foreach ($stations->Station as $station) {
		if ($station->Code == $beginCode) {
			// Haal geolocatie op
			$lat = $station->Lat;
			$lng = $station->Lon;
			// Laad het adres met Google Maps Geocoding API, als street_address voor elke keer hetzelfde format
			$results = simplexml_load_file("https://maps.googleapis.com/maps/api/geocode/xml?result_type=street_address&latlng=".$lat.",".$lng."&key=".GMAPS_key);
			// Krijg de specifieke waarden van het adres:
			$locatie = $results->result[0]->address_component;
			// Voeg toe aan de tussenstops array
			$tussenstops[0] = fetchPlaats($locatie);
		}
		if ($station->Code == $eindCode) {
			// Haal geolocatie op
			$lat = $station->Lat;
			$lng = $station->Lon;
			// Laad het adres met Google Maps Geocoding API, als street_address voor elke keer hetzelfde format
			$results = simplexml_load_file("https://maps.googleapis.com/maps/api/geocode/xml?result_type=street_address&latlng=".$lat.",".$lng."&key=".GMAPS_key);
			// Krijg de specifieke waarden van het adres:
			$locatie = $results->result[0]->address_component;
			// Voeg toe aan de tussenstops array
			$tussenstops[1] = fetchPlaats($locatie);
		}

	}

	return $tussenstops;
}

function fetchPlaats($locatie) {
	// Elke locatie heeft verschillende componenten
	foreach ($locatie as $component) {
		// Voor elk component, bekijk welk type het is, en sla het op als variabele
		switch ($component->type) {
			case 'street_number':
				$huisnummer = 	(string)$component->long_name;
				break;
			case 'route':
				$straat = 		(string)$component->long_name;
				break;
			case 'administrative_area_level_2':
				// Fallback voor plaats als straat leeg is.
				$plaats =		(string)$component->long_name;
				break;
			case 'locality':
				$plaats =		(string)$component->long_name;
				break;
			case 'postal_code_prefix':
				// Fallback voor postcode als postcode leeg is
				$postcode =		(string)$component->long_name;
				break;
			case 'postal_code':
				$postcode =		(string)$component->long_name;
				break;
		}
	}
	// Als huisnummer niet is doorgegeven, maak er 1 van.
	if (!isset($huisnummer)) {
		$huisnummer = 1;
	}

	// Maak resultaat array en geef deze door
	$resultaat = array();
	$resultaat["straat"] = $straat;
	$resultaat["huisnummer"] = $huisnummer;
	$resultaat["plaats"] = $plaats;
	$resultaat["postcode"] = $postcode;

	return $resultaat;
}


//~~ Functies met Google Maps API ~~//
function berekenAfstand($begin, $eind) {
	// Maak strings voor in de url van de adressen
	$beginAdres = urlencode($begin["straat"] . " " . $begin["huisnummer"] . " " . $begin["postcode"] . " " . $begin["plaats"]);
	$eindAdres = urlencode($eind["straat"] . " " . $eind["huisnummer"] . " " . $eind["postcode"] . " " . $eind["plaats"]);

	// Gebruik de Google Maps Directions API voor de afstand
	$route = simplexml_load_file("https://maps.googleapis.com/maps/api/directions/xml?origin=".$beginAdres."&destination=".$eindAdres."&key=".GMAPS_key);

	if ((string)$route->status != "ZERO_RESULTS") {
		return (int)$route->route->leg->distance->value / 1000;
	} else {
		throw new Exception("ERROR: Geen geldig adres gevonden", 1);
		exit();
	}
}

function berekenTijd($begin, $eind) {
	// Maak strings voor in de url van de adressen
	$beginAdres = urlencode($begin["straat"] . " " . $begin["huisnummer"] . " " . $begin["postcode"] . " " . $begin["plaats"]);
	$eindAdres = urlencode($eind["straat"] . " " . $eind["huisnummer"] . " " . $eind["postcode"] . " " . $eind["plaats"]);

	// Gebruik de Google Maps Directions API voor de afstand
	$route = simplexml_load_file("https://maps.googleapis.com/maps/api/directions/xml?alternatives=true&origin=".$beginAdres."&destination=".$eindAdres."&key=".GMAPS_key);

	if ((string)$route->status != "ZERO_RESULTS") {
		return ceil((int)$route->route->leg->duration->value / 60);
	} else {
		throw new Exception("ERROR: Geen geldig adres gevonden", 1);
		exit();
	}
}

//~~ Trackingnr ~~//
function makeTrackingnr($pdo) {
	$stmt = $pdo->prepare("SELECT trackingnr FROM pakket ORDER BY trackingnr DESC LIMIT 1;");
	$stmt->execute();
	$trackingnr = $stmt->fetch(PDO::FETCH_COLUMN, 0);
	if ($trackingnr) {
		$trackingnr = ++$trackingnr . PHP_EOL;
	} else {
		$trackingnr = "AA0000";
	}
	return $trackingnr;
}


//~~ Functies voor het berekenen van een prijs ~~//
function berekenOptimaleKosten($km) {
	// Bereken alle mogelijke kosten
	$fietskoerier = berekenFietskoerierPrijs($km);
	$bodekoerier = berekenBodekoerierPrijs($km);
	$pietersenkoerier = berekenPietersenPrijs($km);

	// Filtreer de laagste eruit en geef deze door
	$laagsteKosten = min($fietskoerier, $bodekoerier, $pietersenkoerier);
	return $laagsteKosten;
}

// Berekening voor de fietskoerier
function berekenFietskoerierPrijs($km) {
	if ($km < 4) {
		return 9;
	} else
	if ($km < 8) {
		return 14;
	} else
	if ($km < 13) {
		return 19;
	} else {
		return 15 + ($km*0.56);
	}
}

// Berekening voor de bodekoerier
function berekenBodekoerierPrijs($km) {
	if ($km < 40) {
		return 12.5;
	} else {
		return 40 + ($km*0.4);
	}
}

// Berekening voor de pietersenkoerier
function berekenPietersenPrijs($km) {
	if ($km < 25) {
		return 10;
	} else {
		return $km*0.39;
	}
}

// Berekening voor de treinkoerier
function berekenTreinKoerierPrijs() {
	return 3;
}



// Formatteer double of int naar leesbare kosten: 25,- of 32,98
function formatKosten($kosten) {
	$kosten = sprintf('%01.2f', $kosten);
	$kosten = str_replace(".", ",", $kosten, $aanpassingen);
	if ($aanpassingen == 0) { // Dit betekend dat er geen punt is aangepast naar een komma, en de kosten dus een rond getal waren
		// Maak van 21 -> 21,-
		$kosten .= ",-";
	}
	return $kosten;
}


function makeBedrijfId($pdoConnection) {
	$stmt = $pdoConnection->prepare("SELECT bedrijf_id FROM bedrijf ORDER BY bedrijf_id DESC LIMIT 1;");
	$stmt->execute();
	$treinkoerierId = $stmt->fetch(PDO::FETCH_COLUMN, 0);
	if ($treinkoerierId) {
		$koerierNummer = ltrim($treinkoerierId, 'B');
		$koerierNummer = ltrim($koerierNummer, '0');
		$koerierNummer ++;
		$koerierNummer = str_pad($koerierNummer, 7, '0', STR_PAD_LEFT);
	} else {
		$koerierNummer = "0000000";
	}


	return "B" . $koerierNummer;
}


function makeParticulierId($pdoConnection) {
	$stmt = $pdoConnection->prepare("SELECT particulier_id FROM particulier ORDER BY particulier_id DESC LIMIT 1;");
	$stmt->execute();
	$treinkoerierId = $stmt->fetch(PDO::FETCH_COLUMN, 0);
	if ($treinkoerierId) {
		$koerierNummer = ltrim($treinkoerierId, 'P');
		$koerierNummer = ltrim($koerierNummer, '0');
		$koerierNummer ++;
		$koerierNummer = str_pad($koerierNummer, 7, '0', STR_PAD_LEFT);
	} else {
		$koerierNummer = "0000000";
	}


	return "P" . $koerierNummer;
}

?>