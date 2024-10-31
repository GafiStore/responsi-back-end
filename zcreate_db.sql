CREATE DATABASE db_back_end; 

USE db_back_end;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    fullname VARCHAR(100) NOT NULL,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nilai DECIMAL(5, 2) DEFAULT 0
);