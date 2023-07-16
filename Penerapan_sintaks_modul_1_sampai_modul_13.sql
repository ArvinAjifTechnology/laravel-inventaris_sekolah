-- DDL
DROP DATABASE inventaris_sekolah;
CREATE DATABASE inventaris_sekolah;

USE inventaris_sekolah;

CREATE TABLE rooms(
id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
kode_ruangan VARCHAR(20) UNIQUE,
room_name VARCHAR(50) NOT NULL,
user_id INT UNSIGNED,
description TEXT NUll,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP);

SHOW TABLES;

DESC rooms;

CREATE TABLE barang(
id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
item_code VARCHAR(20) UNIQUE,
item_name VARCHAR(50) NOT NULL,
room_id INT UNSIGNED,
FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE CASCADE ON UPDATE CASCADE,
description TEXT NULL,
rental_price FLOAT NOT NULL,
late_fee_per_day FLOAT NOT NULL,
quantity INT NOT NULL,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP);

ALTER TABLE rooms MODIFY room_name VARCHAR(255) NOT NULL UNIQUE;
DESC rooms;
ALTER TABLE rooms CHANGE kode_ruangan room_code VARCHAR(20);
ALTER TABLE barang add `condition` ENUM('good', 'fair', 'bad') NOT NULL AFTER description;
DESC rooms;

ALTER TABLE barang rename items;

DROP TABLE items;

SHOW TABLES;
DROP DATABASE inventaris_sekolah;

SHOW DATABASES;

-- DMl

-- Buat Ulang Database dan tablenya

CREATE DATABASE inventaris_sekolah;

USE inventaris_sekolah;

CREATE TABLE users (
id INT AUTO_INCREMENT PRIMARY KEY,
username VARCHAR(255) UNIQUE,
user_code VARCHAR(255) UNIQUE,
email VARCHAR(255) UNIQUE,
first_name VARCHAR(255),
last_name VARCHAR(255),
role ENUM('admin', 'operator', 'borrower'),
gender ENUM('laki-laki', 'perempuan'),
email_verified_at TIMESTAMP,
password VARCHAR(255),
remember_token VARCHAR(100),
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP);

CREATE TABLE rooms(
id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
room_code VARCHAR(20) UNIQUE,
room_name VARCHAR(50) NOT NULL,
user_id INT UNSIGNED,
description TEXT NUll,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP);

CREATE TABLE items(
id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
item_code VARCHAR(20) UNIQUE,
item_name VARCHAR(50) NOT NULL,
room_id INT UNSIGNED,
FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE CASCADE ON UPDATE CASCADE,
description TEXT NULL,
`condition` ENUM('good', 'fair', 'bad') NOT NULL,
rental_price FLOAT NOT NULL,
late_fee_per_day FLOAT NOT NULL,
quantity INT NOT NULL,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP);

CREATE table borrows(
id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
verification_code_for_borrow_request VARCHAR(255) NULL,
item_id INT UNSIGNED,
user_id INT UNSIGNED,
borrow_code VARCHAR(255) UNIQUE,
borrow_date DATE NOT NULL,
return_date DATE NOT NULL,
FOREIGN KEY (item_id) REFERENCES items(id) ON DELETE CASCADE ON UPDATE CASCADE,
borrow_status ENUM('pending', 'completed', 'borrowed', 'rejected') NOT NULL,
borrow_quantity INT(15) NOT NULL,
late_fee float(15) NOT NULL,
total_rental_price float(15) NOT NULL,
sub_total float(15) NOT NULL,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP);


INSERT INTO users (username, user_code, email, first_name, last_name, role, gender, email_verified_at, password, remember_token, created_at, updated_at)
VALUE ('arvin.ajif', 'JD001', 'arvin.ajif@example.com', 'Arvin', 'Ajif', 'admin', 'laki-laki', NULL, 'password123', NULL, NOW(), NOW());

INSERT INTO users (username, user_code, email, first_name, last_name, role, gender, email_verified_at, password, remember_token, created_at, updated_at) VALUE
('ali_ahmad', 'ADM0002', 'ali_ahmad@example.com', 'Ali', 'Ahmad', 'admin', 'laki-laki', NULL, 'password123', NULL, NOW(), NOW()),
('muthia.mutmainah_aprinelia', 'ADM0003', 'muthia.mutmainah_aplinelia@example.com', 'Muthia Mutmainah', 'Aprinelia', 'admin', 'perempuan', NULL, 'password123', NULL, NOW(), NOW()),
('dita.karang', 'OPR0001', 'dita.karang@example.com', 'Dita', 'Bayeo', 'operator', 'perempuan', NULL, 'password123', NULL, NOW(), NOW()),
('lee.soodam', 'OPR0002', 'lee.soodam@example.com', 'Neng', 'Dami', 'operator', 'perempuan', NULL, 'password123', NULL, NOW(), NOW()),
('park_jinny', 'OPR0003', 'park_jinny@example.com', 'Pink', 'Princess', 'operator', 'perempuan', NULL, 'password123', NULL, NOW(), NOW()),
('ogawa_mizuki', 'BRW0001', 'ogawa_mizuki@example.com', 'Oh', 'Mija', 'borrower', 'perempuan', NULL, 'password123', NULL, NOW(), NOW()),
('park_minji', 'BRW0002', 'park_minji@example.com', 'Orenji', 'Vitaminji', 'borrower', 'perempuan', NULL, 'password123', NULL, NOW(), NOW()),
('ji.yeong_ju', 'BRW0003', 'ji.yeong_ju@example.com', 'Zuu', 'Baedah', 'borrower', 'perempuan', NULL, 'password123', NULL, NOW(), NOW()),
('na.jaemin', 'ADM0004', 'na.jaemin@example.com', 'Na', 'Jaemin', 'admin', 'laki-laki', NULL, 'password123', NULL, NOW(), NOW()),
('moch_zayyan', 'OPR0004', 'moch_zayyan@example.com', 'Muhammad', 'Zayyan', 'operator', 'laki-laki', NULL, 'password123', NULL, NOW(), NOW());

INSERT INTO rooms(room_code, room_name, user_id, description, created_at, updated_at)
VALUE ('RMAM8213B-0000001', 'R-A-1-01', 4, 'Ruang Kelas', NOW(), NOW());
INSERT INTO rooms(room_code, room_name, user_id, description, created_at, updated_at) VALUE
('RMAM8213B-0000002', 'R-A-1-02', 5, 'Ruang Kelas', NOW(), NOW()),
('RM7328878-0000003', 'R-A-1-03', 6, 'Ruang Kelas', NOW(), NOW()),
('RM7HJS823-0000004', 'R-A-1-04', 11, 'Ruang Kelas', NOW(), NOW());

INSERT INTO items (item_code, item_name, room_id, description, `condition`, rental_price, late_fee_per_day, quantity, created_at, updated_at)
VALUE ('ITM001', 'Meja', 1, 'Meja', 'good', 10000, 20000, 22, NOW(), NOW());
INSERT INTO items (item_code, item_name, room_id, description, `condition`, rental_price, late_fee_per_day, quantity, created_at, updated_at)
VALUES ('ITM002', 'Buku', 2, 'Buku', 'good', 1000, 10000, 2, NOW(), NOW()),
('ITM003', 'Lemari', 3, 'Lemari', 'fair', 18000, 22000, 1, NOW(), NOW()),
('ITM004', 'Kursi', 4, 'Kursi', 'good', 14000, 18000, 42, NOW(), NOW());


INSERT INTO borrows (verification_code_for_borrow_request, item_id, user_id, borrow_code, borrow_date, return_date, borrow_status, borrow_quantity, late_fee, total_rental_price, sub_total, created_at, updated_at)
VALUE (NULL, 1, 7, 'BRW001', '2023-06-01', '2023-06-03', 'completed', 2, 0.0, 6000, 6000, NOW(), NOW());
INSERT INTO borrows (verification_code_for_borrow_request, item_id, user_id, borrow_code, borrow_date, return_date, borrow_status, borrow_quantity, late_fee, total_rental_price, sub_total, created_at, updated_at)
VALUES
('KJSADB', 3, 8, 'BRW002', '2023-06-03', '2023-06-06', 'pending', 1, 0, 0, 0, NOW(), NOW()),
(NULL, 4, 9, 'BRW004', '2023-06-04', '2023-06-07', 'completed', 39, 0.0, 1792000, 1792000, NOW(), NOW()),
(NULL, 4, 9, 'BRW005', '2023-06-05', '2023-06-08', 'borrowed', 3, 0, 0, 0, NOW(), NOW());

UPDATE borrows
SET borrow_status = 'borrowed',
late_fee = 0,
total_rental_price = 0,
sub_total = 0,
updated_at = NOW()
WHERE id = 2;

DELETE FROM borrows WHERE id = 3;

-- DML LANJUTAN
SELECT * FROM items LIMIT 2;

SELECT * FROM items LIMIT 2,4;

SELECT * FROM items ORDER BY item_name ASC;

SELECT *
FROM items, rooms
WHERE items.room_id = rooms.id;

DESC items;

SELECT borrows.borrow_code, users.username, items.item_name, rooms.room_name
FROM borrows
JOIN users ON borrows.user_id = users.id
JOIN items ON borrows.item_id = items.id
JOIN rooms ON items.room_id = rooms.id;

-- Perhitungan Data Dan Fungsi

-- Arimathic Calculator
SELECT rental_price + late_fee_per_day AS total_fee
FROM items;

SELECT quantity - 1 AS remaining_quantity
FROM items;

SELECT rental_price * quantity AS total_rental_price
FROM items;

SELECT total_rental_price / borrow_quantity AS rental_price_per_item
FROM borrows;

SELECT quantity % 5 AS remainder
FROM items;


-- Aggragate Function
SELECT COUNT(*) AS total_users
FROM users;

SELECT SUM(quantity) AS total_quantity,
AVG(rental_price) AS average_rental_price,
MIN(rental_price) AS minimum_rental_price,
MAX(rental_price) AS maximum_rental_price
FROM items;
-- String Funtion

SELECT CONCAT(first_name, ' ', last_name) AS full_name
FROM users;

SELECT CONCAT_WS('-', room_code, room_name) AS room_full_code
FROM rooms;

SELECT
LENGTH(username) AS username_length,
UPPER(first_name) AS uppercase_first_name,
LOWER(last_name) AS lowercase_last_name,
LEFT(username, 3) AS left_username,
RIGHT(username, 2) AS right_username,
SUBSTRING(email, 1, 10) AS email_prefix,
REPLACE(email, '@example.com', '@gmail.com') AS new_email,
REPEAT('*', 5) AS repeated_string,
REVERSE(username) AS reversed_username
FROM users;

SELECT
item_name,
MID(item_name, 4,3) AS Mid_Item_Name
FROM items;

SELECT TRIM(room_name) AS trimmed_room_name
FROM rooms;

SELECT room_name, INSTR(room_name, '1-') AS dash_position
FROM rooms;

-- Numeric Functions
SELECT
CEIL(rental_price) AS ceil_rental_price,
CEILING(rental_price) AS ceiling_rental_price,
FLOOR(rental_price) AS floor_rental_price,
MOD(quantity, 5) AS quantity_mod_5,
PI() AS pi_value,
POW(quantity, 2) AS quantity_power_2,
POWER(quantity, 3) AS quantity_power_3,
SQRT(quantity) AS quantity_sqrt,
ROUND(rental_price, 2) AS rounded_rental_price,
TRUNCATE(rental_price, 2) AS truncated_rental_price
FROM items;

-- Date / Time Functions
SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));

SELECT
user_id,
COUNT(*) AS total_borrows,
CURDATE() AS 'current_date',
CURTIME() AS 'current_time',
NOW() AS current_datetime,
DATE_FORMAT(MAX(created_at), '%Y-%m-%d') AS formatted_created_date,
DATE_FORMAT(MAX(created_at), '%H:%i:%s') AS formatted_created_time,
DATE_FORMAT(MAX(created_at), '%Y-%m-%d %H:%i:%s') AS formatted_created_datetime,
DATE_ADD(MAX(created_at), INTERVAL 1 DAY) AS next_day_created_datetime,
DATE_SUB(MAX(created_at), INTERVAL 1 MONTH) AS previous_month_created_datetime,
DATEDIFF(NOW(), MAX(created_at)) AS days_difference,
DAYNAME(MAX(created_at)) AS day_name,
MONTHNAME(MAX(created_at)) AS month_name,
DAYOFWEEK(MAX(created_at)) AS day_of_week,
WEEK(MAX(created_at)) AS week_of_year,
YEAR(MAX(created_at)) AS year
FROM borrows
GROUP BY user_id
ORDER BY total_borrows DESC LIMIT 0, 25;


-- Grouping Data Dan Trigger

SELECT user_id, COUNT(*) AS total_borrows
FROM borrows
GROUP BY user_id;

SELECT user_id, COUNT(*) AS total_borrows
FROM borrows
GROUP BY user_id
ORDER BY total_borrows DESC;

SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''));

SELECT
user_id,
COUNT(*) AS total_borrows
FROM
borrows
GROUP BY
user_id
HAVING
COUNT(*) >= 3
ORDER BY
total_borrows DESC;

-- Trigger

DELIMITER $$
CREATE TRIGGER tr_user_insert
BEFORE INSERT ON users
FOR EACH ROW
BEGIN
DECLARE role_prefix VARCHAR(3);
IF NEW.role = "admin" THEN
SET role_prefix = "ADM";
ELSEIF NEW.role = "operator" THEN
SET role_prefix = "OPT";
ELSEIF NEW.role = "borrower" THEN
SET role_prefix = "BWR";
END IF;

SET @random_string = LEFT(UUID(), 9);
SET NEW.user_code = CONCAT(role_prefix, @random_string, LPAD((SELECT COUNT(*) + 1 FROM users WHERE role = NEW.role), 9, "0"));
END$$
DELIMITER ;

DELIMITER //
CREATE TRIGGER tr_room_insert
BEFORE INSERT ON rooms
FOR EACH ROW
BEGIN
SET @random_string = LEFT(UUID(), 9); -- Generate a 9-character random string
SET NEW.room_code = CONCAT("RM", @random_string, LPAD((SELECT COUNT(*) + 1 FROM rooms), 9, "0")); -- Concatenate the random string and a zero-padded sequence number to form the room code
END//
DELIMITER ;

DROP TRIGGER IF EXISTS tr_item_insert;
DELIMITER //
CREATE TRIGGER tr_item_insert
BEFORE INSERT ON items
FOR EACH ROW
BEGIN
SET @random_string = LEFT(UUID(), 6); -- Generate a 6-character random string
SET NEW.item_code = CONCAT("ITM", @random_string, LPAD((SELECT COUNT(*) + 1 FROM items), 6, "0"));
END //
DELIMITER ;

DELIMITER //
CREATE TRIGGER tr_borrow_insert
BEFORE INSERT ON borrows
FOR EACH ROW
BEGIN
SET @random_string = LEFT(UUID(), 6); -- Generate a 6-character random string
SET NEW.borrow_code = CONCAT('BRW', @random_string, LPAD((SELECT COUNT(*) + 1 FROM borrows), 6, '0')); -- Concatenate the random string and a zero-padded sequence number to form the borrow code
END//
DELIMITER ;

-- Membuat trigger setelah melakukan insert pada tabel borrows
DELIMITER //

-- Trigger setelah melakukan insert
CREATE TRIGGER after_insert_borrow
AFTER INSERT ON borrows
FOR EACH ROW
BEGIN
-- Mendapatkan jumlah peminjaman saat ini untuk user_id yang baru saja melakukan peminjaman
SET @total_borrows = (SELECT COUNT(*) FROM borrows WHERE user_id = NEW.user_id);

-- Memperbarui jumlah peminjaman pada tabel users
UPDATE users SET total_borrows = @total_borrows WHERE user_id = NEW.user_id;
END //

-- Trigger setelah melakukan update
CREATE TRIGGER after_update_borrow
AFTER UPDATE ON borrows
FOR EACH ROW
BEGIN
-- Mendapatkan jumlah peminjaman saat ini untuk user_id yang mengalami perubahan
SET @total_borrows = (SELECT COUNT(*) FROM borrows WHERE user_id = NEW.user_id);

-- Memperbarui jumlah peminjaman pada tabel users
UPDATE users SET total_borrows = @total_borrows WHERE user_id = NEW.user_id;
END //

-- Trigger setelah melakukan delete
CREATE TRIGGER after_delete_borrow
AFTER DELETE ON borrows
FOR EACH ROW
BEGIN
-- Mendapatkan jumlah peminjaman saat ini untuk user_id yang mengalami penghapusan peminjaman
SET @total_borrows = (SELECT COUNT(*) FROM borrows WHERE user_id = OLD.user_id);

-- Memperbarui jumlah peminjaman pada tabel users
UPDATE users SET total_borrows = @total_borrows WHERE user_id = OLD.user_id;
END //

//
DELIMITER ;

-- View & Store Prosedur
-- Membuat view untuk menampilkan informasi peminjaman dengan nama lengkap pengguna
CREATE VIEW borrow_info AS
SELECT borrows.id, users.first_name, users.last_name, borrows.borrow_date
FROM borrows
JOIN users ON borrows.user_id = users.id;

-- Membuat stored procedure untuk menghapus peminjaman berdasarkan borrow_id
DELIMITER //

CREATE PROCEDURE delete_borrow(IN borrowId INT)
BEGIN
DELETE FROM borrows WHERE borrow_id = borrowId;
SELECT 'Peminjaman berhasil dihapus' AS message;
END //

DELIMITER ;

-- Backup Dan Restore Database

-- Mengembalikan seluruh database dari file backup.sql
mysql -u <username> -p <database_name> < backup.sql

-- Mengembalikan tabel tertentu dalam database dari file backup.sql
mysql -u <username> -p <database_name> < backup.sql










