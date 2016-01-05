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
ini_set('session.gc_maxlifetime', $SESSIONTIME); 
session_start(); $_SESSION['keepalive']++;
//carichiamo le librerie base
require $_percorso . "librerie/lib_html.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica", $_percorso);

require "../../librerie/motore_primanota.php";
require "../../../setting/par_conta.inc.php";

//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);



if ($_SESSION['user']['setting'] > "3")
{

    echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\n";
    echo "<tr>\n";
    echo "<td colspan=\"8\" align=\"center\" >\n";
    echo "<span class=\"intestazione\"><b>Elaborazione Dati</b></td></tr>\n";

//prendiamo i post dalla pagina precedente..
//anno di elaborazione =
    $_anno = $_POST['anno'];

    $_start = cambio_data("us", $_POST['data_start']);
    $_end = cambio_data("us", $_POST['data_end']);
    $_data_reg = date('Y-m-d');

    $_annonuovo = $_anno + 1;
//date lavoro chiusura ed apertura anno..
    $_data_lavoro = $_anno . "-01-01";

    $_azione = $_POST['procedura'];



//prima di tutto selezioniamo ed eseguiamo tutto quello che riuarda i conti come costi..
// prendiamo il conto profitti e perdite

    $_profitti = tabella_piano_conti("descsingola", $PROFITTI_PERDITE, "descsingola");
    $_perdita = tabella_piano_conti("descsingola",$PERDITA_ESERCIZIO, "descsingola");
    $_utile = tabella_piano_conti("descsingola",$UTILE_ESERCIZIO, "descsingola");
    $_bilancio_chiusura = tabella_piano_conti("descsingola",$BILANCIO_CHIUSURA, "descsingola");
    $_bilancio_apertura = tabella_piano_conti("descsingola",$BILANCIO_APERTURA, "descsingola");


    if (($_azione == "chiudi") OR ($_azione == "apri"))
    {

	if ($_azione == "chiudi")
	{
	    $_annonuovo = $_anno;
	}
	else
	{
	    $_annonuovo = $_anno + 1;
	}

//SESIONE COSTI..

	$query = "SELECT data_cont, desc_conto, conto, SUM( dare ) - SUM( avere ) AS saldo FROM prima_nota INNER JOIN piano_conti ON prima_nota.conto=piano_conti.codconto WHERE data_cont >= '$_data_lavoro' AND data_cont <= '$_end' AND natcon='C' GROUP BY conto ORDER BY conto";

	$result = mysql_query($query, $conn) or mysql_error();

//richiediamo i dati
	while ($dati = mysql_fetch_array($result))
	{
//verifichiamo se il saldo è positivo o negativo..

	    $_saldo = $dati['saldo'];
//eliminiamo eventuali dati precedenti..
	    $_parametri = "";
	    $_testo = "";
	    $_parametri2 = "";

	    if ($_saldo != "0.00")
	    {
		//prepariamo i dati per la positività
		$_testo = "Chiusura $_anno $dati[desc_conto]";
		$_parametri['status'] = "Chiuso";
		$_parametri['conto'] = $dati[conto];
		$_parametri['desc_conto'] = $dati['desc_conto'];
		$_parametri['avere'] = $_saldo;

		//Prepariamo i dati per la seconda scrittura
		$_parametri2['status'] = "Chiuso";
		$_parametri2['conto'] = $PROFITTI_PERDITE;
		$_parametri2['desc_conto'] = $_bilancio_chiusura;
		$_parametri2['dare'] = $_saldo;

//qui inseriamo i dati in contabilità..
//prendiamoci il numero che tocca in contabilita

		$_nreg = tabella_primanota("ultimo", $id, $_annonuovo, $_nreg, $_causale, $_testo, $_data_reg, $_data_cont, $_parametri, $_percorso);

//ora prepariamo i dati da inserire..
//scrivi...
		$_return = tabella_primanota("Inserisci_singolo", $id, $_annonuovo, $_nreg, "ST", $_testo, $_data_reg, $_end, $_parametri, $_percorso);
		if ($_return['errori']['errore'] == "errore")
		{
		    echo $_return[errori][descrizione];
		}

//scrivi...
		$_return = tabella_primanota("Inserisci_singolo", $id, $_annonuovo, $_nreg, "ST", $_testo, $_data_reg, $_end, $_parametri2, $_percorso);
		if ($_return['errori']['errore'] == "errore")
		{
		    echo $_return[errori][descrizione];
		}

//finito tutto.. azzeriamo i parametri
		$_parametri = "";
		$_parametri2 = "";
		$_nreg;
	    }
	}

	echo "<td colspan=\"8\" align=\"center\" ><span class=\"intestazione\">Chiusura Costi effettuata</td></tr>\n";


//sezione RICAVI

	$query = "SELECT data_cont, desc_conto, conto, SUM( avere ) - SUM( dare ) AS saldo FROM prima_nota INNER JOIN piano_conti ON prima_nota.conto=piano_conti.codconto WHERE data_cont >= '$_data_lavoro' AND data_cont <= '$_end' AND natcon='R' GROUP BY conto ORDER BY conto";
	$result = mysql_query($query, $conn) or mysql_error();
//vedo quante righe sono per pagina..
//essendo tutti costi sono tutti in dare.. ora con un ciclo di while facciamo scritture in contabilità

	while ($dati = mysql_fetch_array($result))
	{
	    $_saldo = $dati['saldo'];
//eliminiamo eventuali dati precedenti..
	    $_parametri = "";
	    $_testo = "";
	    $_parametri2 = "";

	    if ($_saldo != "0.00")
	    {
		//prepariamo i dati per la positività
		$_testo = "Chiusura $_anno $dati[desc_conto]";
		$_parametri['status'] = "Chiuso";
		$_parametri['conto'] = $dati[conto];
		$_parametri['desc_conto'] = $dati['desc_conto'];
		$_parametri['dare'] = $_saldo;

		//Prepariamo i dati per la seconda scrittura
		$_parametri2['status'] = "Chiuso";
		$_parametri2['conto'] = $PROFITTI_PERDITE;
		$_parametri2['desc_conto'] = $_bilancio_chiusura;
		$_parametri2['avere'] = $_saldo;

//qui inseriamo i dati in contabilità..
//prendiamoci il numero che tocca in contabilita

		$_nreg = tabella_primanota("ultimo", $id, $_annonuovo, $_nreg, $_causale, $_testo, $_data_reg, $_data_cont, $_parametri, $_percorso);

//ora prepariamo i dati da inserire..
//scrivi...
		$_return = tabella_primanota("Inserisci_singolo", $id, $_annonuovo, $_nreg, "ST", $_testo, $_data_reg, $_end, $_parametri, $_percorso);
		if ($_return['errori']['errore'] == "errore")
		{
		    echo $_return[errori][descrizione];
		}

//scrivi...
		$_return = tabella_primanota("Inserisci_singolo", $id, $_annonuovo, $_nreg, "ST", $_testo, $_data_reg, $_end, $_parametri2, $_percorso);
		if ($_return['errori']['errore'] == "errore")
		{
		    echo $_return[errori][descrizione];
		}

//finito tutto.. azzeriamo i parametri
		$_parametri = "";
		$_parametri2 = "";
		$_nreg;
	    }
	}
	echo "<td colspan=\"8\" align=\"center\" ><span class=\"intestazione\">Chiusura Ricavi Effettuta</td></tr>\n";

// REGISTRIAMO I RICAVI/PERDITE

	$query = "SELECT data_cont, desc_conto, conto, SUM( dare ) - SUM( avere ) AS saldo FROM prima_nota INNER JOIN piano_conti ON prima_nota.conto=piano_conti.codconto WHERE data_cont >= '$_data_lavoro' AND data_cont <= '$_end' AND conto='$PROFITTI_PERDITE' GROUP BY conto ORDER BY conto";

	$result = mysql_query($query, $conn) or mysql_error();

//richiediamo i dati
	$dati = mysql_fetch_array($result);

//verifichiamo se il saldo è positivo o negativo..

	$_saldo = $dati['saldo'];

//eliminiamo eventuali dati precedenti..
	$_parametri = "";
	$_testo = "";
	$_parametri2 = "";

	if ($_saldo > "0.00")
	{

	    //prepariamo i dati per la positività
	    $_testo = "Rilevazione perdita di esercizio $_anno";
	    $_parametri['status'] = "Chiuso";
	    $_parametri['conto'] = $PERDITA_ESERCIZIO;
	    $_parametri['desc_conto'] = $_perdita;
	    $_parametri['dare'] = $dati['saldo'];

	    //Prepariamo i dati per la seconda scrittura
	    $_parametri2['status'] = "Chiuso";
	    $_parametri2['conto'] = $PROFITTI_PERDITE;
	    $_parametri2['desc_conto'] = $_profitti;
	    $_parametri2['avere'] = $dati['saldo'];
	}
	else
	{
	    //se il saldo è negativo ho bisogno del assoluto..
	    //
        $_saldo = abs($dati['saldo']);

	    //prepariamo i dati per la positività
	    $_testo = "Rilevazione utile di esercizio $_anno";
	    $_parametri['status'] = "Chiuso";
	    $_parametri['conto'] = $UTILE_ESERCIZIO;
	    $_parametri['desc_conto'] = $_utile;
	    $_parametri['avere'] = $_saldo;

	    //Prepariamo i dati per la seconda scrittura
	    $_parametri2['status'] = "Chiuso";
	    $_parametri2['conto'] = $PROFITTI_PERDITE;
	    $_parametri2['desc_conto'] = $_profitti;
	    $_parametri2['dare'] = $_saldo;
	}

	$_nreg = "";
	echo "<td colspan=\"8\" align=\"center\" ><span class=\"intestazione\">Registrazioni Utili-Perdite effettuata</td></tr>\n";
//qui inseriamo i dati in contabilità..
//prendiamoci il numero che tocca in contabilita

	$_nreg = tabella_primanota("ultimo", $id, $_annonuovo, $_nreg, $_causale, $_testo, $_data_reg, $_data_cont, $_parametri, $_percorso);

//ora prepariamo i dati da inserire..
//scrivi...
	$_return = tabella_primanota("Inserisci_singolo", $id, $_annonuovo, $_nreg, "ST", $_testo, $_data_reg, $_end, $_parametri, $_percorso);
	if ($_return['errori']['errore'] == "errore")
	{
	    echo $_return[errori][descrizione];
	}

//scrivi...
	$_return = tabella_primanota("Inserisci_singolo", $id, $_annonuovo, $_nreg, "ST", $_testo, $_data_reg, $_end, $_parametri2, $_percorso);
	if ($_return['errori']['errore'] == "errore")
	{
	    echo $_return[errori][descrizione];
	}

//finito tutto.. azzeriamo i parametri
	$_parametri = "";
	$_parametri2 = "";
	$_nreg = "";

//GESTIONE ATTIVITA

	$query = "SELECT data_cont, desc_conto, conto, SUM( dare ) - SUM( avere ) AS saldo FROM prima_nota INNER JOIN piano_conti ON prima_nota.conto=piano_conti.codconto WHERE data_cont >= '$_data_lavoro' AND data_cont <= '$_end' AND natcon='A' GROUP BY conto ORDER BY conto";

	$result = mysql_query($query, $conn) or mysql_error();

//richiediamo i dati
	while ($dati = mysql_fetch_array($result))
	{

//verifichiamo se il saldo è positivo o negativo..

	    $_saldo = $dati['saldo'];

//eliminiamo eventuali dati precedenti..
	    $_parametri = "";
	    $_testo = "";
	    $_parametri2 = "";

	    if ($_saldo != "0.00")
	    {
		if ($_saldo > "0.00")
		{

		    //prepariamo i dati per la positività
		    $_testo = "Chiusura $_anno $dati[desc_conto]";
		    $_parametri['status'] = "Chiuso";
		    $_parametri['conto'] = $dati[conto];
		    $_parametri['desc_conto'] = $dati['desc_conto'];
		    $_parametri['avere'] = $dati['saldo'];

		    //Prepariamo i dati per la seconda scrittura
		    $_parametri2['status'] = "Chiuso";
		    $_parametri2['conto'] = $BILANCIO_CHIUSURA;
		    $_parametri2['desc_conto'] = $_bilancio_chiusura;
		    $_parametri2['dare'] = $dati['saldo'];
		}
		else
		{
		    $_saldo = abs($dati['saldo']);
		    //prepariamo i dati per la positività
		    $_testo = "Chiusura $_anno $dati[desc_conto]";
		    $_parametri['status'] = "Chiuso";
		    $_parametri['conto'] = $dati[conto];
		    $_parametri['desc_conto'] = $dati['desc_conto'];
		    $_parametri['dare'] = $_saldo;

		    //Prepariamo i dati per la seconda scrittura
		    $_parametri2['status'] = "Chiuso";
		    $_parametri2['conto'] = $BILANCIO_CHIUSURA;
		    $_parametri2['desc_conto'] = $_bilancio_chiusura;
		    $_parametri2['avere'] = $_saldo;
		}



//qui inseriamo i dati in contabilità..
//prendiamoci il numero che tocca in contabilita

		$_nreg = tabella_primanota("ultimo", $id, $_annonuovo, $_nreg, $_causale, $_testo, $_data_reg, $_data_cont, $_parametri, $_percorso);

//ora prepariamo i dati da inserire..
//scrivi...
		$_return = tabella_primanota("Inserisci_singolo", $id, $_annonuovo, $_nreg, "ST", $_testo, $_data_reg, $_end, $_parametri, $_percorso);
		if ($_return['errori']['errore'] == "errore")
		{
		    echo $_return[errori][descrizione];
		}

//scrivi...
		$_return = tabella_primanota("Inserisci_singolo", $id, $_annonuovo, $_nreg, "ST", $_testo, $_data_reg, $_end, $_parametri2, $_percorso);
		if ($_return['errori']['errore'] == "errore")
		{
		    echo $_return[errori][descrizione];
		}

//finito tutto.. azzeriamo i parametri
		$_parametri = "";
		$_parametri2 = "";
		$_nreg;
	    }
	}
	echo "<td colspan=\"8\" align=\"center\" ><span class=\"intestazione\">Chiusura Attivit&agrave; effettuata</td></tr>\n";

//Sesione PASSIVITA

	$query = "SELECT data_cont, desc_conto, conto, SUM( dare ) - SUM( avere ) AS saldo FROM prima_nota INNER JOIN piano_conti ON prima_nota.conto=piano_conti.codconto WHERE data_cont >= '$_data_lavoro' AND data_cont <= '$_end' AND natcon='P' GROUP BY conto ORDER BY conto";

	$result = mysql_query($query, $conn) or mysql_error();

//richiediamo i dati
	while ($dati = mysql_fetch_array($result))
	{

//verifichiamo se il saldo è positivo o negativo..

	    $_saldo = $dati['saldo'];

//eliminiamo eventuali dati precedenti..
	    $_parametri = "";
	    $_testo = "";
	    $_parametri2 = "";

	    if ($_saldo != "0.00")
	    {
		if ($_saldo > "0.00")
		{
		    $_saldo = abs($dati['saldo']);
		    //prepariamo i dati per la positività
		    $_testo = "Chiusura $_anno $dati[desc_conto]";
		    $_parametri['status'] = "Chiuso";
		    $_parametri['conto'] = $dati[conto];
		    $_parametri['desc_conto'] = $dati['desc_conto'];
		    $_parametri['avere'] = $_saldo;

		    //Prepariamo i dati per la seconda scrittura
		    $_parametri2['status'] = "Chiuso";
		    $_parametri2['conto'] = $BILANCIO_CHIUSURA;
		    $_parametri2['desc_conto'] = $_bilancio_chiusura;
		    $_parametri2['dare'] = $_saldo;
		}
		else
		{
		    $_saldo = abs($dati['saldo']);
		    //prepariamo i dati per la positività
		    $_testo = "Chiusura $_anno $dati[desc_conto]";
		    $_parametri['status'] = "Chiuso";
		    $_parametri['conto'] = $dati[conto];
		    $_parametri['desc_conto'] = $dati['desc_conto'];
		    $_parametri['dare'] = $_saldo;

		    //Prepariamo i dati per la seconda scrittura
		    $_parametri2['status'] = "Chiuso";
		    $_parametri2['conto'] = $BILANCIO_CHIUSURA;
		    $_parametri2['desc_conto'] = $_bilancio_chiusura;
		    $_parametri2['avere'] = $_saldo;
		}



//qui inseriamo i dati in contabilità..
//prendiamoci il numero che tocca in contabilita

		$_nreg = tabella_primanota("ultimo", $id, $_annonuovo, $_nreg, $_causale, $_testo, $_data_reg, $_data_cont, $_parametri, $_percorso);

//ora prepariamo i dati da inserire..
//scrivi...
		$_return = tabella_primanota("Inserisci_singolo", $id, $_annonuovo, $_nreg, "ST", $_testo, $_data_reg, $_end, $_parametri, $_percorso);
		if ($_return['errori']['errore'] == "errore")
		{
		    echo $_return[errori][descrizione];
		}

//scrivi...
		$_return = tabella_primanota("Inserisci_singolo", $id, $_annonuovo, $_nreg, "ST", $_testo, $_data_reg, $_end, $_parametri2, $_percorso);
		if ($_return['errori']['errore'] == "errore")
		{
		    echo $_return[errori][descrizione];
		}

//finito tutto.. azzeriamo i parametri
		$_parametri = "";
		$_parametri2 = "";
		$_nreg = "";
	    }
	}

	echo "<td colspan=\"8\" align=\"center\" ><span class=\"intestazione\">Chiusura Passivit&agrave; effettuata</td></tr>\n";

//CONTI D'ORDINE....

	$query = "SELECT data_cont, desc_conto, conto, SUM( dare ) - SUM( avere ) AS saldo FROM prima_nota INNER JOIN piano_conti ON prima_nota.conto=piano_conti.codconto WHERE data_cont >= '$_data_lavoro' AND data_cont <= '$_end' AND natcon='0' GROUP BY conto ORDER BY conto";

	$result = mysql_query($query, $conn) or mysql_error();

//richiediamo i dati
	while ($dati = mysql_fetch_array($result))
	{

//verifichiamo se il saldo è positivo o negativo..

	    $_saldo = $dati['saldo'];

//eliminiamo eventuali dati precedenti..
	    $_parametri = "";
	    $_testo = "";
	    $_parametri2 = "";

	    if ($_saldo != "0.00")
	    {
		if ($_saldo > "0.00")
		{

		    //prepariamo i dati per la positività
		    $_testo = "Chiusura $_anno $dati[desc_conto]";
		    $_parametri['status'] = "Chiuso";
		    $_parametri['conto'] = $dati[conto];
		    $_parametri['desc_conto'] = $dati['desc_conto'];
		    $_parametri['avere'] = $dati['saldo'];

		    //Prepariamo i dati per la seconda scrittura
		    $_parametri2['status'] = "Chiuso";
		    $_parametri2['conto'] = $BILANCIO_CHIUSURA;
		    $_parametri2['desc_conto'] = $_bilancio_chiusura;
		    $_parametri2['dare'] = $dati['saldo'];
		}
		else
		{
		    $_saldo = abs($dati['saldo']);
		    //prepariamo i dati per la positività
		    $_testo = "Chiusura $_anno $dati[desc_conto]";
		    $_parametri['status'] = "Chiuso";
		    $_parametri['conto'] = $dati[conto];
		    $_parametri['desc_conto'] = $dati['desc_conto'];
		    $_parametri['dare'] = $_saldo;

		    //Prepariamo i dati per la seconda scrittura
		    $_parametri2['status'] = "Chiuso";
		    $_parametri2['conto'] = $BILANCIO_CHIUSURA;
		    $_parametri2['desc_conto'] = $_bilancio_chiusura;
		    $_parametri2['avere'] = $_saldo;
		}



//qui inseriamo i dati in contabilità..
//prendiamoci il numero che tocca in contabilita

		$_nreg = tabella_primanota("ultimo", $id, $_annonuovo, $_nreg, $_causale, $_testo, $_data_reg, $_data_cont, $_parametri, $_percorso);

//ora prepariamo i dati da inserire..
//scrivi...
		$_return = tabella_primanota("Inserisci_singolo", $id, $_annonuovo, $_nreg, "ST", $_testo, $_data_reg, $_end, $_parametri, $_percorso);
		if ($_return['errori']['errore'] == "errore")
		{
		    echo $_return[errori][descrizione];
		}

//scrivi...
		$_return = tabella_primanota("Inserisci_singolo", $id, $_annonuovo, $_nreg, "ST", $_testo, $_data_reg, $_end, $_parametri2, $_percorso);
		if ($_return['errori']['errore'] == "errore")
		{
		    echo $_return[errori][descrizione];
		}

//finito tutto.. azzeriamo i parametri
		$_parametri = "";
		$_parametri2 = "";
	    }
	}

	echo "<td colspan=\"8\" align=\"center\" ><span class=\"intestazione\">Chiusura Conti ordine effettuata</td></tr>\n";



//SEZIONE clienti

	$query = "SELECT data_cont, desc_conto, conto, SUM( dare ) - SUM( avere ) AS saldo FROM prima_nota WHERE data_cont >= '$_data_lavoro' AND data_cont <= '$_end' AND conto like '$MASTRO_CLI%' GROUP BY conto ORDER BY conto";

	$result = mysql_query($query, $conn) or mysql_error();

//richiediamo i dati
	while ($dati = mysql_fetch_array($result))
	{

//verifichiamo se il saldo è positivo o negativo..

	    $_saldo = $dati['saldo'];

//eliminiamo eventuali dati precedenti..
	    $_parametri = "";
	    $_testo = "";
	    $_parametri2 = "";

	    if ($_saldo != "0.00")
	    {
		if ($_saldo > "0.00")
		{

		    //prepariamo i dati per la positività
		    $_testo = "Chiusura $_anno $dati[desc_conto]";
		    $_parametri['status'] = "Chiuso";
		    $_parametri['conto'] = $dati[conto];
		    $_parametri['desc_conto'] = $dati['desc_conto'];
		    $_parametri['avere'] = $dati['saldo'];

		    //Prepariamo i dati per la seconda scrittura
		    $_parametri2['status'] = "Chiuso";
		    $_parametri2['conto'] = $BILANCIO_CHIUSURA;
		    $_parametri2['desc_conto'] = $_bilancio_chiusura;
		    $_parametri2['dare'] = $dati['saldo'];
		}
		else
		{
		    $_saldo = abs($dati['saldo']);
		    //prepariamo i dati per la positività
		    $_testo = "Chiusura $_anno $dati[desc_conto]";
		    $_parametri['status'] = "Chiuso";
		    $_parametri['conto'] = $dati[conto];
		    $_parametri['desc_conto'] = $dati['desc_conto'];
		    $_parametri['dare'] = $_saldo;

		    //Prepariamo i dati per la seconda scrittura
		    $_parametri2['status'] = "Chiuso";
		    $_parametri2['conto'] = $BILANCIO_CHIUSURA;
		    $_parametri2['desc_conto'] = $_bilancio_chiusura;
		    $_parametri2['avere'] = $_saldo;
		}



//qui inseriamo i dati in contabilità..
//prendiamoci il numero che tocca in contabilita

		$_nreg = tabella_primanota("ultimo", $id, $_annonuovo, $_nreg, $_causale, $_testo, $_data_reg, $_data_cont, $_parametri, $_percorso);

//ora prepariamo i dati da inserire..
//scrivi...
		$_return = tabella_primanota("Inserisci_singolo", $id, $_annonuovo, $_nreg, "ST", $_testo, $_data_reg, $_end, $_parametri, $_percorso);
		if ($_return['errori']['errore'] == "errore")
		{
		    echo $_return[errori][descrizione];
		}

//scrivi...
		$_return = tabella_primanota("Inserisci_singolo", $id, $_annonuovo, $_nreg, "ST", $_testo, $_data_reg, $_end, $_parametri2, $_percorso);
		if ($_return['errori']['errore'] == "errore")
		{
		    echo $_return[errori][descrizione];
		}

//finito tutto.. azzeriamo i parametri
		$_parametri = "";
		$_parametri2 = "";
	    }
	}
	echo "<td colspan=\"8\" align=\"center\" ><span class=\"intestazione\">Chiusura Clienti effettuata</td></tr>\n";

//SEZIONE FORNITORI

	$query = "SELECT data_cont, desc_conto, conto, SUM( dare ) - SUM( avere ) AS saldo FROM prima_nota WHERE data_cont >= '$_data_lavoro' AND data_cont <= '$_end' AND conto like '$MASTRO_FOR%' GROUP BY conto ORDER BY conto";

	$result = mysql_query($query, $conn) or mysql_error();

//richiediamo i dati
	while ($dati = mysql_fetch_array($result))
	{

//verifichiamo se il saldo è positivo o negativo..

	    $_saldo = $dati['saldo'];

//eliminiamo eventuali dati precedenti..
	    $_parametri = "";
	    $_testo = "";
	    $_parametri2 = "";

	    if ($_saldo != "0.00")
	    {
		if ($_saldo > "0.00")
		{

		    //prepariamo i dati per la positività
		    $_testo = "Chiusura $_anno $dati[desc_conto]";
		    $_parametri['status'] = "Chiuso";
		    $_parametri['conto'] = $dati[conto];
		    $_parametri['desc_conto'] = $dati['desc_conto'];
		    $_parametri['avere'] = $dati['saldo'];

		    //Prepariamo i dati per la seconda scrittura
		    $_parametri2['status'] = "Chiuso";
		    $_parametri2['conto'] = $BILANCIO_CHIUSURA;
		    $_parametri2['desc_conto'] = $_bilancio_chiusura;
		    $_parametri2['dare'] = $dati['saldo'];
		}
		else
		{
		    $_saldo = abs($dati['saldo']);
		    //prepariamo i dati per la positività
		    $_testo = "Chiusura $_anno $dati[desc_conto]";
		    $_parametri['status'] = "Chiuso";
		    $_parametri['conto'] = $dati[conto];
		    $_parametri['desc_conto'] = $dati['desc_conto'];
		    $_parametri['dare'] = $_saldo;

		    //Prepariamo i dati per la seconda scrittura
		    $_parametri2['status'] = "Chiuso";
		    $_parametri2['conto'] = $BILANCIO_CHIUSURA;
		    $_parametri2['desc_conto'] = $_bilancio_chiusura;
		    $_parametri2['avere'] = $_saldo;
		}



//qui inseriamo i dati in contabilità..
//prendiamoci il numero che tocca in contabilita

		$_nreg = tabella_primanota("ultimo", $id, $_annonuovo, $_nreg, $_causale, $_testo, $_data_reg, $_data_cont, $_parametri, $_percorso);

//ora prepariamo i dati da inserire..
//scrivi...
		$_return = tabella_primanota("Inserisci_singolo", $id, $_annonuovo, $_nreg, "ST", $_testo, $_data_reg, $_end, $_parametri, $_percorso);
		if ($_return['errori']['errore'] == "errore")
		{
		    echo $_return[errori][descrizione];
		}

//scrivi...
		$_return = tabella_primanota("Inserisci_singolo", $id, $_annonuovo, $_nreg, "ST", $_testo, $_data_reg, $_end, $_parametri2, $_percorso);
		if ($_return['errori']['errore'] == "errore")
		{
		    echo $_return[errori][descrizione];
		}

//finito tutto.. azzeriamo i parametri
		$_parametri = "";
		$_parametri2 = "";
	    }
	}
// a questo punto abbiamo chiuso tutto..
	echo "<td colspan=\"8\" align=\"center\" ><span class=\"intestazione\">Chiusura Fornitori effettuata</td></tr>\n";



	echo "<td colspan=\"8\" align=\"center\" ><span class=\"intestazione\">Chiusura effettuata</td></tr>\n";

	echo "<td colspan=\"8\" align=\"center\" ><span class=\"intestazione\">Premi qui per stampare il bilancio di verifica..</td></tr>\n";
	echo "<td colspan=\"8\" align=\"center\" ><span class=\"intestazione\"><b>Stampa Bilancio di Verifica<a href=\"../../contabilita/bilancio/bilancio_verifica.php?data_start=01-01-$_anno&data_end=$_POST[data_end]\" target=\"_blanck\">Stampa Qui!</a></b></td></tr>";

	if ($_azione == "apri")
	{
	    echo "<form action=\"pong_contabilita.php\" method=\"POST\">\n";
	    echo "<td colspan=\"8\" align=\"center\" ><span class=\"intestazione\">Ora se il bilancio appare corretto si pu&ograve; procedere con l'apertura.</td></tr>\n";
	    echo "<td colspan=\"8\" align=\"center\" ><span class=\"intestazione\"><input type=\"radio\" name=\"anno\" value=\"$_anno\" checked>Anno Apertura $_annonuovo </td></tr>\n";
	    echo "<td colspan=\"8\" align=\"center\" ><span class=\"intestazione\"><input type=\"radio\" name=\"data_end\" value=\"$_POST[data_end]\" checked>data Chiusura = $_POST[data_end] </td></tr>\n";
	    echo "<td colspan=\"8\" align=\"center\" ><span class=\"intestazione\"><input type=\"radio\" name=\"data_start\" value=\"$_POST[data_start]\" checked>data Apertura = $_POST[data_start] </td></tr>\n";

	    echo "<tr><td colspan=\"8\" align=\"center\"><br><input type=\"submit\" name=\"procedura\" value=\"Apertura\"></form></td></tr>\n";
	}
    }
    elseif ($_azione == "Apertura")
    {
	echo "<td colspan=\"8\" align=\"center\" ><span class=\"intestazione\"><b>Apertura..</b></td></tr>\n";


	//inizio parte nuova..

	$query = "SELECT anno, nreg, data_cont, desc_conto, conto FROM prima_nota WHERE data_cont >= '$_data_lavoro' AND data_cont <= '$_end' AND conto = '$BILANCIO_CHIUSURA' ORDER BY nreg";
	//echo "<br>$query";
	$result = mysql_query($query, $conn) or mysql_error();

	while ($dati = mysql_fetch_array($result))
	{
	    //ora per ognuna di esse richiamo la registrazione completa...
	    $result_reg = tabella_primanota("leggi", $id, $_annonuovo, $dati['nreg'], $_causale, $_testo, $_data_reg, $_data_cont, $_parametri, $_percorso);

	    while ($dati_reg = mysql_fetch_array($result_reg))
	    {
		//ora per ogni riga della registrazione precedente dobbiamo inserire una registrazione nuova di apertura..
		//leggiamo solo la riga numero uno...
		if ($dati_reg['rigo'] == "1")
		{
		    //ora iniziamo ad inserire tutti i dati in una nuova registrazione ma invertendo i valori dare avere..

		    if ($dati_reg['dare'] != "0.00")
		    {
			//prepariamo i dati per la positività
			$_testo = "Apertura $_annonuovo $dati_reg[desc_conto]";
			$_parametri['status'] = "Inserito";
			$_parametri['conto'] = $dati_reg[conto];
			$_parametri['desc_conto'] = $dati_reg['desc_conto'];
			$_parametri['avere'] = $dati_reg['dare'];

			//Prepariamo i dati per la seconda scrittura
			$_parametri2['status'] = "Inserito";
			$_parametri2['conto'] = $BILANCIO_APERTURA;
			$_parametri2['desc_conto'] = $_bilancio_apertura;
			$_parametri2['dare'] = $dati_reg['dare'];
		    }
		    else
		    {
			$_testo = "Apertura $_annonuovo $dati_reg[desc_conto]";
			$_parametri['status'] = "Inserito";
			$_parametri['conto'] = $dati_reg[conto];
			$_parametri['desc_conto'] = $dati_reg['desc_conto'];
			$_parametri['dare'] = $dati_reg['avere'];

			//Prepariamo i dati per la seconda scrittura
			$_parametri2['status'] = "Inserito";
			$_parametri2['conto'] = $BILANCIO_APERTURA;
			$_parametri2['desc_conto'] = $_bilancio_apertura;
			$_parametri2['avere'] = $dati_reg['avere'];
		    }



//qui inseriamo i dati in contabilità..
//prendiamoci il numero che tocca in contabilita

		    $_nreg = tabella_primanota("ultimo", $id, $_annonuovo, $_nreg, $_causale, $_testo, $_data_reg, $_data_cont, $_parametri, $_percorso);

//ora prepariamo i dati da inserire..
//scrivi...
		    $_return = tabella_primanota("Inserisci_singolo", $id, $_annonuovo, $_nreg, "ST", $_testo, $_data_reg, $_start, $_parametri, $_percorso);
		    if ($_return['errori']['errore'] == "errore")
		    {
			echo $_return[errori][descrizione];
		    }

//scrivi...
		    $_return = tabella_primanota("Inserisci_singolo", $id, $_annonuovo, $_nreg, "ST", $_testo, $_data_reg, $_start, $_parametri2, $_percorso);
		    if ($_return['errori']['errore'] == "errore")
		    {
			echo $_return[errori][descrizione];
		    }

//finito tutto.. azzeriamo i parametri
		    $_parametri = "";
		    $_parametri2 = "";
		}
	    }
	}//chiusura while..

	echo "<td colspan=\"8\" align=\"center\" ><span class=\"intestazione\">Apertura terminata..</td></tr>\n";
    }
    else
    {
	echo "<td colspan=\"8\" align=\"center\" ><span class=\"intestazione\"><b>Operazione annullata come richiesto.</b></td></tr>\n";
    }

    echo "</body></html>";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>