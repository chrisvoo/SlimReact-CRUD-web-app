<?php

namespace App\Application\Controllers;

use App\Domain\DepartmentsReports\DepartmentsReportsRepository;
use App\Domain\Employee\EmployeeNotFoundException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class DepartmentsReportsController extends BaseController
{
    private $repo;

    public function __construct(DepartmentsReportsRepository $repo)
    {
        $this->repo = $repo;
    }

    /**
     * Lists all the employees.
     *
     * @param Request $request
     * @param Response $response
     * @param array $args Route's arguments
     *
     * @return Response
     */
    public function getHighestSalaries(Request $request, Response $response, $args): Response
    {
        $result = $this->repo->getDepartmentsWithHighSalaries();
        return $this->jsonResponse($response, $result);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args Route's arguments
     *
     * @return Response
     *
     * @throws EmployeeNotFoundException If there's no match in the db for employee with the ID specified
     */
    public function getExpensiveDepartments(Request $request, Response $response, $args): Response
    {
        $result = $this->repo->getDepartmentsWithWealthyEmployees();
        return $this->jsonResponse($response, $result);
    }
}