CREATE TABLE users (
id INT AUTO_INCREMENT PRIMARY KEY,
username VARCHAR(50) UNIQUE NOT NULL,
password VARCHAR(255) NOT NULL,
role ENUM('admin','manager','user')
);

CREATE TABLE room_sensors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    room_id INT,
    sensor_status ENUM('ON','OFF') DEFAULT 'ON',
    FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE CASCADE
);

CREATE TABLE rooms (
id INT AUTO_INCREMENT PRIMARY KEY,
room_name VARCHAR(100) NOT NULL,
latitude DECIMAL(10,6) NOT NULL,
longitude DECIMAL(10,6) NOT NULL,
created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE sensor_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    room_id INT,
    recorded_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    room_temp FLOAT,
    exhaust_temp FLOAT,
    aircon_status ENUM('ON','OFF'),
    fan_status ENUM('ON','OFF'),
    runtime VARCHAR(50),

    FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE CASCADE
);

CREATE TABLE user_rooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    room_id INT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (room_id) REFERENCES rooms(id) ON DELETE CASCADE
);

CREATE TABLE activity_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    action VARCHAR(255) NOT NULL,
    details TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);