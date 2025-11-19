CREATE TABLE projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE endpoint_groups (
    id INT AUTO_INCREMENT PRIMARY KEY,
    project_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    parent_id INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
    FOREIGN KEY (parent_id) REFERENCES endpoint_groups(id) ON DELETE SET NULL
);

CREATE TABLE api_endpoints (
    id INT AUTO_INCREMENT PRIMARY KEY,
    group_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    url VARCHAR(255) NOT NULL,
    method ENUM('GET','POST') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (group_id) REFERENCES endpoint_groups(id) ON DELETE CASCADE
);

CREATE TABLE endpoint_variations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    endpoint_id INT NOT NULL,
    variation_name VARCHAR(255) NOT NULL,
    description TEXT,
    example_body JSON,
    depends_on VARCHAR(255), -- e.g.: "createUser"
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (endpoint_id) REFERENCES api_endpoints(id) ON DELETE CASCADE
);

CREATE TABLE endpoint_parameters (
    id INT AUTO_INCREMENT PRIMARY KEY,
    endpoint_id INT NOT NULL,
    variation_id INT DEFAULT NULL,
    name VARCHAR(255) NOT NULL,
    type VARCHAR(50) NOT NULL,
    required ENUM('yes','no','semi') DEFAULT 'no',
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (endpoint_id) REFERENCES api_endpoints(id) ON DELETE CASCADE,
    FOREIGN KEY (variation_id) REFERENCES endpoint_variations(id) ON DELETE CASCADE
);
