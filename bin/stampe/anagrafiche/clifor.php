<?php
# la query e gia stata eseguita..
#mi resta solo la esposizione dei dati..

$result = $conn->query($query);

if ($conn->errorCode() != "00000")
{
    $_errore = $conn->errorInfo();
    echo $_errore['2'];
    //aggiungiamo la gestione scitta dell'errore..
    $_errori['descrizione'] = "Errore Query = $query - $_errore[2]";
    $_errori['files'] = "cli_for.php";
    scrittura_errori($_cosa, $_percorso, $_errori);
}

//cerco il numero di righe

$righe = $result->rowCount();

#echo $righe;
//inserisco il numero di righe per pagina
$rpp = 6;

$_pagine = $righe / $rpp;
//arrotondo per eccesso
$pagina = ceil($_pagine);

        base_html_stampa("chiudi", $_parametri);


$_parametri['data'] = date('d-m-Y');

for ($_pg = 1; $_pg <= $pagina; $_pg++)
{
    ?>
    <table border="0" cellspacing="0" cellpadding="0" align="center" width="95%">
        <thead>
            <tr>
                <th align="center" width="100%" colspan="6">
                    <?php
                    $_parametri['pg'] = $_pg;
                    $_parametri['pagina'] = $pagina;
                    intestazione_html($_cosa, $_percorso, $_parametri);
                    ?>

                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td align="left" width="100%">
                    <?php
// ciclo di estrazione dei dati
                    for ($_nr = 1; $_nr <= $rpp; $_nr++)
                    {
                        $dati3 = $result->fetch(PDO::FETCH_ASSOC);
                        #          foreach ($result->fetchrow() as $dati3);
                        #$dati3 = mysql_fetch_array($res2);
                        print <<< corpo

<table style="text-align: left; width: 95%;" border="0" cellpadding="0" cellspacing="0">
<tbody>
<tr>
<td valign="top" colspan="1"><font face="arial" size="1" valign="top"><i>Codice</i></font><br><font face="$fontdocsta" style="$fontdocsize">&nbsp;{$dati3['codice']}</td>
<td valign="top" colspan="3"><font face="arial" size="1" valign="top"><i>Ragione Sociale</i></font><br><font face="$fontdocsta" style="$fontdocsize">&nbsp;<b>{$dati3['ragsoc']}</b></td>
<td valign="top" colspan="3"><font face="arial" size="1" valign="top"><i>Estensione ragione sociale</i></font><br><font face="$fontdocsta" style="$fontdocsize">&nbsp;{$dati3['ragsoc2']}</td>
</tr>
<tr>
<td valign="top" colspan="3"><font face="arial" size="1" valign="top"><i>Indirizzo</i></font><br><font face="$fontdocsta" style="$fontdocsize">&nbsp;{$dati3['indirizzo']}</td>
<td valign="top" colspan="1"><font face="arial" size="1" valign="top"><i>Cap.</i></font><br><font face="$fontdocsta" style="$fontdocsize">&nbsp;{$dati3['cap']}</td>
<td valign="top" colspan="2"><font face="arial" size="1" valign="top"><i>Localit&agrave;</i></font><br><font face="$fontdocsta" style="$fontdocsize">&nbsp;{$dati3['citta']}</td>
<td valign="top" colspan="1"><font face="arial" size="1" valign="top"><i>Prov</i></font><br><font face="$fontdocsta" style="$fontdocsize">&nbsp;{$dati3['prov']}</td>
</tr>
<tr>
<td valign="top" colspan="1"><font face="arial" size="1" valign="top"><i>Tel.</i></font><br><font face="$fontdocsta" style="$fontdocsize">&nbsp;{$dati3['telefono']}</td>
<td valign="top" colspan="1"><font face="arial" size="1" valign="top"><i>Fax.</i></font><br><font face="$fontdocsta" style="$fontdocsize">&nbsp;{$dati3['fax']}</td>
<td valign="top" colspan="1"><font face="arial" size="1" valign="top"><i>Cell</i></font><br><font face="$fontdocsta" style="$fontdocsize">&nbsp;{$dati3['cell']}</td>
<td valign="top" colspan="2"><font face="arial" size="1" valign="top"><i>e-mail</i></font><br><font face="$fontdocsta" style="$fontdocsize">&nbsp;{$dati3['email']}</td>
<td valign="top" colspan="1"><font face="arial" size="1" valign="top"><i>C.F.</i></font><br><font face="$fontdocsta" style="$fontdocsize">&nbsp;{$dati3['codfisc']}</td>
<td valign="top" colspan="1"><font face="arial" size="1" valign="top"><i>P.iva</i></font><br><font face="$fontdocsta" style="$fontdocsize">&nbsp;{$dati3['piva']}</td>
</tr>
<tr>
<td valign="top" colspan="3"><font face="arial" size="1" valign="top"><i>Banca Appoggio</i></font><br><font face="$fontdocsta" style="$fontdocsize">&nbsp;{$dati3['banca']}</td>
<td valign="top" colspan="1"><font face="arial" size="1" valign="top"><i>Iban</i></font><br><font face="$fontdocsta" style="$fontdocsize">&nbsp;{$dati3['iban']}</td>
<td valign="top" colspan="1"><font face="arial" size="1" valign="top"><i>Abi</i></font><br><font face="$fontdocsta" style="$fontdocsize">&nbsp;{$dati3['abi']}</td>
<td valign="top" colspan="1"><font face="arial" size="1" valign="top"><i>Cab</i></font><br><font face="$fontdocsta" style="$fontdocsize">&nbsp;{$dati3['cab']}</td>
<td valign="top" colspan="1"><font face="arial" size="1" valign="top"><i>C/C</i></font><br><font face="$fontdocsta" style="$fontdocsize">&nbsp;{$dati3['cc']}</td>
</tr>
<tr>
<td valign="top" colspan="5"><font face="arial" size="1" valign="top"><i>Contatto</i></font><br><font face="$fontdocsta" style="$fontdocsize">&nbsp;{$dati3['contatto']}</td>
<td valign="top" colspan="2"><font face="arial" size="1" valign="top"><i>Agente</i></font><br><font face="$fontdocsta" style="$fontdocsize">&nbsp;{$dati3['codage']}</td>
</tr>
</tbody>
</table>
<HR>
corpo;
                    }
                    ?>
                </td></tr>
        </tbody>
    </table>

    <?php
}
?>
</body>
</html>