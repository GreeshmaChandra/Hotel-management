<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hotel_db";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

$user = $_POST['username'] ?? '';
$pass = $_POST['password'] ?? '';

if(empty($user) || empty($pass)) die("Enter credentials.");

$stmt = $conn->prepare("SELECT id, password FROM admins WHERE username = ? LIMIT 1");
$stmt->bind_param("s", $user);
$stmt->execute();
$stmt->bind_result($id, $hash);
if($stmt->fetch()){
  $stmt->close();
  if(password_verify($pass, $hash)){
    $_SESSION['admin'] = $user;
    header('Location: admin_panel.php');
    exit;
  } else {
    echo "Invalid username/password. <a href='admin_login.html'>Try again</a>";
  }
} else {
  echo "Invalid username/password. <a href='admin_login.html'>Try again</a>";
}
$conn->close();
?>
