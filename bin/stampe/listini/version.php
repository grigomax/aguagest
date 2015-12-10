<?php
// file contenete le modifiche di questo plugins

$VERSION = "1.2 del 31/01/2013";

 echo "<h3> Istruzioni bash per stampare il catalogo..</h3>\n";
    echo "Installare pdftk, ed anche installare CUPS PDF<br>\n";
    echo "Primo passo unire tutti i pdf con pdf-tk  comm .pdftk *.pdf cat output combined.pdf\n";
    echo "<br> secondo..";
    echo "<br> lp -d cups-pdf -o number-up=2 -o media=a3 -o fitplot -o Resolution=600dpi nomefile.pdf\n";

// qui sotto mettiamo le modifiche

/**
 
 * Versione 1.2 Sistemato ed aggiornato il programma ora è unificato con il programma pricipale
 * e ho anche dato la possibilità di gestire la stampa per la tipografia oppure la stampa unica su collegamento
 * PDF.
 
 *  aggiornato il problema delle immagini e cambiato la directory di stampa
 Sistemata la impaginazione con la possibilità di definire quanto largo e grande lo si vuole
 * sistemato il bug dell'aggiorna articolo

Versione 1.0 Dopo il listino figurato classico procedo a quello per la stampa in
 * Tipogragia..

*/

?>