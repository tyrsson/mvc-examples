<?php

declare(strict_types=1);

namespace Application\Repository\Exception;

use Laminas\Db\Exception\RuntimeException;

final class RecordNotFound extends RuntimeException
{
}
