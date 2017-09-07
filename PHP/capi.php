<?php

include('config.php');

/* id rappresenta la ditta su cui operare
   mode è l'operazione da svolgere:
	- tutti: mostra tutti i capi che sono stati allevati dalla ditta
	- presenti: mostra i capi attualmente allevati dalla ditta
	- aggiungi: inserisce altri capi da allevare

*/

if (!empty($_GET['id']) && !empty($_GET['mode'])) {

  switch ($_GET['mode']) {

//----- Fornisce in uscita la tabella con i capi presenti/tutti in base alla modalità scelta ----------------------------------------------
	case 'presenti':
	case 'tutti':
	
	    $risposta = '';

	    try {
		$dbh = new PDO($dbname, $username, $password);
		
		$sql = 'SELECT * FROM alleva AS a INNER JOIN capo AS c ON a.capo=c.marca';

		if ($_GET['mode'] == 'tutti')
			$sql .= ' WHERE a.ditta_ingresso=:id';
		else if ($_GET['mode'] == 'presenti')
			$sql .= ' WHERE a.ditta_ingresso=:id AND a.ditta_uscita IS NULL';

                $stmt = $dbh->prepare($sql);
                $stmt->bindParam(':id', $_GET['id']);
                $stmt->execute();
                $resultSet = $stmt->fetchAll();

                if ($stmt->errorCode() === '00000') {

                        if (count($resultSet) == 0) {

                          $risposta = '<div class="alert alert-warning"><h4>Nessun dato nel database!</h4></div>';

                        } else {

               		  $risposta = '<table class="table table-bordered table-condensed table-striped">
                                 <thead><tr>
                                  <th>MARCA</th>
                                  <th>TIPO</th>
                                  <th>RAZZA</th>
                                  <th>SEX</th>
                                  <th>DATA NASCITA</th>
                                  <th>CERT. SANIT.</th>
                                  <th>MADRE</th>
                                 </tr></thead>
                                <tbody>';
                          // Iteriamo sui risultati della query
                          foreach ($resultSet as $row) {
                                $risposta .= '<tr>' .
                                      '<td>' . $row['marca'] . '</td>' .
                                      '<td>' . $row['tipo'] . '</td>' .
                                      '<td>' . $row['razza'] . '</td>' .
                                      '<td>' . $row['sesso'] . '</td>' .
                                      '<td>' . $row['data_nascita'] . '</td>' .
                                      '<td>' . $row['cert_sanitario'] . '</td>' .
                                      '<td>' . $row['madre'] . '</td>' .
                                     '</tr>';
			  }

                          $risposta .=  '</tbody></table>';
                        }

		} else {

                        $risposta =  "<b>Si e' verificato un errore:</b><br />";
                        $risposta .= "Codice errore: <em>" . $dbh->errorCode() . "</em><br />";
                        $errorInfos = $dbh->errorInfo();
                        /*
                          Elementi di error Info
                          0   SQLSTATE error code (a five characters alphanumeric identifier defined in the ANSI SQL standard).
                          1   Driver-specific error code.
                          2   Driver-specific error message.
                        */
                        $risposta .= "Codice errore SQLSTATE: <em>" . $errorInfos[0] . "</em><br />";
                        $risposta .= "Codice errore: <em>" . $errorInfos[1] . "</em><br />";
                        $risposta .= "Descrizione errore: <em>" . $errorInfos[2] . "</em>";
                  }

                // distruggiamo l'oggetto per chiudere la connessione alla base di dati
                $dbh = null;

	    } catch (PDOException $e) {
            	echo $e->getMessage();
	      }
			
	    echo $risposta;

	break; // tutti-presenti
	

//-------------------------- Inserisco nuovi capi alla ditta -----------------------------------------------------------------------------
	case 'aggiungi':

	include('header.php');
	include('menu.php');

	echo '<div class="container">
		 <ol class="breadcrumb">
                   <li><a href="index.php">Home</a></li>
                   <li><a href="ditta.php?id='. $_GET['id']  .'">Ditta</a></li>
                   <li class="active">Capi</li>
                </ol>';

	//I campi che desidero non siano NULL sono MARCA, RAZZA, MOTIVO INGRESSO, DATA INGRESSO, TRASPORTATORE, DITTA INGRESSO
	if (!empty($_POST) && !empty($_POST['marca'])) {
		// Nell'ipotesi che ci siano più capi nella textara, divido le marche dei capi
		//$capi = explode(" ",  $_POST['marca']);
		
		try {
			$sql = "INSERT INTO Capo VALUES (:marca, :sesso, :tipo, :nascita, :madre, :razza, :cert)";
	                $dbh = new PDO($dbname, $username, $password);
	                $stmt = $dbh->prepare($sql);
			$stmt->bindParam(':marca', trim($_POST['marca']));
			$stmt->bindParam(':tipo', $_POST['tipo']);
			$stmt->bindParam(':sesso', $_POST['sesso']);
			$stmt->bindParam(':nascita', trim($_POST['data_nascita']));
			$stmt->bindParam(':madre', trim($_POST['madre']) == '' ? NULL : trim($_POST['madre']) );
			$stmt->bindParam(':cert', trim($_POST['cert_sanitario']));
			$stmt->bindParam(':razza', trim($_POST['razza']));
			
	                $stmt->execute();			
			
                        if ($stmt->errorCode() == '00000') {

                                echo '<div class="alert alert-success">'.
					'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>'.
					'Inserimento del capo <strong>'. trim($_POST['marca']) .'</strong> avvenuto con successo!'.
				      '</div>';
				$sql = 'INSERT INTO Alleva VALUES
					 (default, :motivo_ingresso, :data_ingresso, :ditta_ingresso, :trasportatore, :marca, NULL, NULL, NULL)';
				$dbh = new PDO($dbname, $username, $password);
	                        $stmt = $dbh->prepare($sql);
        	                $stmt->bindParam(':motivo_ingresso', $_POST['motivo_ingresso']);
                	        $stmt->bindParam(':ditta_ingresso', $_GET['id']);
	                        $stmt->bindParam(':trasportatore', $_POST['trasportatore']);
        	                $stmt->bindParam(':data_ingresso', $_POST['data_ingresso']);
        	                $stmt->bindParam(':marca', $_POST['marca']);
            		        $stmt->execute();

				if ($stmt->errorCode() == '00000') {

					echo '<div class="alert alert-success">'.
						'<button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>'.
						'Inserimento del capo <strong>'. trim($_POST['marca']) .
						 '</strong> in allevamento alla ditta avvenuto con successo!'.
					     '</div>';
				} else {

	                                echo "<b>Si e' verificato un errore:</b><br />";
        	                        echo "Codice errore: <em>" . $dbh->errorCode() . "</em><br />";
                	                $errorInfos = $dbh->errorInfo();
                        	        echo "Codice errore SQLSTATE: <em>" . $errorInfos[0] . "</em><br />";
                                	echo "Codice errore: <em>" . $errorInfos[1] . "</em><br />";
	                                echo "Descrizione errore: <em>" . $errorInfos[2] . "</em>";
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

        	 } catch (PDOException $e) {
                	  echo $e->getMessage();
            	 }

	}
?>	
		<h3>Inserisci nuovo capo da allevare</h3><!-- nella ditta $_GET['id'] -->
		<form role="form" class="form-horizontal" action="capi.php?id=<?= $_GET['id'] ?>&mode=<?= $_GET['mode'] ?>" method="post">

			<div class="form-group">
			  <label for="marca" class="col-sm-2 control-label">MARCA</label>
			  <div class="col-sm-10">
			    <input type="text" class="form-control" name="marca" maxlength=20>
			    <!--textarea rows="4" cols="50" class="form-control" name="marca"></textarea-->
			    <!--span class="help-block">Inserisci la <strong>MARCA</strong> del capo. Puoi inserirne più di una separandole con uno spazio.</span-->
			  </div>
			</div>

			<div class="form-group">
                         <label for="sesso" class="col-sm-2 control-label">SESSO</label>
                          <div class="col-sm-10">
                            <select class="form-control" name="sesso">
	                      <option value="M">M</option>
			      <option value="F">F</option>
                            </select>
                          </div>
                        </div>

                        <div class="form-group">                        
                          <label for="data_nascita" class="col-sm-2 control-label">DATA DI NASCITA</label>
                          <div class="col-sm-10">
                            <input type="text" class="form-control" placeholder="YYYY-MM-DD" name="data_nascita" value="
<?php                       date_default_timezone_set('Europe/Rome');
                            echo date('Y-m-d', time());  ?>
                                "><!-- data_nascita -->
                            <span class="help-block">Inserisci la <strong>DATA</strong> nel formato <strong>YYYY-MM-DD</strong>.</span>
                          </div>
                        </div>

                        <div class="form-group">
                         <label for="tipo" class="col-sm-2 control-label">TIPO</label>
                          <div class="col-sm-10">
                            <select class="form-control" name="tipo">
                              <option value="Bovino">Bovino</option>
                              <option value="Suino">Suino</option>
			      <option value="Caprino">Caprino</option> 
                            </select>
                          </div>
                        </div>

			<div class="form-group">
                          <label for="cert_sanitario" class="col-sm-2 control-label">CERT. SANITARIO</label>
                          <div class="col-sm-10">
                            <input type="text" class="form-control" name="cert_sanitario">
                          </div>
                        </div>

                        <div class="form-group">
                         <label for="razza" class="col-sm-2 control-label">RAZZA</label>
                          <div class="col-sm-10">
                            <select class="form-control" name="razza">
<?php                             try {
                                        $dbh = new PDO($dbname, $username, $password);
                                        foreach ($dbh->query('SELECT codice, nome, sesso FROM razza') as $row) {
                                                echo '<option value="'. $row['codice'] .'">'. $row['nome'] .' - '. $row['sesso'] .'</option>';

                                        }
                                        $dbh = null;
                                } catch (PDOException $e) {
                                        echo $e->getMessage();
                                }
?>
                            </select>
                          </div>
                        </div>

			<div class="form-group">
                          <label for="madre" class="col-sm-2 control-label">MADRE</label>
                          <div class="col-sm-10">
                            <input type="text" class="form-control" name="madre">
                          </div>
                        </div>

			<hr />

			<div class="form-group">			
	  		  <label for="data_ingresso" class="col-sm-2 control-label">DATA DI INGRESSO</label>
                          <div class="col-sm-10">
                            <input type="text" class="form-control" id="data_ingresso" placeholder="YYYY-MM-DD" name="data_ingresso" value="
<?php			    date_default_timezone_set('Europe/Rome');
			    echo date('Y-m-d', time());  ?>
				"><!-- data_ingresso -->
                            <span class="help-block">Inserisci la <strong>DATA</strong> nel formato <strong>YYYY-MM-DD</strong>.</span>
                          </div>
			</div>

                        <div class="form-group">
                         <label for="trasportatore" class="col-sm-2 control-label">TRASPORTATORE</label>
                          <div class="col-sm-10">
                            <select class="form-control" id="trasportatore" name="trasportatore">
<?php                           //Riempio il select dei trasportatori
                                try {
                                        $dbh = new PDO($dbname, $username, $password);
                                        foreach ($dbh->query('SELECT id, nome FROM trasportatore') as $row) {
                                                echo '<option value="'. $row['id'] .'">'. $row['nome'] .'</option>';
                                        
                                        }
                                        $dbh = null;
                                } catch (PDOException $e) {
                                        echo $e->getMessage();
             			}
?>
                            </select>
			  </div>
                        </div>

                        <div class="form-group">
                          <label for="motivo_ingresso" class="col-sm-2 control-label">MOTIVO DI INGRESSO</label>
                          <div class="col-sm-10">
                            <select class="form-control" id="motivo_ingresso" name="motivo_ingresso">
<?php				//Riempio il select dei motivi
				try {
			                $dbh = new PDO($dbname, $username, $password);
        			        foreach ($dbh->query('SELECT * FROM motivo') as $row) {
						echo '<option value="'. $row['codice'] .'">'. $row['descrizione'] .'</option>';
			                
			                }
					$dbh = null;
				} catch (PDOException $e) {
			                echo $e->getMessage();
				}
?>
                            </select>
			  </div>
                        </div>

			<button type="submit" class="btn btn-default">INSERISCI</button>
		</form>
	</div><!-- .container -->

<?php	include('footer.php');
	break; // aggiungi

  } //end switch
} //end if?>
