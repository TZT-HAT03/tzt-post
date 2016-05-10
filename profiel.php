<?php
session_start();

include "includes/database.php";
include 'includes/functions.php';

if (isLoggedIn()) {
    $gebruiker_id = $_SESSION['persoon_id'];
} else {
	header('location: /login');
	exit();
}

// pageinfo
$page_name = "profiel";

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
		</div>
	</div>

	<div class="container section">

		<p><?php print_r($_SESSION); ?></p>

	</div>

</main>

<?php include "includes/footer.php" ?>

</body>
</html>