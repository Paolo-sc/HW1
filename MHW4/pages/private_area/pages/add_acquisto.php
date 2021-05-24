<?php
    $dbconfig = [
        'host'     => '127.0.0.1',
        'name'     => 'progetto',
        'user'     => 'root',
        'password' => ''
    ];
    
    // Create connection
    $conn = mysqli_connect($dbconfig['host'], $dbconfig['user'], $dbconfig['password'], $dbconfig['name']);
    
    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }

    if(isset($_POST['submit'])){
        $id_cliente = mysqli_real_escape_string($conn,$_POST['cliente']);
        $id_articolo = mysqli_real_escape_string($conn,$_POST['articolo']);
        $id_fornitore = mysqli_real_escape_string($conn,$_POST['fornitore']);
        $quantita = mysqli_real_escape_string($conn,$_POST['quantita']);

        $sql = "CALL aggiungi_acquisto('$id_cliente','$id_articolo','$id_fornitore','$quantita')";

        mysqli_query($conn,$sql);

        $message = "Acquisto aggiunto correttamente";
        echo "<script type='text/javascript'>
        alert('acquisto aggiunto correttamente');
        window.location.href='acquisti.php';
        </script>";
    }

    $conn->close();
?>