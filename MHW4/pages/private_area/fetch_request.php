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
header('Content-Type: application/json');

$id = mysqli_real_escape_string($conn,$_GET['id']);

$sql = "CALL inf_richieste($id)";
$result = $conn->query($sql) or die($conn->error);

$postArray = array();

while($row = $result->fetch_assoc()){
    $postArray[]=array('intervento'=>$row['interventoR']);
}

echo json_encode($postArray);

$conn->close();
exit;
?>