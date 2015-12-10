<?php
session_start();
/* 
 * Agua_gest programma gestionale by Grigolin Massimo
 * aguagest.sourceforge.net - massimo@mcetechnik.it
 * Programma distribuito secondo licenza GNU GPL
 */



$_nome = $_GET['nome_variabile_sessione'];
$_valore = $_GET['valore_variabile_sessione'];

echo $_nome;
echo $_valore;


$_SESSION[$_nome] = $_valore;


?>