<?php
//Listini articolo..
// questa selezione mi permette di avere il numero di pagine ed il numero di
//righe in anticipo
if ($res2 = mysql_query($query, $conn))
{
//cerco il numero di righe
    $righe = mysql_num_rows($res2);
//echo $righe;
//inserisco il numero di righe per pagina
    $rpp = 52;

    $_pagine = $righe / $rpp;
//arrotondo per eccesso
    $pagina = ceil($_pagine);
}

for ($_pg = 1; $_pg <= $pagina; $_pg++)
{
    ?>
    <html>
        <head>
            <title>Disponibilita magazzino</title>
        </head>
        <body>
            <table border="0" align="center" width="90%">
                <tr><td colspan="2" align="center">
                        <img src="../../../setting/loghiazienda/intestazione.jpg" width="600">
                    </td></tr>
                <tr>
                    <td colspan="2"	bgcolor="#ffFFFF" valign="top" align="center">
                        <font face="arial" size="4"><br>Disponibilita magazzino</b></font>
                    </td>
                </tr>
            </table>
            <br>
            <table border="1" cellspacing="0" cellpadding="0" align="center" width="600">
                <tr>
                    <td bgcolor="#FFFFFF" valign="top" width="50" align="center"><font face="arial" size="2">Codice</td>
                    <td bgcolor="#FFFFFF" valign="top" width="550" align="left"><font face="arial" size="2">Descrizione</td>
                    <td bgcolor="#FFFFFF" valign="top" width="70" align="center"><font face="arial" size="2">Listino</td>
                    <td bgcolor="#FFFFFF" valign="top" width="30" align="center"><font face="arial" size="2">Um</td>
                    <td bgcolor="#FFFFFF" valign="top" width="70" align="center"><font face="arial" size="2">Disponibilita</td>
                </tr>
                <?php
// ciclo di estrazione dei dati
                for ($_nr = 1; $_nr <= $rpp; $_nr++)
                {
                    $dati3 = mysql_fetch_array($res2);
                    $_listino = $dati3['listino'];
                    $_listino = number_format(($_listino - (($_listino * $_sconto) / 100)), $dec);
// eliminazione della scritta vuoto dalla stampa
                    if ($_listino == "0.00" or null)
                    {
                        $_listino = "&nbsp;";
                    }
                    printf("<tr><td bgcolor=\"#FFFFFF\" align=\"center\" width=\"50\"><font face=\"arial\" size=\"2\">%s&nbsp;</td>", $dati3['articolo']);
                    printf("<td bgcolor=\"#FFFFFF\" align=\"left\" width=\"550\"><font face=\"arial\" size=\"2\">%s</td>", $dati3['descrizione']);
                    printf("<td width=\"70\" align=\"center\"><font face=\"arial\" size=\"2\">%s</td>", $_listino);
                    printf("<td width=\"30\" bgcolor=\"#FFFFFF\" align=\"center\"><font face=\"arial\" size=\"2\">%s</td>", $dati3['unita']);
                    printf("<td width=\"70\" align=\"center\"><font face=\"arial\" size=\"2\">%s</td>", $dati3['disponibilita']);
                    printf("</tr>");
                }
                ?>

                <table border="1" align="center" width="600"><br>
                    <tr>
                        <td width="30%" bgcolor="#FFFFFF" align="left"><font face="arial" size="1"><i>Pagina </i><? echo $_pg;?> di <? echo $pagina; ?></font></td>
                        <td width="30%" bgcolor="#FFFFFF" align="center"><font face="arial" size="1"> Data <? echo date("d / m / Y"); ?> </font></td>
                        <td width="30%" bgcolor="#FFFFFF" align="right"><font face="arial" size="1"><i>Pagina </i><? echo $_pg;?> di <? echo $pagina; ?></font></td>
                    </tr>
                </table>
                <br>
                <br>
                <?php
            }
            ?>
            </body>
            </html>
