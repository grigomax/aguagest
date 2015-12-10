<?php

/*
  Files di aggiornamento programma, al nuovo motore stampe documenti.
 * Passiamo tutti i documenti che sono contenuti all'interno della cartella layout_doc
 * nella cartella report..
 * Ma prima dobbiamo verificare che ci siano i permessi di scritura
 */

//dobbiamo creare una directory per poter usare il lettore dei codice a barre
//la directory si chiamerà upload e sara assieme a bin sulla directory principale
//del programma

echo "Cambio destinazione d'uso delle directory \n";

if (!file_exists("../../../setting"))
{

	if (rename("../../../report", "../../../setting"))
	{
		echo "<br>Directory report cambiata correttamente in Setting\n";
		$fine = "1";
	}
	else
	{
		echo("<br>Non posso cambiare la directory!");
		$fine = "0";
		exit;
	}
}
else
{
	echo "<br>Directory setting gi&agrave; cambiata\n";
	$fine = "1";
}

if (!file_exists("../../../spool"))
{

	if (rename("../../../fpdf-gen", "../../../spool"))
	{
		echo "<br>Directory fpdf-gen cambiata correttamente in Spool\n";
		$fine = "1";
	}
	else
	{
		echo("<br>Non posso cambiare la directory!");
		$fine = "0";
		exit;
	}
}
else
{
	echo "<br>Directory spool gi&agrave; cambiata\n";
	$fine = "1";
}
?>