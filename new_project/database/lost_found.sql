CREATE TABLE IF NOT EXISTS lost_found (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    type ENUM('lost', 'found') NOT NULL,
    item_name VARCHAR(255) NOT NULL,
    category VARCHAR(100) NOT NULL,
    description TEXT,
    location VARCHAR(255) NOT NULL,
    date_reported DATETIME DEFAULT CURRENT_TIMESTAMP,
    status ENUM('open', 'closed', 'claimed') DEFAULT 'open',
    image_path VARCHAR(255),
    contact_info VARCHAR(255),
    is_anonymous BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (user_id) REFERENCES users(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
