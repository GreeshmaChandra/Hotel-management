<?php
session_start();
if(!isset($_SESSION['admin'])){ header('Location: admin_login.html'); exit; }

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "hotel_db";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) die("Connection failed: " . $conn->connect_error);

// Fetch bookings
$bookings = $conn->query("SELECT * FROM bookings ORDER BY created_at DESC");
// Fetch rooms
$rooms = $conn->query("SELECT * FROM rooms ORDER BY created_at DESC");
?>
<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <title>Admin Panel</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <header>
    <h1>Admin Dashboard</h1>
    <p>Welcome, <?php echo htmlspecialchars($_SESSION['admin']); ?> | <a href="admin_logout.php">Logout</a></p>
  </header>

  <main class="container">
    <section>
      <h2>Bookings</h2>
      <table class="table">
        <tr><th>ID</th><th>Name</th><th>Room</th><th>Checkin</th><th>Checkout</th><th>Amount</th></tr>
        <?php while($b = $bookings->fetch_assoc()): ?>
          <tr>
            <td><?php echo $b['id']; ?></td>
            <td><?php echo htmlspecialchars($b['name']); ?></td>
            <td><?php echo htmlspecialchars($b['room_type']); ?></td>
            <td><?php echo $b['checkin']; ?></td>
            <td><?php echo $b['checkout']; ?></td>
            <td>₹<?php echo $b['total_amount']; ?></td>
          </tr>
        <?php endwhile; ?>
      </table>
    </section>

    <section>
      <h2>Rooms</h2>
      <div class="room-list">
        <?php while($r = $rooms->fetch_assoc()): ?>
          <div class="admin-room">
            ID: <?php echo $r['room_code']; ?> <b><?php echo htmlspecialchars($r['type']); ?></b> - ₹<?php echo $r['price']; ?> - Avl: <?php echo $r['available']; ?>
          </div>
        <?php endwhile; ?>
      </div>

      <h3>Add New Room</h3>
      <form action="admin_add_room.php" method="post">
        <label>Room code: <input type="text" name="room_code" required></label>
        <label>Type: <input type="text" name="type" required></label>
        <label>Price: <input type="number" step="0.01" name="price" required></label>
        <label>Capacity: <input type="number" name="capacity" required></label>
        <label>Available: <input type="number" name="available" required></label>
        <label>Description: <input type="text" name="description"></label>
        <label>Image path: <input type="text" name="image" value="images/default.jpg"></label>
        <button type="submit">Add Room</button>
      </form>
    </section>
  </main>
</body>
</html>
<?php $conn->close(); ?>
