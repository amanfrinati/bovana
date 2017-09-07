<?php include('config.php'); ?>
<?php include('header.php'); ?>
<?php include('menu.php'); ?>



<!-- Begin page content -->
<div class="container">

 <ol class="breadcrumb">
   <li><a href="index.php">Home</a></li>
   <li class="active">Ditta</li>
 </ol>

  <div class="row">
    <div class="col-xs-12 col-md-8">
	
	<!-- Informazioni sulla ditta selezionata -->
	<h3>Informazioni sulla Ditta</h3>

<?php	$scorte = false;
	// Mostro informazioni sulla ditta
	if(!empty($_GET['id'])) {
	  try {

	  	$dbh = new PDO($dbname, $username, $password);
          	$sql = 'SELECT D.cod_az, D.indirizzo, D.localita, D.provincia, D.scorte, D.anno_attivazione, De.nome, P.nome, D.nome_ditta 
			FROM Ditta AS D INNER JOIN Proprietario AS De ON D.deterrente = De.id 
			INNER JOIN Proprietario AS P ON D.proprietario = P.id 
			WHERE D.id=:id;';

          	$stmt = $dbh->prepare($sql);
          	$stmt->bindParam(':id', $_GET['id']);
          	$stmt->execute();

          	if ($stmt->errorCode() === '00000') {

	    		$row = $stmt->fetch();
            		echo '<dl class="dl-horizontal">' .
                        '<dt>DETERRENTE</dt><dd>' . $row[6] . '</dd>' .
                        '<dt>PROPRIETARIO</dt><dd>' . $row[7] . '</dd>' .
			'<hr />' .
			'<dt>Nome</dt><dd>' . $row['nome_ditta'] . '</dd>' .
	    	 	'<dt>Codice Aziendale</dt><dd>' . $row['cod_az'] . '</dd>' .
                 	'<dt>Indirizzo</dt><dd>' . $row['indirizzo'] . ', ' . $row['localita'] . ', ' . $row['provincia'] . '</dd>' .
 	         	'<dt>Scorte</dt><dd>' . ($row['scorte'] == 't' ? 'SI' : 'NO') . '</dd>' .
                 	'<dt>Anno attivazione</dt><dd>' . $row['anno_attivazione'] . '</dd>' .
			'</dl>';
			$scorte = $row['scorte'];
		} else {
                         echo '<div class="alert alert-danger fade in">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">x</button>
                                <h4>Si è verificato un errore:</h4>
                                <p>Codice errore: <em>' . $dbh->errorCode() . "</em><br />";
                         $errorInfos = $dbh->errorInfo();
                         echo  "Codice errore SQLSTATE: <em>" . $errorInfos[0] . "</em><br />
                                Codice errore: <em>" . $errorInfos[1] . "</em><br />
                                Descrizione errore: <em>" . $errorInfos[2] . "</em>
                                </p>
                               </div>";
	    	 }

		// distruggiamo l'oggetto per chiudere la connessione alla base di dati
                $dbh = null;
	
	  } catch (PDOException $e) {
                echo $e->getMessage();
            }
	}
?>
    </div><!-- col-xs-12 col-md-8 -->
    <div class="col-xs-6 col-md-4">

	<h3>Operazioni</h3>
	<ul class="nav nav-pills nav-stacked" style="padding-top:20px;">
          <?php if($scorte) echo '<li><a href="farmaci.php?id=' . $_GET['id'] . '">AGGIUNGI FARMACI ALLA SCORTA</a></li>'; ?>
          <li><a href="capi.php?id=<?php echo $_GET['id']; ?>&mode=aggiungi">INSERISCI NUOVI CAPI DA ALLEVARE</a></li>
	</ul>

    </div>

  </div><!-- .row -->

	<!-- Menù secondario selezione -->
	  <!-- Nav tabs -->
          <ul class="nav nav-tabs" id="myTab">
            <li class="active"><a href="#capipresenti" data-toggle="tab">CAPI PRESENTI</a></li>
            <!--li><a href="#capitutti" data-toggle="tab">TUTTI I CAPI</a></li-->
            <li><a href="#scorte" data-toggle="tab">SCORTA FARMACI</a></li>
          </ul>

	  <!-- Tab panes -->
	  <div class="tab-content">

	    <!-- Tab CAPI PRESENTI -->
	    <div class="tab-pane fade in active" id="capipresenti"><br />



<?php	//Mostro i capi presenti attualmente in ditta
        if(!empty($_GET['id'])) {
          try {
	        // creiamo una nuova istanza della classe PDO
	        $dbh = new PDO($dbname, $username, $password);

                // variabile che contiene lo statement SQL da eseguire
		$sql = 'SELECT * FROM alleva AS a INNER JOIN capo AS c ON a.capo=c.marca
			WHERE a.ditta_ingresso=:id AND a.ditta_uscita IS NULL;';
                $stmt = $dbh->prepare($sql);
                $stmt->bindParam(':id', $_GET['id']);
                $stmt->execute();
		$resultSet = $stmt->fetchAll();
		
                if ($stmt->errorCode() === '00000') {

			if (count($resultSet) == 0) {

			  echo '<div class="alert alert-warning">
    				 <h4>Nessun dato nel database!</h4>
				</div>';
			} else {
			
			  echo '<table class="table table-bordered table-condensed table-striped">
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
				echo '<tr>' .
				      '<td>' . $row['marca'] . '</td>' .
                                      '<td>' . $row['tipo'] . '</td>' .
                                      '<td>' . $row['razza'] . '</td>' .
                                      '<td>' . $row['sesso'] . '</td>' .
                                      '<td>' . $row['data_nascita'] . '</td>' .
                                      '<td>' . $row['cert_sanitario'] . '</td>' .
                                      '<td>' . $row['madre'] . '</td>' .
				     '</tr>';
			  }
			  echo  '</tbody>' .
			       '</table>';
			}

		} else {

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

		// distruggo il collegamento
		$dbh = null;

	  } catch (PDOException $e) {
		echo $e->getMessage();
	    }
	}
?>
	    	</div><!-- tab-pane#capipresenti --> 

	    <!-- tab CAPI TUTTI -->
            <div class="tab-pane fade" id="capitutti"></div>

	    <!-- tab SCORTE -->
            <div class="tab-pane fade" id="scorte">
		
	    </div>

	    <!-- tab PRESCRIZIONI MEDICHE -->
            <!--div class="tab-pane fade" id="ricette"></div-->

	  </div><!-- .nav-tab -->

</div><!-- container -->

	<script>
	    var xmlhttp;
            if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
              xmlhttp=new XMLHttpRequest();
            }  else {
            // code for IE6, IE5
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
            }

          $('a[href="#capitutti"]').on('shown.bs.tab', function(e) {

	    xmlhttp.onreadystatechange=function() {
	    
              if (xmlhttp.readyState==4 && xmlhttp.status==200) {
	      
		document.getElementById("capitutti").innerHTML="<br />" + xmlhttp.responseText;
	      }
	    }
   
            xmlhttp.open("GET", "capi.php?id=<?php echo $_GET['id'] ?>&mode=tutti", true);
	    xmlhttp.send();
          });

          $('a[href="#capipresenti"]').on('shown.bs.tab', function(e) {
            
            xmlhttp.onreadystatechange=function() {
            
              if (xmlhttp.readyState==4 && xmlhttp.status==200) {
              
                document.getElementById("capipresenti").innerHTML="<br />" + xmlhttp.responseText;
              }
            } 
            xmlhttp.open("GET", "capi.php?id=<?php echo $_GET['id'] ?>&mode=presenti", true);
            xmlhttp.send();
          });

	  $('a[href="#scorte"]').on('shown.bs.tab', function(e) {
            
            xmlhttp.onreadystatechange=function() {
            
              if (xmlhttp.readyState==4 && xmlhttp.status==200) {
              
                document.getElementById("scorte").innerHTML="<br />" + xmlhttp.responseText;
              }
            } 
            xmlhttp.open("GET", "scorte.php?id=<?php echo $_GET['id'] ?>", true);
            xmlhttp.send();
          });


        </script>

<?php include('footer.php'); ?>
