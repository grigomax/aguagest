<?php

/* Programma Agua gest
 * Programma nato e gestito da grigolin massimo
 * prodotto sotto licenza GPL
 * per tutte le informazioni massimo@mcetechnik.it
 * aguagest.sourceforge.net
 */

//carichiamo la base del programma includendo i file minimi
$_percorso = "../../";
require $_percorso . "../setting/vars.php";
session_start();
$_SESSION['keepalive'] ++;
//carichiamo le librerie base
require $_percorso . "librerie/lib_html.php";
require "../../librerie/motore_anagrafiche.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);

//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);

$_azione = $_GET['azione'];

if ($_SESSION['user']['anagrafiche'] > "2")
{

    if ($_azione == "nuovo")
    {
        $dati['codice'] = cerca_verifica_numero("cerca_libera", "agenti", $_parametri);
        $_submit = "Inserisci";
        $_data_reg = date('d-m-Y');
    }
    else
    {
        $dati = tabella_agenti("singola", $_POST['codice'], $_parametri);
        $_submit = "Aggiorna";
        $_data_reg = $dati['data_reg'];
    }





    echo "<table width=\"100%\">\n";
    echo "<tr><td align=\"center\" width=\"80%\" valign=\"top\">\n";

    echo "<span class=\"testo_blu\"><br><b>Modifica Anagrafica AGENTE</b></span><br><br>";

    echo "<form action=\"risinseage.php\" method=\"POST\">";
    echo "<table width=\"100%\" border=\"0\"";
// CAMPO Codice ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"><span class=\"testo_blu\"><b>Codice:&nbsp;</b></span></td>\n";
    printf("<td class=\"colonna\" align=\"left\"><input type=\"radio\" name=\"codice\" value=\"%s\" checked>%s</td><tr>\n", $dati['codice'], $dati['codice']);

// CAMPO DATA INS ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"><span class=\"testo_blu\"><b>Agente Dal :&nbsp;</b></span></td>\n";
    printf("<td class=\"colonna\" align=\"left\">%s</td><tr>\n", $_data_reg);


// CAMPO ragione sociale 1 ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"><span class=\"testo_blu\"><b>Ragione Sociale:&nbsp;</b></span></td>";
    printf("<td class=\"colonna\" align=\"left\"><input type=\"text\" name=\"ragsoc\" value=\"%s\" size=\"60\" maxlength=\"100\"></td></tr>\n", $dati['ragsoc']);

// CAMPO Ragione sociale 2 ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"><span class=\"testo_blu\"><b>Ragione Sociale 2:&nbsp;</b></span></td>";
    printf("<td class=\"colonna\" align=\"left\"><input type=\"text\" name=\"ragsoc2\" value=\"%s\" size=\"60\" maxlength=\"100\"></td></tr>\n", $dati['ragsoc2']);


// CAMPO Indirizzo ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"><span class=\"testo_blu\">Indirizzo:&nbsp;</span></td>";
    printf("<td align=\"left\"><input type=\"text\" name=\"indirizzo\" value=\"%s\" size=\"60\" maxlength=\"60\"></td>\n", $dati['indirizzo']);

// CAMPO Cap ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"><span class=\"testo_blu\">Cap :&nbsp;</span></td>";
    printf("<td align=\"left\"><input type=\"text\" name=\"cap\" value=\"%s\" size=\"6\" maxlength=\"5\"></td></tr>", $dati['cap']);

// CAMPO citt�---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"><span class=\"testo_blu\">Citta&nbsp;</span></td>";
    printf("<td align=\"left\"><input type=\"text\" name=\"citta\" value=\"%s\" size=\"60\" maxlength=\"60\"></td></tr>", $dati['citta']);


// CAMPO Provincia ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"><span class=\"testo_blu\">Prov. :&nbsp;</span></td>";
    printf("<td align=\"left\"><input type=\"text\" name=\"prov\" value=\"%s\" size=\"3\" maxlength=\"2\"></td></tr>", $dati['prov']);

// CAMPO Nazione -----------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"><span class=\"testo_blu\">Nazione :&nbsp;</span></td>";
    printf("<td align=\"left\"><input type=\"text\" name=\"codnazione\" value=\"%s\" size=\"20\" maxlength=\"20\"></td></tr>", $dati['codnazione']);

// CAMPO codfiscale -----------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"><span class=\"testo_blu\">Codice Fiscale :&nbsp;</span></td>";
    printf("<td align=\"left\"><input type=\"text\" name=\"codfisc\" value=\"%s\" size=\"20\" maxlength=\"16\"></td></tr>", $dati['codfisc']);

// CAMPO Partita iva -----------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"><span class=\"testo_blu\">Partita Iva :&nbsp;</span></td>";
    printf("<td align=\"left\"><input type=\"text\" name=\"piva\" value=\"%s\" size=\"20\" maxlength=\"14\"></td></tr>", $dati['piva']);

// CAMPO Contatto -----------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"><span class=\"testo_blu\">Contatto riportato sui documenti :&nbsp;</span></td>";
    printf("<td align=\"left\"><input type=\"text\" name=\"contatto\" value=\"%s\" size=\"60\" maxlength=\"60\"></td></tr>", $dati['contatto']);


// CAMPO Telefono -----------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"><span class=\"testo_blu\">Telefono :&nbsp;</span></td>";
    printf("<td align=\"left\"><input type=\"text\" name=\"telefono\" value=\"%s\" size=\"20\" maxlength=\"20\"></td></tr>", $dati['telefono']);

// CAMPO Telefono 2 -----------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"><span class=\"testo_blu\">Telefono 2 :&nbsp;</span></td>";
    printf("<td align=\"left\"><input type=\"text\" name=\"telefono2\" value=\"%s\" size=\"20\" maxlength=\"20\"></td></tr>", $dati['telefono2']);

// CAMPO Cell -----------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"><span class=\"testo_blu\">Cellulare :&nbsp;</span></td>";
    printf("<td align=\"left\"><input type=\"text\" name=\"cell\" value=\"%s\" size=\"20\" maxlength=\"20\"></td></tr>", $dati['cell']);

// CAMPO Fax -----------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"><span class=\"testo_blu\">Fax :&nbsp;</span></td>";
    printf("<td align=\"left\"><input type=\"text\" name=\"fax\" value=\"%s\" size=\"20\" maxlength=\"20\"></td></tr>", $dati['fax']);

    /*
      // CAMPO IVA -----------------------------------------------------------------------------------------
      echo "<tr><td align=\"left\"><span class=\"testo_blu\">* I.V.A. Associata:&nbsp;</span></td>\n";
      echo "<td width=\"200\" align=\"left\">";

      // Stringa contenente la query di ricerca... solo dei fornitori
      $query = sprintf("select codice, descrizione from aliquota where codice=\"%s\"", $dati['iva']);

      // Esegue la query...
      $res = mysql_query($query, $conn);

      // Tutto procede a meraviglia...
      echo "<span class=\"testo_blu\">";
      $dati2 = mysql_fetch_array($res);

      $_ivart = $dati2['codice'];
      $_desiva = $dati2['descrizione'];

      echo "<select name=\"iva\">\n";
      printf("<option value=\"%s\">%s</option>\n", $_ivart, $_desiva);
      echo "<option value=\"\"></option>\n";
      // Stringa contenente la query di ricerca... solo dei fornitori
      $query = sprintf("select codice, descrizione from aliquota order by codice");

      // Esegue la query...
      if ($res = mysql_query($query, $conn))
      {
      // La query ?stata eseguita con successo...
      // MA ANCORA NON SAPPIAMO SE L'UTENTE ESISTA O MENO...
      if (mysql_num_rows($res))
      {
      // Tutto procede a meraviglia...
      echo "<span class=\"testo_blu\">";
      while ($dati3 = mysql_fetch_array($res))
      {
      printf("<option value=\"%s\">%s - %s</option>\n", $dati3['codice'], $dati3['codice'], $dati3['descrizione']);
      }
      }
      }
      echo "</select> Solo se diversa dal sistema\n";
      echo "</td></tr>";

      // CAMPO Codice Pagamento -------------------------------------------------------------------------------
      echo "<tr><td align=\"left\"><span class=\"testo_blu\">Pagamento :&nbsp;</span></td>\n";
      echo "<td width=\"200\" align=\"left\">";

      // Stringa contenente la query di ricerca... solo dei fornitori
      $query = sprintf("select codice, descrizione from pagamenti where codice=\"%s\"", $dati['codpag']);

      // Esegue la query...
      if ($res = mysql_query($query, $conn))
      {
      // La query ?stata eseguita con successo...
      // MA ANCORA NON SAPPIAMO SE L'UTENTE ESISTA O MENO...
      if (mysql_num_rows($res))
      {
      // Tutto procede a meraviglia...
      echo "<span class=\"testo_blu\">";
      while ($dati2 = mysql_fetch_array($res))
      {
      $_descpag = $dati2['descrizione'];
      }
      }

      echo "<select name=\"codpag\">\n";
      printf("<option value=\"%s\">%s</option>\n", $dati['codpag'], $_descpag);

      // Stringa contenente la query di ricerca... solo dei fornitori
      $query = sprintf("select codice, descrizione from pagamenti order by descrizione");

      // Esegue la query...
      if ($res = mysql_query($query, $conn))
      {
      // La query ?stata eseguita con successo...
      // MA ANCORA NON SAPPIAMO SE L'UTENTE ESISTA O MENO...
      if (mysql_num_rows($res))
      {
      // Tutto procede a meraviglia...
      echo "<span class=\"testo_blu\">";
      while ($dati3 = mysql_fetch_array($res))
      {
      printf("<option value=\"%s\">%s</option>\n", $dati3['codice'], $dati3['descrizione']);
      }
      }
      }
      echo "</select>\n";
      }
      echo "</td></tr>";




      // CAMPO Banca -----------------------------------------------------------------------------------
      echo "<tr><td align=\"left\"><span class=\"testo_blu\">Banca :&nbsp;</span></td>\n";
      printf("<td align=\"left\"><input type=\"text\" name=\"banca\" value=\"%s\" size=\"50\" maxlength=\"50\"></td></tr>\n", $dati['banca']);

      // CAMPO ABI ------------------------------------------------------------------------------------
      echo "<tr><td align=\"left\"><span class=\"testo_blu\">Abi :&nbsp;</span></td>";
      printf("<td align=\"left\"><input type=\"text\" name=\"abi\" value=\"%s\" size=\"6\" maxlength=\"5\"></td><tr>", $dati['abi']);

      // CAMPO CAB ------------------------------------------------------------------------------------
      echo "<tr><td align=\"left\"><span class=\"testo_blu\">Cab :&nbsp;</span></td>";
      printf("<td align=\"left\"><input type=\"text\" name=\"cab\" value=\"%s\" size=\"6\" maxlength=\"5\"></td><tr>", $dati['cab']);

      // CAMPO Cin ------------------------------------------------------------------------------------
      echo "<tr><td align=\"left\"><span class=\"testo_blu\">Cin :&nbsp;</span></td>";
      printf("<td align=\"left\"><input type=\"text\" name=\"cin\" value=\"%s\" size=\"2\" maxlength=\"1\"></td><tr>", $dati['cin']);
      // CAMPO cc ------------------------------------------------------------------------------------
      echo "<tr><td align=\"left\"><span class=\"testo_blu\">C/C :&nbsp;</span></td>";
      printf("<td align=\"left\"><input type=\"text\" name=\"cc\" value=\"%s\" size=\"13\" maxlength=\"12\"></td><tr>", $dati['cc']);

      // CAMPO iban ------------------------------------------------------------------------------------
      echo "<tr><td align=\"left\"><span class=\"testo_blu\">Iban :&nbsp;</span></td>";
      printf("<td align=\"left\"><input type=\"text\" name=\"iban\" value=\"%s\" size=\"5\" maxlength=\"4\"></td><tr>", $dati['iban']);
      // CAMPO cc ------------------------------------------------------------------------------------
      echo "<tr><td align=\"left\"><span class=\"testo_blu\">Swift (BIC) :&nbsp;</span></td>";
      printf("<td align=\"left\"><input type=\"text\" name=\"swift\" value=\"%s\" size=\"12\" maxlength=\"11\"></td><tr>", $dati['swift']);


      echo "<tr><td align=\"left\"><span class=\"testo_blu\"></span></td>";
      echo "<td align=\"left\">Oppure Seleziona una delle nostre banche <br>";

      // Stringa contenente la query di ricerca...
      $queryba = sprintf("select * from banche order by banca");
      // Esegue la query...
      $resba = mysql_query($queryba, $conn);
      mysql_num_rows($resba);

      // Tutto procede a meraviglia...
      echo "<span class=\"testo_blu\">";
      while ($datiba = mysql_fetch_array($resba))
      {
      printf("<input type=\"radio\" name=\"istituto\" value=\"%s\"> %s<br>\n", $datiba['codice'], $datiba['banca']);
      }


      echo "</td><tr>";


      // CAMPO Listino associato ------------------------------------------------------------------------------------
      echo "<tr><td align=\"left\"><span class=\"testo_blu\">Listino Associato :&nbsp;</span></td>";
      printf("<td align=\"left\"><input type=\"number\" name=\"listino\" value=\"%s\" size=\"4\" maxlength=\"3\"></td><tr>", $dati['listino']);

      // CAMPO sconto ------------------------------------------------------------------------------------
      echo "<tr><td align=\"left\"><span class=\"testo_blu\">Sconto Cliente listino:&nbsp;</span></td>";
      printf("<td align=\"left\"><input type=\"text\" name=\"scontocli\" value=\"%s\" size=\"6\" maxlength=\"6\"> + <input type=\"text\" name=\"scontocli2\" value=\"%s\" size=\"6\" maxlength=\"6\"> + <input type=\"text\" name=\"scontocli3\" value=\"%s\" size=\"6\" maxlength=\"6\"></td><tr>", $dati['scontocli'], $dati['scontocli2'], $dati['scontocli3']);


      // CAMPO Zona ------------------------------------------------------------------------------------
      echo "<tr><td align=\"left\"><span class=\"testo_blu\">Zona appartenenza :&nbsp;</span></td>";
      printf("<td align=\"left\"><input type=\"text\" name=\"zona\" value=\"%s\" size=\"20\" maxlength=\"21\"></td><tr>", $dati['zona']);
     */
// CAMPO email  ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"><span class=\"testo_blu\">E-mail generale:&nbsp;</span></td>";
    printf("<td class=\"colonna\" align=\"left\"><input type=\"text\" name=\"email\" value=\"%s\" size=\"80\" maxlength=\"80\"></td></tr>\n", $dati['email']);

// CAMPO email  ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"><span class=\"testo_blu\">E-mail 2 invio acquisti:&nbsp;</span></td>";
    printf("<td class=\"colonna\" align=\"left\"><input type=\"text\" name=\"email2\" value=\"%s\" size=\"80\" maxlength=\"80\"></td></tr>\n", $dati['email2']);

// CAMPO email  ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"><span class=\"testo_blu\">E-mail 3 contabilita:&nbsp;</span></td>";
    printf("<td class=\"colonna\" align=\"left\"><input type=\"text\" name=\"email3\" value=\"%s\" size=\"80\" maxlength=\"80\"></td></tr>\n", $dati['email3']);

// CAMPO sito internet ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"><span class=\"testo_blu\">Sito internet:&nbsp;</span></td>";
    printf("<td class=\"colonna\" align=\"left\"><input type=\"text\" name=\"sitocli\" value=\"%s\" size=\"80\" maxlength=\"80\"></td></tr>\n", $dati['sitocli']);

    /*
      // CAMPO Legge privacy --------------------------------------------------------------
      echo "<tr><td align=\"left\"><span class=\"testo_blu\">Legge privacy :&nbsp;</span></td>\n";
      echo "<td width=\"200\" align=\"left\">";
      echo "<select name=\"privacy\">\n";
      printf("<option value=\"%s\">%s</option>\n", $dati['privacy'], $dati['privacy']);
      echo "<option value=\"NO\">NO</option>";
      echo "<option value=\"SI\">SI</option>";
      echo "</select>\n";
      echo "Se <b>NO</b> Se No durante le operazioni di ventita si viene avvisati ";
      echo "</td></tr>";



      // CAMPO nome utente  ---------------------------------------------------------------------------------------
      echo "<tr><td align=\"left\"><span class=\"testo_blu\">utente Internet ?&nbsp;</span></td>";
      $_si = "NO";
      if ($dati['username'] != "")
      {
      $_si = "SI";
      }
      printf("<td class=\"colonna\" align=\"left\">%s</td></tr>\n", $_si);

     */
// CAMPO note ---------------------------------------------------------------------------------------
    echo "<tr><td align=\"left\"><span class=\"testo_blu\">Note :&nbsp;</span></td>";
    printf("<td class=\"colonna\" align=\"left\"><textarea name=\"note\" cols=\"60\" rows=\"15\" WRAP=\"physical\">%s</textarea></td></tr>\n", $dati['note']);


// PULSANTI E CHIUSURA FORM -----------------------------------------------------------------------------------------
    echo "</table>\n<br><input type=\"submit\" name=\"azione\" value=\"$_submit\">&nbsp;<input type=\"submit\" name=\"azione\" value=\"Elimina\">\n";
    echo "</form>\n</td>\n";
    echo "</td>\n</tr>\n";
// ************************************************************************************** -->
    echo "</table>\n";
// Fine tabella pagina princ
// Fine tabella pagina principale -----------------------------------------------------------
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>