CREATE TABLE IF NOT EXISTS users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'student', 'staff') NOT NULL DEFAULT 'student',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    CONSTRAINT users_email_unique UNIQUE (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -- Insert default admin user (password: admin123)
-- INSERT INTO users (name, email, password, role) 
-- VALUES ('Administrator', 'admin@admin.com', '$2y$10$8KzQ.ROCxXBPwj9JvICYQ5/QA.cW8o6G', 'admin');
