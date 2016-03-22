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
//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);

require "../../librerie/motore_anagrafiche.php";


$_tut = $_GET['tut'];
$_codice = $_GET['codice'];
$_utente = $_GET['utente'];
$_azione = $_GET['azione'];




//inizio parte visiva..

base_html($_cosa, $_percorso);

echo "</head>\n";
echo "</body>\n";
testata_html($_cosa, $_percorso);
menu_tendina($_cosa, $_percorso);

// Inizio tabella pagina principale ----------------------------------------------------------
echo "<table width=\"100%\" cellspacing=\"0\" align=\"left\" cellpadding=\"0\">\n";
echo "<tr>";

echo "<td width=\"100%\" align=\"center\" valign=\"top\" class=\"foto\">\n";

if ($_SESSION['user']['anagrafiche'] > "2")
{


    if ($_azione == "seleziona")
    {
        echo "<span class=\"testo_blu\"><br><b>Modifica destinazioni $_tipo</b></span><br><br>";

        echo "<form action=\"modifica_dest.php\" method=\"GET\">";
        
        echo "<table class=\"classic_bordo\">";
        echo "<input type=\"hidden\" name=\"tut\" value=\"$_tut\">\n";
        echo "<tr><td align=\"center\"><h2>Seleziona Cliente</h2></td></tr>\n";
        echo "<tr><td align=\"center\">\n";
        
        tabella_clienti("elenca_select", "utente", $_parametri);
        
        echo "</td></tr>\n";
        
        echo "<tr><td align=\"center\"><input type=\"submit\" name=\"azione\" value=\"nuovo\"></td></tr>\n";
        
        echo "</table>\n";
        
        
    }
    else
    {


        if ($_tut == "c")
        {
            $_tipo = "destinazioni";
            $tipo_cf = "C";
            if ($_azione == "nuovo")
            {
                $_codice = cerca_verifica_numero("cerca_libera", $_tipo, $_utente);
                $_submit = "Inserisci";
                $_data_reg = date('d-m-Y');
            }
            else
            {
                $dati = tabella_destinazioni("singola", $_utente, $_codice, $_parametri);
                $_submit = "Aggiorna";
                $_datareg = $dati['data_reg'];
            }
        }
        else
        {

            $_tipo = "fornitori";
            $tipo_cf = "F";
            $dati['sitocli'] = $dati['sitofor'];

            if ($_azione == "nuovo")
            {
                $_codice = cerca_verifica_numero("cerca_libera", $_tipo, $_parametri);
                $_submit = "Inserisci";
                $_data_reg = date('d-m-Y');
            }
            else
            {
                $dati = tabella_fornitori("singola", $_codice, "");
                $_submit = "Aggiorna";
                $_datareg = $dati['data_reg'];
            }
        }





        echo "<span class=\"testo_blu\"><br><b>Modifica Anagrafica $_tipo</b></span><br><br>";

        echo "<form action=\"risinse_dest.php?tut=$_tut\" method=\"POST\">";

        //--------------------------------------------------------------SECONDA TABS-------------------------------------------------------------------------
        echo "<table class=\"classic_bordo\">";

// inizio destinazione diversa dalla ragione sociale
        echo "<tr><td>&nbsp;</td><td><input type=\"radio\" name=\"utente\" value=\"$_utente\" checked>$_utente - <input type=\"radio\" name=\"codice\" value=\"$_codice\" checked>$_codice</td></tr>\n";
// CAMPO ragione sociale  ---------------------------------------------------------------------------------------
        echo "<tr><td align=\"left\"  ><b>Ragione Sociale destinazione:&nbsp;</b></td>";
        printf("<td align=\"left\"    ><input type=\"text\" name=\"dragsoc\" value=\"%s\" size=\"75\" maxlength=\"100\"></td></tr>\n", $dati['dragsoc']);

// CAMPO Ragione sociale 2 ---------------------------------------------------------------------------------------
        echo "<tr><td align=\"left\"  ><b>Ragione Sociale 2 destinazione:&nbsp;</b></td>";
        printf("<td align=\"left\"    ><input type=\"text\" name=\"dragsoc2\" value=\"%s\" size=\"75\" maxlength=\"100\"></td></tr>\n", $dati['dragsoc2']);

// CAMPO Indirizzo ---------------------------------------------------------------------------------------
        echo "<tr><td align=\"left\"  >Indirizzo destinazione:&nbsp;</td>";
        printf("<td align=\"left\" ><input type=\"text\" name=\"dindirizzo\" value=\"%s\" size=\"60\" maxlength=\"60\"></td>\n", $dati['dindirizzo']);

// CAMPO Cap ---------------------------------------------------------------------------------------
        echo "<tr><td align=\"left\"  >Cap destinazione :&nbsp;</td>";
        printf("<td align=\"left\" ><input type=\"text\" name=\"dcap\" value=\"%s\" size=\"9\" maxlength=\"8\"></td></tr>", $dati['dcap']);

// CAMPO citt�---------------------------------------------------------------------------------------
        echo "<tr><td align=\"left\"  >Citt&agrave; destinazione:&nbsp;</td>";
        printf("<td align=\"left\" ><input type=\"text\" name=\"dcitta\" value=\"%s\" size=\"60\" maxlength=\"60\"></td></tr>", $dati['dcitta']);

// CAMPO Provincia ---------------------------------------------------------------------------------------
        echo "<tr><td align=\"left\"  >Prov. destinazione:&nbsp;</td>";
        printf("<td align=\"left\" ><input type=\"text\" name=\"dprov\" value=\"%s\" size=\"3\" maxlength=\"2\"></td></tr>", $dati['dprov']);

// CAMPO Nazione -----------------------------------------------------------------------------------------
        echo "<tr><td align=\"left\"  >Nazione destinazione:&nbsp;</td>";
        printf("<td align=\"left\" ><input type=\"text\" name=\"dcodnazione\" value=\"%s\" size=\"20\" maxlength=\"20\"></td></tr>", $dati['dcodnazione']);

// CAMPO Telefono -----------------------------------------------------------------------------------------
        echo "<tr><td align=\"left\"  >Telefono destinazione :&nbsp;</td>";
        printf("<td align=\"left\" ><input type=\"text\" name=\"telefonodest\" value=\"%s\" size=\"20\" maxlength=\"20\"></td></tr>", $dati['telefonodest']);

// CAMPO Fax -----------------------------------------------------------------------------------------
        echo "<tr><td align=\"left\"  >Fax destinazione :&nbsp;</td>";
        printf("<td align=\"left\" ><input type=\"text\" name=\"faxdest\" value=\"%s\" size=\"20\" maxlength=\"20\"></td></tr>", $dati['faxdest']);

        echo "<tr><td align=\"left\"  >Email destinazione :&nbsp;</td>";
        printf("<td align=\"left\" ><input type=\"text\" name=\"demail\" value=\"%s\" size=\"70\" maxlength=\"60\"></td></tr>", $dati['demail']);

        echo "<tr><td align=\"left\"  >contatto destinazione :&nbsp;</td>";
        printf("<td align=\"left\" ><input type=\"text\" name=\"dcontatto\" value=\"%s\" size=\"20\" maxlength=\"20\"></td></tr>", $dati['dcontatto']);

        echo "<tr><td align=\"left\">Predefinito ? :&nbsp;</td>";
        echo "<td align=\"left\" ><input type=\"checkbox\" name=\"predefinito\"\n";
        
                if($dati['predefinito'] == "1")
                {
                    echo "value=\"1\" checked>\n";
                }
                else
                {
                    echo "value=\"1\">\n";
                }
                    
                    echo "</td></tr>\n";


        echo "</table>\n";

        echo "</table><center><br><b>Azioni possibili</b></center>\n";
        if ($_SESSION['user']['anagrafiche'] == "4")
        {
            printf("<center><input type=\"submit\" name=\"azione\" value=\"$_submit\"> - <input type=\"submit\" name=\"azione\" value=\"Elimina\"></center>");
        }
        elseif ($_SESSION['user']['anagrafiche'] == "3")
        {
            printf("<center><input type=\"submit\" name=\"azione\" value=\"$_submit\"></center>");
        }
        elseif ($_SESSION['user']['anagrafiche'] == "2")
        {
            printf("<center>Non hai i permessi per poter modificare questo cliente/fornitore</center>");
        }
        else
        {
            printf("<center>Non hai i permessi per poter vedere questo cliente/fornitore</center>");
        }
    }
}
else
{
    echo "<h2>Non hai i permessi per poter visualizzare cliente/fornitore</h2>\n";
}


echo "</form>\n";
//// Fine tabella pagina princ
// Fine tabella pagina principale -----------------------------------------------------------
?>
