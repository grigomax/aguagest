<?php

/*
  File di aggiornamento programma fisico..
 * eliminiamo un po di files che non servono più
 * come le stampe inventario ecc..
 * Ma prima dobbiamo verificare che ci siano i permessi di scritura
 */


//dobbiamo creare una directory per poter usare il lettore dei codice a barre
//la directory si chiamerà upload e sara assieme a bin sulla directory principale
//del programma

if(unlink ("../../../setting/rp_rimanenze_merc.php"))
{
    echo "<br>Eliminazione riuscita di rimamenze\n";
}

if(unlink ("../../../setting/rp_rimanenze_tip.php"))
{
    echo "<br>Eliminazione riuscita di rimanenze2.php\n";
}


if(unlink ("../../../setting/rep_inventario.php"))
{
    echo "<br>Eliminazione riuscita di rep_inventario.php\n";
}

?>