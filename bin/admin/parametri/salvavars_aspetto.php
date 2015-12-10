<?php
/* Programma Agua gest
 * Programma nato e gestito da grigolin massimo
 * prodotto sotto licenza GPL
 * per tutte le informazioni massimo@mcetechnik.it
 * aguagest.sourceforge.net
 */

//carichiamo la base del programma includendo i file minimi
$_percorso = "../../";
require $_percorso ."../setting/vars.php";
session_start(); $_SESSION['keepalive']++;
//carichiamo le librerie base
require $_percorso . "librerie/lib_html.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);

//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);



if ($_SESSION['user']['setting'] > "3")
{


    $nfile = "../../../setting/vars_aspetto.php";

// creo il files e nascondo la soluzione
    $fp = fopen($nfile, "w");
//controllo l'esito
    if (!$fp)
	die("Errore.. non sono riuscito a creare il file.. Permessi ?");

#scriviamo una riga di commento per chiarire le posizioni
// scriviamo le righe e le verifico
//scriviamo che un file php
    $_commento = "<?php\n";
    fwrite($fp, $_commento);
    if (!$fp)
	die("Errore.. Riga non inserita ?");


    $_commento = "// file di configurazione dell' aspetto del programma\n";
    fwrite($fp, $_commento);
    if (!$fp)
	die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$SCREEN_WIDTH = "%s";' . "\n", $_POST['SCREEN_WIDTH']);
    fwrite($fp, $_scrivi);
    if (!$fp)
	die("Errore.. Riga non inserita ?");
    
    $_scrivi = sprintf('$SCREEN_FONT_SIZE = "%s";' . "\n", ($_POST['SCREEN_FONT_SIZE'] / 10));
    fwrite($fp, $_scrivi);
    if (!$fp)
	die("Errore.. Riga non inserita ?");
    
    $_scrivi = sprintf('$SCREEN_FONT_TYPE = "%s";' . "\n", $_POST['SCREEN_FONT_TYPE']);
    fwrite($fp, $_scrivi);
    if (!$fp)
	die("Errore.. Riga non inserita ?");
    
    $_scrivi = sprintf('$SCREEN_COLOR_BACKGROUND = "%s";' . "\n", $_POST['SCREEN_COLOR_BACKGROUND']);
    fwrite($fp, $_scrivi);
    if (!$fp)
	die("Errore.. Riga non inserita ?");
    
    
    
    $_scrivi = sprintf('$PRINT_WIDTH = "%s";' . "\n", $_POST['PRINT_WIDTH']);
    fwrite($fp, $_scrivi);
    if (!$fp)
	die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$PRINT_FONT_SIZE = "%s";' . "\n", $_POST['PRINT_FONT_SIZE']);
    fwrite($fp, $_scrivi);
    if (!$fp)
	die("Errore.. Riga non inserita ?");
    
    $_scrivi = sprintf('$PRINT_FONT_TYPE = "%s";' . "\n", $_POST['PRINT_FONT_TYPE']);
    fwrite($fp, $_scrivi);
    if (!$fp)
	die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$fontditta = "%s";' . "\n", $_POST['fontditta']);
    fwrite($fp, $_scrivi);
    if (!$fp)
	die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$fontdimditta = "%s";' . "\n", $_POST['fontdimditta']);
    fwrite($fp, $_scrivi);
    if (!$fp)
	die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$fontintestazione = "%s";' . "\n", $_POST['fontintestazione']);
    fwrite($fp, $_scrivi);
    if (!$fp)
	die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$fontdimintestazione = "%s";' . "\n", $_POST['fontdimintestazione']);
    fwrite($fp, $_scrivi);
    if (!$fp)
	die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$fontdocsta = "%s";' . "\n", $_POST['fontdocsta']);
    fwrite($fp, $_scrivi);
    if (!$fp)
	die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$fontdocsize = "%s";' . "\n", $_POST['fontdocsize']);
    fwrite($fp, $_scrivi);
    if (!$fp)
	die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$fontcorpodocsta = "%s";' . "\n", $_POST['fontcorpodocsta']);
    fwrite($fp, $_scrivi);
    if (!$fp)
	die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$fontcorpodocsize = "%s";' . "\n", $_POST['fontcorpodocsize']);
    fwrite($fp, $_scrivi);
    if (!$fp)
	die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$SCHEDA_LOGO = "%s";' . "\n", $_POST['SCHEDA_LOGO']);
    fwrite($fp, $_scrivi);
    if (!$fp)
	die("Errore.. Riga non inserita ?");
    
    $_scrivi = sprintf('$EFFETTI_LOGO = "%s";' . "\n", $_POST['EFFETTI_LOGO']);
    fwrite($fp, $_scrivi);
    if (!$fp)
	die("Errore.. Riga non inserita ?");
    
    $_scrivi = sprintf('$PRIVACY_LOGO = "%s";' . "\n", $_POST['PRIVACY_LOGO']);
    fwrite($fp, $_scrivi);
    if (!$fp)
	die("Errore.. Riga non inserita ?");
    

// chiudiamo il file php
    $_commento = "?>";
    fwrite($fp, $_commento);
    if (!$fp)
	die("Errore.. Riga non inserita ?");


// chiudiamo il files
    fclose($fp);

    echo "<center>";
    echo "<h2>Se non appaiono errori a video<br> il file &egrave; stato <br>modificato con successo</h2>";
    
    echo "</body></html>";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>