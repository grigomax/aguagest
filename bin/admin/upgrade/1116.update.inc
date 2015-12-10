<?php

/*
  File di aggiornamento programma fisico..
 * eliminiamo un po di files che non servono più
 * come le stampe inventario ecc..
 * Ma prima dobbiamo verificare che ci siano i permessi di scritura
 */

//versione file 1106
//eliminazione dei file non più necessari..

if (file_exists("../../../setting/eti_cli_norm.php"))
{
	if (unlink("../../../setting/eti_cli_norm.php"))
	{
		echo "<br>Eliminazione etichetta clienti\n";
	}
}

if (file_exists("../../../setting/eti_for_norm.php"))
{
	if (unlink("../../../setting/eti_for_norm.php"))
	{
		echo "<br>Eliminazione etichetta fornitori\n";
	}
}

?>