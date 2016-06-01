<?php
session_start();
date_default_timezone_set("Europe/Amsterdam");
setlocale(LC_TIME, 'NL_nl');
include "includes/database.php";
include "includes/functions.php";

if (isLoggedIn()) {
	header('location: /profiel');
	exit();
}

// are the fields filled in?
if (isset($_POST['submit'])) {
	print_r($_POST);

	// input fields into variables
	$email = $_POST['email'];
	$password1 = $_POST['password1'];
	$password2 = $_POST['password2'];

	// other traincourier input fields
	$voornaam = $_POST["voornaam"];
	$achternaam = $_POST["achternaam"];
	$geslacht = $_POST["geslacht"];
	$geb_datum = date('Y-m-d H:i:s', strtotime($_POST["geb_datum"]));
	$straat = $_POST["straat"];
	$huisnummer = $_POST["huisnummer"];
	$postcode = $_POST["postcode"];
	$plaats = $_POST["plaats"];
	$telefoon = $_POST["telefoon"];
	$bsn = $_POST["bsn"];
	$doc_nummer = $_POST["doc_nummer"];
	print($geb_datum);

	// reset SESSION values
	$_SESSION = array();

	// this info in the session so the user does no have to fill this in again after a validation error
	$_SESSION['email'] = $email;
	$_SESSION['registrationValidationError'] = false;


	// form validation
	if ($email == "") {
		$_SESSION['emailErrorMessage'] = "Voer een emailadres in.";
		$_SESSION['registrationValidationError'] = true;
	}
	if ($password1 == "" || $password2 == "") {
		$_SESSION['passwordErrorMessage'] = "Voer een wachtwoord in.";
		$_SESSION['registrationValidationError'] = true;
	}
	if ($password1 != $password2) {
		$_SESSION['passwordMatchErrorMessage'] = "Wachtwoorden komen niet overeen.";
		$_SESSION['registrationValidationError'] = true;
	}

	if (strlen($password1) < 6 || !preg_match('/[A-Z]/', $password1) || !preg_match('/[0-9]/', $password1)) {
		$_SESSION['passwordMatchErrorMessage'] = "Het wachtwoord moet minimaal 6 tekens bevatten, waarvan tenminste 1 hoofdletter en 1 cijfer.";
		$_SESSION['registrationValidationError'] = true;
	}

	// check if username is unique

	// check if emailadress is unique
	if (!emailAllowed($pdo, $email)) {
		$_SESSION['emailErrorMessage'] = "Emailadres is al in gebruik.";
		$_SESSION['registrationValidationError'] = true;
	}

	// generate hash
    $salt = mcrypt_create_iv(128, MCRYPT_RAND);
    $password = $password1 . $salt;
    $hash = hash("sha256", $password);

    // generate activation hash
    $activate_hash = base64_encode(mcrypt_create_iv(64, MCRYPT_RAND));

	// if there is no error add data into database
	if (!$_SESSION['registrationValidationError']) {

		$stmt = $pdo->prepare("INSERT INTO persoon (email, voornaam, achternaam) VALUES (:email, :voornaam, :achternaam) ON DUPLICATE KEY UPDATE voornaam=:voornaam, achternaam=:achternaam;");
		if ($stmt->execute(array('email' => $email, 'voornaam' => $voornaam, 'achternaam' => $achternaam))) {
			$insertError = false;
		} else {
			print_r($pdo->errorInfo());
			$insertError = true;
		}

		if (!$insertError) {
			$stmt = $pdo->prepare("SELECT persoon_id FROM persoon WHERE email = :email AND voornaam = :voornaam AND achternaam = :achternaam ORDER BY persoon_id DESC");
			$stmt->execute(array('email' => $email, 'voornaam' => $voornaam, 'achternaam' => $achternaam));
			$persoon_id = $stmt->fetch(PDO::FETCH_COLUMN, 0);
			$treinkoerier_id = makeTreinkoerierId($pdo);

			$geb_datum = strptime($geb_datum, '%Y-%m-%d %H:%M:%M');



			$stmt = $pdo->prepare("INSERT INTO treinkoerier
				(persoon_id, treinkoerier_id, email, password, salt, actief, geslacht, gebdatum, straat, huisnr, postcode, plaats, telefoon, bsn, documentnr)
				VALUES
				(:persoon_id, :treinkoerier_id, :email, :password, :salt, :actief, :geslacht, :gebdatum, :straat, :huisnummer, :postcode, :plaats, :telefoon, :bsn, :documentnr);");

			if ($stmt->execute(array('persoon_id' => $persoon_id, 'treinkoerier_id' => $treinkoerier_id, 'email' => $email, 'password' => $hash, 'salt' => $salt, 'actief' => 0, 'geslacht' => $geslacht, 'gebdatum' => $geb_datum, 'straat' => $straat, 'huisnummer' => $huisnummer, 'postcode' => $postcode, 'plaats' => $plaats, 'telefoon' => $telefoon, 'bsn' => $bsn, 'documentnr' => $doc_nummer)) === true) {
				$_SESSION = array();
				$_SESSION["register-confirm"] = true;
				$insertError = false;
			} else {
				echo "Foutmelding: ";
				print_r($pdo->errorInfo());
				$_SESSION["register-confirm"] = false;
				$insertError = true;
			}
		}

		//header("location: /treinkoeriers");
		exit();


	} else {

		// if there is a validation error redirect the user back to the registration page and show the error messages.
		//header("location: /treinkoeriers#aanmelden");
		//exit();

	}

} else {
	// if for some reason a user came here
	//header("location: /home");
	//exit();
}

?>