-- Table for projects
CREATE TABLE projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Table for groups
CREATE TABLE groups (
    id INT AUTO_INCREMENT PRIMARY KEY,
    project_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE
);

-- Table for endpoints
CREATE TABLE endpoints (
    id INT AUTO_INCREMENT PRIMARY KEY,
    group_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    endpoint VARCHAR(255) NOT NULL,  -- ex: /user/create
    method ENUM('GET','POST') NOT NULL DEFAULT 'GET',
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (group_id) REFERENCES groups(id) ON DELETE CASCADE
);

-- Table for variations
CREATE TABLE variations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    endpoint_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (endpoint_id) REFERENCES endpoints(id) ON DELETE CASCADE
);

-- Table for parameters
CREATE TABLE parameters (
    id INT AUTO_INCREMENT PRIMARY KEY,
    variation_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    type ENUM('string','int','float','boolean','array','object') NOT NULL DEFAULT 'string',
    required ENUM('required','optional','semi-optional') NOT NULL DEFAULT 'required',
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (variation_id) REFERENCES variations(id) ON DELETE CASCADE
);
