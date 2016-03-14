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


//carichiamo la base delle pagine:
base_html("", $_percorso);


if ($_SESSION['user']['magazzino'] > "2")
{
    ?>

    <script language="JavaScript">
        function giac(form) {
            giacin = eval(form.giacin.value)
            pmsin = eval(form.pmsin.value)
            qtacarico = eval(form.qtacarico.value)
            valoreacq = eval(form.valoreacq.value)
            totcarico = eval(form.totcarico.value)
            qtascarico = eval(form.qtascarico.value)

            valin = pmsin * giacin
            pmpcarico = valoreacq / qtacarico

            totcarico = giacin + qtacarico

            giaeff = totcarico - qtascarico

            result = "OK"

            if (giaeff < 0)
                result = "NO"

            form.valin.value = valin
            form.pmpcarico.value = pmpcarico
            form.totcarico.value = totcarico
            form.giaeff.value = giaeff
            form.result.value = result
        }

    </script>

    <?php

    echo "</head>\n";
//carichiamo la testata del programma.
    testata_html($_cosa, $_percorso);

//carichiamo il menu a tendina..
    menu_tendina($_cosa, $_percorso);

    echo "<center>\n";
    echo "<big><span style=\"color: rgb(204, 0, 0);\">GESTIONE MOVIMENTO ARTICOLI</span></big>\n";
    echo "</center><small>\n";

// includiamo il files funzioni generali
// prendiamoci il codice articolo da modificare
    $_articolo = $_POST['codice'];
    $_anno = $_POST['anno'];


    //vediamo se il magazzino è ancora aperto..
    $query = "SELECT anno FROM magazzino WHERE tut = 'giain' ORDER BY anno LIMIT 1";

    $datianno = domanda_db("query", $query, $_cosa, "fetch", $_parametri);

    if ($_anno < $datianno['anno'])
    {
        $_magazzino = "magastorico";
    }
    else
    {
        $_magazzino = "magazzino";
    }



    $query = "select * from articoli where articolo='$_articolo'";

    $dati = domanda_db("query", $query, $_cosa, "fetch", "verbose");

// La query ?stata eseguita con successo...
// MA ANCORA NON SAPPIAMO SE L'UTENTE ESISTA O MENO...
    if ($dati == "NO")
    {
        echo "<h2><center>Nessun articolo Trovato</h2></center>";
        return;
    }
    else
    {

        printf("<span style=\"color: rgb(51, 204, 0); font-weight: bold;\">Codice :</span> %s", $_articolo);
        echo "<span style=\"font-weight: bold;\">&nbsp;</span>";
        printf("<span style=\"color: rgb(204, 51, 204); font-weight: bold;\">Gruppo:</span> %s", $dati['catmer']);
        echo "<brstyle=\"font-weight: bold;\">";
        printf("<span style=\"color: rgb(204, 51, 204); font-weight: bold;\"><br>Descrizione:</span> %s", $dati['descrizione']);
        printf("<span style=\"color: rgb(204, 51, 204); font-weight: bold;\"><br>Prezzo articolo in anagrafica:</span> Netto acquisto 1 = %s netto acquisto 2 = %s ultimo acquisto = % ", $dati['preacqnetto'], $dati['preacqnetto2'], $dati['ultacq']);
        echo "</small></big>\n";

        echo "<form action=\"mod-p2.php?anno=$_anno&codice=$_articolo\" method=\"POST\">\n";

        #echo "<form action=\"ciao.php\" method=\"POST\">\n";

        echo "<hr style=\"width: 100%; height: 2px;\">\n";

        echo "<center><h4> SITUAZIONE ARTICOLO $_magazzino anno in corso $_anno</h4></center>\n";

        echo "<table border=\"0\" align=\"center\" cellspacing=\"2\" cellpadding=\"2\">\n";

        echo "<tr><td colspan=\"1\" rowspan=\"4\"> CARICHI :<br></td>\n";
        echo "<td><br></td>\n";
        echo "<td style=\"vertical-align: top;\">Quantit&agrave;<br></td>\n";
        echo "<td style=\"vertical-align: top;\">P.M.P.<br></td>\n";
        echo "<td style=\"vertical-align: top;\">Valore Tot.<br></td>\n";
        echo "</tr>\n";

// setto a zero le variabili dei campi in modo che lo script java funzioni senza errori

        $_giacin = "0";
        $_valin = "0";
        $_qtacarico = "0";
        $_valoreacq = "0";

        echo "<tr><td style=\"vertical-align: top;\">Iniziale<br></td>";

//inizio a prendere i dati dal magazzino
        $_tut = "giain";

        $query = "select qtacarico, valoreacq from $_magazzino where anno='$_anno' and tut='$_tut' and articolo='$_articolo'";
        echo $query;

// Esegue la query...
        $dati2 = domanda_db("query", $query, $_cosa, "fetch", "verbose");

//Mi prendo le veriabili

        $_giacin = $dati2['qtacarico'];
        $_valin = $dati2['valoreacq'];


        printf("<td style=\"vertical-align: top;\"><input type=\"text\" size=\"10\" name=\"giacin\" value=\"%s\" autofocus onChange=\"giac(this.form)\" ><br></td> ", $_giacin);

        if ($_valin OR $_giacin != "")
        {
            @$_pmsin = $_valin / $_giacin;
        }

        printf("<td style=\"vertical-align: top;\"><input type=\"text\" size=\"10\" name=\"pmsin\" onChange=\"giac(this.form)\" value=%s><br></td>", number_format(($_pmsin), 2));

        printf("<td valign=\"center\" style=\"vertical-align: top;\"><input type=\"text\" size=\"10\" name=\"valin\" value=\"%s\" ></td></TR>", $_valin);


        echo "<tr> <td style=\"vertical-align: top;\">Annuale <br> </td>";

//inizio a prendere i dati dal magazzino carico
// faccio le somme dei valori per ogni articolo

        $query = sprintf("select sum(qtacarico) AS qtacarico, sum(valoreacq) AS valoreacq from $_magazzino where anno=\"%s\" and tut != 'giain' and articolo=\"%s\"", $_anno, $_articolo);

        echo $query;
// Esegue la query...
        $dati3 = domanda_db("query", $query, $_cosa, "fetch", "verbose");
        $_qtacarico = $dati3['qtacarico'];
        $_valoreacq = $dati3['valoreacq'];

        if ($_qtacarico == "")
        {
            $_qtacarico = "0.00";
            $_valoreacq = "0.00";
        }

        printf("<td style=\"vertical-align: top;\"><input type=\"text\" size=\"10\" name=\"qtacarico\" value=%s><br></td>", $_qtacarico);



        if ($_valoreacq OR $_qtacarico != 0.00)
        {
            @$_pmpcarico = $_valoreacq / $_qtacarico;
        }
        printf("<td style=\"vertical-align: top;\"><input type=\"text\" size=\"10\" name=\"pmpcarico\" value=%s><br></td>", number_format(($_pmpcarico), $dec, '.', ''));

        printf("<td style=\"vertical-align: top;\"><input type=\"text\" size=\"10\" name=\"valoreacq\" value=%s><br></td></tr>", $_valoreacq);

        echo "<tr><td style=\"vertical-align: top;\">Totale<br></td>";

        $_totcarico = $_giacin + $_qtacarico;

        printf("<td style=\"vertical-align: top;\"><input type=\"text\" size=\"10\" name=\"totcarico\" value=%s><br></td>", $_totcarico);

        echo" <td style=\"vertical-align: top;\"><br>
      </td>
    <tr>
      <td colspan=\"5\" rowspan=\"1\" style=\"vertical-align: top;\"><br>
      </td>
    </tr>";

        echo "<tr><td align=\"center\" colspan=\"1\" rowspan=\"2\" style=\"vertical-align: middle;\">SCARICHI<br></td>";


        echo "<td style=\"vertical-align: top;\">Annuale <br></td>";

//inizio a prendere i dati dal magazzino scarico

        $query = sprintf("select sum(qtascarico) AS qtascarico, sum(valorevend) AS valven from $_magazzino where anno=\"%s\" and articolo=\"%s\"", $_anno, $_articolo);
        echo $query;
        $dati4 = domanda_db("query", $query, $_cosa, "fetch", "verbose");
        $_qtascarico = $dati4['qtascarico'];
        $_valven = $dati4['valven'];




        printf("<td style=\"vertical-align: top;\"><input type=\"text\" size=\"10\" name=\"qtascarico\" value=%s><br></td>", $_qtascarico);



        if ($_valven OR $_qtascarico != "")
        {
            @$_pmsfin = $_valven / $_qtascarico;
        }
        printf("<td style=\"vertical-align: top;\"><input type=\"text\" size=\"10\" name=\"pmsfin\" value=%s><br></td>", number_format(($_pmsfin), $dec, '.', ''));
        printf("<td style=\"vertical-align: top;\"><input type=\"text\" size=\"10\" name=\"valfin\" value=%s><br></td></tr>", $_valven);

        echo "<td style=\"vertical-align: top;\"><br>
            </td>
            <td style=\"vertical-align: top;\"><br>
            </td>
            <td style=\"vertical-align: top;\"><br>
            </td>
            <td style=\"vertical-align: top;\"><br>
            </td>
            </tr>
            <tr>
                <td style=\"vertical-align: top;\"><b>Giacenza Effettiva</b><br>
                </td>
                <td style=\"vertical-align: top;\">Q.ta<br>
                </td>\n";
        $_giaeff = $_totcarico - $_qtascarico;
        $_result = "OK !";

        if ($_giaeff < 0)
        {
            $_result = "NO !";
        }

        printf("<td style=\"vertical-align: top;\"><input type=\"text\" size=\"10\" name=\"giaeff\" value=%s><br></td>", $_giaeff);
        printf("<td colspan=\"1\" rowspan=\"1\" style=\"vertical-align: top;\">Controllo =<input type=\"text\" size=\"3\" name=\"result\" value=%s><br></td>", $_result);

        echo "<tr><td style=\"vertical-align: top;\"><br></td></tr>";


        echo "<tr><td>&nbsp;</td><td align=center><input type=\"submit\" name=\"aggp2\" value=\"Aggiorna\"></td>";
        echo "<td align=center><input type=\"submit\" name=\"aggp2\" value=\"Annulla\"></td></tr></form>\n";

        echo "<tr><td colspan=\"5\"><br><br><hr style=\"width: 100%; height: 2px;\"></td></tr></table></div>\n";
    }
    echo "</body></html>\n";
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>