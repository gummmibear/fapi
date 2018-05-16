<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;

class HealthCheckController
{
    public function check():JsonResponse
    {
        return new JsonResponse(['status'=>'live']);
    }
}