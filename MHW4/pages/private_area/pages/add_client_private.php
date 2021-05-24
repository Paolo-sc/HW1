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
        $name = mysqli_real_escape_string($conn,$_POST['name']);
        $surname = mysqli_real_escape_string($conn,$_POST['surname']);
        $cf = mysqli_real_escape_string($conn,$_POST['cf']);
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
        $sql = "INSERT INTO cliente_privato (id_cliente_privato,nome,cognome,CF) VALUES ('$id','$name','$surname','$cf')";
        mysqli_query($conn,$sql);
        $message = "Utente aggiunto correttamente";
        echo "<script type='text/javascript'>
        alert('$message');
        window.location.href='clienti.php';
        </script>";
    }
    $conn->close();
?>