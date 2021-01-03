<?php
declare(strict_types=1);

namespace App\Domain\Employee;

use App\Domain\DomainException\InvalidEntityException;
use App\Infrastructure\Persistence\DatabaseException;

interface EmployeeRepository
{
    /**
     * Returns all employees.
     *
     * @return Employee[]
     */
    public function findAll(): array;

    /**
     * Returns the employee
     * @param int $id
     * @return Employee
     * @throws EmployeeNotFoundException
     */
    public function findEmployeeById(int $id): Employee;

    /**
     * Updates an existing employee.
     *
     * @param Employee $emp The employee to be updated.
     *
     * @return boolean True on success, false otherwise.
     *
     * @throws InvalidEntityException    If you've passed an employee without id.
     * @throws EmployeeNotFoundException If the record doesn't exist.
     */
    public function updateEmployeeById(Employee $emp): bool;

    /**
     * Creates a new employee.
     *
     * @param Employee $emp The employee to insert.
     *
     * @return Employee The Employee with its primary key.
     *
     * @throws EmployeeNotFoundException If it hasn't been created so it cannot be found.
     */
    public function createEmployee(Employee $emp): Employee;

    /**
     * Deletes an employee from the database and every relation with the previous department he belonged to.
     *
     * @param int $id Employee's id.
     *
     * @return boolean True if the transaction has been successful, false otherwise.
     *
     * @throws DatabaseException If the transaction has failed.
     */
    public function deleteEmployee(int $id): bool;
}
