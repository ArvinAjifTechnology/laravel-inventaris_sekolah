DROP TRIGGER IF EXISTS tr_user_insert;
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

DROP TRIGGER IF EXISTS tr_user_insert;
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
DROP TABLE IF EXISTS rooms;
CREATE TABLE rooms(
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    room_code VARCHAR(20) UNIQUE,
    room_name VARCHAR(50) NOT NULL UNIQUE,
    user_id INT UNSIGNED,
    description TEXT NUll,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

DELIMITER //
CREATE TRIGGER tr_room_insert
BEFORE INSERT ON rooms
FOR EACH ROW
BEGIN
    SET @random_string = LEFT(UUID(), 9); -- Generate a 9-character random string
    SET NEW.room_code = CONCAT("RM", @random_string, LPAD((SELECT COUNT(*) + 1 FROM rooms), 9, "0")); -- Concatenate the random string and a zero-padded sequence number to form the room code
END//
DELIMITER ;
DROP TABLE IF EXISTS items;
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
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

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

DROP TABLE IF EXISTS borrows;
CREATE table borrows(
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    verification_code_for_borrow_request VARCHAR(255) NULL,
    item_id INT UNSIGNED,
    user_id INT UNSIGNED,
    borrow_code VARCHAR(255) UNIQUE,
    borrow_date DATE NOT NULL,
    return_date DATE NOT NULL,
    FOREIGN KEY (item_id) REFERENCES items(id) ON DELETE CASCADE ON UPDATE CASCADE,
    -- FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE ON UPDATE CASCADE,
    borrow_status ENUM('pending', 'completed', 'borrowed', 'rejected') NOT NULL,
    borrow_quantity INT(15) NOT NULL,
    late_fee float(15) NOT NULL,
    total_rental_price float(15) NOT NULL,
    sub_total float(15) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

DROP TRIGGER IF EXISTS tr_borrow_insert;
DELIMITER //
CREATE TRIGGER tr_borrow_insert
BEFORE INSERT ON borrows
FOR EACH ROW
BEGIN
    SET @random_string = LEFT(UUID(), 6); -- Generate a 6-character random string
    SET NEW.borrow_code = CONCAT('BRW', @random_string, LPAD((SELECT COUNT(*) + 1 FROM borrows), 6, '0')); -- Concatenate the random string and a zero-padded sequence number to form the borrow code
END//
DELIMITER ;
