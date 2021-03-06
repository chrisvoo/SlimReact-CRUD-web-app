DROP DATABASE IF EXISTS crudwebapp;

CREATE USER IF NOT EXISTS 'cruduser'@'localhost' IDENTIFIED BY 'crudpass';
CREATE USER IF NOT EXISTS 'cruduser'@'127.0.0.1' IDENTIFIED BY 'crudpass';

CREATE DATABASE crudwebapp CHARACTER SET utf8 COLLATE utf8_general_ci;

GRANT ALL ON crudwebapp.* TO 'cruduser'@'localhost';
GRANT ALL ON crudwebapp.* TO 'cruduser'@'127.0.0.1';

USE crudwebapp;

CREATE TABLE department (
    id SERIAL,
    name VARCHAR(50) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    modified_at TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE employee (
    id SERIAL,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    salary FLOAT(8, 2) DEFAULT 0,
    department_id BIGINT UNSIGNED NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    modified_at TIMESTAMP,
    FOREIGN KEY (department_id) REFERENCES department(id)
        ON UPDATE CASCADE
        ON DELETE NO ACTION
) ENGINE=InnoDB;

INSERT INTO department(id, name) VALUES
(1, 'development'),
(2, 'marketing'),
(3, 'management'),
(4, 'sales');

INSERT INTO employee(id, first_name, last_name, salary, department_id) VALUES
  (1, 'Mike', 'Preston', 35000, 2),
  (2, 'Carlos', 'Santana', 56000, 1),
  (3, 'Josè', 'Jalapeño', 48650.5, 1),
  (4, 'Scarlett', 'Johansson', 61500, 2),
  (5, 'Bill', 'Murray', 42356.23, 4),
  (6, 'Jack', 'Black', 52600.23, 1),
  (7, 'John', 'Petrucci', 71000, 1);

CREATE OR REPLACE VIEW highest_salaries AS
SELECT d.name, MAX(COALESCE(e.salary,0)) as num_employees
FROM department d
  LEFT JOIN employee e ON (d.id = e.department_id)
GROUP BY d.name;

CREATE OR REPLACE VIEW expensive_departments AS
SELECT d.name, SUM(CASE WHEN e.salary > 50000 THEN 1 ELSE 0 END) as num_employees
FROM department d
 LEFT JOIN employee e ON (d.id = e.department_id)
GROUP BY d.name
HAVING num_employees > 2
