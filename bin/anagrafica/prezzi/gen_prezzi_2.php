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


//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);


if ($_SESSION['user']['anagrafiche'] > "2")
{

    echo "<center> Stampa Errori </center>\n";

//Prelevo i post inviatomi
//controllo cosa arriva
    if (($_POST['ccodini'] != "") AND ($_POST['ccodfin'] != ""))
    {
	$_codini = $_POST['ccodini'];
	$_codfin = $_POST['ccodfin'];
    }
    else
    {
	//il codice di inizio
	$_codini = $_POST['codini'];
	//il codice di fine
	$_codfin = $_POST['codfin'];
    }

//il numero del listino da ritoccare;
    $_listino = $_POST['listino'];

//il tipo di calcolo
    $_calcolo = $_POST['calcolo'];
//tutti i moltiplicatori
    $_alto = $_POST['alto'];
    $_medio = $_POST['medio'];
    $_basso = $_POST['basso'];

    $_prima = $_POST['prima'];
    $_seconda = $_POST['seconda'];
    $_terza = $_POST['terza'];


    if ($_calcolo == "prezzo_1")
    {/*

     * Ora bisogna calcolare in base al tipo di calcolo i prezzi di vandita:
     * Il Primo caso e la generazione dei prezzi partendo dal prezzo di vendita 1
     * Bisognera fare tutte le operazioni ed in caso non ci sia il prezzo lo
     * facciamo apparire a video
     */

	//Listini articolo..
	$query = sprintf("select articolo, substring(descrizione,1,40) AS descrizione, preacqnetto, preacqnetto2, ultacq from articoli where articolo >= \"%s\" and articolo <= \"%s\" order by articolo", $_codini, $_codfin);
	// questa selezione mi permette di avere il numero di pagine ed il numero di
	//righe in anticipo
//    echo $query;
	$res = mysql_query($query, $conn);

//    Ora con un ciclio di while prendiamo tutti i dati e li elaboriamo
	mysql_num_rows($res);

	while ($dati = mysql_fetch_array($res))
	{

	    $_articolo = $dati['articolo'];
	    $_prezzo = "";
	    //eseguiamo tuti i conti.
	    #Verifico che ci siano i valori altrimenti lo sostituisco con un altro
	    if (($dati['preacqnetto'] == "") OR ($dati['preacqnetto'] == "0.00"))
	    {
		$_preacqnetto = $dati['preacqnetto2'];
		$_prezzo = "prezzo 2";
	    }
	    else
	    {
		$_preacqnetto = $dati['preacqnetto'];
	    }

	    //calcolo
	    if ($_preacqnetto <= $_basso)
	    {
		$_vendita = ($_preacqnetto * $_prima);
	    }
	    elseif ($_preacqnetto <= $_medio)
	    {
		$_vendita = ($_preacqnetto * $_seconda);
	    }
	    else
	    {
		$_vendita = ($_preacqnetto * $_terza);
	    }

	    // a questo punto dobbiamo aggiornare il listino vendita;

	    $query_lis = sprintf("UPDATE listini SET listino =\"%s\" WHERE rigo=\"%s\" and codarticolo=\"%s\"", $_vendita, $_listino, $_articolo);

	    if (mysql_query($query_lis, $conn) != 0)
	    {
		echo "Modifica prezzi ad articolo $_articolo.. riuscita con $_prezzo<br>\n";
	    }
	    else
	    {
		echo ">>>>Attenzione Errore nell 'articolo $_articolo<br> \n";
	    }
	}//fine while
    }//fine funzione


    if ($_calcolo == "prezzo_2")
    {/*

     * Ora bisogna calcolare in base al tipo di calcolo i prezzi di vandita:
     * Il Primo caso e la generazione dei prezzi partendo dal prezzo di vendita 2
     * Bisognera fare tutte le operazioni ed in caso non ci sia il prezzo lo
     * facciamo apparire a video
     */

	//Listini articolo..
	$query = sprintf("select articolo, substring(descrizione,1,40) AS descrizione, preacqnetto, preacqnetto2, ultacq from articoli where articolo >= \"%s\" and articolo <= \"%s\" order by articolo", $_codini, $_codfin);
	// questa selezione mi permette di avere il numero di pagine ed il numero di
	//righe in anticipo
	$res = mysql_query($query, $conn);

//    Ora con un ciclio di while prendiamo tutti i dati e li elaboriamo
	mysql_num_rows($res);

	while ($dati = mysql_fetch_array($res))
	{

	    $_articolo = $dati['articolo'];
	    $_prezzo = "";
	    //eseguiamo tuti i conti.
	    if (($dati['preacqnetto2'] == "") OR ($dati['preacqnetto2'] == "0.00"))
	    {
		$_preacqnetto = $dati['preacqnetto'];
		$_prezzo = "prezzo1";
	    }
	    else
	    {
		$_preacqnetto = $dati['preacqnetto2'];
	    }
	    //calcolo
	    if ($_preacqnetto <= $_basso)
	    {
		$_vendita = ($_preacqnetto * $_prima);
	    }
	    elseif ($_preacqnetto <= $_medio)
	    {
		$_vendita = ($_preacqnetto * $_seconda);
	    }
	    else
	    {
		$_vendita = ($_preacqnetto * $_terza);
	    }

	    // a questo punto dobbiamo aggiornare il listino vendita;

	    $query_lis = sprintf("UPDATE listini SET listino =\"%s\" WHERE rigo=\"%s\" and codarticolo=\"%s\"", $_vendita, $_listino, $_articolo);
	    if (mysql_query($query_lis, $conn) != 0)
	    {
		echo "Modifica prezzi ad articolo $_articolo.. riuscita con $_prezzo<br>\n";
	    }
	    else
	    {
		echo ">>>>Attenzione Errore nell 'articolo $_articolo<br> \n";
	    }
	}//fine while
    }//fine funzione

    if ($_calcolo == "ultimo")
    {/*

     * Ora bisogna calcolare in base al tipo di calcolo i prezzi di vandita:
     * Il Primo caso e la generazione dei prezzi partendo dal prezzo di ultimo aquisto
     * Bisognera fare tutte le operazioni ed in caso non ci sia il prezzo lo
     * facciamo apparire a video
     */

	//Listini articolo..
	$query = sprintf("select articolo, substring(descrizione,1,40) AS descrizione, preacqnetto, preacqnetto2, ultacq from articoli where articolo >= \"%s\" and articolo <= \"%s\" order by articolo", $_codini, $_codfin);
	// questa selezione mi permette di avere il numero di pagine ed il numero di
	//righe in anticipo
	$res = mysql_query($query, $conn);

//    Ora con un ciclio di while prendiamo tutti i dati e li elaboriamo
	mysql_num_rows($res);

	while ($dati = mysql_fetch_array($res))
	{

	    $_articolo = $dati['articolo'];
	    $_prezzo = "";
	    //eseguiamo tuti i conti.
	    if (($dati['ultacq'] == "") OR ($dati['ultacq'] == "0.00"))
	    {
		$_preacqnetto = $dati['preacqnetto'];
		$_prezzo = "prezzo 1";
	    }
	    elseif (($dati['preacqnetto'] == "") OR ($dati['preacqnetto'] == "0.00"))
	    {
		$_preacqnetto = $dati['preacqnetto2'];
		$_prezzo = "Prezzo 2";
	    }
	    else
	    {
		$_preacqnetto = $dati['ultacq'];
	    }


	    //calcolo
	    if ($_preacqnetto <= $_basso)
	    {
		$_vendita = ($_preacqnetto * $_prima);
	    }
	    elseif ($_preacqnetto <= $_medio)
	    {
		$_vendita = ($_preacqnetto * $_seconda);
	    }
	    else
	    {
		$_vendita = ($_preacqnetto * $_terza);
	    }

	    // a questo punto dobbiamo aggiornare il listino vendita;

	    $query_lis = sprintf("UPDATE listini SET listino =\"%s\" WHERE rigo=\"%s\" and codarticolo=\"%s\"", $_vendita, $_listino, $_articolo);
	    if (mysql_query($query_lis, $conn) != 0)
	    {
		echo "Modifica prezzi ad articolo $_articolo.. riuscita con $_prezzo<br>\n";
	    }
	    else
	    {
		echo ">>>>Attenzione Errore nell 'articolo $_articolo<br> \n";
	    }
	}//fine while
    }//fine funzione


    if ($_calcolo == "media")
    {/*

     * Ora bisogna calcolare in base al tipo di calcolo i prezzi di vandita:
     * Il Primo caso e la generazione dei prezzi partendo dalla media dei Prezzi di acquisto
     * Bisognera fare tutte le operazioni ed in caso non ci sia il prezzo lo
     * facciamo apparire a video
     */

	//Listini articolo..
	$query = sprintf("select articolo, substring(descrizione,1,40) AS descrizione, preacqnetto, preacqnetto2, ultacq from articoli where articolo >= \"%s\" and articolo <= \"%s\" order by articolo", $_codini, $_codfin);
	// questa selezione mi permette di avere il numero di pagine ed il numero di
	//righe in anticipo
	$res = mysql_query($query, $conn);

//    Ora con un ciclio di while prendiamo tutti i dati e li elaboriamo
	mysql_num_rows($res);

	while ($dati = mysql_fetch_array($res))
	{

	    $_articolo = $dati['articolo'];

	    //selezioniamo l'articolo dal magazzino e ne facciamo la media
	    $querym = "SELECT SUM(qtacarico) AS qtacarico, SUM(valoreacq) AS valoreacq from magazzino where tut != 'giacin' AND articolo = $_articolo";
	    $resm = mysql_query($querym, $conn);
	    $datim = mysql_fetch_array($resm);


//	effettuiamo la media;

	    @$_media = $datim['valoreacq'] / $datim['qtacarico'];

//	sistemo i decimali
	    $_media = number_format(($_media), 2);

	    $_prezzo = "";
	    //eseguiamo tuti i conti.
	    if (($_media == "") OR ($_media == "0.00"))
	    {
		$_preacqnetto = $dati['preacqnetto'];
		$_prezzo = "prezzo 1";
	    }
	    elseif (($dati['preacqnetto'] == "") OR ($dati['preacqnetto'] == "0.00"))
	    {
		$_preacqnetto = $dati['preacqnetto2'];
		$_prezzo = "Prezzo 2";
	    }
	    else
	    {
		$_preacqnetto = $_media;
	    }


	    //calcolo
	    if ($_preacqnetto <= $_basso)
	    {
		$_vendita = ($_preacqnetto * $_prima);
	    }
	    elseif ($_preacqnetto <= $_medio)
	    {
		$_vendita = ($_preacqnetto * $_seconda);
	    }
	    else
	    {
		$_vendita = ($_preacqnetto * $_terza);
	    }

	    // a questo punto dobbiamo aggiornare il listino vendita;

	    $query_lis = sprintf("UPDATE listini SET listino =\"%s\" WHERE rigo=\"%s\" and codarticolo=\"%s\"", $_vendita, $_listino, $_articolo);
	    if (mysql_query($query_lis, $conn) != 0)
	    {
		echo "$_media poi $_preacqnetto Modifica prezzi ad articolo $_articolo.. riuscita<br>\n";
	    }
	    else
	    {
		echo ">>>>Attenzione Errore nell 'articolo $_articolo<br> \n";
	    }
	}//fine while
    }//fine funzione

    if ($_calcolo == "media_p1p2")
    {/*

     * Calcolo prezzi sulla media del prezzo di acquisto uno e due se poi non si trovano i prezzi
     * automaticamente si prende uno dei due prezzi
     */

	//Listini articolo..
	$query = sprintf("select articolo, substring(descrizione,1,40) AS descrizione, preacqnetto, preacqnetto2, ultacq from articoli where articolo >= \"%s\" and articolo <= \"%s\" order by articolo", $_codini, $_codfin);
	// questa selezione mi permette di avere il numero di pagine ed il numero di
	//righe in anticipo
	$res = mysql_query($query, $conn);

//    Ora con un ciclio di while prendiamo tutti i dati e li elaboriamo
	mysql_num_rows($res);

	while ($dati = mysql_fetch_array($res))
	{

	    $_articolo = $dati['articolo'];

	    //selezioniamo l'articolo dal magazzino e ne facciamo la media
	    #$querym = "SELECT SUM(qtacarico) AS qtacarico, SUM(valoreacq) AS valoreacq from magazzino where tut != 'giacin' AND articolo = $_articolo";
	    #$resm = mysql_query( $querym, $conn );
	    #$datim = mysql_fetch_array($resm);


	    $_prezzo = "";
	    //eseguiamo tuti i conti.
	    if (($dati['preacqnetto'] == "") OR ($dati['preacqnetto'] == "0.00"))
	    {
		$_preacqnetto = $dati['preacqnetto2'];
		$_prezzo = "prezzo 2";
	    }
	    elseif (($dati['preacqnetto2'] == "") OR ($dati['preacqnetto2'] == "0.00"))
	    {
		$_preacqnetto = $dati['preacqnetto'];
		$_prezzo = "Prezzo 1";
	    }
	    else
	    {
		$_preacqnetto1 = $dati['preacqnetto'];
		$_preacqnetto2 = $dati['preacqnetto2'];
		$_prezzo = "tutto ok";
		//	effettuiamo la media;
		@$_media = ($_preacqnetto1 + $_preacqnetto2) / 2;

		//	sistemo i decimali
		$_preacqnetto = number_format(($_media), 2);
	    }


	    //calcolo
	    if ($_preacqnetto <= $_basso)
	    {
		$_vendita = ($_preacqnetto * $_prima);
	    }
	    elseif ($_preacqnetto <= $_medio)
	    {
		$_vendita = ($_preacqnetto * $_seconda);
	    }
	    else
	    {
		$_vendita = ($_preacqnetto * $_terza);
	    }

	    // a questo punto dobbiamo aggiornare il listino vendita;

	    $query_lis = sprintf("UPDATE listini SET listino =\"%s\" WHERE rigo=\"%s\" and codarticolo=\"%s\"", $_vendita, $_listino, $_articolo);
	    if (mysql_query($query_lis, $conn) != 0)
	    {
		echo "$_preacqnetto Modifica prezzi ad articolo $_articolo.. riuscita $_prezzo<br>\n";
	    }
	    else
	    {
		echo ">>>>Attenzione Errore nell 'articolo $_articolo<br> \n";
	    }
	}//fine while
    }//fine funzione

    if ($_calcolo == "media_p1uc")
    {/*

      Il prezzo viene calcolto sulla media tra il prezzo di acquisto n. 1 e l'ultimo acquisto.
     * Nel caso non si venga trovato il prezzo sarà sul uno dei due.
     */

	//Listini articolo..
	$query = sprintf("select articolo, substring(descrizione,1,40) AS descrizione, preacqnetto, preacqnetto2, ultacq from articoli where articolo >= \"%s\" and articolo <= \"%s\" order by articolo", $_codini, $_codfin);
	// questa selezione mi permette di avere il numero di pagine ed il numero di
	//righe in anticipo
	$res = mysql_query($query, $conn);

//    Ora con un ciclio di while prendiamo tutti i dati e li elaboriamo
	mysql_num_rows($res);

	while ($dati = mysql_fetch_array($res))
	{

	    $_articolo = $dati['articolo'];

	    //selezioniamo l'articolo dal magazzino e ne facciamo la media
	    #$querym = "SELECT SUM(qtacarico) AS qtacarico, SUM(valoreacq) AS valoreacq from magazzino where tut != 'giacin' AND articolo = $_articolo";
	    #$resm = mysql_query( $querym, $conn );
	    #$datim = mysql_fetch_array($resm);


	    $_prezzo = "";
	    //eseguiamo tuti i conti.
	    if (($dati['preacqnetto'] == "") OR ($dati['preacqnetto'] == "0.00"))
	    {
		$_preacqnetto = $dati['ultacq'];
		$_prezzo = "ultacq";
	    }
	    elseif (($dati['ultacq'] == "") OR ($dati['ultacq'] == "0.00"))
	    {
		$_preacqnetto = $dati['preacqnetto'];
		$_prezzo = "Prezzo 1";
	    }
	    else
	    {
		$_preacqnetto1 = $dati['preacqnetto'];
		$_preacqnetto2 = $dati['ultacq'];
		$_prezzo = "tutto ok";
		//	effettuiamo la media;
		@$_media = ($_preacqnetto1 + $_preacqnetto2) / 2;

		//	sistemo i decimali
		$_preacqnetto = number_format(($_media), 2);
	    }


	    //calcolo
	    if ($_preacqnetto <= $_basso)
	    {
		$_vendita = ($_preacqnetto * $_prima);
	    }
	    elseif ($_preacqnetto <= $_medio)
	    {
		$_vendita = ($_preacqnetto * $_seconda);
	    }
	    else
	    {
		$_vendita = ($_preacqnetto * $_terza);
	    }

	    // a questo punto dobbiamo aggiornare il listino vendita;

	    $query_lis = sprintf("UPDATE listini SET listino =\"%s\" WHERE rigo=\"%s\" and codarticolo=\"%s\"", $_vendita, $_listino, $_articolo);
	    if (mysql_query($query_lis, $conn) != 0)
	    {
		echo "$_preacqnetto Modifica prezzi ad articolo $_articolo.. riuscita $_prezzo<br>\n";
	    }
	    else
	    {
		echo ">>>>Attenzione Errore nell 'articolo $_articolo<br> \n";
	    }
	}//fine while
    }//fine funzione

    if ($_calcolo == "media_p2uc")
    {/*

      Il prezzo viene calcolto sulla media tra il prezzo di acquisto n. 2 e l'ultimo acquisto.
     * Nel caso non si venga trovato il prezzo sarà sul uno dei due.
     */

	//Listini articolo..
	$query = sprintf("select articolo, substring(descrizione,1,40) AS descrizione, preacqnetto, preacqnetto2, ultacq from articoli where articolo >= \"%s\" and articolo <= \"%s\" order by articolo", $_codini, $_codfin);
	// questa selezione mi permette di avere il numero di pagine ed il numero di
	//righe in anticipo
	$res = mysql_query($query, $conn);

//    Ora con un ciclio di while prendiamo tutti i dati e li elaboriamo
	mysql_num_rows($res);

	while ($dati = mysql_fetch_array($res))
	{

	    $_articolo = $dati['articolo'];

	    //selezioniamo l'articolo dal magazzino e ne facciamo la media
	    #$querym = "SELECT SUM(qtacarico) AS qtacarico, SUM(valoreacq) AS valoreacq from magazzino where tut != 'giacin' AND articolo = $_articolo";
	    #$resm = mysql_query( $querym, $conn );
	    #$datim = mysql_fetch_array($resm);


	    $_prezzo = "";
	    //eseguiamo tuti i conti.
	    if (($dati['preacqnetto2'] == "") OR ($dati['preacqnetto2'] == "0.00"))
	    {
		$_preacqnetto = $dati['ultacq'];
		$_prezzo = "ultacq";
	    }
	    elseif (($dati['ultacq'] == "") OR ($dati['ultacq'] == "0.00"))
	    {
		$_preacqnetto = $dati['preacqnetto2'];
		$_prezzo = "Prezzo 2";
	    }
	    else
	    {
		$_preacqnetto1 = $dati['preacqnetto2'];
		$_preacqnetto2 = $dati['ultacq'];
		$_prezzo = "tutto ok";
		//	effettuiamo la media;
		@$_media = ($_preacqnetto1 + $_preacqnetto2) / 2;

		//	sistemo i decimali
		$_preacqnetto = number_format(($_media), 2);
	    }


	    //calcolo
	    if ($_preacqnetto <= $_basso)
	    {
		$_vendita = ($_preacqnetto * $_prima);
	    }
	    elseif ($_preacqnetto <= $_medio)
	    {
		$_vendita = ($_preacqnetto * $_seconda);
	    }
	    else
	    {
		$_vendita = ($_preacqnetto * $_terza);
	    }

	    // a questo punto dobbiamo aggiornare il listino vendita;

	    $query_lis = sprintf("UPDATE listini SET listino =\"%s\" WHERE rigo=\"%s\" and codarticolo=\"%s\"", $_vendita, $_listino, $_articolo);
	    if (mysql_query($query_lis, $conn) != 0)
	    {
		echo "$_preacqnetto Modifica prezzi ad articolo $_articolo.. riuscita $_prezzo<br>\n";
	    }
	    else
	    {
		echo ">>>>Attenzione Errore nell 'articolo $_articolo<br> \n";
	    }
	}//fine while
    }//fine funzione

    if ($_calcolo == "prezzo_1_ma")
    {/*

      Il prezzo viene calcolto sulla media tra il prezzo di acquisto n. 1 e l'ultimo acquisto.
     * Nel caso non si venga trovato il prezzo sarà sul uno dei due.
     */

	//Listini articolo..
	$query = sprintf("select articolo, substring(descrizione,1,40) AS descrizione, preacqnetto, preacqnetto2, ultacq from articoli where articolo >= \"%s\" and articolo <= \"%s\" order by articolo", $_codini, $_codfin);
	// questa selezione mi permette di avere il numero di pagine ed il numero di
	//righe in anticipo
	$res = mysql_query($query, $conn);

//    Ora con un ciclio di while prendiamo tutti i dati e li elaboriamo
	mysql_num_rows($res);

	while ($dati = mysql_fetch_array($res))
	{

	    $_articolo = $dati['articolo'];

	    //selezioniamo l'articolo dal magazzino e ne facciamo la media
	    $querym = "SELECT SUM(qtacarico) AS qtacarico, SUM(valoreacq) AS valoreacq from magazzino where tut != 'giacin' AND articolo = $_articolo";
	    $resm = mysql_query($querym, $conn);
	    $datim = mysql_fetch_array($resm);

	    //	effettuiamo la media;

	    @$_media = $datim['valoreacq'] / $datim['qtacarico'];

//	sistemo i decimali
	    $_preacqnetto2 = number_format(($_media), 2);


	    $_prezzo = "";
	    //eseguiamo tuti i conti.
	    if (($dati['preacqnetto'] == "") OR ($dati['preacqnetto'] == "0.00"))
	    {
		$_preacqnetto = $dati['preacqnetto2'];
		$_prezzo = "prezzo2";
	    }
	    elseif (($_preacqnetto2 == "") OR ($_preacqnetto2 == "0.00"))
	    {
		$_preacqnetto2 = $dati['preacqnetto'];
		$_prezzo = "Prezzo 1";
	    }
	    else
	    {
		$_preacqnetto1 = $dati['preacqnetto'];
		#$_preacqnetto2 = $dati['ultacq'];
		$_prezzo = "tutto ok";
		//	effettuiamo la media;
		@$_media = ($_preacqnetto1 + $_preacqnetto2) / 2;

		//	sistemo i decimali
		$_preacqnetto = number_format(($_media), 2);
	    }


	    //calcolo
	    if ($_preacqnetto <= $_basso)
	    {
		$_vendita = ($_preacqnetto * $_prima);
	    }
	    elseif ($_preacqnetto <= $_medio)
	    {
		$_vendita = ($_preacqnetto * $_seconda);
	    }
	    else
	    {
		$_vendita = ($_preacqnetto * $_terza);
	    }

	    // a questo punto dobbiamo aggiornare il listino vendita;

	    $query_lis = sprintf("UPDATE listini SET listino =\"%s\" WHERE rigo=\"%s\" and codarticolo=\"%s\"", $_vendita, $_listino, $_articolo);
	    if (mysql_query($query_lis, $conn) != 0)
	    {
		echo "$_preacqnetto Modifica prezzi ad articolo $_articolo.. riuscita $_prezzo<br>\n";
	    }
	    else
	    {
		echo ">>>>Attenzione Errore nell 'articolo $_articolo<br> \n";
	    }
	}//fine while
    }//fine funzione

    if ($_calcolo == "prezzo_2_ma")
    {/*

      Il prezzo viene calcolto sulla media tra il prezzo di acquisto n. 2 e l'ultimo acquisto.
     * Nel caso non si venga trovato il prezzo sarà sul uno dei due.
     */

	//Listini articolo..
	$query = sprintf("select articolo, substring(descrizione,1,40) AS descrizione, preacqnetto, preacqnetto2, ultacq from articoli where articolo >= \"%s\" and articolo <= \"%s\" order by articolo", $_codini, $_codfin);
	// questa selezione mi permette di avere il numero di pagine ed il numero di
	//righe in anticipo
	$res = mysql_query($query, $conn);

//    Ora con un ciclio di while prendiamo tutti i dati e li elaboriamo
	mysql_num_rows($res);

	while ($dati = mysql_fetch_array($res))
	{

	    $_articolo = $dati['articolo'];

	    //selezioniamo l'articolo dal magazzino e ne facciamo la media
	    $querym = "SELECT SUM(qtacarico) AS qtacarico, SUM(valoreacq) AS valoreacq from magazzino where tut != 'giacin' AND articolo = $_articolo";
	    $resm = mysql_query($querym, $conn);
	    $datim = mysql_fetch_array($resm);

	    //	effettuiamo la media;

	    @$_media = $datim['valoreacq'] / $datim['qtacarico'];

//	sistemo i decimali
	    $_preacqnetto1 = number_format(($_media), 2);


	    $_prezzo = "";
	    //eseguiamo tuti i conti.
	    if (($dati['preacqnetto2'] == "") OR ($dati['preacqnetto2'] == "0.00"))
	    {
		$_preacqnetto = $dati['preacqnetto'];
		$_prezzo = "prezzo1";
	    }
	    elseif (($_preacqnetto1 == "") OR ($_preacqnetto1 == "0.00"))
	    {
		$_preacqnetto1 = $dati['preacqnetto2'];
		$_prezzo = "Prezzo 2";
	    }
	    else
	    {
		$_preacqnetto2 = $dati['preacqnetto2'];
		#$_preacqnetto2 = $dati['ultacq'];
		$_prezzo = "tutto ok";
		//	effettuiamo la media;
		@$_media = ($_preacqnetto1 + $_preacqnetto2) / 2;

		//	sistemo i decimali
		$_preacqnetto = number_format(($_media), 2);
	    }


	    //calcolo
	    if ($_preacqnetto <= $_basso)
	    {
		$_vendita = ($_preacqnetto * $_prima);
	    }
	    elseif ($_preacqnetto <= $_medio)
	    {
		$_vendita = ($_preacqnetto * $_seconda);
	    }
	    else
	    {
		$_vendita = ($_preacqnetto * $_terza);
	    }

	    // a questo punto dobbiamo aggiornare il listino vendita;

	    $query_lis = sprintf("UPDATE listini SET listino =\"%s\" WHERE rigo=\"%s\" and codarticolo=\"%s\"", $_vendita, $_listino, $_articolo);
	    if (mysql_query($query_lis, $conn) != 0)
	    {
		echo "$_preacqnetto Modifica prezzi ad articolo $_articolo.. riuscita $_prezzo<br>\n";
	    }
	    else
	    {
		echo ">>>>Attenzione Errore nell 'articolo $_articolo<br> \n";
	    }
	}//fine while
    }//fine funzione

    if ($_calcolo == "totale")
    {/*

      Viene calcolata la media su tutti i prezzi di vendita incluso il magazzino ecc..
     *
     */
	echo "div - media - preacqnetto1 - preacqnetto2 - magazzino Modifica prezzi ad articolo articolo.. riuscita nuovo prezzo = vendita<br>\n";
	//Listini articolo..
	$query = sprintf("select articolo, substring(descrizione,1,40) AS descrizione, preacqnetto, preacqnetto2, ultacq from articoli where articolo >= \"%s\" and articolo <= \"%s\" order by articolo", $_codini, $_codfin);
	// questa selezione mi permette di avere il numero di pagine ed il numero di
	//righe in anticipo
	$res = mysql_query($query, $conn);

//    Ora con un ciclio di while prendiamo tutti i dati e li elaboriamo
	mysql_num_rows($res);

	while ($dati = mysql_fetch_array($res))
	{

	    $_articolo = $dati['articolo'];

	    //selezioniamo l'articolo dal magazzino e ne facciamo la media
	    $querym = "SELECT SUM(qtacarico) AS qtacarico, SUM(valoreacq) AS valoreacq from magazzino where tut != 'giain' AND articolo = $_articolo";
	    $resm = mysql_query($querym, $conn);
	    $datim = mysql_fetch_array($resm);

	    //	effettuiamo la media;

	    @$_media = $datim['valoreacq'] / $datim['qtacarico'];

//	sistemo i decimali
	    $_magazzino = number_format(($_media), 2);


	    $_prezzo = "";
	    $_div = "";
// 	$_magazzino = $dati['preacqnetto'];
	    $_preacqnetto1 = $dati['preacqnetto'];
	    $_preacqnetto2 = $dati['preacqnetto2'];
	    //eseguiamo tuti i conti.
	    if (($dati['preacqnetto'] == "") OR ($dati['preacqnetto'] == "0.00"))
	    {
// 	    $_preacqnetto1 = $dati['preacqnetto2'];
		$_prezzo = "prezzo1";
		$_div = $_div - 1;
	    }
	    if (($dati['preacqnetto2'] == "") OR ($dati['preacqnetto2'] == "0.00"))
	    {
// 	    $_preacqnetto2 = $dati['preacqnetto'];
		$_prezzo = "Prezzo 1";
		$_div = $_div - 1;
	    }
	    if (($_magazzino == "") OR ($_magazzino == "0.00"))
	    {
// 	    $_magazzino = $dati['preacqnetto'];
		$_prezzo = "Prezzo 1 magaz";
		$_div = $_div - 1;
	    }


	    //	effettuiamo la media;
	    @$_media = ($_preacqnetto1 + $_preacqnetto2 + $_magazzino) / (3 + $_div);

	    //	sistemo i decimali
	    $_preacqnetto = number_format(($_media), 2);


	    if ($_preacqnetto <= $_basso)
	    {
		$_vendita = ($_preacqnetto * $_prima);
	    }
	    elseif ($_preacqnetto <= $_medio)
	    {
		$_vendita = ($_preacqnetto * $_seconda);
	    }
	    else
	    {
		$_vendita = ($_preacqnetto * $_terza);
	    }

	    // a questo punto dobbiamo aggiornare il listino vendita;

	    $query_lis = sprintf("UPDATE listini SET listino =\"%s\" WHERE rigo=\"%s\" and codarticolo=\"%s\"", $_vendita, $_listino, $_articolo);
	    if (mysql_query($query_lis, $conn) != 0)
	    {
		echo "Div $_div - Media $_preacqnetto - Lis 1 $_preacqnetto1 - Lis 2 $_preacqnetto2 - Mag. $_magazzino Modifica prezzi ad articolo $_articolo.. riuscita con $_prezzo nuovo prezzo = $_vendita<br>\n";
	    }
	    else
	    {
		echo ">>>>Attenzione Errore nell 'articolo $_articolo<br> \n";
	    }
	}//fine while
    }//fine funzione
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>