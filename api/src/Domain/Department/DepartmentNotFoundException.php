<?php
declare(strict_types=1);

namespace App\Domain\Department;

use App\Domain\DomainException\DomainRecordNotFoundException;

class DepartmentNotFoundException extends DomainRecordNotFoundException
{
    public $message = 'The department you requested does not exist.';
}
