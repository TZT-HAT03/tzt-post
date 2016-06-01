<?php

$host = "host=sql7.freemysqlhosting.net;";
$dbname = "dbname=sql7115322;";
$port = "port=3306";
$database = "mysql:" . $host . $dbname .$port;
$user = "sql7115322";
$pass = "AiRKLLIZeZ";

try {
	$pdo = new PDO($database, $user, $pass);
} catch(PDOException $exception) {
	echo $exception->getMessage();
}

?>