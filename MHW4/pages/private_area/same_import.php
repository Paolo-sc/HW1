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

$sql = "CALL articoli_stesso_importo()";
$result = $conn->query(($sql));

$postArray = array();

while($row = $result->fetch_assoc()){
    $postArray[]=array('id_articolo'=>$row['id_articolo'],'fornitore'=>$row['fornitoreArt']);
}

echo json_encode($postArray);

$conn->close();
exit;
?>