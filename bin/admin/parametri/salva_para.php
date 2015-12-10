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
$conn = permessi_sessione("verifica", $_percorso);

//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);



if ($_SESSION['user']['setting'] > "3")
{

// ora devo prendermi i post..
// poi devo creare il files contenete la esportazione
// e lo creo in questa directory plugins
//inizio parte produttiva
// il nome del files
// nome con full percorso
    $nfile = "../../../setting/par_conta.inc.php";

    #@chmod("$nfile", 0755);

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


    $_commento = "// file di configurazione del programma contabilita\n";
    fwrite($fp, $_commento);
    if (!$fp)
	die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$MASTRO_CLI = "%s";' . "\n", $_POST['MASTRO_CLI']);
    fwrite($fp, $_scrivi);
    if (!$fp)
	die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$CONTO_CLIENTI = "%s";' . "\n", $_POST['CONTO_CLIENTI']);
    fwrite($fp, $_scrivi);
    if (!$fp)
	die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$MASTRO_FOR = "%s";' . "\n", $_POST['MASTRO_FOR']);
    fwrite($fp, $_scrivi);
    if (!$fp)
	die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$CONTO_FORNITORI = "%s";' . "\n", $_POST['CONTO_FORNITORI']);
    fwrite($fp, $_scrivi);
    if (!$fp)
	die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$MASTRO_BANCHE = "%s";' . "\n", $_POST['MASTRO_BANCHE']);
    fwrite($fp, $_scrivi);
    if (!$fp)
	die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$CONTO_SPESE_BANCARIE = "%s";' . "\n", $_POST['CONTO_SPESE_BANCARIE']);
    fwrite($fp, $_scrivi);
    if (!$fp)
	die("Errore.. Riga non inserita ?");		
		
    $_scrivi = sprintf('$CONTO_CASSA = "%s";' . "\n", $_POST['CONTO_CASSA']);
    fwrite($fp, $_scrivi);
    if (!$fp)
	die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$CONTO_ASSEGNI = "%s";' . "\n", $_POST['CONTO_ASSEGNI']);
    fwrite($fp, $_scrivi);
    if (!$fp)
	die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$CONTO_COMPENSAZIONI = "%s";' . "\n", $_POST['CONTO_COMPENSAZIONI']);
    fwrite($fp, $_scrivi);
    if (!$fp)
	die("Errore.. Riga non inserita ?");
		
		
    $_scrivi = sprintf('$REC_TRASPORTO = "%s";' . "\n", $_POST['REC_TRASPORTO']);
    fwrite($fp, $_scrivi);
    if (!$fp)
	die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$REC_IMBALLI = "%s";' . "\n", $_POST['REC_IMBALLI']);
    fwrite($fp, $_scrivi);
    if (!$fp)
	die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$REC_SPESE_VARIE = "%s";' . "\n", $_POST['REC_SPESE_VARIE']);
    fwrite($fp, $_scrivi);
    if (!$fp)
	die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$REC_SPESE_BANCARIE = "%s";' . "\n", $_POST['REC_SPESE_BANCARIE']);
    fwrite($fp, $_scrivi);
    if (!$fp)
	die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$CONTO_SCONTI_FINALI = "%s";' . "\n", $_POST['CONTO_SCONTI_FINALI']);
    fwrite($fp, $_scrivi);
    if (!$fp)
	die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$MASTRO_EFFETTI = "%s";' . "\n", $_POST['MASTRO_EFFETTI']);
    fwrite($fp, $_scrivi);
    if (!$fp)
	die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$CONTO_EFFETTI_ATTIVI = "%s";' . "\n", $_POST['CONTO_EFFETTI_ATTIVI']);
    fwrite($fp, $_scrivi);
    if (!$fp)
	die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$CONTO_EFFETTI_SBF = "%s";' . "\n", $_POST['CONTO_EFFETTI_SBF']);
    fwrite($fp, $_scrivi);
    if (!$fp)
	die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$CONTO_EFFETTI_INCASSO = "%s";' . "\n", $_POST['CONTO_EFFETTI_INCASSO']);
    fwrite($fp, $_scrivi);
    if (!$fp)
	die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$CONTO_EFFETTI_INSOLUTI = "%s";' . "\n", $_POST['CONTO_EFFETTI_INSOLUTI']);
    fwrite($fp, $_scrivi);
    if (!$fp)
	die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$CONTO_SPESE_INSOLUTI = "%s";' . "\n", $_POST['CONTO_SPESE_INSOLUTI']);
    fwrite($fp, $_scrivi);
    if (!$fp)
	die("Errore.. Riga non inserita ?");


    $_scrivi = sprintf('$CONTO_IVA_ACQUISTI = "%s";' . "\n", $_POST['CONTO_IVA_ACQUISTI']);
    fwrite($fp, $_scrivi);
    if (!$fp)
	die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$CONTO_IVA_VENDITE = "%s";' . "\n", $_POST['CONTO_IVA_VENDITE']);
    fwrite($fp, $_scrivi);
    if (!$fp)
	die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$CONTO_IVA_ERARIO = "%s";' . "\n", $_POST['CONTO_IVA_ERARIO']);
    fwrite($fp, $_scrivi);
    if (!$fp)
	die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$PROFITTI_PERDITE = "%s";' . "\n", $_POST['PROFITTI_PERDITE']);
    fwrite($fp, $_scrivi);
    if (!$fp)
	die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$UTILE_ESERCIZIO = "%s";' . "\n", $_POST['UTILE_ESERCIZIO']);
    fwrite($fp, $_scrivi);
    if (!$fp)
	die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$PERDITA_ESERCIZIO = "%s";' . "\n", $_POST['PERDITA_ESERCIZIO']);
    fwrite($fp, $_scrivi);
    if (!$fp)
	die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$BILANCIO_CHIUSURA = "%s";' . "\n", $_POST['BILANCIO_CHIUSURA']);
    fwrite($fp, $_scrivi);
    if (!$fp)
	die("Errore.. Riga non inserita ?");

    $_scrivi = sprintf('$BILANCIO_APERTURA = "%s";' . "\n", $_POST['BILANCIO_APERTURA']);
    fwrite($fp, $_scrivi);
    if (!$fp)
	die("Errore.. Riga non inserita ?");

		$_scrivi = sprintf('$CODATTIVITA = "%s";' . "\n", $_POST['CODATTIVITA']);
    fwrite($fp, $_scrivi);
    if (!$fp)
		{
			echo "Errore.. Riga non inserita ?";
		}

    $_scrivi = sprintf('$SPESOMETRO = "%s";' . "\n", $_POST['SPESOMETRO']);
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