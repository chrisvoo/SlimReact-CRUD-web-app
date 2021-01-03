<?php

namespace App\Infrastructure\Persistence\Department;

use App\Infrastructure\Persistence\TableMapper;
use PDO;
use App\Domain\Department\Department;
use App\Domain\Department\DepartmentNotFoundException;
use App\Domain\Department\DepartmentRepository;
use App\Domain\DomainException\InvalidEntityException;
use App\Infrastructure\Persistence\DatabaseException;

class DepartmentRepositoryDatabase implements DepartmentRepository
{
    /**
     * A PDO instance.
     *
     * @var PDO
     */
    private $pdo;

    public function __construct(PDO $pdoClass)
    {
        $this->pdo = $pdoClass;
    }

    /**
     * Returns all departments.
     *
     * @return Department[]
     */
    public function findAll(): array
    {
        $statement = $this->pdo->query("SELECT * FROM " . TableMapper::DEPARTMENT());
        $departments = [];

        while ($row = $statement->fetch()) {
            array_push($departments, new Department(
                [
                    "id" => $row["id"],
                    "name" => $row["name"],
                ]
            ));
        }

        return $departments;
    }

    /**
     * Returns the department.
     *
     * @param int $id The department's id.
     *
     * @return Department
     *
     * @throws DepartmentNotFoundException If the department with such id does not exist.
     */
    public function findDepartmentById(int $id): Department
    {
        $statement = $this->pdo->prepare("SELECT * FROM " . TableMapper::DEPARTMENT() . " WHERE id = :id");
        $statement->execute([
            "id" => $id
        ]);

        $data = $statement->fetchAll();
        if (empty($data)) {
            throw new DepartmentNotFoundException("Department $id cannot be found");
        }

        return new Department([
            "id" => $data[0]["id"],
            "name" => $data[0]["name"],
        ]);
    }

    /**
     * Updates an existing Department.
     *
     * @param Department $dep The Department to be updated.
     *
     * @return boolean True on success, false otherwise.
     *
     * @throws InvalidEntityException      If you've passed a department without id.
     * @throws DepartmentNotFoundException If the record doesn't exist.
     */
    public function updateDepartmentById(Department $dep): bool
    {
        if (empty($dep->getId())) {
            throw new InvalidEntityException("You've passed a department without id");
        }

        // this step will throw an error if the record does not exist in the db
        $oldDep = $this->findDepartmentById($dep->getId());

        $statement = $this->pdo->prepare(
            "UPDATE " . TableMapper::DEPARTMENT() . "
                   SET name = :name
                   WHERE id = :id     
        ");

        // it's an update, not a total replace.
        return $statement->execute([
            "id" => $dep->getId(),
            "name" => $dep->getName() ?? $oldDep->getName(),
        ]);
    }

    /**
     * Creates a new Department.
     *
     * @param Department $dep The Department to insert.
     *
     * @return Department The Department with its primary key.
     *
     * @throws DepartmentNotFoundException If it hasn't been created so it cannot be found.
     */
    public function createDepartment(Department $dep): Department
    {
        $statement = $this->pdo->prepare("INSERT INTO " . TableMapper::DEPARTMENT() . " (name) VALUES (:name)");
        $statement->execute([
            "name" => $dep->getName(),
        ]);

        $id = $this->pdo->lastInsertId();

        return $this->findDepartmentById((int)$id);
    }

    /**
     * Deletes a Department from the database and every relation with the related employees. If you try to delete a
     * department referenced by an employee, you'll get an exception.
     *
     * @param int $id Department's id.
     *
     * @return boolean True if the transaction has been successful, false otherwise.
     * @throws DatabaseException If there's a Foreign key constraint violation or another db error.
     */
    public function deleteDepartment(int $id): bool
    {
        try {
            $statement = $this->pdo->prepare("DELETE FROM " . TableMapper::DEPARTMENT() . " WHERE id = :id");
            return $statement->execute([
                "id" => $id,
            ]);
        } catch (\PDOException $pdoEx) {
            if (stripos($pdoEx->getMessage(), "foreign key constraint fails") !== false) {
                throw new DatabaseException(
                    "You cannot delete department $id. It's still ".
                        "referenced by one or more employees"
                );
            }

            throw new DatabaseException("A database error occurred for deleteDepartment");
        }
    }
}