-- Use database
CREATE DATABASE IF NOT EXISTS api_helper;
USE api_helper;

-- Projects table
CREATE TABLE IF NOT EXISTS projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Groups table
CREATE TABLE IF NOT EXISTS endpoint_groups (
    id INT AUTO_INCREMENT PRIMARY KEY,
    project_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    parent_id INT DEFAULT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (project_id) REFERENCES projects(id)
);

-- Endpoints table
CREATE TABLE IF NOT EXISTS endpoints (
    id INT AUTO_INCREMENT PRIMARY KEY,
    group_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    url VARCHAR(255) NOT NULL,
    method VARCHAR(10) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (group_id) REFERENCES endpoint_groups(id)
);

-- Parameters table (now linked to endpoint)
CREATE TABLE IF NOT EXISTS parameters (
    id INT AUTO_INCREMENT PRIMARY KEY,
    endpoint_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    type VARCHAR(50) NOT NULL,
    required TINYINT(1) DEFAULT 0,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (endpoint_id) REFERENCES endpoints(id)
);

-- Variations table
CREATE TABLE IF NOT EXISTS variations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    endpoint_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (endpoint_id) REFERENCES endpoints(id)
);

-- Variation parameter values table
CREATE TABLE IF NOT EXISTS variation_parameters (
    id INT AUTO_INCREMENT PRIMARY KEY,
    variation_id INT NOT NULL,
    parameter_id INT NOT NULL,
    value TEXT,
    value_type VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (variation_id) REFERENCES variations(id),
    FOREIGN KEY (parameter_id) REFERENCES parameters(id)
);

-- Example data (projects, groups, endpoints)
INSERT INTO projects (name, description) VALUES
('Project One', 'Demo project 1'),
('Project Two', 'Demo project 2');

INSERT INTO endpoint_groups (project_id, name, parent_id) VALUES
(1, 'User Management', NULL),
(1, 'Admin Tools', NULL),
(2, 'Analytics', NULL);

INSERT INTO endpoints (group_id, title, url, method, description) VALUES
(1, 'Create User', '/api/user/create', 'POST', 'Create a new user'),
(1, 'List Users', '/api/user/list', 'GET', 'List all users'),
(2, 'Admin Stats', '/api/admin/stats', 'GET', 'Get admin statistics');

-- Endpoint parameters
INSERT INTO parameters (endpoint_id, name, type, required, description) VALUES
(1, 'username', 'string', 1, 'User username'),
(1, 'password', 'string', 1, 'User password'),
(1, 'email', 'string', 0, 'Optional email');

-- Variations
INSERT INTO variations (endpoint_id, title, description) VALUES
(1, 'Basic User Creation', 'Create user with only required fields'),
(1, 'Extended User Creation', 'Create user with optional fields');

-- Variation parameter values
INSERT INTO variation_parameters (variation_id, parameter_id, value, value_type) VALUES
(1, 1, '', 'string'),   -- username
(1, 2, '', 'string'),   -- password
(2, 1, '', 'string'),   -- username
(2, 2, '', 'string'),   -- password
(2, 3, '', 'string');   -- email
