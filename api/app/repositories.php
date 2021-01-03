<?php
declare(strict_types=1);

use App\Domain\Employee\EmployeeRepository;
use App\Domain\Department\DepartmentRepository;
use App\Infrastructure\Persistence\Department\DepartmentRepositoryDatabase;
use App\Infrastructure\Persistence\Employee\EmployeeRepositoryDatabase;
use DI\ContainerBuilder;

return function (ContainerBuilder $containerBuilder) {
    // Here we map our UserRepository interface to its in memory implementation
    $containerBuilder->addDefinitions([
        EmployeeRepository::class => \DI\autowire(EmployeeRepositoryDatabase::class),
        DepartmentRepository::class => \DI\autowire(DepartmentRepositoryDatabase::class),
    ]);
};
