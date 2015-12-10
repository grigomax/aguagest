#!/bin/bash
#provo a prendermi una variabile
echo "Il Programma necessita di essere usato da amministratore"
echo "Programma che setta i permessi ai file mysql specialmente dopo un trasferimento"
echo "Ed inoltra la versione due permette di impostare anche la password del database"
echo "Bisogna passare ad amministratore.."


echo "fermo il server mysql"

service mysqld stop


#prendiamo l'iserimento di dove è la cartella mysql
echo "inserisci il percorso della cartella contenente gli archivi "
echo "Partendo dalla radice.. / inclusa"
echo "Esempio /var/www/archivi"
read directory

echo "la directory da modificare è $directory"

echo "confermi ?"
echo "scrivi si oppure no"
read risposta
if [ "$risposta" == "si" ] ; then
echo "Verifica directory..."
  # controllo se la directory esiste
  if [ -d $directory ]; then
    echo "Fatto. !"
    echo "La directory esiste"
    echo "procedo a cambiare tutti i permessi...."
    echo "Cambio nome..."
    chown mysql -R $directory
    echo ".. Fatto. !"
    echo "Procedo a cambiare il gruppo"
    chgrp mysql -R $directory
    echo ".. Fatto.. !"
    echo "Procedo a cambiare i permessi"
    chmod 755 -R $directory
    echo "... Fatto. !"
    echo "se Tutto ok riavvio il server mysql"
    service mysqld restart
    echo "ed ora varifichiamo se è status ok"
    service mysqld status
    echo "Speriamo sia tutto ok"
  else
    echo "la directory non esiste"
  fi

else
 echo "la tua risposta è No"
 exit
fi

echo "Ora la seconda parte del programma..."
echo "Settare la password di root per il database.. ?"
echo "scrivi si oppure no"
read risposta
if [ "$risposta" == "si" ] ; then
  echo "Ok immettere la password per utente root database.."
  echo "ATTENZIONE LA PASSWORD SARA' IN CHIARO"
  read passwd
  #verifico che non sia vuota..
  if [ "$passwd" != "" ] ; then
    echo "impostazione password database.."
    mysqladmin -u root password $passwd
    echo "impostazione password Effettuata"
  else
    echo "Nessun Carattere immesso.. procedura annullata.."
    exit
  fi
else
 echo "la tua risposta è No"
 exit
fi
exit




