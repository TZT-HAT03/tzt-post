<?php


// echo
function if_exists_in($array, $key, $data){
	foreach ($array as $array_data) {
		if ($array_data[$key] == $data) {
			return true;
		}
	}
	return false;
}



// algemene functies

// active class main menu
function activeClass($pageName, $navigationName) {
	if(!is_null($pageName) & !is_null($navigationName)) {
		if ($pageName == $navigationName) {
			return "active";
		}
	}
}

// is logged in?
function isLoggedIn() {
	if (isset($_SESSION["ingelogd"]) && $_SESSION["ingelogd"]) {
		// logged in
		return true;
	}
	// not logged in
	return false;
}

// check if email is allowed
function emailAllowed($pdoConnection, $email) {
	$stmt = $pdoConnection->prepare("SELECT email FROM treinkoerier WHERE email=:email;");
	$stmt->execute(array('email' => $email));
	$email_result = $stmt->fetchAll();

	if (count($email_result) == 1) {
		return false;
	} else {
		return true;
	}
}

/*
	SQL FUNCTIONS
*/

function getAllEmails($pdoConnection) {
	$stmt = $pdoConnection->prepare("SELECT email FROM treinkoerier;");
	$stmt->execute();
	$allEmails = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
	return $allEmails;
}

function getAllUsernames($pdoConnection) {
	$stmt = $pdoConnection->prepare("SELECT username FROM treinkoerier;");
	$stmt->execute();
	$allUsernames = $stmt->fetchAll(PDO::FETCH_COLUMN, 0);
	return $allUsernames;
}

function emailExist($pdoConnection, $givenEmail) {
	$emailArray = getAllEmails($pdoConnection);
	foreach ($emailArray as $singleEmail) {
		if ($singleEmail == $givenEmail) {
			return true;
		}
	}
	return false;
}

function makeTreinkoerierId($pdoConnection) {
	$stmt = $pdoConnection->prepare("SELECT treinkoerier_id FROM treinkoerier ORDER BY treinkoerier_id DESC LIMIT 1;");
	$stmt->execute();
	$treinkoerierId = $stmt->fetch(PDO::FETCH_COLUMN, 0);
	if ($treinkoerierId) {
		$koerierNummer = ltrim($treinkoerierId, 'T');
		$koerierNummer = ltrim($koerierNummer, '0');
		$koerierNummer ++;
		$koerierNummer = str_pad($koerierNummer, 7, '0', STR_PAD_LEFT);
	} else {
		$koerierNummer = "0000000";
	}


	return "T" . $koerierNummer;
}



?>