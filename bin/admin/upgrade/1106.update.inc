<?php

/*
  File di aggiornamento programma fisico..
 * eliminiamo un po di files che non servono più
 * come le stampe inventario ecc..
 * Ma prima dobbiamo verificare che ci siano i permessi di scritura
 */

//versione file 1106
//eliminazione dei file non più necessari..

if (file_exists("../../../setting/maschera_art.php"))
{
	if (unlink("../../../setting/maschera_art.php"))
	{
		echo "<br>Eliminazione maschera articoli\n";
	}
}

if (file_exists("../../../setting/listino_pdf_figurato.php"))
{
	if (unlink("../../../setting/listino_pdf_figurato.php"))
	{
		echo "<br>Eliminazione listino_1.php\n";
	}
}

if (file_exists("../../../setting/listino_pdf_figurato2.php"))
{
	if (unlink("../../../setting/listino_pdf_figurato2.php"))
	{
		echo "<br>Eliminazione listino_2.php\n";
	}
}

if (file_exists("../../../setting/listino_pdf_figurato_3.php"))
{
	if (unlink("../../../setting/listino_pdf_figurato_3.php"))
	{
		echo "<br>Eliminazione listino_3.php\n";
	}
}

if (file_exists("../../../setting/rep_rimanenze_mer.php"))
{
	if (unlink("../../../setting/rep_rimanenze_mer.php"))
	{
		echo "<br>Eliminazione rimanenze.php\n";
	}
}

if (file_exists("../../../setting/rep_rimanenze_tip.php"))
{
	if (unlink("../../../setting/rep_rimanenze_tip.php"))
	{
		echo "<br>Eliminazione rimanenze tip.php\n";
	}
}



?>