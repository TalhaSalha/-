CREATE DATABASE IF NOT EXISTS gaza_builds;
USE gaza_builds;

CREATE TABLE IF NOT EXISTS buildings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    owner_id VARCHAR(20) NOT NULL,
    submitter_name VARCHAR(255) NOT NULL,
    submitter_phone VARCHAR(20) NOT NULL,
    submitter_email VARCHAR(255) NOT NULL,
    building_name VARCHAR(255) NOT NULL,
    building_type ENUM('government', 'private') NOT NULL,
    address TEXT NOT NULL,
    damage_type ENUM('total', 'partial') NOT NULL,
    description TEXT,
    image_path VARCHAR(255),
    additional_info TEXT,
    status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- Insert a default admin user (password: admin123)
-- In a real application, passwords should be hashed using password_hash()
INSERT INTO admins (username, password) VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');
