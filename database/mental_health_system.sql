CREATE DATABASE IF NOT EXISTS mental_health_system;
USE mental_health_system;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    age INT NOT NULL,
    gender VARCHAR(20) NOT NULL,
    occupation VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE assessments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    question_1 TINYINT NOT NULL,
    question_2 TINYINT NOT NULL,
    question_3 TINYINT NOT NULL,
    question_4 TINYINT NOT NULL,
    question_5 TINYINT NOT NULL,
    question_6 TINYINT NOT NULL,
    question_7 TINYINT NOT NULL,
    question_8 TINYINT NOT NULL,
    question_9 TINYINT NOT NULL,
    question_10 TINYINT NOT NULL,
    stress_score INT NOT NULL,
    mental_health_score INT NOT NULL,
    productivity_score INT NOT NULL,
    sleep_score INT NOT NULL,
    risk_level VARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT fk_user FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE research_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    item_type ENUM('summary', 'stat', 'image', 'article') NOT NULL DEFAULT 'summary',
    body TEXT NULL,
    stat_label VARCHAR(100) NULL,
    stat_value VARCHAR(50) NULL,
    image_path VARCHAR(255) NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE research_documents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    doc_type VARCHAR(50) NOT NULL,
    description TEXT,
    file_path VARCHAR(255) NOT NULL,
    file_size INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO admin (username, password) VALUES
('admin', '$2y$10$ZyCRY5/0G17Z7JnM07JkFOj.MObx3L55QPa5LGSwWjf8N3XzW66jO');
