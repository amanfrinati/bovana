<?php 

include ('config.php');

if (!empty($_GET['id'])) {

$r = '';

try {
	$dbh = new PDO($dbname, $username, $password);

	$sql = 'SELECT * FROM scorte AS s INNER JOIN farmaco AS f ON s.farmaco=f.id '.
               'WHERE s.ditta=:id';
        $stmt = $dbh->prepare($sql);
        $stmt->bindParam(':id', $_GET['id']);
        $stmt->execute();
	$resultSet = $stmt->fetchAll();

	if ($stmt->errorCode() == '00000') {
	
	        if (count($resultSet) == 0) {

                         $r = '<div class="alert alert-warning"><h4>Nessun dato nel database!</h4></div>';
                } else {
                
			 $r .= '<table class="table table-bordered table-condensed table-striped">
                                 <thead><tr>
                                  <th>FARMACO</th>
                                  <th>QUANTITA\'</th>
                                 </tr></thead>
                                 <tbody>';
                          // Iteriamo sui risultati della query
                          foreach ($resultSet as $row) {
                            $r .= '<tr>' .
                                   '<td>' . $row['nome'] . '</td>' .
                                   '<td>' . $row['qta'] . '</td>' .
                                  '</tr>';
			  }
                          $r .= '</tbody></table>';
                       }

	} else {
		$r = "<b>Si e' verificato un errore:</b><br />";
                $r .= "Codice errore: <em>" . $dbh->errorCode() . "</em><br />";
                $errorInfos = $dbh->errorInfo();
                /*
                  Elementi di error Info
                  0   SQLSTATE error code (a five characters alphanumeric identifier defined in the ANSI SQL standard).
                  1   Driver-specific error code.
                  2   Driver-specific error message.
                */
                 $r .= "Codice errore SQLSTATE: <em>" . $errorInfos[0] . "</em><br />";
                 $r .= "Codice errore: <em>" . $errorInfos[1] . "</em><br />";
                 $r .= "Descrizione errore: <em>" . $errorInfos[2] . "</em>";
                }

	// distruggo il collegamento
        $dbh = null;

} catch (PDOException $e) {
	 echo $e->getMessage();
  }

echo $r;

}

?>
