<?php


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

function berekenBodekoerierPrijs($km) {
	if ($km < 40) {
		return 12.5;
	} else {
		return 40 + ($km*0.4);
	}
}

function berekenPietersenPrijs($km) {
	if ($km < 25) {
		return 10;
	} else {
		return $km*0.39 - 25*0.39 + 10;
	}
}





?>