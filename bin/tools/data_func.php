<?php
// converto la data da americana ad italiana

//Converte la data americana in italiana..
// Diamo $_dataus
// Riceviamo $_datait

	// invio $_dataus ricevo $_datait
	ereg ("([0-9]{4})([-\./])([0-9]{2})([-\./])([0-9]{2})", $_dataus, $pezzi);
	$_datait = "$pezzi[5]-$pezzi[3]-$pezzi[1]";


?>