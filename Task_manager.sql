

USE if0_39006842_task_manager;


    CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE tasks (
    
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    
    status ENUM('pending', 'completed') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

ALTER TABLE tasks
ADD COLUMN start_time TIMESTAMP NULL DEFAULT NULL,
ADD COLUMN end_time TIMESTAMP NULL DEFAULT NULL,
ADD COLUMN total_time TIME DEFAULT '00:00:00';



CREATE TABLE login (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    password VARCHAR(255) NOT NULL
);
INSERT INTO login (username, password) VALUES
('fened123', 'pass123'),
('admin', 'adminpass'),
('user1', 'welcome1');
ALTER TABLE tasks
ADD priority ENUM('Low', 'Medium', 'High') DEFAULT 'Medium';


