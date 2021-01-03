SET FOREIGN_KEY_CHECKS = 0;
TRUNCATE TABLE employee;
TRUNCATE TABLE department;
SET FOREIGN_KEY_CHECKS = 1;

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