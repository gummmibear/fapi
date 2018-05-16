<?php

declare(strict_types=1);

namespace App\Mongo\Document;

interface DocumentInterface
{
    public function toArray(): array;
}