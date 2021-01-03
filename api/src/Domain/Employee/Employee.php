<?php
declare(strict_types=1);

namespace App\Domain\Employee;

use JsonSerializable;

class Employee implements JsonSerializable
{
    /**
     * @var int|null
     */
    private $id;

    /**
     * @var string|null
     */
    private $firstName;

    /**
     * @var string|null
     */
    private $lastName;

    /**
     * The employee's salary.
     *
     * @var float|null
     */
    private $salary;

    /**
     * Department's ID.
     *
     * @var int|null
     */
    private $departmentId;

    /**
     * Constructor with all the params.
     *
     * @param array  $params
     */
    public function __construct($params = [])
    {
        $this->setId($params["id"] ?? null);
        $this->setFirstName($params["firstName"] ?? null);
        $this->setLastName($params["lastName"] ?? null);
        $this->setSalary($params["salary"] ?? null);
        $this->setDepartmentId($params["departmentId"] ?? null);
    }

    /**
     * Returns the primary key.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Sets the primary key.
     *
     * @param int|null $id The PK.
     * @return Employee
     */
    public function setId(?int $id): Employee
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Returns the first name.
     *
     * @return string|null
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * Sets the first name.
     *
     * @param string|null $firstName The first name.
     *
     * @return Employee
     */
    public function setFirstName(?string $firstName): Employee
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * Returns the last name.
     *
     * @return string|null
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * Sets the last name.
     *
     * @param string|null $lastName
     * @return Employee
     */
    public function setLastName(?string $lastName): Employee
    {
        $this->lastName = $lastName;
        return $this;
    }

    /**
     * @return float|null
     */
    public function getSalary(): ?float
    {
        return $this->salary;
    }

    /**
     * @param float|null $salary
     * @return Employee
     */
    public function setSalary(?float $salary): Employee
    {
        $this->salary = $salary;
        return $this;
    }

    /**
     * Returns the department's id.
     *
     * @return int|null
     */
    public function getDepartmentId(): ?int {
        return $this->departmentId;
    }

    /**
     * Sets the department's ID.
     *
     * @param int|null $depId The department's ID.
     *
     * @return $this
     */
    public function setDepartmentId(?int $depId): Employee
    {
        $this->departmentId = $depId;
        return $this;
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'firstName' => $this->firstName,
            'lastName' => $this->lastName,
            'salary' => $this->salary,
            'departmentId' => $this->departmentId
        ];
    }
}
