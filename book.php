<?php
// book.php - store booking securely using prepared statements
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hotel_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$room_id = intval($_POST['room_id'] ?? 0);
$room_type = $_POST['room_type'] ?? '';
$checkin = $_POST['checkin'] ?? '';
$checkout = $_POST['checkout'] ?? '';
$price = floatval($_POST['price'] ?? 0.0);

// Basic server-side validation
if(empty($name) || empty($email) || empty($phone) || $room_id<=0 || empty($checkin) || empty($checkout)){
  die("Invalid input. Please go back and fill all required fields.");
}

// Calculate days
$d1 = new DateTime($checkin);
$d2 = new DateTime($checkout);
$interval = $d1->diff($d2);
$days = max(1, (int)$interval->days);
$total = $days * $price;

// Check room availability and update atomically
$conn->begin_transaction();

$stmt = $conn->prepare("SELECT available, type FROM rooms WHERE id = ? FOR UPDATE");
$stmt->bind_param("i", $room_id);
$stmt->execute();
$stmt->bind_result($available, $db_room_type);
if(!$stmt->fetch()){
  $stmt->close();
  $conn->rollback();
  die("Room not found.");
}
$stmt->close();

if($available <= 0){
  $conn->rollback();
  die("Selected room is not available.");
}

// decrement availability
$upd = $conn->prepare("UPDATE rooms SET available = available - 1 WHERE id = ?");
$upd->bind_param("i",$room_id);
$ok = $upd->execute();
$upd->close();

if(!$ok){
  $conn->rollback();
  die("Could not update room availability.");
}

// insert booking
$ins = $conn->prepare("INSERT INTO bookings (name,email,phone,room_id,room_type,checkin,checkout,total_days,total_amount) VALUES (?,?,?,?,?,?,?,?,?)");
$ins->bind_param("sssiissid",$name,$email,$phone,$room_id,$room_type,$checkin,$checkout,$days,$total);
$ins_ok = $ins->execute();
$ins->close();

if($ins_ok){
  $conn->commit();
  echo "<h2>Booking Confirmed!</h2>";
  echo "<p>Name: " . htmlspecialchars($name) . "</p>";
  echo "<p>Room: " . htmlspecialchars($room_type) . "</p>";
  echo "<p>Total Nights: {$days}</p>";
  echo "<p>Total Amount: ₹{$total}</p>";
  echo "<p><a href='index.html'>Back to Home</a></p>";
} else {
  $conn->rollback();
  echo "Error booking: " . $conn->error;
}

$conn->close();
?>
