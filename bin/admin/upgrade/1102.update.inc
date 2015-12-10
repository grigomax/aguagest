<?php

/*
  Files di aggiornamento programma, al nuovo motore stampe documenti.
 * PEliminazione vecchi files..
 */

//dobbiamo creare una directory per poter usare il lettore dei codice a barre
//la directory si chiamerà upload e sara assieme a bin sulla directory principale
//del programma

if(unlink ("../../../setting/clifor.php"))
{
    echo "<br>Eliminazione riuscita di clifor.php\n";
}

if(unlink ("../../../setting/inventariopdf.php"))
{
    echo "<br>Eliminazione riuscita di inventariopdf.php\n";
}


if(unlink ("../../../setting/rep_inventario.php"))
{
    echo "<br>Eliminazione riuscita di rep_inventario.php\n";
}

?>