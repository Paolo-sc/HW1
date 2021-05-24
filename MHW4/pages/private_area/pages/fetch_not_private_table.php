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

$sql = "SELECT * FROM cliente C JOIN cliente_impresa CP ON C.id_cliente = CP.id_impresa ORDER BY C.id_cliente";
$result = $conn->query($sql);

$postArray = array();

while($row = $result->fetch_assoc()){
    $postArray[]=array('id'=>$row['id_cliente'],'rag_soc'=>$row['rag_socc'],'phone'=>$row['telefono'],'address'=>$row['indirizzo'],'email'=>$row['email'],'p_iva'=>$row['p_ivac']);
}

echo json_encode($postArray);

$conn->close();
exit;
?>