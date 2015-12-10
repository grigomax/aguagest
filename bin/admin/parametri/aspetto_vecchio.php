<?php

include "../../../setting/vars_aspetto.php";
?>

<link rel="stylesheet" href="../../css/globale.css" type="text/css">


<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
  <meta content="text/html; charset=ISO-8859-1" http-equiv="content-type">
  <title>parametri base</title>
</head>
<body>
<div style="text-align: center;"><span style="font-weight: bold;">Parametri generali CSS di aspetto programma e documenti</span><br>
<br>
Attenzione non si possono inserire nei campi di scrittura<br>
NE parole accentate NE apostrofi e TANTOMENO virgolette.<br>
<br>
La non osservanza potrebbe compromettere l'uso del programma.<br>
<br>
<form action="salvavars_aspetto.php" method="POST">
<table style="width: 80%; text-align: left; margin-left: auto; margin-right: auto;" border="0" cellpadding="1" cellspacing="1">
  <tbody>
    <tr>
      <td colspan="2" rowspan="1" align="center" valign="top"><span style="font-weight: bold;">Immagini per logo aziandale</span></td>
    </tr>
    <?php
    echo "<tr>\n";
    echo "<td style=\"width: 60%;\" align=\"center\" valign=\"top\">Selezionare logo azienda esteso dimensioni 193mm x 30mm per intestazione documenti</td>\n";
    echo "<td style=\"width: 40%; text-align: left;\" valign=\"top\"><select name=\"logog\">\n";
    echo "<option value=\"$logog\">$logog</option>";
    echo "<option value=\"\"></option>";
    exec("ls ../../../setting/loghiazienda/ ", $resrAr);
    while (list($key, $val) = each($resrAr))
    {
	@$val = ereg_replace(".jpg", ".jpg", $val);
	echo "<option value=\"$val\">$val\n";
    }
    echo "</select>\n";
    echo "</tr>\n";
    echo "<tr>\n";
    echo "<td style=\"width: 60%;\" align=\"center\" valign=\"top\">Selezionare logo azienda dimensioni 193mm x 20mm per il catalogo</td>\n";
    echo "<td style=\"width: 40%; text-align: left;\" valign=\"top\"><select name=\"logom\">\n";
    echo "<option value=\"$logom\">$logom</option>";
    echo "<option value=\"\"></option>";
    exec("ls ../../../setting/loghiazienda/ ", $resrAr);
    while (list($key, $val) = each($resrAr))
    {
	@$val = ereg_replace(".jpg", ".jpg", $val);
	echo "<option value=\"$val\">$val\n";
    }
    echo "</select>\n";
    echo "</tr>\n";
    echo "<tr>\n";
    echo "<td style=\"width: 60%;\" align=\"center\" valign=\"top\">Selezionare logo azienda piccolo dimensioni 80mm x 20mm per erichette</td>\n";
    echo "<td style=\"width: 40%; text-align: left;\" valign=\"top\"><select name=\"logop\">\n";
    echo "<option value=\"$logop\">$logop</option>";
    echo "<option value=\"\"></option>";
    exec("ls ../../../setting/loghiazienda/ ", $resrAr);
    while (list($key, $val) = each($resrAr))
    {
	@$val = ereg_replace(".jpg", ".jpg", $val);
	echo "<option value=\"$val\">$val\n";
    }
    echo "</select>\n";
    echo "</tr>\n";
    ?>
  <tr><td colspan="2"><hr></td></tr>
    <tr>
      <td colspan="2" rowspan="1" style="width: 241px;" align="center" valign="top"><span style="font-weight: bold;">Font di stampa predefiniti </span></td>
    </tr>
    <tr>
      <td style="width: 241px;" align="center" valign="top">&nbsp;Larghezza pagina in %</td>
      <td style="width: 400x; text-align: left;" valign="top"><input type="text" size="70" name="WIDTH" value="<?php echo $PRINT_WIDTH; ?>"></td>
    </tr>
    <tr>
      <td style="width: 241px;" align="center" valign="top">Grandezza font spazi vuoti</td>
      <td style="width: 400x; text-align: left;" valign="top"><input type="text" size="70" name="FONT_BACK" value="<?php echo $FONT_BACK; ?>"></td>
    </tr>
    <tr>
      <td style="width: 241px;" align="center" valign="top">&nbsp;font Intestazione ditta</td>
      <td style="width: 400x; text-align: left;" valign="top"><input type="text" size="70" name="fontditta" value="<?php echo $fontditta; ?>"></td>
    </tr>
    <tr>
      <td style="width: 241px;" align="center" valign="top">Grandezza font</td>
      <td style="width: 400x; text-align: left;" valign="top"><input type="text" size="70" name="fontdimditta" value="<?php echo $fontdimditta; ?>"></td>
    </tr>
    <tr>
      <td style="width: 241px;" align="center" valign="top">Font Intestazione cliente</td>
      <td style="width: 400x; text-align: left;" valign="top"><input type="text" size="70" name="fontintestazione" value="<?php echo $fontintestazione; ?>"></td>
    </tr>
    <tr>
      <td style="width: 241px;" align="center" valign="top">Grandezza font</td>
      <td style="width: 400x; text-align: left;" valign="top"><input type="text" size="70" name="fontdimintestazione" value="<?php echo $fontdimintestazione; ?>"></td>
    </tr>
    <tr>
      <td style="width: 241px;" align="center" valign="top">Font stampa testa e calce documunti</td>
      <td style="width: 400x; text-align: left;" valign="top"><input type="text" size="70" name="fontdocsta" value="<?php echo $fontdocsta; ?>"></td>
    </tr>
    <tr>
      <td style="width: 241px;" align="center" valign="top">grandezza font</td>
      <td style="width: 400x; text-align: left;" valign="top"><input type="text" size="70" name="fontdocsize" value="<?php echo $fontdocsize; ?>"></td>
    </tr>
    <tr>
      <td style="width: 241px;" align="center" valign="top">Font stampa corpo documenti</td>
      <td style="width: 400x; text-align: left;" valign="top"><input type="text" size="70" name="fontcorpodocsta" value="<?php echo $fontcorpodocsta; ?>"></td>
    </tr>
    <tr>
      <td style="width: 241px;" align="center" valign="top">Grandezza font</td>
      <td style="width: 400x; text-align: left;" valign="top"><input type="text" size="70" name="fontcorpodocsize" value="<?php echo $fontcorpodocsize; ?>"></td>
    </tr>

     <tr>
      <td colspan="2" rowspan="1" align="center" valign="top"><span style="font-weight: bold;">Impostazioni predefinite per la stampa documenti</span></td>
    </tr>
    <tr>
      <td style="width: 241px;" align="center" valign="top">Stampa prezzi nei ducumenti ?</td>
       <td style="width: 400x; text-align: left;" valign="top"><input type="text" size="70" name="ST_PREZZI" value="<?php echo $ST_PREZZI; ?>"></td>
    </tr>
    <tr>
      <td style="width: 241px;" align="center" valign="top">Stampa data e ora nei documenti consegna ?</td>
      <td style="width: 400x; text-align: left;" valign="top"><input type="text" size="70" name="ST_DATA" value="<?php echo $ST_DATA; ?>"></td>
    </tr>
    <tr>
      <td align="center" valign="top">Stampa logo intestazione nei documenti ?</td>
      <td style="width: 400x; text-align: left;" valign="top"><input type="text" size="70" name="ST_LOGO" value="<?php echo $ST_LOGO; ?>"></td>
    </tr>

        <tr>
      <td colspan=2 align=center><input type="submit" name="azione" value="Modifica !"></td></tr>
    </tr>
  </tbody>
</table>
</div>
</body>
</html>
