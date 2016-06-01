<?php
session_start();

include "../includes/database.php";

$koerier_id = $_SESSION["treinkoerier_id"];
$id = $_POST["id"];

$stmt = $pdo->prepare("UPDATE tussenstap SET koerier_id=:koerier_id WHERE pakket_id=:id AND tussenstap_type=2;");
$stmt->execute(array('koerier_id' => $koerier_id, 'id' => $id));


?>