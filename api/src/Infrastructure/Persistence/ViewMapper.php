<?php

namespace App\Infrastructure\Persistence;

use MyCLabs\Enum\Enum;

/**
 * Maps views' names with constants.
 *
 * @method static ViewMapper EXPENSIVE_DEPARTMENTS()
 * @method static ViewMapper HIGHEST_SALARIES()
 */
class ViewMapper extends Enum
{
    private const EXPENSIVE_DEPARTMENTS = 'expensive_departments';
    private const HIGHEST_SALARIES = 'highest_salaries';
}