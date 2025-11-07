<?php
// view_rooms.php - displays rooms fetched from DB
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hotel_db";

$conn = mysqli_connect($servername, $username, $password, $dbname);
if (!$conn) die("DB Connection failed: " . mysqli_connect_error());

$res = mysqli_query($conn, "SELECT * FROM rooms ORDER BY created_at DESC");
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Available Rooms</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <header><h1>Available Rooms</h1><nav><a href="index.html">Home</a> | <a href="book.html">Book</a></nav></header>
  <main class="container">
    <div class="room-list">
      <?php while($r = mysqli_fetch_assoc($res)): ?>
        <div class="room-card">
          <img src="<?php echo htmlspecialchars($r['image']); ?>" alt="<?php echo htmlspecialchars($r['type']); ?>">
          <h3><?php echo htmlspecialchars($r['type']); ?></h3>
          <p class="desc"><?php echo htmlspecialchars($r['description']); ?></p>
          <p>Capacity: <?php echo $r['capacity']; ?></p>
          <p>Price per night: ₹<?php echo $r['price']; ?></p>
          <p>Available: <?php echo $r['available']; ?></p>
          <a class="book-btn" href="book.html?roomid=<?php echo $r['id']; ?>&roomtype=<?php echo urlencode($r['type']); ?>&price=<?php echo $r['price']; ?>">Book Now</a>
        </div>
      <?php endwhile; ?>
    </div>
  </main>
</body>
</html>
<?php mysqli_close($conn); ?>
