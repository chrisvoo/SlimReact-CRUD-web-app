<?php
declare(strict_types=1);

namespace App\Domain\DepartmentsReports;

interface DepartmentsReportsRepository
{
    /**
     * Show all departments along with the highest salary within each department.
     * A department with no employees should show 0 as the highest salary.
     *
     * @return array
     */
    public function getDepartmentsWithHighSalaries(): array;

    /**
     * List just those departments that have more than two employees that
     * earn over 50,000.
     *
     * @return array
     */
    public function getDepartmentsWithWealthyEmployees(): array;
}
