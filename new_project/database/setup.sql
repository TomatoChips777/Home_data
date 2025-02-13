-- Drop existing tables if they exist
DROP TABLE IF EXISTS lost_found;
DROP TABLE IF EXISTS sos_reports;
DROP TABLE IF EXISTS users;

-- Create users table
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    role ENUM('admin', 'student', 'staff') NOT NULL,
    department VARCHAR(100),
    student_id VARCHAR(50),
    contact_number VARCHAR(20),
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    status ENUM('active', 'inactive', 'suspended') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create lost_found table
CREATE TABLE lost_found (
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
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Create sos_reports table with enhanced structure
CREATE TABLE sos_reports (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT NOT NULL,
    emergency_type VARCHAR(100) NOT NULL,
    description TEXT NOT NULL,
    location VARCHAR(255) NOT NULL,
    status ENUM('pending', 'responding', 'resolved', 'cancelled') DEFAULT 'pending',
    priority_level ENUM('low', 'medium', 'high', 'critical') DEFAULT 'medium',
    reported_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    resolved_at DATETIME,
    responder_notes TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insert default admin user (password: admin123)
INSERT INTO users (username, password, email, full_name, role, department) 
VALUES ('admin', '$2y$10$8KzQ.ROCxXBPwj1vxpKlPeYmZTBG2BH8rQj9JvICYQ5/QA.cW8o6G', 'admin@school.edu', 'System Administrator', 'admin', 'IT Department');
