<?php
declare(strict_types=1);

namespace App\Domain\Department;

use App\Domain\DomainException\InvalidEntityException;
use App\Infrastructure\Persistence\DatabaseException;

interface DepartmentRepository
{
    /**
     * Returns all departments.
     *
     * @return Department[]
     */
    public function findAll(): array;

    /**
     * Returns the employee
     * @param int $id
     * @return Department
     * @throws DepartmentNotFoundException
     */
    public function findDepartmentById(int $id): Department;

    /**
     * Updates an existing Department.
     *
     * @param Department $dep The Department to be updated.
     *
     * @return boolean True on success, false otherwise.
     *
     * @throws InvalidEntityException    If you've passed a department without id.
     * @throws DepartmentNotFoundException If the record doesn't exist.
     */
    public function updateDepartmentById(Department $dep): bool;

    /**
     * Creates a new Department.
     *
     * @param Department $dep The Department to insert.
     *
     * @return Department The Department with its primary key.
     *
     * @throws DepartmentNotFoundException If it hasn't been created so it cannot be found.
     */
    public function createDepartment(Department $dep): Department;

    /**
     * Deletes a Department from the database and every relation with the related employees.
     *
     * @param int $id Department's id.
     *
     * @return boolean True if the transaction has been successful, false otherwise.
     *
     * @throws DatabaseException If the transaction has failed.
     */
    public function deleteDepartment(int $id): bool;
}
