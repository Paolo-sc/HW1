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
// Check connection
$id = mysqli_real_escape_string($conn,$_POST['id']);

$sql = "CALL inf_cliente_privato($id)";

$result = $conn->query($sql);

$row = $result->fetch_row();

$id= $row[0];
$number = $row[1];
$address = $row[2];
$email = $row[3];
$name = $row[5];
$surname = $row[6];
$cf = $row[7];
$username = $row[8];

echo "<script type='text/javascript'>
        alert('Id: $id\\nNome:$name\\nCognome:$surname\\nCodice Fiscale:$cf\\nEmail: $email\\nIndirizzo: $address\\nTelefono: $number\\nUsername: $username');
        window.location.href='private_area.php';
        </script>";


$conn->close();
exit;
?>