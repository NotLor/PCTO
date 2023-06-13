<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

        //FUNZIONAMENTO CON FILE DI TESTO
        class Automobile{
            private $targa; //7 caratteri
            private $marca; //15 caratteri
            private $modello; //15 caratteri

            //costruttore
            public function __construct($targa, $marca, $modello){
                $this->targa = $targa;
                $this->marca = $marca;
                $this->modello = $modello;
            }

            //metodi get
            public function getTarga()
            {
                return $this->targa;
            }

            public function getMarca()
            {
                return $this->marca;
            }

            public function getModello()
            {
                return $this->modello;
            }

            //aggiunta file txt
            public function salvaSuFile(){
                //apertura file senza eliminare il contenuto precedente (a)
                $file = fopen("db.txt", "a");
                //scrittura nel file
                fwrite($file, $this->targa . " " . $this->marca . " " . $this->modello . "\n");
                //chiusura file
                fclose($file);
                echo "<script type='text/javascript'>alert('Automobile aggiunta correttamente!');</script>";
                echo "<script type='text/javascript'>window.location.href = 'index.php';</script>"; 
            }

        }

        //formattazione per file di testo
        function aggiungiSpazi($var, $dimF){
            //calcolo spazi da aggiungere
            $differenza = $dimF - strlen($var);
            //aggiunta spazi alla stringa
            for($i = 0; $i < $differenza; $i++){
                $var .= " ";
            }
            return $var; 
        }

        //eliminazione veicolo per targa
        function elimina($percorsoFile, $targa){
            $file = fopen($percorsoFile, "r");
            $fileTemp = fopen("temp.txt", "w");

            while(!feof($file)){
                $line = fgets($file);
                //var_dump(strpos($line, $targa));
                //var_dump($line);
                //var_dump($targa);
                if(strpos($line, $targa) == false){
                    fwrite($fileTemp, $line);
                }

            }

            fclose($file);
            fclose($fileTemp);
            //eliminazione file originale
            unlink($percorsoFile);
            //rinominazione file finale
            rename("temp.txt", $percorsoFile);
            echo "<script type='text/javascript'>alert('Automobile eliminata correttamente!');</script>";
            echo "<script type='text/javascript'>window.location.href = 'index.php';</script>";     
        }

        //ricerca veicolo per targa
        function ricerca($percorsoFile, $targa){
            $nomeFile = $percorsoFile;
            $linee = file($nomeFile); //legge il file e salva ogni riga in un array

            //informazione da ricercare
            $infoRicercata = $targa;

            foreach($linee as $linea){
                if(strpos($linea, $infoRicercata) !== false){
                    //la riga contiene la targa ricercata
                    echo "Trovata la seguente automobile: ";
                    return $linea;
                }
            }
            return "Nessun automobile trovata con la targa '$targa'";
        }

        //visualizzazione inventario
        function stampaTxt($percorsoFile){
            $file_path = $percorsoFile;
            //Leggi il contenuto del file
            $content = file_get_contents($file_path);
            //Sostituisci i caratteri di nuova riga con <br> e converti i caratteri speciali
            $formatted_content = nl2br(htmlspecialchars($content));
            //Stampa il contenuto nella pagina
            echo $formatted_content;
        }
        
    ?>


<!DOCTYPE html>
<html>

<head>
    <title>Autonoleggio</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <h1>AUTONOLEGGIO</h1>
    
    <!--AGGIUNTA VEICOLO-->
    <h2>Aggiungi Veicolo</h2>
    <form action="#" method="POST">
        <input type="text" name="targa" required placeholder="Inserire la targa"><br>
        <input type="text" name="marca" required placeholder="Inserire la marca"><br>
        <input type="text" name="modello" required placeholder="Inserire il modello"><br>

        <input type="submit" value="Aggiungi Veicolo" name="add">
        <br>
    </form>
    <?php
        if(isset($_POST["add"])){
            $targa = $_POST["targa"];
            $marca = str_replace(" ", "_", $_POST["marca"]);
            $modello = str_replace(" ", "_", $_POST["modello"]);
            $marcaF = aggiungiSpazi($marca, 15);
            $modelloF = aggiungiSpazi($modello, 15);

            $automobile = new Automobile($targa, $marcaF, $modelloF);
            $automobile->salvaSuFile();
        }
        ?>

    <!--ELIMINAZIONE VEICOLO-->
    <h2>Elimina Veicolo</h2>
    <form action="#" method="POST">
        <input type="text" name="elimina" required placeholder="Inserire la targa"><br>
        <input type="submit" value="Elimina Veicolo" name="delete">
    </form>
    <?php
        if(isset($_POST["delete"])){
            $targa = $_POST["elimina"];
            elimina("db.txt", $targa);
        }
        ?>
    
    <!--RICERCA VEICOLO PER TARGA-->
    <h2>Ricerca Veicolo per Targa</h2>
    <form action="#" method="POST">
        <input type="text" name="ricerca" required placeholder="Inserire la targa"><br>

        <input type="submit" value="Ricerca Veicolo" name="search">
    </form>
    <?php
        if(isset($_POST["search"])){
            $targa = $_POST["ricerca"];
            echo ricerca("db.txt", $targa);
        }
        ?>
    
    <!--STAMPA INVENTARIO-->
    <h2>Inventario</h2>
    <form action="#" method="POST">
        <input type="submit" value="Mostra Inventario" name="view">
    </form>

    <?php
        if(isset($_POST["view"])){
            stampaTxt("db.txt");
        }
        ?>
</body>

