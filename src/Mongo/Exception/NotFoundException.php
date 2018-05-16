<?php

declare(strict_types=1);

namespace App\Mongo\Exception;

class NotFoundException extends \Exception implements MongoExceptionInterface
{
}