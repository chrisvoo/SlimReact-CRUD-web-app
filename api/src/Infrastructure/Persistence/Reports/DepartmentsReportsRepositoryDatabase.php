<?php

namespace App\Infrastructure\Persistence\Reports;

use App\Domain\DepartmentsReports\DepartmentsReportsRepository;
use PDO;
use App\Infrastructure\Persistence\ViewMapper;

class DepartmentsReportsRepositoryDatabase implements DepartmentsReportsRepository
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
     * In this case, we're just sharing this method to do the same operation. It
     * wouldn't probably happen in reality.
     *
     * @param string $viewName The view's name.
     *
     * @return array
     */
    private function getViewData(string $viewName)
    {
        $statement = $this->pdo->query("SELECT name, num_employees FROM " . $viewName);
        return $statement->fetchAll();
    }

    /**
     * Show all departments along with the highest salary within each department.
     * A department with no employees should show 0 as the highest salary.
     *
     * @return array
     */
    public function getDepartmentsWithHighSalaries(): array
    {
        return $this->getViewData(ViewMapper::HIGHEST_SALARIES());
    }

    /**
     * List just those departments that have more than two employees that
     * earn over 50,000.
     *
     * @return array
     */
    public function getDepartmentsWithWealthyEmployees(): array
    {
        return $this->getViewData(ViewMapper::EXPENSIVE_DEPARTMENTS());
    }
}