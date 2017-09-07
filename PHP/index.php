<?php include('config.php'); ?>
<?php include('header.php'); ?>
<?php include('menu.php');

$result = '';
if (isset($_GET['nuovaditta']) && $_GET['nuovaditta'] == 1 && isset($_POST)) {

//print_r($_POST);
                try {
                        $sql = "INSERT INTO Ditta VALUE (default, :cod_az, :nome, :indirizzo, :localita, :prov, :scorte, :propr, :det, :asl, :anno)";
                        $dbh = new PDO($dbname, $username, $password);
                        $stmt = $dbh->prepare($sql);
                        $stmt->bindParam(':cod_az', trim($_POST['cod_az']));
                        $stmt->bindParam(':nome', $_POST['nome']);
                        $stmt->bindParam(':anno', $_POST['anno_attivazione'] == "" ? 0 : $_POST['anno_attivazione']);
                        $stmt->bindParam(':localita', trim($_POST['localita']));
                        $stmt->bindParam(':indirizzo', trim($_POST['indirizzo']));
                        $stmt->bindParam(':prov', trim($_POST['provincia']));
                        $stmt->bindParam(':det', trim($_POST['deterrente']));
                        $stmt->bindParam(':propr', trim($_POST['proprietario']));
                        $stmt->bindParam(':asl', trim($_POST['asl']));
								$scorte = false;
                        if (isset($_POST['scorte']) && $_POST['scorte'] == "Yes") $scorte = true; $stmt->bindParam(':scorte', $scorte);
                        $stmt->execute();

			if ($stmt->errorCode() == '00000') {

                                $result = '<div class="alert alert-success fade in">
				       	    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
					    Inserimento della ditta <strong>'. trim($_POST['nome']) .'</strong> avvenuto con successo!
				           </div>';
                        } else {
				$result = '<div class="alert alert-danger fade in">
					   <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
					   <h4>Si è verificato un errore:</h4>
                                	    <p>Codice errore: <em>' . $dbh->errorCode() . "</em><br />";
                                $errorInfos = $dbh->errorInfo();
                                $result .=  "Codice errore SQLSTATE: <em>" . $errorInfos[0] . "</em><br />
                                	     Codice errore: <em>" . $errorInfos[1] . "</em><br />
                                	     Descrizione errore: <em>" . $errorInfos[2] . "</em>
					    </p>
				     	   </div>";
                        }

                        $dbh = null;

                } catch (PDOException $e) {
                          echo $e->getMessage();
                }
}  ?>

        <!-- Begin page content -->
	<div class="container">

		<ol class="breadcrumb">
		  <li class="active">Home</li>
		</ol>

            	<div class="page-header">
                	<h1>Gestione Anagrafe Bovina</h1>
                </div>

		<h5>Seleziona la ditta di cui gestire i capi oppure creane una nuova</h5>
		<?php echo $result; ?>
		<br />

		<div class="row">
		  <div class="col-xs-6 col-md-4">
<?php                   try {
                            // creiamo una nuova istanza della classe PDO
                            $dbh = new PDO($dbname, $username, $password);

                            // variabile che contiene lo statement SQL da eseguire
                            $sql = 'SELECT DISTINCT nome_ditta, id FROM Ditta ORDER BY nome_ditta;';
                            // eseguiamo l'interrogazione
                            $resultSet = $dbh->query($sql);

                            // controlliamo se si e' verificato un errore
                            if ($dbh->errorCode() === '00000') {   // nessun errore
                                // iteriamo sulle entry del result set restituito dalla funzione "query"
                                foreach ($resultSet as $row) {
                                    // stampiamo il nome
                                    echo '<a class="list-group-item" href="ditta.php?id='. $row['id'] . '">' . $row['nome_ditta'] . '</a>';
                                }
                            } else {
                                // nel caso in cui si sia verificato un errore, stampiamo le
                                // informazioni sull'errore mediante le funzioni 
                                // errorCode() ed errorInfo()
                                echo "<b>Si e' verificato un errore:</b><br />";
                                echo "Codice errore: <em>" . $dbh->errorCode() . "</em><br />";
                                $errorInfos = $dbh->errorInfo();
                                /*
                                 Elementi di error Info
                                    0   SQLSTATE error code (a five characters alphanumeric identifier defined in the ANSI SQL standard).
                                    1   Driver-specific error code.
                                    2   Driver-specific error message.
                                */
                                echo "Codice errore SQLSTATE: <em>" . $errorInfos[0] . "</em><br />";
                                echo "Codice errore: <em>" . $errorInfos[1] . "</em><br />";
                                echo "Descrizione errore: <em>" . $errorInfos[2] . "</em>";
                                
                            }

                            // distruggiamo l'oggetto per chiudere la connessione alla base di dati
                            $dbh = null;
                        } catch (PDOException $e) {
                            echo $e->getMessage();
                        }
?>
		  </div><!--col-xs-6 col-md-4 -->

                  <div class="col-xs-6 col-md-8">
			<form role="form" class="form-horizontal" method="post" action="index.php?nuovaditta=1">
			  <div class="form-group">
			    <label for="cod_az" class="col-sm-3 control-label">Cod. Aziendale</label>
			    <div class="col-md-5">
			      <input type="text" maxlength="10" class="form-control" id="cod_az" name="cod_az">
			    </div>
			  </div>
			  <div class="form-group">
			    <label for="nome" class="col-sm-3 control-label">Nome</label>
			    <div class="col-md-5">
			      <input type="text" maxlength="50" class="form-control" name="nome" id="nome">
			    </div>
			  </div>
			  <div class="form-group">
			    <label class="col-sm-3 control-label" for="anno_attivazione">Anno Attivazione</label>
			    <div class="col-md-5">
			      <input type="text" maxlength="4" name="anno_attivazione" class="form-control">
			    </div>
			  </div>
			  <div class="form-group">
                            <label for="indirizzo" class="col-sm-3 control-label">Indirizzo</label>
                            <div class="col-sm-5">
                              <input type="text" class="form-control" maxlength="100" name="indirizzo">
                            </div>
                          </div>
                          <div class="form-group">
                            <label for="localita" class="col-sm-3 control-label">Località</label>
                            <div class="col-sm-5">
                              <input type="text" class="form-control" maxlength="50" name="localita">
                            </div>
                          </div>
                          <div class="form-group">
                            <label for="provincia" class="col-sm-3 control-label">Provincia</label>
                            <div class="col-sm-5">
                              <input type="text" class="form-control" maxlength="30" name="provincia">
                            </div>
                          </div>
			  <div class="form-group">
                            <label for="scorte" class="col-sm-3 control-label">Scorte</label>
                            <div class="col-sm-5">
                              <input type="checkbox" name="scorte" value="Yes" />
                            </div>
                          </div>

			  <div class="form-group">
                          <label for="deterrente" class="col-sm-3 control-label">Deterrente</label>
                           <div class="col-sm-6">
                            <select class="form-control" name="deterrente">
<?php                             try {
                                        $dbh = new PDO($dbname, $username, $password);
                                        foreach ($dbh->query('SELECT id, nome FROM proprietario') as $row) {
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
                          <label for="proprietario" class="col-sm-3 control-label">Proprietario</label>
                           <div class="col-sm-6">
                            <select class="form-control" name="proprietario">
<?php                             try {
                                        $dbh = new PDO($dbname, $username, $password);
                                        foreach ($dbh->query('SELECT id, nome FROM proprietario') as $row) {
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
                          <label for="asl" class="col-sm-3 control-label">ASL</label>
                           <div class="col-sm-6">
                            <select class="form-control" name="asl">
<?php                             try {
                                        $dbh = new PDO($dbname, $username, $password);
                                        foreach ($dbh->query('SELECT numero, nome FROM asl') as $row) {
                                                echo '<option value="'. $row['numero'] .'">'. $row['numero'] .' - '. $row['nome'] .'</option>';

                                        }
                                        $dbh = null;
                                } catch (PDOException $e) {
                                        echo $e->getMessage();
                                }
?>
                            </select>
                           </div>
                          </div>

			  <div class="row" style="text-align:center">
			    <button type="submit" class="btn btn-primary">AGGIUNGI NUOVA DITTA</button>
			  </div>

			</form>
                  </div>
		</div><!-- .row -->
	</div><!-- .container -->

<?php include('footer.php'); ?>
