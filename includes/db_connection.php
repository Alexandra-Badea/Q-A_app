<?php
    // Database configuration
    include "config.php";

    // Create connection
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Create database if it doesn't exist
    $sql_create_database = "CREATE DATABASE IF NOT EXISTS quizz";
    
    if ($conn->query($sql_create_database) === FALSE) {
        echo "Error creating database: " . $conn->error;
    }
    
    // Select the database
    $conn->select_db("quizz");

    // Create users table
    $sql_users = "CREATE TABLE IF NOT EXISTS users (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(30) NOT NULL,
        email VARCHAR(50) NOT NULL,
        password VARCHAR(60) NOT NULL,
        reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    )";

    if ($conn->query($sql_users) === FALSE) {
        echo "Error creating users table: " . $conn->error;
    }
    
    // Create tasks table
    $sql_tasks = "CREATE TABLE IF NOT EXISTS tasks (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id INT(6) UNSIGNED NOT NULL,
        title VARCHAR(30) NOT NULL,
        question VARCHAR(500) NOT NULL,
        reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id)
    )";

    // if ($conn->query($sql_tasks) === FALSE) {
    //     echo "Error creating tasks table: " . $conn->error;
    // }

    // Create answewrs table
    $sql_answers = "CREATE TABLE IF NOT EXISTS answers (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        user_id INT(6) UNSIGNED NOT NULL,
        task_id INT(6) UNSIGNED NOT NULL,
        answer VARCHAR(500) NOT NULL,
        reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id),
        FOREIGN KEY (task_id) REFERENCES tasks(id)
    )";

    if ($conn->query($sql_answers) === FALSE) {
        echo "Error creating answers table: " . $conn->error;
    }
?>