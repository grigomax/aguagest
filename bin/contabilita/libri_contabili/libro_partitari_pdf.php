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
require "../../librerie/stampe_pdf.php";
//includiamo le librerie pdf..
//qui parte l'avventura del sig. buonaventura...


//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);



if ($_SESSION['user']['contabilita'] > "1")
{


//recupero tutti POST.

    $_anno = $_POST['anno'];

//setiamo il timeout..

    set_time_limit("600");


//creaimo il file
    crea_file_pdf($_cosa, $_orientamento, "Partitario $_anno");


//Fissiamo il numero di righe per pagina...
    $rpp = "53";


//inanzitutto dobbiamo leggere il database del piano dei conti..

    $res_piano = piano_conti($_codconto, "mastrini");

//ora iniziamo il while..

    while ($piano = mysql_fetch_array($res_piano))
    {


	//poi per ogni conto creaimo le loro pagine..
	$query = "SELECT *, date_format(data_reg, '%d-%m-%Y') data_reg, date_format(data_cont, '%d-%m-%Y') data_cont, data_cont AS data from prima_nota where anno='$_anno' AND data_cont >='$_anno-01-01' AND data_cont <= '$_anno-12-31' AND conto = '$piano[codconto]' ORDER BY data ASC, nreg ";
	//cerco il numero di righe
	if ($res2 = mysql_query($query, $conn) or mysql_error())
	{
	    //cerco il numero di righe
	    $righe = mysql_num_rows($res2);

	    //selezioniamo slo chi ha muovimenti..
	    if ($righe > "0")
	    {

		//inserisco il numero di righe per pagina
		$_pagine = $righe / $rpp;
		//arrotondo per eccesso
		$pagina = ceil($_pagine);


		for ($_pg = 1; $_pg <= $pagina; $_pg++)
		{
		    crea_pagina_pdf();

		    //intestazione
		    crea_intestazione_ditta_pdf("schede_contabili", "Partitari anno $_anno", $_anno, $_pg, $pagina, $_parametri);

		    intesta_tabella("partitari", $piano['codconto'], $piano['descrizione'], $_anno);

		    $_return = corpo_tabella("partitari", $res2, $rpp, $_return);

		    calce_tabella("partitari", $_return['dare'], $_return['avere'], $_return['saldo'], "193");
		}

		//azzeriamo le variabili..
		$_return = "";
	    }
	}
    }



//ora io farei i clienti
//inanzitutto dobbiamo leggere il database del piano dei conti..

    $query = "SELECT codice, ragsoc from clienti order by codice";

    $res_piano = mysql_query($query, $conn) or mysql_error();

//ora iniziamo il while..

    while ($piano = mysql_fetch_array($res_piano))
    {


//poi per ogni conto creaimo le loro pagine..
	$query = "SELECT *, date_format(data_reg, '%d-%m-%Y') data_reg, date_format(data_cont, '%d-%m-%Y') data_cont, data_cont AS data from prima_nota where anno='$_anno' AND data_cont >='$_anno-01-01' AND data_cont <= '$_anno-12-31' AND conto = '$MASTRO_CLI$piano[codice]' ORDER BY data ASC, nreg ";
//cerco il numero di righe
	if ($res2 = mysql_query($query, $conn) or mysql_error())
	{
//cerco il numero di righe
	    $righe = mysql_num_rows($res2);

	    //selezioniamo slo chi ha muovimenti..
	    if ($righe > "0")
	    {

		//inserisco il numero di righe per pagina
		$_pagine = $righe / $rpp;
		//arrotondo per eccesso
		$pagina = ceil($_pagine);

//	    echo $query;

		for ($_pg = 1; $_pg <= $pagina; $_pg++)
		{
		    crea_pagina_pdf();

		    //intestazione
		    crea_intestazione_ditta_pdf("schede_contabili", "Partitari anno $_anno", $_anno, $_pg, $pagina, $_parametri);

		    intesta_tabella("partitari", $MASTRO_CLI . $piano['codice'], $piano['ragsoc'], $_anno);

		    $_return = corpo_tabella("partitari", $res2, $rpp, $_return);

		    calce_tabella("partitari", $_return['dare'], $_return['avere'], $_return['saldo'], "193");
		}

		//azzeriamo le variabili..
		$_return = "";
	    }
	}
    }

//iniziamo tutti i fornitori..
//ora io farei i clienti
//inanzitutto dobbiamo leggere il database del piano dei conti..

    $query = "SELECT codice, ragsoc from fornitori order by codice";

    $res_piano = mysql_query($query, $conn) or mysql_error();

//ora iniziamo il while..

    while ($piano = mysql_fetch_array($res_piano))
    {


//poi per ogni conto creaimo le loro pagine..
	$query = "SELECT *, date_format(data_reg, '%d-%m-%Y') data_reg, date_format(data_cont, '%d-%m-%Y') data_cont, data_cont AS data from prima_nota where anno='$_anno' AND data_cont >='$_anno-01-01' AND data_cont <= '$_anno-12-31' AND conto = '$MASTRO_FOR$piano[codice]' ORDER BY data ASC, nreg ";
//cerco il numero di righe
	if ($res2 = mysql_query($query, $conn) or mysql_error())
	{
//cerco il numero di righe
	    $righe = mysql_num_rows($res2);

	    //selezioniamo slo chi ha muovimenti..
	    if ($righe > "0")
	    {

		//inserisco il numero di righe per pagina
		$_pagine = $righe / $rpp;
		//arrotondo per eccesso
		$pagina = ceil($_pagine);

//	    echo $query;

		for ($_pg = 1; $_pg <= $pagina; $_pg++)
		{
		    crea_pagina_pdf();

		    //intestazione
		    crea_intestazione_ditta_pdf("schede_contabili", "Partitari anno $_anno", $_anno, $_pg, $pagina, $_parametri);

		    intesta_tabella("partitari", $MASTRO_FOR . $piano['codice'], $piano['ragsoc'], $_anno);

		    $_return = corpo_tabella("partitari", $res2, $rpp, $_return);

		    calce_tabella("partitari", $_return['dare'], $_return['avere'], $_return['saldo'], "193");
		}

		//azzeriamo le variabili..
		$_return = "";
	    }
	}
    }


//chiudiamo il files..

    chiudi_files("partitario_$_anno", "../../..", "F");


    echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"left\">";
    echo "<tr>";

    echo "<td>";

    echo "<td width=\"85%\" align=\"center\" valign=\"top\">\n";
    echo "<h2>Stampa Partitari / Mastrini</h2>";
    echo "<h4>La stampa dei partitari &egrave; stata completata..</h4>\n";
    echo "<h4>Per procedere alla sua visualizzazione e stampa prelevare il file qui sotto..</h4>\n";

    echo "<h4><a href=\"../../../spool/partitario_$_anno.pdf\">Preleva partitario.. </a></h4>\n";


    echo "</td></tr>\n";
    echo "</table></body></html>";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>