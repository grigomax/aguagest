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
require_once $_percorso . "librerie/motore_anagrafiche.php";
require_once $_percorso . "librerie/stampe_pdf.php";

//carico la sessione con la connessione al database..
$conn = permessi_sessione("verifica_PDO", $_percorso);

//carichiamo la base delle pagine:
base_html_stampa("chiudi", $_parametri);



if ($_SESSION['user']['anagrafiche'] > "1")
{

// mi prendo il GET appena passato

    $_articolo = $_GET['articolo'];

    $V001 = "Scheda Articolo";
    $V002 = "Codice";
    $V003 = "Descrizione";
    $V004 = "U.M.";
    $V005 = "Listino";
    $V006 = "Disponibilit&agrave;";
    $V007 = "Acquista on-line uno sconto per te !";
    $V008 = "Aggiungi al Carrello ==> ";
    $V009 = "Dettagli articolo :";
    $V010 = "Torna indietro... ";
    $V011 = "Legenda Disponibilit&agrave; ";
    $V012 = "Attualmente Non Disponibile ";
    $V013 = "Pochi pezzi disponibili ";
    $V014 = "Buona ";
    $V015 = "Molto Buona ";
    $V016 = "La tua posizione ";
    $V017 = "Trovato articolo correlato";
    $V018 = "Visualizza Articolo";

    $dati = tabella_articoli("singola_prezzo", $_GET['articolo'], "1");

    if ($_GET['azione'] == "PDF")
    {

        //qui iniziamo a costruire la pagina direttamente in pdf..
        //creaiamo il file
        crea_file_pdf($_cosa, $_orientamento, "Scheda Articolo ".$dati['articolo']);

        crea_pagina_pdf();

        crea_intestazione_ditta_pdf("conlogo_cat", "Scheda Articolo ".$dati['articolo'], $_anno, $_pg, $pagina, $_parametri);

        //qui mi conviene comporla a mano..
        intesta_pagina("titolo", "Scheda Articolo" , $_parametri);

        intesta_pagina("sotto_titolo", "Scheda Articolo", $_parametri);

        corpo_pagina("scheda_articolo", $dati, $_parametri);

        if ($dati['artcorr'] != "")
        {

            $dati_corr = tabella_articoli("singola_prezzo", $dati['artcorr'], "1");
            corpo_pagina("articolo_correlato", $dati_corr, $_parametri);
        }


        if ($dati['artcorr_2'] != "")
        {

            $dati_corr = tabella_articoli("singola_prezzo", $dati['artcorr_2'], "1");

            corpo_pagina("articolo_correlato", $dati_corr, $_parametri);
        }

        corpo_pagina("calce_scheda", $dati, $_parametri);

        $_pdf = chiudi_files("Articolo_" . $dati[articolo], "../../..", "F");


        //prepariamo la maschera per scrivere
        maschera_invio_posta("singolo", $_percorso, $_pdf, $email2, $dati2['email2'], "Scheda", $_parametri);
    }
    else
    {
        echo "<title>Scheda articolo codice $_articolo</title>\n";
        echo "</head>\n";

        echo "<body>\n";

        $_parametri['intesta_immagine'] = "logocat.png";
        $_parametri['intestazione'] = "1";
        $_parametri['tabella'] = "Scheda Articolo";
        intestazione_html($_cosa, $_percorso, $_parametri);

        echo "<table border=\"0\" width=\"$PRINT_WIDTH.px\">\n";
        echo "<tr>\n";
        echo "<td align=\"right\" valign=\"top\" ><font face=\"$fontditta\" style=\"font-size: $fontdimditta" . "pt;\"><a href=\"scheda_art.php?articolo=$_GET[articolo]&azione=PDF\">Invia per E-mail</a></font></td></tr>\n";

        echo "</table><br>\n";


// passo le variabili unita e disponibilita
        $_unita = $dati['unita'];
// inizio calcolo giacenza
        
        echo "<table width=\"$PRINT_WIDTH.px\" border=\"0\" cellpadding=\"2\" cellspacing=\"1\">\n";
        echo "<tr style=\"font-family: Arial;\" align=\"center\">\n";
        echo "<td colspan=\"5\" rowspan=\"1\">\n";
        
        printf("<img src=\"../../../imm-art/%s\" width=\"200\" height=\"200\">", $dati['immagine']);
        if ($dati['immagine2'] != "")
        {
            printf("<img src=\"../../../imm-art/disegni/%s\" width=\"200\" height=\"200\">", $dati['immagine2']);
        }
        ?>
                </td>
            </tr>

            <tr>
                <td style="text-align: center; font-weight: bold; font-family: Arial; font-size: 12px; background-color: rgb(204, 204, 200);"><? echo $V002; ?></td>
                <td style="text-align: left; font-weight: bold; font-family: Arial; font-size: 12px; background-color: rgb(204, 204, 200);"><? echo $V003; ?></td>
                <td style="text-align: center; font-weight: bold; font-family: Arial; font-size: 12px; background-color: rgb(204, 204, 200);"><? echo $V004; ?></td>
                <td style="text-align: center; font-weight: bold; font-family: Arial; font-size: 12px; background-color: rgb(204, 204, 200);"><? echo $V005; ?></td>

            </tr>
            <tr>
                <td valign="middle" style="text-align: center; font-family: Arial; font-size: 12px; background-color: rgb(225, 225, 225);"><?php echo $dati['articolo']; ?></td>
                <td valign="middle" style="text-align: left; font-family: Arial; font-size: 12px; background-color: rgb(225, 225, 225);"><?php echo $dati['descrizione']; ?></td>
                <td valign="middle" style="text-align: center; font-family: Arial; font-size: 12px;background-color: rgb(225, 225, 225);"><?php echo $dati['unita']; ?></td>
                <td valign="middle" style="text-align: center; font-family: Arial; font-size: 12px; background-color: rgb(225, 225, 225);">
        <?php
        if ($dati['listino'] == "0.00")
        {
            echo "a richiesta";
        }
        else
        {
            echo "&euro; ";
            echo $dati['listino'];
        }
        echo "</td></tr>\n";

        echo "<tr><td colspan=\"4\"><hr></td></tr>\n";
#inizio verifica se esiste un articolo correlato.. se si lo faccio apparire altrimenti no..
        if ($dati['artcorr'] != "")
        {

            $dati_corr = tabella_articoli("singola_prezzo", $dati['artcorr'], "1");

            echo "<tr><td align=\"right\" colspan=\"5\" style=\"text-align: left; text-valign: middle; font-family: Arial; font-size: 12px; \">$V017</td></tr>";
            echo "<tr>\n";
            echo "<td valign=\"middle\" style=\"text-align: center; font-family: Arial; font-size: 12px;\"> $dati_corr[articolo] </td>\n";
            echo "<td valign=\"middle\" style=\"text-align: left; font-family: Arial; font-size: 12px;\">$dati_corr[descrizione]</td>\n";
            echo "<td valign=\"middle\" style=\"text-align: center; font-family: Arial; font-size: 12px;\">$dati_corr[unita]</td>\n";
            echo "<td valign=\"middle\" style=\"text-align: center; font-family: Arial; font-size: 12px;\">&euro; $dati_corr[listino]</td>\n";

            echo "</tr>\n";
        }

        if ($dati['artcorr_2'] != "")
        {

            $dati_corr = tabella_articoli("singola_prezzo", $dati['artcorr_2'], "1");

            echo "<tr><td align=\"right\" colspan=\"5\" style=\"text-align: left; text-valign: middle; font-family: Arial; font-size: 12px; \">$V017</td></tr>";
            echo "<tr>\n";
            echo "<td valign=\"middle\" style=\"text-align: center; font-family: Arial; font-size: 12px;\"> $dati_corr[articolo] </td>\n";
            echo "<td valign=\"middle\" style=\"text-align: left; font-family: Arial; font-size: 12px;\">$dati_corr[descrizione]</td>\n";
            echo "<td valign=\"middle\" style=\"text-align: center; font-family: Arial; font-size: 12px;\">$dati_corr[unita]</td>\n";
            echo "<td valign=\"middle\" style=\"text-align: center; font-family: Arial; font-size: 12px;\">&euro; $dati_corr[listino]</td>\n";

            echo "</tr>\n";
        }

        if ($dati['artcorr_3'] != "")
        {

            $dati_corr = tabella_articoli("singola_prezzo", $dati['artcorr_3'], "1");

            echo "<tr><td align=\"right\" colspan=\"5\" style=\"text-align: left; text-valign: middle; font-family: Arial; font-size: 12px;\">$V017</td></tr>";
            echo "<tr>\n";
            echo "<td valign=\"middle\" style=\"text-align: center; font-family: Arial; font-size: 12px;\"> $dati_corr[articolo] </td>\n";
            echo "<td valign=\"middle\" style=\"text-align: left; font-family: Arial; font-size: 12px;\">$dati_corr[descrizione]</td>\n";
            echo "<td valign=\"middle\" style=\"text-align: center; font-family: Arial; font-size: 12px;\">$dati_corr[unita]</td>\n";
            echo "<td valign=\"middle\" style=\"text-align: center; font-family: Arial; font-size: 12px;\">&euro; $dati_corr[listino]</td>\n";

            echo "</tr>\n";
        }
        ?>


            <tr style="font-family: Arial; font-size: 10px;" align="left">
                <td colspan="5" rowspan="1"><br>
                    <b><? echo $V009; ?></b>
        <?php echo "<br> Peso articolo in Kg " . $dati['pesoart'] . "<br><br>\n"; ?>
        <?php echo "" . $dati['descsito'] . "\n"; ?>
                </td>
            </tr>


        </table>
        </body>
        </html>
        <?php
    }
}
else
{
    permessi_sessione($_cosa, $_percorso);
}
?>