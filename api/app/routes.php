<?php
declare(strict_types=1);

use App\Application\Controllers\DepartmentController;
use App\Application\Controllers\EmployeeController;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
   $app->get('/departments', DepartmentController::class . ":list");
   $app->get('/employees', EmployeeController::class . ":list");

   $app->group("/employee", function (Group $group) {
       $group->get('/{id}', EmployeeController::class . ":getById");
       $group->post('', EmployeeController::class . ":create");     // new employee
       $group->put('/{id}', EmployeeController::class . ":update"); // update
       $group->delete('/{id}', EmployeeController::class . ":delete");
   });

   $app->group("/department", function (Group $group) {
       $group->get('/{id}', DepartmentController::class . ":getById");
       $group->post('', DepartmentController::class . ":create");     // new employee
       $group->put('/{id}', DepartmentController::class . ":update"); // update
       $group->delete('/{id}', DepartmentController::class . ":delete");
   });
};
