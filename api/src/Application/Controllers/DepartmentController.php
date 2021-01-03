<?php

namespace App\Application\Controllers;

use App\Domain\Department\Department;
use App\Domain\Department\DepartmentNotFoundException;
use App\Domain\Department\DepartmentRepository;
use App\Domain\DomainException\InvalidEntityException;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class DepartmentController extends BaseController
{
    private $departmentRepository;

    public function __construct(DepartmentRepository $departmentRepository)
    {
        $this->departmentRepository = $departmentRepository;
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
        $departments = $this->departmentRepository->findAll();
        return $this->jsonResponse($response, $departments);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args Route's arguments
     *
     * @return Response
     *
     * @throws DepartmentNotFoundException If there's no match in the db for employee with the ID specified.
     */
    public function getById(Request $request, Response $response, $args): Response
    {
        $department = $this->departmentRepository->findDepartmentById($args["id"]);
        return $this->jsonResponse($response, $department);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args Route's arguments
     *
     * @return Response
     *
     * @throws DepartmentNotFoundException If there's no match in the db for employee with the ID specified.
     */
    public function create(Request $request, Response $response, $args): Response
    {
        $body = $request->getParsedBody();

        $dep = $this->departmentRepository->createDepartment(
            new Department($body)
        );

        return $this->jsonResponse($response, $dep);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args Route's arguments
     *
     * @return Response
     * @throws InvalidEntityException    If you've passed a department without id.
     * @throws DepartmentNotFoundException If there's no match in the db for employee with the ID specified.
     */
    public function update(Request $request, Response $response, $args): Response
    {
        $body = $request->getParsedBody();
        if (isset($body["id"]) && $body["id"] !== $args["id"]) {
            throw new InvalidEntityException("Department's ID mismatches route's id");
        }
        $body["id"] = (int)$args["id"];

        $operation = $this->departmentRepository->updateDepartmentById(
            new Department($body)
        );

        return $this->jsonResponse($response, $operation);
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args Route's arguments
     *
     * @return Response
     * @throws \App\Infrastructure\Persistence\DatabaseException
     */
    public function delete(Request $request, Response $response, $args): Response
    {
        $operation = $this->departmentRepository->deleteDepartment($args["id"]);
        return $this->jsonResponse($response, $operation);
    }
}