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

    if(isset($_POST ['submit'])){
        $rag_soc = mysqli_real_escape_string($conn,$_POST['rag_soc']);
        $p_iva = mysqli_real_escape_string($conn,$_POST['p_iva']);
        $number = mysqli_real_escape_string($conn,$_POST['number']);
        $street = mysqli_real_escape_string($conn,$_POST['street'].', '.$_POST['city'].', '.$_POST['cap']);
        $email = mysqli_real_escape_string($conn,$_POST['email']);
        $sql = "SELECT MAX(id_cliente) FROM cliente";
        $result = $conn->query($sql);
        $result = $result->fetch_array();
        $result = intval($result[0]);
        $result += 1;
        $id = mysqli_real_escape_string($conn,$result);

        $sql = "INSERT INTO cliente (id_cliente,telefono,indirizzo,email) VALUES ('$id','$number','$street','$email')";
        mysqli_query($conn,$sql);
        $sql = "INSERT INTO cliente_impresa (id_impresa,p_ivac,rag_socc) VALUES ('$id','$p_iva','$rag_soc')";
        mysqli_query($conn,$sql);
        $message = "Utente aggiunto correttamente";
        echo "<script type='text/javascript'>
        alert('$message');
        window.location.href='clienti.php';
        </script>";
    }

    $conn->close();
?>