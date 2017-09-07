<?php 
include('config.php');
include('header.php');
include('menu.php');

//indicare la configurazione della pagina per l'inserimento dei farmaci nelle scorte
$add = FALSE;
//successo dell'insertimento nelle scorte
$success = FALSE;

if (!empty($_GET['id'])) {

	//Attivo l'inserimento
	$add = TRUE;

	//Controllo se ho un attivo un inserimento prodotto da un POST sul form di inserimento
	if(!empty($_GET['add']) && !empty($_POST)) {

	  try {	
		$dbh = new PDO($dbname, $username, $password);
		//$sql = 'UPDATE Scorte SET qta=:qta WHERE ditta=:ditta AND farmaco=:farmaco';
		$sql2 = 'INSERT INTO Scorte VALUES (:ditta, :farmaco, :qta)';

		//effettuo l'INSERT
		$stmt = $dbh->prepare($sql2);
		$stmt->bindParam(':ditta', $_GET['id']);
                $stmt->bindParam(':farmaco', $_POST['farmaco']);
                $stmt->bindParam(':qta', $_POST['fqta']);
                $stmt->execute();

		if ($stmt->errorCode() != '00000') {
                                  
		  echo "<b>Si e' verificato un errore:</b><br />";
                  echo "Codice errore: <em>" . $dbh->errorCode() . "</em><br />";
                  $errorInfos = $dbh->errorInfo();
                  echo "Codice errore SQLSTATE: <em>" . $errorInfos[0] . "</em><br />";
                  echo "Codice errore: <em>" . $errorInfos[1] . "</em><br />";
                  echo "Descrizione errore: <em>" . $errorInfos[2] . "</em>";

                  //è accaduto un errore        
                  $success = TRUE;
                }

		/*for ($j = 0; $j < count($_POST); $j++) {

			//inizializzo la variabile a 'vero', che verrà modificata nel caso in cui accada un errore
			$success = TRUE;
		
			if (!empty($_POST[$j])) {
				$sql = 'UPDATE Scorte SET qta=:qta WHERE ditta=:ditta AND farmaco=:farmaco';
				$stmt = $dbh->prepare($sql);
				$stmt->bindParam(':ditta', $_GET['id']);
                                $stmt->bindParam(':farmaco', $j);
                                $stmt->bindParam(':qta', $_POST[$j]);
			        $stmt->execute();
								
				if ($stmt->errorCode() == '00000') {
				
				} else {
                		  
				  echo "<b>Si e' verificato un errore:</b><br />";
		                  echo "Codice errore: <em>" . $dbh->errorCode() . "</em><br />";
		                  $errorInfos = $dbh->errorInfo();   
		                  echo "Codice errore SQLSTATE: <em>" . $errorInfos[0] . "</em><br />";
               			  echo "Codice errore: <em>" . $errorInfos[1] . "</em><br />";
                		  echo "Descrizione errore: <em>" . $errorInfos[2] . "</em>";

				  //è accaduto un errore	
				  $success = FALSE;
                		  }
			}
		}*/
	
		$dbh = null;
	  } catch(PDOException $e) {
                    echo $e->getMessage();
	    }

	  $add = FALSE;
	}
}

?>

<!-- Begin content -->
<div class="container">
	<ol class="breadcrumb">
          <li><a href="index.php">Home</a></li>
<?php     if (isset($_GET['id'])) echo '<li><a href="ditta.php?id='. $_GET['id'] .'">Ditta</a></li>'; ?>
	  <li class="active">Farmaci</li>
         </ol>


<?php	//Inseriamo nella pagina un avviso e abilitiamo il form per la raccolta della quantità di farmaci da inserire
	 echo $add ? '<form role="form" action="farmaci.php?id=' . $_GET['id'] . '&add=1" method="post">' : '';

	//Notifica dell'inserimento avvenuto correttamente
	 echo $success ? '<div class="alert alert-success">Inserimento eseguito con successo!</div>' : '';

?>
	<table class="table table-hover table-condensed">
		<thead>
		  <tr>
		    <th>Nome</th>
		    <th>Confezione</th>
		    <th>Dose</th>
		    <th>Unità di misura</th>
		    <th>Principio</th>
		    <th>Fabbrica</th>
<?php		    echo $add ? '<th>Quantita</th>' : ''; 
		    /*echo $add ? '<th>Quantita</th>
			         <th>Qta Attuale</th>' : '';*/ ?>
	 	  </tr>
		</thead>

		<tbody>
<?php		
		try {
			$dbh = new PDO($dbname, $username, $password);
			$sql = '';
			$resultSet;

			if (!$add) {
			  $sql = 'SELECT * FROM farmaco';
			  $resultSet = $dbh->query($sql);

			//modalità aggiungi farmaci alla scorta
			} else {
		          $sql = 'SELECT * FROM farmaco AS f INNER JOIN scorte AS s ON f.id=s.farmaco WHERE s.ditta=:id;';
			  $stmt = $dbh->prepare($sql);
		          $stmt->bindParam(':id', $_GET['id']);
		          $stmt->execute();
  		          $resultSet = $stmt->fetchAll();
			}

			// controlliamo se si e' verificato un errore
                        if ($dbh->errorCode() == '00000') {   // nessun errore

                             foreach ($resultSet as $row) {

                                echo '<tr>
				       <td>' . $row['nome']  . '</td>
				       <td>' . $row['confezione']  . '</td>
				       <td>' . $row['dose']  . '</td>
				       <td>' . $row['unita_misura']  . '</td>
				       <td>' . $row['principio']  . '</td>
				       <td>' . $row['fabbrica']  . '</td>';
				//scrivo la riga che permette le aggiunte alla scorta
				//echo $add ? '<td><input type="text" placeholder="0" name="' . $row['id']  . '"></td>' : '';
				echo $add ? '<td>' . $row['qta'] .'</td>' : '';
				echo '</tr>';
                             }

                        } else {
                                echo "<b>Si e' verificato un errore:</b><br />";
                                echo "Codice errore: <em>" . $dbh->errorCode() . "</em><br />";
                                $errorInfos = $dbh->errorInfo();
                                echo "Codice errore SQLSTATE: <em>" . $errorInfos[0] . "</em><br />";
                                echo "Codice errore: <em>" . $errorInfos[1] . "</em><br />";
                                echo "Descrizione errore: <em>" . $errorInfos[2] . "</em>";        
                        }

			$dbh = null;

		} catch(PDOException $e) {
		    echo $e->getMessage();
		}

?>	
		</tbody>
	</table>
<?php
	if ($add) {
		  
                try {
                	$dbh = new PDO($dbname, $username, $password);
			//La query ottiene solamente le righe farmaci che non sono presenti nelle scorte
			$q = '(SELECT id, nome, confezione, dose, unita_misura, principio, fabbrica FROM farmaco)
                              EXCEPT
			      (SELECT f.id, f.nome, f.confezione, f.dose, f.unita_misura, f.principio, f.fabbrica FROM farmaco AS f 
				INNER JOIN scorte AS s ON f.id=s.farmaco WHERE s.ditta = '. $_GET['id'] .')';
			$resultSet = $dbh->query($q);

			if (count($resultSet) != 0) {
			  
			   echo '<hr />
                                  <div class="row">
                                   <div class="col-md-10">
                                    <label for="farmaco">FARMACO</label>
                                    <select class="form-control" name="farmaco">';

	                   foreach ($resultSet as $row) {
	
        	             echo '<option value="'. $row['id'] .'">';
			     echo sprintf('%s - Confezione: %d - Dose: %d - Unità di misura: %s - Principio: %s - Fabbrica: %s', 
				$row['nome'], $row['confezione'], $row['dose'], $row['unita_misura'], $row['principio'], $row['fabbrica']);
			     echo '</option>';			    
			   }
			   $dbh = null;

			   echo ' </select>
                  		 </div>
                  		 <div class="col-md-2">
                    		  <label for="fqta">QTA</label>
                    		  <input type="text" class="form-control" maxlength="3" name="fqta">
                  		 </div>
                		</div><!-- .row -->
                		<br />
                		<button type="submit" class="btn btn-default">CONFERMA</button>';
			}

                  } catch (PDOException $e) {
			echo $e->getMessage();
                  }
	}
	echo '</form>';
?>
	
</div><!-- .container -->

<?php include('footer.php');?>
