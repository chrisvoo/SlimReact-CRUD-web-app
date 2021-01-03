<?php

namespace App\Application\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use App\Domain\Employee\EmployeeRepository;
use App\Domain\Employee\Employee;
use App\Domain\DomainException\InvalidEntityException;
use App\Domain\Employee\EmployeeNotFoundException;

class EmployeeController extends BaseController
{
    private $employeeRepository;

    public function __construct(EmployeeRepository $employeeRepository)
    {
        $this->employeeRepository = $employeeRepository;
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
    public function list(Request $request, Response $response, $args): Response
    {
        $employees = $this->employeeRepository->findAll();
        return $this->jsonResponse($response, $employees);
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
    public function getById(Request $request, Response $response, $args): Response
    {
        $employee = $this->employeeRepository->findEmployeeById($args["id"]);
        return $this->jsonResponse($response, $employee);
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
    public function create(Request $request, Response $response, $args): Response
    {
        $body = $request->getParsedBody();

        $employee = $this->employeeRepository->createEmployee(
            new Employee($body)
        );

        return $this->jsonResponse($response, $employee);
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
    public function update(Request $request, Response $response, $args): Response
    {
        $body = $request->getParsedBody();
        if (isset($body["id"]) && $body["id"] !== $args["id"]) {
            throw new InvalidEntityException("Employee's ID mismatches route's id");
        }
        $body["id"] = (int)$args["id"];

        $operation = $this->employeeRepository->updateEmployeeById(
            new Employee($body)
        );

        return $this->jsonResponse($response, $operation);
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
    public function delete(Request $request, Response $response, $args): Response
    {
        $operation = $this->employeeRepository->deleteEmployee($args["id"]);
        return $this->jsonResponse($response, $operation);
    }
}