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
require "../../librerie/motore_doc_pdo.php";

//carichiamo la base delle pagine:
base_html("chiudi", $_percorso);

//carichiamo la testata del programma.
testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
menu_tendina($_cosa, $_percorso);

if ($_SESSION['user']['vendite'] > "1")
{

    $_tdoc = $_GET['tdoc'];
    $_codice = $_GET['codice'];

    echo "<table width=\"100%\" cellspacing=\"0\" cellpadding=\"0\" border=\"0\" align=\"center\">\n";
    echo "<tr><td width=\"85%\" align=\"center\" valign=\"top\">\n";

    echo "<form action=\"privacydoc.php\" method=\"POST\">\n";


    if ($_tdoc == "fornitore")
    {
        echo "<span class=\"intestazione\">Gestione Ordini acquisto<br><br> <b>Scegliere il Fornitore</b></span><br></td></tr>\n";
        echo "<tr><td align=center><br><b>Selezionare il tipo documento</b><br>";

        if ($_tdoc == "fornitore")
        {
            echo "<input type=radio name=tipodoc value=fornitore checked>Ordine Fornitore&nbsp;";
        }
        echo "</td></tr>\n";
        echo "<tr><td align=center><br>";
        echo "Seleziona il suffisso di numerazione\n";
        suffisso("select", "suffix", $_parametri);
        echo "</td></tr>\n";
        echo "<tr><td align=center><br>";
        tabella_fornitori("elenca_select", "utente", $_parametri);
    }
    elseif ($_tdoc == "ddtacq")
    {

        echo "<span class=\"intestazione\">Gestione Ordini acquisto<br><br> <b>Scegliere il Fornitore</b></span><br></td></tr>\n";
        echo "<tr><td align=center><br><b>Selezionare il tipo documento</b><br>";


        echo "<input type=radio name=tipodoc value=ddtacq checked>D.D.T. Acquisto&nbsp;";

        echo "</td></tr>\n";
        echo "<tr><td align=center><br>";
        echo "Seleziona il suffisso di numerazione\n";
        suffisso("select", "suffix", $_parametri);
        echo "</td></tr>\n";
        echo "<tr><td align=center><br>";
        tabella_fornitori("elenca_select", "utente", $_parametri);
    }
    else
    {

        echo "<span class=\"intestazione\">Gestione Documenti vendita<br><br> <b>Scegliere il cliente</b></span><br></td></tr>\n";
        echo "<tr><td align=center><br><b>Selezionare il tipo documento</b><br>";


        if ($_tdoc == "ddt")
        {
            echo "<input type=radio name=tipodoc value=ddt checked> D.D.T. - Bolla &nbsp;";
        }
        elseif ($_tdoc == "conferma")
        {
            echo "<input type=radio name=tipodoc value=conferma checked> Conferma d'ordine &nbsp;";
        }
        elseif ($_tdoc == "conferma")
        {
            echo "<input type=radio name=tipodoc value=conferma checked> Conferma d'ordine &nbsp;";
        }
        elseif ($_tdoc == "preventivo")
        {
            echo "<input type=radio name=tipodoc value=preventivo checked> Preventivo";
        }
        elseif ($_tdoc == "ordine")
        {
            echo "<input type=radio name=tipodoc value=ordine checked> Ordine Da Agente &nbsp;";
        }
        elseif ($_tdoc == "fattura")
        {
            echo "<input type=radio name=tipodoc value=\"$nomedoc\" checked>$nomedoc";
            echo "<input type=radio name=tipodoc value=\"FATTURA\" >Fattura";
            echo "<input type=radio name=tipodoc value=\"NOTA CREDITO\" >Nota Credito";
            echo "<input type=radio name=tipodoc value=\"NOTA DEBITO\" >Nota Debito";
        }
        else
        {
            echo "<input type=radio name=tipodoc value=ddt checked> D.D.T. - Bolla &nbsp;";
            echo "<input type=radio name=tipodoc value=conferma > Conferma d'ordine &nbsp;";
            echo "<input type=radio name=tipodoc value=ordine > Ordine Da Agente &nbsp;";
            echo "<input type=radio name=tipodoc value=preventivo > Preventivo";
            echo "<br>";
            echo "<input type=radio name=tipodoc value=\"$nomedoc\" >$nomedoc";
            echo "<input type=radio name=tipodoc value=\"FATTURA\" >Fattura";
            echo "<input type=radio name=tipodoc value=\"NOTA CREDITO\" >Nota Credito";
            echo "<input type=radio name=tipodoc value=\"NOTA DEBITO\" >Nota Debito";
        }
        echo "</td></tr>\n";
        echo "<tr><td align=center><br>";
        echo "Seleziona il suffisso di numerazione\n";
        suffisso("select", "suffix", $_parametri);
        echo "</td></tr>\n";
        echo "<tr><td align=center><br>";
        if ($_codice != "")
        {
            tabella_clienti("elenca_select_singolo", "utente", $_codice);
        }
        else
        {
            tabella_clienti("elenca_select", "utente", $_parametri);
        }
    }

    echo "</table><center><br><input type=\"reset\" value=\"Cancella\">&nbsp;<input type=\"submit\" value=\"Avanti\">\n";
    echo "</form>\n</td>\n";
    echo "</td>\n</tr>\n";
    echo "</table>";
    echo "</body></html>";
}
else
{

    echo "<td width=\"85%\" align=\"center\" valign=\"top\">";
    echo "<span class=\"intestazione\"><br><b>Gestione Vendite</b></span><br>";
    echo "<span class=\"intestazione\"><br><b>Non hai i permessi per entrare</b></span><br>";

    echo "</td>
		</tr>
		</table>
		</body>
		</html>";
}
?>