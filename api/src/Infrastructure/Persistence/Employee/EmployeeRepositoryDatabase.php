<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\Employee;

use App\Domain\DomainException\InvalidEntityException;
use App\Domain\Employee\Employee;
use App\Infrastructure\Persistence\DatabaseException;
use App\Infrastructure\Persistence\TableMapper;
use PDO;
use App\Domain\Employee\EmployeeNotFoundException;
use App\Domain\Employee\EmployeeRepository;

class EmployeeRepositoryDatabase implements EmployeeRepository
{
    /**
     * A PDO instance.
     *
     * @var PDO
     */
    private $pdo;

    /**
     * SQL join.
     *
     * @var string
     */
    private $sqlDepartmentJoin;

    public function __construct(PDO $pdoClass)
    {
        $this->pdo = $pdoClass;
        $this->sqlDepartmentJoin =
            "SELECT e.id, e.first_name, e.last_name, e.salary, e.department_id, d.name AS depName
               FROM " . TableMapper::EMPLOYEE() . " e 
                JOIN " . TableMapper::DEPARTMENT() . " d ON d.id = e.department_id ";
    }

    /**
     * Returns all the employees without pagination.
     *
     * @return Employee[]
     */
    public function findAll(): array
    {
        $statement = $this->pdo->query($this->sqlDepartmentJoin);

        $employees = [];

        while ($row = $statement->fetch()) {
            array_push($employees, new Employee(
                [
                    "id" => $row["id"],
                    "firstName" => $row["first_name"],
                    "lastName" => $row["last_name"],
                    "salary" => $row["salary"],
                    "departmentId" => $row["department_id"],
                    "departmentName" => $row["depName"],
                ]
            ));
        }

        return $employees;
    }

    /**
     * @param int $id The employee's ID.
     *
     * @return Employee
     *
     * @throws EmployeeNotFoundException If the record doesn't exist.
     */
    public function findEmployeeById(int $id): Employee
    {
        $statement = $this->pdo->prepare($this->sqlDepartmentJoin . " WHERE e.id = :id");
        $statement->execute([
            "id" => $id
        ]);

        $data = $statement->fetchAll();
        if (empty($data)) {
            throw new EmployeeNotFoundException("Employee $id cannot be found");
        }

        return new Employee([
            "id" => $data[0]["id"],
            "firstName" => $data[0]["first_name"],
            "lastName" => $data[0]["last_name"],
            "salary" => $data[0]["salary"],
            "departmentId" => $data[0]["department_id"],
            "departmentName" => $data[0]["depName"],
        ]);
    }

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
    public function updateEmployeeById(Employee $emp): bool {
        if (empty($emp->getId())) {
            throw new InvalidEntityException("You've passed an employee without id");
        }

        // this step will throw an error if the record does not exist in the db
        $oldEmp = $this->findEmployeeById($emp->getId());

        $statement = $this->pdo->prepare(
            "UPDATE " . TableMapper::EMPLOYEE() . "
                   SET first_name = :first_name,
                       last_name = :last_name,
                       salary = :salary,
                       department_id = :department_id
                   WHERE id = :id     
        ");

        // it's an update, not a total replace.
        return $statement->execute([
            "id" => $emp->getId(),
            "first_name" => $emp->getFirstName() ?? $oldEmp->getFirstName(),
            "last_name" => $emp->getLastName() ?? $oldEmp->getLastName(),
            "salary" => $emp->getSalary() ?? $oldEmp->getSalary(),
            "department_id" => $emp->getDepartmentId() ?? $oldEmp->getDepartmentId(),
        ]);
    }

    /**
     * Creates a new employee.
     *
     * @param Employee $emp The employee to insert.
     *
     * @return Employee The Employee with its primary key.
     *
     * @throws EmployeeNotFoundException If it hasn't been created so it cannot be found.
     */
    public function createEmployee(Employee $emp): Employee {
        $statement = $this->pdo->prepare(
            "INSERT INTO " . TableMapper::EMPLOYEE() . " (first_name, last_name, salary, department_id)
                   VALUES (:first_name, :last_name, :salary, :department_id)
        ");
        $statement->execute([
            "first_name" => $emp->getFirstName(),
            "last_name" => $emp->getLastName(),
            "salary" => $emp->getSalary(),
            "department_id" => $emp->getDepartmentId(),
        ]);

        $id = $this->pdo->lastInsertId();

        return $this->findEmployeeById((int)$id);
    }

    /**
     * Deletes an employee from the database and every relation with the previous department he belonged to.
     *
     * @param int $id Employee's id.
     *
     * @return boolean True if the transaction has been successful, false otherwise.
     *
     * @throws DatabaseException If the transaction has failed.
     */
    public function deleteEmployee(int $id): bool {
        $statement = $this->pdo->prepare(
            "DELETE FROM " . TableMapper::EMPLOYEE() . " WHERE id = :id"
        );

        return $statement->execute([
            "id" => $id,
        ]);
    }
}
