<?php
// exports bookings table as XML
header('Content-Type: text/xml; charset=utf-8');

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hotel_db";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$xml = new SimpleXMLElement('<bookings/>');

$res = $conn->query("SELECT * FROM bookings ORDER BY created_at DESC");
while($row = $res->fetch_assoc()){
  $b = $xml->addChild('booking');
  foreach($row as $k => $v){
    $b->addChild($k, htmlspecialchars($v));
  }
}

echo $xml->asXML();
$conn->close();
?>
