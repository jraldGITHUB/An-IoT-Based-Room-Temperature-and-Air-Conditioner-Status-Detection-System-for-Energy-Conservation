CREATE TABLE users (
id INT AUTO_INCREMENT PRIMARY KEY,
username VARCHAR(50) UNIQUE NOT NULL,
password VARCHAR(255) NOT NULL,
role ENUM('admin','manager','user')
);

CREATE TABLE rooms (
    room_id INT AUTO_INCREMENT PRIMARY KEY,
    room_name VARCHAR(100) NOT NULL,
    latitude DECIMAL(10,6),
    longitude DECIMAL(10,6)
);

CREATE TABLE devices (
    device_id INT AUTO_INCREMENT PRIMARY KEY,
    device_name VARCHAR(100),
    device_type VARCHAR(50),
    room_id INT,
    status VARCHAR(20) DEFAULT 'ACTIVE',

    CONSTRAINT fk_device_room
    FOREIGN KEY (room_id) REFERENCES rooms(room_id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);  

CREATE TABLE sensor_logs (
    log_id INT AUTO_INCREMENT PRIMARY KEY,
    device_id INT,

    temperature DECIMAL(5,2),
    exhaust_temp DECIMAL(5,2),

    aircon_status VARCHAR(10),
    fan_status VARCHAR(10),
    runtime VARCHAR(20),

    log_date DATE,
    log_time TIME,

    CONSTRAINT fk_sensor_device
    FOREIGN KEY (device_id) REFERENCES devices(device_id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

CREATE TABLE device_logs (
    log_id INT AUTO_INCREMENT PRIMARY KEY,
    device_id INT,
    status VARCHAR(20),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_device_logs_device
    FOREIGN KEY (device_id) REFERENCES devices(device_id)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);