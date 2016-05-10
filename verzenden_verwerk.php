<?php
session_start();
date_default_timezone_set("Amsterdam/Europe");

// Check wether form is submitted or not
if (isset($_POST["submit"])) {

	$verzender = $_POST["verzender"];
	$verzend_adres = $_POST["traject"][0];

	$ontvanger = $_POST["ontvanger"];
	$ontvangst_adres = $_POST["traject"][1];


	echo "verzender<br>";
	print_r($verzender);
	echo "<br><br>";

	echo "verzend_adres<br>";
	print_r($verzend_adres);
	echo "<br><br>";

	echo "ontvanger<br>";
	print_r($ontvanger);
	echo "<br><br>";

	echo "ontvangst_adres<br>";
	print_r($ontvangst_adres);
	echo "<br><br>";



} else {
	header('location: /home');
	exit();
}