<?php

namespace App\Infrastructure\Persistence;

use MyCLabs\Enum\Enum;

/**
 * Maps tables' names with constants.
 *
 * @method static TableMapper EMPLOYEE()
 * @method static TableMapper DEPARTMENT()
 */
class TableMapper extends Enum
{
    private const EMPLOYEE = 'employee';
    private const DEPARTMENT = 'department';
}