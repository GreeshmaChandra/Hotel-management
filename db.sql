-- Create DB and tables for Smart Hotel Reservation (Version B)
CREATE DATABASE IF NOT EXISTS hotel_db DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE hotel_db;

-- rooms table
CREATE TABLE IF NOT EXISTS rooms (
  id INT AUTO_INCREMENT PRIMARY KEY,
  room_code VARCHAR(20) NOT NULL UNIQUE,
  type VARCHAR(100) NOT NULL,
  price DECIMAL(10,2) NOT NULL,
  capacity INT NOT NULL,
  description TEXT,
  image VARCHAR(255),
  available INT NOT NULL DEFAULT 0,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- bookings table
CREATE TABLE IF NOT EXISTS bookings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(120) NOT NULL,
  phone VARCHAR(30) NOT NULL,
  room_id INT NOT NULL,
  room_type VARCHAR(100) NOT NULL,
  checkin DATE NOT NULL,
  checkout DATE NOT NULL,
  total_days INT NOT NULL,
  total_amount DECIMAL(10,2) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE CASCADE
);

-- admins table (bcrypt-hashed password for 'password123' inserted)
CREATE TABLE IF NOT EXISTS admins (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) UNIQUE NOT NULL,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO admins (username, password) VALUES
('admin', '$2b$12$KZobyAyfpWgqXuyjpX4OjuEM6P2zWnWW4o7r3lqHzZRghOHerTjIW');

-- Insert sample rooms
INSERT INTO rooms (room_code, type, price, capacity, description, image, available) VALUES
('R101','Deluxe AC',3500.00,2,'Spacious room with king bed, AC, minibar.','images/deluxe.jpg',5),
('R102','Standard Non-AC',1800.00,2,'Cozy room with comfortable bedding.','images/standard.jpg',8),
('R103','Suite',5500.00,4,'Luxury suite with living area and bathtub.','images/suite.jpg',2);
