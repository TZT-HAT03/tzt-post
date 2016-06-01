<?php
session_start();
date_default_timezone_set("Europe/Amsterdam");

include "includes/database.php";
include "includes/functions.php";

// is the form submitted?
if (isset($_POST['submit'])) {

	if (!isset($_POST["g-recaptcha-response"]) || $_POST["g-recaptcha-response"] == false) {
		$_SESSION['errorMessage'] = "reCapctha niet ingevuld.";
		header('Location: /login');
		exit();
	}

	// input fields in variables
	$email = strtolower($_POST['email']);
	$password = $_POST['password'];

	$_SESSION['email'] = $_POST['email'];

	// fields empty?
	if ($email == "" && $password != "") {

		$_SESSION['errorMessage'] = "Voer een emailadres in.";
		header('Location: /login');
		exit();

	} elseif ($password == "" && $email != "") {

		$_SESSION['errorMessage'] = "Voer een wachtwoord in.";
		header('Location: /login');
		exit();

	} elseif ($email == "" && $password == "") {

		$_SESSION['errorMessage'] = "Voer een emailadres en wachtwoord in.";
		header('Location: /login');
		exit();

	} else {

		$stmt = $pdo->prepare("SELECT actief FROM treinkoerier WHERE email = :email");
		$stmt->execute(array('email' => $email));
		$actief = $stmt->fetch();

		if (isset($actief["actief"]) & $actief["actief"] == 0) {
			$_SESSION['errorMessage'] = "Dit account is nog niet geaccepteerd door de beheerders. U krijgt zo spoedig mogelijk bericht of uw aanvraag geaccepteerd is of niet.";
			header('location: /login');
			exit();
		} else {

		}

		// check if treinkoeriers email exists
		if (emailExist($pdo, $email)) {

			// select all treinkoerier info
			$stmt = $pdo->prepare("SELECT * FROM treinkoerier WHERE email = :email;");
			$stmt->execute(array('email' => $email));
			$treinkoerier = $stmt->fetch();

			// create salt and hash from password
			$salt = $treinkoerier["salt"];
			$hashDatabase = $treinkoerier["password"];
			$password = $password . $salt;
			$hashPassword = hash("sha256", $password);

			// is the password correct?
			if ($hashPassword == $hashDatabase) {

		 		// clear session for deleting error messages
		 		$_SESSION = array();
		 		// add treinkoerier data to session
		 		$_SESSION['persoon_id'] = $treinkoerier['persoon_id'];
		 		$_SESSION['treinkoerier_id'] = $treinkoerier['treinkoerier_id'];
				$_SESSION['email'] = $treinkoerier['email'];
				$_SESSION["ingelogd"] = true;

				header('Location: home');
				exit();

			} else {

				// wrong password message
				$_SESSION['errorMessage'] = "Deze combinatie wordt niet herkent.";

				header('Location: login');
				exit();

			}

		} else {

			// wrong email message
			$_SESSION['errorMessage'] = "Deze combinatie wordt niet herkent.";

			header('Location: login');
			exit();

		}
	}
} else {
	header('Location: login');
	exit();
}


?>