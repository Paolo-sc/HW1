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
header('Content-Type: application/json');

$sql = "SELECT * FROM cliente C JOIN cliente_privato CP ON C.id_cliente = CP.id_cliente_privato ORDER BY C.id_cliente";
$result = $conn->query($sql);

$postArray = array();

while($row = $result->fetch_assoc()){
    $postArray[]=array('id'=>$row['id_cliente'],'name'=>$row['nome'],'surname'=>$row['cognome'],'cf'=>$row['CF'],'phone'=>$row['telefono'],'address'=>$row['indirizzo'],'username'=>$row['username'],'email'=>$row['email']);
}

echo json_encode($postArray);

$conn->close();
exit;
?>