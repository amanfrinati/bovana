<?php
// nome della base di dati a cui connettersi, ad esempio usate il vostro nome utente se siete su dbstud
$dbname = 'manfrina';
// nome dell'utente con si effettua la connessione
$username = 'webdb';
// password dell'utente con si effettua la connessione
$password = 'webdb';

try {
    // creiamo una nuova istanza della classe PDO
    $dbh = new PDO("pgsql:dbname=$dbname", $username, $password);

    // stampiamo un messaggio che comunica l'avvenuta connessione alla base di dati
    echo "Connessione alla base di dati avvenuta con successo<br /><br />";

    // distruggiamo l'oggetto per chiudere la connessione alla base di dati
    $dbh = null;
    
} catch (PDOException $e) {
    echo $e->getMessage();
}
?>
