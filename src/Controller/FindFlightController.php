<?php

declare(strict_types=1);

namespace App\Controller;

use App\Mongo\Collection\FlightInfoCollection;
use App\Mongo\Exception\NotFoundException;
use Symfony\Component\HttpFoundation\JsonResponse;

class FindFlightController
{
    private $flightInfoCollection;

    public function __construct(FlightInfoCollection $flightInfoCollection)
    {
        $this->flightInfoCollection = $flightInfoCollection;
    }

    public function find($from, $to):JsonResponse
    {
        try {
            $flightInfoCollection = $this->flightInfoCollection->findOneBy(['from' => $from, 'to' => $to]);

            return new JsonResponse($flightInfoCollection->toArray());
        } catch (NotFoundException $exception) {

            return new JsonResponse([], JsonResponse::HTTP_NOT_FOUND);
        }
    }
}