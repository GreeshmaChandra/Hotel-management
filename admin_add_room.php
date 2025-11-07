<?php
session_start();
if(!isset($_SESSION['admin'])){ header('Location: admin_login.html'); exit; }

$room_code = $_POST['room_code'] ?? '';
$type = $_POST['type'] ?? '';
$price = floatval($_POST['price'] ?? 0);
$capacity = intval($_POST['capacity'] ?? 1);
$available = intval($_POST['available'] ?? 0);
$description = $_POST['description'] ?? '';
$image = $_POST['image'] ?? 'images/default.jpg';

if(empty($room_code) || empty($type) || $price <= 0){
  die("Invalid input.");
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hotel_db";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$stmt = $conn->prepare("INSERT INTO rooms (room_code,type,price,capacity,description,image,available) VALUES (?,?,?,?,?,?,?)");
$stmt->bind_param("ssdisis",$room_code,$type,$price,$capacity,$description,$image,$available);
$ok = $stmt->execute();
$stmt->close();
$conn->close();

if($ok) header('Location: admin_panel.php');
else echo "Error adding room.";
?>
