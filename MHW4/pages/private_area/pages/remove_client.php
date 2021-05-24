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
        $selection = $_POST['select_delete'];
        if($selection == 'cf'){
            $cf = mysqli_real_escape_string($conn,$_POST['value']);

            $sql = "DELETE FROM cliente WHERE id_cliente IN(SELECT id_cliente_privato FROM cliente_privato WHERE CF LIKE '$cf')"; 
        }
        if($selection == 'username'){
            $username = mysqli_real_escape_string($conn,$_POST['value']);

            $sql = "DELETE FROM cliente WHERE id_cliente IN(SELECT id_cliente_privato FROM cliente_privato WHERE username='$username')";
        }
        if($selection == 'rag_soc'){
            $rag_soc = mysqli_real_escape_string($conn,$_POST['value']);

            $sql = "DELETE FROM cliente WHERE id_cliente IN(SELECT id_impresa FROM cliente_impresa WHERE rag_socc='$rag_soc')";
        }
        if($selection == 'p_iva'){
            $p_iva = mysqli_real_escape_string($conn,$_POST['value']);

            $sql = "DELETE FROM cliente WHERE id_cliente IN(SELECT id_impresa FROM cliente_impresa WHERE p_ivac='$p_iva')"; 
        }

        mysqli_query($conn,$sql);

        if($conn->affected_rows){
            echo "<script type='text/javascript'>
            alert('Cliente rimosso correttamente');
            window.location.href='clienti.php';
            </script>";
        } 
        else{
            echo "<script type='text/javascript'>
            alert('Impossibile rimuovere il cliente verificare e riprovare');
            window.location.href='clienti.php';
            </script>";
        }
    }
    $conn->close();
?>