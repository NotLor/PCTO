<?php
    //CONNESSIONE E CONTROLLO ERRORI
    include 'connection.php';
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    //CARICAMENTO TABELLA MODELLI IN UN ARRAY
    $sql = "SELECT * FROM modelli";
    $result = mysqli_query($conn, $sql);

    $modelli = array();
    while($row = $result->fetch_assoc()){
        $modelli[] = $row;
    }
    
    ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Autosalone</title>
</head>
<body>
    <div class="interfaccia">
        <h1>GESTIONE AUTOSALONE</h1>



        <h2>AGGIUNGI VEICOLO</h2>
        <form action="#" method="GET" id="add">
            <input type="text" name="targa" required placeholder="inserire la targa"><br>
            <select name="marca" form="add" id="selectMarca" required>
                <option disabled selected value>selezionare una marca</option>
            <?php
                $sql = "SELECT * FROM marche";
                $result = mysqli_query($conn, $sql);

                if($result->num_rows > 0){
                    while($row = $result->fetch_assoc()){
                        echo "<option value=" . $row["id"] .">" . $row["marca"] . "</option>";
                    }
                }
                ?>
            </select><br>
            <select name="modello" form="add" id="selectModel" required>
                <option disabled selected value>selezionare un modello</option>
            </select>

            <?php
                 $cleanArray = array();
                 foreach ($modelli as $element) {
                     $cleanElement = mb_convert_encoding($element, 'UTF-8', 'UTF-8');
                     $cleanArray[] = $cleanElement;
                 }
                 $str = json_encode($cleanArray, true);
                 if ($str === false) {
                     $jsonError = json_last_error();
                     switch ($jsonError) {
                         case JSON_ERROR_NONE:
                             echo "Nessun errore JSON rilevato.";
                             break;
                         case JSON_ERROR_DEPTH:
                             echo "Errore JSON: superato il limite di profondità massima.";
                             break;
                         case JSON_ERROR_STATE_MISMATCH:
                             echo "Errore JSON: mismatch dei modi o underflow.";
                             break;
                         case JSON_ERROR_CTRL_CHAR:
                             echo "Errore JSON: carattere di controllo imprevisto trovato.";
                             break;
                         case JSON_ERROR_SYNTAX:
                             echo "Errore JSON: errore di sintassi.";
                             break;
                         case JSON_ERROR_UTF8:
                             echo "Errore JSON: carattere UTF-8 malformato, codifica errata.";
                             break;
                         case JSON_ERROR_RECURSION:
                             echo "Errore JSON: un valore fa riferimento a sé stesso ricorsivamente.";
                             break;
                         case JSON_ERROR_INF_OR_NAN:
                             echo "Errore JSON: uno o più valori INF o NAN sono stati forniti.";
                             break;
                         case JSON_ERROR_UNSUPPORTED_TYPE:
                             echo "Errore JSON: tipo di dati non supportato.";
                             break;
                         case JSON_ERROR_INVALID_PROPERTY_NAME:
                             echo "Errore JSON: nome della proprietà non valido.";
                             break;
                         case JSON_ERROR_UTF16:
                             echo "Errore JSON: carattere UTF-16 malformato.";
                             break;
                         default:
                             echo "Errore JSON: errore sconosciuto.";
                             break;
                     }
                 } else {
                     // La codifica JSON è avvenuta correttamente
                     // echo $str;
                 }
                ?>
            <br>
            <input type="number" name="nPosti" required placeholder="inserire il numero di posti"><br>

            <input type="submit" value="Aggiungi Veicolo" name="add" class="btn">
        </form>
        <?php
            //AGGIUNTA

            if(isset($_GET["add"])){
                $targa = $_GET["targa"];
                //controllo targa
                $targa = strtoupper($targa); //riformatta tutto in maiuscolo
                $targa = str_replace(" ", "", $targa); //elimina eventuali spazi
                $pattern = "/^[A-Z]{2}\d{3}[A-Z]{2}$/"; //pattern targa voluto
                //verifica input
                if(preg_match($pattern, $targa)){
                    $marca = $_GET["marca"];
                    $modello = $_GET["modello"];
                    $posti = $_GET["nPosti"];
                    //aggiunta nel database
                    $sql = "INSERT INTO car (targa, marca, modello, posti, deleted) VALUES ('$targa', '$marca', '$modello', '$posti', '0')";
                    //check
                    if ($conn->query($sql) === TRUE) {
                        echo "<p>Veicolo inserito con successo</p>";
                    } else {
                        echo "Errore nell'inserimento del veicolo: ";
                    }
                }else echo "<p>Veicolo non inserito! Inserire una targa validao</p>";
                
                
            }
            ?>

        <!--
        <h2>ELIMINA VEICOLO</h2>
        <form action="#" method="GET">
            <input type="text" name="elimina" required placeholder="inserire la targa"><br>

            <input type="submit" value="Elimina Veicolo" name="delete" class="btn">
        </form>
        <?php
            /*
            if(isset($_GET["delete"])){
                $targa = $_GET["elimina"];
                //ricerca
                $sql = "UPDATE car SET deleted = '1' WHERE targa = '$targa'";

                if (mysqli_query($conn, $sql)) {

                echo "Veicoli rimossi correttamente";

                } else {

                echo "Errore nell'aggiornamento del record: " . mysqli_error($conn);

                }  
            }
            
            ?>
        
        
        <h2>RICERCA VEICOLO</h2>
        <form action="#" method="GET">
            <input type="text" name="cerca" required placeholder="inserire la targa"><br>

            <input type="submit" value="Ricerca Veicolo" name="search" class="btn">
        </form>
        <?php
            if(isset($_GET["search"])){
                $targa = $_GET["cerca"];
                //cerca nel database
                $sql = "SELECT * FROM car WHERE deleted = '0' and targa = '$targa'";
                $result = mysqli_query($conn, $sql);

                if (mysqli_num_rows($result) > 0) {

                    while ($row = mysqli_fetch_assoc($result)) {
                        echo "ID: " . $row["id"] . " - Marca: " . $row["marca"] . " Modello: " . $row["modello"] . " Numero Posti: " . $row["posti"] . "<br>";
                    }
                
                } else {
                
                    echo "Nessun risultato trovato";
                
                }  
            }*/
            ?>
        -->
        <h2>INVENTARIO</h2>
        <form action="#" method="GET">
            <input type="submit" value="Mostra Inventario" name="show" class="btn"><br>
            <input type="submit" value="Nascondi Inventario" name="hide" class="btn"><br>
            <?php
                if(isset($_GET["hide"])){
                    echo "<script>window.location.href='index.php'</script>";
                }
            ?>
            <input type="text1" name="barraRicerca" placeholder="ricerca...">
            <input type="submit" value="Ricerca" name="searchFromTable" class="btn1">

        <?php
            //RICERCA NELLA TABELLA

            if(isset($_GET["searchFromTable"])){
                $info = $_GET["barraRicerca"];
                
                $sql1 = "SELECT * FROM marche where marca LIKE '%$info%'";
                $result = mysqli_query($conn, $sql1);
                if(mysqli_num_rows($result) > 0){
                    $marca1 = $result->fetch_assoc();
                    
                    $marca1 = $marca1["id"];
                }else $marca1 = -1;

                $sql = "SELECT * FROM car where (targa LIKE '%$info%' or modello LIKE '%$info%' or posti = '$info' or marca = '$marca1') and deleted = 0";
                $result = mysqli_query($conn, $sql);
                if(mysqli_num_rows($result) > 0){
                    echo "<table>";
                        echo "<thead>";
                            echo "<tr>";
                                echo "<th></th>";
                                echo "<th><p>TARGA</p></th>";
                                echo "<th><p>MARCA</p></th>";
                                echo "<th><p>MODELLO</p></th>";
                                echo "<th><p>NUMERO POSTI</p></th>";
                            echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";
                        foreach ($result as $r) {
                            //traduzione marca
                            $marca = $r["marca"];
                            $sql = "SELECT * FROM marche where id = $marca";
                    
                            $result2 = mysqli_query($conn, $sql);
                            $r2 = $result2->fetch_assoc();

                            //traduzione modello
                            $modello = $r["modello"];
                            $sql1 = "SELECT * FROM modelli where id = $modello";
                    
                            $result3 = mysqli_query($conn, $sql1);
                            $r3 = $result3->fetch_assoc();

                            echo "<tr>";
                                echo "<td><input type='checkbox' class='ui-checkbox' name='check[]'  value= " . $r["id"] . "></td>";
                                echo "<td>" . $r["targa"] . "</td>";
                                echo "<td>" . $r2["marca"] . "</td>";
                                echo "<td>" . $r3["modello"] . "</td>";
                                echo "<td>" . $r["posti"]. "</td>";
                            echo "</tr>";
                        }
                        echo "</tbody>";
                    echo "</table>";
                    echo"<input type='submit' value='Elimina Veicoli Selezionati' name='remove_selectioned' class='btn'><br>";
                }else{
                    echo "<p>Nessun risultato trovato nel database</p>";
                }
            }

            //MOSTRA TABELLA

            if(isset($_GET["show"])){
                $sql = "SELECT * FROM car WHERE deleted = '0'";
                $result = mysqli_query($conn, $sql);
                if($result->num_rows > 0){
                    echo "<table>";
                        echo "<thead>";
                            echo "<tr>";
                                echo "<th></th>";
                                echo "<th><p>TARGA</p></th>";
                                echo "<th><p>MARCA</p></th>";
                                echo "<th><p>MODELLO</p></th>";
                                echo "<th><p>NUMERO POSTI</p></th>";
                            echo "</tr>";
                        echo "</thead>";
                        echo "<tbody>";
                        foreach ($result as $r) {
                            //traduzione marca
                            $marca = $r["marca"];
                            $sql = "SELECT * FROM marche where id = $marca";
                    
                            $result2 = mysqli_query($conn, $sql);
                            $r2 = $result2->fetch_assoc();
                            //traduzione modello
                            $modello = $r["modello"];
                            $sql1 = "SELECT * FROM modelli where id = $modello";
                    
                            $result3 = mysqli_query($conn, $sql1);
                            $r3 = $result3->fetch_assoc();
                            echo "<tr>";
                                echo "<td><input type='checkbox' class='ui-checkbox' name='check[]'  value= " . $r["id"] . "></td>";
                                echo "<td>" . $r["targa"] . "</td>";
                                echo "<td>" . $r2["marca"] . "</td>";
                                echo "<td>" . $r3["modello"] . "</td>";
                                echo "<td>" . $r["posti"]. "</td>";
                            echo "</tr>";
                        }
                        echo "</tbody>";
                    echo "</table>";
                    echo"<input type='submit' value='Elimina Veicoli Selezionati' name='remove_selectioned' class='btn'><br>";    
                }else echo "<br><p>Database vuoto</p>";
            }

            //RIMUOVI VEICOLI SELEZIONATI
            
            if(isset($_GET['remove_selectioned'])){
                $idElimina = $_GET['check'];

                if(!(empty($idElimina))){
                    foreach($idElimina as $id){
                        $sql = "UPDATE car SET deleted = 1 WHERE id = $id";
    
                        if (mysqli_query($conn, $sql)) {
    
                        echo "<p>Veicolo selezionato rimosso correttamente</p>";
    
                        } else {
    
                        echo "Errore nell'aggiornamento del record: " . mysqli_error($conn);
    
                        }  
                    }
                }else{
                    echo "<script>window.location.href='index.php'</script>";
                }
                
            }

            echo "</form>";
            ?>
    </div>

    <script>
        
        var modelli = <?php echo $str; ?>;
        var selectMarca = document.getElementById("selectMarca");
        selectMarca.onchange = function() {
            //console.log(selectMarca.value);
            var selectModel = document.getElementById("selectModel");
            selectModel.innerHTML = "";
            var option = document.createElement("option");
            option.disabled = true;
            option.selected = true;
            option.value = "";
            selectModel.appendChild(option);
            for (var i = 0; i < modelli.length; i++) {
                if (modelli[i].marca === selectMarca.value) {
                    var option = document.createElement("option");
                    option.value = modelli[i].id;
                    option.innerHTML = modelli[i].modello;
                    selectModel.appendChild(option);
                }
            }
        }
                
    </script>

    <script src="main.js"></script>    
</body>
</html>

<?php
    $conn->close();
    ?>