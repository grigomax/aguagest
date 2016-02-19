<?php

//carichiamo il percorso
$_percorso = "../../";
require $_percorso . "../setting/vars.php";
//settiamo il tempo di sessione
session_start();
$_SESSION['keepalive'] ++;
require $_percorso . "librerie/lib_html.php";
//carichiamo le sessioni correnti
$conn = permessi_sessione("verifica_PDO", $_percorso);
require "../../librerie/motore_anagrafiche.php";
require "../../librerie/motore_doc_pdo.php";
if ($CONTABILITA == "SI")
{
    include "../../../setting/par_conta.inc.php";
}

//inizio parte visiva..

base_html($_cosa, $_percorso);
java_script($_cosa, $_percorso);
jquery_tabs($_cosa, $_percorso);

echo "</head>\n";
echo "<body>\n";

testata_html($_cosa, $_percorso);

menu_tendina($_cosa, $_percorso);

if ($_SESSION['user']['setting'] > "3")
{
    echo "<h2 align=\"center\">Parametri Azienda e Programma</h2>

    Attenzione non si possono inserire nei campi di scrittura
    NE parole accentate NE apostrofi e TANTOMENO virgolette.<br> 
    La non osservanza potrebbe compromettere l'uso del programma.
    \n";

    echo "<form action=\"salvavars.php\" method=\"POST\">";

    echo "<div id=\"tabs\">\n";
    echo "<ul>\n";
    echo "<li><a href=\"#tabs-1\">Azienda</a></li>\n";
    echo "<li><a href=\"#tabs-2\">Ragione Fiscale</a></li>\n";
    echo "<li><a href=\"#tabs-3\">Parametri Funzionamento</a></li>\n";
    echo "<li><a href=\"#tabs-4\">Mail</a></li>\n";
    echo "<li><a href=\"#tabs-5\">Connessione Al Database</a></li>\n";
    echo "<li><a href=\"#tabs-6\">Sezione Programmatore</a></li>\n";
    echo "</ul>\n";

    echo "<div id=\"tabs-1\">\n";

    echo "<table class=\"classic_bordo\" border=\"0\" align=\"center\" width=\"80%\">\n";
    echo "<tr>
                    <td colspan=\"2\" rowspan=\"1\" style=\"width: 350px;\" align=\"center\" valign=\"top\"><span style=\"font-weight: bold;\">Dati Sede Legale</span></td>
                </tr>\n";

    echo "<tr>\n";
    echo "<td>Nome Azienda</td>\n";
    echo "<td><input type=\"text\" size=\"70\" name=\"azienda\" value=\"$azienda\"></td>\n";
    echo "</tr><tr>\n";
    echo "<td>Nome Azienda 2 riga</td>\n";
    echo "<td><input type=\"text\" size=\"70\" name=\"azienda2\" value=\"$azienda2\"></td>\n";
    echo "</tr><tr>\n";
    echo "<td>Indirizzo</td>\n";
    echo "<td><input type=\"text\" size=\"70\" name=\"indirizzo\" value=\"$indirizzo\"></td>\n";
    echo "</tr><tr>\n";
    echo "<td>Cap</td>\n";
    echo "<td><input type=\"text\" size=\"70\" name=\"cap\" value=\"$cap\"></td>\n";
    echo "</tr><tr>\n";
    echo "<td>Citta</td>\n";
    echo "<td><input type=\"text\" size=\"70\" name=\"citta\" value=\"$citta\"></td>\n";
    echo "</tr><tr>\n";
    echo "<td>Provincia</td>\n";
    echo "<td><input type=\"text\" size=\"70\" name=\"prov\" value=\"$prov\"></td>\n";
    echo "</tr><tr>\n";
    echo "<td>Nazione</td>\n";
    echo "<td><input type=\"text\" size=\"70\" name=\"nazione\" value=\"$nazione\"></td>\n";
    echo "</tr><tr>\n";
    echo "<td>P. Iva</td>\n";
    echo "<td><input type=\"text\" size=\"70\" name=\"piva\" value=\"$piva\"></td>\n";
    echo "</tr><tr>\n";
    echo "<td>cod. fiscale</td>\n";
    echo "<td><input type=\"text\" size=\"70\" name=\"codfisc\" value=\"$codfisc\"></td>\n";
    echo "</tr><tr>\n";
    echo "<td>Telefono</td>\n";
    echo "<td><input type=\"text\" size=\"70\" name=\"telefono\" value=\"$telefono\"></td>\n";
    echo "</tr><tr>\n";
    echo "<td>Telefono 2</td>\n";
    echo "<td><input type=\"text\" size=\"70\" name=\"telefono2\" value=\"$telefono2\"></td>\n";
    echo "</tr><tr>\n";
    echo "<td>cellulare</td>\n";
    echo "<td><input type=\"text\" size=\"70\" name=\"cell\" value=\"$cell\"></td>\n";
    echo "</tr><tr>\n";
    echo "<td>Fax</td>\n";
    echo "<td><input type=\"text\" size=\"70\" name=\"fax\" value=\"$fax\"></td>\n";
    echo "</tr>\n";
    echo "<tr>
        <td colspan=\"2\" rowspan=\"1\" align=\"center\" valign=\"top\"><span style=\"font-weight: bold;\">Dati Sede Amministrativa o punto vendita se esistente</span></td>
    </tr>\n";

    echo "<tr>\n";
    echo "<td>Nome Azienda</td>\n";
    echo "<td><input type=\"text\" size=\"70\" name=\"DAZIENDA\" value=\"$DAZIENDA\"></td>\n";
    echo "</tr><tr>\n";
    echo "<td>Nome Azienda 2 riga</td>\n";
    echo "<td><input type=\"text\" size=\"70\" name=\"DAZIENDA2\" value=\"$DAZIENDA2\"></td>\n";
    echo "</tr><tr>\n";
    echo "<td>Indirizzo</td>\n";
    echo "<td><input type=\"text\" size=\"70\" name=\"DINDIRIZZO\" value=\"$DINDIRIZZO\"></td>\n";
    echo "</tr><tr>\n";
    echo "<td>Cap</td>\n";
    echo "<td><input type=\"text\" size=\"70\" name=\"DCAP\" value=\"$DCAP\"></td>\n";
    echo "</tr><tr>\n";
    echo "<td>Citta</td>\n";
    echo "<td><input type=\"text\" size=\"70\" name=\"DCITTA\" value=\"$DCITTA\"></td>\n";
    echo "</tr><tr>\n";
    echo "<td>Provincia</td>\n";
    echo "<td><input type=\"text\" size=\"70\" name=\"DPROV\" value=\"$DPROV\"></td>\n";
    echo "</tr><tr>\n";
    echo "<td>Nazione</td>\n";
    echo "<td><input type=\"text\" size=\"70\" name=\"DNAZIONE\" value=\"$DNAZIONE\"></td>\n";
    echo "</tr><tr>\n";
    echo "<td>Telefono</td>\n";
    echo "<td><input type=\"text\" size=\"70\" name=\"DTELEFONO\" value=\"$DTELEFONO\"></td>\n";
    echo "</tr><tr>\n";
    echo "<td>Telefono 2</td>\n";
    echo "<td><input type=\"text\" size=\"70\" name=\"DTELEFONO2\" value=\"$DTELEFONO2\"></td>\n";
    echo "</tr><tr>\n";
    echo "<td>cellulare</td>\n";
    echo "<td><input type=\"text\" size=\"70\" name=\"DCELL\" value=\"$DCELL\"></td>\n";
    echo "</tr><tr>\n";
    echo "<td>Fax</td>\n";
    echo "<td><input type=\"text\" size=\"70\" name=\"DFAX\" value=\"$DFAX\"></td>\n";
    echo "</tr>\n";



    echo "</table>\n";
    echo "</div>\n";

//--------------------------------------------------------------SECONDA TABS-------------------------------------------------------------------------
    echo "<div id=\"tabs-2\">\n";
    echo "<table class=\"tabs\">";
    echo "<tr>\n";
    echo "<td>Codice SIA assegnato per riba elettroniche</td>\n";
    echo "<td><input type=\"text\" size=\"6\" maxlength=\"5\" name=\"SIA\" value=\"$SIA\"></td>\n";
    echo "</tr><tr>\n";
    echo "<td>Tipo di società</td>\n";
    echo "<td><select name=\"TIPOSOC\">\n";
    echo "<option value=\"$TIPOSOC\">$TIPOSOC</option>\n";
    echo "<option value=\"S.N.C.\">S.N.C.</option>\n";
    echo "<option value=\"S.R.L.\">S.R.L.</option>\n";
    echo "<option value=\"S.R.L.S.\">S.R.L.S.</option>\n";
    echo "<option value=\"S.P.A.\">S.P.A.</option>\n";
    echo "<option value=\"S.A.S.\">S.A.S.</option>\n";
    echo "<option value=\"S.D.F.\">S.D.F.</option>\n";
    echo "<option value=\"S.N.C.\">S.N.C.</option>\n";
    echo "<option value=\"S.D.F.\">S.D.F.</option>\n";
    echo "<option value=\"INDI\">Individuale</option>\n";
    echo "</select></td></tr>\n";
    echo "
                <tr>
                    <td>REA ufficio di</td>
                    <td><input type=\"text\" size=\"3\" maxlength=\"2\" name=\"REAUFFICIO\" value=\"$REAUFFICIO\"> Es. PD VI ecc.</td>
                </tr>
                <tr>
                    <td>REA Numero</td>
                    <td><input type=\"text\" size=\"10\" maxlength=\"20\" name=\"REANUMERO\" value=\"$REANUMERO\"> Es. 192686 ecc.</td>
                </tr>
                <tr>
                    <td>Capitale sociale</td>
                    <td><input type=\"text\" size=\"10\" maxlength=\"15\" name=\"CAPSOCIALE\" value=\"$CAPSOCIALE\"> Es. 10000.00 separare i decimali con il punto</td>
                </tr>\n";
    echo "<tr>\n";
    echo "<td>Numero soci</td>\n";
    echo "<td><select name=\"SOCIOUNICO\">\n";
    echo "<option value=\"$SOCIOUNICO\">$SOCIOUNICO</option>\n";
    echo "<option value=\"SU\">SU Socio unico</option>\n";
    echo "<option value=\"SM\">SM Soci multipli</option>\n";

    echo "</select></td></tr>\n";
    echo "<tr>\n";
    echo "<td>Attività azienda</td>\n";
    echo "<td><select name=\"LIQUIDAZIONE\">\n";
    echo "<option value=\"$LIQUIDAZIONE\">$LIQUIDAZIONE</option>\n";
    echo "<option value=\"LS\">LS In liquidazione</option>\n";
    echo "<option value=\"LN\">LN In attività normale</option>\n";
    echo "</select></td></tr>\n";

    echo "</table>\n";
    echo "</div>\n";


    //--------------------------------------------------------------SECONDA TABS-------------------------------------------------------------------------
    echo "<div id=\"tabs-3\">\n";
    echo "<table class=\"tabs\">";

    echo "<tr><td colspan=\"2\" rowspan=\"1\" style=\"width: 350px;\" align=\"center\" valign=\"top\"><span style=\"font-weight: bold;\">Paramentri funzionamento</span></td>\n";
    echo "</tr>\n";
    
    echo "<tr><td colspan=\"2\" align=\"center\"><b>Documenti di vendita</b></td></tr>\n";
    echo "<tr>\n";
    echo "<td>Nome del documento fattura con gestione del magazzino</td>\n";
    echo "<td><input type=\"text\" size=\"70\" name=\"nomedoc\" value=\"$nomedoc\"></td>\n";
    echo "</tr><tr>\n";
    echo "<td>Lettera suffisso standard per i documenti</td>\n";
    echo "<td><input type=\"text\" size=\"2\" maxlenght=\"2\" name=\"SUFFIX_DDT\" value=\"$SUFFIX_DDT\"> Lettera Maiuscola dalla A alla Z</td>\n";
    echo "</tr><tr>\n";
    echo "<td>Causale base per i ddt</td>\n";
    causale_trasporto($_cosa, $_causale);
    
    
    echo "</tr>\n";
    echo "<tr><td colspan=\"2\" align=\"center\"><hr></td></tr>\n";
    echo "<tr><td colspan=\"2\" align=\"center\"><b>Decimali</b></td></tr>\n";
    
    echo "
    
                
                <tr>
                    <td>Numero di decimali usati per tutte le applicazioni</td>
                    <td><input type=\"number\" size=\"6\" maxlenght=\"5\" name=\"dec\" value=\"$dec\"></td>
                </tr>
                <tr>
                    <td>Numero decimali per arrotondamento totale documento</td>
                    <td><input type=\"number\" size=\"3\" maxlenght=\"1\" name=\"decdoc\" value=\"$decdoc\"></td>
                </tr>
                <tr>
                    <td>numero di listini destinali alla vendita</td>
                    <td><input type=\"number\" size=\"5\" maxlenght=\"3\" name=\"nlv\" value=\"$nlv\"></td>
                </tr>
                \n";

    
    echo "<tr><td colspan=\"2\" align=\"center\"><hr></td></tr>\n";
    echo "<tr><td colspan=\"2\" align=\"center\"><b>Gestione IVA</b></td></tr>\n";
    
    
    if (($_GET['azione'] != "install") AND ( $_GET['azione'] != "recovery"))
    {
        $DESCRIZIONE_CAMPO = "";

        //----------------------------
        if ($ivasis != "")
        {
            $DESCRIZIONE_CAMPO = tabella_aliquota("singola", $ivasis, $_percorso);
        }
        echo "<td>Aliquota iva di sistema</td>\n";
        echo "<td><select name=\"ivasis\">\n";
        echo "<option value=\"$ivasis\">$DESCRIZIONE_CAMPO[descrizione] - $ivasis</option>";
        echo "<option value=\"\"></option>";
        // Tutto procede a meraviglia...
        echo "<span class=\"testo_blu\">";

        $res_2 = tabella_aliquota("elenca_codice", $_codiva, $_percorso);
        foreach ($res_2 AS $dati)
        {
            printf("<option value=\"%s\">%s - %s</option>\n", $dati['codice'], $dati['descrizione'], $dati['codice']);
        }
        echo "</select>\n";
        echo "  Eff. cambio iva ?  <input type=\"checkbox\" name=\"change\" value=\"si\">\n";
        echo "</tr><tr>\n";
        $DESCRIZIONE_CAMPO = "";
    }
    else
    {
        echo "<tr>\n";
        echo "<td>Aliquota iva di sistema</td>\n";
        echo "<td><input type=\"number\" size=\"4\" maxlenght=\"3\"name=\"ivasis\" value=\"$ivasis\"></td>\n";
        echo "</tr>\n";
    }

    echo "<tr>\n";
    echo "<td>Data di entrata in vigore Iva</td>\n";
    echo "<td><input type=\"text\" size=\"11\" maxlenght=\"10\"name=\"DATAIVA\" value=\"$DATAIVA\">Inserire Data americana anno-mese-giorno</td>\n";
    echo "</tr>\n";
    echo "
                <tr>
                    <td>Aliquota iva multiple</td>
                    <td><input type=\"radio\" name=\"ivamulti\" value=\"SI\"\n";

    if ($IVAMULTI == "SI")
    {
        echo "checked";
    }
    else
    {
        echo "";
    }
    echo ">SI <input type=\"radio\" name=\"ivamulti\" value=\"NO\" \n";
    if ($IVAMULTI == "NO")
    {
        echo "checked";
    }
    else
    {
        echo "";
    }
    echo ">NO</td>\n";
    echo "
                </tr>
                <tr><td colspan=\"2\"><hr></td></tr>

                <tr>
                    <td>Abilitare il reparto contabilit&agrave; ?</td>
                    <td><input type=\"radio\" name=\"CONTABILITA\" value=\"SI\" \n";
    if ($CONTABILITA == "SI")
    {
        echo "checked";
    }
    else
    {
        echo "";
    }
    echo ">SI
                        <input type=\"radio\" name=\"CONTABILITA\" value=\"NO\" \n";
    if ($CONTABILITA == "NO")
    {
        echo "checked";
    }
    else
    {
        echo "";
    }
    echo ">NO
                        Abilita la gestione della contabilita <br> Attenzione Per chi abilit&agrave; la funzione ad esercizio avviato deve prima compilare i parametri contabilit&agrave; !</td>
                </tr>

                <tr><td colspan=\"2\"><hr></td></tr>
                <tr>
                \n";


    echo "<td>Abilitare funzione Codice a Barre ?</td>\n";
    echo "<td><select name=\"TERMINAL_CODE\">\n";
    echo "<option value=\"$TERMINAL_CODE\">$TERMINAL_CODE</option>\n";
    echo "<option value=\"SI\">SI</option>\n";
    echo "<option value=\"NO\">NO</option>\n";
    echo "</select></td>\n";
    echo "</tr><tr>\n";
    echo "<td>Nome del file da caricare per il terminale a barre</td>\n";
    echo "<td><input type=\"text\" size=\"70\" name=\"NOME_FILECODBAR\" value=\"$NOME_FILECODBAR\"></td>\n";
    echo "</tr><tr>\n";
    echo "<td>Numero di righe da eliminare alla fine del file</td>
                    <td><input type=\"number\" size=\"10\" name=\"RIGHE_FILECODBAR\" value=\"$RIGHE_FILECODBAR\"></td>
                </tr>
                <tr><td colspan=\"2\"><hr></td></tr>
                <tr>
                    <td>Abilitare la scritta sulla calce dei documenti rilativo alle condizioni generali di ventita ?</td>
                    <td><input type=\"radio\" name=\"CGV\" value=\"SI\" \n";
    if ($CGV == "SI")
    {
        echo "checked";
    }
    else
    {
        echo "";
    }
    echo ">SI <input type=\"radio\" name=\"CGV\" value=\"NO\" \n";

    if ($CGV == "NO")
    {
        echo "checked";
    }
    else
    {
        echo "";
    }
    echo ">NO\n";
    echo "</td></tr><tr>\n";


    echo "</table>\n";
    echo "</div>\n";

    //--------------------------------------------------------------SECONDA TABS-------------------------------------------------------------------------
    echo "<div id=\"tabs-4\">\n";
    echo "<table class=\"tabs\">";

    echo "
    <tr>
                    <td colspan=\"2\" rowspan=\"1\" ><span style=\"font-weight: bold;\">Parametri estesione Server esterni per collegamento sito interne azienda</span></td>
                </tr>
                <tr>
                    <td>sito internet</td>
                    <td><input type=\"text\" size=\"70\" name=\"sitointernet\" value=\"$sitointernet\"></td>
                </tr>
                <tr>
                    <td>e-mail generale</td>
                    <td><input type=\"text\" size=\"70\" name=\"email1\" value=\"$email1\"></td>
                </tr>
                <tr>
                    <td>e-mail ufficio acquisti</td>
                    <td><input type=\"text\" size=\"70\" name=\"email2\" value=\"$email2\"></td>
                </tr>
                <tr>
                    <td>e-mail ufficio contabilita</td>
                    <td><input type=\"text\" size=\"70\" name=\"email3\" value=\"$email3\"></td>
                </tr>
                <tr>
                    <td>e-mail negozio on line</td>
                    <td><input type=\"text\" size=\"70\" name=\"email4\" value=\"$email4\"></td>
                </tr>
                <tr><td colspan=\"2\"><hr></td></tr>\n";
    echo "<tr><td colspan=\"2\"><h3>Nel caso di un accesso google è necessario Fare la procedura di autentificazione</h3>\n";
    echo "<h4><a href=\"http://www.google.com/intl/it/landing/2step/\">http://www.google.com/intl/it/landing/2step/</a></h4>\n";

    echo "</td></tr>\n";


    echo "<tr>
                    <td>nome Host locale</td>
                    <td><input type=\"text\" size=\"70\" name=\"HOSTNAME\" value=\"$HOSTNAME\"><br> es. localhost</td>
                </tr>						
                <tr>
                    <td>nome server uscita posta</td>
                    <td><input type=\"text\" size=\"70\" name=\"mailsmtp\" value=\"$mailsmtp\"><br> es. mail.tin.it  oppure smtp.gmail.com</td>
                </tr>
                <tr>
                    <td>numero porta uscita posta</td>
                    <td><input type=\"text\" size=\"70\" name=\"PORTSMTP\" value=\"$PORTSMTP\"><br> es. 25 o 587 oppure per gmail 587</td>
                </tr>
                <tr>
                    <td>il server posta richiede la password in uscita ?</td>
                    <td align=\"left\" valign=\"top\"><input type=\"radio\" name=\"smtpout\" value=\"true\"\n";



    if ($smtpout == "true")
    {
        echo "checked";
    }
    else
    {
        echo "";
    }
    echo ">SI <input type=\"radio\" name=\"smtpout\" value=\"false\"\n";

    if ($smtpout == "false")
    {
        echo "checked";
    }
    else
    {
        echo "";
    }
    echo ">NO - Se SI &egrave; obbligatorio inserire la password sotto</td>\n";
    echo "</tr><tr>\n";
    echo "<td>Il server Richiede la crittografia ?</td>\n";
    echo "<td style=\"width: 40%; text-align: left;\" valign=\"top\"><select name=\"SSL\">\n";
    echo "<option value=\"$SSL\">$SSL</option>\n";
    echo "<option value=\"NO\">NO</option>\n";
    echo "<option value=\"ssl\">ssl</option>\n";
    echo "<option value=\"tls\">tls</option>\n";
    echo "</select>Esempio per Gmail è la tls</td>\n";
    echo "</tr><tr>\n";

    echo "
      <td>nome utente posta in uscita</td>
      <td><input type=\"text\" size=\"70\" name=\"smtpuser\" value=\"$smtpuser\"></td>
      </tr>
      <tr>
      <td>password posta in uscita</td>
      <td><input type=\"text\" size=\"70\" name=\"smtppass\" value=\"$smtppass\"></td>
      </tr>
    \n";
    echo "</table>\n";
    echo "</div>\n";

    //--------------------------------------------------------------SECONDA TABS-------------------------------------------------------------------------
    echo "<div id=\"tabs-5\">\n";
    echo "<table class=\"tabs\">";
    echo "
    <tr>
                    <td>Nome Host<br>
                        nome del server locale</td>
                    <td style=\"width: 350px; text-align: left;\" valign=\"top\"><input type=\"text\" size=\"70\" name=\"host\" value=\"$host\"></td>
                </tr>
                <tr>
                    <td>Percorso assoluto</td>
                    <td style=\"width: 350px; text-align: left;\" valign=\"top\"><input type=\"text\" size=\"70\" name=\"sito\" value=\"$sito\"></td>
                </tr>
                <tr><td colspan=\"2\"><hr></td></tr>
                <tr>
                    <td colspan=\"2\" rowspan=\"1\" style=\"width: 350px;\" align=\"center\" valign=\"top\"><span style=\"font-weight: bold;\">Parametri mysql</span></td>
                </tr>
                <tr>
                    <td>Nome host server mysql</td>
                    <td style=\"width: 350px; text-align: left;\" valign=\"top\"><input type=\"text\" size=\"70\" name=\"db_server\" value=\"$db_server\"></td>
                </tr>
                <tr>
                    <td>Nome utente mysql</td>
                    <td style=\"width: 350px; text-align: left;\" valign=\"top\"><input type=\"text\" size=\"70\" name=\"db_user\" value=\"$db_user\"></td>
                </tr>
                <tr>
                    <td>Password mysql</td>
                    <td style=\"width: 350px; text-align: left;\" valign=\"top\"><input type=\"text\" size=\"70\" name=\"db_password\" value=\"$db_password\"></td>
                </tr>
                <tr>
                    <td>Nome archivio</td>
                    <td style=\"width: 350px; text-align: left;\" valign=\"top\"><input type=\"text\" size=\"70\" name=\"db_nomedb\" value=\"$db_nomedb\"></td>
                </tr>
                

                
\n";

    echo "</table>\n";
    echo "</div>\n";

    echo "<div id=\"tabs-6\">\n";
    echo "<table class=\"tabs\">";
    echo " <tr>
                    <td>Modatità Debug</td>
                    <td style=\"width: 350px; text-align: left;\" valign=\"top\"><input type=\"text\" size=\"3\" name=\"DEBUG\" value=\"$DEBUG\"></td>
                </tr>\n";

    echo " <tr>
                    <td>Modatità Inoltra Errori per posta</td>
                    <td style=\"width: 350px; text-align: left;\" valign=\"top\"><input type=\"text\" size=\"3\" name=\"ERRORMAIL\" value=\"$ERRORMAIL\"></td>
                </tr>\n";

    echo " <tr>
                    <td>Registra Query</td>
                    <td style=\"width: 350px; text-align: left;\" valign=\"top\"><input type=\"text\" size=\"3\" name=\"REGISTRA\" value=\"$REGISTRA\"></td>
                </tr>\n";
    echo " <tr>
                    <td>Email sviluppatore</td>
                    <td style=\"width: 350px; text-align: left;\" valign=\"top\"><input type=\"text\" size=\"50\" name=\"EMAILSVILUPPO\" value=\"$EMAILSVILUPPO\"></td>
                </tr>\n";

    echo "</table>\n";
    echo "</div>\n";




    echo "<td colspan=\"2\" align=\"center\"><br><input type=\"submit\" name=\"azione\" value=\"Modifica\"></td></tr>\n";

    echo "</tbody></table></form></div></body></html>\n";
}
else
{
    echo "<h2>Non hai i permessi per poter visualizzare cliente/fornitore</h2>\n";
}
?>