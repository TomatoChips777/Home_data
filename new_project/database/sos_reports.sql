    CREATE TABLE IF NOT EXISTS reports (
        id INT PRIMARY KEY AUTO_INCREMENT,
        user_id INT NOT NULL,
        location VARCHAR(255) NOT NULL,
        issue_type VARCHAR(100) NOT NULL,
        description TEXT NOT NULL,
        image_path VARCHAR(255),
        status ENUM('pending', 'in_progress', 'resolved') DEFAULT 'pending',
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(id)
    );
